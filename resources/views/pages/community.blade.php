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
                <p class="text-text-muted text-sm mb-6">액티브우먼 고객님들의 멋진 스타일링!</p>
                <div class="flex justify-center">
                    <button onclick="openOotdCreateModal()" class="inline-flex items-center gap-2 px-8 py-4 bg-primary text-white font-bold rounded-full shadow-lg shadow-primary/20 hover:scale-105 active:scale-95 transition-all text-sm">
                        <span class="material-symbols-outlined text-[20px]">photo_camera</span>
                        스타일 공유하기
                    </button>
                </div>
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
            <div class="mt-16 text-center">
                <button id="btn-load-more-mag" onclick="loadMoreMagazines()" class="px-10 py-4 bg-white border border-gray-200 rounded-full text-sm font-bold shadow-sm hover:border-primary transition-all {{ $hasMoreMag ? '' : 'hidden' }}">더 많은 매거진 보기 +</button>
            </div>
        </section>
    </div>
    <div id="comm-ootd" class="comm-section hidden bg-gray-50/50">
        <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex justify-center mb-12">
                <button onclick="openOotdCreateModal()" class="inline-flex items-center gap-2 px-10 py-5 bg-primary text-white font-bold rounded-full shadow-xl shadow-primary/20 hover:scale-105 active:scale-95 transition-all text-base">
                    <span class="material-symbols-outlined text-[24px]">photo_camera</span>
                    스타일 공유하기
                </button>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6" id="ootd-full"></div>
            <div class="mt-16 text-center">
                <button id="btn-load-more" onclick="loadMoreOOTD()" class="px-10 py-4 bg-white border border-gray-200 rounded-full text-sm font-bold shadow-sm hover:border-primary transition-all {{ $hasMoreOotd ? '' : 'hidden' }}">더 많은 스타일 보기 +</button>
            </div>
        </section>
    </div>
    <div id="comm-notice" class="comm-section hidden">
        <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
            <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm">
                <ul class="divide-y divide-gray-100" id="notice-full"></ul>
            </div>
            <div class="mt-12 text-center">
                <button id="btn-load-more-notice" onclick="loadMoreNotices()" class="px-10 py-4 bg-white border border-gray-200 rounded-full text-sm font-bold shadow-sm hover:border-primary transition-all {{ $hasMoreNotice ? '' : 'hidden' }}">더 많은 공지 보기 +</button>
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
            <div class="prose max-w-none text-text-main space-y-6"></div>
        </div>
    </div>
</div>

