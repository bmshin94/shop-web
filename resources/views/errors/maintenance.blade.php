<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>서비스 점검 중 | Active Women</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pretendard:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        body { font-family: 'Pretendard', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-6">
    <div class="max-w-lg w-full text-center space-y-8">
        <!-- Logo/Icon -->
        <div class="relative inline-block">
            <div class="size-24 rounded-[32px] bg-white shadow-2xl flex items-center justify-center mx-auto text-primary animate-bounce-subtle">
                <span class="material-symbols-outlined text-5xl">construction</span>
            </div>
            <div class="absolute -top-2 -right-2 size-8 rounded-full bg-red-500 text-white flex items-center justify-center shadow-lg">
                <span class="material-symbols-outlined text-sm">priority_high</span>
            </div>
        </div>

        <div class="space-y-4">
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">서비스 점검 중입니다</h1>
            <p class="text-slate-500 font-medium leading-relaxed">
                더 나은 서비스를 위해 현재 시스템 정기 점검을 진행하고 있습니다.<br>
                이용에 불편을 드려 죄송합니다. 잠시만 기다려 주세요!
            </p>
        </div>

        <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 space-y-6">
            <div class="grid grid-cols-1 gap-4 text-left">
                <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl">
                    <span class="material-symbols-outlined text-primary">schedule</span>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">점검 시간</p>
                        <p class="text-sm font-black text-slate-700">작업 완료 시까지</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl">
                    <span class="material-symbols-outlined text-primary">support_agent</span>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">고객센터</p>
                        <p class="text-sm font-black text-slate-700">support@activewomen.example</p>
                    </div>
                </div>
            </div>
            
            <button onclick="location.reload()" class="w-full py-4 bg-slate-900 text-white font-black rounded-2xl hover:bg-black transition-all shadow-xl active:scale-95">
                페이지 새로고침
            </button>
        </div>

        <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">© Active Women. All rights reserved.</p>
    </div>

    <style>
        @keyframes bounce-subtle {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-bounce-subtle {
            animation: bounce-subtle 2s infinite ease-in-out;
        }
    </style>
</body>
</html>
