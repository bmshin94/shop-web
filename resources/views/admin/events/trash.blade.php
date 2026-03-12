@extends('layouts.admin')

@section('page_title', '이벤트 휴지통')

@push('styles')
<style>
    .event-row {
        display: grid;
        align-items: center;
        grid-template-columns: 1.4fr 0.8fr 0.8fr 1fr;
        gap: 12px;
    }

    @media (min-width: 1024px) {
        .event-row {
            grid-template-columns: 1.7fr 0.9fr 0.7fr 1.1fr 220px;
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
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h3 class="text-xl font-black text-text-main">삭제된 이벤트</h3>
            <p class="mt-1 text-[12px] font-bold text-text-muted">soft delete 처리된 이벤트만 표시됩니다.</p>
        </div>
        <a href="{{ route('admin.events.index') }}" class="inline-flex items-center justify-center gap-1 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-bold text-text-main hover:border-primary hover:text-primary transition-all">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
            이벤트 목록으로
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
        <form action="{{ route('admin.events.trash') }}" method="GET" class="space-y-4">
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
                    <a href="{{ route('admin.events.trash') }}" class="flex-1 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-bold text-text-muted hover:bg-gray-50 transition-colors text-center">
                        초기화
                    </a>
                    <button type="submit" class="flex-1 px-6 py-2.5 bg-text-main text-white rounded-xl text-sm font-bold hover:bg-black transition-colors">
                        검색
                    </button>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-100">
                <input type="text" name="start_from" value="{{ request('start_from') }}" class="datepicker px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
                <input type="text" name="start_to" value="{{ request('start_to') }}" class="datepicker px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
            </div>
        </form>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-white/80 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <p class="text-sm font-extrabold text-text-main">휴지통 {{ number_format($events->total()) }}건</p>
            <div class="flex items-center gap-2 text-[12px] font-bold text-text-muted">
                @if($activeFilterCount > 0)
                    <span class="inline-flex items-center rounded-full bg-primary/5 px-3 py-1 text-primary">적용된 필터 {{ $activeFilterCount }}개</span>
                @endif
                <span>페이지 {{ number_format($events->currentPage()) }} / {{ number_format($events->lastPage()) }}</span>
            </div>
        </div>

        <div class="hidden lg:grid event-row px-6 py-4 bg-gray-50/70 border-b border-gray-100">
            <div class="text-[11px] font-bold text-text-muted uppercase">이벤트 정보</div>
            <div class="text-center text-[11px] font-bold text-text-muted uppercase">상태</div>
            <div class="text-center text-[11px] font-bold text-text-muted uppercase">정렬</div>
            <div class="text-center text-[11px] font-bold text-text-muted uppercase">삭제일</div>
            <div class="text-center text-[11px] font-bold text-text-muted uppercase">관리</div>
        </div>

        <div class="divide-y divide-gray-50">
            @forelse($events as $event)
                <div class="event-row px-4 lg:px-6 py-4 hover:bg-gray-50/60 transition-colors">
                    <div class="min-w-0">
                        <p class="text-sm font-extrabold text-text-main truncate">{{ $event->title }}</p>
                        <p class="mt-1 text-[12px] font-bold text-text-muted truncate">/{{ $event->slug }}</p>
                        <p class="mt-1 text-[12px] font-bold text-text-muted truncate">{{ $event->summary ?: '요약 없음' }}</p>
                    </div>
                    <div class="text-left lg:text-center">
                        <x-admin.status-badge type="event" :value="$event->status" />
                    </div>
                    <div class="text-left lg:text-center">
                        <p class="text-sm font-extrabold text-text-main">{{ number_format($event->sort_order) }}</p>
                    </div>
                    <div class="text-left lg:text-center">
                        <p class="text-[12px] font-bold text-text-main">{{ optional($event->deleted_at)->format('Y.m.d H:i') ?: '-' }}</p>
                    </div>
                    <div class="flex lg:justify-center gap-2">
                        <form action="{{ route('admin.events.restore', $event) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="inline-flex items-center justify-center rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-[12px] font-bold text-emerald-700 hover:bg-emerald-100 transition-colors">
                                복구
                            </button>
                        </form>
                        <form
                            action="{{ route('admin.events.force-destroy', $event) }}"
                            method="POST"
                            class="js-confirm-submit"
                            data-confirm-title="이벤트 영구 삭제"
                            data-confirm-message="이 이벤트를 영구 삭제하시겠습니까? 삭제 후에는 복구할 수 없습니다."
                            data-confirm-text="영구 삭제">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-[12px] font-bold text-red-700 hover:bg-red-100 transition-colors">
                                영구삭제
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="px-6 py-20 text-center">
                    <div class="size-20 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-gray-300 text-[40px]">delete</span>
                    </div>
                    <p class="text-text-muted text-sm font-bold">
                        {{ $activeFilterCount > 0 ? '조건에 맞는 삭제 이벤트가 없습니다.' : '휴지통이 비어 있습니다.' }}
                    </p>
                    <p class="mt-2 text-[12px] font-bold text-text-muted">
                        {{ $activeFilterCount > 0 ? '검색 조건을 조정하거나 초기화 후 다시 확인해 주세요.' : '이벤트를 삭제하면 이곳에서 복구 또는 영구삭제할 수 있습니다.' }}
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