<div id="ootdModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-6">
    <!-- 모달 배경 (클릭 시 닫기 용도) -->
    <div class="absolute inset-0 bg-black/80 backdrop-blur-md transition-opacity" onclick="closeModal('ootdModal')"></div>
    
    <!-- 모달 본체 -->
    <div class="relative w-full max-w-6xl max-h-[90vh] bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row animate-in fade-in zoom-in-95 duration-300">
        
        <!-- 닫기 버튼 (모바일에서는 이미지 위, 데스크탑에서는 우측 패널 상단) -->
        <button onclick="closeModal('ootdModal')" class="absolute top-4 right-4 z-50 p-2 bg-black/20 hover:bg-black/40 text-white rounded-full backdrop-blur-sm transition-colors md:hidden">
            <span class="material-symbols-outlined text-[20px]">close</span>
        </button>

        {{-- 1. 왼쪽 이미지 영역 (최대 높이에 맞춰 이미지 꽉 차게) --}}
        <div class="md:w-3/5 lg:w-2/3 bg-black flex items-center justify-center relative min-h-[40vh] md:min-h-0">
            <img id="ootdMImg" src="" class="w-full h-full object-contain max-h-[90vh]" alt="OOTD Style Image" />
        </div>
        
        {{-- 2. 오른쪽 정보 패널 --}}
        <div class="w-full md:w-2/5 lg:w-1/3 flex flex-col bg-white overflow-hidden max-h-[50vh] md:max-h-full">
            
            {{-- 글쓴이 정보 영역 (헤더) --}}
            <div class="flex items-center justify-between p-4 border-b border-gray-100 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="size-10 rounded-full bg-gradient-to-tr from-primary to-orange-400 p-[2px]">
                        <div class="size-full rounded-full bg-white flex items-center justify-center border border-white">
                            <span class="text-[12px] font-black text-primary leading-none mt-0.5">AW</span>
                        </div>
                    </div>
                    <div>
                        <span id="ootdMUser" class="font-bold text-sm text-text-main block"></span>
                        <span class="text-[11px] text-gray-400 font-medium">Style Creator</span>
                    </div>
                </div>
                
                <!-- 데스크탑용 닫기 버튼 -->
                <button onclick="closeModal('ootdModal')" class="hidden md:flex p-2 text-gray-400 hover:text-text-main transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            {{-- 내용 영역 (스크롤) --}}
            <div class="flex-1 overflow-y-auto custom-scrollbar p-6">
                <!-- 본문 텍스트 -->
                <p class="text-[14px] leading-relaxed text-text-main whitespace-pre-wrap font-medium" id="ootdMContent"></p>
                
                <!-- 날짜 -->
                <p class="text-[11px] text-gray-400 mt-6 font-medium" id="ootdMDate"></p>
            </div>
            
            {{-- 본인 게시물 수정/삭제 버튼 --}}
            <div id="ootdMAdmin" class="px-5 py-3 border-t border-gray-100 hidden bg-gray-50/50 flex-col gap-2 shrink-0">
                <p class="text-[11px] font-bold text-gray-400 text-center mb-1">내 게시물 관리</p>
                <div class="flex gap-2">
                    <a href="" id="ootdEditBtn" class="flex-1 py-2.5 bg-white border border-gray-200 text-text-main text-xs font-bold rounded-xl text-center hover:border-primary hover:text-primary transition-all shadow-sm">수정하기</a>
                    <button onclick="" id="ootdDeleteBtn" class="flex-1 py-2.5 bg-rose-50 border border-rose-100 text-rose-500 text-xs font-bold rounded-xl hover:bg-rose-100 transition-all shadow-sm">삭제하기</button>
                </div>
            </div>

            {{-- 인스타그램 연동 버튼 --}}
            <div id="ootdMInsta" class="px-5 py-4 border-t border-gray-100 hidden shrink-0">
                <a href="" target="_blank" class="flex items-center justify-center gap-2 text-sm font-bold text-white bg-gradient-to-r from-purple-500 via-pink-500 to-orange-500 px-4 py-3.5 rounded-2xl hover:scale-[1.02] active:scale-95 transition-all shadow-md">
                    <i class="fa-brands fa-instagram text-[18px]"></i>
                    인스타그램에서 스타일 더보기
                </a>
            </div>
            
            {{-- 하단 액션바 (좋아요) --}}
            <div class="p-4 border-t border-gray-100 bg-white shrink-0 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button id="ootdLikeBtn" onclick="" class="group relative flex items-center justify-center size-12 bg-gray-50 rounded-full hover:bg-rose-50 transition-colors focus:outline-none focus:ring-2 focus:ring-rose-200">
                        <span class="material-symbols-outlined text-[24px] text-gray-400 group-hover:text-rose-500 transition-colors z-10" id="ootdHeartIcon">favorite</span>
                        <!-- 하트 애니메이션 효과용 더미 요소 -->
                        <span class="absolute inset-0 rounded-full border-2 border-rose-400 scale-0 opacity-0 transition-all duration-300 pointer-events-none" id="ootdHeartRipple"></span>
                    </button>
                    <div class="flex flex-col">
                        <span class="text-[11px] font-bold text-gray-400 transform translate-y-0.5">Likes</span>
                        <span class="text-sm font-black text-text-main" id="ootdMLikes">0</span>
                    </div>
                </div>
                
                <div class="hidden">
                    <span class="text-xs font-bold text-text-main" id="ootdLikeStatus">좋아요</span>
                </div>
                
                <button class="size-10 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 hover:text-text-main hover:bg-gray-100 transition-colors">
                    <span class="material-symbols-outlined text-[20px]">share</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div id="noticeModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl p-8 md:p-12 animate-in fade-in zoom-in duration-300">
        <h2 id="noticeMTitle" class="text-xl md:text-2xl font-bold text-text-main mb-4 leading-tight"></h2>
        <p id="noticeMDate" class="text-xs text-gray-400 mb-8 pb-4 border-b border-gray-50"></p>
        <div class="text-sm text-text-main leading-relaxed space-y-4 whitespace-pre-wrap"></div>
        <button onclick="closeModal('noticeModal')" class="w-full mt-10 py-4 bg-gray-100 text-text-main font-bold rounded-2xl">확인</button>
    </div>
</div>

