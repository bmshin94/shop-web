@extends('layouts.admin')

@section('page_title', '쿠폰 관리')

@push('styles')
<style>
    .coupon-row {
        display: grid;
        align-items: center;
        grid-template-columns: 1fr;
        gap: 12px;
    }

    @media (min-width: 1024px) {
        .coupon-row {
            grid-template-columns: 80px 2fr 1fr 1.5fr 1fr 1fr 1fr 120px;
            gap: 16px;
        }
    }
</style>
@endpush

@section('content')
@php
    $activeFilterCount = collect([
        request('search'),
        request('type'),
        request('status'),
    ])->filter(fn ($value) => filled($value))->count();
@endphp

<div class="space-y-6">
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h2 class="text-xl font-black text-text-main">쿠폰 목록</h2>
        <a href="{{ route('admin.coupons.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-xl text-sm font-bold shadow-lg shadow-primary/20 hover:bg-red-600 transition-all active:scale-95">
            <span class="material-symbols-outlined text-[18px]">add_circle</span>
            새 쿠폰 등록
        </a>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
        <form action="{{ route('admin.coupons.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <div class="lg:col-span-2 relative">
                    <span class="material-symbols-outlined absolute left-3 top-3 text-text-muted text-[18px]">search</span>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="쿠폰명 또는 코드 검색"
                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all">
                </div>
                <div class="relative">
                    <select name="type" class="w-full px-4 py-2.5 pr-10 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 appearance-none outline-none transition-all cursor-pointer">
                        <option value="">모든 쿠폰 유형</option>
                        <option value="discount" {{ request('type') == 'discount' ? 'selected' : '' }}>할인 쿠폰</option>
                        <option value="shipping" {{ request('type') == 'shipping' ? 'selected' : '' }}>배송비 쿠폰</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-3 text-text-muted text-[18px] pointer-events-none">expand_more</span>
                </div>
                <div class="relative">
                    <select name="status" class="w-full px-4 py-2.5 pr-10 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 appearance-none outline-none transition-all cursor-pointer">
                        <option value="">모든 상태</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>활성</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>비활성</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-3 text-text-muted text-[18px] pointer-events-none">expand_more</span>
                </div>
            </div>
            <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                <a href="{{ route('admin.coupons.index') }}" class="px-6 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-bold text-text-muted hover:bg-gray-50 transition-colors text-center">
                    초기화
                </a>
                <button type="submit" class="px-8 py-2.5 bg-text-main text-white rounded-xl text-sm font-bold hover:bg-black transition-colors">
                    조회
                </button>
            </div>
        </form>
    </div>

    <!-- List Section -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-white/80 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <p class="text-sm font-extrabold text-text-main">
                검색 결과 <span class="text-primary">{{ number_format($coupons->total()) }}</span>건
            </p>
            <div class="flex items-center gap-2 text-[12px] font-bold text-text-muted">
                @if($activeFilterCount > 0)
                    <span class="inline-flex items-center rounded-full bg-primary/5 px-3 py-1 text-primary">
                        적용된 필터 {{ $activeFilterCount }}개
                    </span>
                @endif
                <span>페이지 {{ number_format($coupons->currentPage()) }} / {{ number_format($coupons->lastPage()) }}</span>
            </div>
        </div>

        <div class="hidden lg:grid coupon-row px-6 py-4 bg-gray-50/70 border-b border-gray-100">
            <div class="text-[11px] font-bold text-text-muted uppercase">No.</div>
            <div class="text-[11px] font-bold text-text-muted uppercase">쿠폰 정보</div>
            <div class="text-center text-[11px] font-bold text-text-muted uppercase">유형</div>
            <div class="text-center text-[11px] font-bold text-text-muted uppercase">혜택 내용</div>
            <div class="text-center text-[11px] font-bold text-text-muted uppercase">최소 주문금액</div>
            <div class="text-center text-[11px] font-bold text-text-muted uppercase">상태</div>
            <div class="text-center text-[11px] font-bold text-text-muted uppercase">사용 기간</div>
            <div class="text-center text-[11px] font-bold text-text-muted uppercase">관리</div>
        </div>

        <div class="divide-y divide-gray-50">
            @forelse($coupons as $coupon)
                <div class="coupon-row px-4 lg:px-6 py-5 hover:bg-gray-50/60 transition-colors">
                    <div class="hidden lg:block text-[12px] font-bold text-gray-400">
                        {{ str_pad($coupon->id, 5, '0', STR_PAD_LEFT) }}
                    </div>

                    <div class="min-w-0">
                        <p class="text-sm font-extrabold text-text-main truncate">{{ $coupon->name }}</p>
                        <p class="mt-1 text-[11px] font-bold text-text-muted inline-flex px-2 py-0.5 bg-gray-100 rounded-md truncate">
                            {{ $coupon->code ?: '공용(코드없음)' }}
                        </p>
                    </div>

                    <div class="text-left lg:text-center">
                        <span class="inline-flex px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider {{ $coupon->type === 'shipping' ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-primary-light text-primary border border-primary/10' }}">
                            {{ $coupon->type === 'shipping' ? '배송비' : '할인' }}
                        </span>
                    </div>

                    <div class="text-left lg:text-center">
                        <p class="text-[13px] font-black text-text-main">
                            @if($coupon->discount_type === 'percent')
                                {{ $coupon->discount_value }}<span class="text-[11px] text-text-muted">% 할인</span>
                            @else
                                {{ number_format($coupon->discount_value) }}<span class="text-[11px] text-text-muted">원 할인</span>
                            @endif
                        </p>
                        @if($coupon->max_discount_amount)
                            <p class="mt-1 text-[10px] font-bold text-gray-400 truncate">최대 {{ number_format($coupon->max_discount_amount) }}원</p>
                        @endif
                    </div>

                    <div class="text-left lg:text-center">
                        <p class="text-[12px] font-bold text-text-main">
                            {{ $coupon->min_order_amount > 0 ? number_format($coupon->min_order_amount).'원' : '제한없음' }}
                        </p>
                    </div>

                    <div class="text-left lg:text-center">
                        <span class="inline-flex items-center gap-1 text-[12px] font-black {{ $coupon->is_active ? 'text-green-500' : 'text-gray-400' }}">
                            <span class="size-1.5 rounded-full {{ $coupon->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                            {{ $coupon->is_active ? '활성' : '비활성' }}
                        </span>
                    </div>

                    <div class="text-left lg:text-center">
                        <p class="text-[11px] font-bold text-text-muted leading-tight">
                            <span class="text-text-main block mb-0.5">{{ $coupon->starts_at ? $coupon->starts_at->format('Y.m.d') : '무제한' }}</span>
                            ~ <span class="text-text-main block mt-0.5">{{ $coupon->ends_at ? $coupon->ends_at->format('Y.m.d') : '무제한' }}</span>
                        </p>
                    </div>

                    <div class="flex lg:justify-center gap-2">
                        <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="inline-flex size-9 items-center justify-center bg-white border border-gray-200 rounded-xl text-text-main hover:border-primary hover:text-primary transition-all shadow-sm">
                            <span class="material-symbols-outlined text-[18px]">edit</span>
                        </a>
                        <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" 
                              class="js-confirm-submit inline-block" 
                              data-confirm-title="쿠폰 삭제" 
                              data-confirm-message="정말 이 쿠폰을 삭제하시겠습니까?<br>삭제된 쿠폰은 휴지통으로 이동하며, 발급된 내역은 유지되지만 신규 발급은 중단됩니다."
                              data-confirm-text="삭제하기">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex size-9 items-center justify-center bg-white border border-gray-200 rounded-xl text-red-500 hover:border-red-500 hover:bg-red-50 transition-all shadow-sm">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="px-6 py-20 text-center">
                    <div class="size-20 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-gray-300 text-[40px]">confirmation_number</span>
                    </div>
                    <p class="text-text-muted text-sm font-bold">
                        {{ $activeFilterCount > 0 ? '조건에 맞는 쿠폰이 없습니다.' : '등록된 쿠폰이 없습니다.' }}
                    </p>
                    <p class="mt-2 text-[12px] font-bold text-text-muted">
                        우측 상단의 '새 쿠폰 등록' 버튼을 눌러 첫 번째 혜택을 만들어보세요.
                    </p>
                </div>
            @endforelse
        </div>
    </div>

    @if($coupons->hasPages())
    <div class="mt-10">
        {{ $coupons->links() }}
    </div>
    @endif
</div>
@endsection
