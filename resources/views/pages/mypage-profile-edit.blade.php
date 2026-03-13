@extends('layouts.app')

@section('title', '회원정보 수정 | 마이페이지 - Active Women\'s Premium Store')

@section('content')
<main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-text-muted mb-6">
        <a href="/" class="hover:text-primary transition-colors">Home</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <a href="{{ route('mypage') }}" class="hover:text-primary transition-colors">마이페이지</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <span class="font-bold text-text-main">회원정보 수정</span>
    </nav>

    <!-- Page Title -->
    <h2 class="text-3xl font-extrabold text-text-main tracking-tight mb-8">회원정보 수정</h2>

    <div class="flex flex-col lg:flex-row gap-8 items-start">
        <!-- LNB (Left Navigation Bar) -->
        @include('partials.mypage-sidebar')

        <!-- Main Dashboard Content -->
        <div class="flex-1 w-full space-y-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8 lg:p-10 mb-8 max-w-3xl mx-auto">
                {{-- 상단 헤더 --}}
                <div class="flex justify-end items-center mb-10 border-b border-gray-50 pb-6">
                    <span class="text-[11px] text-primary font-bold"><span class="text-primary">*</span> 필수입력</span>
                </div>
                
                <form id="profileEditForm" class="space-y-10">
                    @csrf
                    @method('PATCH')
                    
                    {{-- 기본 정보 --}}
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 items-center">
                            <label class="text-sm font-black text-text-main">이메일 (아이디)</label>
                            <div class="sm:col-span-2">
                                <input type="text" value="{{ $member->email }}" disabled 
                                       class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl text-gray-400 text-sm font-medium cursor-not-allowed">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 items-center">
                            <label class="text-sm font-black text-text-main">이름</label>
                            <div class="sm:col-span-2">
                                <input type="text" value="{{ $member->name }}" disabled 
                                       class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl text-gray-400 text-sm font-medium cursor-not-allowed">
                            </div>
                        </div>
                    </div>

                    {{-- 비밀번호 변경 --}}
                    <div class="pt-8 border-t border-gray-50 space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 items-start">
                            <label class="text-sm font-black text-text-main sm:mt-3">새 비밀번호</label>
                            <div class="sm:col-span-2">
                                <input type="password" name="password" placeholder="영문, 숫자, 특수문자 조합 8-16자" 
                                       class="w-full h-12 px-4 bg-white border border-gray-200 rounded-xl text-text-main text-sm font-bold focus:border-primary focus:ring-primary/20 transition-all">
                                <p class="text-[11px] text-text-muted mt-2 ml-1">변경할 경우에만 입력해 주세요. ✨</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 items-center">
                            <label class="text-sm font-black text-text-main">새 비밀번호 확인</label>
                            <div class="sm:col-span-2">
                                <input type="password" name="password_confirmation" placeholder="비밀번호를 한번 더 입력해 주세요" 
                                       class="w-full h-12 px-4 bg-white border border-gray-200 rounded-xl text-text-main text-sm font-bold focus:border-primary focus:ring-primary/20 transition-all">
                            </div>
                        </div>
                    </div>

                    {{-- 휴대폰 번호 --}}
                    <div class="pt-8 border-t border-gray-50 space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 items-center">
                            <label class="text-sm font-black text-text-main">휴대폰 번호 <span class="text-primary">*</span></label>
                            <div class="sm:col-span-2 flex gap-2">
                                <input type="text" name="phone" id="user_phone" value="{{ $member->phone }}" readonly
                                       class="flex-1 h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl text-gray-500 text-sm font-bold cursor-not-allowed focus:ring-0">
                                <button type="button" id="btn-open-verify-modal"
                                        class="px-6 bg-white border border-gray-200 text-text-main font-black rounded-xl hover:bg-gray-50 transition-all text-[13px] whitespace-nowrap">인증변경</button>
                            </div>
                        </div>
                    </div>
                    
                    {{-- 기본 배송지 --}}
                    <div class="pt-8 border-t border-gray-50 space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 items-start">
                            <label class="text-sm font-black text-text-main sm:mt-3">기본 배송지</label>
                            <div class="sm:col-span-2 space-y-3">
                                <div class="flex gap-2">
                                    <input type="text" name="postal_code" id="postal_code" value="{{ $member->postal_code }}" placeholder="우편번호" readonly 
                                           class="w-32 h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl text-gray-600 text-sm font-bold cursor-default focus:ring-0">
                                    <button type="button" onclick="execDaumPostcode()" 
                                            class="px-6 bg-text-main text-white font-black rounded-xl hover:bg-black transition-all text-[13px] whitespace-nowrap shadow-sm">우편번호 찾기</button>
                                </div>
                                <input type="text" name="address_line1" id="address_line1" value="{{ $member->address_line1 }}" placeholder="기본 주소" readonly 
                                       class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl text-gray-600 text-sm font-bold cursor-default focus:ring-0">
                                <input type="text" name="address_line2" id="address_line2" value="{{ $member->address_line2 }}" placeholder="상세 주소를 입력해 주세요" 
                                       class="w-full h-12 px-4 bg-white border border-gray-200 rounded-xl text-text-main text-sm font-bold focus:border-primary focus:ring-primary/20 transition-all">
                            </div>
                        </div>
                    </div>
                    
                    {{-- 마케팅 수신동의 --}}
                    <div class="pt-8 border-t border-gray-50 space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 items-start">
                            <div class="sm:mt-1">
                                <label class="text-sm font-black text-text-main">마케팅 수신동의</label>
                                <p class="text-[11px] text-text-muted mt-1 leading-tight">다양한 이벤트 및 혜택 안내 정보를 받아보실 수 있습니다. ✨</p>
                            </div>
                            <div class="sm:col-span-2 flex items-center gap-8 mt-2">
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="checkbox" name="marketing_sms" value="1" {{ $member->marketing_sms ? 'checked' : '' }} 
                                           class="size-6 rounded-lg border-gray-300 text-primary focus:ring-primary/20 cursor-pointer transition-all">
                                    <span class="text-sm font-bold text-text-muted group-hover:text-text-main transition-colors">SMS 수신</span>
                                </label>
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="checkbox" name="marketing_email" value="1" {{ $member->marketing_email ? 'checked' : '' }} 
                                           class="size-6 rounded-lg border-gray-300 text-primary focus:ring-primary/20 cursor-pointer transition-all">
                                    <span class="text-sm font-bold text-text-muted group-hover:text-text-main transition-colors">이메일 수신</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- 버튼 --}}
                    <div class="flex gap-4 pt-8 border-t border-gray-50">
                        <button type="button" onclick="history.back()" class="flex-1 h-14 bg-gray-100 text-text-muted text-sm font-black rounded-2xl hover:bg-gray-200 transition-all">취소</button>
                        <button type="submit" class="flex-1 h-14 bg-primary text-white font-black rounded-2xl hover:bg-red-600 transition-all shadow-lg shadow-primary/30 active:scale-95">수정 완료</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

