@extends('layouts.app')

@section('title', '기획전 - Active Women\'s Premium Store')

@push('styles')
<style>
    .aspect-banner { aspect-ratio: 21 / 9; }
    @media (max-width: 640px) { .aspect-banner { aspect-ratio: 16 / 9; } }

    /* 1. 모바일 터치 스크롤 유지를 위한 overflow-x-auto 및 스크롤바 숨기기 ✨ */
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

    /* 2. 드래그 인터랙션을 위한 커서 및 스타일 설정 😊 */
    #exhibit-nav {
        cursor: grab;
        user-select: none;
        scroll-behavior: smooth; /* 기본은 부드럽게! */
    }
    #exhibit-nav.active {
        cursor: grabbing;
        scroll-behavior: auto; /* 드래그 중엔 즉각 반응하도록 auto로 변경! 🎯 */
    }
</style>
@endpush

@section('content')
<main class="flex-1 w-full bg-white pb-20">
    <!-- Page Title -->
    <div class="py-12 border-b border-gray-100 bg-background-alt">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center pt-8">
            <h2 class="text-4xl font-extrabold text-text-main tracking-tight mb-4 uppercase">Season Exhibition</h2>
            <p class="text-text-muted text-base">Active Women이 큐레이션한 스페셜 테마 콜렉션 </p>
        </div>
    </div>

    <!-- Sticky Nav (핵심: 하이브리드 드래그 스크롤 🚀) -->
    <div class="sticky top-[110px] z-40 bg-white/95 backdrop-blur-md border-b border-gray-200 shadow-sm overflow-hidden">
        <div class="mx-auto max-w-7xl">
            {{-- overflow-x-auto를 통해 모바일 터치 스크롤은 그대로 작동! 📱 --}}
            <nav id="exhibit-nav" class="flex gap-3 overflow-x-auto scrollbar-hide touch-scroll py-4 px-4 sm:px-6 lg:px-8 text-sm font-bold flex-nowrap items-center w-full relative">
                @if(isset($exhibitions) && $exhibitions->count() > 0)
                    @foreach($exhibitions as $index => $exhibition)
                    <a href="#exhibit-{{ $exhibition->id }}" 
                       class="nav-item px-5 py-2.5 {{ $index === 0 ? 'bg-text-main text-white border-text-main' : 'bg-white text-text-muted border-gray-200' }} rounded-full whitespace-nowrap transition-all border shadow-sm active:scale-95 inline-block">
                        {{ $exhibition->title }}
                    </a>
                    @endforeach
                @endif
            </nav>
        </div>
    </div>

    @if(isset($exhibitions) && $exhibitions->count() > 0)
        @foreach($exhibitions as $index => $exhibition)
        <!-- Exhibition {{ $index + 1 }} -->
        <section id="exhibit-{{ $exhibition->id }}" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16 scroll-mt-[180px] border-b border-gray-50 last:border-0">
            {{-- 진행예정 상태일 때의 스타일 처리 ✨ --}}
            @php
                $isUpcoming = $exhibition->status === '진행예정';
            @endphp

            <div class="relative aspect-banner rounded-3xl overflow-hidden mb-10 group {{ $isUpcoming ? '' : 'cursor-pointer' }} shadow-lg">
                <img src="{{ $exhibition->banner_image_url ?? 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?q=80&w=2070' }}" 
                     class="w-full h-full object-cover transition-transform duration-700 {{ $isUpcoming ? 'grayscale' : 'group-hover:scale-105' }}" />
                
                <div class="absolute inset-0 bg-black/30 flex flex-col justify-center px-10 md:px-20 text-white">
                    @if($isUpcoming)
                        <div class="mb-4 inline-flex items-center gap-2 px-4 py-1.5 bg-primary text-white rounded-full w-fit animate-pulse">
                            <span class="material-symbols-outlined text-[16px]">schedule</span>
                            <span class="text-[11px] font-black uppercase tracking-widest">Coming Soon</span>
                        </div>
                    @else
                        <span class="text-xs font-bold mb-4 uppercase opacity-80">Collection {{ sprintf('%02d', $exhibition->id) }}</span>
                    @endif

                    <h3 class="text-3xl md:text-5xl font-extrabold mb-6 leading-tight">{!! nl2br(e($exhibition->title)) !!}</h3>
                    <p class="max-w-md text-sm md:text-base mb-8 opacity-90 leading-relaxed break-keep">{{ $exhibition->summary }}</p>
                    
                    <div>
                        @if($isUpcoming)
                            <div class="inline-block bg-white/20 backdrop-blur-md text-white font-bold px-8 py-3 rounded-full cursor-default border border-white/30">
                                {{ optional($exhibition->start_at)->format('m월 d일') }} 오픈 예정 ✨
                            </div>
                        @else
                            <a href="{{ route('exhibition.show', $exhibition->slug) }}" class="inline-block bg-white text-text-main font-bold px-8 py-3 rounded-full hover:bg-primary hover:text-white transition-all">전체보기</a>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Product Mini Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 {{ $isUpcoming ? 'opacity-50 grayscale-[0.5] pointer-events-none' : '' }}">
                @foreach($exhibition->products as $product)
                <a href="{{ route('product-detail', $product->slug) }}" class="group cursor-pointer">
                    <div class="aspect-[3/4] rounded-2xl bg-gray-100 overflow-hidden mb-4 shadow-sm border border-gray-50">
                        <img src="{{ $product->images->first()?->image_url ?? 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=500' }}" class="w-full h-full object-cover group-hover:scale-105 transition-all" />
                    </div>
                    <h4 class="font-bold text-sm text-text-main line-clamp-1">{{ $product->name }}</h4>
                    <p class="text-sm font-bold text-primary mt-1">₩{{ number_format($product->sale_price ?? $product->price) }}</p>
                </a>
                @endforeach
            </div>
        </section>
        @endforeach

        <!-- Pagination -->
        <div class="mt-20 flex justify-center pb-20">
            {{ $exhibitions->links() }}
        </div>
    @else
        <section class="py-20 text-center">
            <p class="text-text-muted">현재 진행 중인 기획전이 없습니다. 😊</p>
        </section>
    @endif
