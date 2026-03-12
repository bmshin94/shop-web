<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 바로 구매하기 (세션에 구매 정보 저장 후 결제 페이지로 이동)
     */
    public function buyNow(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'color' => 'nullable|string',
            'size' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        // 바로구매 정보를 세션에 저장
        session()->put('buy_now', [
            'product_id' => $product->id,
            'color' => $request->color,
            'size' => $request->size,
            'quantity' => $request->quantity,
        ]);

        return response()->json([
            'success' => true,
            'redirect' => route('checkout')
        ]);
    }

    /**
     * 결제 페이지 표시
     */
    public function index()
    {
        $member = Auth::user();
        $buyNow = session('buy_now');

        $checkoutItems = [];
        $totalProductPrice = 0;

        if ($buyNow) {
            $product = Product::find($buyNow['product_id']);
            if ($product) {
                $price = $product->sale_price ?? $product->price;
                $checkoutItems[] = [
                    'product' => $product,
                    'color' => $buyNow['color'],
                    'size' => $buyNow['size'],
                    'quantity' => $buyNow['quantity'],
                    'price' => $price,
                    'total' => $price * $buyNow['quantity']
                ];
                $totalProductPrice = $price * $buyNow['quantity'];
            }
        } else {
            // 장바구니에서 온 경우 (추후 구현)
            // return redirect()->route('cart.index')->with('error', '결제할 상품이 없습니다.');
        }

        if (empty($checkoutItems)) {
            return redirect()->route('home');
        }

        // 배송비 계산 (Product 모델의 로직 활용 가능하면 좋음)
        // 일단은 기본 정책 적용 (5만원 이상 무료, 미만 3000원)
        // 실제로는 개별 상품의 shipping_type을 따져야 하지만, 바로구매는 단일 상품이므로 간단함.
        $shippingFee = 0;
        if ($buyNow) {
            $product = $checkoutItems[0]['product'];
            if ($product->shipping_type === '무료') {
                $shippingFee = 0;
            } elseif ($product->shipping_type === '고정') {
                $shippingFee = $product->shipping_fee ?? 0;
            } else {
                $shippingFee = $totalProductPrice >= 50000 ? 0 : 3000;
            }
        }

        $finalTotal = $totalProductPrice + $shippingFee;

        return view('pages.checkout', compact('member', 'checkoutItems', 'totalProductPrice', 'shippingFee', 'finalTotal'));
    }

    /**
     * 결제 처리 (주문 생성)
     */
    public function store(\App\Http\Requests\CheckoutRequest $request, \App\Services\CheckoutService $checkoutService)
    {
        try {
            $member = Auth::user();
            $order = $checkoutService->processCheckout($member, $request->validated());

            return response()->json([
                'success' => true,
                'redirect' => route('mypage.order-list'),
                'message' => '주문이 성공적으로 완료되었습니다.'
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('결제 실패: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * 포트원(Iamport) 결제 사후 검증 및 주문 생성
     */
    public function verifyPayment(Request $request, \App\Services\CheckoutService $checkoutService)
    {
        $request->validate([
            'imp_uid' => 'required|string',
            'merchant_uid' => 'required|string',
            // 폼 데이터들도 함께 전송받음
            'recipient_name' => 'required|string|max:50',
            'recipient_phone' => 'required|string|max:20',
            'recipient_zipcode' => 'required|string|max:10',
            'recipient_address' => 'required|string|max:255',
            'recipient_detail_address' => 'nullable|string|max:255',
            'shipping_message' => 'nullable|string|max:500',
            'payment_method' => 'required|string',
            'applied_points' => 'nullable|integer|min:0',
        ]);

        try {
            $member = Auth::user();
            
            // 서비스 단에서 아임포트 토큰 발급 -> 결제 금액 조회 -> 금액 비교 후 주문 생성까지 한 번에 처리
            $order = $checkoutService->verifyAndProcessCheckout($member, $request->all());

            return response()->json([
                'success' => true,
                'redirect' => route('mypage.order-list'),
                'message' => '결제가 성공적으로 검증되고 완료되었습니다.'
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('결제 검증 실패: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
