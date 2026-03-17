@extends('layouts.admin')

@section('page_title', '주문 / 배송 관리')

@push('styles')
<style>
    .order-row {
        display: grid;
        align-items: center;
        grid-template-columns: 1.3fr 1fr 0.8fr 0.8fr;
        gap: 12px;
    }

    @media (min-width: 1024px) {
        .order-row {
            grid-template-columns: 1.4fr 1.1fr 1fr 1fr 1.2fr 120px;
            gap: 16px;
        }
    }
</style>
@endpush

@section('content')
@php
    $activeFilterCount = collect([
        request('search'),
        request('order_status'),
        request('payment_status'),
        request('date_from'),
        request('date_to'),
    ])->filter(fn ($value) => filled($value))->count();
@endphp

<div class="space-y-6">
    <!-- 주문 요약 -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 lg:gap-6">
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">shopping_bag</span>
                </div>
                <span class="text-[11px] font-bold text-text-muted uppercase">전체 주문</span>
            </div>
            <p class="text-3xl font-black text-text-main">{{ number_format($stats['total_orders']) }}</p>
        </div>
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">calendar_month</span>
                </div>
                <span class="text-[11px] font-bold text-text-muted uppercase">오늘 주문</span>
            </div>
            <p class="text-3xl font-black text-text-main">{{ number_format($stats['today_orders']) }}</p>
        </div>
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">local_shipping</span>
                </div>
                <span class="text-[11px] font-bold text-text-muted uppercase">배송중</span>
            </div>
            <p class="text-3xl font-black text-text-main">{{ number_format($stats['shipping_orders']) }}</p>
        </div>
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 rounded-2xl bg-green-50 text-green-500 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">task_alt</span>
                </div>
                <span class="text-[11px] font-bold text-text-muted uppercase">배송완료</span>
            </div>
            <p class="text-3xl font-black text-text-main">{{ number_format($stats['completed_orders']) }}</p>
        </div>
    </div>

    <!-- 주문 필터 -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <div class="lg:col-span-2 relative">
                    <span class="material-symbols-outlined absolute left-3 top-3 text-text-muted text-[18px]">search</span>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="주문번호, 주문자, 수령인, 송장번호 검색"
                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
                </div>
                <div class="relative">
                    <select name="order_status" class="w-full px-4 py-2.5 pr-10 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 appearance-none outline-none">
                        <option value="">모든 주문상태</option>
                        @foreach($orderStatusOptions as $status)
                            <option value="{{ $status }}" {{ request('order_status') === $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-3 text-text-muted text-[18px] pointer-events-none">expand_more</span>
                </div>
                <div class="relative">
                    <select name="payment_status" class="w-full px-4 py-2.5 pr-10 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 appearance-none outline-none">
                        <option value="">모든 결제상태</option>
                        @foreach($paymentStatusOptions as $status)
                            <option value="{{ $status }}" {{ request('payment_status') === $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-3 text-text-muted text-[18px] pointer-events-none">expand_more</span>
                </div>
            </div>

            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 pt-4 border-t border-gray-100">
                <div class="flex flex-col sm:flex-row items-center gap-3 pt-4 border-t border-gray-100">
                    <div class="relative flex-1 group">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-muted text-[18px] group-focus-within:text-primary transition-colors pointer-events-none">calendar_today</span>
                        <input type="text" name="date_from" value="{{ request('date_from') }}" placeholder="주문 시작일" class="datepicker w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all font-medium">
                    </div>
                    <span class="text-gray-300 hidden sm:block">~</span>
                    <div class="relative flex-1 group">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-muted text-[18px] group-focus-within:text-primary transition-colors pointer-events-none">calendar_month</span>
                        <input type="text" name="date_to" value="{{ request('date_to') }}" placeholder="주문 종료일" class="datepicker w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all font-medium">
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.orders.index') }}" class="px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-bold text-text-muted hover:bg-gray-50 active:scale-95 transition-all">
                        초기화
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-text-main text-white rounded-xl text-sm font-bold hover:bg-black active:scale-95 transition-all">
                        검색
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- 주문 목록 -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-white/80 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <p class="text-sm font-extrabold text-text-main">
                검색 결과 {{ number_format($orders->total()) }}건
            </p>
            <div class="flex items-center gap-2 text-[12px] font-bold text-text-muted">
                <a href="{{ route('admin.orders.trash') }}" class="inline-flex items-center gap-1 rounded-full border border-gray-200 bg-white px-3 py-1 text-text-main hover:border-primary hover:text-primary active:scale-95 transition-all">
                    <span class="material-symbols-outlined text-[14px]">delete</span>
                    휴지통 {{ number_format($trashedOrdersCount ?? 0) }}
                </a>
                @if($activeFilterCount > 0)
                    <span class="inline-flex items-center rounded-full bg-primary/5 px-3 py-1 text-primary">
                        적용된 필터 {{ $activeFilterCount }}개
                    </span>
                @endif
                <span>페이지 {{ number_format($orders->currentPage()) }} / {{ number_format($orders->lastPage()) }}</span>
            </div>
        </div>

        <div class="hidden lg:grid order-row px-6 py-4 bg-gray-50/70 border-b border-gray-100">
            <div class="text-[11px] font-bold text-text-muted uppercase">주문번호 / 주문자</div>
            <div class="text-[11px] font-bold text-text-muted uppercase">수령인 / 상품수</div>
            <div class="text-center text-[11px] font-bold text-text-muted uppercase">주문상태</div>
            <div class="text-center text-[11px] font-bold text-text-muted uppercase">결제상태</div>
            <div class="text-right text-[11px] font-bold text-text-muted uppercase">결제금액 / 주문일</div>
            <div class="text-center text-[11px] font-bold text-text-muted uppercase">관리</div>
        </div>

        <div class="divide-y divide-gray-50">
            @forelse($orders as $order)
                <div class="order-row px-4 lg:px-6 py-4 hover:bg-gray-50/60 transition-colors">
                    <div class="min-w-0">
                        <a href="{{ route('admin.orders.show', $order) }}" class="text-sm font-extrabold text-text-main hover:text-primary transition-colors block truncate">
                            {{ $order->order_number }}
                        </a>
                        <p class="mt-1 text-[12px] font-bold text-text-muted truncate">{{ $order->customer_name }} · {{ $order->customer_phone }}</p>
                    </div>

                    <div class="min-w-0">
                        <p class="text-sm font-bold text-text-main truncate">{{ $order->recipient_name }}</p>
                        <p class="mt-1 text-[12px] font-bold text-text-muted">
                            상품 {{ number_format($order->items_count) }}종 / 수량 {{ number_format($order->total_quantity ?? 0) }}개
                        </p>
                    </div>

                    <div class="text-left lg:text-center">
                        <x-admin.status-badge type="order" :value="$order->order_status" />
                        @if($order->tracking_number)
                            <p class="mt-1.5 text-[10px] font-bold text-text-muted truncate bg-gray-100 px-2 py-0.5 rounded-md inline-block">송장: {{ $order->tracking_number }}</p>
                        @endif
                    </div>

                    <div class="text-left lg:text-center">
                        <x-admin.status-badge type="payment" :value="$order->payment_status" />
                    </div>

                    <div class="text-left lg:text-right">
                        <p class="text-sm font-extrabold text-text-main">₩{{ number_format($order->total_amount) }}</p>
                        <p class="mt-1 text-[12px] font-bold text-text-muted">{{ optional($order->ordered_at)->format('Y.m.d H:i') }}</p>
                    </div>

                    <div class="flex lg:justify-center">
                        <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center justify-center gap-1 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-[12px] font-bold text-text-main hover:border-primary hover:text-primary active:scale-95 transition-all shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">receipt_long</span>
                            상세
                        </a>
                    </div>
                </div>
            @empty
                <div class="px-6 py-20 text-center">
                    <div class="size-20 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-gray-300 text-[40px]">shopping_bag</span>
                    </div>
                    <p class="text-text-muted text-sm font-bold">
                        {{ $activeFilterCount > 0 ? '조건에 맞는 주문이 없습니다.' : '등록된 주문이 없습니다.' }}
                    </p>
                    <p class="mt-2 text-[12px] font-bold text-text-muted">
                        {{ $activeFilterCount > 0 ? '검색 조건을 조정하거나 필터를 초기화해 다시 확인해 주세요.' : '주문 데이터가 쌓이면 이곳에서 배송 상태와 송장번호를 관리할 수 있습니다.' }}
                    </p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-10">
        {{ $orders->links() }}
    </div>
</div>
@endsection
