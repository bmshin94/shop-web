<header class="sticky top-0 z-50 w-full border-b border-gray-100 bg-white/80 backdrop-blur-xl transition-all duration-300">
  <!-- Top Utility Bar -->
  <div class="bg-gray-50/50 py-1.5 text-[11px] font-bold text-text-muted border-b border-gray-100/50">
    <div class="mx-auto flex max-w-7xl justify-end px-6 lg:px-8 gap-6 tracking-tight">
      @guest
      <a class="hover:text-primary transition-colors flex items-center gap-1" href="{{ route('login') }}">
        <span class="material-symbols-outlined text-[14px]">login</span> 로그인
      </a>
      <a class="hover:text-primary transition-colors flex items-center gap-1" href="{{ route('register') }}">
        <span class="material-symbols-outlined text-[14px]">person_add</span> 회원가입
      </a>
      @endguest
      @auth
      <span class="flex items-center gap-1 text-text-main">
        <span class="font-black text-primary">{{ Auth::user()->name }}</span>님 환영합니다
      </span>
      <a class="hover:text-primary transition-colors flex items-center gap-1" href="{{ route('mypage') }}">
        <span class="material-symbols-outlined text-[14px]">person</span> 마이페이지
      </a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
      </form>
      <a class="hover:text-primary transition-colors flex items-center gap-1" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <span class="material-symbols-outlined text-[14px]">logout</span> 로그아웃
      </a>
      @endauth
      <a class="hover:text-primary transition-colors flex items-center gap-1" href="/support">
        <span class="material-symbols-outlined text-[14px]">help</span> 고객센터
      </a>
    </div>
  </div>

  <!-- Main Header -->
  <div class="mx-auto flex h-[88px] max-w-7xl items-center justify-between px-6 lg:px-8">
    <div class="flex items-center gap-10">
      <!-- Mobile Menu Button -->
      <button id="mobile-menu-btn" class="lg:hidden flex size-10 items-center justify-center rounded-xl text-text-main transition-all hover:bg-background-alt hover:text-primary active:scale-90">
        <span class="material-symbols-outlined text-2xl">menu</span>
      </button>
      
      <!-- Logo -->
      <a class="flex items-center gap-2.5 group" href="/">
        <div class="flex size-9 items-center justify-center rounded-xl bg-primary text-white shadow-lg shadow-primary/20 group-hover:rotate-12 transition-transform duration-300">
          <span class="material-symbols-outlined text-xl">stat_1</span>
        </div>
        <h1 class="text-2xl font-black tracking-tighter text-text-main group-hover:text-primary transition-colors">
          Active Women
        </h1>
      </a>

      <!-- Search Bar (Desktop) -->
      <div class="hidden lg:flex items-center relative">
        <div class="flex w-[280px] xl:w-[320px] items-center rounded-2xl bg-gray-100/80 px-4 py-2.5 transition-all focus-within:ring-4 focus-within:ring-primary/10 hover:bg-gray-200/50 border border-transparent focus-within:border-primary/30 focus-within:bg-white shadow-sm group/search">
          <span class="material-symbols-outlined text-text-muted group-focus-within/search:text-primary transition-colors">search</span>
          <input
            class="w-full border-none bg-transparent px-3 text-[13px] font-bold text-text-main placeholder:text-text-muted focus:ring-0"
            placeholder="어떤 스타일을 찾으시나요?" type="text" />
        </div>
      </div>
    </div>

    <!-- Center Navigation -->
    <nav class="hidden items-center gap-1 lg:flex">
      <!-- Categories  -->
      <div class="group relative py-6">
        <button class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-[15px] font-bold text-text-main transition-all hover:text-primary hover:bg-primary-light/50">
          <span class="material-symbols-outlined text-xl">grid_view</span>
          카테고리
        </button>
        <!-- Mega Menu Dropdown -->
        <div class="invisible absolute left-0 top-full z-50 w-[560px] rounded-[2rem] border border-gray-100 bg-white/95 p-10 shadow-[0_30px_60px_-15px_rgba(0,0,0,0.1)] backdrop-blur-xl transition-all duration-300 opacity-0 -translate-y-2 group-hover:visible group-hover:opacity-100 group-hover:translate-y-0">
          <div class="grid grid-cols-3 gap-10">
            @foreach($globalCategories ?? [] as $category)
            <div>
              <h3 class="mb-5 text-[11px] font-bold text-primary uppercase tracking-[0.2em] border-b border-primary/10 pb-2">{{ $category->name }}</h3>
              <ul class="space-y-3.5">
                @foreach($category->children as $child)
                <li>
                  <a href="{{ route('product-list', ['category' => $child->slug]) }}" class="text-[14px] font-bold text-text-main hover:text-primary transition-all flex items-center gap-2 group/link">
                    <span class="size-1 rounded-full bg-gray-300 group-hover/link:bg-primary transition-colors"></span>
                    {{ $child->name }}
                  </a>
                </li>
                @endforeach
              </ul>
            </div>
            @endforeach
          </div>
        </div>
      </div>

      @foreach(['신상품' => 'products.new', '베스트' => 'products.best'] as $label => $route)
      <a class="px-4 py-2 rounded-xl text-[15px] font-bold text-text-main transition-all hover:text-primary hover:bg-primary-light/50" href="{{ route($route) }}">{{ $label }}</a>
      @endforeach
      <a class="px-4 py-2 rounded-xl text-[15px] font-bold text-text-main transition-all hover:text-primary hover:bg-primary-light/50" href="/event">이벤트</a>
      <a class="px-4 py-2 rounded-xl text-[15px] font-bold text-text-main transition-all hover:text-primary hover:bg-primary-light/50" href="/community">커뮤니티</a>
      <a class="px-4 py-2 rounded-xl text-[15px] font-bold text-primary transition-all hover:bg-primary-light" href="/exhibition">기획전</a>
    </nav>

    <!-- Action Icons -->
    <div class="flex items-center gap-1">
      <a href="/mypage/wishlist"
        class="relative flex size-11 items-center justify-center rounded-xl text-text-main transition-all hover:bg-primary-light hover:text-primary active:scale-95" title="찜한 상품">
        <span class="material-symbols-outlined text-[24px]">favorite</span>
        <span class="header-wishlist-count absolute top-0 right-0 {{ auth()->check() && auth()->user()->wishlists()->count() > 0 ? 'flex' : 'hidden' }} size-[18px] items-center justify-center rounded-full bg-primary text-[9px] leading-none font-bold text-white ring-1 ring-white shadow-sm shrink-0">@auth{{ auth()->user()->wishlists()->count() }}@else 0 @endauth</span>
      </a>
      <a href="{{ route('cart.index') }}"
        class="relative flex size-11 items-center justify-center rounded-xl text-text-main transition-all hover:bg-primary-light hover:text-primary active:scale-95" title="장바구니">
        <span class="material-symbols-outlined text-[24px]">shopping_cart</span>
        <span class="header-cart-count absolute top-0 right-0 {{ auth()->check() && auth()->user()->carts()->count() > 0 ? 'flex' : 'hidden' }} size-[18px] items-center justify-center rounded-full bg-primary text-[9px] leading-none font-bold text-white ring-1 ring-white shadow-sm shrink-0">@auth{{ auth()->user()->carts()->count() }}@else 0 @endauth</span>
      </a>
      <a href="/mypage"
        class="flex size-11 items-center justify-center rounded-xl text-text-main transition-all hover:bg-primary-light hover:text-primary active:scale-95" title="마이페이지">
        <span class="material-symbols-outlined text-[24px]">person</span>
      </a>
    </div>
  </div>
