@extends('layouts.app')

@section('title', '교환/반품 신청 - Active Women\'s Premium Store')

@section('content')
<main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-text-muted mb-6">
        <a href="{{ route('mypage') }}" class="hover:text-primary transition-colors">마이페이지</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <a href="{{ route('mypage.order-list') }}" class="hover:text-primary transition-colors">주문/배송 조회</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <a href="{{ route('mypage.order-detail', $order->order_number) }}" class="hover:text-primary transition-colors">주문 상세</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <span class="font-bold text-text-main">교환/반품 신청</span>
    </nav>

    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl font-extrabold text-text-main tracking-tight mb-2">교환/반품 신청</h2>
        <p class="text-text-muted mb-8 text-sm">신청하실 상품과 사유를 선택해 주세요.</p>

        <form action="#" method="POST" id="exchangeReturnForm" class="space-y-8">
            @csrf
            <!-- 주문 상품 선택 -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-base font-bold text-text-main">1. 신청 상품 선택</h3>
                </div>
                <div class="divide-y divide-gray-50">
                    @foreach($order->items as $item)
                    <div class="p-6 flex items-start gap-4 hover:bg-gray-50/30 transition-colors">
                        <div class="pt-1">
                            <input type="checkbox" name="items[]" value="{{ $item->id }}" checked
                                   class="size-5 rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                        </div>
                        <div class="size-20 rounded-lg bg-gray-100 overflow-hidden shrink-0 border border-gray-200">
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-text-main truncate">{{ $item->product_name }}</p>
                            <p class="text-xs text-text-muted mt-1">{{ $item->option_summary ?? '기본' }}</p>
                            <div class="mt-2 flex items-center justify-between">
                                <p class="text-xs font-bold text-text-muted">수량: {{ $item->quantity }}개</p>
                                <p class="text-sm font-black text-text-main">₩{{ number_format($item->line_total) }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- 사유 및 유형 선택 -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8 space-y-6">
                <h3 class="text-base font-bold text-text-main border-b border-gray-100 pb-4">2. 신청 사유 및 유형</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main">신청 유형</label>
                        <select name="type" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all">
                            <option value="return">반품 (환불)</option>
                            <option value="exchange">교환 (사이즈/색상 등)</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main">상세 사유</label>
                        <select name="reason" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all">
                            <option value="change_mind">단순 변심 (색상/디자인 등)</option>
                            <option value="size_issue">사이즈 안 맞음</option>
                            <option value="damaged">상품 파손/불량</option>
                            <option value="wrong_item">오배송 (다른 상품 배송)</option>
                            <option value="other">기타</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-text-main">상세 내용 (선택)</label>
                    <textarea name="content" rows="4" placeholder="더 자세한 사유를 적어주시면 빠른 처리에 도움이 됩니다."
                              class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all resize-none"></textarea>
                </div>
            </div>

            <!-- 환불 안내 -->
            <div class="bg-primary-light/30 rounded-2xl border border-primary/10 p-6 space-y-3">
                <h4 class="text-sm font-bold text-primary flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">info</span> 확인해 주세요!
                </h4>
                <ul class="text-xs text-text-main space-y-2 leading-relaxed opacity-80">
                    <li>· 단순 변심에 의한 교환/반품은 왕복 배송비가 발생할 수 있습니다.</li>
                    <li>· 상품의 라벨/태그를 제거하거나 착용 흔적이 있는 경우 신청이 거부될 수 있습니다.</li>
                    <li>· 신청 접수 후 담당자가 확인하여 1~2일 내에 안내 연락을 드립니다.</li>
                </ul>
            </div>

            <!-- 하단 버튼 -->
            <div class="flex gap-4 pt-4">
                <a href="{{ route('mypage.order-detail', $order->order_number) }}" 
                   class="flex-1 px-6 py-4 bg-white border border-gray-300 text-text-main text-sm font-bold rounded-2xl hover:bg-gray-50 transition-all text-center">
                    취소
                </a>
                <button type="submit" 
                        class="flex-[2] px-6 py-4 bg-primary text-white text-sm font-black rounded-2xl hover:bg-red-600 transition-all shadow-lg shadow-primary/20 shadow-md transform active:scale-95">
                    신청하기
                </button>
            </div>
        </form>
    </div>
</main>

<!-- Toast Popup (공통 사용) -->
<div id="toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[150] flex items-center justify-center gap-2 bg-text-main text-white px-6 py-3 rounded-full text-sm font-bold shadow-2xl transition-all duration-300 opacity-0 translate-y-8 pointer-events-none">
    <span class="material-symbols-outlined text-lg text-green-400" id="toastIcon">check_circle</span>
    <span id="toastMsg">처리되었습니다.</span>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('exchangeReturnForm');
        const toast = document.getElementById('toast');
        const toastMsg = document.getElementById('toastMsg');
        
        function showToast(message, isError = false) {
            toastMsg.textContent = message;
            if (isError) toast.classList.replace('bg-text-main', 'bg-red-600');
            else toast.classList.replace('bg-red-600', 'bg-text-main');
            toast.classList.remove('opacity-0', 'translate-y-8');
            setTimeout(() => { toast.classList.add('opacity-0', 'translate-y-8'); }, 3000);
        }

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const submitBtn = form.querySelector('button[type="submit"]');
            const checkedItems = Array.from(document.querySelectorAll('input[name="items[]"]:checked')).map(el => el.value);
            
            if (checkedItems.length === 0) {
                showToast('신청할 상품을 하나 이상 선택해 주세요.', true);
                return;
            }

            const formData = new FormData(form);
            const data = {
                items: checkedItems,
                type: formData.get('type'),
                reason: formData.get('reason'),
                content: formData.get('content'),
                _token: document.querySelector('meta[name="csrf-token"]').content
            };

            // 버튼 비활성화 및 로딩 표시
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="animate-spin material-symbols-outlined text-sm">sync</span> 처리 중...';

            fetch("{{ route('mypage.exchange-return.store', $order->order_number) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': data._token
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success' || result.message.includes('정상적으로')) {
                    showToast(result.message || '신청이 정상적으로 접수되었습니다.');
                    setTimeout(() => {
                        location.href = "{{ route('mypage.order-detail', $order->order_number) }}";
                    }, 2000);
                } else {
                    showToast(result.message || '요청 처리 중 오류가 발생했습니다.', true);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '신청하기';
                }
            })
            .catch(error => {
                showToast('서버 통신 중 오류가 발생했습니다.', true);
                submitBtn.disabled = false;
                submitBtn.innerHTML = '신청하기';
            });
        });
    });
</script>
@endpush
