<!DOCTYPE html>
<html lang="ko">

<head>
  @include('partials.head')
  @stack('styles')
  <!-- Flatpickr CSS (라이브러리 스타일 우선 로드) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

  <style>
    /* Pretendard 폰트 우선 적용 */
    body { font-family: 'Pretendard', sans-serif; }
    
    /* Flatpickr Global Custom Theme - 프리미엄 레드 테마 */
    .flatpickr-calendar {
        border-radius: 24px !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15) !important;
        border: 1px solid #f1f5f9 !important;
        min-width: 320px !important;
        background: #fff !important;
        padding: 12px !important;
    }
    .flatpickr-months {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        height: 50px !important;
        position: relative !important;
    }
    .flatpickr-month {
        height: 100% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        background: transparent !important;
        color: #1e293b !important;
        fill: #1e293b !important;
        position: static !important;
    }
    .flatpickr-prev-month, .flatpickr-next-month {
        position: absolute !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        padding: 8px !important;
        cursor: pointer !important;
        z-index: 10 !important;
        height: 34px !important;
        width: 34px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 10px !important;
        transition: all 0.2s !important;
    }
    .flatpickr-prev-month:hover, .flatpickr-next-month:hover { background: #f1f5f9 !important; }
    .flatpickr-prev-month { left: 10px !important; }
    .flatpickr-next-month { right: 10px !important; }
    .flatpickr-current-month {
        font-weight: 800 !important;
        font-size: 16px !important;
        color: #1e293b !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px !important;
        position: static !important;
        width: auto !important;
        height: auto !important;
        line-height: 1 !important;
        padding: 0 !important;
        text-align: center !important;
    }
    .flatpickr-weekday {
        color: #94a3b8 !important;
        font-weight: 700 !important;
        font-size: 12px !important;
        text-align: center !important;
    }
    .flatpickr-day {
        flex-basis: 14.28% !important;
        max-width: 42px !important;
        height: 42px !important;
        line-height: 42px !important;
        margin: 2px 0 !important;
        border-radius: 12px !important;
        font-weight: 500 !important;
        font-size: 14px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    .flatpickr-day.selected {
        background: #ec3713 !important;
        border-color: #ec3713 !important;
        color: #fff !important;
        font-weight: 700 !important;
    }
    .flatpickr-day:hover { background: #f8fafc !important; }

    /* Toast Animations */
    @keyframes toast-in {
        from { transform: translateY(100%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    @keyframes toast-out {
        from { transform: translateY(0); opacity: 1; }
        to { transform: translateY(100%); opacity: 0; }
    }
    .toast-enter { animation: toast-in 0.3s ease-out forwards; }
    .toast-exit { animation: toast-out 0.3s ease-in forwards; }
  </style>
</head>

<body class="bg-background-light text-text-main font-display antialiased selection:bg-primary selection:text-white">
  <div class="flex min-h-screen flex-col">
    @include('partials.header')

    <main class="flex-grow">
      @yield('content')
    </main>

    @include('partials.footer')
  </div>

  <!-- Alert Modal -->
  <div id="alert-modal" class="fixed inset-0 z-[10000] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-all">
      <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden animate-in fade-in zoom-in duration-200">
          <div class="p-8 text-center">
              <div class="size-16 rounded-2xl bg-gray-50 flex items-center justify-center mx-auto mb-6">
                  <span id="alert-icon" class="material-symbols-outlined text-3xl text-primary">info</span>
              </div>
              <h3 id="alert-title" class="text-lg font-black text-text-main mb-2">알림</h3>
              <p id="alert-message" class="text-sm font-bold text-text-muted leading-relaxed"></p>
          </div>
          <div class="p-4 bg-gray-50 flex gap-3">
              <button onclick="closeAlert()" class="flex-1 py-[18px] bg-text-main text-white text-sm font-black rounded-2xl hover:bg-black transition-all shadow-lg active:scale-95">확인</button>
          </div>
      </div>
  </div>

  <!-- Confirm Modal -->
  <div id="confirm-modal" class="fixed inset-0 z-[10000] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-all">
      <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden animate-in fade-in zoom-in duration-200">
          <div class="p-8 text-center">
              <div class="size-16 rounded-2xl bg-red-50 flex items-center justify-center mx-auto mb-6">
                  <span class="material-symbols-outlined text-3xl text-primary">help</span>
              </div>
              <h3 id="confirm-title" class="text-lg font-black text-text-main mb-2">확인</h3>
              <p id="confirm-message" class="text-sm font-bold text-text-muted leading-relaxed"></p>
          </div>
          <div class="p-4 bg-gray-50 flex gap-3">
              <button id="confirm-cancel" class="flex-1 py-[18px] bg-white border border-gray-200 text-text-muted text-sm font-black rounded-2xl hover:bg-gray-100 transition-all active:scale-95">취소</button>
              <button id="confirm-accept" class="flex-1 py-[18px] bg-primary text-white text-sm font-black rounded-2xl hover:bg-red-600 transition-all shadow-lg shadow-primary/20 active:scale-95">확인</button>
          </div>
      </div>
  </div>

  <!-- Toast Container -->
  <div id="toastContainer" class="fixed bottom-8 left-1/2 -translate-x-1/2 z-[10001] flex flex-col gap-3 pointer-events-none"></div>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://npmcdn.com/flatpickr/dist/l10n/ko.js"></script>
  <!-- Swiper JS -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script src="{{ asset('js/wishlist.js') }}"></script>

  <script>
    let confirmResolver = null;

    function showAlert(message, title = "알림", icon = "info") { $('#alert-title').text(title); $('#alert-message').html(message); $('#alert-icon').text(icon); $('#alert-modal').removeClass('hidden').addClass('flex'); $('body').addClass('overflow-hidden'); }
    function closeAlert() { $('#alert-modal').removeClass('flex').addClass('hidden'); $('body').removeClass('overflow-hidden'); if (window.alertCallback) { window.alertCallback(); window.alertCallback = null; } }
    function showConfirm(message, options = {}) { $('#confirm-title').text(options.title || "확인"); $('#confirm-message').html(message); $('#confirm-accept').text(options.confirmText || "확인"); $('#confirm-modal').removeClass('hidden').addClass('flex'); $('body').addClass('overflow-hidden'); return new Promise((resolve) => { confirmResolver = resolve; }); }
    function closeConfirm(result) { $('#confirm-modal').removeClass('flex').addClass('hidden'); $('body').removeClass('overflow-hidden'); if (confirmResolver) { confirmResolver(result); confirmResolver = null; } }
    function showToast(message, icon = "check_circle", color = "bg-[#181211]") { const container = document.getElementById("toastContainer"); const toast = document.createElement("div"); toast.className = `flex items-center gap-3 ${color} text-white px-8 py-4 rounded-2xl shadow-2xl text-sm font-bold pointer-events-auto toast-enter`; toast.innerHTML = `<span class="material-symbols-outlined text-xl">${icon}</span><span>${message}</span>`; container.appendChild(toast); setTimeout(() => { toast.classList.remove("toast-enter"); toast.classList.add("toast-exit"); toast.addEventListener("animationend", () => toast.remove()); }, 3000); }

    @inject('settingService', 'App\Services\Admin\SettingManagementService')
    @php
        $settings = $settingService->getSettings();
        $couriersData = collect($settings['couriers'] ?? [])->pluck('url', 'name')->all();
    @endphp
    const COURIER_URLS = @json($couriersData);

    function trackDelivery(courier, trackingNumber) {
        if (!trackingNumber) {
            showToast('운송장 번호가 없습니다.', 'error', 'bg-red-500');
            return;
        }

        const carriers = {
            'CJ대한통운': `https://www.doortodoor.co.kr/parcel/doortodoor_search.jsp?f_invc_no=${trackingNumber}`,
            '한진택배': `https://www.hanjin.com/kor/CMS/DeliveryMgr/WaybillResult.do?mCode=MN038&wblNum=${trackingNumber}`,
            '롯데택배': `https://www.lotteglogis.com/home/reservation/tracking/linkView?InvNo=${trackingNumber}`,
            '로젠택배': `https://www.ilogen.com/web/personal/trace/${trackingNumber}`,
            '우체국택배': `https://service.epost.go.kr/trace.RetrieveDomRcvTracePost.comm?POST_STR=${trackingNumber}`,
            '경동택배': `https://kdexp.com/basic_search.kd?barcode=${trackingNumber}`,
            '대신택배': `https://www.daesin.co.kr/kr/service/service01.jsp?wbl=${trackingNumber}`,
            'CU 편의점택배': `https://www.cupost.co.kr/post/tracking/tracking.cupost?inv_no=${trackingNumber}`,
            'GS25 편의점택배': `https://www.cvsnet.co.kr/invoice/tracking.do?invoice_no=${trackingNumber}`
        };

        let url = carriers[courier];
        
        // 관리자 설정에서 가져온 URL이 있다면 우선 적용!
        if (COURIER_URLS[courier]) {
            url = COURIER_URLS[courier].replace('{tracking_number}', trackingNumber);
        }

        if (!url) {
            url = `https://search.naver.com/search.naver?query=${encodeURIComponent(courier + ' ' + trackingNumber)}`;
        }
        
        window.open(url, '_blank', 'width=800,height=800');
    }

    $(document).ready(function() {
      // 1. Flatpickr 전역 초기화
      flatpickr(".datepicker", {
        locale: "ko",
        dateFormat: "Y-m-d",
        disableMobile: "true",
        animate: true
      });

      // 2. 공용 컨펌 모달 이벤트
      $('#confirm-cancel').on('click', () => closeConfirm(false));
      $('#confirm-accept').on('click', () => closeConfirm(true));

      // 3. 모바일 메뉴 제어
      const $mobileMenu = $('#mobile-menu');
      const $menuOverlay = $('#mobile-menu-overlay');
      const $menuContent = $('#mobile-menu-content');

      $('#open-mobile-menu').on('click', function() {
        $mobileMenu.removeClass('hidden');
        setTimeout(() => {
          $menuOverlay.removeClass('opacity-0').addClass('opacity-100');
          $menuContent.removeClass('-translate-x-full').addClass('translate-x-0');
        }, 10);
        $('body').addClass('overflow-hidden');
      });

      const closeMobileMenu = () => {
        $menuOverlay.removeClass('opacity-100').addClass('opacity-0');
        $menuContent.removeClass('translate-x-0').addClass('-translate-x-full');
        setTimeout(() => {
          $mobileMenu.addClass('hidden');
          $('body').removeClass('overflow-hidden');
        }, 300);
      };

      $('#close-mobile-menu, #mobile-menu-overlay').on('click', closeMobileMenu);

    });

    /**
     * 브라우저 뒤로가기 시 페이지 상태 동기화 (Bfcache 대응)
     */
    window.addEventListener('pageshow', function(event) {
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            // 캐시를 통해 페이지가 로드된 경우 최신 데이터 반영을 위해 새로고침 실행
            window.location.reload();
        }
    });
  </script>
  @stack('scripts')
</body>

</html>
