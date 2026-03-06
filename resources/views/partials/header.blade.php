<header class="sticky top-0 z-50 w-full border-b border-background-alt bg-white/95 backdrop-blur-md">
  <div class="bg-background-alt py-1 text-xs text-text-muted">
    <div class="mx-auto flex max-w-7xl justify-end px-6 lg:px-8 gap-4">
      <a class="hover:text-primary" href="/login">로그인</a>
      <a class="hover:text-primary" href="/register">회원가입</a>
      <a class="hover:text-primary" href="#">고객센터</a>
    </div>
  </div>
  <div class="mx-auto flex h-[110px] max-w-7xl items-center justify-between px-6 lg:px-8">
    <div class="flex items-center gap-8 lg:gap-12">
      <!-- 모바일 메뉴 버튼 (Mobile Hamburger Button) -->
      <button id="mobile-menu-btn" class="lg:hidden flex size-10 items-center justify-center rounded-full text-text-main transition-colors hover:bg-background-alt hover:text-primary">
        <span class="material-symbols-outlined">menu</span>
      </button>
      
      <a class="flex items-center gap-2" href="/">
        <div class="flex size-8 items-center justify-center rounded-full bg-primary text-white">
          <span class="material-symbols-outlined text-xl">stat_1</span>
        </div>
        <h1 class="text-xl font-extrabold tracking-tight text-text-main">
          Active Women
        </h1>
      </a>
      <div class="hidden md:flex flex-col relative group">
        <div
          class="flex w-[380px] items-center rounded-full bg-background-alt px-4 py-2 transition-colors focus-within:ring-2 focus-within:ring-primary/20 hover:bg-neutral-100 border border-transparent focus-within:border-primary/50">
          <input
            class="w-full border-none bg-transparent px-2 text-sm font-medium text-text-main placeholder:text-text-muted focus:ring-0"
            placeholder="찾으시는 상품을 검색해보세요" type="text" />
          <span class="material-symbols-outlined text-text-muted cursor-pointer hover:text-primary">search</span>
        </div>
        <div class="absolute top-full left-4 mt-1 flex gap-2 text-xs text-text-muted">
          <span class="font-bold text-primary">추천:</span>
          <a class="hover:underline" href="#">#야구</a>
          <a class="hover:underline" href="#">#풋살</a>
          <a class="hover:underline" href="#">#러닝</a>
        </div>
      </div>
    </div>
    <nav class="hidden items-center gap-8 lg:flex">
      <a class="text-base font-bold text-text-main transition-colors hover:text-primary" href="/product-list">신상품</a>
      <a class="text-base font-bold text-text-main transition-colors hover:text-primary" href="#">베스트</a>
      <a class="text-base font-bold text-text-main transition-colors hover:text-primary" href="#">이벤트</a>
      <a class="text-base font-bold text-text-main transition-colors hover:text-primary" href="#">커뮤니티</a>
      <a class="text-base font-bold text-primary transition-colors hover:text-red-700" href="#">기획전</a>
    </nav>
    <div class="flex items-center gap-2">
      <button
        class="flex size-10 items-center justify-center rounded-full text-text-main transition-colors hover:bg-background-alt hover:text-primary">
        <span class="material-symbols-outlined">favorite</span>
      </button>
      <button
        class="relative flex size-10 items-center justify-center rounded-full text-text-main transition-colors hover:bg-background-alt hover:text-primary">
        <span class="material-symbols-outlined">shopping_cart</span>
        <span
          class="absolute right-0 top-0 flex size-4 items-center justify-center rounded-full bg-primary text-[10px] font-bold text-white">3</span>
      </button>
      <button
        class="flex size-10 items-center justify-center rounded-full text-text-main transition-colors hover:bg-background-alt hover:text-primary">
        <span class="material-symbols-outlined">person</span>
      </button>
    </div>
  </div>
</header>

<!-- 모바일 사이드 메뉴 (Mobile Menu Drawer) -->
<div id="mobile-menu" class="fixed inset-0 z-[100] hidden">
  <div id="mobile-menu-overlay" class="absolute inset-0 bg-black/50 opacity-0 transition-opacity duration-300"></div>
  <div id="mobile-menu-content" class="absolute left-0 top-0 h-full w-4/5 max-w-sm -translate-x-full bg-white transition-transform duration-300 ease-in-out">
    <div class="flex h-full flex-col p-6">
      <div class="mb-10 flex items-center justify-between">
        <a class="flex items-center gap-2" href="/">
          <div class="flex size-8 items-center justify-center rounded-full bg-primary text-white">
            <span class="material-symbols-outlined text-lg">stat_1</span>
          </div>
          <span class="text-xl font-extrabold text-text-main">Active Women</span>
        </a>
        <button id="mobile-menu-close" class="flex size-10 items-center justify-center rounded-full text-text-muted transition-colors hover:bg-background-alt hover:text-primary">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>
      
      <nav class="flex flex-col gap-1">
        <p class="mb-2 px-2 text-[10px] font-bold uppercase tracking-widest text-text-muted">Menu</p>
        <a class="flex items-center justify-between rounded-lg px-2 py-3 text-lg font-bold text-text-main transition-colors hover:bg-background-alt hover:text-primary" href="/product-list">
          신상품 <span class="material-symbols-outlined text-sm opacity-30">chevron_right</span>
        </a>
        <a class="flex items-center justify-between rounded-lg px-2 py-3 text-lg font-bold text-text-main transition-colors hover:bg-background-alt hover:text-primary" href="#">
          베스트 <span class="material-symbols-outlined text-sm opacity-30">chevron_right</span>
        </a>
        <a class="flex items-center justify-between rounded-lg px-2 py-3 text-lg font-bold text-text-main transition-colors hover:bg-background-alt hover:text-primary" href="#">
          이벤트 <span class="material-symbols-outlined text-sm opacity-30">chevron_right</span>
        </a>
        <a class="flex items-center justify-between rounded-lg px-2 py-3 text-lg font-bold text-text-main transition-colors hover:bg-background-alt hover:text-primary" href="#">
          커뮤니티 <span class="material-symbols-outlined text-sm opacity-30">chevron_right</span>
        </a>
        <a class="flex items-center justify-between rounded-lg px-2 py-3 text-lg font-bold text-primary transition-colors hover:bg-primary-light" href="#">
          기획전 <span class="material-symbols-outlined text-sm opacity-30">chevron_right</span>
        </a>
      </nav>
      
      <div class="mt-auto flex flex-col gap-3 pt-8 border-t border-gray-100">
        <a href="/login" class="flex h-12 items-center justify-center rounded-full bg-background-alt text-sm font-bold text-text-main transition-colors hover:bg-gray-200">로그인</a>
        <a href="/register" class="flex h-12 items-center justify-center rounded-full bg-primary text-sm font-bold text-white shadow-lg shadow-primary/20 transition-all hover:bg-red-600 hover:scale-[1.02]">회원가입</a>
      </div>
    </div>
  </div>
</div>
