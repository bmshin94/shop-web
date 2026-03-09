@extends('layouts.app')

@section('title', '주문/배송 조회 | 마이페이지 - Active Women\'s Premium Store')

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
                <h3 class="text-xl font-bold text-text-main mb-6">주문/배송 조회</h3>
                
                <!-- Period Filter -->
                <div class="flex flex-wrap gap-2 mb-8">
                    @foreach([1, 3, 6, 12] as $m)
                    <a href="{{ route('mypage.order-list', ['months' => $m, 'status' => request('status')]) }}" 
                       class="px-4 py-2 text-sm font-bold rounded-lg border {{ $months == $m ? 'border-primary text-primary bg-primary-light' : 'border-gray-300 text-text-main hover:bg-gray-50' }} transition-colors">
                        {{ $m }}개월
                    </a>
                    @endforeach
                </div>
                
                <div class="overflow-x-auto rounded-xl border border-gray-100 mb-8">
                    <table class="w-full text-left border-collapse min-w-[700px]">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="py-4 px-6 text-sm font-bold text-text-main text-center">주문일자<br><span class="text-xs font-normal text-text-muted">[주문번호]</span></th>
                                <th class="py-4 px-6 text-sm font-bold text-text-main">상품정보</th>
                                <th class="py-4 px-6 text-sm font-bold text-text-main text-center">결제금액</th>
                                <th class="py-4 px-6 text-sm font-bold text-text-main text-center">주문상태</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($orders as $order)
                            @foreach($order->items as $itemIndex => $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                @if($itemIndex === 0)
                                <td class="py-5 px-6 text-center border-r border-gray-50" rowspan="{{ $order->items->count() }}">
                                    <p class="font-bold text-text-main text-sm">{{ $order->ordered_at->format('Y.m.d') }}</p>
                                    <a href="{{ route('mypage.order-detail', ['order' => $order->order_number]) }}" class="text-xs text-text-muted mt-1 hover:underline">[{{ $order->order_number }}]</a>
                                </td>
                                @endif
                                <td class="py-5 px-6">
                                    <div class="flex items-center gap-4">
                                        <div class="size-16 bg-gray-100 rounded-lg overflow-hidden shrink-0 border border-gray-100">
                                            <img src="{{ $item->product->image_url }}" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-primary mb-1">Active Women</p>
                                            <p class="text-sm font-bold text-text-main line-clamp-1">{{ $item->product_name }}</p>
                                            <p class="text-xs text-text-muted mt-1">
                                                @if($item->option_summary)
                                                옵션: {{ $item->option_summary }}
                                                @endif
                                                ({{ number_format($item->quantity) }}개)
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                @if($itemIndex === 0)
                                <td class="py-5 px-6 text-center whitespace-nowrap border-l border-gray-50" rowspan="{{ $order->items->count() }}">
                                    <p class="font-extrabold text-text-main text-sm">₩{{ number_format($order->total_amount) }}</p>
                                    <p class="text-[10px] text-text-muted mt-1">({{ $order->payment_method }})</p>
                                </td>
                                <td class="py-5 px-6 text-center border-l border-gray-50" rowspan="{{ $order->items->count() }}">
                                    <span class="inline-flex py-1 px-3 {{ $order->order_status === '배송완료' ? 'bg-green-50 text-green-600' : 'bg-primary-light text-primary' }} font-bold text-xs rounded-full border border-current/20">
                                        {{ $order->order_status }}
                                    </span>
                                    @if($order->tracking_number)
                                    <button class="block w-full mt-2 py-1 px-2 border border-gray-200 rounded text-[10px] font-bold text-text-muted hover:bg-gray-50">배송추적</button>
                                    @endif
                                </td>
                                @endif
                            </tr>
                            @endforeach
                            @empty
                            <tr>
                                <td colspan="4" class="py-20 text-center bg-gray-50">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="material-symbols-outlined text-5xl text-gray-300 mb-4">inventory_2</span>
                                        <p class="text-text-muted font-medium">조회 기간 동안 주문한 내역이 없습니다.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
