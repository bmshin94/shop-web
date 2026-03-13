<!doctype html>
<html lang="ko">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>관리자 모드 - Active Women's Admin Dashboard</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" rel="stylesheet" />
    
    <!-- Flatpickr CSS (라이브러리 스타일 우선 로드) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#ec3713",
                        "admin-bg": "#f4f7f6",
                        "admin-sidebar": "#1e293b",
                        "text-main": "#1e293b",
                        "text-muted": "#64748b",
                    },
                    fontFamily: {
                        display: ["Pretendard", "sans-serif"],
                    },
                },
            },
        };
    </script>
    <style>
        .material-symbols-outlined { font-size: 22px; }
        .sidebar-item:hover { background-color: rgba(255, 255, 255, 0.1); }
        .sidebar-item.active { background-color: #ec3713; color: white; }
        
        /* Sidebar Transitions */
        #admin-sidebar { 
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s ease-in-out, opacity 0.3s; 
            width: 16rem; 
        }
        
        .sidebar-collapsed #admin-sidebar { 
            width: 0 !important; 
            opacity: 0;
            pointer-events: none;
        }

        @media (max-width: 1023px) {
            #admin-sidebar {
                width: 16rem !important;
                transform: translateX(-100%);
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                z-index: 50;
            }
            #admin-sidebar.show-mobile { transform: translateX(0); }
            .sidebar-collapsed #admin-sidebar { width: 16rem !important; opacity: 1; pointer-events: auto; }
        }

        input[type="checkbox"]:focus, input[type="radio"]:focus {
            --tw-ring-offset-width: 0px !important;
            --tw-ring-width: 0px !important;
            outline: none !important;
            box-shadow: none !important;
            border-color: #d1d5db !important;
        }

        /* Flatpickr Global Custom Theme - Tailwind 간섭 방지 초강력 보정 */
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
        .flatpickr-prev-month:hover, .flatpickr-next-month:hover {
            background: #f1f5f9 !important;
        }
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
        .flatpickr-current-month .cur-month {
            font-weight: 800 !important;
            margin: 0 !important;
        }
        .numInputWrapper {
            width: 75px !important;
            display: inline-block !important;
        }
        .flatpickr-innerContainer {
            display: block !important;
        }
        .flatpickr-rContainer {
            display: block !important;
            width: 100% !important;
        }
        .flatpickr-weekdays {
            display: flex !important;
            width: 100% !important;
            margin-top: 10px !important;
            margin-bottom: 8px !important;
        }

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
    @stack('styles')
</head>

