<aside class="w-full lg:w-64 shrink-0 bg-white rounded-2xl shadow-sm border border-gray-200 p-0 lg:p-6 lg:sticky lg:top-32 h-fit overflow-hidden">
    <!-- Mobile Toggle Button (Only Mobile) -->
    <button id="toggle-mypage-menu" class="lg:hidden w-full flex items-center justify-between p-5 text-sm font-black text-text-main hover:bg-gray-50 transition-colors">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">menu_open</span>
            <span>마이페이지 메뉴</span>
        </div>
        <span id="toggle-icon" class="material-symbols-outlined transition-transform duration-300">expand_more</span>
    </button>

    <!-- Menu Content (Hidden on mobile by default) -->
    <div id="mypage-menu-content" class="hidden lg:block p-5 pt-0 lg:p-0">
        <div class="mb-6 lg:mb-8 pt-2 lg:pt-0">
            <h3 class="text-base lg:text-lg font-bold text-text-main mb-3 lg:mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-xl lg:text-2xl">person</span> 나의 쇼핑 정보
            </h3>
            <ul class="grid grid-cols-2 lg:grid-cols-1 gap-2 lg:space-y-3">
                <li><a href="{{ route('mypage.order-list') }}" class="block px-3 py-2 lg:p-0 rounded-lg lg:rounded-none bg-gray-50 lg:bg-transparent text-xs lg:text-sm font-medium {{ request()->routeIs('mypage.order-list') ? 'text-primary font-bold lg:underline bg-primary-light lg:bg-transparent' : 'text-text-main hover:text-primary' }} transition-colors">주문/배송 조회</a></li>
                <li><a href="{{ route('mypage.cancel-list') }}" class="block px-3 py-2 lg:p-0 rounded-lg lg:rounded-none bg-gray-50 lg:bg-transparent text-xs lg:text-sm font-medium {{ request()->routeIs('mypage.cancel-list') ? 'text-primary font-bold lg:underline bg-primary-light lg:bg-transparent' : 'text-text-main hover:text-primary' }} transition-colors">취소/교환/반품 내역</a></li>
                <li><a href="{{ route('mypage.refund-list') }}" class="block px-3 py-2 lg:p-0 rounded-lg lg:rounded-none bg-gray-50 lg:bg-transparent text-xs lg:text-sm font-medium {{ request()->routeIs('mypage.refund-list') ? 'text-primary font-bold lg:underline bg-primary-light lg:bg-transparent' : 'text-text-main hover:text-primary' }} transition-colors">환불/입금 내역</a></li>
                <li><a href="{{ route('mypage.receipt') }}" class="block px-3 py-2 lg:p-0 rounded-lg lg:rounded-none bg-gray-50 lg:bg-transparent text-xs lg:text-sm font-medium {{ request()->routeIs('mypage.receipt') ? 'text-primary font-bold lg:underline bg-primary-light lg:bg-transparent' : 'text-text-main hover:text-primary' }} transition-colors">영수증 발급</a></li>
            </ul>
        </div>
        
        <div class="mb-6 lg:mb-8">
            <h3 class="text-base lg:text-lg font-bold text-text-main mb-3 lg:mb-4 flex items-center gap-2 border-t lg:border-none pt-5 lg:pt-0">
                <span class="material-symbols-outlined text-primary text-xl lg:text-2xl">redeem</span> 혜택 관리
            </h3>
            <ul class="grid grid-cols-2 lg:grid-cols-1 gap-2 lg:space-y-3">
                <li><a href="{{ route('mypage.coupon') }}" class="flex flex-col lg:flex-row justify-center lg:justify-between items-center px-2 py-3 lg:p-0 rounded-xl lg:rounded-none bg-gray-50 lg:bg-transparent text-[11px] lg:text-sm font-medium {{ request()->routeIs('mypage.coupon') ? 'text-primary font-bold bg-primary-light lg:bg-transparent' : 'text-text-main' }}">
                    <span>보유 쿠폰</span> <span class="text-primary font-bold lg:ml-1">@if(Auth::check()){{ number_format(Auth::user()->coupons()->whereNull('used_at')->count()) }}@else 0 @endif장</span>
                </a></li>
                <li><a href="{{ route('mypage.point') }}" class="flex flex-col lg:flex-row justify-center lg:justify-between items-center px-2 py-3 lg:p-0 rounded-xl lg:rounded-none bg-gray-50 lg:bg-transparent text-[11px] lg:text-sm font-medium {{ request()->routeIs('mypage.point') ? 'text-primary font-bold bg-primary-light lg:bg-transparent' : 'text-text-main' }}">
                    <span>적립금</span> <span class="text-primary font-bold lg:ml-1">@if(Auth::check()){{ number_format(Auth::user()->points ?? 0) }}@else 0 @endif원</span>
                </a></li>
            </ul>
        </div>

        <div class="mb-6 lg:mb-8">
            <h3 class="text-base lg:text-lg font-bold text-text-main mb-3 lg:mb-4 flex items-center gap-2 border-t lg:border-none pt-5 lg:pt-0">
                <span class="material-symbols-outlined text-primary text-xl lg:text-2xl">favorite</span> 관심 상품
            </h3>
            <ul class="grid grid-cols-2 lg:grid-cols-1 gap-2 lg:space-y-3">
                <li><a href="{{ route('mypage.wishlist') }}" class="block px-3 py-2 lg:p-0 rounded-lg lg:rounded-none bg-gray-50 lg:bg-transparent text-xs lg:text-sm font-medium {{ request()->routeIs('mypage.wishlist') ? 'text-primary font-bold lg:underline bg-primary-light lg:bg-transparent' : 'text-text-main hover:text-primary' }} transition-colors">찜한 상품</a></li>
                <li><a href="{{ route('mypage.recent') }}" class="block px-3 py-2 lg:p-0 rounded-lg lg:rounded-none bg-gray-50 lg:bg-transparent text-xs lg:text-sm font-medium {{ request()->routeIs('mypage.recent') ? 'text-primary font-bold lg:underline bg-primary-light lg:bg-transparent' : 'text-text-main hover:text-primary' }} transition-colors">최근 본 상품</a></li>
            </ul>
        </div>

        <div>
            <h3 class="text-base lg:text-lg font-bold text-text-main mb-3 lg:mb-4 flex items-center gap-2 border-t lg:border-none pt-5 lg:pt-0">
                <span class="material-symbols-outlined text-primary text-xl lg:text-2xl">support_agent</span> 고객 센터
            </h3>
            <ul class="grid grid-cols-3 lg:grid-cols-1 gap-2 lg:space-y-3">
                <li><a href="{{ route('mypage.inquiry') }}" class="block px-2 py-2 lg:p-0 rounded-lg lg:rounded-none bg-gray-50 lg:bg-transparent text-[11px] lg:text-sm font-medium {{ request()->routeIs('mypage.inquiry') ? 'text-primary font-bold lg:underline bg-primary-light lg:bg-transparent' : 'text-text-main hover:text-primary' }} transition-colors text-center lg:text-left">1:1 문의</a></li>
                <li><a href="{{ route('mypage.review') }}" class="block px-2 py-2 lg:p-0 rounded-lg lg:rounded-none bg-gray-50 lg:bg-transparent text-[11px] lg:text-sm font-medium {{ request()->routeIs('mypage.review') ? 'text-primary font-bold lg:underline bg-primary-light lg:bg-transparent' : 'text-text-main hover:text-primary' }} transition-colors text-center lg:text-left">리뷰 관리</a></li>
                <li><a href="{{ route('mypage.profile') }}" class="block px-2 py-2 lg:p-0 rounded-lg lg:rounded-none bg-gray-50 lg:bg-transparent text-[11px] lg:text-sm font-medium {{ request()->routeIs('mypage.profile') ? 'text-primary font-bold lg:underline bg-primary-light lg:bg-transparent' : 'text-text-main hover:text-primary' }} transition-colors text-center lg:text-left">정보 수정</a></li>
            </ul>
        </div>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggleBtn = document.getElementById('toggle-mypage-menu');
        const menuContent = document.getElementById('mypage-menu-content');
        const toggleIcon = document.getElementById('toggle-icon');

        if (toggleBtn && menuContent) {
            toggleBtn.addEventListener('click', () => {
                const isHidden = menuContent.classList.contains('hidden');
                
                if (isHidden) {
                    menuContent.classList.remove('hidden');
                    toggleIcon.style.transform = 'rotate(180deg)';
                } else {
                    menuContent.classList.add('hidden');
                    toggleIcon.style.transform = 'rotate(0deg)';
                }
            });
        }
    });
</script>
