@extends('layouts.admin')

@section('page_title', '이벤트 관리')

@push('styles')
<style>
    .event-row {
        display: grid;
        align-items: center;
        grid-template-columns: 1.5fr 0.8fr 1fr 0.6fr;
        gap: 12px;
    }

    @media (min-width: 1024px) {
        .event-row {
            grid-template-columns: 1.7fr 0.9fr 1.1fr 0.7fr 180px;
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
        request('start_from'),
        request('start_to'),
    ])->filter(fn ($value) => filled($value))->count();
@endphp

<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4 lg:gap-6">
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">campaign</span>
                </div>
                <span class="text-[11px] font-bold text-text-muted uppercase">전체 이벤트</span>
            </div>
            <p class="text-3xl font-black text-text-main">{{ number_format($stats['total_events']) }}</p>
        </div>
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">event_upcoming</span>
                </div>
                <span class="text-[11px] font-bold text-text-muted uppercase">진행예정</span>
            </div>
            <p class="text-3xl font-black text-text-main">{{ number_format($stats['upcoming_events']) }}</p>
        </div>
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">bolt</span>
                </div>
                <span class="text-[11px] font-bold text-text-muted uppercase">진행중</span>
            </div>
            <p class="text-3xl font-black text-text-main">{{ number_format($stats['active_events']) }}</p>
        </div>
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 rounded-2xl bg-gray-100 text-gray-500 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">flag</span>
                </div>
                <span class="text-[11px] font-bold text-text-muted uppercase">종료</span>
            </div>
            <p class="text-3xl font-black text-text-main">{{ number_format($stats['ended_events']) }}</p>
        </div>
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">visibility_off</span>
                </div>
                <span class="text-[11px] font-bold text-text-muted uppercase">비노출</span>
            </div>
            <p class="text-3xl font-black text-text-main">{{ number_format($stats['hidden_events']) }}</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
        <form action="{{ route('admin.events.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <div class="lg:col-span-2 relative">
                    <span class="material-symbols-outlined absolute left-3 top-3 text-text-muted text-[18px]">search</span>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="이벤트명, 슬러그, 요약 검색"
                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
                </div>
                <div class="relative">
                    <select name="status" class="w-full px-4 py-2.5 pr-10 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 appearance-none outline-none">
                        <option value="">모든 상태</option>
                        @foreach($statusOptions as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-3 text-text-muted text-[18px] pointer-events-none">expand_more</span>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.events.index') }}" class="flex-1 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-bold text-text-muted hover:bg-gray-50 transition-colors text-center">
                        초기화
                    </a>
                    <button type="submit" class="flex-1 px-6 py-2.5 bg-text-main text-white rounded-xl text-sm font-bold hover:bg-black transition-colors">
                        검색
                    </button>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 pt-4 border-t border-gray-100">
                <div class="relative flex-1 group">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-muted text-[18px] group-focus-within:text-primary transition-colors pointer-events-none">calendar_today</span>
                    <input type="text" name="start_from" value="{{ request('start_from') }}" placeholder="이벤트 시작일" class="datepicker w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all font-medium">
                </div>
                <span class="text-gray-300 hidden sm:block">~</span>
                <div class="relative flex-1 group">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-muted text-[18px] group-focus-within:text-primary transition-colors pointer-events-none">calendar_month</span>
                    <input type="text" name="start_to" value="{{ request('start_to') }}" placeholder="이벤트 종료일" class="datepicker w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all font-medium">
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-white/80 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <p class="text-sm font-extrabold text-text-main">검색 결과 {{ number_format($events->total()) }}건</p>
            <div class="flex flex-wrap items-center gap-2 text-[12px] font-bold text-text-muted">
                <a href="{{ route('admin.events.create') }}" class="inline-flex items-center gap-1 rounded-full border border-primary/20 bg-primary/5 px-3 py-1 text-primary hover:bg-primary hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-[14px]">add</span>
                    이벤트 등록
                </a>
                <a href="{{ route('admin.events.trash') }}" class="inline-flex items-center gap-1 rounded-full border border-gray-200 bg-white px-3 py-1 text-text-main hover:border-primary hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[14px]">delete</span>
                    휴지통 {{ number_format($trashedEventsCount ?? 0) }}
                </a>
                @if($activeFilterCount > 0)
                    <span class="inline-flex items-center rounded-full bg-primary/5 px-3 py-1 text-primary">적용된 필터 {{ $activeFilterCount }}개</span>
                @endif
                <span>페이지 {{ number_format($events->currentPage()) }} / {{ number_format($events->lastPage()) }}</span>
            </div>
        </div>

        <div class="hidden lg:grid event-row px-6 py-4 bg-gray-50/70 border-b border-gray-100">
            <div class="text-[11px] font-bold text-text-muted uppercase">이벤트 정보</div>
            <div class="text-center text-[11px] font-bold text-text-muted uppercase">상태</div>
            <div class="text-center text-[11px] font-bold text-text-muted uppercase">기간</div>
            <div class="text-center text-[11px] font-bold text-text-muted uppercase">정렬</div>
            <div class="text-center text-[11px] font-bold text-text-muted uppercase">관리</div>
        </div>

        <div class="divide-y divide-gray-50">
            @forelse($events as $event)
                <div class="event-row px-4 lg:px-6 py-4 hover:bg-gray-50/60 transition-colors">
                    <div class="min-w-0">
                        <a href="{{ route('admin.events.edit', $event) }}" class="text-sm font-extrabold text-text-main hover:text-primary transition-colors block truncate">
                            {{ $event->title }}
                        </a>
                        <p class="mt-1 text-[12px] font-bold text-text-muted truncate">/{{ $event->slug }}</p>
                        <p class="mt-1 text-[12px] font-bold text-text-muted truncate">{{ $event->summary ?: '요약 없음' }}</p>
                    </div>

                    <div class="text-left lg:text-center">
                        <x-admin.status-badge type="event" :value="$event->status" />
                    </div>

                    <div class="text-left lg:text-center">
                        <p class="text-[12px] font-bold text-text-main">
                            {{ optional($event->start_at)->format('Y.m.d H:i') ?: '-' }}
                        </p>
                        <p class="mt-1 text-[12px] font-bold text-text-muted">
                            ~ {{ optional($event->end_at)->format('Y.m.d H:i') ?: '-' }}
                        </p>
                    </div>

                    <div class="text-left lg:text-center">
                        <p class="text-sm font-extrabold text-text-main">{{ number_format($event->sort_order) }}</p>
                    </div>

                    <div class="flex lg:justify-center gap-2">
                        <a href="{{ route('admin.events.edit', $event) }}" class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-3 py-2 text-[12px] font-bold text-text-main hover:border-primary hover:text-primary transition-colors">
                            수정
                        </a>
                        <form
                            action="{{ route('admin.events.destroy', $event) }}"
                            method="POST"
                            class="js-confirm-submit"
                            data-confirm-title="이벤트 삭제"
                            data-confirm-message="이 이벤트를 soft delete 처리하시겠습니까? 목록에서 숨김 처리됩니다."
                            data-confirm-text="삭제 처리">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-[12px] font-bold text-red-700 hover:bg-red-100 transition-colors">
                                삭제
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="px-6 py-20 text-center">
                    <div class="size-20 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-gray-300 text-[40px]">campaign</span>
                    </div>
                    <p class="text-text-muted text-sm font-bold">
                        {{ $activeFilterCount > 0 ? '조건에 맞는 이벤트가 없습니다.' : '등록된 이벤트가 없습니다.' }}
                    </p>
                    <p class="mt-2 text-[12px] font-bold text-text-muted">
                        {{ $activeFilterCount > 0 ? '검색 조건을 조정하거나 초기화 후 다시 확인해 주세요.' : '이벤트를 등록하면 운영 메뉴에서 상태와 기간을 관리할 수 있습니다.' }}
                    </p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-10">
        {{ $events->links() }}
    </div>
</div>
@endsection
