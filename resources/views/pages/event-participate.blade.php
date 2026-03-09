@extends('layouts.app')

@section('title', '이벤트 참여하기 | Active Women\'s Premium Store')

@section('content')
<main class="flex-1 w-full max-w-3xl mx-auto px-4 py-12 lg:py-20">
    <!-- Event Summary -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="bg-primary-light px-8 py-6">
            <span class="inline-block px-2.5 py-1 bg-primary text-white text-[10px] font-bold rounded mb-2">진행중인 이벤트</span>
            <h2 class="text-2xl font-extrabold text-text-main leading-tight">신규 회원 가입 웰컴 팩 : 1만원 쿠폰 무조건 증정</h2>
        </div>
        <div class="p-8">
            <div class="flex items-center gap-6 text-sm text-text-muted">
                <div class="flex items-center gap-1.5"><span class="material-symbols-outlined text-lg">calendar_today</span> 기간: 2026.03.01 - 2026.03.31</div>
                <div class="flex items-center gap-1.5"><span class="material-symbols-outlined text-lg">person</span> 대상: 모든 신규 가입 고객</div>
            </div>
        </div>
    </div>

    <!-- Participation Form -->
    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 md:p-12">
        <h3 class="text-xl font-bold text-text-main mb-8 flex items-center gap-2">
            <span class="size-2 bg-primary rounded-full"></span> 참여 정보 입력
        </h3>
        
        <form id="participateForm" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-text-main mb-2">성함 <span class="text-primary">*</span></label>
                    <input type="text" placeholder="김에스핏" required class="w-full rounded-xl border-gray-200 focus:border-primary focus:ring-primary transition-all py-3">
                </div>
                <div>
                    <label class="block text-sm font-bold text-text-main mb-2">연락처 <span class="text-primary">*</span></label>
                    <input type="tel" placeholder="010-0000-0000" required class="w-full rounded-xl border-gray-200 focus:border-primary focus:ring-primary transition-all py-3">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-text-main mb-2">이메일 <span class="text-primary">*</span></label>
                <input type="email" placeholder="active@example.com" required class="w-full rounded-xl border-gray-200 focus:border-primary focus:ring-primary transition-all py-3">
            </div>

            <div>
                <label class="block text-sm font-bold text-text-main mb-2">참여 메시지 / 기대평 (선택)</label>
                <textarea rows="5" placeholder="이벤트에 대한 기대평이나 참여 사연을 자유롭게 남겨주세요!" class="w-full rounded-xl border-gray-200 focus:border-primary focus:ring-primary transition-all resize-none"></textarea>
            </div>

            <!-- Terms -->
            <div class="pt-4 space-y-3">
                <label class="flex items-start gap-3 cursor-pointer group">
                    <input type="checkbox" required class="mt-1 rounded text-primary focus:ring-primary border-gray-300">
                    <span class="text-sm text-text-muted leading-relaxed group-hover:text-text-main transition-colors">
                        [필수] 개인정보 수집 및 이용 동의
                    </span>
                </label>
                <label class="flex items-start gap-3 cursor-pointer group">
                    <input type="checkbox" class="mt-1 rounded text-primary focus:ring-primary border-gray-300">
                    <span class="text-sm text-text-muted leading-relaxed group-hover:text-text-main transition-colors">
                        [선택] 마케팅 활용 및 광고성 정보 수신 동의
                    </span>
                </label>
            </div>

            <div class="pt-8 flex gap-4">
                <a href="{{ route('event') }}" class="flex-1 text-center py-4 bg-gray-100 text-text-main font-bold rounded-2xl hover:bg-gray-200 transition-colors">취소</a>
                <button type="submit" class="flex-[2] py-4 bg-primary text-white font-bold rounded-2xl hover:bg-red-600 transition-all shadow-lg shadow-primary/20 transform hover:-translate-y-1">이벤트 신청하기</button>
            </div>
        </form>
    </div>
</main>

<!-- Success Toast -->
<div id="successToast" class="fixed bottom-10 left-1/2 -translate-x-1/2 z-[100] hidden items-center gap-2 bg-text-main text-white px-8 py-4 rounded-full shadow-2xl">
    <span class="material-symbols-outlined text-green-400">check_circle</span>
    <span class="font-bold">이벤트 신청이 완료되었습니다! 행운을 빌어요 </span>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('participateForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const toast = document.getElementById('successToast');
        toast.classList.remove('hidden');
        toast.classList.add('flex');
        
        setTimeout(() => {
            window.location.href = "{{ route('event') }}";
        }, 3000);
    });
</script>
@endpush
