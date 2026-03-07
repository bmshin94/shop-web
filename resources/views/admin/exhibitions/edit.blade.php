@extends('layouts.admin')

@section('page_title', '기획전 수정')

@section('content')
<div class="space-y-6 lg:space-y-8">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.exhibitions.index') }}" class="flex items-center justify-center size-10 rounded-xl bg-white border border-gray-200 text-text-muted hover:text-primary hover:border-primary transition-all shadow-sm">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <h3 class="text-2xl font-extrabold text-text-main">{{ $exhibition->title }}</h3>
                <p class="mt-1 text-[12px] font-bold text-text-muted">슬러그 /{{ $exhibition->slug }}</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <x-admin.status-badge type="exhibition" :value="$exhibition->status" class="px-3 py-1.5 text-[12px]" />
            <a href="{{ route('admin.exhibitions.trash') }}" class="inline-flex items-center gap-1 rounded-full border border-gray-200 bg-white px-3 py-1.5 text-[12px] font-bold text-text-main hover:border-primary hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[14px]">delete</span>
                휴지통
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[1.3fr_1fr] gap-6">
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
            @include('admin.exhibitions._form', [
                'exhibition' => $exhibition,
                'statusOptions' => $statusOptions,
                'formAction' => route('admin.exhibitions.update', $exhibition),
                'formMethod' => 'PUT',
                'submitLabel' => '기획전 저장',
            ])
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                <h4 class="text-base font-extrabold text-text-main mb-5">기획전 요약</h4>
                <dl class="space-y-4 text-sm">
                    <div>
                        <dt class="text-[11px] font-bold text-text-muted uppercase tracking-widest">상태</dt>
                        <dd class="mt-1"><x-admin.status-badge type="exhibition" :value="$exhibition->status" /></dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-bold text-text-muted uppercase tracking-widest">기간</dt>
                        <dd class="mt-1 font-bold text-text-main">
                            {{ optional($exhibition->start_at)->format('Y.m.d H:i') ?: '-' }}
                            <span class="text-text-muted">~</span>
                            {{ optional($exhibition->end_at)->format('Y.m.d H:i') ?: '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-bold text-text-muted uppercase tracking-widest">정렬 순서</dt>
                        <dd class="mt-1 font-bold text-text-main">{{ number_format($exhibition->sort_order) }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-bold text-text-muted uppercase tracking-widest">생성일</dt>
                        <dd class="mt-1 font-bold text-text-main">{{ optional($exhibition->created_at)->format('Y.m.d H:i') ?: '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-bold text-text-muted uppercase tracking-widest">수정일</dt>
                        <dd class="mt-1 font-bold text-text-main">{{ optional($exhibition->updated_at)->format('Y.m.d H:i') ?: '-' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white rounded-3xl border border-red-100 shadow-sm p-6">
                <h4 class="text-base font-extrabold text-text-main mb-4">기획전 삭제</h4>
                <p class="text-[12px] font-bold text-text-muted leading-relaxed">
                    기획전을 삭제하면 목록에서 숨겨지며(soft delete), 휴지통에서 복구 또는 영구삭제할 수 있습니다.
                </p>
                <form
                    action="{{ route('admin.exhibitions.destroy', $exhibition) }}"
                    method="POST"
                    class="mt-5 js-confirm-submit"
                    data-confirm-title="기획전 삭제"
                    data-confirm-message="이 기획전을 soft delete 처리하시겠습니까? 목록에서 숨김 처리됩니다."
                    data-confirm-text="삭제 처리">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-5 py-4 bg-red-50 text-red-600 border border-red-200 rounded-2xl text-sm font-extrabold hover:bg-red-100 transition-colors">
                        기획전 삭제
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

