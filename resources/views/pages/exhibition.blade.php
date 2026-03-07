@extends('layouts.app')

@section('title', '기획전 - Active Women\'s Premium Store')

@push('styles')
<style>
    .aspect-banner { aspect-ratio: 21 / 9; }
    @media (max-width: 640px) { .aspect-banner { aspect-ratio: 16 / 9; } }
</style>
@endpush

@section('content')
<main class="flex-1 w-full bg-white pb-20">
    <!-- Page Title -->
    <div class="py-12 border-b border-gray-100 bg-background-alt">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center pt-8">
            <h2 class="text-4xl font-extrabold text-text-main tracking-tight mb-4 uppercase">Season Exhibition</h2>
            <p class="text-text-muted text-base">Active Women이 큐레이션한 스페셜 테마 콜렉션 ✨</p>
        </div>
    </div>

    <!-- Sticky Nav -->
    <div class="sticky top-[110px] z-40 bg-white/95 backdrop-blur-md border-b border-gray-200 shadow-sm">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <nav id="exhibit-nav" class="flex gap-4 overflow-x-auto scrollbar-hide py-4 text-sm font-bold">
                <a href="#exhibit-1" class="nav-item px-5 py-2 bg-text-main text-white rounded-full whitespace-nowrap transition-all border border-text-main">24 SPRING 애슬레저 룩</a>
                <a href="#exhibit-2" class="nav-item px-5 py-2 bg-white text-text-muted border border-gray-200 rounded-full whitespace-nowrap transition-all hover:text-text-main">홈트 필수 기어 10선</a>
                <a href="#exhibit-3" class="nav-item px-5 py-2 bg-white text-text-muted border border-gray-200 rounded-full whitespace-nowrap transition-all hover:text-text-main">주말 하이킹 기획전</a>
            </nav>
        </div>
    </div>

    <!-- Exhibition 1 -->
    <section id="exhibit-1" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16 scroll-mt-[180px]">
        <div class="relative aspect-banner rounded-3xl overflow-hidden mb-10 group cursor-pointer shadow-lg">
            <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?q=80&w=2070" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />
            <div class="absolute inset-0 bg-black/30 flex flex-col justify-center px-10 md:px-20 text-white">
                <span class="text-xs font-bold mb-4 tracking-widest uppercase opacity-80">Collection 01</span>
                <h3 class="text-3xl md:text-5xl font-extrabold mb-6 leading-tight">24 SPRING<br />ATHLEISURE LOOK</h3>
                <p class="max-w-md text-sm md:text-base mb-8 opacity-90 leading-relaxed break-keep">일상과 운동의 경계를 허무는 세련된 스타일. 지금 가장 사랑받는 애슬레저 아이템을 만나보세요.</p>
                <div>
                    <button class="bg-white text-text-main font-bold px-8 py-3 rounded-full hover:bg-primary hover:text-white transition-all">전체보기</button>
                </div>
            </div>
        </div>
        <!-- Product Mini Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @for ($i = 1; $i <= 4; $i++)
            <div class="group cursor-pointer">
                <div class="aspect-[3/4] rounded-2xl bg-gray-100 overflow-hidden mb-4 shadow-sm border border-gray-50">
                    <img src="https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=500" class="w-full h-full object-cover group-hover:scale-105 transition-all" />
                </div>
                <h4 class="font-bold text-sm text-text-main">스페셜 아이템 #{{ $i }}</h4>
                <p class="text-sm font-bold text-primary mt-1">₩49,000</p>
            </div>
            @endfor
        </div>
    </section>

    <!-- Exhibition 2 -->
    <section id="exhibit-2" class="bg-gray-50 py-20 scroll-mt-[180px]">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <div class="flex-1">
                    <span class="text-xs font-bold text-primary mb-4 block tracking-widest uppercase">Essential Gear</span>
                    <h3 class="text-3xl md:text-5xl font-extrabold text-text-main mb-6 leading-tight">홈트 필수 기어 10선</h3>
                    <p class="text-text-muted mb-10 leading-relaxed break-keep">완벽한 홈 트레이닝을 위해 꼭 필요한 아이템들만 모았습니다. 당신의 홈짐을 완성해보세요.</p>
                    <button class="px-10 py-4 bg-text-main text-white font-bold rounded-2xl hover:bg-primary transition-all">기획전 바로가기</button>
                </div>
                <div class="flex-1 grid grid-cols-2 gap-4">
                    <div class="aspect-square rounded-3xl overflow-hidden shadow-md"><img src="https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=600" class="w-full h-full object-cover"></div>
                    <div class="aspect-square rounded-3xl overflow-hidden shadow-md translate-y-8"><img src="https://images.unsplash.com/photo-1518611012118-696072aa579a?w=600" class="w-full h-full object-cover"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Exhibition 3 -->
    <section id="exhibit-3" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-20 scroll-mt-[180px]">
        <div class="text-center mb-12">
            <h3 class="text-3xl font-extrabold text-text-main mb-4">주말 하이킹 기획전 🏔️</h3>
            <p class="text-text-muted">자연 속에서 즐기는 건강한 주말, 하이킹 필수템!</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @for ($i = 1; $i <= 3; $i++)
            <div class="group cursor-pointer">
                <div class="aspect-video rounded-3xl bg-gray-100 overflow-hidden mb-6 shadow-sm">
                    <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=800" class="w-full h-full object-cover group-hover:scale-105 transition-all" />
                </div>
                <h4 class="text-xl font-bold text-text-main group-hover:text-primary transition-colors">Hiking Concept #{{ $i }}</h4>
                <p class="text-sm text-text-muted mt-2">지금 가장 인기 있는 아웃도어 스타일링</p>
            </div>
            @endfor
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const y = target.getBoundingClientRect().top + window.pageYOffset - 170;
                window.scrollTo({ top: y, behavior: 'smooth' });

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
