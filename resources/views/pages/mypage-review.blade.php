@extends('layouts.app')

@section('title', '상품 리뷰 관리 | 마이페이지 - Active Women\'s Premium Store')

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
                <h3 class="text-xl font-bold text-text-main mb-6">상품 리뷰 관리</h3>
                
                <!-- Tabs -->
                <div class="flex border-b border-gray-200 mb-8 relative">
                    <button id="tabBtnAvailable" onclick="switchTab('available')" class="pb-3 px-6 text-sm font-bold border-b-2 border-primary text-primary transition-colors">작성 가능한 리뷰 <span class="badge ml-1 bg-primary text-white text-[10px] px-1.5 py-0.5 rounded-full">10</span></button>
                    <button id="tabBtnWritten" onclick="switchTab('written')" class="pb-3 px-6 text-sm font-medium border-b-2 border-transparent text-text-muted hover:text-text-main transition-colors">내가 작성한 리뷰 <span class="badge ml-1 bg-gray-300 text-white text-[10px] px-1.5 py-0.5 rounded-full">10</span></button>
                </div>

                <!-- 작성 가능한 리뷰 -->
                <div id="tabAvailable" class="space-y-4">
                    @for ($i = 1; $i <= 5; $i++)
                    <div class="flex flex-col sm:flex-row gap-6 p-5 border border-gray-100 rounded-xl hover:shadow-md transition-shadow bg-white">
                        <div class="size-20 bg-gray-100 rounded-lg overflow-hidden shrink-0"><img src="https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?w=200" class="w-full h-full object-cover"></div>
                        <div class="flex flex-col justify-center flex-1">
                            <p class="text-xs font-bold text-primary mb-1">배송완료</p>
                            <h4 class="text-base font-bold text-text-main">에어 컴포트 스포츠 브라탑 (샘플 {{ $i }})</h4>
                            <p class="text-xs text-text-muted">리뷰 작성 시 적립금 500원!</p>
                        </div>
                        <div class="flex items-center"><a href="{{ route('review.write') }}" class="w-full sm:w-auto px-6 py-2 bg-text-main text-white text-sm font-bold rounded-lg hover:bg-black transition-colors text-center">리뷰 작성</a></div>
                    </div>
                    @endfor
                </div>

                <!-- 내가 작성한 리뷰 (초기 숨김) -->
                <div id="tabWritten" class="space-y-4 hidden">
                    @for ($i = 1; $i <= 3; $i++)
                    <div class="p-6 border border-gray-100 rounded-xl bg-gray-50">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm font-bold text-text-main">디자인도 예쁘고 아주 편해요! </span>
                            <div class="flex text-yellow-400 text-xs"></div>
                        </div>
                        <p class="text-sm text-text-main leading-relaxed">운동할 때 입으려고 샀는데 신축성도 좋고 땀 흡수도 잘 되네요. 역시 Active Women! </p>
                        <div class="mt-4 flex justify-end gap-2 text-[11px] text-gray-400">
                            <span>2026.02.{{ 20 + $i }}</span>
                            <span class="mx-1">|</span>
                            <button class="hover:text-primary transition-colors">수정</button>
                            <span class="mx-1">|</span>
                            <button class="hover:text-red-500 transition-colors">삭제</button>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    function switchTab(tab) {
        const available = document.getElementById('tabAvailable');
        const written = document.getElementById('tabWritten');
        const btnAvail = document.getElementById('tabBtnAvailable');
        const btnWritten = document.getElementById('tabBtnWritten');
        
        if (tab === 'available') {
            available.classList.remove('hidden');
            written.classList.add('hidden');
            btnAvail.classList.add('border-primary', 'text-primary');
            btnAvail.classList.remove('border-transparent', 'text-text-muted');
            btnWritten.classList.remove('border-primary', 'text-primary');
            btnWritten.classList.add('border-transparent', 'text-text-muted');
        } else {
            available.classList.add('hidden');
            written.classList.remove('hidden');
            btnAvail.classList.remove('border-primary', 'text-primary');
            btnAvail.classList.add('border-transparent', 'text-text-muted');
            btnWritten.classList.add('border-primary', 'text-primary');
            btnWritten.classList.remove('border-transparent', 'text-text-muted');
        }
    }
</script>
@endpush
