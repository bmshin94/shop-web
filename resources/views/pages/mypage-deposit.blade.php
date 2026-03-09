@extends('layouts.app')

@section('title', '예치금 내역 | 마이페이지 - Active Women\'s Premium Store')

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
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-text-main">예치금 조회</h3>
                    <p class="text-3xl font-extrabold text-primary">50,000<span class="text-lg text-text-main font-bold ml-1">원</span></p>
                </div>
                <p class="text-sm text-text-muted mb-8 bg-gray-50 p-4 rounded-xl">예치금은 고객님의 계좌로 언제든지 환불이 가능합니다. 예치금 환불은 1:1 문의를 통해 신청해주세요.</p>
                
                <h4 class="text-md font-bold text-primary mb-4 mt-8"><span class="material-symbols-outlined align-middle text-sm mr-1">check_circle</span>[상태 1] 데이터가 있는 경우</h4>
                <div class="border-t border-gray-100 divide-y divide-gray-100 mb-12">
                     <div class="py-4 flex justify-between items-center">
                        <div>
                            <p class="font-bold text-sm text-text-main mb-1">반품 접수에 따른 환불 금액 예치</p>
                            <p class="text-xs text-text-muted">2026.02.20</p>
                        </div>
                        <span class="font-bold text-primary">+50,000원</span>
                    </div>
                </div>

                <h4 class="text-md font-bold text-gray-500 mb-4"><span class="material-symbols-outlined align-middle text-sm mr-1">cancel</span>[상태 2] 데이터가 없는 경우</h4>
                <div class="border border-gray-100 rounded-xl pt-8 pb-8 text-center bg-gray-50">
                    <p class="text-text-muted font-medium">발생한 예치금 내역이 없습니다.</p>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
