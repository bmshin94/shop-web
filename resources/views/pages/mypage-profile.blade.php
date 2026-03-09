@extends('layouts.app')

@section('title', '회원정보 수정 | 마이페이지 - Active Women\'s Premium Store')

@section('content')
<main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
    <!-- Page Title -->
    <h2 class="text-3xl font-extrabold text-text-main tracking-tight mb-8">마이페이지</h2>

    <div class="flex flex-col lg:flex-row gap-8 items-start">
        <!-- LNB (Left Navigation Bar) -->
        @include('partials.mypage-sidebar')

        <!-- Main Dashboard Content -->
        <div class="flex-1 w-full space-y-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8 lg:p-12 mb-8 max-w-3xl mx-auto">
                <h4 class="text-md font-bold text-primary mb-8 px-4 py-2 border border-primary/20 bg-primary-light/30 rounded inline-block"><span class="material-symbols-outlined align-middle text-sm mr-1">check_circle</span>비밀번호 재확인</h4>
                
                <div class="text-center mb-10">
                    <span class="material-symbols-outlined text-5xl text-primary mb-4">lock</span>
                    <h3 class="text-2xl font-extrabold text-text-main mb-2">비밀번호 재확인</h3>
                    <p class="text-text-muted text-sm">고객님의 소중한 개인정보를 보호하기 위해 비밀번호를 다시 확인합니다.</p>
                </div>
                
                <div class="space-y-6 text-left">
                    <div>
                        <label class="block text-sm font-bold text-text-main mb-2">이메일(아이디)</label>
                        <input type="text" value="s*f*t@gmail.com" disabled class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-500 text-sm focus:ring-0">
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-bold text-text-main mb-2">비밀번호</label>
                        <input type="password" id="password" placeholder="비밀번호를 입력해주세요" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-text-main text-sm focus:ring-primary focus:border-primary">
                    </div>
                    <button onclick="location.href='{{ route('mypage.profile-edit') }}'" class="w-full py-4 bg-text-main text-white font-bold rounded-xl hover:bg-black transition-colors mt-2">확인</button>
                </div>
                
                <div class="mt-8 pt-8 border-t border-gray-100 flex justify-end">
                    <a href="{{ route('mypage.withdraw') }}" class="text-xs text-gray-400 hover:text-gray-600 underline font-medium">회원 탈퇴를 원하시나요?</a>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