{{-- 휴대폰 인증 변경 모달 ✨ --}}
<div id="phoneVerifyModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="relative bg-white rounded-3xl shadow-2xl max-w-sm w-full overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-black text-text-main">휴대폰 번호 변경</h3>
            <button type="button" class="btn-close-modal size-8 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-400">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>
        </div>
        <div class="p-8 space-y-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-[10px] font-black text-text-muted uppercase mb-2 tracking-widest">New Phone Number</label>
                    <div class="flex gap-2">
                        <input type="tel" id="new_phone" placeholder="010-0000-0000" 
                               class="flex-1 h-12 px-4 rounded-xl border-gray-200 focus:border-primary focus:ring-primary/20 text-sm font-bold transition-all">
                        <button type="button" id="btn-send-code" class="px-4 bg-text-main text-white text-[11px] font-black rounded-xl hover:bg-black transition-all whitespace-nowrap">번호발송</button>
                    </div>
                </div>
                <div id="verify-code-area" class="hidden animate-in slide-in-from-top-2">
                    <label class="block text-[10px] font-black text-text-muted uppercase mb-2 tracking-widest">Verification Code</label>
                    <div class="flex gap-2">
                        <input type="text" id="verify_code" maxlength="6" placeholder="6자리 입력" 
                               class="flex-1 h-12 px-4 rounded-xl border-gray-200 focus:border-primary focus:ring-primary/20 text-sm font-black tracking-[0.5em] text-center">
                        <button type="button" id="btn-verify-code" class="px-4 bg-primary text-white text-[11px] font-black rounded-xl hover:bg-red-600 transition-all whitespace-nowrap shadow-md shadow-primary/20">인증확인</button>
                    </div>
                    <p class="text-[10px] text-primary font-bold mt-2 ml-1" id="timer">03:00</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script>
