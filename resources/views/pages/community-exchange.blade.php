@extends('layouts.app')

@section('title', '교환/반품 안내 | 고객센터 - Active Women\'s Premium Store')

@section('content')
<main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex flex-col lg:flex-row gap-12">
        <!-- LEFT SIDEBAR -->
        <aside class="w-full lg:w-64 flex-shrink-0 space-y-8">
            <h2 class="text-3xl font-extrabold text-text-main mb-8 uppercase tracking-tighter">CS Center</h2>
            <nav class="flex flex-col gap-1">
                <a href="{{ route('support') }}" class="px-4 py-3 text-text-muted hover:bg-gray-50 hover:text-text-main rounded-xl font-bold transition-all flex items-center justify-between group">
                    자주 묻는 질문 <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">chevron_right</span>
                </a>
                <a href="{{ route('support.notice') }}" class="px-4 py-3 text-text-muted hover:bg-gray-50 hover:text-text-main rounded-xl font-bold transition-all flex items-center justify-between group">
                    공지사항 <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">chevron_right</span>
                </a>
                <a href="{{ route('support.exchange') }}" class="px-4 py-3 bg-text-main text-white rounded-xl font-bold transition-all shadow-md shadow-black/10 flex items-center justify-between">
                    교환/반품 안내 <span class="material-symbols-outlined text-sm">chevron_right</span>
                </a>
                <a href="{{ route('support.membership') }}" class="px-4 py-3 text-text-muted hover:bg-gray-50 hover:text-text-main rounded-xl font-bold transition-all flex items-center justify-between group">
                    멤버십 혜택 안내 <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">chevron_right</span>
                </a>
            </nav>

            <!-- Contact Panel -->
            <div class="p-6 bg-background-alt rounded-[2rem] border border-gray-100">
                <h3 class="font-bold text-text-main flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-primary">support_agent</span> 고객상담센터
                </h3>
                <p class="text-3xl font-black text-primary mb-1">1544-0000</p>
                <a href="https://pf.kakao.com" target="_blank" class="w-full mt-8 py-4 bg-kakao text-background-dark rounded-2xl font-bold text-sm flex items-center justify-center gap-2 hover:shadow-lg transition-all" style="background-color: #FEE500;">
                    <span class="material-symbols-outlined text-lg">chat_bubble</span> 상담 시작하기
                </a>
            </div>
        </aside>

        <!-- CONTENT -->
        <div class="flex-1 space-y-12">
            <div class="bg-primary-light/20 p-10 rounded-[3rem] mb-12 border border-primary/5">
                <h2 class="text-3xl font-black text-text-main tracking-tight mb-2 uppercase">Exchange & Return </h2>
                <p class="text-text-muted text-sm font-medium">편안한 쇼핑을 위한 교환 및 반품 프로세스를 안내해 드립니다.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-8 bg-white border border-gray-100 rounded-3xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="size-12 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-3xl">done_all</span>
                    </div>
                    <h4 class="text-xl font-bold text-text-main mb-4">교환/반품이 가능한 경우</h4>
                    <ul class="space-y-3 text-sm text-text-muted list-disc pl-5">
                        <li>상품 수령 후 7일 이내 신청 시</li>
                        <li>상품이 표시 내용과 다르거나 하자가 있는 경우</li>
                        <li>단순 변심에 의한 교환/반품 (배송비 고객 부담)</li>
                    </ul>
                </div>
                <div class="p-8 bg-white border border-gray-100 rounded-3xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="size-12 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-3xl">block</span>
                    </div>
                    <h4 class="text-xl font-bold text-text-main mb-4">신청이 불가능한 경우</h4>
                    <ul class="space-y-3 text-sm text-text-muted list-disc pl-5">
                        <li>고객님의 부주의로 상품이 훼손된 경우</li>
                        <li>택(Tag) 제거, 세탁, 수선 등으로 가치가 상실된 경우</li>
                        <li>상품 수령 후 7일이 경과한 경우</li>
                    </ul>
                </div>
            </div>

            <div class="bg-background-alt rounded-[3.5rem] p-12 md:p-16 border border-gray-100">
                <h4 class="text-2xl font-black text-text-main mb-12 text-center underline decoration-primary/30 underline-offset-8">진행 프로세스</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-10">
                    <div class="text-center space-y-4">
                        <div class="size-16 rounded-full bg-white text-primary font-black text-xl flex items-center justify-center mx-auto shadow-lg border border-primary/5">01</div>
                        <p class="font-bold text-text-main">신청 접수</p>
                        <p class="text-xs text-text-muted">마이페이지에서 접수</p>
                    </div>
                    <div class="text-center space-y-4">
                        <div class="size-16 rounded-full bg-white text-primary font-black text-xl flex items-center justify-center mx-auto shadow-lg border border-primary/5">02</div>
                        <p class="font-bold text-text-main">상품 회수</p>
                        <p class="text-xs text-text-muted">기사님 1-3일 내 방문</p>
                    </div>
                    <div class="text-center space-y-4">
                        <div class="size-16 rounded-full bg-white text-primary font-black text-xl flex items-center justify-center mx-auto shadow-lg border border-primary/5">03</div>
                        <p class="font-bold text-text-main">검수 및 승인</p>
                        <p class="text-xs text-text-muted">물류센터 도착 후 확인</p>
                    </div>
                    <div class="text-center space-y-4">
                        <div class="size-16 rounded-full bg-white text-primary font-black text-xl flex items-center justify-center mx-auto shadow-lg border border-primary/5">04</div>
                        <p class="font-bold text-text-main">처리 완료</p>
                        <p class="text-xs text-text-muted">환불 또는 재발송</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