</header>

<!-- Mobile Menu Drawer (Refined) -->
<div id="mobile-menu" class="fixed inset-0 z-[100] hidden">
  <div id="mobile-menu-overlay" class="absolute inset-0 bg-black/40 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>
  <div id="mobile-menu-content" class="absolute left-0 top-0 h-full w-[85%] max-w-sm -translate-x-full bg-white transition-transform duration-300 ease-in-out shadow-2xl">
    <div class="flex h-full flex-col">
      <!-- Header -->
      <div class="p-6 flex items-center justify-between border-b border-gray-50">
        <a class="flex items-center gap-2" href="/">
          <div class="flex size-8 items-center justify-center rounded-lg bg-primary text-white">
            <span class="material-symbols-outlined text-lg">stat_1</span>
          </div>
          <span class="text-xl font-bold text-text-main tracking-tight">Active Women</span>
        </a>
        <button id="mobile-menu-close" class="flex size-10 items-center justify-center rounded-xl text-text-muted hover:bg-gray-100 transition-colors">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>
      
      <!-- Content -->
      <div class="flex-1 overflow-y-auto p-6 space-y-8">
        <!-- Categories Section -->
        <div>
          <p class="mb-4 px-1 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Shop Categories</p>
          <div class="space-y-6">
            @foreach($globalCategories ?? [] as $category)
            <div class="space-y-3">
              <p class="px-1 text-xs font-bold text-primary uppercase tracking-wider">{{ $category->name }}</p>
              <div class="grid grid-cols-2 gap-2">
                @foreach($category->children as $child)
                <a class="rounded-xl px-3 py-2.5 text-[13px] font-bold text-text-main bg-gray-50 hover:bg-primary-light hover:text-primary transition-colors" href="{{ route('product-list', ['category' => $child->slug]) }}">
                  {{ $child->name }}
                </a>
                @endforeach
              </div>
            </div>
            @endforeach
          </div>
        </div>

        <!-- Main Menu Section -->
        <div class="pt-2 border-t border-gray-50">
          <p class="mb-4 px-1 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Quick Links</p>
          <nav class="flex flex-col gap-1">
            @foreach(['신상품' => 'products.new', '베스트' => 'products.best'] as $label => $route)
            <a class="flex items-center justify-between rounded-xl px-3 py-3.5 text-[15px] font-bold text-text-main hover:bg-gray-50 transition-colors" href="{{ route($route) }}">
              {{ $label }} <span class="material-symbols-outlined text-sm opacity-20">chevron_right</span>
            </a>
            @endforeach
            <a class="flex items-center justify-between rounded-xl px-3 py-3.5 text-[15px] font-bold text-text-main hover:bg-gray-50 transition-colors" href="/event">이벤트</a>
            <a class="flex items-center justify-between rounded-xl px-3 py-3.5 text-[15px] font-bold text-text-main hover:bg-gray-50 transition-colors" href="/community">커뮤니티</a>
            <a class="flex items-center justify-between rounded-xl px-3 py-3.5 text-[15px] font-bold text-primary hover:bg-primary-light transition-colors" href="/exhibition">기획전</a>
          </nav>
        </div>
      </div>
      
      <!-- Footer Actions -->
      <div class="p-6 bg-gray-50 border-t border-gray-100 grid grid-cols-2 gap-3">
        @guest
        <a href="{{ route('login') }}" class="flex h-12 items-center justify-center rounded-xl bg-white border border-gray-200 text-[13px] font-bold text-text-main shadow-sm">로그인</a>
        <a href="{{ route('register') }}" class="flex h-12 items-center justify-center rounded-xl bg-primary text-[13px] font-bold text-white shadow-lg shadow-primary/20">회원가입</a>
        @endguest
        @auth
        <a href="{{ route('mypage') }}" class="flex h-12 items-center justify-center rounded-xl bg-white border border-gray-200 text-[13px] font-bold text-text-main shadow-sm">마이페이지</a>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex h-12 items-center justify-center rounded-xl bg-primary text-[13px] font-bold text-white shadow-lg shadow-primary/20">로그아웃</a>
        @endauth
      </div>
    </div>
  </div>
</div>
