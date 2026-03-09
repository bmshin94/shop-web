@extends('layouts.app')

@section('title', '쿠폰 | 마이페이지 - Active Women\'s Premium Store')

@section('content')
<main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
    <!-- Page Title -->
    <h2 class="text-3xl font-extrabold text-text-main tracking-tight mb-8">마이페이지</h2>

    <div class="flex flex-col lg:flex-row gap-8 items-start">
        <!-- LNB (Left Navigation Bar) -->
        @include('partials.mypage-sidebar')

        <!-- Main Dashboard Content -->
        <div class="flex-1 w-full space-y-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-text-main">나의 쿠폰</h3>
                    <p class="text-3xl font-extrabold text-primary">2<span class="text-lg text-text-main font-bold ml-1">장</span></p>
                </div>
                <div class="flex flex-col sm:flex-row gap-4">
                    <input type="text" placeholder="쿠폰 번호를 입력하세요" class="flex-1 rounded-xl border border-gray-300 focus:border-primary focus:ring-primary px-4 py-3 text-sm">
                    <button class="px-8 py-3 bg-text-main text-white font-bold rounded-xl hover:bg-black transition-colors shrink-0">쿠폰 등록</button>
                </div>
            </div>
            
            <h4 class="text-md font-bold text-primary mb-4 mt-8"><span class="material-symbols-outlined align-middle text-sm mr-1">check_circle</span>[상태 1] 혜택 데이터가 있는 경우</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-12">
                <!-- Coupon 1 -->
                <div class="border border-primary/20 bg-primary-light/30 rounded-2xl p-6 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 size-16 bg-primary rounded-full opacity-10"></div>
                    <span class="inline-block px-2 py-1 bg-primary text-white text-xs font-bold rounded mb-3">배송비 쿠폰</span>
                    <h4 class="text-lg font-extrabold text-text-main mb-1">무료배송 쿠폰</h4>
                    <p class="text-sm text-text-muted mb-4">5만원 이상 결제 시 사용 가능</p>
                    <p class="text-xs text-gray-500 font-medium">유효기간: 2026.03.31 까지</p>
                </div>
                <!-- Coupon 2 -->
                <div class="border border-gray-200 border-dashed bg-white rounded-2xl p-6 relative">
                    <span class="inline-block px-2 py-1 bg-text-main text-white text-xs font-bold rounded mb-3">할인 쿠폰</span>
                    <h4 class="text-lg font-extrabold text-text-main mb-1">웰컴 10% 장바구니 할인</h4>
                    <p class="text-sm text-text-muted mb-4">전 상품 적용 가능 (최대 3만원)</p>
                    <p class="text-xs text-text-muted font-medium">유효기간: 2026.04.15 까지</p>
                </div>
            </div>

            <h4 class="text-md font-bold text-gray-500 mb-4"><span class="material-symbols-outlined align-middle text-sm mr-1">cancel</span>[상태 2] 데이터가 없는 경우</h4>
            <div class="flex flex-col items-center justify-center py-12 text-center border border-gray-100 rounded-xl bg-gray-50">
                <p class="text-text-muted font-medium">사용 가능한 쿠폰이 없습니다.</p>
            </div>
        </div>
    </div>
</main>
@endsection
