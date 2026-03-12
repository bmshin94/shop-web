<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Order;
use App\Models\Review;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\PointHistory;
use App\Models\Inquiry; // Inquiry 모델 추가! ✨
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class MemberService
{
    /**
     * 찜 목록 데이터 조회
     * 
     * @param Member $member
     * @return array
     */
    public function getWishlistData(Member $member): array
    {
        $wishlist = $member->wishlists()
            ->with(['product.images'])
            ->latest()
            ->paginate(12);

        return [
            'member' => $member,
            'wishlist' => $wishlist,
        ];
    }

    /**
     * 적립금 내역 데이터 조회
     */
    public function getPointListData(Member $member): array
    {
        // 1. 30일 내 소멸 예정 적립금 계산 (단순 합산 로직)
        $expiringPoints = $member->pointHistories()
            ->where('amount', '>', 0) // 적립된 것 중
            ->whereBetween('expired_at', [now(), now()->addDays(30)])
            ->sum('amount');

        // 2. 적립/사용 내역 (최신순 페이징)
        $histories = $member->pointHistories()
            ->latest()
            ->paginate(10);

        return [
            'member' => $member,
            'currentPoints' => $member->points,
            'expiringPoints' => $expiringPoints,
            'histories' => $histories,
        ];
    }

    /**
     * 마이페이지 대시보드 데이터 조회
     */
    public function getDashboardData(Member $member): array
    {
        // ... (기존 로직 유지)
        $orderStats = [
            'received' => $member->orders()->where('order_status', '주문접수')->count(),
            'preparing' => $member->orders()->where('order_status', '상품준비중')->count(),
            'shipping' => $member->orders()->where('order_status', '배송중')->count(),
            'delivered' => $member->orders()->where('order_status', '배송완료')->count(),
            'confirmed' => $member->orders()->where('order_status', '구매확정')->count(),
        ];

        $recentOrders = $member->orders()->with('items.product')->latest()->take(5)->get();
        $totalOrderCount = $member->orders()->count();

        // 사용 가능한 쿠폰 개수 조회 추가!
        $couponCount = $member->coupons()->whereNull('used_at')->count();

        return [
            'member' => $member,
            'orderStats' => $orderStats,
            'recentOrders' => $recentOrders,
            'totalOrderCount' => $totalOrderCount,
            'couponCount' => $couponCount,
        ];
    }

    /**
     * 보유 쿠폰 목록 조회 및 필터링
     */
    public function getCouponListData(Member $member, Request $request): array
    {
        // 모든 쿠폰 내역 조회 (사용 여부 상관없이!)
        $query = $member->coupons();

        // 1. 키워드 검색
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 2. 유형 필터
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $coupons = $query->latest('assigned_at')->get()->map(function($coupon) {
            $isUsed = $coupon->pivot->used_at !== null;
            $isExpired = $coupon->ends_at && $coupon->ends_at->isPast();

            return (object)[
                'id' => $coupon->pivot->id, // 쿠폰 고유 ID가 아닌 '발급 고유 번호'로 교체!
                'name' => $coupon->name,
                'description' => $coupon->description,
                'type' => $coupon->type === 'shipping' ? '배송비 쿠폰' : '할인 쿠폰',
                'raw_type' => $coupon->type,
                'expired_at' => $coupon->ends_at ? $coupon->ends_at->format('Y.m.d') : '제한 없음',
                'used_at' => $coupon->pivot->used_at ? $coupon->pivot->used_at->format('Y.m.d') : null,
                'status_text' => $isUsed ? '사용 완료' : ($isExpired ? '기간 만료' : '사용 가능'),
                'is_active' => !$isUsed && !$isExpired
            ];
        });

        return [
            'member' => $member,
            'coupons' => $coupons,
            'couponCount' => $member->coupons()->whereNull('used_at')->count(),
            'search' => $request->get('search'),
            'type' => $request->get('type'),
        ];
    }

    /**
     * 쿠폰 코드를 통한 쿠폰 등록
     */
    public function registerCoupon(Member $member, string $code): Coupon
    {
        // 대소문자 구분 없이 검색!
        $coupon = Coupon::where('code', strtoupper($code))->first();

        if (!$coupon) {
            throw new \Exception('존재하지 않거나 발급 가능한 코드가 아닙니다.');
        }

        if (!$coupon->is_active) {
            throw new \Exception('현재 사용 중단된 쿠폰입니다.');
        }

        if ($coupon->starts_at && $coupon->starts_at->isFuture()) {
            throw new \Exception('아직 발급 기간이 시작되지 않은 쿠폰입니다.');
        }

        if ($coupon->ends_at && $coupon->ends_at->isPast()) {
            throw new \Exception('발급 기간이 종료된 쿠폰 코드입니다.');
        }

        if ($member->coupons()->where('coupon_id', $coupon->id)->exists()) {
            throw new \Exception('이미 등록된 쿠폰입니다.');
        }

        // 회원에게 쿠폰 할당!
        $member->coupons()->attach($coupon->id, [
            'assigned_at' => now(),
            'used_at' => null
        ]);

        return $coupon;
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
        // 1. 작성 가능한 리뷰 ✨ (page_avail 파라미터 사용)
        $purchasedProductIds = OrderItem::whereHas('order', function($q) use ($member) {
                $q->where('member_id', $member->id)->where('order_status', '배송완료');
            })
            ->pluck('product_id')
            ->unique();

        $reviewedProductIds = Review::where('member_id', $member->id)->pluck('product_id');
        
        $availableProductIds = $purchasedProductIds->diff($reviewedProductIds);
        $availableReviews = Product::whereIn('id', $availableProductIds)
            ->paginate(10, ['*'], 'page_avail');

        // 2. 내가 작성한 리뷰 목록 ✨ (page_written 파라미터 사용)
        $writtenReviews = Review::with('product')
            ->where('member_id', $member->id)
            ->latest()
            ->paginate(10, ['*'], 'page_written');

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
     * 최근 본 상품 데이터 조회 및 날짜별 그룹화 ✨ (비로그인 지원!)
     */
    public function getRecentViewData(?Member $member = null): array
    {
        if ($member) {
            // 1. 로그인 회원: DB 조회 후 그룹화 😊
            $recentViews = $member->recentViews()
                ->with(['product.images', 'product.colors']) // 색상 정보 추가! ✨
                ->orderByDesc('viewed_at')
                ->take(50)
                ->get()
                ->groupBy(function($view) {
                    $date = $view->viewed_at;
                    if ($date->isToday()) return '오늘';
                    if ($date->isYesterday()) return '어제';
                    return $date->format('Y.m.d');
                });
        } else {
            // 2. 비로그인 게스트: 쿠키 조회 후 가공 🍪
            $viewedIds = json_decode(request()->cookie('recent_views', '[]'), true) ?: [];
            
            // 쿠키에 담긴 ID 순서대로 상품 가져오기! ✨
            $products = Product::with(['images', 'colors']) // 여기도 색상 정보 추가! ✨
                ->whereIn('id', $viewedIds)
                ->get()
                ->sortBy(function($p) use ($viewedIds) {
                    return array_search($p->id, $viewedIds);
                });

            // 게스트는 "오늘" 본 상품으로 묶어서 보여줄게! 🥰
            $recentViews = $products->isNotEmpty() ? collect(['오늘' => $products->map(fn($p) => (object)['product' => $p])]) : collect();
        }

        return [
            'member' => $member,
            'recentViews' => $recentViews,
        ];
    }

    /**
     * 최근 본 상품 전체 삭제 ✨ (비로그인 지원!)
     */
    public function clearRecentViews(?Member $member = null): void
    {
        if ($member) {
            $member->recentViews()->delete();
        } else {
            \Illuminate\Support\Facades\Cookie::queue(\Illuminate\Support\Facades\Cookie::forget('recent_views'));
        }
    }

    /**
     * 최근 본 상품 선택 삭제 ✨
     */
    public function deleteSelectedRecentViews(Member $member, array $ids): void
    {
        $member->recentViews()->whereIn('id', $ids)->delete();
    }

    /**
     * 회원의 찜 목록 전체 삭제 ✨
     */
    public function clearWishlist(Member $member): void
    {
        $member->wishlists()->delete();
    }

    /**
     * 1:1 문의 내역 조회 ✨
     */
    public function getInquiryData(Member $member): array
    {
        $inquiries = $member->inquiries()
            ->latest()
            ->paginate(10);

        return [
            'member' => $member,
            'inquiries' => $inquiries,
        ];
    }

    /**
     * 1:1 문의 등록 ✨
     */
    public function createInquiry(Member $member, array $data): Inquiry
    {
        return $member->inquiries()->create([
            'title' => $data['title'],
            'content' => $data['content'],
            'status' => '답변대기'
        ]);
    }
}
