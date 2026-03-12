@extends('layouts.app')

@section('title', '적립금 | 마이페이지 - Active Women\'s Premium Store')

@section('content')
<main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-text-muted mb-6">
        <a href="/" class="hover:text-primary transition-colors">Home</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <a href="{{ route('mypage') }}" class="hover:text-primary transition-colors">마이페이지</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <span class="font-bold text-text-main">적립금 내역</span>
    </nav>

    <!-- Page Title -->
    <h2 class="text-3xl font-extrabold text-text-main tracking-tight mb-8">적립금 내역</h2>

    <div class="flex flex-col lg:flex-row gap-8 items-start">
        <!-- LNB (Left Navigation Bar) -->
        @include('partials.mypage-sidebar')

        <!-- Main Dashboard Content -->
        <div class="flex-1 w-full space-y-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8 mb-8">
                <div class="flex items-center justify-end mb-6 border-b border-gray-100 pb-6">
                    <p class="text-3xl font-extrabold text-primary">12,500<span class="text-lg text-text-main font-bold ml-1">원</span></p>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="font-medium text-text-muted">30일 내 소멸 예정 적립금</span>
                    <span class="font-bold text-text-main">0원</span>
                </div>
            </div>
            
            <h4 class="text-md font-bold text-primary mb-4 mt-8"><span class="material-symbols-outlined align-middle text-sm mr-1">check_circle</span>[상태 1] 데이터가 있는 경우</h4>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8 mb-12">
                <h4 class="text-lg font-bold text-text-main mb-4">적립/사용 내역</h4>
                <div class="border-t border-gray-100 divide-y divide-gray-100">
                    <div class="py-4 flex justify-between items-center">
                        <div>
                            <p class="font-bold text-sm text-text-main mb-1">상품 구매 확정 적립</p>
                            <p class="text-xs text-text-muted">2026.02.20</p>
                        </div>
                        <span class="font-bold text-primary">+2,500원</span>
                    </div>
                    <div class="py-4 flex justify-between items-center">
                        <div>
                            <p class="font-bold text-sm text-text-main mb-1">신규 가입 환영 적립금</p>
                            <p class="text-xs text-text-muted">2026.01.10</p>
                        </div>
                        <span class="font-bold text-primary">+10,000원</span>
                    </div>
                </div>
            </div>

            <h4 class="text-md font-bold text-gray-500 mb-4"><span class="material-symbols-outlined align-middle text-sm mr-1">cancel</span>[상태 2] 데이터가 없는 경우</h4>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8 text-center py-10">
                <p class="text-text-muted font-medium">적립금 내역이 없습니다.</p>
            </div>
        </div>
    </div>
</main>
@endsection
