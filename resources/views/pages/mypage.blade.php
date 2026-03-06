@extends('layouts.app')

@section('title', '마이페이지 - Active Women\'s Premium Store')

@section('content')
<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
    <h2 class="text-3xl font-extrabold text-text-main tracking-tight mb-8">마이페이지</h2>

    <div class="flex flex-col lg:flex-row gap-8 items-start">
        @include('partials.sidebar-mypage')

        <div class="flex-1 w-full space-y-8">
            <!-- User Summary -->
            <div class="bg-white rounded-2xl shadow-sm border border-border-color overflow-hidden">
                <div class="p-6 sm:p-8 flex flex-col md:flex-row items-center gap-6 justify-between bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center gap-5">
                        <div class="size-16 rounded-full bg-primary flex items-center justify-center text-white shadow-md">
                            <span class="material-symbols-outlined text-3xl">workspace_premium</span>
                        </div>
                        <div>
                            <p class="text-2xl font-extrabold text-text-main">김에스핏 <span class="text-base font-medium text-text-muted">님</span></p>
                            <p class="text-sm font-bold text-primary mt-1 border border-primary/20 bg-primary-light px-2 py-0.5 rounded-md inline-block">VIP 등급</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <button class="px-5 py-2.5 bg-white border border-border-color rounded-xl text-sm font-bold text-text-main hover:bg-gray-50 transition-colors shadow-sm">회원정보 수정</button>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-border-color border-t border-border-color">
                    <a href="#" class="p-6 text-center hover:bg-background-alt transition-colors group">
                        <p class="text-text-muted text-sm font-medium mb-2 group-hover:text-primary transition-colors">가용 적립금</p>
                        <p class="text-2xl font-extrabold text-text-main">12,500 <span class="text-lg font-bold">원</span></p>
                    </a>
                    <a href="#" class="p-6 text-center hover:bg-background-alt transition-colors group">
                        <p class="text-text-muted text-sm font-medium mb-2 group-hover:text-primary transition-colors">보유 쿠폰</p>
                        <p class="text-2xl font-extrabold text-text-main">2 <span class="text-lg font-bold">장</span></p>
                    </a>
                    <a href="#" class="p-6 text-center hover:bg-background-alt transition-colors group">
                        <p class="text-text-muted text-sm font-medium mb-2 group-hover:text-primary transition-colors">총 주문 횟수</p>
                        <p class="text-2xl font-extrabold text-text-main">15 <span class="text-lg font-bold">건</span></p>
                    </a>
                </div>
            </div>

            <!-- 주문 현황 등 나머지 대시보드 내용 (생략 가능, 여기서는 간단히 구조만 유지) -->
            <div class="bg-white rounded-2xl shadow-sm border border-border-color p-6 sm:p-8">
                <h3 class="text-xl font-bold text-text-main mb-6">나의 주문처리 현황</h3>
                {{-- 주문 상태 아이콘들... --}}
            </div>
        </div>
    </div>
</div>
@endsection
