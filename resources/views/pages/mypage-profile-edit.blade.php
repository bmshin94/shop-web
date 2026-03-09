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
                <div class="border-b border-gray-100 pb-6 mb-8 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-text-main">회원정보 수정</h3>
                    <span class="text-xs text-primary font-bold"><span class="text-primary">*</span> 필수입력</span>
                </div>
                
                <form action="#" method="POST" class="space-y-8">
                    @csrf
                    <!-- Basic Info -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 items-start">
                        <label class="text-sm font-bold text-text-main sm:mt-3">이메일(아이디)</label>
                        <div class="sm:col-span-2">
                            <input type="text" value="s*f*t@gmail.com" disabled class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-500 text-sm focus:ring-0">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 items-start">
                        <label class="text-sm font-bold text-text-main sm:mt-3">이름</label>
                        <div class="sm:col-span-2">
                            <input type="text" value="김에스핏" disabled class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-500 text-sm focus:ring-0">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 items-start border-t border-gray-50 pt-8">
                        <label class="text-sm font-bold text-text-main sm:mt-3">새 비밀번호</label>
                        <div class="sm:col-span-2 space-y-2">
                            <input type="password" placeholder="영문, 숫자, 특수문자 조합 8-16자" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-text-main text-sm focus:ring-primary focus:border-primary">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 items-start">
                        <label class="text-sm font-bold text-text-main sm:mt-3">새 비밀번호 확인</label>
                        <div class="sm:col-span-2">
                            <input type="password" placeholder="비밀번호를 한번 더 입력해주세요" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-text-main text-sm focus:ring-primary focus:border-primary">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 items-start border-t border-gray-50 pt-8">
                        <label class="text-sm font-bold text-text-main sm:mt-3">휴대폰 번호 <span class="text-primary">*</span></label>
                        <div class="sm:col-span-2 flex gap-2">
                            <input type="text" value="010-1234-5678" class="flex-1 bg-white border border-gray-300 rounded-xl px-4 py-3 text-text-main text-sm focus:ring-primary focus:border-primary">
                            <button type="button" class="px-6 py-3 bg-white border border-gray-300 text-text-main font-bold rounded-xl hover:bg-gray-50 transition-colors whitespace-nowrap text-sm">인증변경</button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 items-start border-t border-gray-50 pt-8">
                        <label class="text-sm font-bold text-text-main sm:mt-3">기본 배송지</label>
                        <div class="sm:col-span-2 space-y-3">
                            <div class="flex gap-2">
                                <input type="text" value="06236" placeholder="우편번호" readonly class="w-32 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-600 text-sm focus:ring-0 cursor-default">
                                <button type="button" class="px-6 py-3 bg-white border border-gray-300 text-text-main font-bold rounded-xl hover:bg-gray-50 transition-colors whitespace-nowrap text-sm">우편번호 찾기</button>
                            </div>
                            <input type="text" value="서울특별시 강남구 테헤란로 123" placeholder="기본 주소" readonly class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-600 text-sm focus:ring-0 cursor-default">
                            <input type="text" value="액티브 빌딩 4층" placeholder="상세 주소를 입력해주세요" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-text-main text-sm focus:ring-primary focus:border-primary">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 items-start pt-8 border-t border-gray-50">
                        <div class="sm:mt-1">
                            <label class="text-sm font-bold text-text-main">마케팅 수신동의</label>
                            <p class="text-xs text-text-muted mt-1">다양한 이벤트 및 혜택 안내</p>
                        </div>
                        <div class="sm:col-span-2 flex items-center gap-6 mt-1 sm:mt-2 text-sm">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" checked class="rounded text-primary focus:ring-primary w-5 h-5 border-gray-300">
                                <span class="font-medium text-text-main">SMS</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" checked class="rounded text-primary focus:ring-primary w-5 h-5 border-gray-300">
                                <span class="font-medium text-text-main">이메일</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex gap-4 mt-12">
                        <button type="button" onclick="history.back()" class="flex-1 py-4 bg-white border border-gray-300 text-text-main font-bold rounded-xl hover:bg-gray-50 transition-colors">취소</button>
                        <button type="submit" class="flex-1 py-4 bg-text-main text-white font-bold rounded-xl hover:bg-black transition-colors">수정완료</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
