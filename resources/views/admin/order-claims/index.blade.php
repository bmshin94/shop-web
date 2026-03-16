@extends('layouts.admin')

@section('page_title', '교환 / 반품 관리')

@push('styles')
<style>
    .claim-row {
        display: grid;
        align-items: center;
        grid-template-columns: 1.3fr 1fr 0.8fr 0.8fr;
        gap: 12px;
    }

    @media (min-width: 1024px) {
        .claim-row {
            grid-template-columns: 1.4fr 1.1fr 1fr 1fr 1.2fr 100px;
            gap: 16px;
        }
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- 교환/반품 요약 -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4 lg:gap-6">
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">assignment_return</span>
                </div>
                <span class="text-[11px] font-bold text-text-muted uppercase">전체 신청</span>
            </div>
            <p class="text-3xl font-black text-text-main">{{ number_format($stats['total']) }}</p>
        </div>
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">move_to_inbox</span>
                </div>
                <span class="text-[11px] font-bold text-text-muted uppercase">접수</span>
            </div>
            <p class="text-3xl font-black text-text-main">{{ number_format($stats['received']) }}</p>
        </div>
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">sync</span>
                </div>
                <span class="text-[11px] font-bold text-text-muted uppercase">처리중</span>
            </div>
            <p class="text-3xl font-black text-text-main">{{ number_format($stats['processing']) }}</p>
        </div>
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 rounded-2xl bg-green-50 text-green-500 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">task_alt</span>
                </div>
                <span class="text-[11px] font-bold text-text-muted uppercase">완료</span>
            </div>
            <p class="text-3xl font-black text-text-main">{{ number_format($stats['completed']) }}</p>
        </div>
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">cancel</span>
                </div>
                <span class="text-[11px] font-bold text-text-muted uppercase">거절</span>
            </div>
            <p class="text-3xl font-black text-text-main">{{ number_format($stats['rejected']) }}</p>
        </div>
    </div>

    <!-- 필터 영역 -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
        <form action="{{ route('admin.order-claims.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <div class="lg:col-span-2 relative">
                    <span class="material-symbols-outlined absolute left-3 top-3 text-text-muted text-[18px]">search</span>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="신청번호, 주문번호, 신청인 검색"
                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
                </div>
                <div class="relative">
                    <select name="type" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all">
                        <option value="">모든 유형</option>
                        @foreach($typeOptions as $value => $label)
                            <option value="{{ $value }}" {{ request('type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="relative">
                    <select name="status" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all">
                        <option value="">모든 상태</option>
                        @foreach($statusOptions as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 pt-4 border-t border-gray-100">
                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <div class="relative flex-1 group">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-muted text-[18px] group-focus-within:text-primary transition-colors pointer-events-none">calendar_today</span>
                        <input type="text" name="date_from" value="{{ request('date_from') }}" placeholder="신청 시작일" class="datepicker w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all font-medium">
                    </div>
                    <span class="text-gray-300 hidden sm:block">~</span>
                    <div class="relative flex-1 group">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-muted text-[18px] group-focus-within:text-primary transition-colors pointer-events-none">calendar_month</span>
                        <input type="text" name="date_to" value="{{ request('date_to') }}" placeholder="신청 종료일" class="datepicker w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all font-medium">
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.order-claims.index') }}" class="px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-bold text-text-muted hover:bg-gray-50 transition-colors">
                        초기화
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-text-main text-white rounded-xl text-sm font-bold hover:bg-black transition-colors">
                        검색
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- 신청 목록 -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-white/80 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <p class="text-sm font-extrabold text-text-main">
                검색 결과 {{ number_format($claims->total()) }}건
            </p>
            <div class="flex items-center gap-2 text-[12px] font-bold text-text-muted">
                <a href="{{ route('admin.order-claims.trash') }}" class="inline-flex items-center gap-1 rounded-full border border-gray-200 bg-white px-3 py-1 text-text-main hover:border-primary hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[14px]">delete</span>
                    휴지통 {{ number_format($trashedCount ?? 0) }}
                </a>
                <span>페이지 {{ number_format($claims->currentPage()) }} / {{ number_format($claims->lastPage()) }}</span>
            </div>
        </div>

        <div class="hidden lg:grid claim-row px-6 py-4 bg-gray-50/70 border-b border-gray-100">
            <div class="text-[11px] font-bold text-text-muted uppercase">신청번호 / 주문번호</div>
            <div class="text-[11px] font-bold text-text-muted uppercase">신청자 / 유형</div>
            <div class="text-center text-[11px] font-bold text-text-muted uppercase">사유</div>
            <div class="text-center text-[11px] font-bold text-text-muted uppercase">상태</div>
            <div class="text-right text-[11px] font-bold text-text-muted uppercase">신청일 / 처리일</div>
            <div class="text-center text-[11px] font-bold text-text-muted uppercase">관리</div>
        </div>

        <div class="divide-y divide-gray-50">
            @forelse($claims as $claim)
                <div class="claim-row px-4 lg:px-6 py-4 hover:bg-gray-50/60 transition-colors">
                    <div class="min-w-0">
                        <a href="{{ route('admin.order-claims.show', $claim) }}" class="text-sm font-extrabold text-text-main hover:text-primary transition-colors block truncate">
                            {{ $claim->claim_number }}
                        </a>
                        <p class="mt-1 text-[12px] font-bold text-text-muted truncate">
                            주문: {{ optional($claim->order)->order_number }}
                        </p>
                    </div>

                    <div class="min-w-0">
                        <p class="text-sm font-bold text-text-main truncate">{{ optional($claim->member)->name }}</p>
                        <p class="mt-1 text-[12px] font-bold {{ $claim->type === 'exchange' ? 'text-blue-500' : 'text-red-500' }}">
                            {{ $claim->type === 'exchange' ? '교환 신청' : '반품 신청' }}
                        </p>
                    </div>

                    <div class="text-left lg:text-center min-w-0">
                        <p class="text-[13px] font-bold text-text-main truncate">{{ $claim->reason_type }}</p>
                        <p class="mt-1 text-[11px] font-medium text-text-muted truncate">{{ $claim->reason_detail }}</p>
                    </div>

                    <div class="text-left lg:text-center">
                        <x-admin.status-badge type="claim" :value="$claim->status" />
                    </div>

                    <div class="text-left lg:text-right">
                        <p class="text-[12px] font-bold text-text-main">{{ $claim->created_at->format('Y.m.d H:i') }}</p>
                        <p class="mt-1 text-[11px] font-bold text-text-muted">
                            {{ $claim->processed_at ? $claim->processed_at->format('Y.m.d H:i') : '-' }}
                        </p>
                    </div>

                    <div class="flex lg:justify-center">
                        <a href="{{ route('admin.order-claims.show', $claim) }}" class="inline-flex items-center justify-center gap-1 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-[12px] font-bold text-text-main hover:border-primary hover:text-primary transition-all shadow-sm">
                            상세
                        </a>
                    </div>
                </div>
            @empty
                <div class="px-6 py-20 text-center">
                    <div class="size-20 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-gray-300 text-[40px]">assignment_return</span>
                    </div>
                    <p class="text-text-muted text-sm font-bold">신청 내역이 없습니다.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-10">
        {{ $claims->links() }}
    </div>
</div>
@endsection
