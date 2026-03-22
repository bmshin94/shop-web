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
     * 결제 페이지 표시 (체크아웃 데이터 수집)
     * 
     * @param Request $request 결제 대상 식별 파라미터들
     * @param \App\Services\CheckoutService $checkoutService 결제 로직 서비스
     * @return \Illuminate\View\View |\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request, \App\Services\CheckoutService $checkoutService)
    {
        // 1. 서비스에 결제 대상 아이템 수집 및 금액 계산 위임 (Skinny Controller)
        $result = $checkoutService->prepareCheckoutData(Auth::user(), $request);

        // 2. 결제 대상이 없는 경우 홈으로 리다이렉트 (Early Return)
        if (!$result['success']) {
            return redirect()->route('home')->with('error', $result['message'] ?? null);
        }

        // 3. 회원의 배송지 목록 가져오기 (최신순, 기본 배송지 우선)
        $addresses = Auth::user()->shippingAddresses()
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $defaultAddress = $addresses->where('is_default', true)->first();

        // 4. 뷰 데이터 추출 및 렌더링
        return view('pages.checkout', [
            'member' => Auth::user(),
            'checkoutItems' => $result['items'],
            'totalProductPrice' => $result['totalProductPrice'],
            'shippingFee' => $result['shippingFee'],
            'finalTotal' => $result['finalTotal'],
            'addresses' => $addresses,
            'defaultAddress' => $defaultAddress,
        ]);
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
