@extends('layouts.admin')

@section('page_title', '교환 / 반품 상세 정보')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- 상단 액션바 -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.order-claims.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-text-muted hover:text-text-main transition-colors">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            목록으로 돌아가기
        </a>
        <div class="flex items-center gap-3">
            <x-admin.status-badge type="claim" :value="$claim->status" class="px-4 py-1.5 text-sm" />
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- 왼쪽: 상세 정보 -->
        <div class="lg:col-span-2 space-y-6">
            <!-- 신청 정보 카드 -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                    <h3 class="font-black text-text-main flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">assignment_return</span>
                        신청 정보
                    </h3>
                    <span class="text-[12px] font-bold text-text-muted">신청번호: {{ $claim->claim_number }}</span>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-[11px] font-bold text-text-muted uppercase mb-1.5">신청 유형</p>
                            <p class="text-sm font-extrabold {{ $claim->type === 'exchange' ? 'text-blue-500' : 'text-red-500' }}">
                                {{ $claim->type === 'exchange' ? '교환 신청' : '반품 신청' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-text-muted uppercase mb-1.5">신청일</p>
                            <p class="text-sm font-extrabold text-text-main">{{ $claim->created_at->format('Y.m.d H:i:s') }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-text-muted uppercase mb-1.5">신청 사유 ({{ $claim->reason_type }})</p>
                        <div class="bg-gray-50 rounded-2xl p-4 text-sm font-bold text-text-main leading-relaxed">
                            {{ $claim->reason_detail ?: '상세 사유 없음' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- 신청 상품 카드 -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-black text-text-main flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">inventory_2</span>
                        신청 상품 목록
                    </h3>
                </div>
                <div class="divide-y divide-gray-50">
                    @foreach($claim->items as $item)
                        <div class="p-6 flex items-center gap-4">
                            <div class="size-20 rounded-2xl overflow-hidden bg-gray-100 flex-shrink-0">
                                @if($item->orderItem->product && $item->orderItem->product->main_image_url)
                                    <img src="{{ $item->orderItem->product->main_image_url }}" alt="" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                                        <span class="material-symbols-outlined text-[32px]">image</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <p class="text-[11px] font-bold text-text-muted">{{ $item->orderItem->product->brand ?? 'Brand' }}</p>
                                    <x-admin.status-badge type="item" :value="$item->orderItem->status" class="scale-90" />
                                </div>
                                <h4 class="text-sm font-extrabold text-text-main truncate">{{ $item->orderItem->product_name }}</h4>
                                <p class="mt-1 text-[12px] font-bold text-text-muted">
                                    옵션: {{ $item->orderItem->color_name }} / {{ $item->orderItem->size_name }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-black text-text-main">수량 {{ number_format($item->quantity) }}개</p>
                                <p class="mt-1 text-[12px] font-bold text-text-muted">₩{{ number_format($item->orderItem->price * $item->quantity) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 오른쪽: 주문/회원 정보 & 처리 폼 -->
        <div class="space-y-6">
            <!-- 회원/주문 요약 -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 space-y-6">
                <div>
                    <h4 class="text-[11px] font-bold text-text-muted uppercase mb-3">신청자 정보</h4>
                    <div class="flex items-center gap-3">
                        <div class="size-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                            <span class="material-symbols-outlined">person</span>
                        </div>
                        <div>
                            <p class="text-sm font-extrabold text-text-main">{{ optional($claim->member)->name }}</p>
                            <p class="text-[12px] font-bold text-text-muted">{{ optional($claim->member)->email }}</p>
                        </div>
                    </div>
                </div>
                <div class="pt-6 border-t border-gray-100">
                    <h4 class="text-[11px] font-bold text-text-muted uppercase mb-3">연관 주문 정보</h4>
                    <a href="{{ route('admin.orders.show', $claim->order) }}" class="group block">
                        <div class="flex items-center justify-between p-3 rounded-2xl border border-gray-100 hover:border-primary hover:bg-primary/5 transition-all">
                            <div>
                                <p class="text-sm font-extrabold text-text-main group-hover:text-primary transition-colors">{{ $claim->order->order_number }}</p>
                                <p class="text-[11px] font-bold text-text-muted mt-0.5">결제금액: ₩{{ number_format($claim->order->total_amount) }}</p>
                            </div>
                            <span class="material-symbols-outlined text-gray-300 group-hover:text-primary">chevron_right</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- 처리 업데이트 폼 -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                <h4 class="text-[11px] font-bold text-text-muted uppercase mb-4">관리자 처리</h4>
                <form action="{{ route('admin.order-claims.update', $claim) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    
                    <div>
                        <label class="block text-[11px] font-bold text-text-muted mb-1.5 px-1">진행 상태</label>
                        <div class="relative">
                            <select name="status" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/5 outline-none transition-all">
                                @foreach($statusOptions as $status)
                                    <option value="{{ $status }}" {{ $claim->status === $status ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-text-muted mb-1.5 px-1">관리자 메모</label>
                        <textarea 
                            name="admin_memo" 
                            rows="4" 
                            placeholder="처리 내역이나 특이사항을 기록해 주세요."
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/5 outline-none transition-all resize-none">{{ $claim->admin_memo }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-4 bg-text-main text-white rounded-2xl text-sm font-black hover:bg-black transition-all shadow-lg shadow-black/5 active:scale-[0.98]">
                        상태 업데이트
                    </button>
                </form>

                <div class="mt-4 pt-4 border-t border-gray-100">
                    <form action="{{ route('admin.order-claims.destroy', $claim) }}" method="POST" class="js-confirm-submit" data-confirm-title="신청 삭제" data-confirm-message="이 교환/반품 신청을 삭제하시겠습니까? 삭제된 데이터는 복구할 수 없습니다." data-confirm-text="삭제하기">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-3 bg-white border border-red-100 text-red-500 rounded-2xl text-[12px] font-extrabold hover:bg-red-50 transition-all active:scale-[0.98]">
                            이 신청 내역 삭제
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
