@extends('layouts.app')

@section('title', '취소/반품/교환 내역 | 마이페이지 - Active Women\'s Premium Store')

@section('content')
<main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
    <!-- Page Title -->
    <h2 class="text-3xl font-extrabold text-text-main tracking-tight mb-8">마이페이지</h2>

    <div class="flex flex-col lg:flex-row gap-8 items-start">
        <!-- LNB (Left Navigation Bar) -->
        @include('partials.mypage-sidebar')

        <!-- Main Dashboard Content -->
        <div class="flex-1 w-full space-y-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
                <h3 class="text-xl font-bold text-text-main mb-6">취소/반품/교환 내역</h3>
                <p class="text-sm text-text-muted mb-6 bg-gray-50 p-4 rounded-xl">최근 3개월 간의 클레임 내역입니다. 이전 내역은 기간 검색을 이용해주세요.</p>
                
                <h4 class="text-md font-bold text-primary mb-4 mt-8"><span class="material-symbols-outlined align-middle text-sm mr-1">check_circle</span>[상태 1] 데이터가 있는 경우</h4>
                <div class="overflow-x-auto rounded-xl border border-gray-100 mb-12">
                    <table class="w-full text-left border-collapse min-w-[700px]">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-sm font-bold text-text-main">
                                <th class="py-4 px-6 text-center whitespace-nowrap">접수일자</th>
                                <th class="py-4 px-6 whitespace-nowrap">상품정보</th>
                                <th class="py-4 px-6 text-center whitespace-nowrap">클레임 유형</th>
                                <th class="py-4 px-6 text-center whitespace-nowrap">진행상태</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="hover:bg-gray-50 transition-colors border-b border-gray-100">
                                <td class="py-5 px-6 text-center">
                                    <p class="font-bold text-text-main text-sm">2026.02.25</p>
                                </td>
                                <td class="py-5 px-6">
                                    <div class="flex items-center gap-4">
                                        <div class="size-16 bg-gray-100 rounded-lg overflow-hidden shrink-0">
                                            <img src="https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?w=200&h=200&fit=crop" class="w-full h-full object-cover">
                                        </div>
                                        <a href="{{ route('product-detail') }}" class="text-sm font-bold text-text-main hover:text-primary transition-colors line-clamp-1">에어 컴포트 스포츠 브라탑</a>
                                    </div>
                                </td>
                                <td class="py-5 px-6 text-center font-bold text-sm">반품신청</td>
                                <td class="py-5 px-6 text-center">
                                    <span class="inline-flex py-1 px-3 bg-gray-100 text-gray-500 font-bold text-xs rounded-full border border-gray-200">반품처리중</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h4 class="text-md font-bold text-gray-500 mb-4"><span class="material-symbols-outlined align-middle text-sm mr-1">cancel</span>[상태 2] 데이터가 없는 경우</h4>
                <div class="flex flex-col items-center justify-center py-16 text-center border border-gray-100 rounded-xl bg-gray-50">
                    <span class="material-symbols-outlined text-4xl text-gray-300 mb-3">assignment_return</span>
                    <p class="text-text-muted font-medium">취소/반품/교환 신청 내역이 없습니다.</p>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
