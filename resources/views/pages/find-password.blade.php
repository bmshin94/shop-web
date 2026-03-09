@extends('layouts.app')

@section('title', '비밀번호 찾기 - Active Women\'s Premium Store')

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
            <h2 class="text-3xl font-bold tracking-tight text-text-main">비밀번호 찾기</h2>
            <p class="mt-2 text-sm text-text-muted" id="stepDescription">
                가입 시 등록한 이메일로 인증번호를 받아 비밀번호를 재설정할 수 있습니다.
            </p>
        </div>

        <!-- Step 1: Email Verification Form -->
        <form id="step1Form" class="mt-8 space-y-6" novalidate>
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-bold text-text-main mb-2">가입 이메일</label>
                    <div class="flex gap-2">
                        <input id="email" name="email" type="text"
                            class="flex-1 block w-full appearance-none rounded-xl border border-gray-200 px-4 py-3 text-text-main placeholder-text-muted focus:z-10 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary text-sm transition-colors"
                            placeholder="이메일을 입력해주세요" />
                        <button type="button" id="btnSendAuthCode"
                            class="flex-shrink-0 bg-gray-100 hover:bg-gray-200 text-text-main px-4 py-3 rounded-xl text-sm font-bold transition-colors border border-gray-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            인증번호 발송
                        </button>
                    </div>
                    <p id="error-email" class="text-red-500 text-xs mt-1 hidden font-bold"></p>
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
                <button type="submit" id="btnVerifyCode" disabled
                    class="group relative flex w-full justify-center rounded-xl bg-primary px-4 py-3.5 text-sm font-bold text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all transform hover:-translate-y-0.5 shadow-md disabled:bg-gray-300 disabled:text-gray-500 disabled:transform-none disabled:shadow-none disabled:cursor-not-allowed">
                    인증 확인
                </button>
            </div>
        </form>

        <!-- Step 2: Reset Password Form (Hidden initially) -->
        <form id="step2Form" class="mt-8 space-y-6 hidden" novalidate>
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="newPassword" class="block text-sm font-bold text-text-main mb-2">새 비밀번호</label>
                    <input id="newPassword" name="password" type="password"
                        class="relative block w-full appearance-none rounded-xl border border-gray-200 px-4 py-3 text-text-main placeholder-text-muted focus:z-10 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary text-sm transition-colors"
                        placeholder="영문, 숫자, 특수문자 조합 8자 이상" />
                    <p id="error-password" class="text-red-500 text-xs mt-1 hidden font-bold"></p>
                </div>
                <div>
                    <label for="confirmPassword" class="block text-sm font-bold text-text-main mb-2">새 비밀번호 확인</label>
                    <input id="confirmPassword" name="password_confirm" type="password"
                        class="relative block w-full appearance-none rounded-xl border border-gray-200 px-4 py-3 text-text-main placeholder-text-muted focus:z-10 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary text-sm transition-colors"
                        placeholder="비밀번호를 한번 더 입력해주세요" />
                    <p id="error-password_confirm" class="text-red-500 text-xs mt-1 hidden font-bold"></p>
                </div>
            </div>

            <div>
                <button type="submit" id="btnResetPassword"
                    class="group relative flex w-full justify-center rounded-xl bg-primary px-4 py-3.5 text-sm font-bold text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all transform hover:-translate-y-0.5 shadow-md">
                    <span id="btnResetPasswordText">비밀번호 변경하기</span>
                </button>
            </div>
        </form>

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
        let isEmailVerified = false;

        // 인증번호 발송
        $('#btnSendAuthCode').on('click', function() {
            const email = $('#email').val().trim();
            if (!email) {
                showError('email', '이메일을 입력해주세요.');
                return;
            }

            clearErrors();
            $(this).prop('disabled', true).text('발송 중...');

            $.post('{{ route("email.send") }}', { email: email })
                .done(function(res) {
                    showToast(res.message, 'mark_email_read', 'text-white');
                    $('#btnSendAuthCode').prop('disabled', false).text('재발송').addClass('text-primary');
                    $('#authCodeContainer').removeClass('hidden');
                    $('#btnVerifyCode').prop('disabled', false);
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
                            $('#btnVerifyCode').prop('disabled', true);
                        }
                        timeLeft--;
                    };
                    updateTimer();
                    timerInterval = setInterval(updateTimer, 1000);
                })
                .fail(function(xhr) {
                    $('#btnSendAuthCode').prop('disabled', false).text('인증번호 발송');
                    showError('email', xhr.responseJSON.message || '인증번호 발송에 실패했습니다.');
                });
        });

        // 인증 확인
        $('#step1Form').on('submit', function(e) {
            e.preventDefault();
            clearErrors();

            const email = $('#email').val().trim();
            const code = $('#authCode').val().trim();

            if (!code) {
                showError('authCode', '인증번호를 입력해주세요.');
                return;
            }

            $.post('{{ route("email.verify") }}', { email: email, code: code })
                .done(function(res) {
                    if (res.success) {
                        isEmailVerified = true;
                        clearInterval(timerInterval);
                        showToast(res.message, 'verified_user', 'text-green-400');
                        $('#step1Form').addClass('hidden');
                        $('#step2Form').removeClass('hidden');
                        $('#stepDescription').text('안전한 사용을 위해 새로운 비밀번호를 등록해주세요.');
                    } else {
                        showError('authCode', res.message);
                    }
                })
                .fail(function() {
                    showToast('서버 오류가 발생했습니다.', 'error', 'text-white', true);
                });
        });

        // 비밀번호 변경
        $('#step2Form').on('submit', function(e) {
            e.preventDefault();
            clearErrors();

            if (!isEmailVerified) {
                showToast('이메일 인증이 필요합니다.', 'error', 'text-white', true);
                return;
            }

            const data = $(this).serialize() + '&email=' + encodeURIComponent($('#email').val().trim());
            const $btnText = $('#btnResetPasswordText');
            
            $('#btnResetPassword').prop('disabled', true);
            $btnText.text('변경 중...');

            $.post('{{ route("password.reset.post") }}', data)
                .done(function(res) {
                    showToast(res.message, 'check_circle', 'text-green-400');
                    setTimeout(() => {
                        window.location.href = "{{ route('login') }}";
                    }, 1500);
                })
                .fail(function(xhr) {
                    $('#btnResetPassword').prop('disabled', false);
                    $btnText.text('비밀번호 변경하기');
                    
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        Object.keys(errors).forEach(key => {
                            showError(key, errors[key][0]);
                        });
                    } else {
                        showToast(xhr.responseJSON.message || '오류가 발생했습니다.', 'error', 'text-white', true);
                    }
                });
        });
    });
</script>
@endpush
