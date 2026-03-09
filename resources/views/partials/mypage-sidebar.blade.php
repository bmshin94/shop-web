<aside class="w-full lg:w-64 shrink-0 bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sticky top-32 h-fit">
    <div class="mb-8">
        <h3 class="text-lg font-bold text-text-main mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">person</span> 나의 쇼핑 정보
        </h3>
        <ul class="space-y-3">
            <li><a href="{{ route('mypage.order-list') }}" class="text-sm font-medium {{ request()->routeIs('mypage.order-list') ? 'text-primary font-bold underline' : 'text-text-main hover:text-primary' }} transition-colors">주문/배송 조회</a></li>
            <li><a href="{{ route('mypage.claim-list') }}" class="text-sm font-medium {{ request()->routeIs('mypage.claim-list') ? 'text-primary font-bold underline' : 'text-text-main hover:text-primary' }} transition-colors">취소/반품/교환 내역</a></li>
            <li><a href="{{ route('mypage.refund-list') }}" class="text-sm font-medium {{ request()->routeIs('mypage.refund-list') ? 'text-primary font-bold underline' : 'text-text-main hover:text-primary' }} transition-colors">환불/입금 내역</a></li>
            <li><a href="{{ route('mypage.receipt') }}" class="text-sm font-medium {{ request()->routeIs('mypage.receipt') ? 'text-primary font-bold underline' : 'text-text-main hover:text-primary' }} transition-colors">영수증/계산서 발급</a></li>
        </ul>
    </div>
    <div class="mb-8">
        <h3 class="text-lg font-bold text-text-main mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">redeem</span> 혜택 관리
        </h3>
        <ul class="space-y-3">
            <li><a href="{{ route('mypage.coupon') }}" class="text-sm font-medium {{ request()->routeIs('mypage.coupon') ? 'text-primary font-bold underline' : 'text-text-main hover:text-primary' }} transition-colors flex justify-between">쿠폰 <span class="text-primary font-bold">2장</span></a></li>
            <li><a href="{{ route('mypage.point') }}" class="text-sm font-medium {{ request()->routeIs('mypage.point') ? 'text-primary font-bold underline' : 'text-text-main hover:text-primary' }} transition-colors flex justify-between">적립금 <span class="text-primary font-bold">12,500원</span></a></li>
            <li><a href="{{ route('mypage.deposit') }}" class="text-sm font-medium {{ request()->routeIs('mypage.deposit') ? 'text-primary font-bold underline' : 'text-text-main hover:text-primary' }} transition-colors">예치금 내역</a></li>
        </ul>
    </div>
    <div class="mb-8">
        <h3 class="text-lg font-bold text-text-main mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">favorite</span> 관심 상품
        </h3>
        <ul class="space-y-3">
            <li><a href="{{ route('mypage.wishlist') }}" class="text-sm font-medium {{ request()->routeIs('mypage.wishlist') ? 'text-primary font-bold underline' : 'text-text-main hover:text-primary' }} transition-colors">찜한 상품</a></li>
            <li><a href="{{ route('mypage.recent') }}" class="text-sm font-medium {{ request()->routeIs('mypage.recent') ? 'text-primary font-bold underline' : 'text-text-main hover:text-primary' }} transition-colors">최근 본 상품</a></li>
        </ul>
    </div>
    <div>
        <h3 class="text-lg font-bold text-text-main mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">support_agent</span> 고객 센터
        </h3>
        <ul class="space-y-3">
            <li><a href="{{ route('mypage.inquiry') }}" class="text-sm font-medium {{ request()->routeIs('mypage.inquiry') ? 'text-primary font-bold underline' : 'text-text-main hover:text-primary' }} transition-colors">1:1 문의내역</a></li>
            <li><a href="{{ route('mypage.review') }}" class="text-sm font-medium {{ request()->routeIs('mypage.review') ? 'text-primary font-bold underline' : 'text-text-main hover:text-primary' }} transition-colors">상품 리뷰 관리</a></li>
            <li><a href="{{ route('mypage.profile') }}" class="text-sm font-medium {{ request()->routeIs('mypage.profile') ? 'text-primary font-bold underline' : 'text-text-main hover:text-primary' }} transition-colors">회원정보 수정</a></li>
        </ul>
    </div>
</aside>
