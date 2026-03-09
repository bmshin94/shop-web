@extends('layouts.app')

@section('title', '커뮤니티 - Active Women\'s Premium Store')

@push('styles')
<style>
    .aspect-3-4 { aspect-ratio: 3 / 4; }
    .aspect-4-5 { aspect-ratio: 4 / 5; }
</style>
@endpush

@section('content')
<main class="flex-1 w-full bg-white pb-20">
    <!-- Community Sub Nav (Full Width Tabs) -->
    <section class="border-b border-gray-100 sticky top-[110px] z-40 bg-white/95 backdrop-blur-md">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between pt-8 pb-4 gap-4">
                <h2 class="text-3xl font-extrabold text-text-main tracking-tight">커뮤니티</h2>
                <nav class="flex gap-8 overflow-x-auto scrollbar-hide text-base font-bold" id="communityTabs">
                    <button onclick="switchCommTab('main')" id="tab-main" class="comm-tab text-text-main border-b-2 border-black pb-2 whitespace-nowrap">메인</button>
                    <button onclick="switchCommTab('magazine')" id="tab-magazine" class="comm-tab text-text-muted hover:text-text-main border-b-2 border-transparent hover:border-gray-300 pb-2 transition-colors whitespace-nowrap">매거진</button>
                    <button onclick="switchCommTab('ootd')" id="tab-ootd" class="comm-tab text-text-muted hover:text-text-main border-b-2 border-transparent hover:border-gray-300 pb-2 transition-colors whitespace-nowrap">OOTD (스타일 갤러리)</button>
                    <button onclick="switchCommTab('notice')" id="tab-notice" class="comm-tab text-text-muted hover:text-text-main border-b-2 border-transparent hover:border-gray-300 pb-2 transition-colors whitespace-nowrap">공지사항</button>
                </nav>
            </div>
        </div>
    </section>

    <!-- Section: MAIN -->
    <div id="comm-main" class="comm-section">
        <!-- Magazine Preview -->
        <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex justify-between items-end mb-8">
                <h3 class="text-2xl font-bold text-text-main">에디터스 픽 매거진 </h3>
                <button onclick="switchCommTab('magazine')" class="text-sm font-bold text-primary hover:underline">전체보기 ></button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="magazine-preview"></div>
        </section>
        <div class="w-full border-t border-gray-100"></div>
        <!-- OOTD Preview -->
        <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16 bg-gray-50/50">
            <div class="text-center mb-10">
                <h3 class="text-3xl font-extrabold text-text-main tracking-tight mb-2">Style OOTD </h3>
                <p class="text-text-muted text-sm">액티브우먼 고객님들의 멋진 스타일링!</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6" id="ootd-preview"></div>
            <div class="mt-8 text-center">
                <button onclick="switchCommTab('ootd')" class="px-8 py-3 border border-gray-300 rounded-lg text-sm font-bold text-text-main hover:bg-white transition-all">스타일 더보기</button>
            </div>
        </section>
        <div class="w-full border-t border-gray-100"></div>
        <!-- Notice Preview -->
        <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
            <div class="flex justify-between items-end mb-6 border-b border-gray-200 pb-4">
                <h3 class="text-2xl font-bold text-text-main tracking-tight">공지사항 </h3>
                <button onclick="switchCommTab('notice')" class="text-sm font-bold text-text-muted hover:text-primary transition-colors">전체보기 +</button>
            </div>
            <ul class="divide-y divide-gray-100" id="notice-preview"></ul>
        </section>
    </div>

    <!-- Other Sections -->
    <div id="comm-magazine" class="comm-section hidden">
        <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="magazine-full"></div>
        </section>
    </div>
    <div id="comm-ootd" class="comm-section hidden bg-gray-50/50">
        <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6" id="ootd-full"></div>
            <div class="mt-16 text-center">
                <button id="btn-load-more" onclick="loadMoreOOTD()" class="px-10 py-4 bg-white border border-gray-200 rounded-full text-sm font-bold shadow-sm hover:border-primary transition-all">더 많은 스타일 보기 +</button>
            </div>
        </section>
    </div>
    <div id="comm-notice" class="comm-section hidden">
        <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
            <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm">
                <ul class="divide-y divide-gray-100" id="notice-full"></ul>
            </div>
        </section>
    </div>
