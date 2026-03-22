@extends('layouts.app')

@section('title', '홈 - Active Women\'s Premium Store')

@push('styles')
<style>
    /* 스크롤 시 나타나는 애니메이션 효과 */
    .reveal { opacity: 0; transform: translateY(30px); transition: all 0.8s cubic-bezier(0.22, 1, 0.36, 1); }
    .reveal.active { opacity: 1; transform: translateY(0); }

    /* Hero Swiper Custom Styles */
    .hero-swiper { overflow: visible !important; }
    .hero-swiper .swiper-slide { transition: all 0.7s cubic-bezier(0.25, 1, 0.5, 1); transform: scale(0.85); opacity: 0.4; filter: blur(2px); }
    .hero-swiper .swiper-slide-active { transform: scale(1.02); opacity: 1; z-index: 20; filter: blur(0); }
    .hero-swiper .swiper-slide-active > div { box-shadow: 0 30px 60px -12px rgba(236, 55, 19, 0.15); }
    
    /* 상품 정보 애니메이션 */
    .hero-info { transform: translateY(15px); opacity: 0; transition: all 0.5s ease 0.3s; }
    .swiper-slide-active .hero-info { transform: translateY(0); opacity: 1; }

    /* 섹션 타이틀 디자인 */
    .section-title::before { content: ''; position: absolute; left: 0; top: -12px; width: 24px; height: 3px; background-color: #ec3713; border-radius: 2px; }

    .scrollbar-hide::-webkit-scrollbar { display: none; }
</style>
@endpush

@section('content')
<!-- 메인 히어로 배너 섹션 -->
<section class="relative mt-4 w-full overflow-hidden opacity-0 transition-opacity duration-700" id="hero-section">
  <div class="swiper hero-swiper px-4 py-8">
    <div class="swiper-wrapper">
      @forelse($heroProducts as $product)
      <div class="swiper-slide w-full">
        <div class="group relative overflow-hidden rounded-[2.5rem] bg-gray-50 aspect-[4/5] w-full shadow-lg transition-all duration-500">
          <a href="{{ route('product-detail', $product->slug) }}" class="block w-full h-full">
            @php
              $imgSrc = $product->images->first()?->image_url ?? $product->image_url;
              $hasImage = !empty($imgSrc);
              $gradients = ['from-rose-400 to-pink-600', 'from-violet-400 to-purple-600', 'from-sky-400 to-blue-600', 'from-emerald-400 to-green-600', 'from-amber-400 to-orange-600'];
              $gradient = $gradients[$loop->index % count($gradients)];
            @endphp
            
            @if($hasImage)
              <img src="{{ $imgSrc }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition-transform duration-1000 group-hover:scale-110" />
            @else
              <div class="h-full w-full bg-gradient-to-br {{ $gradient }} flex items-center justify-center p-8">
                <span class="text-white text-2xl font-black text-center drop-shadow-xl break-keep">{{ $product->name }}</span>
              </div>
            @endif
            
            <!-- 오버레이 및 정보 -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-60 transition-opacity group-hover:opacity-80"></div>
            
            <div class="hero-info absolute bottom-0 left-0 right-0 p-8 sm:p-10 z-30">
                <div class="mb-3 flex gap-2">
                    <span class="px-3 py-1 bg-primary text-[10px] font-black text-white rounded-full uppercase tracking-widest">Premium Pick</span>
                </div>
                <h3 class="text-2xl sm:text-3xl font-black text-white mb-3 drop-shadow-md line-clamp-2 leading-tight">
                    {{ $product->name }}
                </h3>
                <div class="flex items-center gap-4">
                    <span class="text-xl sm:text-2xl font-black text-white">
                        ₩{{ number_format($product->sale_price ?? $product->price) }}
                    </span>
                    @if($product->discount_rate > 0)
                    <span class="text-sm font-bold text-white/50 line-through">
                        ₩{{ number_format($product->price) }}
                    </span>
                    @endif
                </div>
            </div>
          </a>
        </div>
      </div>
      @empty
      <div class="swiper-slide w-full">
        <div class="relative overflow-hidden rounded-[2.5rem] bg-black aspect-[3/4] flex items-center justify-center">
            <h2 class="text-3xl font-black text-white">COMING SOON</h2>
        </div>
      </div>
      @endforelse
    </div>
    
    <!-- Navigation & Progress -->
    <div class="flex items-center justify-center gap-8 mt-12">
      <button class="hero-prev flex size-12 items-center justify-center rounded-2xl bg-white text-text-main shadow-sm hover:bg-primary hover:text-white transition-all active:scale-90">
        <span class="material-symbols-outlined">west</span>
      </button>
      
      <div class="flex flex-col items-center gap-3">
        <div class="w-32 sm:w-64 h-1 bg-gray-100 rounded-full overflow-hidden relative">
          <div class="hero-progress-bar h-full bg-primary absolute left-0 top-0 transition-all duration-500 ease-out" style="width: 0%"></div>
        </div>
        <div class="text-[10px] font-black text-text-main tracking-[0.3em] uppercase opacity-40">
            <span class="hero-current-slide">01</span> / <span class="hero-total-slides">00</span>
        </div>
      </div>

      <button class="hero-next flex size-12 items-center justify-center rounded-2xl bg-white text-text-main shadow-sm hover:bg-primary hover:text-white transition-all active:scale-90">
        <span class="material-symbols-outlined">east</span>
      </button>
    </div>
  </div>
</section>

<!-- 카테고리 퀵 메뉴 섹션 -->
<section class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8 reveal">
  <div class="grid grid-cols-2 gap-6 md:grid-cols-4 lg:gap-10">
    @foreach($topCategories as $cat)
    <a class="group relative flex flex-col items-center gap-6 rounded-[2.5rem] bg-white p-10 transition-all hover:shadow-2xl hover:-translate-y-2 active:scale-95 border border-gray-50"
      href="{{ route('product-list', ['category' => $cat->slug]) }}">
      <div class="relative">
          <div class="absolute inset-0 bg-primary/10 rounded-full blur-xl scale-0 group-hover:scale-150 transition-transform duration-500"></div>
          <div class="relative flex size-20 items-center justify-center rounded-3xl bg-gray-50 text-text-main shadow-inner transition-all group-hover:bg-primary group-hover:text-white group-hover:rotate-[10deg]">
            <span class="material-symbols-outlined text-4xl">
              {{ $cat->icon ?? 'category' }}
            </span>
          </div>
      </div>
      <div class="text-center">
          <span class="block text-lg font-black text-text-main mb-1">{{ $cat->name }}</span>
          <span class="text-[10px] font-bold text-text-muted uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">둘러보기</span>
      </div>
    </a>
    @endforeach
  </div>
</section>

<!-- Editor's Pick 섹션 -->
<section class="bg-white py-24 reveal">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="mb-16 flex items-end justify-between">
      <div class="relative">
        <h3 class="section-title text-3xl font-black tracking-tight text-text-main sm:text-4xl">Editor's Pick</h3>
        <p class="mt-4 text-base text-text-muted font-medium">에디터가 제안하는 이번 시즌 머스트 해브 아이템</p>
      </div>
      <a class="group flex items-center gap-2 text-xs font-black text-text-main uppercase tracking-widest hover:text-primary transition-colors" href="{{ route('products.best') }}">
        View All <span class="material-symbols-outlined text-sm transition-transform group-hover:translate-x-2">east</span>
      </a>
    </div>
    <div class="grid grid-cols-2 gap-x-6 gap-y-12 lg:gap-x-8 lg:gap-y-16 lg:grid-cols-4">
      @foreach($editorsPicks as $product)
        <x-product-card :product="$product" />
      @endforeach
    </div>
  </div>
</section>

<!-- 실시간 인기 급상승 섹션 -->
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 reveal">
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
    // 0. Scroll Reveal Logic
    const reveal = () => {
        const reveals = document.querySelectorAll('.reveal');
        reveals.forEach(el => {
            const windowHeight = window.innerHeight;
            const elementTop = el.getBoundingClientRect().top;
            const elementVisible = 150;
            if (elementTop < windowHeight - elementVisible) {
                el.classList.add('active');
            }
        });
    };
    window.addEventListener('scroll', reveal);
    reveal();

    // 1. Hero Swiper
    const heroSwiper = new Swiper('.hero-swiper', {
      slidesPerView: 1.4,
      centeredSlides: true,
      spaceBetween: 20,
      loop: true,
      speed: 1000,
      autoplay: { delay: 5000, disableOnInteraction: false },
      navigation: { nextEl: '.hero-next', prevEl: '.hero-prev' },
      breakpoints: {
        640: { slidesPerView: 2, spaceBetween: 30 },
        1024: { slidesPerView: 3, spaceBetween: 50 }
      },
      on: {
        init: function () {
          updateHeroProgress(this);
          setTimeout(() => $('#hero-section').removeClass('opacity-0'), 100);
        },
        slideChange: function () { updateHeroProgress(this); }
      }
    });

    function updateHeroProgress(swiper) {
      const total = document.querySelectorAll('.hero-swiper .swiper-slide:not(.swiper-slide-duplicate)').length;
      if (total === 0) return;
      let current = swiper.realIndex + 1;
      $('.hero-current-slide').text(current.toString().padStart(2, '0'));
      $('.hero-total-slides').text(total.toString().padStart(2, '0'));
      $('.hero-progress-bar').css('width', (current / total) * 100 + '%');
    }

    // 2. 실시간 인기 급상승 섹션 가로 스크롤 및 드래그 제어
    const container = document.querySelector('.trending-container');
    
    function getScrollAmount() {
        const item = container.querySelector('.w-56');
        return item ? item.offsetWidth + 24 : 248;
    }

    $('.btn-scroll-right').on('click', () => container.scrollBy({ left: getScrollAmount(), behavior: 'smooth' }));
    $('.btn-scroll-left').on('click', () => container.scrollBy({ left: -getScrollAmount(), behavior: 'smooth' }));

    // 마우스 드래그 스크롤 기능
    let isDown = false;
    let startX;
    let scrollLeft;
    let isDragging = false;
    const DRAG_THRESHOLD = 5;
    let initialX;

    $(container).on('mousedown', 'a, img', (e) => e.preventDefault());

    $(container).on('mousedown', function(e) {
        isDown = true;
        isDragging = false;
        container.classList.add('active');
        container.style.cursor = 'grabbing';
        startX = e.pageX - container.offsetLeft;
        scrollLeft = container.scrollLeft;
        initialX = e.pageX;
    });

    $(container).on('mouseleave mouseup', function() {
        if(!isDown) return;
        isDown = false;
        container.classList.remove('active');
        container.style.cursor = 'grab';
        setTimeout(() => { isDragging = false; }, 50);
    });

    $(container).on('mousemove', function(e) {
        if (!isDown) return;
        const x = e.pageX - container.offsetLeft;
        const walk = (x - startX);
        if (Math.abs(walk) > DRAG_THRESHOLD) {
            isDragging = true;
            e.preventDefault();
            container.scrollLeft = scrollLeft - walk;
        }
    });

    $(container).on('click', 'a', function(e) {
        if (isDragging) {
            e.preventDefault();
            e.stopImmediatePropagation();
        }
    });
  });
</script>
<style>
  .trending-container { cursor: grab; user-select: none; }
  .trending-container.active { cursor: grabbing; scroll-behavior: auto; }
</style>
@endpush
