@extends('layouts.app')

@section('title', '아이디 찾기 - Active Women\'s Premium Store')

@section('content')
<div class="flex min-h-[calc(100vh-110px)] flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-background-alt">
    <div class="w-full max-w-md space-y-8 bg-white p-8 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
        <!-- Logo & Header -->
        <div class="text-center">
            <a href="/" class="inline-flex items-center gap-2 mb-6">
                <div class="flex size-10 items-center justify-center rounded-full bg-primary text-white">
                    <span class="material-symbols-outlined text-2xl">stat_1</span>
                </div>
                <h1 class="text-2xl font-extrabold tracking-tight text-text-main">
                    Active Women
                </h1>
            </a>
            <h2 class="text-3xl font-bold tracking-tight text-text-main">아이디 찾기</h2>
            <p class="mt-2 text-sm text-text-muted" id="stepDescription">
                가입 시 등록한 휴대폰 번호로 인증하여 이메일 주소를 찾을 수 있습니다.
            </p>
        </div>

        <!-- Step 1: Phone Verification Form -->
        <div id="verifyStep">
            <form id="phoneAuthForm" class="mt-8 space-y-6" novalidate>
                <div class="space-y-4">
                    <div>
                        <label for="phone" class="block text-sm font-bold text-text-main mb-2">휴대폰 번호</label>
                        <div class="flex gap-2">
                            <input id="phone" name="phone" type="tel" required
                                class="flex-1 block w-full appearance-none rounded-xl border border-gray-200 px-4 py-3 text-text-main placeholder-text-muted focus:z-10 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary text-sm transition-colors"
                                placeholder="010-0000-0000" />
                            <button type="button" id="btnSendSms"
                                class="flex-shrink-0 bg-gray-100 hover:bg-gray-200 text-text-main px-4 py-3 rounded-xl text-sm font-bold transition-colors border border-gray-200 whitespace-nowrap">
                                인증번호 발송
                            </button>
                        </div>
                        <p id="error-phone" class="text-red-500 text-xs mt-1 hidden font-bold"></p>
                    </div>

                    <div id="authCodeContainer" class="hidden transition-all duration-300">
                        <label for="authCode" class="block text-sm font-bold text-text-main mb-2">인증번호</label>
                        <div class="relative">
                            <input id="authCode" name="authCode" type="text"
                                class="block w-full appearance-none rounded-xl border border-gray-200 px-4 py-3 text-text-main placeholder-text-muted focus:z-10 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary text-sm transition-colors"
                                placeholder="6자리 숫자 입력" maxlength="6" />
                            <span id="authTimer"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-primary font-bold text-sm">03:00</span>
                        </div>
                        <p id="error-authCode" class="text-red-500 text-xs mt-1 hidden font-bold"></p>
                    </div>
                </div>

                <div>
                    <button type="submit" id="btnSubmitVerify" disabled
                        class="group relative flex w-full justify-center rounded-xl bg-primary px-4 py-3.5 text-sm font-bold text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all transform hover:-translate-y-0.5 shadow-md disabled:bg-gray-300 disabled:text-gray-500 disabled:transform-none disabled:shadow-none disabled:cursor-not-allowed">
                        인증 확인 및 아이디 찾기
                    </button>
                </div>
            </form>
        </div>

        <!-- Result Step (Hidden initially) -->
        <div id="resultStep" class="mt-8 space-y-8 hidden animate-fade-in">
            <div class="bg-gray-50 rounded-2xl p-6 text-center border border-gray-100">
                <p class="text-sm text-text-muted mb-2">가입하신 이메일 주소입니다.</p>
                <div class="text-xl font-extrabold text-text-main tracking-tight break-all" id="foundEmail">
                    -
                </div>
                <p class="text-xs text-text-muted mt-4">개인정보 보호를 위해 아이디의 일부가 마스킹 되었습니다.</p>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('login') }}" 
                    class="flex h-14 items-center justify-center rounded-xl border-2 border-gray-200 bg-white text-sm font-bold text-text-main transition-colors hover:bg-gray-50">
                    로그인하기
                </a>
                <a href="{{ route('password.find') }}" 
                    class="flex h-14 items-center justify-center rounded-xl bg-primary text-sm font-bold text-white transition-colors hover:bg-red-600 shadow-md">
                    비밀번호 찾기
                </a>
            </div>
        </div>

        <!-- Footer Linking -->
        <div class="pt-6 text-center border-t border-gray-100 mt-6">
            <a href="{{ route('login') }}"
                class="text-sm font-bold text-text-muted hover:text-primary transition-colors flex items-center justify-center gap-1">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                로그인으로 돌아가기
            </a>
        </div>
    </div>
</div>

