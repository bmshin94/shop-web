@extends('layouts.app')

@section('title', '회원가입 - Active Women\'s Premium Store')

@section('content')

@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // --- 토스트 알림 컴포넌트 ---
      const toast = document.getElementById('toast');
      const toastMsg = document.getElementById('toastMsg');
      const toastIcon = document.getElementById('toastIcon');
      let toastTimeout;

      function showToast(message, iconName = 'check_circle', iconColorClass = 'text-green-400', isError = false) {
        toastMsg.textContent = message;
        toastIcon.textContent = iconName;
        toastIcon.className = `material-symbols-outlined text-lg ${iconColorClass}`;

        if (isError) {
          toast.classList.replace('bg-text-main', 'bg-red-600');
        } else {
          toast.classList.replace('bg-red-600', 'bg-text-main');
        }

        toast.classList.remove('opacity-0', 'translate-y-8');

        clearTimeout(toastTimeout);
        toastTimeout = setTimeout(() => {
          toast.classList.add('opacity-0', 'translate-y-8');
        }, 3000);
      }

      // --- 이메일 중복 확인 모의 동작 ---
      const emailInput = document.getElementById('email');
      const btnCheckEmail = document.getElementById('btnCheckEmail');
      const emailErrorMsg = document.getElementById('emailErrorMsg');

      function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
      }

      btnCheckEmail.addEventListener('click', () => {
        const emailVal = emailInput.value.trim();
        if (!isValidEmail(emailVal)) {
          emailErrorMsg.classList.remove('hidden');
          emailInput.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
        } else {
          emailErrorMsg.classList.add('hidden');
          emailInput.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
          showToast('사용 가능한 이메일입니다.', 'check_circle', 'text-green-400');
        }
      });

      // --- 비밀번호 일치 실시간 검증 ---
      const passwordInput = document.getElementById('password');
      const confirmPasswordInput = document.getElementById('password_confirm');
      const passwordMatchMsg = document.getElementById('passwordMatchMsg');

      confirmPasswordInput.addEventListener('input', () => {
        if (passwordInput.value && confirmPasswordInput.value !== passwordInput.value) {
          passwordMatchMsg.classList.remove('hidden');
        } else {
          passwordMatchMsg.classList.add('hidden');
        }
      });

      // --- 휴대폰 인증번호 발송 모의 동작 ---
      const phoneInput = document.getElementById('phone');
      const btnSendPhoneAuth = document.getElementById('btnSendPhoneAuth');
      const phoneAuthCodeContainer = document.getElementById('phoneAuthCodeContainer');
      const phoneAuthTimer = document.getElementById('phoneAuthTimer');
      let phoneTimerInterval;

      btnSendPhoneAuth.addEventListener('click', () => {
        if (phoneInput.value.length < 10) {
          showToast('올바른 휴대폰 번호를 입력해주세요.', 'error', 'text-white', true);
          return;
        }

        btnSendPhoneAuth.textContent = '재발송';
        btnSendPhoneAuth.classList.add('text-primary');
        showToast('인증번호 6자리를 발송했습니다.', 'sms', 'text-white');

        phoneAuthCodeContainer.classList.remove('hidden');

        clearInterval(phoneTimerInterval);
        let timeLeft = 180; // 3분

        function updateTimer() {
          const m = Math.floor(timeLeft / 60);
          const s = timeLeft % 60;
          phoneAuthTimer.textContent = `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
          if (timeLeft <= 0) {
            clearInterval(phoneTimerInterval);
            showToast('인증 시간이 만료되었습니다.', 'error', 'text-white', true);
          }
          timeLeft--;
        }
        updateTimer();
        phoneTimerInterval = setInterval(updateTimer, 1000);
      });

      // --- 이용약관 전체 동의 연동 ---
      const checkAllTerms = document.getElementById('checkAllTerms');
      const termCheckboxes = document.querySelectorAll('.term-checkbox');

      checkAllTerms.addEventListener('change', (e) => {
        const isChecked = e.target.checked;
        termCheckboxes.forEach(cb => cb.checked = isChecked);
      });

      // 리스너: 하위 체크박스 변경 시 전체 동의 체크박스 상태 업데이트
      termCheckboxes.forEach(cb => {
        cb.addEventListener('change', () => {
          const allChecked = Array.from(termCheckboxes).every(t => t.checked);
          checkAllTerms.checked = allChecked;
        });
      });

      // --- 회원가입 폼 제출 인터랙션 ---
      const registerForm = document.querySelector('form');
      const registerBtnText = document.getElementById('registerBtnText');

      registerForm.addEventListener('submit', (e) => {
        e.preventDefault();

        // 최종 비밀번호 일치 확인
        if (passwordInput.value !== confirmPasswordInput.value) {
          confirmPasswordInput.classList.add('border-red-500', 'focus:border-red-500');
          passwordMatchMsg.classList.remove('hidden');
          window.scrollTo({ top: passwordMatchMsg.parentElement.offsetTop - 100, behavior: 'smooth' });
          return;
        }

        // (인증번호 검증 시뮬레이션 생략, 무조건 통과하는 것으로 간주)

        // 로딩 상태 추가 후 완료 처리 연출
        registerBtnText.textContent = '가입 처리 중...';

        setTimeout(() => {
          showToast('가입이 완료되었습니다! 프리미엄 혜택을 환영합니다.', 'celebration', 'text-yellow-400');

          setTimeout(() => {
            window.location.href = '/';
          }, 1800);
        }, 1000);
      });
    });
  </script>
@endpush
