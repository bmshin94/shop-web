@extends('layouts.admin')

@section('page_title', '기획전 상세 정보')

@section('content')
<div class="space-y-6 lg:space-y-8">
    {{-- 상단 네비게이션 및 액션 버튼  --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.exhibitions.index', request()->query()) }}" class="flex items-center justify-center size-10 rounded-xl bg-white border border-gray-200 text-text-muted hover:text-primary hover:border-primary transition-all shadow-sm">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <h3 class="text-2xl font-extrabold text-text-main">{{ $exhibition->title }}</h3>
                <p class="mt-1 text-[12px] font-bold text-text-muted">기획전 상세 내용을 확인하고 관리합니다.</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.exhibitions.edit', array_merge(['exhibition' => $exhibition->id], request()->query())) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-xl text-sm font-extrabold hover:bg-red-600 transition-colors shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-[18px]">edit</span>
                수정하기
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[1.5fr_1fr] gap-6 lg:gap-8">
        {{-- 왼쪽: 상세 정보  --}}
        <div class="space-y-6">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
                    <h4 class="text-sm font-black text-text-main flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[20px]">info</span>
                        기본 정보
                    </h4>
                    <x-admin.status-badge type="exhibition" :value="$exhibition->status" />
                </div>
                <div class="p-6 space-y-8">
                    {{-- 배너 이미지 --}}
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-text-muted uppercase tracking-wider">배너 이미지</label>
                        <div class="relative aspect-video rounded-2xl overflow-hidden border border-gray-100 shadow-inner bg-gray-50">
                            <img src="{{ $exhibition->banner_image_url ?? 'https://via.placeholder.com/1200x500?text=No+Image' }}" class="w-full h-full object-cover">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-text-muted uppercase tracking-wider">슬러그</label>
                            <p class="text-sm font-bold text-text-main bg-gray-50 px-4 py-2.5 rounded-xl border border-gray-100">/{{ $exhibition->slug }}</p>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-text-muted uppercase tracking-wider">정렬 순서</label>
                            <p class="text-sm font-bold text-text-main bg-gray-50 px-4 py-2.5 rounded-xl border border-gray-100">{{ number_format($exhibition->sort_order) }}</p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-text-muted uppercase tracking-wider">요약 문구</label>
                        <div class="text-sm font-bold text-text-main leading-relaxed bg-gray-50 px-4 py-3 rounded-xl border border-gray-100">
                            {{ $exhibition->summary ?: '등록된 요약 문구가 없습니다.' }}
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-text-muted uppercase tracking-wider">상세 설명</label>
                        <div class="text-sm font-medium text-text-main leading-loose bg-gray-50 px-4 py-4 rounded-xl border border-gray-100 min-h-[100px] whitespace-pre-wrap">
                            {{ $exhibition->description ?: '등록된 상세 설명이 없습니다.' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- 연결 상품 목록  --}}
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-50 bg-gray-50/30 flex items-center justify-between">
                    <h4 class="text-sm font-black text-text-main flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[20px]">link</span>
                        연결된 상품 ({{ $exhibition->products->count() }})
                    </h4>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($exhibition->products as $product)
                        <div class="flex items-center gap-4 p-4 hover:bg-gray-50/50 transition-colors">
                            <div class="size-14 rounded-xl overflow-hidden border border-gray-100 bg-gray-50 shrink-0">
                                <img src="{{ $product->images->first()?->image_url ?? 'https://via.placeholder.com/100' }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-extrabold text-text-main truncate">{{ $product->name }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[11px] font-bold text-primary px-2 py-0.5 bg-primary/5 rounded-md">{{ $product->category?->name ?? '미분류' }}</span>
                                    <span class="text-[11px] font-black text-text-main">₩{{ number_format($product->sale_price ?? $product->price) }}</span>
                                </div>
                            </div>
                            <a href="{{ route('admin.products.edit', $product) }}" class="size-8 flex items-center justify-center rounded-lg bg-gray-100 text-gray-400 hover:bg-text-main hover:text-white transition-all">
                                <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                            </a>
                        </div>
                    @empty
                        <div class="py-12 text-center">
                            <p class="text-sm font-bold text-text-muted">연결된 상품이 없습니다. </p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- 오른쪽: 운영 정보  --}}
        <div class="space-y-6">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                <h4 class="text-sm font-black text-text-main mb-5 uppercase tracking-wider">운영 히스토리</h4>
                <dl class="space-y-5 text-sm">
                    <div class="flex justify-between items-center border-b border-gray-50 pb-3">
                        <dt class="text-[11px] font-bold text-text-muted uppercase">현재 상태</dt>
                        <dd><x-admin.status-badge type="exhibition" :value="$exhibition->status" /></dd>
                    </div>
                    <div class="space-y-2 border-b border-gray-50 pb-3">
                        <dt class="text-[11px] font-bold text-text-muted uppercase">기획전 기간</dt>
                        <dd class="font-extrabold text-text-main flex flex-col gap-1">
                            <span class="flex items-center gap-2">
                                <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                {{ optional($exhibition->start_at)->format('Y.m.d H:i') ?: '시작일 없음' }}
                            </span>
                            <span class="flex items-center gap-2">
                                <span class="size-1.5 rounded-full bg-rose-500"></span>
                                {{ optional($exhibition->end_at)->format('Y.m.d H:i') ?: '종료일 없음' }}
                            </span>
                        </dd>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-50 pb-3">
                        <dt class="text-[11px] font-bold text-text-muted uppercase">최초 생성</dt>
                        <dd class="font-bold text-text-main">{{ optional($exhibition->created_at)->format('Y.m.d H:i') }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-[11px] font-bold text-text-muted uppercase">마지막 수정</dt>
                        <dd class="font-bold text-text-main">{{ optional($exhibition->updated_at)->format('Y.m.d H:i') }}</dd>
                    </div>
                </dl>
            </div>

            {{-- 미리보기 링크  --}}
            <div class="bg-text-main rounded-3xl p-6 text-white shadow-xl shadow-text-main/20">
                <h4 class="text-sm font-black mb-2 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">visibility</span>
                    사용자 페이지 미리보기
                </h4>
                <p class="text-[11px] text-white/60 mb-5 leading-relaxed">사용자에게 보여지는 실제 기획전 상세 화면을 확인합니다.</p>
                <a href="{{ route('exhibition.show', $exhibition->slug) }}" target="_blank" class="flex items-center justify-center w-full py-3 bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl text-sm font-bold transition-all">
                    새 창에서 열기
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
