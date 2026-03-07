@extends('layouts.admin')

@section('page_title', '운영자 관리')

@push('styles')
<style>
    .operator-row {
        display: grid;
        align-items: center;
        grid-template-columns: 1.6fr 0.9fr 1fr 1fr;
        gap: 12px;
    }

    @media (min-width: 1024px) {
        .operator-row {
            grid-template-columns: 1.7fr 0.9fr 1fr 1fr 120px;
            gap: 16px;
        }
    }
</style>
@endpush

@section('content')
@php
    $activeFilterCount = collect([
        request('search'),
        request('status'),
        request('joined_from'),
        request('joined_to'),
    ])->filter(fn ($value) => filled($value))->count();
@endphp

<div class="space-y-6">
    <div class="flex justify-end">
        <a href="{{ route('admin.operators.create') }}" class="inline-flex items-center justify-center gap-1 px-4 py-2.5 bg-primary text-white rounded-xl text-sm font-bold hover:bg-red-600 transition-colors shadow-sm">
            <span class="material-symbols-outlined text-[16px]">person_add</span>
            운영자 등록
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4 lg:gap-6">
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">badge</span>
                </div>
                <span class="text-[11px] font-bold text-text-muted uppercase tracking-widest">전체 운영자</span>
            </div>
            <p class="text-3xl font-black text-text-main">{{ number_format($stats['total_operators']) }}</p>
        </div>
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 rounded-2xl bg-green-50 text-green-500 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">person_check</span>
                </div>
                <span class="text-[11px] font-bold text-text-muted uppercase tracking-widest">활성</span>
            </div>
            <p class="text-3xl font-black text-text-main">{{ number_format($stats['active_operators']) }}</p>
        </div>
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">bedtime</span>
                </div>
                <span class="text-[11px] font-bold text-text-muted uppercase tracking-widest">휴면</span>
            </div>
            <p class="text-3xl font-black text-text-main">{{ number_format($stats['dormant_operators']) }}</p>
        </div>
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">person_off</span>
                </div>
                <span class="text-[11px] font-bold text-text-muted uppercase tracking-widest">정지</span>
            </div>
            <p class="text-3xl font-black text-text-main">{{ number_format($stats['suspended_operators']) }}</p>
        </div>
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">person_add</span>
                </div>
                <span class="text-[11px] font-bold text-text-muted uppercase tracking-widest">최근 7일 가입</span>
            </div>
            <p class="text-3xl font-black text-text-main">{{ number_format($stats['new_operators_7d']) }}</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
        <form action="{{ route('admin.operators.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <div class="lg:col-span-2 relative">
                    <span class="material-symbols-outlined absolute left-3 top-3 text-text-muted text-[18px]">search</span>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="운영자명, 이메일, 연락처 검색"
                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
                </div>
                <div class="relative">
                    <select name="status" class="w-full px-4 py-2.5 pr-10 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 appearance-none outline-none">
                        <option value="">모든 운영자상태</option>
                        @foreach($statusOptions as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-3 text-text-muted text-[18px] pointer-events-none">expand_more</span>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.operators.index') }}" class="flex-1 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-bold text-text-muted hover:bg-gray-50 transition-colors text-center">
                        초기화
                    </a>
                    <button type="submit" class="flex-1 px-6 py-2.5 bg-text-main text-white rounded-xl text-sm font-bold hover:bg-black transition-colors">
                        검색
                    </button>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-100">
                <input type="date" name="joined_from" value="{{ request('joined_from') }}" class="px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
                <input type="date" name="joined_to" value="{{ request('joined_to') }}" class="px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
            </div>
        </form>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-white/80 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <p class="text-sm font-extrabold text-text-main">
                검색 결과 {{ number_format($operators->total()) }}명
            </p>
            <div class="flex items-center gap-2 text-[12px] font-bold text-text-muted">
                <a href="{{ route('admin.operators.trash') }}" class="inline-flex items-center gap-1 rounded-full border border-gray-200 bg-white px-3 py-1 text-text-main hover:border-primary hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[14px]">delete</span>
                    휴지통 {{ number_format($trashedOperatorsCount ?? 0) }}
                </a>
                @if($activeFilterCount > 0)
                    <span class="inline-flex items-center rounded-full bg-primary/5 px-3 py-1 text-primary">
                        적용된 필터 {{ $activeFilterCount }}개
                    </span>
                @endif
                <span>페이지 {{ number_format($operators->currentPage()) }} / {{ number_format($operators->lastPage()) }}</span>
            </div>
        </div>

        <div class="hidden lg:grid operator-row px-6 py-4 bg-gray-50/70 border-b border-gray-100">
            <div class="text-[11px] font-bold text-text-muted uppercase tracking-widest">운영자 정보</div>
            <div class="text-center text-[11px] font-bold text-text-muted uppercase tracking-widest">운영자상태</div>
            <div class="text-center text-[11px] font-bold text-text-muted uppercase tracking-widest">가입일</div>
            <div class="text-center text-[11px] font-bold text-text-muted uppercase tracking-widest">최근 로그인</div>
            <div class="text-center text-[11px] font-bold text-text-muted uppercase tracking-widest">관리</div>
        </div>

        <div class="divide-y divide-gray-50">
            @forelse($operators as $operator)
                <div class="operator-row px-4 lg:px-6 py-4 hover:bg-gray-50/60 transition-colors">
                    <div class="min-w-0">
                        <a href="{{ route('admin.operators.show', $operator) }}" class="text-sm font-extrabold text-text-main hover:text-primary transition-colors block truncate">
                            {{ $operator->name }}
                        </a>
                        <p class="mt-1 text-[12px] font-bold text-text-muted truncate">{{ $operator->email }}</p>
                        <p class="mt-1 text-[12px] font-bold text-text-muted truncate">{{ $operator->phone ?: '연락처 미등록' }}</p>
                    </div>

                    <div class="text-left lg:text-center">
                        <x-admin.status-badge type="operator" :value="$operator->status" />
                    </div>

                    <div class="text-left lg:text-center">
                        <p class="text-[12px] font-bold text-text-main">{{ optional($operator->created_at)->format('Y.m.d H:i') }}</p>
                    </div>

                    <div class="text-left lg:text-center">
                        <p class="text-[12px] font-bold text-text-main">{{ optional($operator->last_login_at)->format('Y.m.d H:i') ?: '-' }}</p>
                    </div>

                    <div class="flex lg:justify-center">
                        <a href="{{ route('admin.operators.show', $operator) }}" class="inline-flex items-center justify-center gap-1 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-[12px] font-bold text-text-main hover:border-primary hover:text-primary transition-all shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">manage_accounts</span>
                            상세
                        </a>
                    </div>
                </div>
            @empty
                <div class="px-6 py-20 text-center">
                    <div class="size-20 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-gray-300 text-[40px]">badge</span>
                    </div>
                    <p class="text-text-muted text-sm font-bold">
                        {{ $activeFilterCount > 0 ? '조건에 맞는 운영자가 없습니다.' : '등록된 운영자가 없습니다.' }}
                    </p>
                    <p class="mt-2 text-[12px] font-bold text-text-muted">
                        {{ $activeFilterCount > 0 ? '검색 조건을 조정하거나 필터를 초기화해 다시 확인해 주세요.' : '운영자 데이터가 쌓이면 이곳에서 운영자 상태를 관리할 수 있습니다.' }}
                    </p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-10">
        {{ $operators->links() }}
    </div>
</div>
@endsection

