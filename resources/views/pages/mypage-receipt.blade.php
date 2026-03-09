@extends('layouts.app')

@section('title', '영수증/계산서 발급 | 마이페이지 - Active Women\'s Premium Store')

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
                <h3 class="text-xl font-bold text-text-main mb-6">영수증/계산서 발급</h3>
                <p class="text-sm text-text-muted mb-6 bg-gray-50 p-4 rounded-xl">신용카드 매출전표, 현금영수증, 세금계산서 발급 내역을 확인하고 인쇄할 수 있습니다.</p>

                <h4 class="text-md font-bold text-primary mb-4 mt-8"><span class="material-symbols-outlined align-middle text-sm mr-1">check_circle</span>[상태 1] 데이터가 있는 경우</h4>
                <div class="overflow-x-auto rounded-xl border border-gray-100 mb-12">
                    <table class="w-full text-left border-collapse min-w-[500px]">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-sm font-bold text-text-main">
                                <th class="py-4 px-6 text-center whitespace-nowrap">발급일자</th>
                                <th class="py-4 px-6 whitespace-nowrap">내용(주문정보)</th>
                                <th class="py-4 px-6 text-center whitespace-nowrap">결제금액</th>
                                <th class="py-4 px-6 text-center whitespace-nowrap">증빙종류</th>
                                <th class="py-4 px-6 text-center whitespace-nowrap">출력</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="hover:bg-gray-50 transition-colors border-b border-gray-100">
                                <td class="py-4 px-6 text-center text-sm font-medium">2026.03.01</td>
                                <td class="py-4 px-6 text-sm">[260301-1234567] 프리미엄 요가 레깅스 3종 세트</td>
                                <td class="py-4 px-6 text-center text-sm font-bold text-text-main">54,000원</td>
                                <td class="py-4 px-6 text-center text-sm">신용카드 매출전표</td>
                                <td class="py-4 px-6 text-center"><button class="px-3 py-1 bg-white border border-gray-300 text-xs font-bold rounded shadow-sm hover:bg-gray-50">인쇄</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h4 class="text-md font-bold text-gray-500 mb-4"><span class="material-symbols-outlined align-middle text-sm mr-1">cancel</span>[상태 2] 데이터가 없는 경우</h4>
                <div class="flex flex-col items-center justify-center py-12 text-center border border-gray-100 rounded-xl bg-gray-50">
                    <span class="material-symbols-outlined text-4xl text-gray-300 mb-3">receipt_long</span>
                    <p class="text-text-muted font-medium">발급 가능한 증빙 서류 내역이 없습니다.</p>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
