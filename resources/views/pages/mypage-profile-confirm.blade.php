@extends('layouts.app')

@section('title', '비밀번호 재확인 | 마이페이지 - Active Women\'s Premium Store')

@section('content')
<main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-text-muted mb-6">
        <a href="/" class="hover:text-primary transition-colors">Home</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <a href="{{ route('mypage') }}" class="hover:text-primary transition-colors">마이페이지</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <span class="font-bold text-text-main">회원정보 수정</span>
    </nav>

    <!-- Page Title -->
    <h2 class="text-3xl font-extrabold text-text-main tracking-tight mb-8">회원정보 수정</h2>

    <div class="flex flex-col lg:flex-row gap-8 items-start">
        <!-- LNB (Left Navigation Bar) -->
        @include('partials.mypage-sidebar')

        <!-- Main Dashboard Content -->
        <div class="flex-1 w-full space-y-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8 lg:p-10 mb-8 max-w-3xl mx-auto">
                <div class="text-center py-10">
                    <div class="size-16 rounded-2xl bg-primary/5 flex items-center justify-center mx-auto mb-6">
                        <span class="material-symbols-outlined text-4xl text-primary">lock</span>
                    </div>
                    <h3 class="text-2xl font-extrabold text-text-main mb-2">비밀번호 재확인</h3>
                    <p class="text-text-muted text-sm leading-relaxed">고객님의 소중한 개인정보를 보호하기 위해<br>비밀번호를 다시 한번 확인합니다. ✨</p>
                </div>
                
                <form id="confirmForm" class="space-y-6 text-left border-t border-gray-50 pt-10">
                    @csrf
                    <div>
                        <label class="block text-xs font-black text-text-muted uppercase mb-2 tracking-tighter">이메일 (아이디)</label>
                        <input type="text" value="{{ Auth::user()->email }}" disabled 
                               class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl text-gray-500 text-sm font-medium focus:ring-0 cursor-not-allowed">
                    </div>
                    <div>
                        <label for="password" class="block text-xs font-black text-text-muted uppercase mb-2 tracking-tighter">비밀번호</label>
                        <input type="password" id="password" name="password" placeholder="비밀번호를 입력해 주세요" 
                               class="w-full h-12 px-4 bg-white border border-gray-200 rounded-xl text-text-main text-sm font-bold focus:border-primary focus:ring-primary/20 transition-all">
                    </div>
                    <button type="submit" class="w-full h-14 bg-text-main text-white font-black rounded-2xl hover:bg-black transition-all shadow-lg shadow-gray-200 active:scale-95 mt-2">확인</button>
                </form>
                
                <div class="mt-10 pt-8 border-t border-gray-100 flex justify-center">
                    <a href="{{ route('mypage.withdraw') }}" class="text-xs text-gray-400 hover:text-primary underline font-bold transition-colors">회원 탈퇴를 원하시나요?</a>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#confirmForm').on('submit', function(e) {
        e.preventDefault();
        const $btn = $(this).find('button[type="submit"]');
        $btn.prop('disabled', true).text('확인 중...');

        $.ajax({
            url: "{{ route('mypage.profile.confirm') }}",
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    showToast('비밀번호가 확인되었습니다! ✨', 'lock_open', 'bg-primary');
                    setTimeout(() => {
                        location.href = response.redirect;
                    }, 800);
                }
            },
            error: function(xhr) {
                const msg = xhr.responseJSON ? xhr.responseJSON.message : '비밀번호가 일치하지 않습니다.';
                showToast(msg, 'error', 'bg-red-500');
                $btn.prop('disabled', false).text('확인');
            }
        });
    });
});
</script>
@endpush
