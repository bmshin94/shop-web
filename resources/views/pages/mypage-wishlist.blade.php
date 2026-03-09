@extends('layouts.app')

@section('title', '찜한 상품 | 마이페이지 - Active Women\'s Premium Store')

@section('content')
<main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
    <!-- Page Title -->
    <h2 class="text-3xl font-extrabold text-text-main tracking-tight mb-8">마이페이지</h2>

    <div class="flex flex-col lg:flex-row gap-8 items-start">
        <!-- LNB (Left Navigation Bar) -->
        @include('partials.mypage-sidebar')

        <!-- Main Dashboard Content -->
        <div class="flex-1 w-full space-y-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-text-main">찜한 상품</h3>
                    <button class="text-sm border border-gray-300 text-text-main rounded px-3 py-1.5 hover:bg-gray-50 font-bold transition-colors">선택 삭제</button>
                </div>

                <h4 class="text-md font-bold text-primary mb-4 mt-8"><span class="material-symbols-outlined align-middle text-sm mr-1">check_circle</span>[상태 1] 데이터가 있는 경우</h4>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                    <!-- Product -->
                    @for ($i = 1; $i <= 4; $i++)
                    <div class="group relative">
                        <div class="aspect-[3/4] overflow-hidden rounded-xl bg-gray-100 mb-3 relative shadow-sm border border-gray-100">
                            <a href="{{ route('product-detail') }}"><img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=400&h=533&fit=crop" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" /></a>
                            <button class="absolute top-2 right-2 p-1.5 rounded-full bg-white/80 text-primary hover:bg-white transition-colors">
                                <span class="material-symbols-outlined filled text-[20px]" style="font-variation-settings: 'FILL' 1;">favorite</span>
                            </button>
                        </div>
                        <div>
                            <a href="{{ route('product-detail') }}" class="text-sm font-bold text-text-main leading-tight group-hover:underline line-clamp-2">프리미엄 요가 레깅스 (찜 {{ $i }})</a>
                            <div class="mt-2 flex items-center gap-2">
                                <span class="text-sm font-extrabold text-text-main">54,000원</span>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>

                <h4 class="text-md font-bold text-gray-500 mb-4"><span class="material-symbols-outlined align-middle text-sm mr-1">cancel</span>[상태 2] 데이터가 없는 경우</h4>
                <div class="flex flex-col items-center justify-center py-20 text-center border border-gray-100 rounded-xl bg-gray-50">
                    <span class="material-symbols-outlined text-5xl text-gray-300 mb-4">favorite</span>
                    <p class="text-text-muted font-medium text-lg">아직 찜한 상품이 없습니다.</p>
                    <a href="{{ route('product-list') }}" class="mt-4 px-6 py-2 bg-text-main text-white font-bold rounded-lg hover:bg-black transition-colors block w-fit mx-auto">상품 보러가기</a>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
