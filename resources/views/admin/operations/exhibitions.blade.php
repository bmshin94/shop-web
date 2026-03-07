@extends('layouts.admin')

@section('page_title', '기획전 관리')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8">
        <div class="flex items-center gap-3 mb-4">
            <div class="size-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-[28px]">storefront</span>
            </div>
            <div>
                <h3 class="text-xl font-black text-text-main">기획전 관리</h3>
                <p class="mt-1 text-[12px] font-bold text-text-muted">기획전 등록/상품 연결/노출 순서를 관리하는 영역입니다.</p>
            </div>
        </div>

        <div class="rounded-2xl border border-dashed border-gray-200 bg-gray-50 px-6 py-8 text-center">
            <p class="text-sm font-bold text-text-main">기획전 관리 기능 준비 중</p>
            <p class="mt-2 text-[12px] font-bold text-text-muted">메뉴 분리 완료. 다음 단계에서 기획전 생성/전시 상품 구성 기능을 추가하면 됩니다.</p>
        </div>
    </div>
</div>
@endsection
