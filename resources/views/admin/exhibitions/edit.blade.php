@extends('layouts.admin')

@section('page_title', '기획전 수정')

@section('content')
<div class="space-y-6 lg:space-y-8">
    {{-- 상단 헤더 영역 ✨ --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.exhibitions.index', request()->query()) }}" class="flex items-center justify-center size-10 rounded-xl bg-white border border-gray-200 text-text-muted hover:text-primary hover:border-primary transition-all shadow-sm">
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

    {{-- 메인 수정 폼 (단일 컬럼으로 시원하게! 🚀) --}}
    <div class="max-w-5xl">
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 lg:p-10">
            @include('admin.exhibitions._form', [
                'exhibition' => $exhibition,
                'statusOptions' => $statusOptions,
                'formAction' => route('admin.exhibitions.update', $exhibition),
                'formMethod' => 'PUT',
                'submitLabel' => '기획전 정보 업데이트',
            ])
        </div>
    </div>
</div>
@endsection
