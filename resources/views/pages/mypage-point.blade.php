@extends('layouts.app')

@section('title', '적립금 | 마이페이지 - Active Women\'s Premium Store')

@section('content')
<main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-text-muted mb-6">
        <a href="/" class="hover:text-primary transition-colors">Home</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <a href="{{ route('mypage') }}" class="hover:text-primary transition-colors">마이페이지</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <span class="font-bold text-text-main">적립금 내역</span>
    </nav>

    {{-- Page Title --}}
    <h2 class="text-3xl font-extrabold text-text-main tracking-tight mb-8">적립금 내역</h2>

    <div class="flex flex-col lg:flex-row gap-8 items-start">
        {{-- LNB (Left Navigation Bar) --}}
        @include('partials.mypage-sidebar')

        {{-- Main Dashboard Content --}}
        <div class="flex-1 w-full space-y-8">
            {{-- Point Summary Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8 mb-8">
                <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-6">
                    <p class="text-lg font-bold text-text-main">현재 보유 적립금</p>
                    <p class="text-3xl font-extrabold text-primary">{{ number_format($currentPoints) }}<span class="text-lg text-text-main font-bold ml-1">원</span></p>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="font-medium text-text-muted">30일 내 소멸 예정 적립금</span>
                    <span class="font-bold text-text-main">{{ number_format($expiringPoints) }}원</span>
                </div>
            </div>
            
            {{-- History Section --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
                <h4 class="text-lg font-bold text-text-main mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">history</span> 적립/사용 내역
                </h4>
                
                <div class="border-t border-gray-100 divide-y divide-gray-100">
                    @forelse($histories as $history)
                    <div class="py-5 flex justify-between items-center group hover:bg-gray-50/50 transition-colors">
                        <div>
                            <p class="font-bold text-sm text-text-main mb-1 group-hover:text-primary transition-colors">{{ $history->reason }}</p>
                            <p class="text-xs text-text-muted font-medium">{{ $history->created_at->format('Y.m.d H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-black text-sm {{ $history->amount > 0 ? 'text-primary' : 'text-text-main' }}">
                                {{ $history->amount > 0 ? '+' : '' }}{{ number_format($history->amount) }}원
                            </p>
                            <p class="text-[10px] text-text-muted font-bold mt-0.5">잔액 {{ number_format($history->balance_after) }}원</p>
                        </div>
                    </div>
                    @empty
                    <div class="py-20 text-center">
                        <div class="size-16 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-4">
                            <span class="material-symbols-outlined text-gray-300 text-3xl">database_off</span>
                        </div>
                        <p class="text-text-muted font-bold">적립금 내역이 없습니다.</p>
                    </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($histories->hasPages())
                <div class="mt-8">
                    {{ $histories->links() }}
                </div>
                @endif
            </div>

            {{-- Caution Note --}}
            <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100">
                <h5 class="text-sm font-black text-text-main mb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined text-gray-400 text-lg">info</span> 적립금 이용 안내
                </h5>
                <ul class="space-y-2 text-xs text-text-muted font-medium leading-relaxed">
                    <li>• 적립금은 상품 구매 시 현금처럼 사용할 수 있습니다.</li>
                    <li>• 적립금의 유효기간은 적립일로부터 1년이며, 기간 내 미사용 시 자동 소멸됩니다.</li>
                    <li>• 1회 결제 시 사용 가능한 최소/최대 적립금은 정책에 따라 다를 수 있습니다.</li>
                </ul>
            </div>
        </div>
    </div>
</main>
@endsection