<!-- Toast Popup -->
<div id="toast"
    class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[100] flex items-center justify-center gap-2 bg-text-main text-white px-6 py-3 rounded-full text-sm font-bold shadow-2xl transition-all duration-300 opacity-0 translate-y-8 pointer-events-none">
    <span class="material-symbols-outlined text-lg text-green-400" id="toastIcon">check_circle</span>
    <span id="toastMsg">처리되었습니다.</span>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const $toast = $('#toast');
        const $toastMsg = $('#toastMsg');
        const $toastIcon = $('#toastIcon');
        let toastTimeout;

        function showToast(message, iconName = 'check_circle', iconColorClass = 'text-green-400', isError = false) {
            $toastMsg.text(message);
            $toastIcon.text(iconName);
            $toastIcon.attr('class', `material-symbols-outlined text-lg ${iconColorClass}`);

            if (isError) {
                $toast.removeClass('bg-text-main').addClass('bg-red-600');
            } else {
                $toast.removeClass('bg-red-600').addClass('bg-text-main');
            }

            $toast.removeClass('opacity-0 translate-y-8');

            clearTimeout(toastTimeout);
            toastTimeout = setTimeout(() => {
                $toast.addClass('opacity-0 translate-y-8');
            }, 3000);
        }

        function showError(field, message) {
            const $errorEl = $(`#error-${field}`);
            if ($errorEl.length) {
                $errorEl.text(message).removeClass('hidden');
                $(`#${field}`).addClass('border-red-500 focus:border-red-500 focus:ring-red-500');
            }
        }

        function clearErrors() {
            $('p[id^="error-"]').text('').addClass('hidden');
            $('input').removeClass('border-red-500 focus:border-red-500 focus:ring-red-500');
        }

        let timerInterval;

        // 인증번호 발송
        $('#btnSendSms').on('click', function() {
            const phone = $('#phone').val().trim();
            if (!phone) {
                showError('phone', '휴대폰 번호를 입력해주세요.');
                return;
            }

            clearErrors();
            $(this).prop('disabled', true).text('발송 중...');

            $.post('{{ route("verify.phone.send") }}', { phone: phone })
                .done(function(res) {
                    showToast(res.message, 'sms', 'text-white');
                    $('#btnSendSms').prop('disabled', false).text('재발송').addClass('text-primary');
                    $('#authCodeContainer').removeClass('hidden');
                    $('#btnSubmitVerify').prop('disabled', false);
                    $('#authCode').val('').focus();

                    clearInterval(timerInterval);
                    let timeLeft = 180;
                    const updateTimer = () => {
                        const m = Math.floor(timeLeft / 60);
                        const s = timeLeft % 60;
                        $('#authTimer').text(`${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`);
                        if (timeLeft <= 0) {
                            clearInterval(timerInterval);
                            showToast('인증 시간이 만료되었습니다.', 'error', 'text-white', true);
                            $('#btnSubmitVerify').prop('disabled', true);
                        }
                        timeLeft--;
                    };
                    updateTimer();
                    timerInterval = setInterval(updateTimer, 1000);
                })
                .fail(function(xhr) {
                    $('#btnSendSms').prop('disabled', false).text('인증번호 발송');
                    showError('phone', xhr.responseJSON.message || '인증번호 발송에 실패했습니다.');
                });
        });

        // 인증 확인 및 아이디 찾기
        $('#phoneAuthForm').on('submit', function(e) {
            e.preventDefault();
            clearErrors();

            const phone = $('#phone').val().trim();
            const code = $('#authCode').val().trim();

            if (!code) {
                showError('authCode', '인증번호를 입력해주세요.');
                return;
            }

            const $btn = $('#btnSubmitVerify');
            $btn.prop('disabled', true).text('처리 중...');

            // 1. 휴대폰 인증 확인
            $.post('{{ route("verify.phone.confirm") }}', { phone: phone, code: code })
                .done(function(res) {
                    if (res.success) {
                        // 2. 인증 성공 시 이메일 찾기 요청
                        $.post('{{ route("email.find.post") }}', { phone: phone })
                            .done(function(findRes) {
                                if (findRes.success) {
                                    clearInterval(timerInterval);
                                    showToast('아이디를 찾았습니다.');
                                    $('#verifyStep').addClass('hidden');
                                    $('#resultStep').removeClass('hidden');
                                    $('#foundEmail').text(findRes.email);
                                    $('#stepDescription').text('회원님의 정보를 찾았습니다.');
                                } else {
                                    showToast(findRes.message, 'error', 'text-white', true);
                                    $btn.prop('disabled', false).text('인증 확인 및 아이디 찾기');
                                }
                            })
                            .fail(function(xhr) {
                                showToast(xhr.responseJSON.message || '오류가 발생했습니다.', 'error', 'text-white', true);
                                $btn.prop('disabled', false).text('인증 확인 및 아이디 찾기');
                            });
                    } else {
                        showError('authCode', res.message);
                        $btn.prop('disabled', false).text('인증 확인 및 아이디 찾기');
                    }
                })
                .fail(function() {
                    showToast('서버 오류가 발생했습니다.', 'error', 'text-white', true);
                    $btn.prop('disabled', false).text('인증 확인 및 아이디 찾기');
                });
        });
    });
</script>
@endpush
