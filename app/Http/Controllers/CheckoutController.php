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
    public function index(Request $request)
    {
        $member = Auth::user();
        $buyNow = session('buy_now');
        
        // 쿼리 파라미터들
        $cartIds = $request->query('cart_ids'); // 장바구니 기반 결제 
        $pIds = $request->query('p', []); 
        $qtys = $request->query('q', []); 
        $cIds = $request->query('c', []); 
        $sIds = $request->query('s', []); 

        $directProductIds = $request->query('direct_product_ids');
        $directProductId = $request->query('direct_product_id');

        $checkoutItems = [];
        $totalProductPrice = 0;

        // 1. 장바구니 기반 결제 처리 (cart.blade.php 대응 )
        if ($cartIds) {
            $ids = explode(',', $cartIds);
            $cartItems = \App\Models\Cart::whereIn('id', $ids)
                ->where('member_id', $member->id)
                ->with('product')
                ->get();

            foreach ($cartItems as $item) {
                $product = $item->product;
                $price = $product->sale_price ?? $product->price;
                $checkoutItems[] = [
                    'product' => $product,
                    'color' => $item->color,
                    'size' => $item->size,
                    'quantity' => $item->quantity,
                    'price' => $price,
                    'total' => $price * $item->quantity
                ];
                $totalProductPrice += ($price * $item->quantity);
            }
        }
        // 2. 복합 배열 파라미터 처리 (멀티 옵션 모달 대응)
        elseif (!empty($pIds)) {
            foreach ($pIds as $index => $id) {
                $product = Product::with(['colors', 'sizes'])->find($id);
                if (!$product) continue;

                $qty = isset($qtys[$index]) ? (int)$qtys[$index] : 1;
                $colorId = $cIds[$index] ?? null;
                $sizeId = $sIds[$index] ?? null;

                // 옵션 검증 (서버 측 방어)
                if (($product->colors->count() > 0 && !$colorId) || ($product->sizes->count() > 0 && !$sizeId)) {
                    return redirect()->back()->with('error', "'{$product->name}'의 옵션이 선택되지 않았습니다.");
                }

                $price = $product->sale_price ?? $product->price;
                $checkoutItems[] = [
                    'product' => $product,
                    'color' => $colorId ? \App\Models\Color::find($colorId)?->name : null,
                    'size' => $sizeId ? \App\Models\Size::find($sizeId)?->name : null,
                    'quantity' => $qty,
                    'price' => $price,
                    'total' => $price * $qty
                ];
                $totalProductPrice += ($price * $qty);
            }
        }
        // 2. 세션에 저장된 바로구매 정보 처리 (기존 방식)
        elseif ($buyNow) {
            $product = Product::find($buyNow['product_id']);
            if ($product) {
                $price = $product->sale_price ?? $product->price;
                $checkoutItems[] = [
                    'product' => $product,
                    'color' => $buyNow['color'] ?? null,
                    'size' => $buyNow['size'] ?? null,
                    'quantity' => $buyNow['quantity'],
                    'price' => $price,
                    'total' => $price * $buyNow['quantity']
                ];
                $totalProductPrice += $price * $buyNow['quantity'];
            }
        } 
        // 3. 기존 쉼표 구분자 방식 처리
        elseif ($directProductIds) {
            $ids = explode(',', $directProductIds);
            $products = Product::whereIn('id', $ids)->with(['colors', 'sizes'])->get();
            
            foreach ($products as $product) {
                if ($product->colors->count() > 0 || $product->sizes->count() > 0) {
                    return redirect()->back()->with('error', "'{$product->name}' 상품은 옵션 선택이 필수입니다.");
                }

                $price = $product->sale_price ?? $product->price;
                $checkoutItems[] = [
                    'product' => $product,
                    'color' => null,
                    'size' => null,
                    'quantity' => 1,
                    'price' => $price,
                    'total' => $price
                ];
                $totalProductPrice += $price;
            }
        }
        // 4. 단수 상품 처리
        elseif ($directProductId) {
            $product = Product::find($directProductId);
            if ($product) {
                $qty = $request->query('quantity', 1);
                $price = $product->sale_price ?? $product->price;
                $checkoutItems[] = [
                    'product' => $product,
                    'color' => $request->query('color_id'),
                    'size' => $request->query('size_id'),
                    'quantity' => $qty,
                    'price' => $price,
                    'total' => $price * $qty
                ];
                $totalProductPrice += $price * $qty;
            }
        }

        if (empty($checkoutItems)) {
            // 살 상품이 없으면 홈으로 보냄 (원인 파악 완료)
            return redirect()->route('home');
        }

        // 배송비 계산 로직 (첫 번째 상품 기준으로 타입 결정하거나 전체 금액 기준)
        $shippingFee = 0;
        $firstItem = $checkoutItems[0]['product'];
        if ($firstItem->shipping_type === '무료') {
            $shippingFee = 0;
        } elseif ($firstItem->shipping_type === '고정') {
            $shippingFee = $firstItem->shipping_fee ?? 0;
        } else {
            // 5만원 이상 무료배송 정책 적용
            $shippingFee = $totalProductPrice >= 50000 ? 0 : 3000;
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
