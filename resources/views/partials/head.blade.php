<meta charset="utf-8" />
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<meta name="csrf-token" content="{{ csrf_token() }}">
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
