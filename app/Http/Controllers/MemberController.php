<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class MemberController extends Controller
{
    /**
     * 마이페이지 메인 (대시보드)
     */
    public function index()
    {
        $member = Auth::user();
        
        // 1. 주문 현황 요약 (취소 제외)
        $orderStats = [
            'pending' => $member->orders()->where('payment_status', '결제대기')->count(),
            'preparing' => $member->orders()->where('order_status', '상품준비중')->count(),
            'shipping' => $member->orders()->where('order_status', '배송중')->count(),
            'delivered' => $member->orders()->where('order_status', '배송완료')->count(),
        ];

        // 2. 최근 주문 목록 (최근 5건)
        $recentOrders = $member->orders()
            ->with('items.product')
            ->latest()
            ->take(5)
            ->get();

        // 3. 총 주문 횟수
        $totalOrderCount = $member->orders()->count();

        // 4. 쿠폰 개수 (아직 쿠폰 시스템이 없으므로 가상 데이터)
        $couponCount = 0;

        return view('pages.mypage', compact(
            'member', 
            'orderStats', 
            'recentOrders', 
            'totalOrderCount', 
            'couponCount'
        ));
    }

    /**
     * 주문/배송 조회 목록
     */
    public function orderList(Request $request)
    {
        $member = Auth::user();
        $query = $member->orders()->with('items.product')->latest();

        // 1. 상태 필터 (메인 대시보드에서 넘어온 경우)
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        // 2. 기간 필터 (기본 1개월)
        $months = $request->get('months', 1);
        $query->where('ordered_at', '>=', now()->subMonths($months));

        $orders = $query->paginate(10)->withQueryString();

        return view('pages.mypage-order-list', compact('member', 'orders', 'months'));
    }
}
