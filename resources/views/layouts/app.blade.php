<!doctype html>
<html lang="ko">

<head>
  @include('partials.head')
  @stack('styles')
</head>

<body class="bg-background-light text-text-main font-display antialiased selection:bg-primary selection:text-white">
  <div class="flex min-h-screen flex-col">
    @include('partials.header')

    <main class="flex-grow">
      @yield('content')
    </main>

    @include('partials.footer')
  </div>

  <!-- jQuery CDN -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <script>
    $(document).ready(function() {
      // 모바일 메뉴 제어
      const $mobileMenu = $('#mobile-menu');
      const $menuOverlay = $('#mobile-menu-overlay');
      const $menuContent = $('#mobile-menu-content');

      function openMobileMenu() {
        $mobileMenu.removeClass('hidden');
        setTimeout(() => {
          $menuOverlay.removeClass('opacity-0').addClass('opacity-100');
          $menuContent.removeClass('-translate-x-full').addClass('translate-x-0');
        }, 10);
        $('body').addClass('overflow-hidden');
      }

      function closeMobileMenu() {
        $menuOverlay.removeClass('opacity-100').addClass('opacity-0');
        $menuContent.removeClass('translate-x-0').addClass('-translate-x-full');
        setTimeout(() => {
          $mobileMenu.addClass('hidden');
          $('body').removeClass('overflow-hidden');
        }, 300);
      }

      $('#mobile-menu-btn').on('click', openMobileMenu);
      $('#mobile-menu-close, #mobile-menu-overlay').on('click', closeMobileMenu);

      // 메뉴 내부 링크 클릭 시 자동 닫기
      $mobileMenu.find('a').on('click', function() {
        if (!$(this).attr('onclick')) {
          closeMobileMenu();
        }
      });

      // 검색창 포커스 효과
      $('header input[type="text"]').on('focus', function() {
        $(this).parent().addClass('shadow-lg shadow-primary/10');
      }).on('blur', function() {
        $(this).parent().removeClass('shadow-lg shadow-primary/10');
      });
    });
  </script>
  @stack('scripts')
</body>

</html>
