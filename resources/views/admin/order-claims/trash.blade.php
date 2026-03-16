@extends('layouts.admin')

@section('page_title', '교환 / 반품 휴지통')

@push('styles')
<style>
    .claim-row {
        display: grid;
        align-items: center;
        grid-template-columns: 1.3fr 1fr 1fr 1fr 150px;
        gap: 12px;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.order-claims.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-text-muted hover:text-text-main transition-colors">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            목록으로 돌아가기
        </a>
    </div>

    <!-- 휴지통 목록 -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-white/80 flex items-center justify-between">
            <p class="text-sm font-extrabold text-text-main">
                삭제된 내역 총 {{ number_format($claims->total()) }}건
            </p>
        </div>

        <div class="hidden lg:grid claim-row px-6 py-4 bg-gray-50/70 border-b border-gray-100">
            <div class="text-[11px] font-bold text-text-muted uppercase">신청번호 / 주문번호</div>
            <div class="text-[11px] font-bold text-text-muted uppercase">신청자 / 유형</div>
            <div class="text-center text-[11px] font-bold text-text-muted uppercase">상태</div>
            <div class="text-right text-[11px] font-bold text-text-muted uppercase">삭제일</div>
            <div class="text-center text-[11px] font-bold text-text-muted uppercase">관리</div>
        </div>

        <div class="divide-y divide-gray-50">
            @forelse($claims as $claim)
                <div class="claim-row px-4 lg:px-6 py-4 hover:bg-gray-50/60 transition-colors">
                    <div class="min-w-0">
                        <p class="text-sm font-extrabold text-text-main">{{ $claim->claim_number }}</p>
                        <p class="mt-1 text-[12px] font-bold text-text-muted">주문: {{ optional($claim->order)->order_number }}</p>
                    </div>

                    <div class="min-w-0">
                        <p class="text-sm font-bold text-text-main">{{ optional($claim->member)->name }}</p>
                        <p class="mt-1 text-[12px] font-bold {{ $claim->type === 'exchange' ? 'text-blue-500' : 'text-red-500' }}">
                            {{ $claim->type === 'exchange' ? '교환' : '반품' }}
                        </p>
                    </div>

                    <div class="text-center">
                        <x-admin.status-badge type="claim" :value="$claim->status" />
                    </div>

                    <div class="text-right">
                        <p class="text-[12px] font-bold text-red-400">{{ $claim->deleted_at->format('Y.m.d H:i') }}</p>
                    </div>

                    <div class="flex items-center justify-center gap-2">
                        <form action="{{ route('admin.order-claims.restore', $claim) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors" title="복구">
                                <span class="material-symbols-outlined text-[18px]">restore</span>
                            </button>
                        </form>
                        <form action="{{ route('admin.order-claims.force-destroy', $claim) }}" method="POST" class="js-confirm-submit" data-confirm-title="영구 삭제" data-confirm-message="정말로 영구 삭제하시겠습니까? 이 작업은 되돌릴 수 없습니다." data-confirm-text="영구 삭제">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors" title="영구 삭제">
                                <span class="material-symbols-outlined text-[18px]">delete_forever</span>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="px-6 py-20 text-center">
                    <div class="size-20 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-gray-300 text-[40px]">delete_outline</span>
                    </div>
                    <p class="text-text-muted text-sm font-bold">휴지통이 비어 있습니다.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-10">
        {{ $claims->links() }}
    </div>
</div>
@endsection
