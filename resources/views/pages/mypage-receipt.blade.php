@extends('layouts.app')

@section('title', '영수증/계산서 발급 | 마이페이지 - Active Women\'s Premium Store')

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
        <span class="font-bold text-text-main">영수증/계산서 발급</span>
    </nav>

    <!-- Page Title -->
    <h2 class="text-3xl font-extrabold text-text-main tracking-tight mb-8">영수증/계산서 발급</h2>

    <div class="flex flex-col lg:flex-row gap-8 items-start">
        <!-- LNB -->
        @include('partials.mypage-sidebar')

        <!-- Main Content -->
        <div class="flex-1 w-full space-y-6">
            
            <!-- Modern Filter Section -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <form action="{{ route('mypage.receipt') }}" method="GET" class="p-5 lg:p-8 space-y-5">
                    <input type="hidden" name="months" id="selected-months" value="{{ $months }}">
                    <!-- Top Row: Search & Status -->
                    <div class="flex flex-col lg:flex-row gap-4">
                        <div class="flex-1 relative group">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 group-focus-within:text-primary transition-colors">search</span>
                            <input type="text" name="search" value="{{ $search }}" placeholder="상품명 또는 주문번호 검색" 
                                   class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-transparent rounded-2xl text-sm focus:ring-2 focus:ring-primary/20 focus:bg-white focus:border-primary/30 transition-all outline-none font-semibold">
                        </div>
                        <div class="w-full lg:w-[220px] relative">
                            <select name="status" class="w-full pl-4 pr-10 py-3 bg-gray-50 border border-transparent rounded-2xl text-sm focus:ring-2 focus:ring-primary/20 focus:bg-white focus:border-primary/30 transition-all outline-none font-medium appearance-none cursor-pointer !bg-none">
                                <option value="">모든 증빙종류</option>
                                <option value="신용카드" {{ request('status') == '신용카드' ? 'selected' : '' }}>신용카드 전표</option>
                                <option value="가상계좌" {{ request('status') == '가상계좌' ? 'selected' : '' }}>현금영수증</option>
                                <option value="실시간계좌이체" {{ request('status') == '실시간계좌이체' ? 'selected' : '' }}>세금계산서</option>
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
                            <a href="{{ route('mypage.receipt') }}" title="초기화" class="size-10 flex items-center justify-center rounded-xl bg-gray-100 text-gray-500 hover:bg-gray-200 transition-colors shrink-0 ml-auto lg:ml-0">
                                <span class="material-symbols-outlined text-xl">restart_alt</span>
                            </a>
                            <button type="submit" class="flex-1 lg:flex-none px-8 py-2.5 bg-primary text-white text-sm font-black rounded-xl hover:bg-red-600 transition-all shadow-lg shadow-primary/20 active:scale-95">
                                조회
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="p-4 bg-primary-light rounded-2xl border border-primary/10 flex items-start gap-3">
                <span class="material-symbols-outlined text-primary text-xl">info</span>
                <p class="text-xs text-text-main font-medium leading-relaxed">
                    신용카드 매출전표, 현금영수증 발급 내역을 확인하고 인쇄할 수 있습니다.<br>
                    결제 수단에 따라 발급되는 증빙 서류의 종류가 다를 수 있습니다.
                </p>
            </div>

            <!-- List Section -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th class="py-5 px-8 text-[13px] font-semibold text-text-muted uppercase tracking-widest text-center">발급일자 / 번호</th>
                                <th class="py-5 px-6 text-[13px] font-semibold text-text-muted uppercase tracking-widest">내용(주문정보)</th>
                                <th class="py-5 px-6 text-[13px] font-semibold text-text-muted uppercase tracking-widest text-center">결제금액</th>
                                <th class="py-5 px-6 text-[13px] font-semibold text-text-muted uppercase tracking-widest text-center">증빙종류</th>
                                <th class="py-5 px-8 text-[13px] font-semibold text-text-muted uppercase tracking-widest text-center">출력</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($receipts as $order)
                                @foreach($order->items as $itemIndex => $item)
                                <tr class="group hover:bg-gray-50/30 transition-colors">
                                    @if($itemIndex === 0)
                                    <td class="py-6 px-8 text-center border-r border-gray-50/50 align-top" rowspan="{{ $order->items->count() }}">
                                        <p class="font-semibold text-text-main text-sm">{{ $order->ordered_at->format('Y.m.d') }}</p>
                                        <p class="inline-block mt-2 px-2.5 py-1 bg-gray-100 rounded-lg text-xs font-semibold text-text-muted tracking-tighter">
                                            {{ $order->order_number }}
                                        </p>
                                    </td>
                                    @endif
                                    <td class="py-6 px-6 align-top">
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-text-main line-clamp-1 group-hover:text-primary transition-colors">
                                                {{ $item->product_name }}
                                            </p>
                                            @if($item->option_summary)
                                            <p class="text-[11px] text-text-muted mt-1.5 font-medium bg-gray-100 inline-block px-2 py-0.5 rounded-md">
                                                {{ $item->option_summary }}
                                            </p>
                                            @endif
                                        </div>
                                    </td>
                                    @if($itemIndex === 0)
                                    <td class="py-6 px-6 text-center align-top whitespace-nowrap border-l border-gray-50/50" rowspan="{{ $order->items->count() }}">
                                        <p class="font-semibold text-text-main text-base tracking-tight">₩{{ number_format($order->total_amount) }}</p>
                                        <p class="text-[10px] font-semibold text-gray-400 mt-1 uppercase tracking-tighter">{{ $order->payment_method }}</p>
                                    </td>
                                    <td class="py-6 px-6 text-center align-top whitespace-nowrap border-l border-gray-50/50" rowspan="{{ $order->items->count() }}">
                                        <span class="text-xs font-medium text-text-main">
                                            @if(str_contains($order->payment_method, '카드'))
                                                신용카드 전표
                                            @else
                                                현금영수증
                                            @endif
                                        </span>
                                    </td>
                                    <td class="py-6 px-8 text-center align-top border-l border-gray-50/50" rowspan="{{ $order->items->count() }}">
                                        @if($order->imp_uid)
                                        <button type="button" 
                                                onclick="openReceiptModal('{{ $order->receipt_url }}')"
                                                class="px-4 py-1.5 bg-white border border-gray-200 rounded-xl text-[11px] font-black text-text-main hover:bg-primary hover:text-white hover:border-primary transition-all shadow-sm">
                                            인쇄
                                        </button>
                                        @else
                                        <span class="text-[11px] font-bold text-text-muted">발급불가</span>
                                        @endif
                                    </td>                                    @endif
                                </tr>
                                @endforeach
                            @empty
                            <tr>
                                <td colspan="5" class="py-32 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="size-20 rounded-full bg-gray-50 flex items-center justify-center mb-6">
                                            <span class="material-symbols-outlined text-4xl text-gray-200">receipt_long</span>
                                        </div>
                                        <p class="text-text-muted font-bold">발급 가능한 증빙 서류 내역이 없습니다.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($receipts->hasPages())
                <div class="p-8 border-t border-gray-50">
                    {{ $receipts->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</main>

<!-- Receipt Modal -->
<div id="receiptModal" class="fixed inset-0 z-[200] hidden overflow-hidden">
    <!-- Overlay -->
    <div id="receiptModalOverlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity cursor-pointer"></div>

    <!-- Modal Panel -->
    <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-lg transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all animate-fade-in-up flex flex-col h-[85vh] sm:h-[800px]">
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-white z-10 shrink-0">
                    <h3 class="text-lg font-extrabold text-text-main flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">receipt_long</span> 영수증 / 매출전표
                    </h3>
                    <button type="button" id="btnReceiptModalClose" class="text-gray-400 hover:text-text-main hover:bg-gray-100 p-2 rounded-full transition-colors flex items-center justify-center">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <!-- Iframe Container -->
                <div class="flex-1 w-full relative bg-gray-50 overflow-hidden">
                    <div id="receiptLoading" class="absolute inset-0 flex flex-col items-center justify-center bg-white z-10 transition-opacity duration-300">
                        <span class="material-symbols-outlined animate-spin text-primary text-4xl mb-4">refresh</span>
                        <p class="text-sm font-bold text-text-muted">영수증 정보를 불러오는 중입니다...</p>
                    </div>
                    <!-- iframe loading="lazy" 로 설정하여 처음 빈화면일 때는 안보이다가 URL 세팅 후 보이게 함 -->
                    <iframe id="receiptIframe" src="about:blank" class="absolute inset-0 w-full h-full border-0" title="영수증"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/ko.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const monthsInput = document.getElementById('selected-months');
        const receiptModal = document.getElementById('receiptModal');
        const receiptModalOverlay = document.getElementById('receiptModalOverlay');
        const btnReceiptModalClose = document.getElementById('btnReceiptModalClose');
        const receiptIframe = document.getElementById('receiptIframe');
        const receiptLoading = document.getElementById('receiptLoading');

        // Modal Functions
        window.openReceiptModal = function(url) {
            receiptModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // 화면 스크롤 방지
            
            receiptLoading.classList.remove('opacity-0', 'pointer-events-none');
            
            receiptIframe.onload = function() {
                receiptLoading.classList.add('opacity-0', 'pointer-events-none');
            };
            
            receiptIframe.src = url;
        };

        function closeReceiptModal() {
            receiptModal.classList.add('hidden');
            document.body.style.overflow = '';
            receiptIframe.src = 'about:blank'; // 닫을 때 내용 초기화
        }

        [btnReceiptModalClose, receiptModalOverlay].forEach(el => {
            if(el) el.addEventListener('click', closeReceiptModal);
        });

        
        const fpStart = flatpickr("input[name='start_date']", {
            locale: "ko",
            dateFormat: "Y-m-d",
            disableMobile: "true",
            onChange: function() {
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

        document.querySelectorAll('.btn-period').forEach(btn => {
            btn.addEventListener('click', function() {
                const months = parseInt(this.dataset.months);
                monthsInput.value = months;

                const now = new Date();
                const startDate = new Date();
                startDate.setMonth(now.getMonth() - months);

                const formatDate = (date) => {
                    const y = date.getFullYear();
                    const m = String(date.getMonth() + 1).padStart(2, '0');
                    const d = String(date.getDate()).padStart(2, '0');
                    return `${y}-${m}-${d}`;
                };

                fpStart.setDate(formatDate(startDate));
                fpEnd.setDate(formatDate(now));

                resetPeriodButtons();
                this.classList.remove('bg-white', 'text-text-muted', 'border-gray-200');
                this.classList.add('bg-primary', 'text-white', 'border-primary', 'shadow-md', 'shadow-primary/20');
            });
        });
    });
</script>
@endpush
@endsection
