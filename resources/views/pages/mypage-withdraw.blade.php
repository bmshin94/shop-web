@extends('layouts.app')

@section('title', '회원 탈퇴 | 마이페이지 - Active Women\'s Premium Store')

@section('content')
<main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-text-muted mb-6">
        <a href="/" class="hover:text-primary transition-colors">Home</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <a href="{{ route('mypage') }}" class="hover:text-primary transition-colors">마이페이지</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <span class="font-bold text-text-main">회원 탈퇴</span>
    </nav>

    <!-- Page Title -->
    <h2 class="text-3xl font-extrabold text-text-main tracking-tight mb-8">회원 탈퇴</h2>

    <div class="flex flex-col lg:flex-row gap-8 items-start">
        <!-- LNB (Left Navigation Bar) -->
        @include('partials.mypage-sidebar')

        <!-- Main Dashboard Content -->
        <div class="flex-1 w-full space-y-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 sm:p-12 text-center">
                <div class="max-w-md mx-auto">
                    <div class="size-20 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-6">
                        <span class="material-symbols-outlined text-4xl text-primary">logout</span>
                    </div>
                    <h3 class="text-2xl font-extrabold text-text-main mb-2">회원 탈퇴 대기</h3>
                    <p class="text-text-muted text-sm leading-relaxed">액티브 우먼을 이용하시는 동안 불편한 점이 있으셨나요?<br>탈퇴하기 전 아래 유의사항을 반드시 확인해주세요.</p>
                </div>

                <div class="mt-10 bg-gray-50 rounded-2xl p-6 sm:p-8 text-left max-w-2xl mx-auto border border-gray-100">
                    <h4 class="font-bold text-text-main mb-3 flex items-center gap-1"><span class="material-symbols-outlined text-sm text-primary">warning</span>탈퇴 시 유의사항</h4>
                    <ul class="space-y-2.5 text-xs text-text-muted leading-relaxed">
                        <li class="flex gap-2"><span>•</span><span>탈퇴 시 보유하고 계신 쿠폰 및 적립금은 모두 소멸되며 복구가 불가능합니다. (현재 보유 적립금: <strong class="text-primary text-base">₩{{ number_format($member->points) }}</strong> / 쿠폰: <strong class="text-primary text-base">{{ $member->coupons()->whereNull('used_at')->count() }}장</strong>)</span></li>
                        <li class="flex gap-2"><span>•</span><span>탈퇴 후 30일간 재가입이 불가능하며, 동일한 이메일로 가입이 제한될 수 있습니다.</span></li>
                        <li class="flex gap-2"><span>•</span><span>현재 진행 중인 주문, 교환, 환불 건이 있는 경우 완료 후 탈퇴가 가능합니다.</span></li>
                        <li class="flex gap-2"><span>•</span><span>작성하신 게시물 및 리뷰는 탈퇴 후에도 삭제되지 않으므로, 삭제를 원하실 경우 탈퇴 전 미리 삭제해 주시기 바랍니다.</span></li>
                    </ul>
                </div>

                <div class="mt-12 max-w-md mx-auto">
                    <label class="flex items-start gap-3 cursor-pointer group mb-8">
                        <input type="checkbox" id="withdraw_agree" class="mt-1 size-5 rounded-lg border-gray-300 text-primary focus:ring-primary/20 cursor-pointer transition-all">
                        <span class="text-sm font-bold text-text-main text-left leading-relaxed">위 탈퇴 유의사항을 모두 확인하였으며, 내용에 동의합니다.</span>
                    </label>

                    <div class="flex gap-3">
                        <a href="{{ route('mypage') }}" class="flex-1 py-4 bg-gray-100 text-text-muted font-bold rounded-xl hover:bg-gray-200 transition-colors">취소하기</a>
                        <button type="button" id="btn-withdraw" class="flex-1 py-4 bg-white border border-gray-300 text-text-main font-bold rounded-xl hover:bg-gray-50 transition-colors">탈퇴하기</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@push('scripts')
<script>
$(document).ready(function() {
    $('#btn-withdraw').on('click', async function() {
        const $agree = $('#withdraw_agree');
        
        if (!$agree.is(':checked')) {
            showToast('탈퇴 유의사항에 동의해주세요.', 'warning', 'bg-red-500');
            return;
        }

        const confirm = await showConfirm('정말 액티브 우먼을 탈퇴하시겠습니까? <br><small class="text-text-muted">보유하신 혜택이 모두 소멸됩니다. </small>', {
            title: '회원 탈퇴 확인',
            confirmText: '탈퇴 진행'
        });

        if (confirm) {
            const $btn = $(this);
            $btn.prop('disabled', true).text('처리 중...');

            $.ajax({
                url: "{{ route('mypage.withdraw.post') }}",
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        showAlert(response.message, '탈퇴 완료', 'check_circle');
                        window.alertCallback = () => {
                            location.href = "{{ route('home') }}";
                        };
                    }
                },
                error: function(xhr) {
                    const msg = xhr.responseJSON?.message || '탈퇴 처리 중 오류가 발생했습니다.';
                    showToast(msg, 'error', 'bg-red-500');
                    $btn.prop('disabled', false).text('탈퇴하기');
                }
            });
        }
    });
});
</script>
@endpush
@endsection
