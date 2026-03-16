<meta charset="utf-8" />
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
<title>@yield('title', 'Active Women\'s Premium Store')</title>

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
        min-width: 307px !important;
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
