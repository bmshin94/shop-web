@extends('layouts.app')

@section('title', '최근 본 상품 | 마이페이지 - Active Women\'s Premium Store')

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
                    <h3 class="text-xl font-bold text-text-main">최근 본 상품</h3>
                    <button class="text-sm border border-gray-300 text-text-main rounded px-3 py-1.5 hover:bg-gray-50 font-bold transition-colors">전체 삭제</button>
                </div>
                
                <h4 class="text-md font-bold text-primary mb-4 mt-8"><span class="material-symbols-outlined align-middle text-sm mr-1">check_circle</span>[상태 1] 데이터가 있는 경우</h4>
                <div class="mb-12 border-l-2 border-gray-100 pl-6 py-2 relative">
                    <div class="absolute left-[-5px] top-4 size-2 rounded-full bg-primary ring-4 ring-white"></div>
                    <span class="text-sm font-bold text-primary block mb-4">오늘</span>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Product -->
                        <div class="group relative">
                            <div class="aspect-[3/4] overflow-hidden rounded-xl bg-gray-100 mb-3 relative">
                                <a href="{{ route('product-detail') }}"><img src="https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?w=400&h=533&fit=crop" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" /></a>
                            </div>
                            <div>
                                <a href="{{ route('product-detail') }}" class="text-sm font-bold text-text-main leading-tight group-hover:underline line-clamp-2">에어 컴포트 스포츠 브라탑</a>
                                <div class="mt-2 flex items-center gap-2">
                                    <span class="text-sm font-extrabold text-text-main">39,000원</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <h4 class="text-md font-bold text-gray-500 mb-4"><span class="material-symbols-outlined align-middle text-sm mr-1">cancel</span>[상태 2] 데이터가 없는 경우</h4>
                <div class="flex flex-col items-center justify-center py-20 text-center border border-gray-100 rounded-xl bg-gray-50">
                    <span class="material-symbols-outlined text-5xl text-gray-300 mb-4">visibility_off</span>
                    <p class="text-text-muted font-medium text-lg">최근 본 상품이 없습니다.</p>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
