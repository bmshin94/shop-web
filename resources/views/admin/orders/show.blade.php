@extends('layouts.admin')

@section('page_title', '주문 상세')

@push('styles')
<style>
    .detail-grid {
        display: grid;
        gap: 24px;
    }

    @media (min-width: 1024px) {
        .detail-grid {
            grid-template-columns: 1.5fr 1fr;
            align-items: start;
        }
    }
</style>
@endpush

@section('content')
<div class="space-y-6 lg:space-y-8">
    <!-- 주문 헤더 -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.orders.index') }}" class="flex items-center justify-center size-10 rounded-xl bg-white border border-gray-200 text-text-muted hover:text-primary hover:border-primary transition-all shadow-sm">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <h3 class="text-2xl font-extrabold text-text-main">{{ $order->order_number }}</h3>
                <p class="mt-1 text-[12px] font-bold text-text-muted">주문일시 {{ optional($order->ordered_at)->format('Y.m.d H:i') }}</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <x-admin.status-badge type="order" label="주문" :value="$order->order_status" class="px-3 py-1.5 text-[12px]" />
            <x-admin.status-badge type="payment" label="결제" :value="$order->payment_status" class="px-3 py-1.5 text-[12px]" />
            <x-admin.status-badge type="shipping" label="배송" :value="$order->shipping_status" class="px-3 py-1.5 text-[12px]" />
        </div>
    </div>

    <!-- 주문 요약 -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 lg:gap-6">
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <p class="text-[11px] font-bold text-text-muted uppercase tracking-widest">결제 금액</p>
            <p class="mt-3 text-3xl font-black text-text-main">₩{{ number_format($order->total_amount) }}</p>
        </div>
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <p class="text-[11px] font-bold text-text-muted uppercase tracking-widest">상품 합계</p>
            <p class="mt-3 text-3xl font-black text-text-main">₩{{ number_format($order->subtotal_amount) }}</p>
        </div>
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <p class="text-[11px] font-bold text-text-muted uppercase tracking-widest">배송비 / 할인</p>
            <p class="mt-3 text-2xl font-black text-text-main">₩{{ number_format($order->shipping_amount) }}</p>
            <p class="mt-1 text-[12px] font-bold text-text-muted">할인 ₩{{ number_format($order->discount_amount) }}</p>
        </div>
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <p class="text-[11px] font-bold text-text-muted uppercase tracking-widest">수량 / 품목</p>
            <p class="mt-3 text-3xl font-black text-text-main">{{ number_format($order->items->sum('quantity')) }}개</p>
            <p class="mt-1 text-[12px] font-bold text-text-muted">{{ number_format($order->items->count()) }}개 품목</p>
        </div>
    </div>

    <div class="detail-grid">
        <div class="space-y-6">
            <!-- 주문 상품 -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h4 class="text-lg font-extrabold text-text-main">주문 상품</h4>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($order->items as $item)
                        <div class="px-6 py-5 flex flex-col md:flex-row md:items-center gap-4">
                            <div class="size-20 rounded-2xl bg-gray-100 overflow-hidden shrink-0">
                                @if($item->product && $item->product->image_url)
                                    @if(\Illuminate\Support\Str::startsWith($item->product->image_url, 'http'))
                                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                    @else
                                        <img src="{{ asset($item->product->image_url) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                    @endif
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                                        <span class="material-symbols-outlined text-[28px]">inventory_2</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-base font-extrabold text-text-main truncate">{{ $item->product_name }}</p>
                                @if($item->option_summary)
                                    <p class="mt-1 text-[12px] font-bold text-text-muted">{{ $item->option_summary }}</p>
                                @endif
                                <div class="mt-3 flex flex-wrap items-center gap-3 text-[12px] font-bold text-text-muted">
                                    <span>단가 ₩{{ number_format($item->unit_price) }}</span>
                                    <span>수량 {{ number_format($item->quantity) }}개</span>
                                </div>
                            </div>
                            <div class="text-left md:text-right">
                                <p class="text-lg font-black text-text-main">₩{{ number_format($item->line_total) }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-16 text-center">
                            <p class="text-sm font-bold text-text-muted">주문 품목 정보가 아직 없습니다.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- 주문자 및 배송지 -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <h4 class="text-base font-extrabold text-text-main mb-5">주문자 정보</h4>
                    <dl class="space-y-4 text-sm">
                        <div>
                            <dt class="text-[11px] font-bold text-text-muted uppercase tracking-widest">이름</dt>
                            <dd class="mt-1 font-bold text-text-main">{{ $order->customer_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-[11px] font-bold text-text-muted uppercase tracking-widest">연락처</dt>
                            <dd class="mt-1 font-bold text-text-main">{{ $order->customer_phone }}</dd>
                        </div>
                        <div>
                            <dt class="text-[11px] font-bold text-text-muted uppercase tracking-widest">이메일</dt>
                            <dd class="mt-1 font-bold text-text-main">{{ $order->customer_email ?: '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-[11px] font-bold text-text-muted uppercase tracking-widest">결제수단</dt>
                            <dd class="mt-1 font-bold text-text-main">{{ $order->payment_method }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <h4 class="text-base font-extrabold text-text-main mb-5">배송지 정보</h4>
                    <dl class="space-y-4 text-sm">
                        <div>
                            <dt class="text-[11px] font-bold text-text-muted uppercase tracking-widest">수령인</dt>
                            <dd class="mt-1 font-bold text-text-main">{{ $order->recipient_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-[11px] font-bold text-text-muted uppercase tracking-widest">연락처</dt>
                            <dd class="mt-1 font-bold text-text-main">{{ $order->recipient_phone }}</dd>
                        </div>
                        <div>
                            <dt class="text-[11px] font-bold text-text-muted uppercase tracking-widest">주소</dt>
                            <dd class="mt-1 font-bold text-text-main">[{{ $order->postal_code ?: '-' }}] {{ $order->address_line1 }} {{ $order->address_line2 }}</dd>
                        </div>
                        <div>
                            <dt class="text-[11px] font-bold text-text-muted uppercase tracking-widest">배송 요청사항</dt>
                            <dd class="mt-1 font-bold text-text-main">{{ $order->shipping_message ?: '-' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <!-- 주문 처리 폼 -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                <h4 class="text-base font-extrabold text-text-main mb-5">주문 처리</h4>
                <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PATCH')

                    <div class="rounded-2xl bg-amber-50 border border-amber-100 px-4 py-3 text-[12px] font-bold text-amber-700 leading-relaxed">
                        배송상태를 <span class="text-amber-900">출고완료</span> 이상으로 변경하면 택배사와 송장번호를 반드시 입력해야 합니다.
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main">주문상태</label>
                        <select name="order_status" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 appearance-none outline-none">
                            @foreach($orderStatusOptions as $status)
                                <option value="{{ $status }}" {{ old('order_status', $order->order_status) === $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                        @error('order_status')
                            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main">결제상태</label>
                        <select name="payment_status" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 appearance-none outline-none">
                            @foreach($paymentStatusOptions as $status)
                                <option value="{{ $status }}" {{ old('payment_status', $order->payment_status) === $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                        @error('payment_status')
                            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main">배송상태</label>
                        <select name="shipping_status" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 appearance-none outline-none">
                            @foreach($shippingStatusOptions as $status)
                                <option value="{{ $status }}" {{ old('shipping_status', $order->shipping_status) === $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                        @error('shipping_status')
                            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main">택배사</label>
                        <input type="text" name="courier" value="{{ old('courier', $order->courier) }}" placeholder="예: CJ대한통운" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
                        @error('courier')
                            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main">송장번호</label>
                        <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" placeholder="송장번호 입력" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
                        @error('tracking_number')
                            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main">관리자 메모</label>
                        <textarea name="admin_memo" rows="5" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">{{ old('admin_memo', $order->admin_memo) }}</textarea>
                        @error('admin_memo')
                            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    @if($errors->any())
                        <div class="rounded-2xl bg-red-50 border border-red-100 px-4 py-3 text-[12px] font-bold text-red-600">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <button type="submit" class="w-full px-5 py-4 bg-primary text-white rounded-2xl text-sm font-extrabold hover:bg-red-600 transition-colors shadow-lg shadow-primary/20">
                        주문 상태 저장
                    </button>
                </form>
            </div>

            <!-- 처리 이력 -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                <h4 class="text-base font-extrabold text-text-main mb-5">처리 이력</h4>
                <div class="space-y-4 text-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="font-bold text-text-main">주문 접수</p>
                            <p class="mt-1 text-[12px] font-bold text-text-muted">주문이 생성된 시각입니다.</p>
                        </div>
                        <span class="text-[12px] font-bold text-text-main">{{ optional($order->ordered_at)->format('Y.m.d H:i') ?: '-' }}</span>
                    </div>
                    <div class="border-t border-gray-100 pt-4 flex items-start justify-between gap-4">
                        <div>
                            <p class="font-bold text-text-main">출고 처리</p>
                            <p class="mt-1 text-[12px] font-bold text-text-muted">배송 시작 시 자동 기록됩니다.</p>
                        </div>
                        <span class="text-[12px] font-bold text-text-main">{{ optional($order->shipped_at)->format('Y.m.d H:i') ?: '-' }}</span>
                    </div>
                    <div class="border-t border-gray-100 pt-4 flex items-start justify-between gap-4">
                        <div>
                            <p class="font-bold text-text-main">배송 완료</p>
                            <p class="mt-1 text-[12px] font-bold text-text-muted">배송완료로 변경되면 자동 기록됩니다.</p>
                        </div>
                        <span class="text-[12px] font-bold text-text-main">{{ optional($order->delivered_at)->format('Y.m.d H:i') ?: '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- 주문 삭제 -->
            <div class="bg-white rounded-3xl border border-red-100 shadow-sm p-6">
                <h4 class="text-base font-extrabold text-text-main mb-4">주문 삭제</h4>
                <p class="text-[12px] font-bold text-text-muted leading-relaxed">
                    주문을 삭제하면 화면 목록에서 숨겨지며(soft delete), 주문 데이터와 주문 상품 데이터는 DB에 보관됩니다.
                </p>
                <form
                    action="{{ route('admin.orders.destroy', $order) }}"
                    method="POST"
                    class="mt-5 js-confirm-submit"
                    data-confirm-title="주문 삭제"
                    data-confirm-message="이 주문을 soft delete 처리하시겠습니까? 목록에서 숨김 처리됩니다."
                    data-confirm-text="삭제 처리">
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        class="w-full px-5 py-4 bg-red-50 text-red-600 border border-red-200 rounded-2xl text-sm font-extrabold hover:bg-red-100 transition-colors">
                        주문 삭제
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
