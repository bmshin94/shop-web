@extends('layouts.app')

@section('title', '회원 탈퇴 | 마이페이지 - Active Women\'s Premium Store')

@section('content')
<main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
    <!-- Page Title -->
    <h2 class="text-3xl font-extrabold text-text-main tracking-tight mb-8">마이페이지</h2>

    <div class="flex flex-col lg:flex-row gap-8 items-start">
        <!-- LNB (Left Navigation Bar) -->
        @include('partials.mypage-sidebar')

        <!-- Main Dashboard Content -->
        <div class="flex-1 w-full space-y-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8 lg:p-12 mb-8 max-w-3xl mx-auto">
                <div class="text-center mb-10">
                    <span class="material-symbols-outlined text-5xl text-gray-300 mb-4">sentiment_dissatisfied</span>
                    <h3 class="text-2xl font-extrabold text-text-main mb-2">회원 탈퇴 대기</h3>
                    <p class="text-text-muted text-sm leading-relaxed">액티브 우먼을 이용하시는 동안 불편한 점이 있으셨나요?<br>탈퇴하기 전 아래 유의사항을 반드시 확인해주세요.</p>
                </div>
                
                <div class="bg-gray-50 rounded-xl p-6 mb-8 text-sm text-text-muted leading-relaxed border border-gray-100">
                    <h4 class="font-bold text-text-main mb-3 flex items-center gap-1"><span class="material-symbols-outlined text-sm text-primary">warning</span>탈퇴 시 유의사항</h4>
                    <ul class="list-disc pl-5 space-y-2">
                        <li>탈퇴 시 보유하고 계신 쿠폰 및 적립금은 모두 소멸되며 복구가 불가능합니다. (현재 보유 적립금: <strong class="text-primary">12,500원</strong> / 쿠폰: <strong class="text-primary">2장</strong>)</li>
                        <li>주문내역 및 1:1 문의 등 관련 데이터는 개인정보처리방침에 따라 일정 기간 보관 후 파기됩니다.</li>
                        <li>동일한 이메일로 재가입 시 신규 회원 혜택은 중복 제공되지 않습니다.</li>
                        <li>현재 진행 중인 주문, 교환, 환불 건이 있는 경우 완료 후 탈퇴가 가능합니다.</li>
                    </ul>
                </div>
                
                <form action="#" method="POST">
                    @csrf
                    <div class="border-t border-gray-100 pt-8 mb-10">
                        <p class="text-sm font-bold text-text-main mb-4">무엇이 불편하셨나요? (선택)</p>
                        <div class="flex flex-col gap-3">
                            @foreach(['상품 종류가 부족함', '가격 혜택이 부족함', '방문 빈도가 낮음', '기타 사유'] as $reason)
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="reason" value="{{ $reason }}" class="text-primary focus:ring-primary w-5 h-5 border-gray-300">
                                <span class="text-sm text-text-main group-hover:text-primary transition-colors">{{ $reason }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 mb-10 bg-primary-light/50 p-4 rounded-xl border border-primary/20">
                        <input type="checkbox" id="agree" required class="rounded text-primary focus:ring-primary w-5 h-5 border-primary/30">
                        <label for="agree" class="text-sm font-bold text-primary cursor-pointer">안내사항을 모두 확인하였으며, 이에 동의합니다.</label>
                    </div>
                    
                    <div class="flex gap-4">
                        <button type="button" onclick="history.back()" class="flex-1 py-4 bg-text-main text-white font-bold rounded-xl hover:bg-black transition-colors shadow-md">계속 이용하기</button>
                        <button type="submit" class="flex-1 py-4 bg-white border border-gray-300 text-text-main font-bold rounded-xl hover:bg-gray-50 transition-colors">탈퇴하기</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
