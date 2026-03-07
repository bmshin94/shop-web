@extends('layouts.admin')

@section('page_title', '기획전 등록')

@section('content')
<div class="space-y-6 lg:space-y-8">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.exhibitions.index') }}" class="flex items-center justify-center size-10 rounded-xl bg-white border border-gray-200 text-text-muted hover:text-primary hover:border-primary transition-all shadow-sm">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <h3 class="text-2xl font-extrabold text-text-main">기획전 등록</h3>
                <p class="mt-1 text-[12px] font-bold text-text-muted">운영 중인 기획전을 등록하고 상태/기간을 설정합니다.</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
        @include('admin.exhibitions._form', [
            'statusOptions' => $statusOptions,
            'formAction' => route('admin.exhibitions.store'),
            'formMethod' => 'POST',
            'submitLabel' => '기획전 등록',
        ])
    </div>
</div>
@endsection

