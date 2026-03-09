@extends('layouts.app')

@section('title', '회원가입 - Active Women\'s Premium Store')

@section('content')
<div class="flex min-h-[calc(100vh-110px)] flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-background-alt">
  <div class="w-full max-w-lg space-y-8 bg-white p-8 sm:p-10 rounded-2xl shadow-sm border border-gray-100">
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
      <h2 class="text-3xl font-bold tracking-tight text-text-main">
        회원가입
      </h2>
      <p class="mt-2 text-sm text-text-muted">
        가입하고 신규 회원 10% 혜택을 받으세요.
      </p>
    </div>

    <!-- Register Form -->
    <form class="mt-8 space-y-6" id="registerForm" novalidate>
      @csrf
      <div class="space-y-5">
        <!-- Name -->
        <div>
          <label for="name" class="block text-sm font-bold text-text-main mb-2">이름 <span
              class="text-primary">*</span></label>
          <input id="name" name="name" type="text"
            class="relative block w-full appearance-none rounded-xl border border-gray-200 px-4 py-3 text-text-main placeholder:text-text-muted focus:z-10 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary text-sm transition-colors"
            placeholder="이름을 입력해주세요" />
          <p id="error-name" class="text-red-500 text-xs mt-1 hidden font-bold"></p>
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-bold text-text-main mb-2">이메일 주소 <span
              class="text-primary">*</span></label>
          <div class="flex gap-2">
            <input id="email" name="email" type="text" autocomplete="email"
              class="relative block w-full appearance-none rounded-xl border border-gray-200 px-4 py-3 text-text-main placeholder:text-text-muted focus:z-10 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary text-sm transition-colors flex-1"
              placeholder="example@email.com" />
            <button type="button" id="btnCheckEmail"
              class="px-4 py-3 bg-gray-100 hover:bg-gray-200 font-bold text-xs text-text-main rounded-xl transition-colors border border-gray-200 whitespace-nowrap">
              중복 확인
            </button>
          </div>
          <p id="error-email" class="text-red-500 text-xs mt-1 hidden font-bold"></p>
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="block text-sm font-bold text-text-main mb-2">비밀번호 <span
              class="text-primary">*</span></label>
          <input id="password" name="password" type="password"
            class="relative block w-full appearance-none rounded-xl border border-gray-200 px-4 py-3 text-text-main placeholder:text-text-muted focus:z-10 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary text-sm transition-colors"
            placeholder="영문, 숫자, 특수문자 조합 8자 이상" />
          <p id="error-password" class="text-red-500 text-xs mt-1 hidden font-bold"></p>
        </div>

        <!-- Password Confirm -->
        <div>
          <label for="password_confirm" class="block text-sm font-bold text-text-main mb-2">비밀번호 확인 <span
              class="text-primary">*</span></label>
          <input id="password_confirm" name="password_confirm" type="password"
            class="relative block w-full appearance-none rounded-xl border border-gray-200 px-4 py-3 text-text-main placeholder:text-text-muted focus:z-10 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary text-sm transition-colors"
            placeholder="비밀번호를 다시 입력해주세요" />
          <p id="error-password_confirm" class="text-red-500 text-xs mt-1 hidden font-bold"></p>
        </div>

        <!-- Phone -->
        <div>
          <label for="phone" class="block text-sm font-bold text-text-main mb-2">휴대폰 번호 <span
              class="text-primary">*</span></label>
          <div class="flex gap-2">
            <input id="phone" name="phone" type="tel"
              class="relative block w-full appearance-none rounded-xl border border-gray-200 px-4 py-3 text-text-main placeholder:text-text-muted focus:z-10 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary text-sm transition-colors flex-1"
              placeholder="010-0000-0000" />
            <button type="button" id="btnSendPhoneAuth"
              class="px-4 py-3 bg-gray-100 hover:bg-gray-200 font-bold text-xs text-text-main rounded-xl transition-colors border border-gray-200 whitespace-nowrap">
              인증번호 받기
            </button>
          </div>
          <p id="error-phone" class="text-red-500 text-xs mt-1 hidden font-bold"></p>

          <!-- Auth Code Input (Hidden initially) -->
          <div id="phoneAuthCodeContainer" class="mt-2 hidden">
            <div class="flex gap-2 relative">
              <input id="phoneAuthCode" type="text"
                class="block w-full appearance-none rounded-xl border border-gray-200 px-4 py-3 text-text-main placeholder:text-text-muted focus:z-10 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary text-sm transition-colors"
                placeholder="6자리 숫자 입력" maxlength="6" />
              <button type="button" id="btnVerifyPhone"
                class="px-4 py-3 bg-text-main hover:bg-gray-800 font-bold text-xs text-white rounded-xl transition-colors whitespace-nowrap">
                인증 확인
              </button>
              <span id="phoneAuthTimer"
                class="absolute right-[100px] top-1/2 -translate-y-1/2 text-primary font-bold text-sm">03:00</span>
            </div>
            <p id="phoneAuthMsg" class="text-xs mt-1 hidden font-bold"></p>
          </div>
        </div>
      </div>

      <!-- Terms Agreement -->
      <div class="pt-4 pb-2 border-t border-gray-100">
        <h3 class="font-bold text-sm text-text-main mb-4">이용약관 동의</h3>

        <div class="space-y-3">
          <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-200 cursor-pointer">
            <input type="checkbox" id="checkAllTerms"
              class="rounded border-gray-300 text-primary w-5 h-5 focus:ring-primary focus:ring-offset-0" />
            <span class="font-bold text-text-main text-sm">전체 동의합니다.</span>
          </label>

          <div class="pl-2 space-y-3 mt-3">
            <div class="flex justify-between items-center group">
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="terms[]" value="service"
                  class="term-checkbox rounded border-gray-300 text-primary w-4 h-4 focus:ring-primary relative top-[-1px]" />
                <span class="text-sm text-text-muted group-hover:text-text-main transition-colors">[필수] 서비스 이용약관
                  동의</span>
              </label>
              <a href="#" class="text-xs font-bold text-text-muted hover:underline">보기</a>
            </div>
            <div class="flex justify-between items-center group">
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="terms[]" value="privacy"
                  class="term-checkbox rounded border-gray-300 text-primary w-4 h-4 focus:ring-primary relative top-[-1px]" />
                <span class="text-sm text-text-muted group-hover:text-text-main transition-colors">[필수] 개인정보 수집 및 이용
                  동의</span>
              </label>
              <a href="#" class="text-xs font-bold text-text-muted hover:underline">보기</a>
            </div>
            <div class="flex justify-between items-center group">
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="terms[]" value="marketing"
                  class="term-checkbox rounded border-gray-300 text-primary w-4 h-4 focus:ring-primary relative top-[-1px]" />
                <span class="text-sm text-text-muted group-hover:text-text-main transition-colors">[선택] 마케팅 정보 수신
                  동의</span>
              </label>
              <a href="#" class="text-xs font-bold text-text-muted hover:underline">보기</a>
            </div>
          </div>
          <p id="error-terms" class="text-red-500 text-xs mt-1 hidden font-bold"></p>
        </div>
      </div>

      <!-- Submit Button -->
      <div>
        <button type="submit" id="registerSubmitBtn"
          class="group relative flex w-full justify-center rounded-xl bg-primary px-4 py-4 text-sm font-bold text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all transform hover:-translate-y-0.5 shadow-md">
          <span id="registerBtnText">가입하기</span>
        </button>
      </div>
    </form>

    <!-- Footer Linking -->
    <div class="pt-6 text-center mt-6">
      <p class="text-sm text-text-muted">
        이미 계정이 있으신가요?
        <a href="{{ route('login') }}" class="font-bold text-primary hover:underline ml-1">로그인하기</a>
      </p>
    </div>
  </div>

  <!-- Toast Popup -->
  <div id="toast"
    class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[100] flex items-center justify-center gap-2 bg-text-main text-white px-6 py-3 rounded-full text-sm font-bold shadow-2xl transition-all duration-300 opacity-0 translate-y-8 pointer-events-none">
    <span class="material-symbols-outlined text-lg text-green-400" id="toastIcon">check_circle</span>
    <span id="toastMsg">처리되었습니다.</span>
  </div>
