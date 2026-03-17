@extends('layouts.app')

@section('title', '주문 상세 - Active Women\'s Premium Store')

@section('content')
<main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-text-muted mb-6">
        <a href="{{ route('mypage') }}" class="hover:text-primary transition-colors">마이페이지</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <a href="{{ route('mypage.order-list') }}" class="hover:text-primary transition-colors">주문/배송 조회</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <span class="font-bold text-text-main">주문 상세</span>
    </nav>

    <!-- Page Title -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <h2 class="text-3xl font-extrabold text-text-main tracking-tight">주문 상세</h2>
        <p class="text-sm text-text-muted">주문번호 <span class="font-bold text-text-main">{{ $order->order_number }}</span></p>
    </div>

    <div class="space-y-8">
        <!-- 주문 상태 Progress -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
            <h3 class="text-lg font-bold text-text-main mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">local_shipping</span> 주문 진행 상태
            </h3>
            <div class="relative flex flex-col sm:flex-row items-center justify-between w-full">
                <!-- 진행바 배경 -->
                <div class="hidden sm:block absolute top-[22px] left-6 right-6 h-1 bg-gray-200 rounded-full z-0"></div>
                
                @php
                    $statusOrder = ['주문접수', '상품준비중', '배송중', '배송완료', '구매확정'];
                    $currentStatusIndex = array_search($order->order_status, $statusOrder);
                    
                    $statuses = [
                        '주문접수' => ['time' => $order->ordered_at, 'index' => 0],
                        '상품준비중' => ['time' => ($order->order_status === '상품준비중' || $currentStatusIndex > 1 ? ($order->shipped_at ? $order->shipped_at->subHours(2) : $order->ordered_at->addHours(2)) : null), 'index' => 1],
                        '배송중' => ['time' => $order->shipped_at, 'index' => 2],
                        '배송완료' => ['time' => $order->delivered_at, 'index' => 3],
                        '구매확정' => ['time' => ($order->order_status === '구매확정' ? $order->updated_at : null), 'index' => 4],
                    ];
                @endphp

                @foreach($statuses as $status => $data)
                @php 
                    $time = $data['time'];
                    $stepIndex = $data['index'];
                    // 상태 순서상 현재 상태보다 이전 단계거나, 시간 데이터가 있으면 완료 처리!
                    $isCompleted = ($currentStatusIndex !== false && $currentStatusIndex >= $stepIndex) || $time !== null;
                    if ($order->order_status === '취소완료') $isCompleted = false;
                @endphp
                <div class="flex flex-col items-center gap-2 z-10 w-full sm:w-auto mb-4 sm:mb-0 bg-white sm:bg-transparent">
                    <div class="size-12 rounded-full {{ $isCompleted ? 'bg-primary text-white' : 'bg-gray-100 text-gray-400' }} flex items-center justify-center font-bold text-lg shadow-md ring-4 ring-white transition-all">
                        <span class="material-symbols-outlined">{{ $isCompleted ? 'check' : ($order->order_status === '취소완료' ? 'close' : 'pending') }}</span>
                    </div>
                    <span class="text-sm font-bold {{ $isCompleted ? 'text-primary' : 'text-gray-400' }}">{{ $status }}</span>
                    <span class="text-xs text-text-muted">{{ $time ? $time->format('m.d H:i') : '-' }}</span>
                </div>
                @if(!$loop->last)
                <span class="material-symbols-outlined text-gray-300 sm:hidden mb-4">south</span>
                @endif
                @endforeach
            </div>
            @if($order->order_status === '취소완료')
            <div class="mt-6 p-4 bg-red-50 rounded-xl border border-red-100 flex items-center gap-3 text-red-600">
                <span class="material-symbols-outlined">error</span>
                <p class="text-sm font-bold">이 주문은 취소되었습니다. 환불 처리는 카드사에 따라 3~5일 정도 소요될 수 있습니다.</p>
            </div>
            @endif
        </div>

        <!-- 주문 상품 정보 -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
            <h3 class="text-lg font-bold text-text-main mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">inventory_2</span> 주문 상품 정보
            </h3>
            <div class="space-y-6">
                @foreach($order->items as $item)
                <div class="flex flex-col sm:flex-row items-start gap-5 p-5 bg-gray-50 rounded-xl border border-gray-100">
                    <div class="size-24 bg-white rounded-lg overflow-hidden shrink-0 border border-gray-200">
                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 w-full">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                            <div>
                                <p class="text-xs font-bold text-primary mb-1">Active Women</p>
                                <a href="{{ route('product-detail', ['slug' => $item->product->slug]) }}" class="text-base font-bold text-text-main hover:text-primary transition-colors">
                                    {{ $item->product->name }}
                                </a>
                                <p class="text-sm text-text-muted mt-1">
                                    옵션: {{ $item->option_summary ?? '기본' }}
                                </p>
                                <p class="text-sm text-text-muted">수량: {{ $item->quantity }}개</p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-lg font-extrabold text-text-main">₩{{ number_format($item->line_total) }}</p>
                                @if($item->product->price > $item->unit_price)
                                <p class="text-xs text-text-muted line-through">₩{{ number_format($item->product->price * $item->quantity) }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4 flex items-center gap-3">
                            <span class="inline-flex py-1 px-3 bg-white text-primary font-bold text-xs rounded-full border border-primary/20">
                                {{ $order->order_status }}
                            </span>
                            @if($order->tracking_number)
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-text-muted">{{ $order->courier }} | {{ $order->tracking_number }}</span>
                                <button type="button" 
                                        onclick="trackDelivery('{{ $order->courier }}', '{{ $order->tracking_number }}')"
                                        class="px-2 py-1 bg-gray-100 text-[10px] font-bold text-text-main rounded-md border border-gray-200 hover:bg-gray-200 transition-colors">배송추적</button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- 배송지 정보 & 결제 정보 그리드 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- 배송지 정보 -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
                <h3 class="text-lg font-bold text-text-main mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">location_on</span> 배송지 정보
                </h3>
                <dl class="space-y-4">
                    <div class="flex items-start gap-4"><dt class="text-sm font-bold text-text-muted w-24 shrink-0">수령인</dt><dd class="text-sm font-medium text-text-main">{{ $order->recipient_name }}</dd></div>
                    <div class="flex items-start gap-4"><dt class="text-sm font-bold text-text-muted w-24 shrink-0">연락처</dt><dd class="text-sm font-medium text-text-main">{{ $order->recipient_phone }}</dd></div>
                    <div class="flex items-start gap-4"><dt class="text-sm font-bold text-text-muted w-24 shrink-0">배송지</dt><dd class="text-sm font-medium text-text-main leading-relaxed">[{{ $order->postal_code }}] {{ $order->address_line1 }}<br>{{ $order->address_line2 }}</dd></div>
                    <div class="flex items-start gap-4"><dt class="text-sm font-bold text-text-muted w-24 shrink-0">배송메모</dt><dd class="text-sm font-medium text-text-main">{{ $order->shipping_message ?? '없음' }}</dd></div>
                </dl>
            </div>

            <!-- 결제 정보 -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
                <h3 class="text-lg font-bold text-text-main mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">payment</span> 결제 정보
                </h3>
                <dl class="space-y-4">
                    <div class="flex items-start justify-between"><dt class="text-sm font-bold text-text-muted">상품금액</dt><dd class="text-sm font-medium text-text-main">₩{{ number_format($order->subtotal_amount) }}</dd></div>
                    @if($order->discount_amount > 0)
                    <div class="flex items-start justify-between"><dt class="text-sm font-bold text-text-muted">할인금액</dt><dd class="text-sm font-bold text-primary">-₩{{ number_format($order->discount_amount) }}</dd></div>
                    @endif
                    <div class="flex items-start justify-between"><dt class="text-sm font-bold text-text-muted">배송비</dt><dd class="text-sm font-medium text-text-main">{{ $order->shipping_amount > 0 ? '₩'.number_format($order->shipping_amount) : '무료' }}</dd></div>
                    <div class="border-t border-gray-100 pt-4 flex items-start justify-between">
                        <dt class="text-base font-extrabold text-text-main">총 결제금액</dt>
                        <dd class="text-xl font-extrabold text-primary">₩{{ number_format($order->total_amount) }}</dd>
                    </div>
                    <div class="flex items-start justify-between pt-2 border-t border-gray-100">
                        <dt class="text-sm font-bold text-text-muted">결제수단</dt>
                        <dd class="text-sm font-medium text-text-main">{{ $order->payment_method }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- 하단 버튼 영역 -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4">
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('mypage.order-list') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-gray-300 text-text-main text-sm font-bold rounded-xl hover:bg-gray-50 transition-colors">
                    <span class="material-symbols-outlined text-lg">arrow_back</span> 목록으로
                </a>
                <a href="{{ route('mypage') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-gray-300 text-text-main text-sm font-bold rounded-xl hover:bg-gray-50 transition-colors">
                    <span class="material-symbols-outlined text-lg">home</span> 마이페이지 메인
                </a>
            </div>
            
            <div class="flex items-center gap-3">
                @php
                    $isCancellable = in_array($order->order_status, \App\Models\Order::CANCELLABLE_STATUSES);
                @endphp
                <button id="btnCancelOrder" 
                        data-order-number="{{ $order->order_number }}"
                        data-cancellable="{{ $isCancellable ? 'true' : 'false' }}"
                        data-status="{{ $order->order_status }}"
                        class="px-6 py-3 {{ $isCancellable ? 'bg-red-50 border-red-200 text-red-600 hover:bg-red-100' : 'bg-gray-100 border-gray-200 text-gray-400 cursor-not-allowed' }} border text-sm font-bold rounded-xl transition-colors">
                    주문취소
                </button>
            </div>
            
            @if($order->order_status === '배송완료' && !$order->has_active_claim)
            <div class="flex items-center gap-3">
                <a href="{{ route('mypage.exchange-return', $order->order_number) }}" class="px-6 py-3 bg-white border border-gray-300 text-text-main text-sm font-bold rounded-xl hover:bg-gray-50 transition-colors">교환/반품 신청</a>
            </div>
            @endif

            @if($order->order_status === '배송완료')
            <div class="flex items-center gap-3">
                <button type="button" 
                        class="btn-confirm-purchase px-6 py-3 bg-text-main text-white text-sm font-bold rounded-xl hover:bg-black transition-all shadow-lg shadow-gray-200"
                        data-order-number="{{ $order->order_number }}">구매확정</button>
            </div>
            @endif
            
            @if($order->has_active_claim)
            <div class="flex items-center gap-3">
                <span class="px-6 py-3 bg-gray-100 border border-gray-200 text-gray-400 text-sm font-bold rounded-xl cursor-default">교환/반품 신청 완료</span>
            </div>
            @endif
        </div>
    </div>
</main>

<!-- Cancel Confirm Modal -->
<div id="cancelModal" class="fixed inset-0 z-[200] hidden overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <!-- Overlay -->
        <div id="modalOverlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>

        <!-- Modal Panel -->
        <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md animate-fade-in-up">
            <div class="bg-white px-6 pb-6 pt-8 sm:px-10 sm:pb-8 sm:pt-10">
                <div class="flex flex-col items-center">
                    <div class="mx-auto flex size-16 shrink-0 items-center justify-center rounded-full bg-red-50 text-red-600 mb-6">
                        <span class="material-symbols-outlined text-3xl">error</span>
                    </div>
                    <div class="text-center">
                        <h3 class="text-xl font-black leading-6 text-text-main mb-3" id="modal-title">주문을 취소하시겠습니까?</h3>
                        <p class="text-sm font-medium text-text-muted leading-relaxed">
                            결제된 금액은 즉시 환불 처리되며,<br>
                            취소된 주문은 되돌릴 수 없습니다.
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex gap-3 px-6 pb-8 sm:px-10 sm:pb-10">
                <button type="button" id="btnModalClose" class="flex-1 rounded-2xl bg-gray-100 px-6 py-4 text-sm font-bold text-text-muted hover:bg-gray-200 transition-colors">아니오</button>
                <button type="button" id="btnModalConfirm" class="flex-1 rounded-2xl bg-primary px-6 py-4 text-sm font-black text-white shadow-lg shadow-primary/20 hover:bg-red-600 transition-colors">네, 취소합니다</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Popup -->
<div id="toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[150] flex items-center justify-center gap-2 bg-text-main text-white px-6 py-3 rounded-full text-sm font-bold shadow-2xl transition-all duration-300 opacity-0 translate-y-8 pointer-events-none">
    <span class="material-symbols-outlined text-lg text-green-400" id="toastIcon">check_circle</span>
    <span id="toastMsg">처리되었습니다.</span>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toast = document.getElementById('toast');
        const toastMsg = document.getElementById('toastMsg');
        const toastIcon = document.getElementById('toastIcon');
        
        const cancelModal = document.getElementById('cancelModal');
        const btnModalConfirm = document.getElementById('btnModalConfirm');
        const btnModalClose = document.getElementById('btnModalClose');
        const modalOverlay = document.getElementById('modalOverlay');
        let currentOrderNumber = '';

        let toastTimeout;

        function showToast(message, iconName = 'check_circle', iconColorClass = 'text-green-400', isError = false) {
            toastMsg.textContent = message;
            toastIcon.textContent = iconName;
            toastIcon.className = `material-symbols-outlined text-lg ${iconColorClass}`;
            if (isError) toast.classList.replace('bg-text-main', 'bg-red-600');
            else toast.classList.replace('bg-red-600', 'bg-text-main');
            toast.classList.remove('opacity-0', 'translate-y-8');
            clearTimeout(toastTimeout);
            toastTimeout = setTimeout(() => { toast.classList.add('opacity-0', 'translate-y-8'); }, 3000);
        }

        function openModal(orderNumber) {
            currentOrderNumber = orderNumber;
            cancelModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            cancelModal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        const btnCancelOrder = document.getElementById('btnCancelOrder');
        if (btnCancelOrder) {
            btnCancelOrder.addEventListener('click', function() {
                const orderNumber = this.dataset.orderNumber;
                const isCancellable = this.dataset.cancellable === 'true';
                const status = this.dataset.status;

                if (!isCancellable) {
                    if (status === '취소완료') {
                        showToast('이미 취소된 주문입니다.', 'info', 'text-blue-400');
                    } else {
                        showToast('배송이 시작된 상품은 취소가 불가능합니다. 고객센터로 문의해주세요.', 'error', 'text-white', true);
                    }
                    return;
                }
                
                openModal(orderNumber);
            });
        }

        [btnModalClose, modalOverlay].forEach(el => {
            if (el) el.addEventListener('click', closeModal);
        });

        if (btnModalConfirm) {
            btnModalConfirm.addEventListener('click', function() {
                this.disabled = true;
                this.innerHTML = '<span class="animate-spin material-symbols-outlined text-sm">sync</span> 처리 중...';

                fetch(`/mypage/orders/${currentOrderNumber}/cancel`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ reason: '사용자 직접 취소' })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message.includes('정상적으로')) {
                        closeModal();
                        showToast(data.message);
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showToast(data.message, 'error', 'text-white', true);
                        this.disabled = false;
                        this.innerHTML = '네, 취소합니다';
                    }
                })
                .catch(error => {
                    showToast('서버 통신 오류가 발생했습니다.', 'error', 'text-white', true);
                    this.disabled = false;
                    this.innerHTML = '네, 취소합니다';
                });
            });
        }

        const btnExchangeReturn = document.getElementById('btnExchangeReturn');
        if (btnExchangeReturn) btnExchangeReturn.addEventListener('click', () => { showToast('교환/반품 신청 페이지로 이동합니다.', 'swap_horiz', 'text-blue-400'); });

        // 구매확정 버튼 
        const btnConfirmPurchase = document.querySelector('.btn-confirm-purchase');
        if (btnConfirmPurchase) {
            btnConfirmPurchase.addEventListener('click', async function() {
                const orderNumber = this.dataset.orderNumber;
                
                if (!await showConfirm('상품을 잘 받으셨나요? \n구매확정 후에는 교환/반품이 어려울 수 있어요. ')) return;

                try {
                    const response = await fetch(`/mypage/orders/${orderNumber}/confirm`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const result = await response.json();

                    if (response.ok) {
                        showToast(result.message);
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showToast(result.message || '처리 중 오류가 발생했습니다.', 'error', 'bg-red-500');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showToast('처리 중 오류가 발생했습니다.', 'error', 'bg-red-500');
                }
            });
        }
    });
</script>
@endpush