<!-- OOTD Create Modal ✨💖 -->
<div id="ootdCreateModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/70 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden my-auto animate-in fade-in zoom-in duration-300 flex flex-col max-h-[90vh]">
        <div class="p-6 border-b flex items-center justify-between bg-white shrink-0">
            <h2 class="text-xl font-bold text-text-main">Style OOTD 등록</h2>
            <button onclick="closeModal('ootdCreateModal')" class="text-2xl">×</button>
        </div>
        <div class="overflow-y-auto flex-1 custom-scrollbar">
            <form id="ootdCreateForm" onsubmit="submitOotd(event)" class="p-8 space-y-6">
                <!-- Image Drop Zone - Size Reduced ✨ -->
                <div id="drop-zone" class="relative group">
                    <input type="file" id="image_file" accept="image/*" class="hidden" onchange="previewOotdImage(this)">
                    <label for="image_file" class="flex flex-col items-center justify-center w-full aspect-video bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer hover:border-primary/50 hover:bg-primary/5 transition-all overflow-hidden relative">
                        <div id="upload-placeholder" class="flex flex-col items-center justify-center space-y-2 text-center px-4">
                            <span class="material-symbols-outlined text-[32px] text-gray-300">add_photo_alternate</span>
                            <span class="text-xs font-bold text-gray-400 leading-tight">사진을 선택하거나<br>여기로 드래그하세요.</span>
                        </div>
                        <img id="ootd-preview-img" src="" class="hidden w-full h-full object-cover">
                        <div id="drag-overlay" class="absolute inset-0 bg-primary/10 backdrop-blur-[2px] hidden items-center justify-center border-4 border-primary border-dashed rounded-2xl animate-pulse">
                            <span class="text-primary font-bold text-sm">파일을 내려놓으세요.</span>
                        </div>
                    </label>
                </div>
                
                <div class="space-y-5">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-text-main ml-1">스타일 설명 <span class="text-primary">*</span></label>
                        <textarea id="ootdContent" rows="4" placeholder="스타일링에 대한 설명을 입력하세요." class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-primary/20 transition-all outline-none resize-none"></textarea>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-text-main ml-1">인스타그램 게시물 링크 <span class="text-text-muted font-normal">(선택)</span></label>
                        <div class="relative">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-primary font-bold text-xs">@</span>
                            <input type="url" id="ootdInsta" placeholder="https://www.instagram.com/p/..." class="w-full pl-10 pr-5 py-4 bg-gray-50 border-none rounded-2xl text-xs focus:ring-2 focus:ring-primary/20 transition-all outline-none">
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" id="ootdSubmitBtn" class="w-full py-4 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all text-base">등록하기</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const mags = @json($magazines);
    const ots = @json($ootds);
    const nots = @json($notices);

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
        // 상세 내용 업데이트
        document.querySelector('#magModal .prose').innerHTML = `<p>${m.content || '상세 내용이 없습니다.'}</p>`;
        const modal = document.getElementById('magModal'); modal.classList.remove('hidden'); modal.classList.add('flex');
    }
    function openOotd(id) {
        const o = ots.find(x => x.id === id);
        const modal = document.getElementById('ootdModal');
        
        // 이미지 할당
        document.getElementById('ootdMImg').src = o.img;
        
        // 텍스트 데이터 바인딩
        document.getElementById('ootdMUser').innerText = o.user;
        document.getElementById('ootdMLikes').innerText = o.likes;
        document.getElementById('ootdMContent').innerText = o.content || '내용이 없습니다.';
        
        // 날짜 (데이터에 있다면 표시, 없으면 임의 처리)
        const dateEl = document.getElementById('ootdMDate');
        if (dateEl) dateEl.innerText = o.date || '최근 등록됨';
        
        // 인스타그램 링크 처리
        const instaDiv = document.getElementById('ootdMInsta');
        const instaLink = instaDiv.querySelector('a');
        if (o.insta) {
            instaLink.href = o.insta;
            instaDiv.classList.remove('hidden');
        } else {
            instaDiv.classList.add('hidden');
        }

        // 본인 게시물 화면 처리 (수정/삭제)
        const adminDiv = document.getElementById('ootdMAdmin');
        if (o.is_mine) {
            document.getElementById('ootdEditBtn').href = `/community/ootd/${o.id}/edit`;
            document.getElementById('ootdDeleteBtn').onclick = () => deleteOotd(o.id);
            adminDiv.classList.remove('hidden');
            adminDiv.classList.add('flex');
        } else {
            adminDiv.classList.remove('flex');
            adminDiv.classList.add('hidden');
        }

        // 좋아요 버튼 상태 초기화 및 바인딩
        updateLikeUI(o.id, o.liked);
        document.getElementById('ootdLikeBtn').onclick = () => toggleOotdLike(o.id);
        
        // 모달 열기 애니메이션 처리
        modal.classList.remove('hidden'); 
        modal.classList.add('flex');
    }

    function updateLikeUI(id, liked) {
        const icon = document.getElementById('ootdHeartIcon');
        const status = document.getElementById('ootdLikeStatus');
        const btn = document.getElementById('ootdLikeBtn');
        const ripple = document.getElementById('ootdHeartRipple');
        
        if (liked) {
            icon.classList.add('text-rose-500');
            icon.classList.remove('text-gray-400');
            icon.style.fontVariationSettings = "'FILL' 1";
            status.innerText = '좋아요 취소';
            
            // 귀여운 하트 리플 효과 💖 (하트 찍을 때만)
            if (ripple) {
                ripple.classList.remove('scale-0', 'opacity-0');
                ripple.classList.add('scale-150', 'opacity-0');
                setTimeout(() => {
                    ripple.classList.replace('scale-150', 'scale-0');
                }, 300);
            }
        } else {
            icon.classList.remove('text-rose-500');
            icon.classList.add('text-gray-400');
            icon.style.fontVariationSettings = "'FILL' 0";
            status.innerText = '좋아요';
        }
    }

    async function toggleOotdLike(id) {
        @guest
            alert('로그인한 회원만 이용 가능합니다.');
            return;
        @endguest

        try {
            const res = await fetch(`/community/ootd/${id}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            const data = await res.json();
            
            if (res.ok) {
                const o = ots.find(x => x.id === id);
                o.liked = data.liked;
                o.likes = data.likes_count;
                
                document.getElementById('ootdMLikes').innerText = o.likes;
                updateLikeUI(id, o.liked);
            } else if (res.status === 401) {
                alert('로그인이 필요합니다.');
            }
        } catch (e) {
            console.error('오류가 발생했습니다.', e);
        }
    }

    async function deleteOotd(id) {
        if (!confirm('정말 삭제하시겠습니까? 삭제된 데이터는 복구할 수 없습니다.')) {
            return;
        }

        try {
            const res = await fetch(`/community/ootd/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            const data = await res.json();

            if (res.ok) {
                alert(data.message);
                location.reload();
            } else {
                alert('삭제에 실패했습니다. 다시 시도해 주세요.');
            }
        } catch (e) {
            console.error('오류가 발생했습니다.', e);
        }
    }
    function openNotice(id) {
        const n = nots.find(x => x.id === id);
        document.getElementById('noticeMTitle').innerText = n.title;
        document.getElementById('noticeMDate').innerText = n.date;
        // 상세 내용 업데이트
        document.querySelector('#noticeModal .text-sm.text-text-main').innerHTML = `<p>${n.content || '내용이 없습니다.'}</p>`;
        const modal = document.getElementById('noticeModal'); modal.classList.remove('hidden'); modal.classList.add('flex');
    }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); document.getElementById(id).classList.remove('flex'); }

    let ootdPage = 1;
    async function loadMoreOOTD() {
        const btn = document.getElementById('btn-load-more');
        const originalText = btn.innerText;
        btn.innerText = '로딩 중... ✨';
        btn.disabled = true;

        try {
            ootdPage++;
            const res = await fetch(`/community/ootd/more?page=${ootdPage}`);
            const data = await res.json();
            
            if (data.data.length > 0) {
                const fullList = document.getElementById('ootd-full');
                data.data.forEach(o => {
                    ots.push(o); // 데이터 배열에 추가해서 모달 연동 ✨
                    const h = `<div onclick="openOotd(${o.id})" class="aspect-4-5 rounded-xl overflow-hidden cursor-pointer shadow-sm animate-in fade-in zoom-in duration-500"><img src="${o.img}" class="w-full h-full object-cover hover:scale-105 transition-all"></div>`;
                    fullList.innerHTML += h;
                });

                if (!data.hasMore) {
                    btn.classList.add('hidden');
                } else {
                    btn.innerText = originalText;
                    btn.disabled = false;
                }
            } else {
                btn.classList.add('hidden');
            }
        } catch (e) {
            console.error('더보기 실패 😢', e);
            btn.innerText = originalText;
            btn.disabled = false;
        }
    }

    let magPage = 1;
    async function loadMoreMagazines() {
        const btn = document.getElementById('btn-load-more-mag');
        const originalText = btn.innerText;
        btn.innerText = '로딩 중... ✨';
        btn.disabled = true;

        try {
            magPage++;
            const res = await fetch(`/community/magazine/more?page=${magPage}`);
            const data = await res.json();
            
            if (data.data.length > 0) {
                const fullList = document.getElementById('magazine-full');
                data.data.forEach(m => {
                    mags.push(m); // 데이터 배열에 추가해서 모달 연동 ✨
                    const h = `<article onclick="openMag(${m.id})" class="group cursor-pointer animate-in fade-in zoom-in duration-500"><div class="aspect-video overflow-hidden rounded-2xl mb-4 bg-gray-100"><img src="${m.img}" class="w-full h-full object-cover group-hover:scale-105 transition-all"></div><h4 class="font-bold group-hover:text-primary transition-colors">${m.title}</h4></article>`;
                    fullList.innerHTML += h;
                });

                if (!data.hasMore) {
                    btn.classList.add('hidden');
                } else {
                    btn.innerText = originalText;
                    btn.disabled = false;
                }
            } else {
                btn.classList.add('hidden');
            }
        } catch (e) {
            console.error('더보기 실패 😢', e);
            btn.innerText = originalText;
            btn.disabled = false;
        }
    }

    let noticePage = 1;
    async function loadMoreNotices() {
        const btn = document.getElementById('btn-load-more-notice');
        const originalText = btn.innerText;
        btn.innerText = '로딩 중... ✨';
        btn.disabled = true;

        try {
            noticePage++;
            const res = await fetch(`/community/notice/more?page=${noticePage}`);
            const data = await res.json();
            
            if (data.data.length > 0) {
                const fullList = document.getElementById('notice-full');
                data.data.forEach(n => {
                    nots.push(n); // 데이터 배열에 추가해서 모달 연동 ✨
                    const h = `<li onclick="openNotice(${n.id})" class="py-5 px-6 flex justify-between hover:bg-gray-50 cursor-pointer rounded-xl transition-colors animate-in fade-in slide-in-from-bottom-2 duration-500"><span>${n.title}</span><span class="text-gray-400 text-sm">${n.date}</span></li>`;
                    fullList.innerHTML += h;
                });

                if (!data.hasMore) {
                    btn.classList.add('hidden');
                } else {
                    btn.innerText = originalText;
                    btn.disabled = false;
                }
            } else {
                btn.classList.add('hidden');
            }
        } catch (e) {
            console.error('더보기 실패 😢', e);
            btn.innerText = originalText;
            btn.disabled = false;
        }
    }

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
        if(e.target.id === 'ootdCreateModal') closeModal('ootdCreateModal');
    }

    // OOTD Create Modal Functions ✨💖
    function openOotdCreateModal() {
        @guest
            alert('로그인한 회원만 이용 가능합니다.');
            return;
        @endguest
        document.getElementById('ootdCreateModal').classList.replace('hidden', 'flex');
        initDropZone(); // 드롭존 초기화 ✨
    }

    function previewOotdImage(input) {
        const placeholder = document.getElementById('upload-placeholder');
        const previewImg = document.getElementById('ootd-preview-img');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                previewImg.src = e.target.result;
                previewImg.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function initDropZone() {
        const dz = document.getElementById('drop-zone');
        const overlay = document.getElementById('drag-overlay');
        const input = document.getElementById('image_file');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(ev => {
            dz.addEventListener(ev, e => { e.preventDefault(); e.stopPropagation(); }, false);
        });

        ['dragenter', 'dragover'].forEach(ev => {
            dz.addEventListener(ev, () => overlay.classList.replace('hidden', 'flex'), false);
        });

        ['dragleave', 'drop'].forEach(ev => {
            dz.addEventListener(ev, () => overlay.classList.replace('flex', 'hidden'), false);
        });

        dz.addEventListener('drop', e => {
            input.files = e.dataTransfer.files;
            previewOotdImage(input);
        }, false);
    }

    async function submitOotd(e) {
        e.preventDefault();
        const btn = document.getElementById('ootdSubmitBtn');
        const originalText = btn.innerText;
        const fileInput = document.getElementById('image_file');
        const content = document.getElementById('ootdContent').value;
        const insta = document.getElementById('ootdInsta').value;

        if (!fileInput.files[0]) { return alert('스타일 사진을 선택해 주세요.'); }
        if (!content) { return alert('스타일 설명을 입력해 주세요.'); }

        btn.innerText = '등록 중... ✨';
        btn.disabled = true;

        const formData = new FormData();
        formData.append('image_file', fileInput.files[0]);
        formData.append('content', content);
        formData.append('instagram_url', insta);

        try {
            const res = await fetch('{{ route('ootd.store') }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });
            const data = await res.json();

            if (res.ok) {
                alert('등록되었습니다.');
                location.reload(); // 성공 시 새로고침하여 목록 갱신 ✨
            } else {
                alert(data.message || '등록에 실패했습니다.');
                btn.innerText = originalText;
                btn.disabled = false;
            }
        } catch (err) {
            console.error(err);
            alert('오류가 발생했습니다.');
            btn.innerText = originalText;
            btn.disabled = false;
        }
    }
</script>
@endpush
