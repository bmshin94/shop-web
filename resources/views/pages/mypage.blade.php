@extends('layouts.app')

@section('title', '마이페이지 - Active Women\'s Premium Store')

@section('content')
<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-text-muted mb-6">
        <a href="/" class="hover:text-primary transition-colors">Home</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <span class="font-bold text-text-main">마이페이지</span>
    </nav>

    <h2 class="text-3xl font-extrabold text-text-main tracking-tight mb-8">마이페이지</h2>

    <div class="flex flex-col lg:flex-row gap-8 items-start">
        @include('partials.mypage-sidebar')

        <div class="flex-1 w-full space-y-8">
            <!-- User Summary (Restored) -->
            <div class="bg-white rounded-2xl shadow-sm border border-border-color overflow-hidden">
                <div class="p-6 sm:p-8 flex flex-col md:flex-row items-center gap-6 justify-between bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center gap-5">
                        <div class="size-16 rounded-full bg-primary flex items-center justify-center text-white shadow-md overflow-hidden">
                            @if($member->avatar)
                                <img src="{{ $member->avatar }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="material-symbols-outlined text-3xl">person</span>
                            @endif
                        </div>
                        <div>
                            <p class="text-2xl font-extrabold text-text-main">{{ $member->name }} <span class="text-base font-medium text-text-muted">님</span></p>
                            <p class="text-sm font-bold text-primary mt-1 border border-primary/20 bg-primary-light px-2 py-0.5 rounded-md inline-block">{{ $member->level }} 등급</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('mypage.profile-edit') }}" class="px-5 py-2.5 bg-white border border-border-color rounded-xl text-sm font-bold text-text-main hover:bg-gray-50 transition-all active:scale-95 shadow-sm">회원정보 수정</a>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-border-color border-t border-border-color">
                    <a href="{{ route('mypage.point') }}" class="p-6 text-center hover:bg-background-alt transition-all active:scale-[0.98] group">
                        <p class="text-text-muted text-sm font-medium mb-2 group-hover:text-primary transition-colors">가용 적립금</p>
                        <p class="text-2xl font-extrabold text-text-main">{{ number_format($member->points) }} <span class="text-lg font-bold">원</span></p>
                    </a>
                    <a href="{{ route('mypage.coupon') }}" class="p-6 text-center hover:bg-background-alt transition-all active:scale-[0.98] group">
                        <p class="text-text-muted text-sm font-medium mb-2 group-hover:text-primary transition-colors">보유 쿠폰</p>
                        <p class="text-2xl font-extrabold text-text-main">{{ number_format($couponCount) }} <span class="text-lg font-bold">장</span></p>
                    </a>
                    <a href="{{ route('mypage.order-list') }}" class="p-6 text-center hover:bg-background-alt transition-all active:scale-[0.98] group">
                        <p class="text-text-muted text-sm font-medium mb-2 group-hover:text-primary transition-colors">총 주문 횟수</p>
                        <p class="text-2xl font-extrabold text-text-main">{{ number_format($totalOrderCount) }} <span class="text-lg font-bold">건</span></p>
                    </a>
                </div>
            </div>

            <!-- 주문 현황 -->
            <div class="bg-white rounded-2xl shadow-sm border border-border-color p-6 sm:p-8">
                <h3 class="text-xl font-bold text-text-main mb-6">나의 주문처리 현황</h3>
                <div class="flex items-center justify-between">
                    <a href="{{ route('mypage.order-list', ['status' => '주문접수']) }}" class="flex flex-col items-center gap-3 group transition-transform active:scale-95">
                        <div class="size-14 rounded-2xl bg-gray-50 flex items-center justify-center text-text-muted group-hover:bg-primary-light group-hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-2xl">assignment</span>
                        </div>
                        <p class="text-xs font-bold text-text-muted group-hover:text-text-main">주문접수</p>
                        <p class="text-lg font-black text-text-main">{{ number_format($orderStats['received']) }}</p>
                    </a>
                    <span class="material-symbols-outlined text-gray-200">chevron_right</span>
                    <a href="{{ route('mypage.order-list', ['status' => '상품준비중']) }}" class="flex flex-col items-center gap-3 group transition-transform active:scale-95">
                        <div class="size-14 rounded-2xl bg-gray-50 flex items-center justify-center text-text-muted group-hover:bg-primary-light group-hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-2xl">inventory_2</span>
                        </div>
                        <p class="text-xs font-bold text-text-muted group-hover:text-text-main">상품준비중</p>
                        <p class="text-lg font-black text-text-main">{{ number_format($orderStats['preparing']) }}</p>
                    </a>
                    <span class="material-symbols-outlined text-gray-200">chevron_right</span>
                    <a href="{{ route('mypage.order-list', ['status' => '배송중']) }}" class="flex flex-col items-center gap-3 group transition-transform active:scale-95">
                        <div class="size-14 rounded-2xl bg-gray-50 flex items-center justify-center text-text-muted group-hover:bg-primary-light group-hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-2xl">local_shipping</span>
                        </div>
                        <p class="text-xs font-bold text-text-muted group-hover:text-text-main">배송중</p>
                        <p class="text-lg font-black text-text-main">{{ number_format($orderStats['shipping']) }}</p>
                    </a>
                    <span class="material-symbols-outlined text-gray-200">chevron_right</span>
                    <a href="{{ route('mypage.order-list', ['status' => '배송완료']) }}" class="flex flex-col items-center gap-3 group transition-transform active:scale-95">
                        <div class="size-14 rounded-2xl bg-gray-50 flex items-center justify-center text-text-muted group-hover:bg-primary-light group-hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-2xl">package_2</span>
                        </div>
                        <p class="text-xs font-bold text-text-muted group-hover:text-text-main">배송완료</p>
                        <p class="text-lg font-black text-text-main">{{ number_format($orderStats['delivered']) }}</p>
                    </a>
                    <span class="material-symbols-outlined text-gray-200">chevron_right</span>
                    <a href="{{ route('mypage.order-list', ['status' => '구매확정']) }}" class="flex flex-col items-center gap-3 group transition-transform active:scale-95">
                        <div class="size-14 rounded-2xl bg-gray-50 flex items-center justify-center text-text-muted group-hover:bg-primary-light group-hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-2xl">verified</span>
                        </div>
                        <p class="text-xs font-bold text-text-muted group-hover:text-text-main">구매확정</p>
                        <p class="text-lg font-black text-text-main">{{ number_format($orderStats['confirmed']) }}</p>
                    </a>
                </div>
            </div>

            <!-- 최근 주문 내역 -->
            <div class="bg-white rounded-2xl shadow-sm border border-border-color overflow-hidden">
                <div class="p-6 border-b border-border-color flex justify-between items-center">
                    <h3 class="text-lg font-bold text-text-main">최근 주문 내역</h3>
                    <a href="{{ route('mypage.order-list') }}" class="text-sm font-bold text-text-muted hover:text-primary transition-colors">전체보기</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-6 py-4 text-xs font-bold text-text-muted uppercase tracking-wider">주문정보</th>
                                <th class="px-6 py-4 text-xs font-bold text-text-muted uppercase tracking-wider">상품명</th>
                                <th class="px-6 py-4 text-xs font-bold text-text-muted uppercase tracking-wider">결제금액</th>
                                <th class="px-6 py-4 text-xs font-bold text-text-muted uppercase tracking-wider">상태</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-color">
                            @forelse($recentOrders as $order)
                            <tr class="hover:bg-gray-50/30 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="text-xs text-text-muted mb-1">{{ $order->ordered_at->format('Y.m.d') }}</p>
                                    <a href="{{ route('mypage.order-detail', ['order_number' => $order->order_number]) }}" class="text-sm font-bold text-text-main hover:underline">{{ $order->order_number }}</a>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($order->items->first())
                                        <div class="size-12 rounded-lg bg-gray-100 overflow-hidden shrink-0 border border-border-color">
                                            <img src="{{ $order->items->first()->product->image_url }}" alt="" class="w-full h-full object-cover">
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-text-main truncate">
                                                {{ $order->items->first()->product->name }}
                                                @if($order->items->count() > 1)
                                                <span class="text-text-muted font-medium">외 {{ $order->items->count() - 1 }}건</span>
                                                @endif
                                            </p>
                                        </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-bold text-text-main text-sm">
                                    ₩{{ number_format($order->total_amount) }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-2.5 py-1 rounded-md text-[11px] font-black {{ $order->order_status === '배송완료' ? 'bg-green-50 text-green-600' : 'bg-primary-light text-primary' }}">
                                        {{ $order->order_status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-20 text-center text-text-muted text-sm font-medium">
                                    최근 주문 내역이 없습니다.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
