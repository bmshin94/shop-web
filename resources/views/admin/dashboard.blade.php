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
                <h4 class="text-2xl font-black text-text-main">₩{{ number_format($stats['today_sales']) }}</h4>
                @if($stats['sales_trend'] > 0)
                <p class="text-[11px] text-green-500 font-bold flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">trending_up</span> {{ $stats['sales_trend'] }}%
                </p>
                @elseif($stats['sales_trend'] < 0)
                <p class="text-[11px] text-red-500 font-bold flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">trending_down</span> {{ abs($stats['sales_trend']) }}%
                </p>
                @else
                <p class="text-[11px] text-text-muted font-bold tracking-tight">전일과 동일</p>
                @endif
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
                <h4 class="text-2xl font-black text-text-main">{{ number_format($stats['today_orders']) }}건</h4>
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
                <h4 class="text-2xl font-black text-text-main">{{ number_format($stats['new_members']) }}명</h4>
                <p class="text-[11px] text-primary font-bold tracking-tight">전체 회원 {{ number_format(\App\Models\Member::count()) }}명</p>
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
                <h4 class="text-2xl font-black text-text-main text-red-600">{{ number_format($stats['pending_qna']) }}건</h4>
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
            
            {{-- Simple Visual Bar Chart (Tailwind) --}}
            <div class="h-[300px] flex items-end justify-between gap-4 bg-gray-50/50 p-8 rounded-2xl border border-gray-100">
                @php $maxAmount = collect($stats['weekly_sales'])->max('amount') ?: 1; @endphp
                @foreach($stats['weekly_sales'] as $data)
                <div class="flex-1 flex flex-col items-center gap-3 group">
                    <div class="relative w-full flex items-end justify-center">
                        {{-- Tooltip --}}
                        <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-text-main text-white px-2 py-1 rounded text-[10px] font-bold opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10">
                            ₩{{ number_format($data['amount']) }}
                        </div>
                        <div class="w-full max-w-[40px] bg-primary/20 rounded-t-lg group-hover:bg-primary transition-all duration-500" 
                             style="height: {{ ($data['amount'] / $maxAmount) * 200 }}px"></div>
                    </div>
                    <span class="text-[10px] font-black text-text-muted group-hover:text-text-main">{{ $data['date'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Right: Recent Orders -->
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex flex-col">
            <div class="flex items-center justify-between mb-6 border-b border-gray-50 pb-4">
                <h3 class="text-xl font-extrabold text-text-main">최근 주문</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-[11px] font-bold text-primary hover:underline uppercase tracking-tight">전체보기</a>
            </div>
            <div class="flex-1 space-y-6 overflow-y-auto pr-2 custom-scrollbar">
                @forelse($stats['recent_orders'] as $order)
                <div onclick="location.href='{{ route('admin.orders.show', $order) }}'" class="flex items-center justify-between group cursor-pointer">
                    <div class="flex items-center gap-4">
                        <div class="size-10 rounded-full bg-gray-50 flex items-center justify-center text-text-muted group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[20px]">person</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-text-main line-clamp-1">
                                {{ $order->items->first()->product->name ?? '상품정보없음' }}
                                @if($order->items->count() > 1)
                                외 {{ $order->items->count() - 1 }}건
                                @endif
                            </p>
                            <p class="text-[11px] font-bold text-text-muted tracking-tight">
                                {{ $order->member->name ?? '게스트' }} 님 | ₩{{ number_format($order->total_amount) }}
                            </p>
                        </div>
                    </div>
                    @php
                        $statusColor = match($order->order_status) {
                            '구매확정' => 'bg-green-100 text-green-600',
                            '배송중' => 'bg-blue-100 text-blue-600',
                            '주문접수' => 'bg-amber-100 text-amber-600',
                            '취소완료' => 'bg-red-100 text-red-600',
                            default => 'bg-gray-100 text-gray-600'
                        };
                    @endphp
                    <span class="px-2 py-1 {{ $statusColor }} text-[11px] font-bold rounded-full tracking-tight">{{ $order->order_status }}</span>
                </div>
                @empty
                <div class="h-full flex flex-col items-center justify-center text-center opacity-50">
                    <span class="material-symbols-outlined text-4xl mb-2">shopping_basket</span>
                    <p class="text-xs font-bold">최근 주문이 없습니다.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
