<?php

namespace App\Http\Controllers;

use App\Services\MemberService;
use App\Services\CheckoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    protected $memberService;
    protected $checkoutService;

    /**
     * MemberController 생성자
     */
    public function __construct(MemberService $memberService, CheckoutService $checkoutService)
    {
        $this->memberService = $memberService;
        $this->checkoutService = $checkoutService;
    }

    /**
     * 마이페이지 메인 (대시보드)
     */
    public function index()
    {
        $member = Auth::user();
        $data = $this->memberService->getDashboardData($member);
        return view('pages.mypage', $data);
    }

    /**
     * 주문/배송 조회 목록
     */
    public function orderList(Request $request)
    {
        $member = Auth::user();
        $data = $this->memberService->getOrderListData($member, $request);
        return view('pages.mypage-order-list', $data);
    }

    /**
     * 취소/반품/교환 내역 조회
     */
    public function cancelList(Request $request)
    {
        $member = Auth::user();
        $data = $this->memberService->getCancelListData($member, $request);

        return view('pages.mypage-cancel-list', $data);
    }

    /**
     * 환불/입금 내역 조회
     */
    public function refundList(Request $request)
    {
        $member = Auth::user();
        $data = $this->memberService->getRefundListData($member, $request);

        return view('pages.mypage-refund-list', $data);
    }

    /**
     * 보유 쿠폰 조회
     */
    public function couponList(Request $request)
    {
        $member = Auth::user();
        $data = $this->memberService->getCouponListData($member, $request);

        return view('pages.mypage-coupon', $data);
    }

    /**
     * 쿠폰 등록 처리 (AJAX)
     */
    public function registerCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50'
        ]);

        try {
            $member = Auth::user();
            $this->memberService->registerCoupon($member, $request->code);
            
            return response()->json([
                'message' => '쿠폰이 성공적으로 등록되었습니다.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * 영수증/계산서 발급 조회
     */
    public function receiptList(Request $request)
    {
        $member = Auth::user();
        $data = $this->memberService->getReceiptListData($member, $request);

        return view('pages.mypage-receipt', $data);
    }

    /**
     * 상품 리뷰 관리 페이지
     */
    public function reviewList()
    {
        $member = Auth::user();
        $data = $this->memberService->getReviewListData($member);
        return view('pages.mypage-review', $data);
    }

    /**
     * 주문 상세 조회
     */
    public function orderDetail($orderNumber)
    {
        $member = Auth::user();
        $order = $member->orders()
            ->with(['items.product'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('pages.mypage-order-detail', compact('member', 'order'));
    }

    /**
     * 주문 취소 처리
     */
    public function cancelOrder(Request $request, $orderNumber)
    {
        $member = Auth::user();
        $order = $member->orders()->where('order_number', $orderNumber)->firstOrFail();

        if (!in_array($order->order_status, ['주문접수', '상품준비중'])) {
            return response()->json(['message' => '이미 배송이 시작되어 취소할 수 없습니다.'], 422);
        }

        try {
            $this->checkoutService->cancelOrder($order, $request->input('reason', '사용자 직접 취소'));
            return response()->json(['message' => '주문이 정상적으로 취소되었습니다.']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
