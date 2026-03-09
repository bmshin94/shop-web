@extends('layouts.app')

@section('title', '주문 상세 - Active Women\'s Premium Store')

@section('content')
<main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-text-muted mb-6">
        <a href="{{ route('mypage.profile') }}" class="hover:text-primary transition-colors">마이페이지</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <a href="{{ route('mypage.order-list') }}" class="hover:text-primary transition-colors">주문/배송 조회</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <span class="font-bold text-text-main">주문 상세</span>
    </nav>

    <!-- Page Title -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <h2 class="text-3xl font-extrabold text-text-main tracking-tight">주문 상세</h2>
        <p class="text-sm text-text-muted">주문번호 <span class="font-bold text-text-main">260215-9876543</span></p>
    </div>

    <div class="space-y-8">
        <!-- 주문 상태 Progress (배송완료 예시) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
            <h3 class="text-lg font-bold text-text-main mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">local_shipping</span> 주문 진행 상태 (배송완료 예시)
            </h3>
            <div class="relative flex flex-col sm:flex-row items-center justify-between w-full">
                <!-- 진행바 배경 -->
                <div class="hidden sm:block absolute top-[22px] left-6 right-6 h-1 bg-gray-200 rounded-full z-0"></div>
                <!-- 진행바 완료 (배송완료까지 100%) -->
                <div class="hidden sm:block absolute top-[22px] left-6 right-6 h-1 bg-primary rounded-full z-0"></div>

                @foreach(['주문접수' => '02.15 14:30', '결제완료' => '02.15 14:30', '배송준비' => '02.16 10:00', '배송중' => '02.17 09:20', '배송완료' => '02.18 16:45'] as $status => $time)
                <div class="flex flex-col items-center gap-2 z-10 w-full sm:w-auto mb-4 sm:mb-0 bg-white sm:bg-transparent">
                    <div class="size-12 rounded-full bg-primary flex items-center justify-center text-white font-bold text-lg shadow-md ring-4 ring-white">
                        <span class="material-symbols-outlined">check</span>
                    </div>
                    <span class="text-sm font-bold text-primary">{{ $status }}</span>
                    <span class="text-xs text-text-muted">{{ $time }}</span>
                </div>
                @if(!$loop->last)
                <span class="material-symbols-outlined text-gray-300 sm:hidden mb-4">south</span>
                @endif
                @endforeach
            </div>
        </div>

        <!-- 주문 상품 정보 -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
            <h3 class="text-lg font-bold text-text-main mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">inventory_2</span> 주문 상품 정보
            </h3>
            <div class="space-y-6">
                <div class="flex flex-col sm:flex-row items-start gap-5 p-5 bg-gray-50 rounded-xl border border-gray-100">
                    <div class="size-24 bg-white rounded-lg overflow-hidden shrink-0 border border-gray-200">
                        <img src="https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?w=200&h=200&fit=crop" alt="에어 컴포트 스포츠 브라탑" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 w-full">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                            <div>
                                <p class="text-xs font-bold text-primary mb-1">Active Women</p>
                                <a href="{{ route('product-detail') }}" class="text-base font-bold text-text-main hover:text-primary transition-colors">에어 컴포트 스포츠 브라탑</a>
                                <p class="text-sm text-text-muted mt-1">옵션: 화이트 / S 사이즈</p>
                                <p class="text-sm text-text-muted">수량: 2개</p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-lg font-extrabold text-text-main">78,000원</p>
                                <p class="text-xs text-text-muted line-through">82,000원</p>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center gap-2">
                            <span class="inline-flex py-1 px-3 bg-gray-100 text-gray-600 font-bold text-xs rounded-full border border-gray-200">배송완료</span>
                            <span class="text-xs text-text-muted">CJ대한통운 | 123456789012</span>
                        </div>
                    </div>
                </div>
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
                    <div class="flex items-start gap-4"><dt class="text-sm font-bold text-text-muted w-24 shrink-0">수령인</dt><dd class="text-sm font-medium text-text-main">김에스핏</dd></div>
                    <div class="flex items-start gap-4"><dt class="text-sm font-bold text-text-muted w-24 shrink-0">연락처</dt><dd class="text-sm font-medium text-text-main">010-1234-5678</dd></div>
                    <div class="flex items-start gap-4"><dt class="text-sm font-bold text-text-muted w-24 shrink-0">배송지</dt><dd class="text-sm font-medium text-text-main leading-relaxed">[06123] 서울특별시 강남구 테헤란로 123<br>에스핏 빌딩 4층</dd></div>
                    <div class="flex items-start gap-4"><dt class="text-sm font-bold text-text-muted w-24 shrink-0">배송메모</dt><dd class="text-sm font-medium text-text-main">부재 시 문 앞에 놓아주세요</dd></div>
                </dl>
            </div>

            <!-- 결제 정보 -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
                <h3 class="text-lg font-bold text-text-main mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">payment</span> 결제 정보
                </h3>
                <dl class="space-y-4">
                    <div class="flex items-start justify-between"><dt class="text-sm font-bold text-text-muted">상품금액</dt><dd class="text-sm font-medium text-text-main">82,000원</dd></div>
                    <div class="flex items-start justify-between"><dt class="text-sm font-bold text-text-muted">상품할인</dt><dd class="text-sm font-bold text-primary">-4,000원</dd></div>
                    <div class="flex items-start justify-between"><dt class="text-sm font-bold text-text-muted">배송비</dt><dd class="text-sm font-medium text-text-main">무료</dd></div>
                    <div class="flex items-start justify-between"><dt class="text-sm font-bold text-text-muted">쿠폰할인</dt><dd class="text-sm font-bold text-primary">0원</dd></div>
                    <div class="flex items-start justify-between"><dt class="text-sm font-bold text-text-muted">적립금 사용</dt><dd class="text-sm font-bold text-primary">0원</dd></div>
                    <div class="border-t border-gray-100 pt-4 flex items-start justify-between">
                        <dt class="text-base font-extrabold text-text-main">총 결제금액</dt>
                        <dd class="text-xl font-extrabold text-primary">78,000원</dd>
                    </div>
                    <div class="flex items-start justify-between pt-2 border-t border-gray-100">
                        <dt class="text-sm font-bold text-text-muted">결제수단</dt>
                        <dd class="text-sm font-medium text-text-main">신용카드 (삼성카드 / 일시불)</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- 주문자 정보 -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
            <h3 class="text-lg font-bold text-text-main mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">person</span> 주문자 정보
            </h3>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex items-start gap-4"><dt class="text-sm font-bold text-text-muted w-24 shrink-0">주문자</dt><dd class="text-sm font-medium text-text-main">김에스핏</dd></div>
                <div class="flex items-start gap-4"><dt class="text-sm font-bold text-text-muted w-24 shrink-0">연락처</dt><dd class="text-sm font-medium text-text-main">010-1234-5678</dd></div>
                <div class="flex items-start gap-4"><dt class="text-sm font-bold text-text-muted w-24 shrink-0">이메일</dt><dd class="text-sm font-medium text-text-main">esfit@example.com</dd></div>
                <div class="flex items-start gap-4"><dt class="text-sm font-bold text-text-muted w-24 shrink-0">주문일시</dt><dd class="text-sm font-medium text-text-main">2026.02.15 14:30:22</dd></div>
            </dl>
        </div>

        <!-- 하단 버튼 영역 -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4">
            <a href="{{ route('mypage.profile') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-gray-300 text-text-main text-sm font-bold rounded-xl hover:bg-gray-50 transition-colors">
                <span class="material-symbols-outlined text-lg">arrow_back</span> 마이페이지로 돌아가기
            </a>
            <div class="flex items-center gap-3">
                <button id="btnCancelOrder" class="px-6 py-3 bg-white border border-gray-300 text-text-main text-sm font-bold rounded-xl hover:bg-gray-50 transition-colors">주문취소</button>
                <button id="btnExchangeReturn" class="px-6 py-3 bg-white border border-gray-300 text-text-main text-sm font-bold rounded-xl hover:bg-gray-50 transition-colors">교환/반품 신청</button>
            </div>
        </div>
    </div>
</main>

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

        const btnCancelOrder = document.getElementById('btnCancelOrder');
        if (btnCancelOrder) btnCancelOrder.addEventListener('click', () => { showToast('이미 배송이 완료된 주문은 취소할 수 없습니다.', 'error', 'text-white', true); });

        const btnExchangeReturn = document.getElementById('btnExchangeReturn');
        if (btnExchangeReturn) btnExchangeReturn.addEventListener('click', () => { showToast('교환/반품 신청 페이지로 이동합니다.', 'swap_horiz', 'text-blue-400'); });
    });
</script>
@endpush
