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
        
        /* Mobile Sidebar Animation */
        #admin-sidebar { transition: transform 0.3s ease-in-out; }
        @media (max-width: 1023px) {
            #admin-sidebar.hidden-mobile { transform: translateX(-100%); }
            #admin-sidebar.show-mobile { transform: translateX(0); }
        }

        /* Toast Animations (From Front-end) */
        @keyframes toastIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes toastOut {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(20px); }
        }
        .toast-enter { animation: toastIn 0.3s ease-out forwards; }
        .toast-exit { animation: toastOut 0.3s ease-in forwards; }

        /* Remove Double Border (Focus Ring) on Form Elements */
        input[type="checkbox"]:focus, 
        input[type="radio"]:focus {
            --tw-ring-offset-width: 0px !important;
            --tw-ring-width: 0px !important;
            outline: none !important;
            box-shadow: none !important;
            border-color: #d1d5db !important; /* 기본 테두리 유지 */
        }
    </style>
    @stack('styles')
</head>

<body class="bg-admin-bg font-display text-text-main overflow-x-hidden">
    @php
        $operatorSessionKey = (string) config('admin_permissions.session_key', 'admin_operator_id');
        $operatorId = session($operatorSessionKey);
        $currentOperator = is_numeric($operatorId) ? \App\Models\Operator::query()->find((int) $operatorId) : null;
        $canAccessMenu = static function (string $menuKey) use ($currentOperator): bool {
            if (! $currentOperator) {
                return true;
            }

            return $currentOperator->hasMenuAccess($menuKey);
        };
    @endphp

    <div class="flex min-h-screen relative">
        <!-- Mobile Overlay -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-[40] hidden lg:hidden opacity-0 transition-opacity duration-300"></div>

        <!-- Sidebar -->
        <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-[50] w-64 bg-admin-sidebar text-white flex flex-col lg:static lg:translate-x-0 hidden-mobile shadow-2xl lg:shadow-none">
            <div class="p-6 border-b border-white/10 flex items-center justify-between">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="flex size-8 items-center justify-center rounded-full bg-primary">
                        <span class="material-symbols-outlined text-white">admin_panel_settings</span>
                    </div>
                    <span class="text-lg font-bold tracking-tight uppercase">Admin Center</span>
                </a>
                <button id="close-sidebar" class="lg:hidden p-2 hover:bg-white/10 rounded-lg">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
                        <nav class="flex-1 py-6 px-4 space-y-1 overflow-y-auto">
                @if($canAccessMenu('dashboard'))
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all">
                        <span class="material-symbols-outlined">dashboard</span>
                        <span class="font-medium">대시보드</span>
                    </a>
                @endif

                <div class="pt-4 pb-2 px-4 text-[11px] font-bold text-white/30 uppercase tracking-widest">쇼핑몰 관리</div>
                @if($canAccessMenu('categories'))
                    <a href="{{ route('admin.categories.index') }}" class="sidebar-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-white/70 hover:text-white">
                        <span class="material-symbols-outlined">category</span>
                        <span class="font-medium">카테고리 관리</span>
                    </a>
                @endif
                @if($canAccessMenu('products'))
                    <a href="{{ route('admin.products.index') }}" class="sidebar-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-white/70 hover:text-white">
                        <span class="material-symbols-outlined">inventory_2</span>
                        <span class="font-medium">상품 관리</span>
                    </a>
                @endif
                @if($canAccessMenu('orders'))
                    <a href="{{ route('admin.orders.index') }}" class="sidebar-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-white/70 hover:text-white">
                        <span class="material-symbols-outlined">shopping_cart</span>
                        <span class="font-medium">주문/배송 관리</span>
                    </a>
                @endif
                @if($canAccessMenu('members'))
                    <a href="{{ route('admin.members.index') }}" class="sidebar-item {{ request()->routeIs('admin.members.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-white/70 hover:text-white">
                        <span class="material-symbols-outlined">group</span>
                        <span class="font-medium">회원 관리</span>
                    </a>
                @endif
                @if($canAccessMenu('operators'))
                    <a href="{{ route('admin.operators.index') }}" class="sidebar-item {{ request()->routeIs('admin.operators.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-white/70 hover:text-white">
                        <span class="material-symbols-outlined">badge</span>
                        <span class="font-medium">운영자 관리</span>
                    </a>
                @endif

                <div class="pt-4 pb-2 px-4 text-[11px] font-bold text-white/30 uppercase tracking-widest">운영 관리</div>
                @if($canAccessMenu('events'))
                    <a href="{{ route('admin.events.index') }}" class="sidebar-item {{ request()->routeIs('admin.events.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-white/70 hover:text-white">
                        <span class="material-symbols-outlined">campaign</span>
                        <span class="font-medium">이벤트 관리</span>
                    </a>
                @endif
                @if($canAccessMenu('exhibitions'))
                    <a href="{{ route('admin.exhibitions.index') }}" class="sidebar-item {{ request()->routeIs('admin.exhibitions.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-white/70 hover:text-white">
                        <span class="material-symbols-outlined">storefront</span>
                        <span class="font-medium">기획전 관리</span>
                    </a>
                @endif
                <a href="#" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-white/70 hover:text-white">
                    <span class="material-symbols-outlined">support_agent</span>
                    <span class="font-medium">고객센터 문의</span>
                </a>
                @if($canAccessMenu('settings'))
                    <a href="{{ route('admin.settings.index') }}" class="sidebar-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-white/70 hover:text-white">
                        <span class="material-symbols-outlined">settings</span>
                        <span class="font-medium">기본 설정</span>
                    </a>
                @endif
            </nav>
            <div class="p-4 border-t border-white/10">
                <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-2 text-sm text-white/50 hover:text-white transition-colors px-4 py-2">
                    <span class="material-symbols-outlined text-[18px]">open_in_new</span> 쇼핑몰 바로가기
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-w-0">
            <!-- Top Nav -->
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 lg:px-8 shrink-0">
                <div class="flex items-center gap-4">
                    <button id="open-sidebar" class="lg:hidden p-2 hover:bg-gray-100 rounded-lg text-text-main">
                        <span class="material-symbols-outlined">menu</span>
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

    <!-- Toast Container (Centered Bottom) -->
    <div id="toastContainer" class="fixed bottom-10 left-1/2 -translate-x-1/2 z-[9999] flex flex-col items-center gap-3 pointer-events-none">
    </div>

    <!-- Global Alert Modal -->
    <div id="alert-modal" class="fixed inset-0 z-[10000] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full overflow-hidden animate-[modalIn_0.2s_ease-out]">
            <div class="p-8 text-center">
                <div id="alert-icon-wrapper" class="size-16 rounded-full bg-primary/10 text-primary flex items-center justify-center mx-auto mb-6">
                    <span id="alert-icon" class="material-symbols-outlined text-[32px]">info</span>
                </div>
                <h4 id="alert-title" class="text-xl font-bold text-text-main mb-2">알림</h4>
                <p id="alert-message" class="text-[11px] font-bold text-text-muted tracking-tight leading-relaxed"></p>
            </div>
            <div class="border-t border-gray-100">
                <button onclick="closeAlert()" class="w-full px-6 py-4 text-sm font-bold text-primary hover:bg-gray-50 transition-colors">
                    확인
                </button>
            </div>
        </div>
    </div>

    <!-- Global Confirm Modal -->
    <div id="confirm-modal" class="fixed inset-0 z-[10001] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full overflow-hidden animate-[modalIn_0.2s_ease-out]">
            <div class="p-8 text-center">
                <div class="size-16 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mx-auto mb-6">
                    <span class="material-symbols-outlined text-[32px]">warning</span>
                </div>
                <h4 id="confirm-title" class="text-xl font-bold text-text-main mb-2">확인</h4>
                <p id="confirm-message" class="text-[12px] font-bold text-text-muted tracking-tight leading-relaxed"></p>
            </div>
            <div class="grid grid-cols-2 border-t border-gray-100">
                <button id="confirm-cancel" type="button" class="px-6 py-4 text-sm font-bold text-text-muted hover:bg-gray-50 transition-colors border-r border-gray-100">
                    취소
                </button>
                <button id="confirm-accept" type="button" class="px-6 py-4 text-sm font-bold text-red-600 hover:bg-red-50 transition-colors">
                    확인
                </button>
            </div>
        </div>
    </div>

    <!-- jQuery & Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        let confirmResolver = null;

        function showAlert(message, title = "알림", icon = "info") {
            $('#alert-title').text(title);
            $('#alert-message').html(message);
            $('#alert-icon').text(icon);
            $('#alert-modal').removeClass('hidden').addClass('flex');
            $('body').addClass('overflow-hidden');
        }

        function closeAlert() {
            $('#alert-modal').removeClass('flex').addClass('hidden');
            $('body').removeClass('overflow-hidden');
        }

        function showConfirm(message, options = {}) {
            const title = options.title || "확인";
            const confirmText = options.confirmText || "확인";

            $('#confirm-title').text(title);
            $('#confirm-message').text(message);
            $('#confirm-accept').text(confirmText);
            $('#confirm-modal').removeClass('hidden').addClass('flex');
            $('body').addClass('overflow-hidden');

            return new Promise((resolve) => {
                confirmResolver = resolve;
            });
        }

        function closeConfirm(result) {
            $('#confirm-modal').removeClass('flex').addClass('hidden');
            $('body').removeClass('overflow-hidden');

            if (confirmResolver) {
                const resolve = confirmResolver;
                confirmResolver = null;
                resolve(result);
            }
        }

        function showToast(message, icon = "check_circle", color = "bg-[#181211]") {
            const container = document.getElementById("toastContainer");
            const toast = document.createElement("div");
            toast.className = `flex items-center gap-3 ${color} text-white px-8 py-4 rounded-2xl shadow-2xl text-sm font-bold pointer-events-auto toast-enter`;
            toast.innerHTML = `<span class="material-symbols-outlined text-xl">${icon}</span><span>${message}</span>`;
            container.appendChild(toast);
            setTimeout(() => {
                toast.classList.remove("toast-enter");
                toast.classList.add("toast-exit");
                toast.addEventListener("animationend", () => toast.remove());
            }, 3000);
        }

        $(document).ready(function() {
            const $sidebar = $('#admin-sidebar');
            const $overlay = $('#sidebar-overlay');
            function openSidebar() { $sidebar.removeClass('hidden-mobile').addClass('show-mobile'); $overlay.removeClass('hidden').addClass('block'); setTimeout(() => $overlay.removeClass('opacity-0').addClass('opacity-100'), 10); $('body').addClass('overflow-hidden'); }
            function closeSidebar() { $sidebar.removeClass('show-mobile').addClass('hidden-mobile'); $overlay.removeClass('opacity-100').addClass('opacity-0'); setTimeout(() => { $overlay.removeClass('block').addClass('hidden'); }, 300); $('body').removeClass('overflow-hidden'); }
            $('#open-sidebar').on('click', openSidebar);
            $('#close-sidebar, #sidebar-overlay').on('click', closeSidebar);

            $('#confirm-cancel').on('click', () => closeConfirm(false));
            $('#confirm-accept').on('click', () => closeConfirm(true));
            $('#confirm-modal').on('click', function(event) {
                if (event.target === this) {
                    closeConfirm(false);
                }
            });

            $(document).on('keydown', function(event) {
                if (event.key === 'Escape' && $('#confirm-modal').hasClass('flex')) {
                    closeConfirm(false);
                }
            });

            $(document).on('submit', 'form.js-confirm-submit', function(event) {
                event.preventDefault();

                const form = this;
                const $form = $(form);
                const message = $form.data('confirm-message') || '이 작업을 진행하시겠습니까?';
                const title = $form.data('confirm-title') || '확인';
                const confirmText = $form.data('confirm-text') || '확인';

                showConfirm(message, { title, confirmText }).then((isConfirmed) => {
                    if (!isConfirmed) {
                        return;
                    }

                    form.submit();
                });
            });

            @if(session('success'))
                showToast("{{ session('success') }}", "check_circle", "bg-[#181211]");
            @endif
            @if(session('error'))
                showToast("{{ session('error') }}", "error", "bg-[#ec3713]");
            @endif
        });
    </script>
    @stack('scripts')
</body>

</html>
