@extends('layouts.app')

@section('title', '멤버십 혜택 안내 | 고객센터 - Active Women\'s Premium Store')

@section('content')
<main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex flex-col lg:flex-row gap-12">
        <!-- SIDEBAR -->
        <aside class="w-full lg:w-64 flex-shrink-0 space-y-8">
            <h2 class="text-3xl font-extrabold text-text-main mb-8 uppercase tracking-tighter">CS Center</h2>
            <nav class="flex flex-col gap-1">
                <a href="{{ route('support') }}" class="px-4 py-3 text-text-muted hover:bg-gray-50 hover:text-text-main rounded-xl font-bold transition-all flex items-center justify-between group">
                    자주 묻는 질문 <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">chevron_right</span>
                </a>
                <a href="{{ route('support.notice') }}" class="px-4 py-3 text-text-muted hover:bg-gray-50 hover:text-text-main rounded-xl font-bold transition-all flex items-center justify-between group">
                    공지사항 <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">chevron_right</span>
                </a>
                <a href="{{ route('support.exchange') }}" class="px-4 py-3 text-text-muted hover:bg-gray-50 hover:text-text-main rounded-xl font-bold transition-all flex items-center justify-between group">
                    교환/반품 안내 <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">chevron_right</span>
                </a>
                <a href="{{ route('support.membership') }}" class="px-4 py-3 bg-text-main text-white rounded-xl font-bold transition-all shadow-md shadow-black/10 flex items-center justify-between">
                    멤버십 혜택 안내 <span class="material-symbols-outlined text-sm">chevron_right</span>
                </a>
            </nav>

            <!-- Contact Panel -->
            <div class="p-6 bg-background-alt rounded-[2rem] border border-gray-100">
                <h3 class="font-bold text-text-main flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-primary">support_agent</span> 고객상담센터
                </h3>
                <p class="text-3xl font-black text-primary mb-1">1544-0000</p>
                <div class="text-xs text-text-muted space-y-1.5 mt-4">
                    <p>평일 <span class="text-text-main font-bold">09:00 - 18:00</span></p>
                    <p>점심 <span class="text-text-main font-bold">12:00 - 13:00</span></p>
                    <p>주말/공휴일 휴무</p>
                </div>
                <a href="https://pf.kakao.com" target="_blank" class="w-full mt-8 py-4 bg-kakao text-background-dark rounded-2xl font-bold text-sm flex items-center justify-center gap-2 hover:shadow-lg transition-all" style="background-color: #FEE500;">
                    <span class="material-symbols-outlined text-lg">chat_bubble</span> 실시간 상담 시작
                </a>
            </div>
        </aside>

        <!-- CONTENT -->
        <div class="flex-1 space-y-12">
            <div class="bg-primary-light/20 p-10 rounded-[3.5rem] mb-12 border border-primary/5 text-center relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8 opacity-5"><span class="material-symbols-outlined text-9xl">workspace_premium</span></div>
                <span class="inline-block px-3 py-1 bg-primary text-white text-[10px] font-bold rounded-full mb-4 uppercase">Benefit Guide</span>
                <h2 class="text-4xl font-black text-text-main tracking-tight mb-4">Active Women Membership </h2>
                <p class="text-text-muted text-sm max-w-md mx-auto break-keep">함께할수록 더 커지는 특별한 등급별 혜택을 확인해 보세요.</p>
            </div>

            <!-- Membership Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Silver -->
                <div class="p-8 bg-white border border-gray-100 rounded-[2.5rem] hover:shadow-xl transition-all text-center group">
                    <div class="size-16 bg-gray-100 text-gray-400 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-primary-light group-hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-4xl">grade</span>
                    </div>
                    <h4 class="text-xl font-black text-text-main mb-2">SILVER</h4>
                    <p class="text-xs text-text-muted mb-8 italic">신규 가입 고객</p>
                    <ul class="space-y-4 text-sm font-bold text-text-main text-left">
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">check</span> 신규 가입 1만원 쿠폰</li>
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">check</span> 구매 금액 1% 적립</li>
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">check</span> 생일 축하 쿠폰 지급</li>
                    </ul>
                </div>
                <!-- Gold -->
                <div class="p-8 bg-white border-2 border-primary rounded-[2.5rem] shadow-2xl text-center relative z-10 scale-105">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1 bg-primary text-white text-[10px] font-bold rounded-full uppercase">Most Loved</div>
                    <div class="size-16 bg-primary-light text-primary rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <span class="material-symbols-outlined text-4xl">workspace_premium</span>
                    </div>
                    <h4 class="text-xl font-black text-text-main mb-2">GOLD</h4>
                    <p class="text-xs text-text-muted mb-8 italic">20만원 이상 구매</p>
                    <ul class="space-y-4 text-sm font-bold text-text-main text-left">
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">check</span> 상시 3% 추가 할인</li>
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">check</span> 구매 금액 3% 적립</li>
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">check</span> 무료배송 쿠폰 (월 1회)</li>
                    </ul>
                </div>
                <!-- VIP -->
                <div class="p-8 bg-white border border-gray-100 rounded-[2.5rem] hover:shadow-xl transition-all text-center group">
                    <div class="size-16 bg-background-dark text-yellow-400 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <span class="material-symbols-outlined text-4xl">diamond</span>
                    </div>
                    <h4 class="text-xl font-black text-text-main mb-2">VIP</h4>
                    <p class="text-xs text-text-muted mb-8 italic">50만원 이상 구매</p>
                    <ul class="space-y-4 text-sm font-bold text-text-main text-left">
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">check</span> 상시 5% 추가 할인</li>
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">check</span> 구매 금액 5% 적립</li>
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">check</span> 전용 시크릿 세일 초대</li>
                    </ul>
                </div>
            </div>

            <div class="p-8 bg-gray-50 rounded-3xl border border-gray-100 text-center">
                <p class="text-sm text-text-muted italic">"자기가 어떤 등급이든, 관리자한테는 언제나 VIP인 거 알지? "</p>
            </div>
        </div>
    </div>
</main>
@endsection
