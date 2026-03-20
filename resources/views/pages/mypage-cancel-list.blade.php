@extends('layouts.app')

@section('title', '취소/교환/반품 내역 | 마이페이지 - Active Women\'s Premium Store')

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
        <span class="font-bold text-text-main">취소/교환/반품 내역</span>
    </nav>

    <!-- Page Title -->
    <h2 class="text-3xl font-extrabold text-text-main tracking-tight mb-8">취소/교환/반품 내역</h2>

    <div class="flex flex-col lg:flex-row gap-8 items-start">
        <!-- LNB -->
        @include('partials.mypage-sidebar')

        <!-- Main Content -->
        <div class="flex-1 w-full space-y-6">
            
            <!-- Modern Filter Section -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 transition-all">
                <form action="{{ route('mypage.cancel-list') }}" method="GET" class="p-5 lg:p-8 space-y-5">
                    <input type="hidden" name="months" id="selected-months" value="{{ $months }}">
                    <!-- Top Row: Search & Type & Status -->
                    <div class="flex flex-col lg:flex-row gap-4">
                        <div class="flex-1 relative group">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 group-focus-within:text-primary transition-colors">search</span>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="상품명 또는 주문번호 검색" 
                                   class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-transparent rounded-2xl text-sm focus:ring-2 focus:ring-primary/20 focus:bg-white focus:border-primary/30 transition-all outline-none font-semibold">
                        </div>
                        <div class="w-full lg:w-[180px] relative">
                            <select name="type" class="w-full pl-4 pr-10 py-3 bg-gray-50 border border-transparent rounded-2xl text-sm focus:ring-2 focus:ring-primary/20 focus:bg-white focus:border-primary/30 transition-all outline-none font-medium appearance-none cursor-pointer !bg-none">
                                <option value="">모든 신청유형</option>
                                <option value="cancel" {{ request('type') == 'cancel' ? 'selected' : '' }}>주문취소</option>
                                <option value="exchange" {{ request('type') == 'exchange' ? 'selected' : '' }}>교환신청</option>
                                <option value="return" {{ request('type') == 'return' ? 'selected' : '' }}>반품신청</option>
                            </select>
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 pointer-events-none">expand_more</span>
                        </div>
                        <div class="w-full lg:w-[180px] relative">
                            <select name="status" class="w-full pl-4 pr-10 py-3 bg-gray-50 border border-transparent rounded-2xl text-sm focus:ring-2 focus:ring-primary/20 focus:bg-white focus:border-primary/30 transition-all outline-none font-medium appearance-none cursor-pointer !bg-none">
                                <option value="">모든 진행상태</option>
                                <option value="접수" {{ request('status') == '접수' ? 'selected' : '' }}>접수</option>
                                <option value="처리중" {{ request('status') == '처리중' ? 'selected' : '' }}>처리중</option>
                                <option value="완료" {{ request('status') == '완료' ? 'selected' : '' }}>완료</option>
                                <option value="거부" {{ request('status') == '거부' ? 'selected' : '' }}>거부</option>
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
                            <a href="{{ route('mypage.cancel-list') }}" title="초기화" class="size-10 flex items-center justify-center rounded-xl bg-gray-100 text-gray-500 hover:bg-gray-200 transition-colors shrink-0 ml-auto lg:ml-0">
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
                <!-- PC Version: Table (Hidden on mobile) -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th class="py-5 px-8 text-[13px] font-semibold text-text-muted uppercase tracking-widest text-center">접수일자 / 번호</th>
                                <th class="py-5 px-6 text-[13px] font-semibold text-text-muted uppercase tracking-widest">상품정보</th>
                                <th class="py-5 px-6 text-[13px] font-semibold text-text-muted uppercase tracking-widest text-center">신청 유형</th>
                                <th class="py-5 px-8 text-[13px] font-semibold text-text-muted uppercase tracking-widest text-center">진행상태</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($cancels as $order)
                            <tr class="group hover:bg-gray-50/30 transition-colors">
                                <td class="py-6 px-8 text-center border-r border-gray-50/50 align-top">
                                    <p class="font-semibold text-text-main text-sm">{{ $order->created_at->format('Y.m.d') }}</p>
                                    <p class="inline-block mt-2 px-2.5 py-1 bg-gray-50 rounded-lg text-xs font-semibold text-text-muted tracking-tighter">
                                        {{ $order->number }}
                                    </p>
                                </td>
                                <td class="py-6 px-6">
                                    <div class="space-y-4">
                                        @foreach($order->items as $item)
                                        <div class="flex items-center gap-5 {{ !$loop->last ? 'mb-4 pb-4 border-b border-gray-50' : '' }}">
                                            <div class="size-20 bg-gray-100 rounded-2xl overflow-hidden shrink-0 border border-gray-100 shadow-sm">
                                                <img src="{{ optional($item->product)->image_url }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-semibold text-text-main line-clamp-1 group-hover:text-primary transition-colors">
                                                    {{ $item->product_name }}
                                                </p>
                                                @if(isset($item->option_summary))
                                                <p class="text-[11px] text-text-muted mt-1.5 font-medium bg-gray-100 inline-block px-2 py-0.5 rounded-md">
                                                    {{ $item->option_summary }}
                                                </p>
                                                @endif
                                                <p class="text-[11px] text-text-muted mt-1 font-semibold">{{ number_format($item->quantity) }}개</p>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="py-6 px-6 text-center align-top border-l border-gray-50/50">
                                    @php
                                        $typeClasses = [
                                            '교환' => 'bg-blue-50 text-blue-600 border-blue-100',
                                            '반품' => 'bg-red-50 text-red-600 border-red-100',
                                            '취소' => 'bg-gray-100 text-gray-600 border-gray-200',
                                        ];
                                        $typeClass = $typeClasses[$order->type] ?? 'bg-gray-100 text-gray-600 border-gray-200';
                                    @endphp
                                    <span class="inline-flex py-1 px-3 {{ $typeClass }} font-bold text-[10px] rounded-md border shadow-sm">
                                        {{ $order->type === '취소' ? '주문취소' : $order->type . '신청' }}
                                    </span>
                                </td>
                                <td class="py-6 px-8 text-center align-top border-l border-gray-50/50">
                                    @if($order->is_claim)
                                        <x-admin.status-badge type="claim" :value="$order->status" />
                                    @else
                                        <x-admin.status-badge type="order" :value="$order->status" />
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-32 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="size-20 rounded-full bg-gray-50 flex items-center justify-center mb-6">
                                            <span class="material-symbols-outlined text-4xl text-gray-200">inventory_2</span>
                                        </div>
                                        <p class="text-text-muted font-bold">조회 기간 동안 내역이 없습니다.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Version: Card List (Shown on mobile only) -->
                <div class="md:hidden divide-y divide-gray-50">
                    @forelse($cancels as $order)
                    <div class="p-5 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-text-muted">{{ $order->created_at->format('Y.m.d') }}</span>
                                <span class="text-[10px] text-gray-300">|</span>
                                <span class="text-xs font-black text-text-main">{{ $order->number }}</span>
                            </div>
                            @if($order->is_claim)
                                <x-admin.status-badge type="claim" :value="$order->status" />
                            @else
                                <x-admin.status-badge type="order" :value="$order->status" />
                            @endif
                        </div>

                        <div class="space-y-3">
                            @foreach($order->items as $item)
                            <div class="flex gap-4">
                                <div class="size-16 bg-gray-50 rounded-xl overflow-hidden shrink-0 border border-gray-100">
                                    <img src="{{ optional($item->product)->image_url }}" class="w-full h-full object-cover">
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[13px] font-bold text-text-main line-clamp-1">{{ $item->product_name }}</p>
                                    @if(isset($item->option_summary))
                                    <p class="text-[10px] text-text-muted mt-1 font-medium">{{ $item->option_summary }}</p>
                                    @endif
                                    <p class="text-[11px] text-text-muted mt-1 font-bold">{{ number_format($item->quantity) }}개</p>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="pt-3 border-t border-gray-50 flex items-center justify-between">
                            <span class="text-xs font-bold text-text-muted">신청 유형</span>
                            @php
                                $typeClasses = [
                                    '교환' => 'bg-blue-50 text-blue-600 border-blue-100',
                                    '반품' => 'bg-red-50 text-red-600 border-red-100',
                                    '취소' => 'bg-gray-100 text-gray-600 border-gray-200',
                                ];
                                $typeClass = $typeClasses[$order->type] ?? 'bg-gray-100 text-gray-600 border-gray-200';
                            @endphp
                            <span class="inline-flex py-1 px-3 {{ $typeClass }} font-bold text-[10px] rounded-md border shadow-sm">
                                {{ $order->type === '취소' ? '주문취소' : $order->type . '신청' }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="py-20 text-center">
                        <span class="material-symbols-outlined text-4xl text-gray-200 mb-4">inventory_2</span>
                        <p class="text-text-muted text-sm font-bold">내역이 없습니다.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($cancels->hasPages())
                <div class="p-8 border-t border-gray-50">
                    {{ $cancels->links() }}
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
        const startEl = document.querySelector("input[name='start_date']");
        const endEl = document.querySelector("input[name='end_date']");
        
        // Flatpickr 인스턴스 생성! ✨
        flatpickr(startEl, {
            locale: "ko",
            dateFormat: "Y-m-d",
            disableMobile: true,
            static: true, // 컨테이너 안에서 안정적으로 위치 🚀
            onChange: function() {
                monthsInput.value = '';
                resetPeriodButtons();
            }
        });
        flatpickr(endEl, {
            locale: "ko",
            dateFormat: "Y-m-d",
            disableMobile: true,
            static: true, // 컨테이너 안에서 안정적으로 위치 🚀
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

                const formattedStart = formatDate(startDate);
                const formattedEnd = formatDate(now);

                // 엘리먼트의 _flatpickr 인스턴스를 직접 호출하여 날짜 동기화 🚀
                if (startEl._flatpickr) startEl._flatpickr.setDate(formattedStart);
                if (endEl._flatpickr) endEl._flatpickr.setDate(formattedEnd);

                // 입력창 value 강제 동기화 
                startEl.value = formattedStart;
                endEl.value = formattedEnd;

                // 버튼 스타일 업데이트
                resetPeriodButtons();
                this.classList.remove('bg-white', 'text-text-muted', 'border-gray-200');
                this.classList.add('bg-primary', 'text-white', 'border-primary', 'shadow-md', 'shadow-primary/20');

                // 오빠가 "조회" 버튼 누를 때만 조회되게 자동 제출은 하지 않아! 💖
            });
        });

        // 페이지 로드 시 현재 months 버튼에 불 켜기 ✨
        if (monthsInput.value) {
            const activeBtn = document.querySelector(`.btn-period[data-months="${monthsInput.value}"]`);
            if (activeBtn) {
                resetPeriodButtons();
                activeBtn.classList.remove('bg-white', 'text-text-muted', 'border-gray-200');
                activeBtn.classList.add('bg-primary', 'text-white', 'border-primary', 'shadow-md', 'shadow-primary/20');
            }
        }
    });
</script>
@endpush
@endsection