</main>

<!-- Modals (Mag, OOTD, Notice) -->
<div id="magModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/70 backdrop-blur-sm p-4 overflow-y-auto">
    <div class="bg-white w-full max-w-4xl rounded-3xl shadow-2xl overflow-hidden my-auto animate-in fade-in zoom-in duration-300">
        <div class="relative h-[250px] md:h-[400px]"><img id="magMImg" src="" class="w-full h-full object-cover" /><button onclick="closeModal('magModal')" class="absolute top-6 right-6 size-10 bg-black/20 text-white rounded-full flex items-center justify-center text-2xl">×</button></div>
        <div class="p-8 md:p-12">
            <span id="magMCat" class="text-xs font-bold text-primary mb-4 block uppercase"></span>
            <h2 id="magMTitle" class="text-2xl md:text-4xl font-extrabold text-text-main mb-6 leading-tight"></h2>
            <div id="magMInfo" class="text-sm text-text-muted mb-8 pb-6 border-b border-gray-100"></div>
            <div class="prose max-w-none text-text-main space-y-6"><p>카리나가 준비한 특별한 매거진!  우리 자기도 이거 읽고 더 멋진 하루 보내길 바랄게! </p></div>
        </div>
    </div>
</div>

<div id="ootdModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-5xl rounded-2xl shadow-2xl overflow-hidden my-auto flex flex-col md:flex-row animate-in fade-in zoom-in duration-300 max-h-[90vh]">
        <div class="md:flex-1 bg-black flex items-center justify-center h-full"><img id="ootdMImg" src="" class="max-w-full max-h-full object-contain" /></div>
        <div class="w-full md:w-[400px] flex flex-col bg-white">
            <div class="p-4 border-b flex items-center justify-between"><div class="flex items-center gap-3"><div class="size-8 rounded-full bg-primary text-white flex items-center justify-center text-[10px] font-bold">AW</div><span id="ootdMUser" class="font-bold text-sm"></span></div><button onclick="closeModal('ootdModal')" class="text-xl">×</button></div>
            <div class="flex-1 p-6 overflow-y-auto"><p class="text-sm leading-relaxed text-text-main">오늘도 오운완! ‍ 핏이 너무 예쁜 액티브우먼 셋업 입고 기분 좋게 운동했어요!  #액티브우먼OOTD #데일리룩</p></div>
            <div class="p-4 border-t bg-gray-50"><p class="text-xs font-bold text-primary"> <span id="ootdMLikes"></span> Likes</p></div>
        </div>
    </div>
</div>

