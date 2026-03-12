<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Order;
use App\Models\Review;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class MemberService
{
    /**
     * 마이페이지 대시보드 데이터 조회
     * 
     * @param Member $member
     * @return array
     */
    public function getDashboardData(Member $member): array
    {
        // 1. 주문 현황 요약 (취소 제외)
        $orderStats = [
            'received' => $member->orders()->where('order_status', '주문접수')->count(),
            'preparing' => $member->orders()->where('order_status', '상품준비중')->count(),
            'shipping' => $member->orders()->where('order_status', '배송중')->count(),
            'delivered' => $member->orders()->where('order_status', '배송완료')->count(),
            'confirmed' => $member->orders()->where('order_status', '구매확정')->count(),
        ];

        // 2. 최근 주문 목록 (최근 5건)
        $recentOrders = $member->orders()
            ->with('items.product')
            ->latest()
            ->take(5)
            ->get();

        // 3. 총 주문 횟수
        $totalOrderCount = $member->orders()->count();

        return [
            'member' => $member,
            'orderStats' => $orderStats,
            'recentOrders' => $recentOrders,
            'totalOrderCount' => $totalOrderCount,
            'couponCount' => 0, // TODO: 쿠폰 시스템 연동 예정
        ];
    }

    /**
     * 주문 목록 조회 및 필터링
     * 
     * @param Member $member
     * @param Request $request
     * @return array
     */
    public function getOrderListData(Member $member, Request $request): array
    {
        $query = $member->orders()->with('items.product')->latest();

        // 0. 키워드 검색 (상품명 또는 주문번호)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('items', function($sq) use ($search) {
                      $sq->where('product_name', 'like', "%{$search}%");
                  });
            });
        }

        // 1. 상태 필터
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        // 2. 기간 필터 및 직접 날짜 검색
        $months = $request->get('months');
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $query->whereBetween('ordered_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59'
            ]);
            // months가 없으면 1이 아닌 빈 값을 유지 (수동 입력 감지용)
        } else {
            $months = $months ?: 1; // 버튼 클릭이 없거나 첫 진입이면 1개월 기본
            $startDate = now()->subMonths($months)->format('Y-m-d');
            $endDate = now()->format('Y-m-d');
            $query->where('ordered_at', '>=', $startDate . ' 00:00:00');
        }

        $orders = $query->paginate(10)->withQueryString();

        return [
            'member' => $member,
            'orders' => $orders,
            'months' => $months,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'search' => $request->get('search'),
        ];
    }

    /**
     * 리뷰 관리 페이지 데이터 조회
     * 
     * @param Member $member
     * @return array
     */
    public function getReviewListData(Member $member): array
    {
        // 1. 작성 가능한 리뷰
        $purchasedProductIds = OrderItem::whereHas('order', function($q) use ($member) {
                $q->where('member_id', $member->id)->where('order_status', '배송완료');
            })
            ->pluck('product_id')
            ->unique();

        $reviewedProductIds = Review::where('member_id', $member->id)->pluck('product_id');
        
        $availableProductIds = $purchasedProductIds->diff($reviewedProductIds);
        $availableReviews = Product::whereIn('id', $availableProductIds)->get();

        // 2. 내가 작성한 리뷰
        $writtenReviews = Review::with('product')
            ->where('member_id', $member->id)
            ->latest()
            ->get();

        return [
            'member' => $member,
            'availableReviews' => $availableReviews,
            'writtenReviews' => $writtenReviews,
        ];
    }

    /**
     * 취소/반품/교환 내역 조회 및 필터링
     */
    public function getCancelListData(Member $member, Request $request): array
    {
        $query = $member->orders()
            ->with(['items.product'])
            ->where(function($q) {
                $q->whereIn('order_status', ['취소완료'])
                  ->orWhereIn('payment_status', ['환불완료', '취소완료']);
            })
            ->latest();

        // 0. 키워드 검색
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('items', function($sq) use ($search) {
                      $sq->where('product_name', 'like', "%{$search}%");
                  });
            });
        }

        // 1. 상태 필터
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        // 2. 기간 필터 및 직접 날짜 검색
        $months = $request->get('months');
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $query->whereBetween('ordered_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59'
            ]);
            // months가 없으면 1이 아닌 빈 값을 유지 (수동 입력 감지용)
        } else {
            $months = $months ?: 1; // 버튼 클릭이 없거나 첫 진입이면 1개월 기본
            $startDate = now()->subMonths($months)->format('Y-m-d');
            $endDate = now()->format('Y-m-d');
            $query->where('ordered_at', '>=', $startDate . ' 00:00:00');
        }

        $cancels = $query->paginate(10)->withQueryString();

        return [
            'member' => $member,
            'cancels' => $cancels,
            'months' => $months,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'search' => $request->get('search'),
        ];
    }

    /**
     * 환불/입금 내역 조회 및 필터링
     */
    public function getRefundListData(Member $member, Request $request): array
    {
        $query = $member->orders()
            ->with(['items.product'])
            ->whereIn('payment_status', ['환불완료', '취소완료'])
            ->latest();

        // 0. 키워드 검색
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('items', function($sq) use ($search) {
                      $sq->where('product_name', 'like', "%{$search}%");
                  });
            });
        }

        // 1. 상태 필터
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        // 2. 기간 필터 및 직접 날짜 검색
        $months = $request->get('months');
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $query->whereBetween('ordered_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59'
            ]);
        } else {
            $months = $months ?: 1;
            $startDate = now()->subMonths($months)->format('Y-m-d');
            $endDate = now()->format('Y-m-d');
            $query->where('ordered_at', '>=', $startDate . ' 00:00:00');
        }

        $refunds = $query->paginate(10)->withQueryString();

        return [
            'member' => $member,
            'refunds' => $refunds,
            'months' => $months,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'search' => $request->get('search'),
        ];
    }

    /**
     * 영수증/계산서 발급 내역 조회 및 필터링
     */
    public function getReceiptListData(Member $member, Request $request): array
    {
        $query = $member->orders()
            ->with(['items.product'])
            ->whereIn('payment_status', ['결제완료', '배송중', '배송완료', '구매확정'])
            ->latest();

        // 0. 키워드 검색
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('items', function($sq) use ($search) {
                      $sq->where('product_name', 'like', "%{$search}%");
                  });
            });
        }

        // 1. 상태 필터 (증빙 종류 등)
        if ($request->filled('status')) {
            $query->where('payment_method', 'like', "%{$request->status}%");
        }

        // 2. 기간 필터 및 직접 날짜 검색
        $months = $request->get('months');
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $query->whereBetween('ordered_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59'
            ]);
        } else {
            $months = $months ?: 1;
            $startDate = now()->subMonths($months)->format('Y-m-d');
            $endDate = now()->format('Y-m-d');
            $query->where('ordered_at', '>=', $startDate . ' 00:00:00');
        }

        $receipts = $query->paginate(10)->withQueryString();

        // 각 주문에 대해 영수증 링크 생성 (우리의 내부 라우트로 매핑하여 동적으로 URL 조회 후 리다이렉트) ✨
        $receipts->getCollection()->transform(function($order) {
            if ($order->imp_uid) {
                $order->receipt_url = route('mypage.order-receipt', ['order_number' => $order->order_number]);
            }
            return $order;
        });

        return [
            'member' => $member,
            'receipts' => $receipts,
            'months' => $months,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'search' => $request->get('search'),
        ];
    }

    /**
     * 보유 쿠폰 목록 조회 및 필터링
     */
    public function getCouponListData(Member $member, Request $request): array
    {
        $query = $member->activeCoupons();

        // 1. 키워드 검색 (쿠폰명 또는 설명)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 2. 유형 필터 (discount, shipping)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $coupons = $query->get()->map(function($coupon) {
            return (object)[
                'id' => $coupon->id,
                'type' => $coupon->type === 'shipping' ? '배송비 쿠폰' : '할인 쿠폰',
                'raw_type' => $coupon->type,
                'name' => $coupon->name,
                'description' => $coupon->description,
                'expired_at' => $coupon->ends_at ? $coupon->ends_at->format('Y.m.d') : '무제한',
                'is_active' => true
            ];
        });

        return [
            'member' => $member,
            'coupons' => $coupons,
            'couponCount' => $coupons->count(),
            'search' => $request->get('search'),
            'type' => $request->get('type'),
        ];
    }

    /**
     * 쿠폰 코드를 통한 쿠폰 등록
     */
    public function registerCoupon(Member $member, string $code): Coupon
    {
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            throw new \Exception('유효하지 않은 쿠폰 코드입니다.');
        }

        if (!$coupon->is_active || ($coupon->ends_at && $coupon->ends_at->isPast())) {
            throw new \Exception('사용 기간이 만료되었거나 비활성화된 쿠폰입니다.');
        }

        if ($member->coupons()->where('coupon_id', $coupon->id)->exists()) {
            throw new \Exception('이미 등록된 쿠폰입니다.');
        }

        $member->coupons()->attach($coupon->id, ['assigned_at' => now()]);

        return $coupon;
    }
}