</div>
@endsection

@push('scripts')
  <script>
    $(document).ready(function() {
      // CSRF 토큰 설정
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      let isEmailChecked = false;
      let lastCheckedEmail = '';

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

      function clearErrors() {
        $('p[id^="error-"]').text('').addClass('hidden');
        $('input').removeClass('border-red-500 focus:border-red-500 focus:ring-red-500');
      }

      function showError(field, message) {
        const $errorEl = $(`#error-${field}`);
        if ($errorEl.length) {
          $errorEl.text(message).removeClass('hidden');
          $(`#${field}`).addClass('border-red-500 focus:border-red-500 focus:ring-red-500');
        }
      }

      // 이메일 입력값 변경 시 중복 확인 상태 초기화
      $('#email').on('input', function() {
        if ($(this).val().trim() !== lastCheckedEmail) {
          isEmailChecked = false;
          $(this).removeClass('border-green-500');
        }
      });

      // 이메일 중복 확인
      $('#btnCheckEmail').on('click', function() {
        const email = $('#email').val().trim();
        if (!email) {
          showError('email', '이메일을 입력해주세요.');
          return;
        }

        $.post('{{ route("check-email") }}', { email: email })
          .done(function(res) {
            if (res.success) {
              showToast(res.message);
              $('#email').removeClass('border-red-500').addClass('border-green-500');
              $('#error-email').addClass('hidden');
              isEmailChecked = true;
              lastCheckedEmail = email;
            } else {
              showError('email', res.message);
              isEmailChecked = false;
            }
          })
          .fail(function(xhr) {
            isEmailChecked = false;
            const errors = xhr.responseJSON.errors;
            if (errors && errors.email) {
              showError('email', errors.email[0]);
            } else {
              showToast('서버 오류가 발생했습니다.', 'error', 'text-white', true);
            }
          });
      });

      // 이용약관 전체 동의
      $('#checkAllTerms').on('change', function() {
        $('.term-checkbox').prop('checked', this.checked);
      });

      $('.term-checkbox').on('change', function() {
        const allChecked = $('.term-checkbox:checked').length === $('.term-checkbox').length;
        $('#checkAllTerms').prop('checked', allChecked);
      });

      // 휴대폰 인증번호 발송 모의 동작 (기존 유지하되 jQuery로 변경)
      let phoneTimerInterval;
      let isPhoneAuthenticated = false;
      let lastAuthenticatedPhone = '';

      $('#phone').on('input', function() {
        if ($(this).val().trim() !== lastAuthenticatedPhone) {
          isPhoneAuthenticated = false;
          $('#phoneAuthCodeContainer').addClass('hidden');
          $('#phoneAuthMsg').addClass('hidden');
        }
      });

      $('#btnSendPhoneAuth').on('click', function() {
        const phone = $('#phone').val().trim();
        if (phone.length < 10) {
          showToast('올바른 휴대폰 번호를 입력해주세요.', 'error', 'text-white', true);
          return;
        }

        $.post('{{ route("sms.send") }}', { phone: phone })
          .done(function(res) {
            $(this).text('재발송').addClass('text-primary');
            showToast(res.message, 'sms', 'text-white');
            $('#phoneAuthCodeContainer').removeClass('hidden');
            $('#phoneAuthCode').val('').focus();
            $('#phoneAuthMsg').text('인증번호를 입력해주세요.').removeClass('hidden text-green-500 text-red-500').addClass('text-text-muted');

            clearInterval(phoneTimerInterval);
            let timeLeft = 180;

            const updateTimer = () => {
              const m = Math.floor(timeLeft / 60);
              const s = timeLeft % 60;
              $('#phoneAuthTimer').text(`${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`);
              if (timeLeft <= 0) {
                clearInterval(phoneTimerInterval);
                showToast('인증 시간이 만료되었습니다.', 'error', 'text-white', true);
                $('#phoneAuthMsg').text('인증 시간이 만료되었습니다. 다시 시도해주세요.').removeClass('text-text-muted').addClass('text-red-500');
              }
              timeLeft--;
            };
            updateTimer();
            phoneTimerInterval = setInterval(updateTimer, 1000);
          }.bind(this))
          .fail(function(xhr) {
            showToast(xhr.responseJSON.message || '인증번호 발송에 실패했습니다.', 'error', 'text-white', true);
          });
      });

      // 휴대폰 인증번호 확인
      $('#btnVerifyPhone').on('click', function() {
        const phone = $('#phone').val().trim();
        const code = $('#phoneAuthCode').val().trim();
        if (code.length !== 6) {
          $('#phoneAuthMsg').text('6자리 인증번호를 입력해주세요.').removeClass('text-text-muted text-green-500').addClass('text-red-500');
          return;
        }

        $.post('{{ route("sms.verify") }}', { phone: phone, code: code })
          .done(function(res) {
            if (res.success) {
              isPhoneAuthenticated = true;
              lastAuthenticatedPhone = phone;
              clearInterval(phoneTimerInterval);
              $('#phoneAuthTimer').addClass('hidden');
              $('#phoneAuthCode').prop('readonly', true);
              $('#btnVerifyPhone').prop('disabled', true).addClass('opacity-50');
              $('#phoneAuthMsg').text(res.message).removeClass('text-text-muted text-red-500').addClass('text-green-500');
              showToast(res.message);
            } else {
              $('#phoneAuthMsg').text(res.message).removeClass('text-text-muted text-green-500').addClass('text-red-500');
            }
          })
          .fail(function(xhr) {
            showToast('서버 오류가 발생했습니다.', 'error', 'text-white', true);
          });
      });

      // 회원가입 폼 제출
      $('#registerForm').on('submit', function(e) {
        e.preventDefault();
        clearErrors();

        // 1. 이메일 중복 확인 여부 체크
        if (!isEmailChecked || $('#email').val().trim() !== lastCheckedEmail) {
          showError('email', '이메일 중복 확인을 해주세요.');
          $('html, body').animate({
            scrollTop: $('#email').offset().top - 120
          }, 500);
          return;
        }

        // 2. 휴대폰 인증 여부 체크
        if (!isPhoneAuthenticated || $('#phone').val().trim() !== lastAuthenticatedPhone) {
          showToast('휴대폰 인증을 완료해주세요.', 'error', 'text-white', true);
          if (!$('#phoneAuthCodeContainer').is(':visible')) {
            $('html, body').animate({
              scrollTop: $('#phone').offset().top - 120
            }, 500);
          } else {
            $('html, body').animate({
              scrollTop: $('#phoneAuthCodeContainer').offset().top - 120
            }, 500);
          }
          return;
        }

        // 3. 비밀번호 규칙 체크 (JS 수준에서 1차 검증)
        const password = $('#password').val();
        const passwordRegex = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&^#_-])[A-Za-z\d@$!%*?&^#_-]{8,}$/;
        if (!passwordRegex.test(password)) {
          showError('password', '비밀번호는 영문, 숫자, 특수문자를 포함하여 8자 이상이어야 합니다.');
          return;
        }

        // 필수 약관 체크 확인
        const serviceTerm = $('input[value="service"]').is(':checked');
        const privacyTerm = $('input[value="privacy"]').is(':checked');

        if (!serviceTerm || !privacyTerm) {
          showError('terms', '필수 이용약관에 동의해주세요.');
          return;
        }

        const $btn = $('#registerSubmitBtn');
        const $btnText = $('#registerBtnText');
        
        $btn.prop('disabled', true);
        $btnText.text('가입 처리 중...');

        $.post('{{ route("register.post") }}', $(this).serialize())
          .done(function(res) {
            if (res.success) {
              showToast(res.message, 'celebration', 'text-yellow-400');
              setTimeout(() => {
                window.location.href = res.redirect;
              }, 1500);
            }
          })
          .fail(function(xhr) {
            $btn.prop('disabled', false);
            $btnText.text('가입하기');

            if (xhr.status === 422) {
              const errors = xhr.responseJSON.errors;
              Object.keys(errors).forEach(key => {
                showError(key, errors[key][0]);
              });
              
              // 첫 번째 에러 위치로 스크롤
              const firstError = Object.keys(errors)[0];
              $('html, body').animate({
                scrollTop: $(`#${firstError}`).offset().top - 120
              }, 500);
            } else {
              showToast('서버 오류가 발생했습니다.', 'error', 'text-white', true);
            }
          });
      });
    });
  </script>
@endpush
