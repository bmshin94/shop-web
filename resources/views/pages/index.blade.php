@extends('layouts.app')

@section('title', '홈 - Active Women\'s Premium Store')

@section('content')
<!-- 메인 히어로 배너 섹션 (Swiper) -->
<section class="relative mt-6 w-full overflow-hidden">
  <div class="swiper hero-swiper px-4 py-4">
    <div class="swiper-wrapper">
      @forelse($heroProducts as $product)
      <div class="swiper-slide w-full transition-transform duration-500">
        <div class="group relative overflow-hidden rounded-3xl bg-gray-100 aspect-[3/4] w-full h-full shadow-md transition-all duration-500">
          <a href="{{ route('product-detail', $product->slug) }}" class="block w-full h-full">
            @php
              $imgSrc = $product->images->first()?->image_url;
              if (empty($imgSrc)) $imgSrc = $product->image_url;
              $hasImage = !empty($imgSrc);
              // 각 슬라이드마다 다른 그라데이션 색상을 적용
              $gradients = [
                'from-rose-300 to-pink-500',
                'from-violet-300 to-purple-500',
                'from-sky-300 to-blue-500',
                'from-emerald-300 to-green-500',
                'from-amber-300 to-orange-500',
                'from-teal-300 to-cyan-500',
                'from-fuchsia-300 to-pink-600',
                'from-indigo-300 to-blue-600',
                'from-lime-300 to-emerald-500',
                'from-red-300 to-rose-500',
              ];
              $gradient = $gradients[$loop->index % count($gradients)];
            @endphp
            @if($hasImage)
              <img src="{{ $imgSrc }}"
                alt="{{ $product->name }}"
                onerror="this.style.display='none';this.nextElementSibling.style.display='flex';"
                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" />
              <!-- 이미지 로딩 실패 시 표시될 그라데이션 fallback -->
              <div class="h-full w-full bg-gradient-to-br {{ $gradient }} items-center justify-center p-6 transition-transform duration-700 group-hover:scale-110" style="display:none;">
                <span class="text-white text-lg sm:text-xl font-extrabold text-center drop-shadow-md break-keep leading-relaxed">{{ $product->name }}</span>
              </div>
            @else
              <!-- 이미지 미등록 시 그라데이션 배경 + 상품명 표시 -->
              <div class="h-full w-full bg-gradient-to-br {{ $gradient }} flex items-center justify-center p-6 transition-transform duration-700 group-hover:scale-110">
                <span class="text-white text-lg sm:text-xl font-extrabold text-center drop-shadow-md break-keep leading-relaxed">{{ $product->name }}</span>
              </div>
            @endif
          </a>
          
          <!-- 그라데이션 오버레이 (텍스트 가독성 확보, 호버 시 노출) -->
          <div class="absolute inset-0 z-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>

          <!-- 상품 정보 (호버 시 아래에서 위로 등장) -->
          <div class="absolute bottom-0 left-0 right-0 z-10 p-6 sm:p-8 translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500 pointer-events-none">
            <h3 class="text-xl sm:text-2xl font-extrabold text-white mb-2 drop-shadow-md truncate">
              {{ $product->name }}
            </h3>
            <div class="flex items-end gap-3">
              <span class="text-lg sm:text-xl font-black text-primary drop-shadow-md">
                ₩{{ number_format($product->sale_price ?? $product->price) }}
              </span>
              @if($product->discount_rate > 0)
              <span class="text-sm font-bold text-gray-300 line-through">
                ₩{{ number_format($product->price) }}
              </span>
              @endif
            </div>
          </div>
          
        </div>
      </div>
      @empty
      <!-- 데이터가 없을 때 기본 슬라이드 -->
      <div class="swiper-slide w-full transition-transform duration-500">
        <div class="relative overflow-hidden rounded-3xl bg-black aspect-[3/4] w-full h-full shadow-lg">
          <div class="relative z-10 flex h-full flex-col items-center justify-center px-4 py-20 text-center sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl">상품을 <br/> <span class="text-primary">준비 중입니다</span></h2>
          </div>
        </div>
      </div>
      @endforelse
    </div>
    
    <!-- Custom Pagination Controls -->
    <div class="flex items-center justify-center gap-6 mt-4 sm:mt-6">
      <button class="hero-prev flex size-10 items-center justify-center rounded-full bg-white border border-gray-200 text-text-main hover:border-primary hover:text-primary transition-all active:scale-95 shadow-sm">
        <span class="material-symbols-outlined">chevron_left</span>
      </button>
      
      <div class="flex items-center gap-4">
        <div class="text-sm font-black text-text-main tracking-widest"><span class="hero-current-slide">1</span> <span class="text-gray-400 font-medium mx-1">/</span> <span class="hero-total-slides whitespace-nowrap">1</span></div>
        <div class="w-24 sm:w-48 h-1.5 bg-gray-200 rounded-full overflow-hidden relative">
          <div class="hero-progress-bar h-full bg-primary absolute left-0 top-0 transition-all duration-300 ease-out" style="width: 0%"></div>
        </div>
      </div>

      <button class="hero-next flex size-10 items-center justify-center rounded-full bg-white border border-gray-200 text-text-main hover:border-primary hover:text-primary transition-all active:scale-95 shadow-sm">
        <span class="material-symbols-outlined">chevron_right</span>
      </button>
    </div>
  </div>
</section>

