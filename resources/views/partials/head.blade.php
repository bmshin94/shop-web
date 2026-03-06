<meta charset="utf-8" />
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<title>@yield('title', 'Active Women\'s Premium Store')</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css" rel="stylesheet" />
<link
  href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=block"
  rel="stylesheet" />
<script>
  tailwind.config = {
    darkMode: "class",
    theme: {
      extend: {
        colors: {
          primary: "#ec3713",
          "primary-light": "#ffefe5",
          "background-light": "#ffffff",
          "background-alt": "#f8f6f6",
          "background-dark": "#221310",
          "text-main": "#181211",
          "text-muted": "#896861",
        },
        fontFamily: {
          display: [
            "Pretendard",
            "-apple-system",
            "BlinkMacSystemFont",
            "system-ui",
            "Roboto",
            "Helvetica Neue",
            "Segoe UI",
            "Apple SD Gothic Neo",
            "Noto Sans KR",
            "Malgun Gothic",
            "sans-serif",
          ],
        },
        borderRadius: {
          DEFAULT: "0.25rem",
          lg: "0.5rem",
          xl: "0.75rem",
          full: "9999px",
        },
      },
    },
  };
</script>
<style>
  /* Tailwind 로드 전 텍스트 깜빡임(FOUC) 방지 */
  .hidden {
    display: none;
  }

  .scrollbar-hide::-webkit-scrollbar {
    display: none;
  }

  .scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
  }
</style>

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
<script>
  document.fonts.ready.then(() => {
    const foutGuard = document.getElementById('fout-guard');
    if (foutGuard) foutGuard.remove();
  });
</script>