// Modal Functions ✨ (전역 스코프 보장)
window.openVerifyModal = function(modal) {
    if (!modal) return;
    $(modal).removeClass('hidden').addClass('flex');
    $('body').css('overflow', 'hidden');
}
window.closeVerifyModal = function(modal) {
    if (!modal) return;
    $(modal).addClass('hidden').removeClass('flex');
    $('body').css('overflow', '');
}

function execDaumPostcode() {
    new daum.Postcode({
        oncomplete: function(data) {
            let addr = data.userSelectedType === 'R' ? data.roadAddress : data.jibunAddress;
            document.getElementById('postal_code').value = data.zonecode;
            document.getElementById('address_line1').value = addr;
            document.getElementById('address_line2').focus();
        }
    }).open();
}

$(document).ready(function() {
    let timerInterval;

    function startTimer() {
        let timeLeft = 180;
        clearInterval(timerInterval);
        timerInterval = setInterval(() => {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            $('#timer').text(`${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`);
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                showToast('인증 시간이 만료되었습니다.', 'error', 'bg-red-500');
            }
            timeLeft--;
        }, 1000);
    }

    // 모달 열기 버튼 이벤트 ✨
    $('#btn-open-verify-modal').on('click', function() {
        window.openVerifyModal(document.getElementById('phoneVerifyModal'));
    });

    // 모달 닫기 버튼 이벤트 ✨
    $('.btn-close-modal').on('click', function() {
        window.closeVerifyModal(document.getElementById('phoneVerifyModal'));
    });

    // 모달 외부 클릭 시 닫기
    $('#phoneVerifyModal').on('click', function(e) {
        if (e.target === this) window.closeVerifyModal(this);
    });

    // 인증번호 발송 ✨
    $('#btn-send-code').on('click', function() {
        const phone = $('#new_phone').val();
        if (!/^010-?\d{3,4}-?\d{4}$/.test(phone)) {
            showToast('올바른 휴대폰 번호를 입력해 주세요. (예: 010-1234-5678)', 'error', 'bg-red-500');
            return;
        }

        const $btn = $(this);
        $btn.prop('disabled', true).text('발송 중...');

        $.ajax({
            url: "{{ route('verify.phone.send') }}",
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            data: { phone: phone },
            success: function(response) {
                if (response.success) {
                    showToast(response.message, 'sms', 'bg-primary');
                    $('#verify-code-area').removeClass('hidden');
                    startTimer();
                    $btn.text('재발송');
                } else {
                    showToast(response.message || '발송에 실패했습니다.', 'error', 'bg-red-500');
                    $btn.text('번호발송');
                }
                $btn.prop('disabled', false);
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.message || '서버 통신 오류가 발생했습니다.';
                showToast(errorMsg, 'error', 'bg-red-500');
                $btn.prop('disabled', false).text('번호발송');
            }
        });
    });

    // 인증번호 확인 ✨
    $('#btn-verify-code').on('click', function() {
        const phone = $('#new_phone').val();
        const code = $('#verify_code').val();

        if (code.length !== 6) {
            showToast('인증번호 6자리를 입력해 주세요.', 'warning', 'bg-red-500');
            return;
        }

        $.ajax({
            url: "{{ route('verify.phone.confirm') }}",
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            data: { phone: phone, code: code },
            success: function(response) {
                if (response.success) {
                    showToast('인증이 완료되었습니다! ✨', 'check_circle', 'bg-primary');
                    $('#user_phone').val(phone); 
                    window.closeVerifyModal(document.getElementById('phoneVerifyModal'));
                }
            },
            error: function(xhr) {
                showToast(xhr.responseJSON?.message || '인증에 실패했습니다.', 'error', 'bg-red-500');
            }
        });
    });

    // 프로필 수정 제출
    $('#profileEditForm').on('submit', function(e) {
        e.preventDefault();
        const $btn = $(this).find('button[type="submit"]');
        $btn.prop('disabled', true).text('수정 중...');

        $.ajax({
            url: "{{ route('mypage.profile.update') }}",
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    showToast(response.message, 'check_circle', 'bg-primary');
                    setTimeout(() => {
                        location.href = "{{ route('mypage') }}";
                    }, 1500);
                }
            },
            error: function(xhr) {
                showToast(xhr.responseJSON?.message || '수정에 실패했습니다.', 'error', 'bg-red-500');
                $btn.prop('disabled', false).text('수정 완료');
            }
        });
    });
});
</script>
@endpush
