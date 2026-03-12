@extends('layouts.app')

@section('title', '주문/배송 조회 | 마이페이지 - Active Women\'s Premium Store')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* Flatpickr Custom Theme */
    .flatpickr-calendar {
        border-radius: 20px !important;
        box-shadow: 0 20px 40px -15px rgba(0,0,0,0.1) !important;
        border: 1px solid #f3f4f6 !important;
        padding: 8px !important;
    }
    .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, 
    .flatpickr-day.selected:hover, .flatpickr-day.nextMonthDay.selected, 
    .flatpickr-day.prevMonthDay.selected {
        background: #ec3713 !important;
        border-color: #ec3713 !important;
        font-weight: 700 !important;
    }
    .flatpickr-months .flatpickr-month, .flatpickr-current-month .flatpickr-monthDropdown-months {
        font-weight: 800 !important;
        color: #181211 !important;
    }
    .flatpickr-weekday {
        color: #896861 !important;
        font-weight: 700 !important;
        font-size: 11px !important;
    }
    .flatpickr-innerContainer { margin-top: 10px; }
</style>
@endpush

@section('content')
<main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-text-muted mb-6">
        <a href="/" class="hover:text-primary transition-colors">Home</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <a href="{{ route('mypage') }}" class="hover:text-primary transition-colors">마이페이지</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <span class="font-bold text-text-main">주문/배송 조회</span>
    </nav>

    <!-- Page Title -->
    <h2 class="text-3xl font-extrabold text-text-main tracking-tight mb-8">주문/배송 조회</h2>

    <div class="flex flex-col lg:flex-row gap-8 items-start">
        <!-- LNB -->
        @include('partials.mypage-sidebar')

        <!-- Main Content -->
        <div class="flex-1 w-full space-y-6">
            
            <!-- Modern Filter Section -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <form action="{{ route('mypage.order-list') }}" method="GET" class="p-5 lg:p-8 space-y-5">
                    <input type="hidden" name="months" id="selected-months" value="{{ $months }}">
                    <!-- Top Row: Search & Status -->
                    <div class="flex flex-col lg:flex-row gap-4">
                        <div class="flex-1 relative group">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 group-focus-within:text-primary transition-colors">search</span>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="상품명 또는 주문번호 검색" 
                                   class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-transparent rounded-2xl text-sm focus:ring-2 focus:ring-primary/20 focus:bg-white focus:border-primary/30 transition-all outline-none font-semibold">
                        </div>
                        <div class="w-full lg:w-[220px] relative">
                            <select name="status" class="w-full pl-4 pr-10 py-3 bg-gray-50 border border-transparent rounded-2xl text-sm focus:ring-2 focus:ring-primary/20 focus:bg-white focus:border-primary/30 transition-all outline-none font-medium appearance-none cursor-pointer !bg-none">
                                <option value="">모든 주문상태</option>
                                @foreach(['주문접수', '상품준비중', '배송중', '배송완료', '구매확정'] as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 pointer-events-none">expand_more</span>
                        </div>
                    </div>

                    <!-- Bottom Row: Period & Dates -->
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5 pt-5 border-t border-gray-100">
                        <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-black text-text-muted uppercase tracking-wider shrink-0">조회기간</span>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach([1, 3, 6, 12] as $m)
                                    <button type="button" 
                                       data-months="{{ $m }}"
                                       class="btn-period px-3 py-1.5 text-[11px] font-bold rounded-xl border transition-all {{ $months == $m ? 'bg-primary text-white border-primary shadow-md shadow-primary/20' : 'bg-white text-text-muted border-gray-200 hover:border-gray-400' }}">
                                        {{ $m }}개월
                                    </button>
                                    @endforeach
                                </div>
                            </div>

                            <div class="flex items-center bg-gray-50 rounded-xl px-3 py-1.5 border border-gray-100 focus-within:border-primary/30 transition-all w-full lg:w-auto">
                                <input type="text" name="start_date" value="{{ $startDate }}" placeholder="연도-월-일" class="datepicker bg-transparent border-none p-0 text-[11px] font-medium focus:ring-0 outline-none w-full sm:w-24 text-center">
                                <span class="text-gray-300 mx-2">~</span>
                                <input type="text" name="end_date" value="{{ $endDate }}" placeholder="연도-월-일" class="datepicker bg-transparent border-none p-0 text-[11px] font-medium focus:ring-0 outline-none w-full sm:w-24 text-center">
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2 w-full lg:w-auto">
                            <a href="{{ route('mypage.order-list') }}" title="초기화" class="size-10 flex items-center justify-center rounded-xl bg-gray-100 text-gray-500 hover:bg-gray-200 transition-colors shrink-0 ml-auto lg:ml-0">
                                <span class="material-symbols-outlined text-xl">restart_alt</span>
                            </a>
                            <button type="submit" class="flex-1 lg:flex-none px-8 py-2.5 bg-primary text-white text-sm font-black rounded-xl hover:bg-red-600 transition-all shadow-lg shadow-primary/20 active:scale-95">
                                조회
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- List Section -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th class="py-5 px-8 text-[13px] font-semibold text-text-muted uppercase tracking-widest text-center">주문일자 / 번호</th>
                                <th class="py-5 px-6 text-[13px] font-semibold text-text-muted uppercase tracking-widest">상품정보</th>
                                <th class="py-5 px-6 text-[13px] font-semibold text-text-muted uppercase tracking-widest text-center">결제금액</th>
                                <th class="py-5 px-8 text-[13px] font-semibold text-text-muted uppercase tracking-widest text-center">주문상태</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($orders as $order)
                            @foreach($order->items as $itemIndex => $item)
                            <tr class="group hover:bg-gray-50/30 transition-colors">
                                @if($itemIndex === 0)
                                <td class="py-6 px-8 text-center border-r border-gray-50/50 align-top" rowspan="{{ $order->items->count() }}">
                                    <p class="font-semibold text-text-main text-sm">{{ $order->ordered_at->format('Y.m.d') }}</p>
                                    <a href="{{ route('mypage.order-detail', ['order_number' => $order->order_number]) }}" class="inline-block mt-2 px-2.5 py-1 bg-gray-100 rounded-lg text-xs font-semibold text-text-muted hover:bg-primary-light hover:text-primary transition-colors tracking-tighter">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                @endif
                                <td class="py-6 px-6">
                                    <div class="flex items-center gap-5">
                                        <div class="size-20 bg-gray-100 rounded-2xl overflow-hidden shrink-0 border border-gray-100 shadow-sm">
                                            <img src="{{ $item->product->image_url }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-text-main line-clamp-1 group-hover:text-primary transition-colors">{{ $item->product_name }}</p>
                                            @if($item->option_summary)
                                            <p class="text-[11px] text-text-muted mt-1.5 font-medium bg-gray-100 inline-block px-2 py-0.5 rounded-md">
                                                {{ $item->option_summary }}
                                            </p>
                                            @endif
                                            <p class="text-[11px] text-text-muted mt-1 font-semibold">{{ number_format($item->quantity) }}개</p>
                                        </div>
                                    </div>
                                </td>
                                @if($itemIndex === 0)
                                <td class="py-6 px-6 text-center whitespace-nowrap border-l border-gray-50/50 align-top" rowspan="{{ $order->items->count() }}">
                                    <p class="font-semibold text-text-main text-base tracking-tight">₩{{ number_format($order->total_amount) }}</p>
                                    <p class="text-[10px] font-semibold text-gray-400 mt-1 uppercase tracking-tighter">{{ $order->payment_method }}</p>
                                </td>
                                <td class="py-6 px-8 text-center border-l border-gray-50/50 align-top" rowspan="{{ $order->items->count() }}">
                                    @php
                                        $statusClasses = [
                                            '주문접수' => 'bg-gray-100 text-gray-600',
                                            '상품준비중' => 'bg-blue-50 text-blue-600',
                                            '배송중' => 'bg-primary-light text-primary',
                                            '배송완료' => 'bg-green-50 text-green-600',
                                            '구매확정' => 'bg-background-dark text-white',
                                        ];
                                        $statusClass = $statusClasses[$order->order_status] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <span class="inline-flex py-1.5 px-4 {{ $statusClass }} font-semibold text-[10px] rounded-full border border-current/10 shadow-sm">
                                        {{ $order->order_status }}
                                    </span>
                                    
                                    @if($order->tracking_number)
                                    <button class="block w-full mt-3 py-2 px-3 bg-white border border-gray-200 rounded-xl text-[10px] font-black text-text-muted hover:bg-gray-50 hover:text-primary hover:border-primary transition-all shadow-sm">배송추적</button>
                                    @endif
                                </td>
                                @endif
                            </tr>
                            @endforeach
                            @empty
                            <tr>
                                <td colspan="4" class="py-32 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="size-20 rounded-full bg-gray-50 flex items-center justify-center mb-6">
                                            <span class="material-symbols-outlined text-4xl text-gray-200">inventory_2</span>
                                        </div>
                                        <p class="text-text-muted font-bold">조회 기간 동안 주문한 내역이 없습니다.</p>
                                        <a href="/product-list" class="mt-6 px-6 py-2.5 bg-text-main text-white text-xs font-black rounded-xl hover:bg-primary transition-colors shadow-lg shadow-gray-200">쇼핑하러 가기</a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                <div class="p-8 border-t border-gray-50">
                    {{ $orders->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</main>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/ko.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const monthsInput = document.getElementById('selected-months');
        
        const fpStart = flatpickr("input[name='start_date']", {
            locale: "ko",
            dateFormat: "Y-m-d",
            disableMobile: "true",
            onChange: function() {
                // 날짜 직접 변경 시 개월 수 버튼 해제
                monthsInput.value = '';
                resetPeriodButtons();
            }
        });
        const fpEnd = flatpickr("input[name='end_date']", {
            locale: "ko",
            dateFormat: "Y-m-d",
            disableMobile: "true",
            onChange: function() {
                monthsInput.value = '';
                resetPeriodButtons();
            }
        });

        function resetPeriodButtons() {
            document.querySelectorAll('.btn-period').forEach(b => {
                b.classList.remove('bg-primary', 'text-white', 'border-primary', 'shadow-md', 'shadow-primary/20', 'bg-text-main', 'border-text-main');
                b.classList.add('bg-white', 'text-text-muted', 'border-gray-200');
            });
        }

        // 개월 수 버튼 클릭 이벤트
        document.querySelectorAll('.btn-period').forEach(btn => {
            btn.addEventListener('click', function() {
                const months = parseInt(this.dataset.months);
                monthsInput.value = months; // Hidden 필드에 저장 ✨

                const now = new Date();
                const startDate = new Date();
                startDate.setMonth(now.getMonth() - months);

                const formatDate = (date) => {
                    const y = date.getFullYear();
                    const m = String(date.getMonth() + 1).padStart(2, '0');
                    const d = String(date.getDate()).padStart(2, '0');
                    return `${y}-${m}-${d}`;
                };

                // 입력창 및 Flatpickr 업데이트
                fpStart.setDate(formatDate(startDate));
                fpEnd.setDate(formatDate(now));

                // 버튼 스타일 업데이트
                resetPeriodButtons();
                this.classList.remove('bg-white', 'text-text-muted', 'border-gray-200');
                this.classList.add('bg-primary', 'text-white', 'border-primary', 'shadow-md', 'shadow-primary/20');
            });
        });
    });
</script>
@endpush
@endsection
