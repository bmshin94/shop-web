@extends('layouts.app')

@section('title', '홈 - Active Women\'s Premium Store')

@section('content')
<!-- 메인 히어로 배너 섹션  -->
<section class="relative mx-auto mt-6 max-w-[1400px] px-4 sm:px-6 lg:px-8">
  <div class="relative overflow-hidden rounded-2xl bg-black">
    @forelse($heroExhibitions as $exhibition)
    <div class="relative z-0 {{ $loop->first ? '' : 'hidden' }}"> <!-- 우선 첫 번째 기획전만 보여줄게!  -->
      <div class="absolute inset-0 z-0">
        <div class="h-full w-full bg-cover bg-center opacity-70 transition-transform duration-700 hover:scale-105"
          style="background-image: url('{{ $exhibition->banner_image_url ?? 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?q=80&w=2070' }}');">
        </div>
      </div>
      <div class="relative z-10 flex min-h-[500px] sm:min-h-[560px] flex-col items-center justify-center px-4 py-16 sm:py-20 text-center sm:px-6 lg:px-8">
        <span class="mb-4 inline-flex items-center rounded-full bg-primary/90 px-4 py-1.5 text-[10px] sm:text-xs font-bold uppercase tracking-wide text-white backdrop-blur-sm shadow-lg">
          {{ $exhibition->summary ?? 'New Season 2026' }}
        </span>
        <h2 class="mb-6 text-3xl font-extrabold leading-tight tracking-tight text-white sm:text-5xl md:text-6xl drop-shadow-md break-keep">
          {!! nl2br(e($exhibition->title)) !!}
        </h2>
        <p class="mb-8 sm:mb-10 max-w-xl text-base font-medium text-gray-100 sm:text-xl break-keep drop-shadow">
          {{ $exhibition->description ?? '하이엔드 감성과 퍼포먼스의 완벽한 조화. 새로운 컬렉션으로 최고의 순간을 준비하세요.' }}
        </p>
        <div class="flex flex-col gap-4 sm:flex-row">
          <a href="{{ route('exhibition.show', $exhibition->slug) }}"
            class="inline-flex h-12 min-w-[160px] items-center justify-center rounded-full bg-primary px-8 text-base font-bold text-white transition-all hover:bg-red-600 hover:shadow-lg hover:shadow-primary/30">
            컬렉션 보기
          </a>
          <a href="/exhibition"
            class="inline-flex h-12 min-w-[160px] items-center justify-center rounded-full bg-white px-8 text-base font-bold text-text-main transition-all hover:bg-gray-100">
            스타일북
          </a>
        </div>
      </div>
    </div>
    @empty
    <!-- 데이터가 없을 때의 기본 배너!  -->
    <div class="relative z-10 flex min-h-[560px] flex-col items-center justify-center px-4 py-20 text-center sm:px-6 lg:px-8">
      <h2 class="text-4xl font-extrabold text-white sm:text-6xl">당신만의 스타일로 <br class="hidden sm:block" /> <span class="text-primary">플레이하세요</span></h2>
    </div>
    @endforelse
  </div>
</section>

<!-- 카테고리 퀵 메뉴 섹션  -->
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
  <div class="grid grid-cols-2 gap-4 md:grid-cols-4 lg:gap-8">
    @foreach($topCategories as $cat)
    <a class="group flex flex-col items-center gap-4 rounded-xl border border-transparent bg-background-alt p-6 transition-all hover:border-primary/20 hover:bg-primary-light hover:shadow-md"
      href="{{ route('product-list', ['category' => $cat->slug]) }}">
      <div class="flex size-16 items-center justify-center rounded-full bg-white text-primary shadow-sm transition-transform group-hover:scale-110">
        <span class="material-symbols-outlined text-3xl">
          @if(str_contains($cat->name, '야구')) sports_baseball
          @elseif(str_contains($cat->name, '축구') || str_contains($cat->name, '풋살')) sports_soccer
          @elseif(str_contains($cat->name, '러닝')) directions_run
          @elseif(str_contains($cat->name, '뷰티')) spa
          @else category @endif
        </span>
      </div>
      <span class="text-lg font-bold text-text-main group-hover:text-primary">{{ $cat->name }}</span>
    </a>
    @endforeach
  </div>
</section>