</main>
@endsection

@push('scripts')
<script>
    // --- 드래그 스크롤 로직 (클릭+드래그로만 좌우 이동) ---
    const nav = document.querySelector('#exhibit-nav');
    let isDown = false;
    let startX;
    let scrollLeft;
    let isDragging = false;
    const DRAG_THRESHOLD = 5; // 드래그 판정 임계값 (5px)

    // 0. 마우스 휠/트랙패드로 호버 시 스크롤되는 것을 차단
    nav.addEventListener('wheel', (e) => {
        e.preventDefault();
    }, { passive: false });

    // 1. mousedown 시 시작 좌표와 스크롤 위치 기록
    nav.addEventListener('mousedown', (e) => {
        isDown = true;
        isDragging = false;
        nav.classList.add('active');

        startX = e.pageX - nav.offsetLeft;
        scrollLeft = nav.scrollLeft;
        // 앵커 태그의 네이티브 드래그 동작 방지
        e.preventDefault();
    });

    // 2. mousemove에서 이동 거리만큼 scrollLeft 갱신
    nav.addEventListener('mousemove', (e) => {
        if (!isDown) return;

        const x = e.pageX - nav.offsetLeft;
        const walk = (x - startX); // 1:1 드래그 트래킹

        // 이동 거리가 임계값을 넘으면 드래그 상태로 전환
        if (Math.abs(walk) > DRAG_THRESHOLD) {
            isDragging = true;
            e.preventDefault();
            nav.scrollLeft = scrollLeft - walk;
        }
    });

    // 3. 드래그 종료 시 상태 초기화
    function endDragging() {
        isDown = false;
        nav.classList.remove('active');
    }

    nav.addEventListener('mouseup', endDragging);
    nav.addEventListener('mouseleave', endDragging);

    // 4. 드래그 후 탭 클릭 오작동 방지 처리 🎤
    nav.addEventListener('click', (e) => {
        if (isDragging) {
            e.preventDefault();
            e.stopImmediatePropagation();
        }
    }, true);

    // 5. 탭 클릭 시 부드러운 이동 (드래그가 아닐 때만 작동하도록 기본 UI 유지) ✨
    document.querySelectorAll('.nav-item').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            if (isDragging) return; // 드래그 중이면 이동 방지! 😊

            e.preventDefault();
            const targetId = this.getAttribute('href');
            const target = document.querySelector(targetId);
            
            if (target) {
                const y = target.getBoundingClientRect().top + window.pageYOffset - 170;
                window.scrollTo({ top: y, behavior: 'smooth' });

                // 활성 탭 스타일 전환
                document.querySelectorAll('.nav-item').forEach(item => {
                    item.classList.remove('bg-text-main', 'text-white', 'border-text-main');
                    item.classList.add('bg-white', 'text-text-muted', 'border-gray-200');
                });
                this.classList.remove('bg-white', 'text-text-muted', 'border-gray-200');
                this.classList.add('bg-text-main', 'text-white', 'border-text-main');
            }
        });
    });
</script>
@endpush
