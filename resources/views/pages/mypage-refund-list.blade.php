@extends('layouts.app')

@section('title', '환불/입금 내역 | 마이페이지 - Active Women\'s Premium Store')

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
                <h3 class="text-xl font-bold text-text-main mb-6">환불/입금 내역</h3>
                
                <h4 class="text-md font-bold text-primary mb-4 mt-8"><span class="material-symbols-outlined align-middle text-sm mr-1">check_circle</span>[상태 1] 데이터가 있는 경우</h4>
                <div class="overflow-x-auto rounded-xl border border-gray-100 mb-12">
                    <table class="w-full text-left border-collapse min-w-[500px]">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-sm font-bold text-text-main">
                                <th class="py-4 px-6 text-center whitespace-nowrap">일자</th>
                                <th class="py-4 px-6 text-center whitespace-nowrap">유형</th>
                                <th class="py-4 px-6 whitespace-nowrap">내용</th>
                                <th class="py-4 px-6 text-center whitespace-nowrap">금액</th>
                                <th class="py-4 px-6 text-center whitespace-nowrap">상태</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <td class="py-5 px-6 text-center text-sm font-medium">2026.02.26</td>
                                <td class="py-5 px-6 text-center text-sm font-bold">환불</td>
                                <td class="py-5 px-6 text-sm">에어 컴포트 스포츠 브라탑 반품에 따른 환불</td>
                                <td class="py-5 px-6 text-center text-sm font-extrabold text-primary">78,000원</td>
                                <td class="py-5 px-6 text-center"><span class="inline-flex py-1 px-3 bg-gray-100 text-gray-500 font-bold text-xs rounded-full border border-gray-200">환불완료</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h4 class="text-md font-bold text-gray-500 mb-4"><span class="material-symbols-outlined align-middle text-sm mr-1">cancel</span>[상태 2] 데이터가 없는 경우</h4>
                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="w-full text-left border-collapse min-w-[500px]">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-sm font-bold text-text-main">
                                <th class="py-4 px-6 text-center whitespace-nowrap">접수일자</th>
                                <th class="py-4 px-6 text-center whitespace-nowrap">유형</th>
                                <th class="py-4 px-6 whitespace-nowrap">내용</th>
                                <th class="py-4 px-6 text-center whitespace-nowrap">금액</th>
                                <th class="py-4 px-6 text-center whitespace-nowrap">상태</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5" class="py-12 text-center text-text-muted font-medium bg-gray-50">해당하는 환불/입금 내역이 존재하지 않습니다.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
