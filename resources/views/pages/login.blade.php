@extends('layouts.app')

@section('title', '로그인 - Active Women\'s Premium Store')

@section('content')
<div class="flex min-h-[calc(100vh-220px)] items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
  <div class="w-full max-w-md space-y-8 bg-white p-8 sm:p-10 rounded-3xl shadow-xl border border-gray-100">
    <div>
      <h2 class="mt-6 text-center text-3xl font-extrabold tracking-tight text-text-main italic">Welcome Back! </h2>
      <p class="mt-2 text-center text-sm text-text-muted">
        다시 만나서 반가워요! 오늘 하루도 액티브하게 보내볼까요? 
      </p>
    </div>
    
    <form class="mt-8 space-y-6" id="loginForm" novalidate>
      @csrf
      <div class="space-y-4 rounded-md shadow-sm">
        <div>
          <label for="email" class="block text-sm font-bold text-text-main mb-1 ml-1">이메일 주소</label>
          <input id="email" name="email" type="text" autocomplete="email"
            class="relative block w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3.5 text-text-main placeholder:text-gray-400 focus:z-10 focus:border-primary focus:ring-primary sm:text-sm transition-all"
            placeholder="example@active.com" />
          <p id="error-email" class="text-red-500 text-xs mt-1 hidden font-bold"></p>
        </div>
        <div>
          <label for="password" class="block text-sm font-bold text-text-main mb-1 ml-1">비밀번호</label>
          <input id="password" name="password" type="password" autocomplete="current-password"
            class="relative block w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3.5 text-text-main placeholder:text-gray-400 focus:z-10 focus:border-primary focus:ring-primary sm:text-sm transition-all"
            placeholder="••••••••" />
          <p id="error-password" class="text-red-500 text-xs mt-1 hidden font-bold"></p>
        </div>
      </div>

      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <input id="remember-me" name="remember-me" type="checkbox"
            class="size-4 rounded border-gray-300 text-primary focus:ring-primary cursor-pointer" />
          <label for="remember-me" class="ml-2 block text-sm text-text-muted cursor-pointer">아이디 저장</label>
        </div>
        <div class="text-sm flex gap-3">
          <a href="{{ route('email.find') }}" class="font-bold text-text-muted hover:text-primary transition-colors">아이디 찾기</a>
          <span class="text-gray-200">|</span>
          <a href="{{ route('password.find') }}" class="font-bold text-primary hover:text-red-600 transition-colors">비밀번호 찾기</a>
        </div>
      </div>

      <div>
        <button type="submit" id="loginSubmitBtn"
          class="group relative flex w-full justify-center rounded-xl bg-primary px-4 py-3.5 text-sm font-bold text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all transform hover:-translate-y-0.5 shadow-md">
          <span id="loginBtnText">로그인</span>
        </button>
      </div>
    </form>

    <!-- Social Login -->
    <div class="mt-8">
      <div class="relative">
        <div class="absolute inset-0 flex items-center">
          <div class="w-full border-t border-gray-200"></div>
        </div>
        <div class="relative flex justify-center text-sm">
          <span class="bg-white px-4 text-text-muted font-bold text-xs uppercase tracking-wider">간편 로그인</span>
        </div>
      </div>

      <div class="mt-6 grid grid-cols-2 gap-3">
        <a href="{{ route('login.social', 'kakao') }}" class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#fae100] px-4 py-3 hover:bg-[#ebd300] transition-colors">
          <span class="rounded-full w-5 h-5 bg-[#3c1e1e] text-[#fae100] text-[10px] flex items-center justify-center font-extrabold">K</span>
          <span class="text-sm font-bold text-[#3c1e1e]">카카오 로그인</span>
        </a>
        <a href="{{ route('login.social', 'naver') }}" class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#03c75a] px-4 py-3 hover:bg-[#02b351] transition-colors">
          <span class="font-extrabold text-[#03c75a] bg-white rounded-sm w-5 h-5 flex items-center justify-center text-xs tracking-tighter">N</span>
          <span class="text-sm font-bold text-white">네이버 로그인</span>
        </a>
      </div>
    </div>

    <div class="pt-6 text-center border-t border-gray-100 mt-6">
      <p class="text-sm text-text-muted">
        아직 회원이 아니신가요?
        <a href="{{ route('register') }}" class="font-bold text-primary hover:underline ml-1">회원가입하기</a>
      </p>
    </div>
  </div>
</div>

{{-- Toast Popup --}}
<div id="toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[100] flex items-center justify-center gap-2 bg-text-main text-white px-6 py-3 rounded-full text-sm font-bold shadow-2xl transition-all duration-300 opacity-0 translate-y-8 pointer-events-none">
  <span class="material-symbols-outlined text-lg text-green-400" id="toastIcon">check_circle</span>
  <span id="toastMsg">처리되었습니다.</span>
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

    $('#loginForm').on('submit', function(e) {
      e.preventDefault();
      clearErrors();

      const $btn = $('#loginSubmitBtn');
      const $btnText = $('#loginBtnText');
      
      $btn.prop('disabled', true);
      $btnText.text('로그인 중...');

      $.post('{{ route("login.post") }}', $(this).serialize())
        .done(function(res) {
          if (res.success) {
            showToast(res.message);
            setTimeout(() => {
              window.location.href = res.redirect;
            }, 1000);
          }
        })
        .fail(function(xhr) {
          $btn.prop('disabled', false);
          $btnText.text('로그인');

          if (xhr.status === 422) {
            const errors = xhr.responseJSON.errors;
            Object.keys(errors).forEach(key => {
              showError(key, errors[key][0]);
            });
            showToast(xhr.responseJSON.message || '로그인 정보를 확인해주세요.', 'error', 'text-white', true);
          } else {
            showToast('서버 오류가 발생했습니다.', 'error', 'text-white', true);
          }
        });
    });
  });
</script>
@endpush