<!-- Editor's Pick 섹션 (베스트 상품)  -->
<section class="bg-background-alt py-16">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="mb-10 flex items-center justify-between border-b border-gray-200 pb-4">
      <div>
        <h3 class="text-2xl font-bold tracking-tight text-text-main sm:text-3xl">Editor's Pick</h3>
        <p class="mt-2 text-sm text-text-muted">액티브한 당신을 위한 에디터 추천 #OOTD</p>
      </div>
      <a class="group flex items-center text-sm font-bold text-text-muted transition-colors hover:text-primary" href="{{ route('products.best') }}">
        전체보기 <span class="material-symbols-outlined ml-1 text-base transition-transform group-hover:translate-x-1">arrow_forward</span>
      </a>
    </div>
    <div class="grid grid-cols-2 gap-x-4 gap-y-8 lg:gap-x-6 lg:gap-y-10 lg:grid-cols-4">
      @foreach($editorsPicks as $product)
      <div class="group relative flex flex-col">
        <div class="relative aspect-[3/4] w-full overflow-hidden rounded-lg bg-gray-200">
          <a href="{{ route('product-detail', $product->slug) }}" class="block h-full w-full">
            <div class="h-full w-full bg-cover bg-center transition-transform duration-500 group-hover:scale-105"
              style="background-image: url('{{ $product->image_url ?? ($product->images->first()?->image_url ?? 'https://via.placeholder.com/400x533') }}');">
            </div>
          </a>
          <!-- 찜하기 버튼  -->
          <div class="absolute right-3 top-3 rounded-full bg-white/80 p-2 backdrop-blur-sm transition-colors hover:bg-primary hover:text-white cursor-pointer z-10 btn-toggle-wishlist" data-id="{{ $product->id }}">
            <span class="material-symbols-outlined block text-lg {{ $product->is_wishlisted ? 'filled text-red-500' : '' }}" style="{{ $product->is_wishlisted ? "font-variation-settings: 'FILL' 1" : "" }}">favorite</span>
          </div>
          @if($product->is_new)
          <div class="absolute bottom-3 left-3 flex gap-1">
            <span class="rounded bg-black/70 px-2 py-1 text-[10px] font-bold text-white backdrop-blur-sm">NEW SEASON</span>
          </div>
          @endif
        </div>
        <div class="mt-4 flex flex-1 flex-col">
          <a href="{{ route('product-detail', $product->slug) }}">
            <h4 class="text-base font-bold text-text-main">{{ $product->name }}</h4>
            <p class="text-xs text-text-muted">{{ $product->brief_description ?? ($product->category?->name ?? 'Premium Gear') }}</p>
          </a>
          <div class="mt-3 flex items-center justify-between">
            <div class="flex flex-col">
              @if($product->discount_rate > 0)
              <span class="text-xs text-red-500 font-bold">{{ $product->discount_rate }}%
                <span class="text-text-muted font-normal line-through ml-1">₩{{ number_format($product->price) }}</span></span>
              @endif
              <span class="text-lg font-bold text-text-main">₩{{ number_format($product->sale_price ?? $product->price) }}</span>
            </div>
          </div>
          <div class="mt-2 flex gap-1">
            <span class="inline-block rounded-sm bg-gray-100 px-1.5 py-0.5 text-[10px] text-gray-600">{{ $product->shipping_info }}</span>
            @if($product->stock_quantity <= 5 && $product->stock_quantity > 0)
            <span class="inline-block rounded-sm bg-red-50 px-1.5 py-0.5 text-[10px] text-red-500">품절임박</span>
            @endif
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<!-- 실시간 인기 급상승 섹션  -->
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
  <div class="mb-8 flex items-end justify-between">
    <div>
      <h3 class="text-2xl font-bold tracking-tight text-text-main sm:text-3xl">실시간 인기 급상승</h3>
      <p class="mt-2 text-sm text-text-muted">지금 가장 주목받는 아이템을 확인하세요.</p>
    </div>
    <div class="flex gap-2">
      <button class="btn-scroll-left flex size-9 items-center justify-center rounded-full border border-gray-200 bg-white transition-colors hover:border-primary hover:text-primary">
        <span class="material-symbols-outlined text-sm">arrow_back</span>
      </button>
      <button class="btn-scroll-right flex size-9 items-center justify-center rounded-full border border-gray-200 bg-white transition-colors hover:border-primary hover:text-primary">
        <span class="material-symbols-outlined text-sm">arrow_forward</span>
      </button>
    </div>
  </div>
  <div class="scrollbar-hide touch-scroll trending-container -mx-4 flex gap-6 overflow-x-auto px-4 pb-4 sm:mx-0 sm:px-0 scroll-smooth">
    @foreach($trendingProducts as $product)
    <div class="w-56 flex-none">
      <div class="group relative mb-3 aspect-square overflow-hidden rounded-xl bg-gray-100">
        <a href="{{ route('product-detail', $product->slug) }}">
          <div class="h-full w-full bg-cover bg-center transition-transform duration-500 group-hover:scale-105"
            style="background-image: url('{{ $product->image_url ?? ($product->images->first()?->image_url ?? 'https://via.placeholder.com/300x300') }}');">
          </div>
        </a>
      </div>
      <a href="{{ route('product-detail', $product->slug) }}">
        <h5 class="text-sm font-bold text-text-main truncate">{{ $product->name }}</h5>
        <p class="text-sm font-medium text-text-muted">₩{{ number_format($product->sale_price ?? $product->price) }}</p>
      </a>
    </div>
    @endforeach
  </div>