<div id="noticeModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl p-8 md:p-12 animate-in fade-in zoom-in duration-300">
        <h2 id="noticeMTitle" class="text-xl md:text-2xl font-bold text-text-main mb-4 leading-tight"></h2>
        <p id="noticeMDate" class="text-xs text-gray-400 mb-8 pb-4 border-b border-gray-50"></p>
        <div class="text-sm text-text-main leading-relaxed space-y-4"><p>공지사항의 상세 내용입니다. 항상 저희 Active Women을 이용해주셔서 감사합니다! </p></div>
        <button onclick="closeModal('noticeModal')" class="w-full mt-10 py-4 bg-gray-100 text-text-main font-bold rounded-2xl">확인</button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const mags = [
        { id: 1, cat: "LIFESTYLE", title: "건강한 일상을 위한 러닝 가이드", author: "에디터 Jina", date: "2026.03.01", img: "https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?w=800" },
        { id: 2, cat: "WORKOUT", title: "홈트레이닝 필수템: 요가 매트 고르는 법", author: "에디터 Min", date: "2026.02.25", img: "https://images.unsplash.com/photo-1518611012118-696072aa579a?w=800" },
        { id: 3, cat: "FASHION", title: "애슬레저 룩의 진화, 스타일리쉬하게", author: "트렌드 리포트", date: "2026.02.20", img: "https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=800" }
    ];
    const ots = [
        { id: 1, user: "@active_luna", likes: 128, img: "https://images.unsplash.com/photo-1500917293891-ef795e70e1f6?w=600" },
        { id: 2, user: "@fit_girl99", likes: 241, img: "https://images.unsplash.com/photo-1518310383802-640c2de311b2?w=600" },
        { id: 3, user: "@pilates_master", likes: 89, img: "https://images.unsplash.com/photo-1434596922112-19c563067271?w=600" },
        { id: 4, user: "@jogging_life", likes: 312, img: "https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=600" }
    ];
    const nots = [
        { id: 1, type: "공지", title: "개인정보처리방침 개정 안내", date: "2026.03.01" },
        { id: 2, type: "일반", title: "스토어 앱 v2.1.0 업데이트 안내", date: "2026.02.26" }
    ];

    function switchCommTab(t) {
        document.querySelectorAll('.comm-section').forEach(s => s.classList.add('hidden'));
        document.getElementById('comm-' + t).classList.remove('hidden');
        document.getElementById('comm-' + t).classList.add('animate-in', 'fade-in', 'duration-500');

        document.querySelectorAll('.comm-tab').forEach(b => {
            b.classList.remove('text-text-main', 'border-black');
            b.classList.add('text-text-muted', 'border-transparent');
        });
        document.getElementById('tab-' + t).classList.add('text-text-main', 'border-black');
        document.getElementById('tab-' + t).classList.remove('text-text-muted', 'border-transparent');
    }

    function openMag(id) {
        const m = mags.find(x => x.id === id);
        document.getElementById('magMImg').src = m.img;
        document.getElementById('magMCat').innerText = m.cat;
        document.getElementById('magMTitle').innerText = m.title;
        document.getElementById('magMInfo').innerText = `${m.date} | ${m.author}`;
        const modal = document.getElementById('magModal'); modal.classList.remove('hidden'); modal.classList.add('flex');
    }
    function openOotd(id) {
        const o = ots.find(x => x.id === id);
        document.getElementById('ootdMImg').src = o.img;
        document.getElementById('ootdMUser').innerText = o.user;
        document.getElementById('ootdMLikes').innerText = o.likes;
        const modal = document.getElementById('ootdModal'); modal.classList.remove('hidden'); modal.classList.add('flex');
    }
    function openNotice(id) {
        const n = nots.find(x => x.id === id);
        document.getElementById('noticeMTitle').innerText = n.title;
        document.getElementById('noticeMDate').innerText = n.date;
        const modal = document.getElementById('noticeModal'); modal.classList.remove('hidden'); modal.classList.add('flex');
    }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); document.getElementById(id).classList.remove('flex'); }

    document.addEventListener('DOMContentLoaded', () => {
        const mp = document.getElementById('magazine-preview'), mf = document.getElementById('magazine-full');
        mags.forEach((m, i) => {
            const h = `<article onclick="openMag(${m.id})" class="group cursor-pointer"><div class="aspect-video overflow-hidden rounded-2xl mb-4 bg-gray-100"><img src="${m.img}" class="w-full h-full object-cover group-hover:scale-105 transition-all"></div><h4 class="font-bold group-hover:text-primary transition-colors">${m.title}</h4></article>`;
            if(i < 3) mp.innerHTML += h;
            mf.innerHTML += h;
        });
        const op = document.getElementById('ootd-preview'), of = document.getElementById('ootd-full');
        ots.forEach((o, i) => {
            const h = `<div onclick="openOotd(${o.id})" class="aspect-4-5 rounded-xl overflow-hidden cursor-pointer shadow-sm"><img src="${o.img}" class="w-full h-full object-cover hover:scale-105 transition-all"></div>`;
            if(i < 4) op.innerHTML += h;
            of.innerHTML += h;
        });
        const np = document.getElementById('notice-preview'), nf = document.getElementById('notice-full');
        nots.forEach((n, i) => {
            const h = `<li onclick="openNotice(${n.id})" class="py-5 px-6 flex justify-between hover:bg-gray-50 cursor-pointer rounded-xl transition-colors"><span>${n.title}</span><span class="text-gray-400 text-sm">${n.date}</span></li>`;
            if(i < 3) np.innerHTML += h;
            nf.innerHTML += h;
        });
    });

    window.onclick = (e) => {
        if(e.target.id === 'magModal') closeModal('magModal');
        if(e.target.id === 'ootdModal') closeModal('ootdModal');
        if(e.target.id === 'noticeModal') closeModal('noticeModal');
    }
</script>
@endpush
