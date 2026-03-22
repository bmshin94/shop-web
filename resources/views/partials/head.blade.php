<meta charset="utf-8" />
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="description" content="{{ $siteSettings['site_description'] ?? '프리미엄 스포츠 기어와 뷰티 에센셜로 완성하는 액티브 라이프스타일, Active Women입니다.' }}">
<meta name="keywords" content="{{ $siteSettings['site_keywords'] ?? '요가복, 레깅스, 필라테스복, 여성스포츠웨어' }}">
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
<title>@yield('title', $siteSettings['mall_name'] ?? 'Active Women\'s Premium Store')</title>

<!-- Fonts -->
<link href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=block" rel="stylesheet" />

<!-- Laravel Vite (Tailwind CSS) -->
@vite(['resources/css/app.css', 'resources/js/app.js'])

<!-- Prevent FOUT and Layout Shift for Material Symbols -->
<style id="fout-guard">
  .material-symbols-outlined { 
    color: transparent !important; 
    display: inline-block;
    width: 1em;
    overflow: hidden;
    white-space: nowrap;
    vertical-align: bottom;
  }
</style>

<!-- Global Form Element Styles -->
<style>
  input[type="checkbox"]:focus, 
  input[type="radio"]:focus {
    --tw-ring-offset-width: 0px !important;
    --tw-ring-width: 0px !important;
    outline: none !important;
    box-shadow: none !important;
  }
</style>

<script>
  document.fonts.ready.then(() => {
    const foutGuard = document.getElementById('fout-guard');
    if (foutGuard) foutGuard.remove();
  });
</script>

<!-- Flatpickr Global Style -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<style>
    /* Flatpickr Global Custom Theme */
    .flatpickr-calendar {
        border-radius: 20px !important;
        box-shadow: 0 20px 40px -15px rgba(0,0,0,0.1) !important;
        border: 1px solid #f3f4f6 !important;
        width: 320px !important;
        padding-bottom: 5px !important;
        box-sizing: border-box !important;
    }
    .flatpickr-innerContainer, .flatpickr-rContainer, .flatpickr-days, .dayContainer {
        width: 100% !important;
        min-width: 100% !important;
        max-width: 100% !important;
    }
    @media (max-width: 640px) {
        .flatpickr-calendar {
            left: 50% !important;
            transform: translateX(-50%) !important;
            right: auto !important;
            margin-top: 10px !important;
        }
        .flatpickr-calendar.arrowTop:before,
        .flatpickr-calendar.arrowTop:after,
        .flatpickr-calendar.arrowBottom:before,
        .flatpickr-calendar.arrowBottom:after {
            display: none !important; /* 화살표 제거 (중앙 정렬 시 어긋나 보이기 때문) */
        }
    }
    .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, 
    .flatpickr-day.selected:hover, .flatpickr-day.nextMonthDay.selected, 
    .flatpickr-day.prevMonthDay.selected {
        background: #ec3713 !important;
        border-color: #ec3713 !important;
        font-weight: 700 !important;
    }
    .flatpickr-months .flatpickr-month, .flatpickr-current-month .flatpickr-monthDropdown-months {
        font-weight: 800 !important;
        color: #181211 !important;
    }
    .flatpickr-weekday {
        color: #896861 !important;
        font-weight: 700 !important;
        font-size: 11px !important;
    }
</style>