<body class="bg-admin-bg font-display text-text-main overflow-x-hidden">
    @php
        $operatorId = session('admin_operator_id');
        $currentOperator = is_numeric($operatorId) ? \App\Models\Operator::query()->find((int) $operatorId) : null;
        $canAccessMenu = static function (string $menuKey) use ($currentOperator): bool {
            if (! $currentOperator) { return true; }
            return $currentOperator->hasMenuAccess($menuKey);
        };
    @endphp

    <div id="admin-layout" class="flex min-h-screen relative">
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-[40] hidden opacity-0 transition-opacity duration-300"></div>

        <!-- Sidebar -->
        <aside id="admin-sidebar" class="bg-admin-sidebar text-white flex flex-col flex-shrink-0 overflow-hidden shadow-2xl lg:shadow-none">
            <div class="w-64 flex flex-col h-full">
                <div class="p-6 border-b border-white/10 flex items-center justify-between">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                        <div class="flex size-8 items-center justify-center rounded-full bg-primary text-white">
                            <span class="material-symbols-outlined">admin_panel_settings</span>
                        </div>
                        <span class="text-lg font-bold tracking-tight uppercase whitespace-nowrap">Admin Center</span>
                    </a>
                    <button id="close-sidebar" class="lg:hidden p-2 hover:bg-white/10 rounded-lg">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <nav class="flex-1 py-6 px-4 space-y-1 overflow-y-auto">
                    @php $currentGroup = ''; @endphp
                    @foreach($sidebarMenus as $menu)
                        @if($menu->group_name && $menu->group_name !== $currentGroup)
                            <div class="pt-4 pb-2 px-4 text-[11px] font-bold text-white/30 uppercase">{{ $menu->group_name }}</div>
                            @php $currentGroup = $menu->group_name; @endphp
                        @endif
                        @if($canAccessMenu($menu->permission_key))
                            @php
                                $activePattern = $menu->route;
                                if (Str::endsWith($activePattern, '.index')) {
                                    $activePattern = Str::beforeLast($activePattern, '.index') . '.*';
                                }
                            @endphp
                            <a href="{{ $menu->route ? route($menu->route) : '#' }}" 
                               class="sidebar-item {{ ($menu->route && request()->routeIs($activePattern)) ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-white/70 hover:text-white whitespace-nowrap">
                                <span class="material-symbols-outlined">{{ $menu->icon }}</span>
                                <span class="font-medium">{{ $menu->name }}</span>
                            </a>
                        @endif
                    @endforeach
                </nav>
                <div class="p-4 border-t border-white/10">
                    <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-2 text-sm text-white/50 hover:text-white transition-colors px-4 py-2 whitespace-nowrap">
                        <span class="material-symbols-outlined text-[18px]">open_in_new</span> 쇼핑몰 바로가기
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-w-0 transition-all duration-300">
            <!-- Top Nav -->
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 lg:px-8 shrink-0">
                <div class="flex items-center gap-4">
                    <button id="open-sidebar" class="lg:hidden p-2 hover:bg-gray-100 rounded-lg text-text-main">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                    <button id="toggle-sidebar-desktop" class="hidden lg:flex p-2 hover:bg-gray-100 rounded-lg text-text-main transition-colors">
                        <span id="desktop-toggle-icon" class="material-symbols-outlined">menu_open</span>
                    </button>
                    <h2 class="text-base lg:text-lg font-bold text-text-main line-clamp-1">@yield('page_title', '관리자 센터')</h2>
                </div>
                <div class="flex items-center gap-2 lg:gap-4">
                    <div class="flex items-center gap-3 px-2 lg:px-3 py-1 bg-gray-50 rounded-full">
                        <div class="size-7 lg:size-8 rounded-full bg-primary flex items-center justify-center text-white text-[9px] lg:text-[11px] font-bold">
                            {{ mb_substr(Auth::guard('admin')->user()->name ?? 'A', 0, 1) }}
                        </div>
                        <span class="hidden sm:inline text-[11px] lg:text-sm font-bold text-text-main">{{ Auth::guard('admin')->user()->name ?? '관리자' }} 님</span>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="p-2 hover:bg-gray-100 rounded-lg text-text-muted hover:text-primary transition-colors flex items-center" title="로그아웃">
                            <span class="material-symbols-outlined">logout</span>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Page Body -->
            <div class="flex-1 overflow-y-auto p-4 lg:p-8 relative">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Alert Modal -->
    <div id="alert-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-all">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden animate-in fade-in zoom-in duration-200">
            <div class="p-8 text-center">
                <div class="size-16 rounded-2xl bg-gray-50 flex items-center justify-center mx-auto mb-6">
                    <span id="alert-icon" class="material-symbols-outlined text-3xl text-primary">info</span>
                </div>
                <h3 id="alert-title" class="text-lg font-black text-text-main mb-2">알림</h3>
                <p id="alert-message" class="text-sm font-bold text-text-muted leading-relaxed"></p>
            </div>
            <div class="p-4 bg-gray-50 flex gap-3">
                <button onclick="closeAlert()" class="flex-1 py-3 bg-text-main text-white text-sm font-black rounded-xl hover:bg-black transition-all">확인</button>
            </div>
        </div>
    </div>

    <!-- Confirm Modal -->
    <div id="confirm-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-all">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden animate-in fade-in zoom-in duration-200">
            <div class="p-8 text-center">
                <div class="size-16 rounded-2xl bg-red-50 flex items-center justify-center mx-auto mb-6">
                    <span class="material-symbols-outlined text-3xl text-primary">help</span>
                </div>
                <h3 id="confirm-title" class="text-lg font-black text-text-main mb-2">확인</h3>
                <p id="confirm-message" class="text-sm font-bold text-text-muted leading-relaxed"></p>
            </div>
            <div class="p-4 bg-gray-50 flex gap-3">
                <button id="confirm-cancel" class="flex-1 py-3 bg-white border border-gray-200 text-text-muted text-sm font-black rounded-xl hover:bg-gray-100 transition-all">취소</button>
                <button id="confirm-accept" class="flex-1 py-3 bg-primary text-white text-sm font-black rounded-xl hover:bg-red-600 transition-all shadow-lg shadow-primary/20">확인</button>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toastContainer" class="fixed bottom-8 left-1/2 -translate-x-1/2 z-[110] flex flex-col gap-3 pointer-events-none"></div>

    <!-- jQuery & Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ko.js"></script>
    <script>
        let confirmResolver = null;
        
        // Flatpickr 전역 초기화
        function initGlobalDatepickers() {
            flatpickr(".datepicker", {
                locale: "ko",
                dateFormat: "Y-m-d",
                disableMobile: "true",
                animate: true
            });
            
            flatpickr(".datepicker-datetime", {
                locale: "ko",
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                disableMobile: "true",
                animate: true
            });
        }

        function showAlert(message, title = "알림", icon = "info") { $('#alert-title').text(title); $('#alert-message').html(message); $('#alert-icon').text(icon); $('#alert-modal').removeClass('hidden').addClass('flex'); $('body').addClass('overflow-hidden'); }
        function closeAlert() { $('#alert-modal').removeClass('flex').addClass('hidden'); $('body').removeClass('overflow-hidden'); }
        function showConfirm(message, options = {}) { $('#confirm-title').text(options.title || "확인"); $('#confirm-message').html(message); $('#confirm-accept').text(options.confirmText || "확인"); $('#confirm-modal').removeClass('hidden').addClass('flex'); $('body').addClass('overflow-hidden'); return new Promise((resolve) => { confirmResolver = resolve; }); }
        function closeConfirm(result) { $('#confirm-modal').removeClass('flex').addClass('hidden'); $('body').removeClass('overflow-hidden'); if (confirmResolver) { confirmResolver(result); confirmResolver = null; } }
        function showToast(message, icon = "check_circle", color = "bg-[#181211]") { const container = document.getElementById("toastContainer"); const toast = document.createElement("div"); toast.className = `flex items-center gap-3 ${color} text-white px-8 py-4 rounded-2xl shadow-2xl text-sm font-bold pointer-events-auto toast-enter`; toast.innerHTML = `<span class="material-symbols-outlined text-xl">${icon}</span><span>${message}</span>`; container.appendChild(toast); setTimeout(() => { toast.classList.remove("toast-enter"); toast.classList.add("toast-exit"); toast.addEventListener("animationend", () => toast.remove()); }, 3000); }

        $(document).ready(function() {
            initGlobalDatepickers();
            const $layout = $('#admin-layout');
            const $sidebar = $('#admin-sidebar');
            const $overlay = $('#sidebar-overlay');
            const $toggleIcon = $('#desktop-toggle-icon');

            // 사이드바 토글 로직
            $('#toggle-sidebar-desktop').on('click', function() {
                const isCollapsed = $layout.toggleClass('sidebar-collapsed').hasClass('sidebar-collapsed');
                localStorage.setItem('admin_sidebar_collapsed', isCollapsed);
                $toggleIcon.text(isCollapsed ? 'menu' : 'menu_open');
            });

            $('#open-sidebar').on('click', function() { $sidebar.addClass('show-mobile'); $overlay.removeClass('hidden').addClass('block'); setTimeout(() => $overlay.removeClass('opacity-0').addClass('opacity-100'), 10); });
            $('#close-sidebar, #sidebar-overlay').on('click', function() { $sidebar.removeClass('show-mobile'); $overlay.removeClass('opacity-100').addClass('opacity-0'); setTimeout(() => $overlay.removeClass('block').addClass('hidden'), 300); });

            // 공용 컨펌 모달 핸들러
            $('#confirm-cancel').on('click', () => closeConfirm(false));
            $('#confirm-accept').on('click', () => closeConfirm(true));
            $('#confirm-modal').on('click', function(e) { if (e.target === this) closeConfirm(false); });
            $(document).on('keydown', function(e) { if (e.key === 'Escape' && $('#confirm-modal').hasClass('flex')) closeConfirm(false); });
            
            // 클래스 기반 자동 모달 제출 핸들러
            $(document).on('submit', 'form.js-confirm-submit', function(e) {
                e.preventDefault();
                const form = this;
                showConfirm($(this).data('confirm-message') || '이 작업을 진행하시겠습니까?', { 
                    title: $(this).data('confirm-title'), 
                    confirmText: $(this).data('confirm-text') 
                }).then((res) => { 
                    if (res) form.submit(); 
                });
            });

            @if(session('success')) showToast("{{ session('success') }}", "check_circle", "bg-[#181211]"); @endif
            @if(session('error')) showToast("{{ session('error') }}", "error", "bg-[#ec3713]"); @endif
        });
    </script>
    @stack('scripts')
</body>
</html>
