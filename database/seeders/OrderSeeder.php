<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * 관리자 주문/배송 관리 화면용 샘플 주문을 생성한다.
     */
    public function run(): void
    {
        $products = Product::query()->inRandomOrder()->get();

        $templates = [
            [
                'order_number' => 'HF202603070001',
                'customer_name' => '김하늘',
                'customer_email' => 'customer1@example.com',
                'customer_phone' => '010-1234-5678',
                'recipient_name' => '김하늘',
                'recipient_phone' => '010-1234-5678',
                'postal_code' => '06236',
                'address_line1' => '서울 강남구 테헤란로 123',
                'address_line2' => '8층',
                'shipping_message' => '문 앞에 놓아주세요.',
                'payment_method' => '신용카드',
                'payment_status' => '결제완료',
                'order_status' => '주문접수',
                'shipping_status' => '배송대기',
                'courier' => null,
                'tracking_number' => null,
                'admin_memo' => '결제 승인 완료',
                'ordered_at' => now()->subHours(3),
                'shipped_at' => null,
                'delivered_at' => null,
            ],
            [
                'order_number' => 'HF202603060014',
                'customer_name' => '박서연',
                'customer_email' => 'customer2@example.com',
                'customer_phone' => '010-3456-7890',
                'recipient_name' => '박서연',
                'recipient_phone' => '010-3456-7890',
                'postal_code' => '04524',
                'address_line1' => '서울 중구 세종대로 110',
                'address_line2' => '1204호',
                'shipping_message' => '배송 전에 연락 부탁드립니다.',
                'payment_method' => '간편결제',
                'payment_status' => '결제완료',
                'order_status' => '배송중',
                'shipping_status' => '배송중',
                'courier' => 'CJ대한통운',
                'tracking_number' => '531245789012',
                'admin_memo' => '출고 완료 후 이동 중',
                'ordered_at' => now()->subDays(1)->subHours(4),
                'shipped_at' => now()->subHours(18),
                'delivered_at' => null,
            ],
            [
                'order_number' => 'HF202603040021',
                'customer_name' => '이민지',
                'customer_email' => 'customer3@example.com',
                'customer_phone' => '010-5678-9012',
                'recipient_name' => '이민지',
                'recipient_phone' => '010-5678-9012',
                'postal_code' => '48058',
                'address_line1' => '부산 해운대구 센텀동로 45',
                'address_line2' => '301호',
                'shipping_message' => null,
                'payment_method' => '신용카드',
                'payment_status' => '결제완료',
                'order_status' => '배송완료',
                'shipping_status' => '배송완료',
                'courier' => '롯데택배',
                'tracking_number' => '248913570011',
                'admin_memo' => '배송 완료 확인',
                'ordered_at' => now()->subDays(3),
                'shipped_at' => now()->subDays(2),
                'delivered_at' => now()->subDays(1)->subHours(6),
            ],
            [
                'order_number' => 'HF202603030009',
                'customer_name' => '정유진',
                'customer_email' => 'customer4@example.com',
                'customer_phone' => '010-2468-1357',
                'recipient_name' => '정유진',
                'recipient_phone' => '010-2468-1357',
                'postal_code' => '35229',
                'address_line1' => '대전 서구 둔산로 77',
                'address_line2' => '1502호',
                'shipping_message' => '경비실에 맡겨주세요.',
                'payment_method' => '무통장입금',
                'payment_status' => '환불완료',
                'order_status' => '취소완료',
                'shipping_status' => '배송대기',
                'courier' => null,
                'tracking_number' => null,
                'admin_memo' => '품절로 주문 취소 처리',
                'ordered_at' => now()->subDays(4)->subHours(5),
                'shipped_at' => null,
                'delivered_at' => null,
            ],
        ];

        foreach ($templates as $index => $template) {
            $order = Order::withTrashed()->firstOrNew([
                'order_number' => $template['order_number'],
            ]);

            $order->fill($this->buildOrderPayload($template, $products, $index));
            $order->save();

            if ($order->trashed()) {
                $order->restore();
            }

            $order->items()->delete();

            foreach ($this->buildOrderItems($products, $index) as $item) {
                $order->items()->create($item);
            }

            $order->update($this->calculateOrderAmounts($order));
        }
    }

    /**
     * 주문 기본 정보를 만든다.
     *
     * @param  array<string, mixed>  $template
     * @param  \Illuminate\Support\Collection<int, \App\Models\Product>  $products
     * @return array<string, mixed>
     */
    private function buildOrderPayload(array $template, $products, int $index): array
    {
        $itemAmounts = $this->calculateItemAmounts($products, $index);

        return array_merge($template, $itemAmounts);
    }

    /**
     * 주문별 상품 라인업을 생성한다.
     *
     * @param  \Illuminate\Support\Collection<int, \App\Models\Product>  $products
     * @return array<int, array<string, mixed>>
     */
    private function buildOrderItems($products, int $index): array
    {
        $fallbackItems = [
            ['product_name' => '에어리 메쉬 브라탑', 'unit_price' => 39000, 'quantity' => 1],
            ['product_name' => '하이웨이스트 시그니처 레깅스', 'unit_price' => 49000, 'quantity' => 1],
            ['product_name' => '라이트 윈드브레이커', 'unit_price' => 98000, 'quantity' => 1],
            ['product_name' => '프리미엄 TPE 요가매트', 'unit_price' => 35000, 'quantity' => 2],
            ['product_name' => '비건 프로틴 파우더 1kg', 'unit_price' => 49000, 'quantity' => 1],
        ];

        $items = [];

        for ($offset = 0; $offset < 2; $offset++) {
            $product = $products->get(($index + $offset) % max($products->count(), 1));
            $fallbackItem = $fallbackItems[($index + $offset) % count($fallbackItems)];
            $quantity = $offset === 0 ? 1 : 2;

            $unitPrice = $product ? ($product->sale_price ?: $product->price) : $fallbackItem['unit_price'];

            $items[] = [
                'product_id' => $product?->id,
                'product_name' => $product?->name ?? $fallbackItem['product_name'],
                'option_summary' => $offset === 0 ? '색상: 블랙 / 사이즈: M' : '색상: 아이보리 / 사이즈: FREE',
                'unit_price' => $unitPrice,
                'quantity' => $quantity,
                'line_total' => $unitPrice * $quantity,
            ];
        }

        return $items;
    }

    /**
     * 주문 금액을 계산한다.
     *
     * @param  \Illuminate\Support\Collection<int, \App\Models\Product>  $products
     * @return array<string, int>
     */
    private function calculateItemAmounts($products, int $index): array
    {
        $subtotalAmount = collect($this->buildOrderItems($products, $index))->sum('line_total');
        $shippingAmount = $subtotalAmount >= 50000 ? 0 : 3000;
        $discountAmount = $index === 0 ? 3000 : 0;

        return [
            'subtotal_amount' => $subtotalAmount,
            'shipping_amount' => $shippingAmount,
            'discount_amount' => $discountAmount,
            'total_amount' => $subtotalAmount + $shippingAmount - $discountAmount,
        ];
    }

    /**
     * 저장된 주문 기준으로 금액을 재계산한다.
     *
     * @return array<string, int>
     */
    private function calculateOrderAmounts(Order $order): array
    {
        $subtotalAmount = (int) $order->items()->sum('line_total');
        $shippingAmount = $subtotalAmount >= 50000 ? 0 : 3000;
        $discountAmount = (int) $order->discount_amount;

        return [
            'subtotal_amount' => $subtotalAmount,
            'shipping_amount' => $shippingAmount,
            'total_amount' => $subtotalAmount + $shippingAmount - $discountAmount,
        ];
    }
}
