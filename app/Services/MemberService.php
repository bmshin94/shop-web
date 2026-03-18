<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Order;
use App\Models\Review;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\PointHistory;
use App\Models\Inquiry; // Inquiry 모델 추가!
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Notifications\WelcomeNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class MemberService
{
    /**
     * 회원 가입 처리 및 알림 발송
     * 
     * @param array $data 가입 데이터 (name, email, phone, password)
     * @return Member 생성된 회원 모델
     */
    public function register(array $data): Member
    {
        // 1. 회원 정보 생성
        $member = Member::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'status' => '활성',
        ]);

        // 2. 가입 축하 알림 발송 (푸쉬/데이터베이스)
        $member->notify(new WelcomeNotification());

        return $member;
    }

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
     * 
     * @param Member $member
     * @return array 데이터 배열 ('member', 'currentPoints', 'expiringPoints', 'histories')
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
     * 
     * @param Member $member
     * @return array 대시보드 요약 정보 데이터
     */
    public function getDashboardData(Member $member): array
    {
        // 1. 주문 현황 통계 (상태별 카운트)
        $orderStats = [
            'received' => $member->orders()->where('order_status', '주문접수')->count(),
            'preparing' => $member->orders()->where('order_status', '상품준비중')->count(),
            'shipping' => $member->orders()->where('order_status', '배송중')->count(),
            'delivered' => $member->orders()->where('order_status', '배송완료')->count(),
            'confirmed' => $member->orders()->where('order_status', '구매확정')->count(),
        ];

        // 2. 최근 주문 내역 5건
        $recentOrders = $member->orders()->with('items.product')->latest()->take(5)->get();
        
        // 3. 총 주문 횟수
        $totalOrderCount = $member->orders()->count();

        // 4. 사용 가능한 쿠폰 개수
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
     * 
     * @param Member $member
     * @param Request $request 검색/필터링 조건
     * @return array 쿠폰 목록 및 통계 데이터
     */
    public function getCouponListData(Member $member, Request $request): array
    {
        // 1. 회원의 모든 쿠폰 베이스 쿼리 생성
        $query = $member->coupons();

        // 2. 키워드 검색 적용 (이름, 설명)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 3. 쿠폰 유형 필터링
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // 4. 데이터 가공 및 상태 결정
        $coupons = $query->latest('assigned_at')->get()->map(function($coupon) {
            $isUsed = $coupon->pivot->used_at !== null;
            $isExpired = $coupon->ends_at && $coupon->ends_at->isPast();

            return (object)[
                'id' => $coupon->pivot->id, // 쿠폰 발급 고유 ID
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
     * 
     * @param Member $member
     * @param string $code 등록할 쿠폰 코드
     * @return Coupon 등록된 쿠폰 모델
     * @throws \Exception 쿠폰 코드 유효성 검사 실패 시
     */
    public function registerCoupon(Member $member, string $code): Coupon
    {
        // 1. 코드 대문자화 후 존재 여부 확인
        $coupon = Coupon::where('code', strtoupper($code))->first();

        if (!$coupon) {
            throw new \Exception('존재하지 않거나 발급 가능한 코드가 아닙니다.');
        }

        // 2. 쿠폰 상태 검증 (활성, 기간, 중복 등록 여부)
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

        // 3. 회원에게 쿠폰 할당 및 이력 생성
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
     * @param Request $request 검색/필터링 조건 (search, status, months, start_date, end_date)
     * @return array 주문 목록 및 필터링 메타데이터
     */
    public function getOrderListData(Member $member, Request $request): array
    {
        // 1. 기본 주문 쿼리 생성 (취소/환불 건 영구 제외)
        $query = $member->orders()
            ->with('items.product')
            ->whereNotIn('order_status', ['취소완료'])
            ->whereNotIn('payment_status', ['환불완료', '취소완료'])
            ->latest();

        // 2. 키워드 검색 적용 (검색 조건은 그룹화하여 취소 제외 조건과 분리)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('items', function($sq) use ($search) {
                      $sq->where('product_name', 'like', "%{$search}%");
                  });
            });
        }

        // 3. 주문 상태 필터링
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        // 4. 기간 필터링 처리 (날짜 범위 또는 사전 정의된 개월 수)
        $months = $request->get('months');
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $query->whereBetween('ordered_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59'
            ]);
        } else {
            $months = $months ?: 1; // 기본 1개월
            $startDate = now()->subMonths($months)->format('Y-m-d');
            $endDate = now()->format('Y-m-d');
            $query->where('ordered_at', '>=', $startDate . ' 00:00:00');
        }

        // 5. 페이징 처리 및 결과 반환
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
     * @return array 작성 가능한 리뷰 및 작성한 리뷰 데이터
     */
    public function getReviewListData(Member $member): array
    {
        // 1. 작성 가능한 리뷰 대상 추출 (배송완료 또는 구매확정 상품 중 미작성건)
        $purchasedProductIds = OrderItem::whereHas('order', function($q) use ($member) {
                $q->where('member_id', $member->id)->whereIn('order_status', ['배송완료', '구매확정']);
            })
            ->pluck('product_id')
            ->unique();

        $reviewedProductIds = Review::where('member_id', $member->id)->pluck('product_id');
        
        $availableProductIds = $purchasedProductIds->diff($reviewedProductIds);
        $availableReviews = Product::whereIn('id', $availableProductIds)
            ->paginate(10, ['*'], 'page_avail');

        // 2. 이미 작성된 나의 리뷰 목록 추출
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
     * 
     * @param Member $member
     * @param Request $request 필터 조건
     * @return array 취소 내역 데이터 및 필터 메타데이터
     */
    public function getCancelListData(Member $member, Request $request): array
    {
        // 1. 모든 클레임(취소/반품/교환) 통합 쿼리
        $query = $member->orderClaims()
            ->with(['order', 'items.orderItem.product'])
            ->latest();

        // 2. 검색어 필터링 적용 (신청번호, 주문번호, 상품명)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('claim_number', 'like', "%{$search}%")
                  ->orWhereHas('order', function($sq) use ($search) {
                      $sq->where('order_number', 'like', "%{$search}%");
                  })
                  ->orWhereHas('items.orderItem', function($sq) use ($search) {
                      $sq->where('product_name', 'like', "%{$search}%");
                  });
            });
        }

        // 3. 신청유형 필터링 (cancel, exchange, return)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // 4. 진행상태 필터링 (접수, 처리중, 완료, 거부)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 5. 기간 필터링 처리
        $months = $request->get('months', 1);
        $startDate = $request->filled('start_date') ? $request->start_date : now()->subMonths($months)->format('Y-m-d');
        $endDate = $request->filled('end_date') ? $request->end_date : now()->format('Y-m-d');

        $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        // 5. 페이징 처리 및 데이터 가공 (뷰 호환성 유지)
        $claims = $query->paginate(10)->withQueryString();

        $claims->getCollection()->transform(function($claim) {
            $typeLabel = '취소';
            if ($claim->type === 'exchange') $typeLabel = '교환';
            if ($claim->type === 'return') $typeLabel = '반품';

            return (object)[
                'id' => $claim->id,
                'number' => $claim->claim_number,
                'type' => $typeLabel,
                'items' => $claim->items->map(fn($ci) => $ci->orderItem),
                'status' => $claim->status,
                'created_at' => $claim->created_at,
                'is_claim' => true
            ];
        });

        return [
            'member' => $member,
            'cancels' => $claims,
            'months' => $months,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'status' => $request->get('status'),
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
     * 영수증/계산서 발급 데이터 조회 및 필터링
     * 
     * @param Member $member
     * @param Request $request 필터 조건
     * @return array 영수증 목록 및 메타데이터
     */
    public function getReceiptListData(Member $member, Request $request): array
    {
        // 1. 결제 완료된 주문 베이스 쿼리
        $query = $member->orders()
            ->with(['items.product'])
            ->where('payment_status', '결제완료')
            ->latest();

        // 2. 검색어 필터링
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('items', function($sq) use ($search) {
                      $sq->where('product_name', 'like', "%{$search}%");
                  });
            });
        }

        // 3. 필터링 (결제 수단 기준 등)
        if ($request->filled('status')) {
            $query->where('payment_method', $request->status);
        }

        // 4. 기간 필터링 처리
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

        // 5. 페이징 및 영수증 URL 생성 (PortOne 연동)
        $receipts = $query->paginate(10)->withQueryString();

        $receipts->getCollection()->transform(function($order) {
            // NOTE: 실제 운영에서는 PG 응답 결과에 따라 URL을 생성하거나 저장된 값을 사용함
            $order->receipt_url = "https://iniweb.inicis.com/DefaultWebApp/mall/cr/cm/m_s_receipt.jsp?noTid={$order->imp_uid}&noMethod=1"; 
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
     * 최근 본 상품 데이터 조회 및 날짜별 그룹화 (로그인/비로그인 공통)
     * 
     * @param Member|null $member 로그인 회원 정보 (null일 경우 게스트)
     * @return array 그룹화된 최근 본 상품 데이터
     */
    public function getRecentViewData(?Member $member = null): array
    {
        if ($member) {
            // 1. 로그인 회원: DB에서 최근 50건 조회 및 날짜별 그룹화 
            $recentViews = $member->recentViews()
                ->with(['product.images', 'product.colors'])
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
            // 2. 비로그인 게스트: 쿠키에 저장된 ID를 기반으로 상품 정보 조회
            $viewedIds = json_decode(request()->cookie('recent_views', '[]'), true) ?: [];
            
            $products = Product::with(['images', 'colors'])
                ->whereIn('id', $viewedIds)
                ->get()
                ->sortBy(function($p) use ($viewedIds) {
                    return array_search($p->id, $viewedIds);
                });

            $recentViews = $products->isNotEmpty() 
                ? collect(['오늘' => $products->map(fn($p) => (object)['product' => $p])]) 
                : collect();
        }

        return [
            'member' => $member,
            'recentViews' => $recentViews,
        ];
    }

    /**
     * 최근 본 상품 전체 삭제  (비로그인 지원!)
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
     * 최근 본 상품 선택 삭제 
     */
    public function deleteSelectedRecentViews(Member $member, array $ids): void
    {
        $member->recentViews()->whereIn('id', $ids)->delete();
    }

    /**
     * 회원의 찜 목록 전체 삭제 
     */
    public function clearWishlist(Member $member): void
    {
        $member->wishlists()->delete();
    }

    /**
     * 1:1 문의 내역 조회 
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
     * 1:1 문의 등록
     * 
     * @param Member $member
     * @param array $data 문의 내용 (title, content, product_id, is_private, images)
     * @return Inquiry 생성된 문의 모델
     */
    public function createInquiry(Member $member, array $data): Inquiry
    {
        // 1. 회원의 문의 내역으로 새로운 데이터 생성
        return $member->inquiries()->create([
            'product_id' => $data['product_id'] ?? null,
            'title' => $data['title'],
            'content' => $data['content'],
            'is_private' => $data['is_private'] ?? false,
            'images' => $data['images'] ?? null,
            'status' => '답변대기'
        ]);
    }

    /**
     * 휴대폰 번호로 가입된 이메일 주소 찾기 (마스킹 처리 포함)
     * 
     * @param string $phone 찾으려는 휴대폰 번호
     * @return string|null 마스킹된 이메일 주소 또는 null
     */
    public function findEmailByPhone(string $phone): ?string
    {
        // 1. 휴대폰 번호에서 하이픈 제거 후 조회
        $cleanPhone = str_replace('-', '', $phone);
        $member = Member::where('phone', $phone)
            ->orWhere('phone', $cleanPhone)
            ->first();

        if (!$member) {
            return null;
        }

        // 2. 이메일 마스킹 처리
        return $this->maskEmail($member->email);
    }

    /**
     * 이메일 주소 마스킹 처리
     * 
     * @param string $email 원본 이메일 주소
     * @return string 마스킹된 이메일 주소 (예: ab****@example.com)
     */
    public function maskEmail(string $email): string
    {
        $emailParts = explode('@', $email);
        $name = $emailParts[0];
        $domain = $emailParts[1];
        
        $length = strlen($name);
        $visibleCount = $length > 4 ? 3 : 2;
        
        return substr($name, 0, $visibleCount) . str_repeat('*', $length - $visibleCount) . '@' . $domain;
    }
}