<!-- 카테고리 퀵 메뉴 섹션 -->
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
  <div class="grid grid-cols-2 gap-4 md:grid-cols-4 lg:gap-8">
    @foreach($topCategories as $cat)
    <a class="group flex flex-col items-center gap-4 rounded-xl border border-transparent bg-background-alt p-6 transition-all hover:border-primary/20 hover:bg-primary-light hover:shadow-md active:scale-[0.98]"
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

<!-- Editor's Pick 섹션 (베스트 상품) -->
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
        <x-product-card :product="$product" />
      @endforeach
    </div>
  </div>
</section>

<!-- 실시간 인기 급상승 섹션 -->
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
  <div class="mb-8 flex items-end justify-between">
    <div>
      <h3 class="text-2xl font-bold tracking-tight text-text-main sm:text-3xl">실시간 인기 급상승</h3>
      <p class="mt-2 text-sm text-text-muted">지금 가장 주목받는 아이템을 확인하세요.</p>
    </div>
    <div class="flex gap-2">
      <button class="btn-scroll-left flex size-9 items-center justify-center rounded-full border border-gray-200 bg-white transition-all hover:border-primary hover:text-primary active:scale-95">
        <span class="material-symbols-outlined text-sm">arrow_back</span>
      </button>
      <button class="btn-scroll-right flex size-9 items-center justify-center rounded-full border border-gray-200 bg-white transition-all hover:border-primary hover:text-primary active:scale-95">
        <span class="material-symbols-outlined text-sm">arrow_forward</span>
      </button>
    </div>
  </div>
  <div class="scrollbar-hide touch-scroll trending-container -mx-4 flex gap-6 overflow-x-auto px-4 pb-4 sm:mx-0 sm:px-0 scroll-smooth">
    @foreach($trendingProducts as $product)
      <x-product-card :product="$product" class="w-56 flex-none" />
    @endforeach
  </div>
</section>
@endsection

@push('scripts')
<script>
  $(document).ready(function() {
    // 0. 메인 히어로 Swiper 초기화 (상품 10개, 5열 직노출)
    const heroSwiper = new Swiper('.hero-swiper', {
      slidesPerView: 2, // 모바일 기본
      centeredSlides: true,
      spaceBetween: 10,
      loop: true,
      speed: 800,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true, // 마우스 오버 시 일시정지, 아웃 시 자동 재개
      },
      navigation: {
        nextEl: '.hero-next',
        prevEl: '.hero-prev',
      },
      breakpoints: {
        640: {
          slidesPerView: 3,
          spaceBetween: 14,
        },
        1024: {
          slidesPerView: 5, // PC 화면에서는 5줄!
          spaceBetween: 18,
        }
      },
      on: {
        init: function () {
          updateHeroProgress(this);
        },
        slideChange: function () {
          updateHeroProgress(this);
        }
      }
    });

    function updateHeroProgress(swiper) {
      if(!swiper.slides) return;
      const total = document.querySelectorAll('.hero-swiper .swiper-slide:not(.swiper-slide-duplicate)').length;
      if (total === 0) return;
      
      let current = swiper.realIndex + 1;
      
      $('.hero-current-slide').text(current);
      $('.hero-total-slides').text(total);
      
      const progress = (current / total) * 100;
      $('.hero-progress-bar').css('width', progress + '%');
    }

    // 1. 장바구니 담기 (AJAX 연동 준비)
    $(document).on('click', '.btn-add-cart', function(e) {
      e.preventDefault();
      const productId = $(this).data('id');
      
      // 지금은 일단 알림만 띄워줄게!
      showToast('상품을 장바구니에 담았어요! ', 'shopping_cart');
      
      const $cartBadge = $('.header-cart-count'); // 레이아웃에 정의된 배지 클래스 확인 필요!
      if($cartBadge.length) {
          let count = parseInt($cartBadge.first().text()) || 0;
          $cartBadge.text(count + 1).removeClass('hidden').addClass('flex animate-bounce-subtle');
          setTimeout(() => $cartBadge.removeClass('animate-bounce-subtle'), 1000);
      }
    });

    // 2. 실시간 인기 급상승 섹션 가로 스크롤 제어 (아이템 1개씩 이동)
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

    // 3. 마우스 드래그 스크롤 기능 (가장 최신의 드래그 로직)
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

    // 드래그 중일 때는 링크 클릭 방지 로직 (확실하게!)
    $(container).on('click', 'a', function(e) {
        if (isDragging) {
            e.preventDefault();
            e.stopImmediatePropagation();
        }
    });
  });
</script>
<style>
  /* Hero Swiper Custom Styles */
  .hero-swiper {
    overflow: visible; /* scale 확대 시 잘림 방지 */
  }
  .hero-swiper .swiper-slide {
    transition: all 0.7s cubic-bezier(0.25, 1, 0.5, 1);
    transform: scale(0.88);
    opacity: 0.5;
  }
  .hero-swiper .swiper-slide-active {
    transform: scale(1); /* 활성화 슬라이드만 원래 크기 (짤림 없음!) */
    opacity: 1;
    z-index: 10;
  }
  .hero-swiper .swiper-slide-active > div {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); /* 활성화된 슬라이드 그림자 강화 */
  }

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
    scroll-behavior: auto; /* 드래그 중에는 부드러운 스크롤 잠시 끄기! */
  }
</style>
@endpush