</section>
@endsection

@push('scripts')
<script>
  $(document).ready(function() {
    // 1. 장바구니 담기  (AJAX 연동 준비)
    $(document).on('click', '.btn-add-cart', function(e) {
      e.preventDefault();
      const productId = $(this).data('id');
      
      // 나중에 실제 장바구니 API가 생기면 여기에 연결할 거야! 
      // 지금은 일단 알림만 띄워줄게~ 
      showToast('상품을 장바구니에 담았어요! ', 'shopping_cart');
      
      const $cartBadge = $('.header-cart-count'); // 레이아웃에 정의된 배지 클래스 확인 필요!
      if($cartBadge.length) {
          let count = parseInt($cartBadge.first().text()) || 0;
          $cartBadge.text(count + 1).removeClass('hidden').addClass('flex animate-bounce-subtle');
          setTimeout(() => $cartBadge.removeClass('animate-bounce-subtle'), 1000);
      }
    });

    // 2. 실시간 인기 급상승 섹션 가로 스크롤 제어  (아이템 1개씩 이동)
    const containerEl = document.querySelector('.trending-container');
    
    // 이동할 너비 계산 (아이템 너비 56rem(224px) + gap 6rem(24px) = 248px 추정, 좀 더 넉넉하게 250px)
    // 혹은 동적으로 계산
    function getScrollAmount() {
        const item = containerEl.querySelector('.w-56');
        if (item) {
            // 아이템 너비 + 부모의 gap (보통 24px)
            return item.offsetWidth + 24; 
        }
        return 248; // 기본값
    }

    $('.btn-scroll-right').on('click', function() {
        containerEl.scrollBy({ left: getScrollAmount(), behavior: 'smooth' });
    });

    $('.btn-scroll-left').on('click', function() {
        containerEl.scrollBy({ left: -getScrollAmount(), behavior: 'smooth' });
    });

    // 3. 마우스 드래그 스크롤 기능 ️ (가장 최신의 드래그 로직 )
    const container = document.querySelector('.trending-container');
    let isDown = false;
    let startX;
    let scrollLeft;
    let isDragging = false;
    const DRAG_THRESHOLD = 5; // 드래그 판정 기준 픽셀 
    let initialX;

    // 기본 이미지/링크 드래그 팡지
    $(container).on('mousedown', 'a, img', function(e) {
        e.preventDefault();
    });

    $(container).on('mousedown', function(e) {
        isDown = true;
        isDragging = false;
        container.classList.add('active');
        container.style.cursor = 'grabbing';
        
        startX = e.pageX - container.offsetLeft;
        scrollLeft = container.scrollLeft;
        initialX = e.pageX;
    });

    $(container).on('mouseleave', function() {
        if(!isDown) return;
        isDown = false;
        container.classList.remove('active');
        container.style.cursor = 'grab';
        
        setTimeout(() => { isDragging = false; }, 50);
    });

    $(container).on('mouseup', function(e) {
        isDown = false;
        container.classList.remove('active');
        container.style.cursor = 'grab';
        
        const finalX = e.pageX;
        if (Math.abs(finalX - initialX) > DRAG_THRESHOLD) {
            isDragging = true;
        }

        setTimeout(() => { isDragging = false; }, 50);
    });

    $(container).on('mousemove', function(e) {
        if (!isDown) return;
        
        const x = e.pageX - container.offsetLeft;
        const walk = (x - startX); // 스크롤 감도 조절 (기본 1배)
        
        if (Math.abs(walk) > DRAG_THRESHOLD) {
            isDragging = true;
            e.preventDefault(); // 스크롤 중 선택/클릭 이벤트 방지
            
            // 드래그 방향에 맞춰 스크롤 부드럽게 이동
            container.scrollLeft = scrollLeft - walk;
        }
    });

    // 드래그 중일 때는 링크 클릭 방지 로직 (확실하게!) ️
    $(container).on('click', 'a', function(e) {
        if (isDragging) {
            e.preventDefault();
            e.stopImmediatePropagation();
        }
    });
  });
</script>
<style>
  .scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
  }
  .scrollbar-hide::-webkit-scrollbar {
    display: none;
  }
  .touch-scroll {
    -webkit-overflow-scrolling: touch;
  }
  .trending-container {
    cursor: grab;
    user-select: none;
  }
  .trending-container.active {
    cursor: grabbing;
    scroll-behavior: auto; /* 드래그 중에는 부드러운 스크롤 잠시 끄기!  */
  }
</style>
@endpush
