@extends('layouts.admin')

@section('page_title', '대시보드')

@section('content')
<div class="space-y-8">
    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Stat Card 1 -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 transition-all hover:shadow-md group">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-[28px]">payments</span>
                </div>
                <p class="text-[11px] font-bold text-text-muted uppercase">오늘의 매출액</p>
            </div>
            <div class="flex items-end justify-between">
                <h4 class="text-2xl font-black text-text-main">₩1,250,000</h4>
                <p class="text-[11px] text-green-500 font-bold flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">trending_up</span> 12%
                </p>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 transition-all hover:shadow-md group">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-[28px]">shopping_cart</span>
                </div>
                <p class="text-[11px] font-bold text-text-muted uppercase">오늘의 주문</p>
            </div>
            <div class="flex items-end justify-between">
                <h4 class="text-2xl font-black text-text-main">42건</h4>
                <p class="text-[11px] text-text-muted font-bold tracking-tight">결제 완료 기준</p>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 transition-all hover:shadow-md group">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-[28px]">group</span>
                </div>
                <p class="text-[11px] font-bold text-text-muted uppercase">신규 가입</p>
            </div>
            <div class="flex items-end justify-between">
                <h4 class="text-2xl font-black text-text-main">12명</h4>
                <p class="text-[11px] text-primary font-bold tracking-tight">목표 달성률 85%</p>
            </div>
        </div>

        <!-- Stat Card 4 -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 transition-all hover:shadow-md group">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 rounded-2xl bg-purple-50 text-purple-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-[28px]">question_answer</span>
                </div>
                <p class="text-[11px] font-bold text-text-muted uppercase">답변 대기 문의</p>
            </div>
            <div class="flex items-end justify-between">
                <h4 class="text-2xl font-black text-text-main">5건</h4>
                <p class="text-[11px] text-red-500 font-bold tracking-tight">긴급 처리 필요</p>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left: Sales Chart Placeholder -->
        <div class="lg:col-span-2 bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-extrabold text-text-main">주간 매출 추이</h3>
                <div class="flex bg-gray-50 p-1 rounded-xl">
                    <button class="px-4 py-1.5 bg-white text-[11px] font-bold text-text-main rounded-lg shadow-sm">매출액</button>
                    <button class="px-4 py-1.5 text-[11px] font-bold text-text-muted hover:text-text-main transition-colors">주문건수</button>
                </div>
            </div>
            <div class="h-[300px] flex items-center justify-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                <div class="text-center">
                    <span class="material-symbols-outlined text-[48px] text-gray-300 mb-2">bar_chart</span>
                    <p class="text-[11px] font-bold text-text-muted tracking-tight">차트 데이터 로딩 중...</p>
                </div>
            </div>
        </div>

        <!-- Right: Recent Orders -->
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex flex-col">
            <div class="flex items-center justify-between mb-6 border-b border-gray-50 pb-4">
                <h3 class="text-xl font-extrabold text-text-main">최근 주문</h3>
                <a href="#" class="text-[11px] font-bold text-primary hover:underline uppercase tracking-tight">전체보기</a>
            </div>
            <div class="flex-1 space-y-6 overflow-y-auto pr-2 custom-scrollbar">
                @foreach([1, 2, 3, 4, 5] as $order)
                <div class="flex items-center justify-between group cursor-pointer">
                    <div class="flex items-center gap-4">
                        <div class="size-10 rounded-full bg-gray-50 flex items-center justify-center text-text-muted group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[20px]">person</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-text-main line-clamp-1">하이웨이스트 레깅스 외 1건</p>
                            <p class="text-[11px] font-bold text-text-muted tracking-tight">김액티브 님 | ₩59,000</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 bg-green-100 text-green-600 text-[11px] font-bold rounded-full tracking-tight">결제완료</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
