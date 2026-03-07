<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>관리자 로그인 - HER FIELD</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Google Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <style>
        .login-bg {
            background-image: radial-gradient(circle at 50% -20%, #fef2f0, #f8f6f6);
        }
    </style>
</head>
<body class="login-bg min-h-screen flex items-center justify-center p-4">

    <!-- Card Container -->
    <div class="w-full max-w-[420px] bg-white rounded-[2rem] shadow-[0_20px_40px_-15px_rgba(236,55,19,0.15)] border border-gray-100 overflow-hidden transition-all duration-300 relative">
        
        <!-- Decorative Elements -->
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-primary to-orange-400"></div>
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-primary/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-primary/5 rounded-full blur-3xl"></div>

        <div class="p-10 relative z-10">
            <!-- Logo & Title -->
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary-light text-primary mb-4 shadow-sm border border-red-100">
                    <span class="material-symbols-outlined text-3xl">admin_panel_settings</span>
                </div>
                <h1 class="text-3xl font-black text-text-main tracking-tight font-display mb-1">HER FIELD</h1>
                <p class="text-sm font-semibold text-text-muted tracking-wide uppercase">Admin System Login</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50/80 border border-red-100 rounded-2xl flex items-start gap-3 animate-pulse">
                    <span class="material-symbols-outlined text-red-500 shrink-0">error</span>
                    <div class="text-[13px] font-medium text-red-700 mt-0.5">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-5">
                @csrf

                <!-- Email Input -->
                <div class="space-y-1.5">
                    <label for="email" class="block text-[12px] font-bold text-text-muted uppercase tracking-wider ml-1">Email ID</label>
                    <div class="relative group">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 group-focus-within:text-primary transition-colors text-[20px]">person</span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@herfield.com"
                            class="w-full pl-11 pr-4 py-3.5 bg-gray-50/50 border border-gray-200 rounded-2xl text-[15px] font-medium text-text-main placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm">
                    </div>
                </div>

                <!-- Password Input -->
                <div class="space-y-1.5">
                    <label for="password" class="block text-[12px] font-bold text-text-muted uppercase tracking-wider ml-1">Password</label>
                    <div class="relative group">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 group-focus-within:text-primary transition-colors text-[20px]">lock</span>
                        <input type="password" id="password" name="password" required placeholder="••••••••"
                            class="w-full pl-11 pr-4 py-3.5 bg-gray-50/50 border border-gray-200 rounded-2xl text-[15px] font-medium text-text-main placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm">
                    </div>
                </div>

                <!-- Options -->
                <div class="flex items-center justify-between pt-2">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <div class="relative flex items-center justify-center">
                            <input type="checkbox" name="remember" class="peer appearance-none w-5 h-5 border-2 border-gray-300 rounded-md checked:bg-primary checked:border-primary transition-colors focus:ring-2 focus:ring-primary/20 focus:ring-offset-1">
                            <span class="material-symbols-outlined absolute text-white text-[16px] opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity">check</span>
                        </div>
                        <span class="text-[13px] font-bold text-text-muted group-hover:text-text-main transition-colors">로그인 상태 유지</span>
                    </label>
                    <a href="#" class="text-[13px] font-bold text-primary hover:text-red-700 transition-colors">비밀번호 찾기</a>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" class="w-full relative group overflow-hidden bg-primary text-white font-black py-4 rounded-2xl shadow-[0_8px_16px_-6px_rgba(236,55,19,0.4)] hover:shadow-[0_12px_20px_-6px_rgba(236,55,19,0.5)] transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-primary/30">
                        <span class="relative z-10 flex items-center justify-center gap-2 text-[15px] tracking-wide">
                            로그인
                            <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </span>
                        <div class="absolute inset-0 h-full w-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:animate-[shimmer_1.5s_infinite]"></div>
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Footer -->
        <div class="bg-gray-50/80 py-5 text-center border-t border-gray-100/50">
            <p class="text-[12px] font-bold text-gray-400">&copy; {{ date('Y') }} HER FIELD. All rights reserved.</p>
        </div>
    </div>

</body>
</html>
