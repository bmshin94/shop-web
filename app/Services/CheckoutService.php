<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutService
{
    /**
     * 포트원(Iamport) 금액 검증 및 결제 처리
     */
    public function verifyAndProcessCheckout($member, array $data)
    {
        $imp_uid = $data['imp_uid'];
        $merchant_uid = $data['merchant_uid'];

        // WARNING: 로컬 개발 환경에서는 포트원 테스트 모드의 한계(결제 기록 미생성)로 인해
        // API 사후검증을 건너뛰고 프론트엔드 결제 성공 응답만으로 주문을 생성합니다.
        // 운영 환경(production)에서는 반드시 아래 검증 로직이 실행됩니다.
        $isLocalEnv = app()->environment('local');

        $buyNow = session('buy_now');
        if (!$buyNow) {
            throw new \Exception('결제할 상품 정보가 없습니다.');
        }

        $product = Product::findOrFail($buyNow['product_id']);
        $price = $product->sale_price ?? $product->price;
        $totalProductPrice = $price * $buyNow['quantity'];
        $shippingFee = $this->calculateShippingFee($product, $totalProductPrice);

        $appliedPoints = $data['applied_points'] ?? 0;
        if ($appliedPoints > ($totalProductPrice + $shippingFee)) {
            $appliedPoints = $totalProductPrice + $shippingFee;
        }

        $amountToBePaid = max(0, $totalProductPrice + $shippingFee - $appliedPoints);

        if (!$isLocalEnv) {
            // --- 운영 환경: 포트원 API 사후검증 수행 ---
            // 1. 아임포트 액세스 토큰 발급
            $token = $this->getIamportToken();

            // 2. imp_uid로 아임포트 서버에서 결제 정보 조회
            $paymentData = $this->getIamportPaymentData($imp_uid, $token);

            // 3. 금액 비교 (위변조 방지)
            if ((int)$paymentData['amount'] !== (int)$amountToBePaid) {
                $this->cancelIamportPayment($imp_uid, $token, '결제 금액 위변조가 의심됩니다.');
                throw new \Exception('결제된 금액이 요청한 금액과 다릅니다. 결제가 자동 취소되었습니다.');
            }
        } else {
            Log::info('[LOCAL] 포트원 API 검증 스킵 - imp_uid: ' . $imp_uid . ' / 서버 계산 금액: ' . $amountToBePaid);
        }

        // 주문 생성 트랜잭션 진행
        $data['calculated_total'] = $amountToBePaid;
        $data['calculated_shipping'] = $shippingFee;
        $data['calculated_product_total'] = $totalProductPrice;

        return $this->processCheckoutWithPG($member, $data, $product, $buyNow, $imp_uid, $merchant_uid);
    }

    /**
     * 주문 취소 및 환불 처리 (Portone 연동)
     */
    public function cancelOrder(Order $order, string $reason = '사용자 요청 취소')
    {
        return DB::transaction(function () use ($order, $reason) {
            $isLocalEnv = app()->environment('local');

            if (!$isLocalEnv && $order->imp_uid) {
                // 1. 포트원 토큰 발급
                $token = $this->getIamportToken();

                // 2. 포트원 환불 요청
                $response = \Illuminate\Support\Facades\Http::withToken($token)->post('https://api.iamport.kr/payments/cancel', [
                    'imp_uid' => $order->imp_uid,
                    'reason' => $reason,
                    'amount' => $order->total_amount, // 전액 환불
                ]);

                if ($response->failed() || $response->json('code') != 0) {
                    Log::error('포트원 환불 실패 - 주문번호: ' . $order->order_number . ' / 응답: ' . $response->body());
                    throw new \Exception('결제 취소 처리에 실패했습니다. 고객센터로 문의해주세요.');
                }
            } else {
                Log::info('[LOCAL/NO_UID] 포트원 환불 스킵 - 주문번호: ' . $order->order_number);
            }

            // 3. DB 상태 업데이트
            $order->update([
                'order_status' => '취소완료',
                'payment_status' => '취소완료',
                'admin_memo' => $order->admin_memo . "\n[" . now()->format('Y-m-d H:i:s') . "] 주문 취소 처리됨 (사유: " . $reason . ")"
            ]);

            // 4. 사용된 적립금 복구
            if ($order->discount_amount > 0) {
                $order->member->increment('points', $order->discount_amount);
            }

            // 5. 지급된 적립금 회수 (이미 배송 전이면 회수 가능)
            $rewardPoints = floor($order->total_amount * 0.01);
            if ($rewardPoints > 0 && $order->member->points >= $rewardPoints) {
                $order->member->decrement('points', $rewardPoints);
            }

            return $order;
        });
    }

    /**
     * 영수증 URL 조회 (포트원 API 연동)
     * 
     * @param string $impUid
     * @return string|null
     */
    public function getReceiptUrl(string $impUid): ?string
    {
        try {
            $token = $this->getIamportToken();
            $paymentData = $this->getIamportPaymentData($impUid, $token);
            
            return $paymentData['receipt_url'] ?? null;
        } catch (\Exception $e) {
            Log::error('포트원 영수증 URL 조회 실패 - imp_uid: ' . $impUid . ' / 사유: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * 아임포트 토큰 발급 API
     */
    private function getIamportToken()
    {
        $response = \Illuminate\Support\Facades\Http::post('https://api.iamport.kr/users/getToken', [
            'imp_key' => env('IAMPORT_REST_API_KEY'),
            'imp_secret' => env('IAMPORT_REST_API_SECRET')
        ]);

        // NOTE: 포트원 API는 code를 int(0)으로 반환하지만 느슨 비교로 안전하게 처리
        if ($response->failed() || $response->json('code') != 0) {
            Log::error('포트원 토큰 발급 실패 - 응답: ' . $response->body());
            throw new \Exception('포트원 토큰 발급에 실패했습니다.');
        }

        return $response->json('response.access_token');
    }

    /**
     * 아임포트 결제 정보 조회 API
     */
    private function getIamportPaymentData($imp_uid, $token)
    {
        $response = \Illuminate\Support\Facades\Http::withToken($token)
            ->get("https://api.iamport.kr/payments/{$imp_uid}");

        // NOTE: 포트원 API는 code를 int(0)으로 반환하지만 느슨 비교로 안전하게 처리
        if ($response->failed() || $response->json('code') != 0) {
            Log::error('포트원 결제내역 조회 실패 - imp_uid: ' . $imp_uid . ' / 응답: ' . $response->body());
            throw new \Exception('포트원 결제내역에서 조회 실패했습니다. (imp_uid: ' . $imp_uid . ')');
        }

        return $response->json('response');
    }

    /**
     * 아임포트 결제 취소 API (망취소용)
     */
    private function cancelIamportPayment($imp_uid, $token, $reason)
    {
        \Illuminate\Support\Facades\Http::withToken($token)->post('https://api.iamport.kr/payments/cancel', [
            'imp_uid' => $imp_uid,
            'reason' => $reason
        ]);
    }

    /**
     * 실제 DB 저장 트랜잭션 (기존 processCheckout 대체형)
     */
    private function processCheckoutWithPG($member, array $data, $product, $buyNow, $imp_uid, $merchant_uid)
    {
        return DB::transaction(function () use ($member, $data, $product, $buyNow, $imp_uid, $merchant_uid) {
            $totalProductPrice = $data['calculated_product_total'];
            $shippingFee = $data['calculated_shipping'];
            $finalTotal = $data['calculated_total'];
            $appliedPoints = $data['applied_points'] ?? 0;

            // 프론트엔드에서 넘어온 결제수단 매핑
            $paymentMethodMap = [
                'card' => '신용카드',
                'vbank' => '무통장입금',
                'kakaopay' => '간편결제',
                'naverpay' => '간편결제',
            ];
            $paymentMethod = $paymentMethodMap[$data['payment_method']] ?? '신용카드';

            // 주문 생성
            $order = Order::create([
                'member_id' => $member->id,
                'order_number' => $merchant_uid, // PG 생성 시 사용한 고유번호 사용
                'customer_name' => $member->name,
                'customer_email' => $member->email,
                'customer_phone' => $member->phone,
                'recipient_name' => $data['recipient_name'],
                'recipient_phone' => $data['recipient_phone'],
                'postal_code' => $data['recipient_zipcode'],
                'address_line1' => $data['recipient_address'],
                'address_line2' => $data['recipient_detail_address'] ?? null,
                'shipping_message' => $data['shipping_message'] ?? null,
                'payment_method' => $paymentMethod,
                'imp_uid' => $imp_uid,
                'merchant_uid' => $merchant_uid,
                'payment_status' => '결제완료',
                'order_status' => '주문접수',
                'shipping_status' => '배송대기',
                'subtotal_amount' => $totalProductPrice,
                'shipping_amount' => $shippingFee,
                'discount_amount' => $appliedPoints,
                'total_amount' => $finalTotal,
                'ordered_at' => now(),
            ]);

            // 주문 상품 생성
            $optionSummary = [];
            if (!empty($buyNow['color'])) $optionSummary[] = '색상: ' . $buyNow['color'];
            if (!empty($buyNow['size'])) $optionSummary[] = '사이즈: ' . $buyNow['size'];

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'option_summary' => implode(', ', $optionSummary) ?: null,
                'unit_price' => $product->sale_price ?? $product->price,
                'quantity' => $buyNow['quantity'],
                'line_total' => $totalProductPrice,
            ]);

            // 적립금 차감 및 부여 (기존과 동일)
            if ($appliedPoints > 0) {
                $member->decrement('points', $appliedPoints);
            }
            $rewardPoints = floor($finalTotal * 0.01);
            if ($rewardPoints > 0) {
                $member->increment('points', $rewardPoints);
            }

            // 세션 초기화
            session()->forget('buy_now');

            return $order;
        });
    }

    /**
     * (기존 컨트롤러용, 하위호환 유지 또는 삭제 가능) 결제 처리 비즈니스 로직
     */
    public function processCheckout($member, array $validatedData)
    {
        return DB::transaction(function () use ($member, $validatedData) {
            // 1. 세션에서 현재 결제할 상품 정보 가져오기 (바로구매 기준 먼저 구현)
            // TODO: 일반 장바구니 결제 로직 추가 필요
            $buyNow = session('buy_now');
            
            if (!$buyNow) {
                throw new \Exception('결제할 상품 정보가 없습니다.');
            }

            $product = Product::findOrFail($buyNow['product_id']);
            $price = $product->sale_price ?? $product->price;
            $quantity = $buyNow['quantity'];
            $totalProductPrice = $price * $quantity;

            // 2. 배송비 계산
            $shippingFee = $this->calculateShippingFee($product, $totalProductPrice);

            // 3. 적립금 사용 검증
            $appliedPoints = $validatedData['applied_points'] ?? 0;
            if ($appliedPoints > 0) {
                if ($member->points < $appliedPoints) {
                    throw new \Exception('보유 적립금이 부족합니다.');
                }
                // 결제 금액보다 많은 적립금 사용 불가
                if ($appliedPoints > ($totalProductPrice + $shippingFee)) {
                    $appliedPoints = $totalProductPrice + $shippingFee;
                }
            }

            // 4. 최종 결제 금액 계산 (쿠폰 할인은 추후 구현)
            $discountAmount = $appliedPoints; // 현재는 적립금만 할인으로 간주
            $finalTotal = max(0, $totalProductPrice + $shippingFee - $discountAmount);

            // 5. 프론트엔드에서 넘어온 결제수단 매핑
            $paymentMethodMap = [
                'card' => '신용카드',
                'vbank' => '무통장입금',
                'kakaopay' => '간편결제',
                'naverpay' => '간편결제',
            ];
            $paymentMethod = $paymentMethodMap[$validatedData['payment_method']] ?? '신용카드';

            // 6. 주문 생성
            $order = Order::create([
                'member_id' => $member->id,
                'order_number' => $this->generateOrderNumber(),
                'customer_name' => $member->name,
                'customer_email' => $member->email,
                'customer_phone' => $member->phone,
                'recipient_name' => $validatedData['recipient_name'],
                'recipient_phone' => $validatedData['recipient_phone'],
                'postal_code' => $validatedData['recipient_zipcode'],
                'address_line1' => $validatedData['recipient_address'],
                'address_line2' => $validatedData['recipient_detail_address'] ?? null,
                'shipping_message' => $validatedData['shipping_message'] ?? null,
                'payment_method' => $paymentMethod,
                'payment_status' => '결제완료', // 테스트를 위해 즉시 결제 완료 처리
                'order_status' => '주문접수',
                'shipping_status' => '배송대기',
                'subtotal_amount' => $totalProductPrice,
                'shipping_amount' => $shippingFee,
                'discount_amount' => $discountAmount,
                'total_amount' => $finalTotal,
                'ordered_at' => now(),
            ]);

            // 7. 주문 상품 생성
            $optionSummary = [];
            if (!empty($buyNow['color'])) $optionSummary[] = '색상: ' . $buyNow['color'];
            if (!empty($buyNow['size'])) $optionSummary[] = '사이즈: ' . $buyNow['size'];

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'option_summary' => implode(', ', $optionSummary) ?: null,
                'unit_price' => $price,
                'quantity' => $quantity,
                'line_total' => $totalProductPrice,
            ]);

            // 8. 적립금 차감
            if ($appliedPoints > 0) {
                $member->decrement('points', $appliedPoints);
                // TODO: 포인트 사용 내역 로그 기록
            }

            // 9. 구매 적립금 계산 및 추후 지급 예약 (현재는 즉시 지급으로 테스트)
            $rewardPoints = floor($finalTotal * 0.01);
            if ($rewardPoints > 0) {
                $member->increment('points', $rewardPoints);
                // TODO: 포인트 적립 내역 로그 기록
            }

            // 10. 세션 초기화
            session()->forget('buy_now');

            return $order;
        });
    }

    /**
     * 배송비 계산
     */
    private function calculateShippingFee($product, $totalProductPrice)
    {
        if ($product->shipping_type === '무료') {
            return 0;
        } elseif ($product->shipping_type === '고정') {
            return $product->shipping_fee ?? 0;
        } else {
            return $totalProductPrice >= 50000 ? 0 : 3000;
        }
    }

    /**
     * 주문번호 생성 (Ymd-랜덤문자)
     */
    private function generateOrderNumber()
    {
        return date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }
}
