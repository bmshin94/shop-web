@extends('layouts.app')

@section('title', '이벤트 - Active Women\'s Premium Store')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    .aspect-event { aspect-ratio: 16 / 9; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f9f9f9; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; border: 1px solid #f9f9f9; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #896861; }
    .custom-scrollbar { scrollbar-width: thin; scrollbar-color: #d1d5db #f9f9f9; }

    /* Hide scrollbar for Chrome, Safari and Opera */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    /* Hide scrollbar for IE, Edge and Firefox */
    .scrollbar-hide {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }

    /* Swiper Custom */
    .hero-swiper { width: 100%; height: 300px; }
    @media (min-width: 768px) { .hero-swiper { height: 500px; } }
    .swiper-pagination-bullet { background: #fff !important; opacity: 0.5; }
    .swiper-pagination-bullet-active { opacity: 1; width: 24px; border-radius: 4px; transition: all 0.3s; }
</style>
@endpush

@section('content')
<main class="flex-1 w-full bg-background-light pb-20">
    <!-- Hero Section -->
    @if($heroEvents->isNotEmpty())
    <section class="relative w-full mb-10 overflow-hidden">
        <div class="swiper hero-swiper">
            <div class="swiper-wrapper">
                @foreach($heroEvents as $event)
                <div class="swiper-slide relative flex items-center">
                    <div class="absolute inset-0">
                        <img src="{{ $event->banner_image_url ? Storage::url($event->banner_image_url) : 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?q=80&w=2070' }}" class="object-cover w-full h-full" alt="Hero" />
                        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent"></div>
                    </div>
                    <div class="relative z-10 mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 text-white pt-10 md:pt-14">
                        <span class="inline-block px-3 py-1 bg-primary text-white text-xs font-bold rounded-full mb-6 animate-pulse uppercase tracking-wider">Hot Event</span>
                        <h2 class="text-4xl md:text-7xl font-extrabold mb-4 break-keep leading-[1.1]">{!! nl2br(e($event->title)) !!}</h2>
                        <p class="text-sm md:text-xl text-gray-200 mb-8 max-w-xl break-keep font-medium">{{ $event->summary }}</p>
                        <button onclick="openEventDetail({{ $event->id }})" class="bg-white text-text-main hover:bg-primary hover:text-white transition-all font-bold px-8 py-4 rounded-xl shadow-lg">이벤트 자세히 보기</button>
                    </div>
                </div>
                @endforeach
            </div>
            
            @if($heroEvents->count() > 1)
            <!-- Swiper Controls -->
            <div class="swiper-pagination !bottom-8 !left-auto !right-8 !w-auto"></div>
            <div class="hidden md:flex absolute bottom-8 left-8 z-20 gap-3">
                <button class="hero-prev size-12 rounded-full border border-white/20 bg-white/10 text-white backdrop-blur-md flex items-center justify-center hover:bg-primary hover:border-primary transition-all">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <button class="hero-next size-12 rounded-full border border-white/20 bg-white/10 text-white backdrop-blur-md flex items-center justify-center hover:bg-primary hover:border-primary transition-all">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>
            @endif
        </div>
    </section>
    @endif

    <!-- Events List -->
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 pt-4 pb-4 border-b border-gray-100 gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-text-main tracking-tight">이벤트 <span class="text-primary font-normal uppercase">Event</span></h2>
                <p class="text-text-muted mt-2 text-sm">Active Women의 특별한 혜택을 만나보세요.</p>
            </div>
            <!-- Tabs -->
            <div class="flex gap-1 md:gap-2 text-xs md:text-sm font-bold overflow-x-auto scrollbar-hide pb-1 -mx-4 px-4 md:mx-0 md:px-0" id="eventTabs">
                <button onclick="switchEventTab('upcoming')" id="tab-upcoming" class="tab-btn px-3 py-1.5 md:px-5 md:py-2.5 rounded-full whitespace-nowrap shadow-sm transition-all bg-white text-text-muted border border-gray-200 hover:bg-text-main hover:text-white hover:border-text-main">진행예정 이벤트</button>
                <button onclick="switchEventTab('ongoing')" id="tab-ongoing" class="tab-btn px-3 py-1.5 md:px-5 md:py-2.5 rounded-full whitespace-nowrap shadow-sm transition-all bg-text-main text-white border border-text-main hover:bg-text-main hover:text-white hover:border-text-main">진행중인 이벤트</button>
                <button onclick="switchEventTab('winner')" id="tab-winner" class="tab-btn px-3 py-1.5 md:px-5 md:py-2.5 rounded-full whitespace-nowrap shadow-sm transition-all bg-white text-text-muted border border-gray-200 hover:bg-text-main hover:text-white hover:border-text-main">당첨자 발표</button>
                <button onclick="switchEventTab('ended')" id="tab-ended" class="tab-btn px-3 py-1.5 md:px-5 md:py-2.5 rounded-full whitespace-nowrap shadow-sm transition-all bg-white text-text-muted border border-gray-200 hover:bg-text-main hover:text-white hover:border-text-main">종료된 이벤트</button>
            </div>
        </div>

        <div id="event-upcoming" class="event-section hidden grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
            @forelse($upcomingEvents as $event)
            <div onclick="openEventDetail({{ $event->id }})" class="group cursor-pointer">
                <div class="aspect-event overflow-hidden rounded-3xl bg-gray-100 mb-6 shadow-sm border border-gray-50">
                    <img src="{{ $event->banner_image_url ? Storage::url($event->banner_image_url) : 'https://images.unsplash.com/photo-1518310383802-640c2de311b2?w=1000' }}" class="w-full h-full object-cover group-hover:scale-105 transition-all duration-700" alt="{{ $event->title }}" />
                </div>
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-xs font-bold text-primary block uppercase tracking-wide">Coming Soon</span>
                                @if($event->type === '응모형')
                                <span class="px-1.5 py-0.5 bg-text-main text-white text-[10px] font-black rounded">응모형</span>
                                @endif
                            </div>
                            <h3 class="text-xl md:text-2xl font-extrabold text-text-main mb-2 group-hover:text-primary transition-colors">{{ $event->title }}</h3>                            <p class="text-sm text-text-muted">
                                {{ $event->start_at ? $event->start_at->format('Y.m.d') : '-' }} 오픈 예정
                            </p>
                        </div>
                        @if($event->start_at)
                            @php
                                $days = now()->startOfDay()->diffInDays($event->start_at->startOfDay(), false);
                            @endphp
                            <span class="px-3 py-1 bg-primary/10 text-primary text-[10px] font-bold rounded-full">{{ $days <= 0 ? 'D-DAY' : 'D-' . intval($days) }}</span>
                        @endif
                    </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center bg-gray-50 rounded-3xl border border-dashed border-gray-300">
                <span class="material-symbols-outlined text-5xl text-gray-300 mb-4">event_upcoming</span>
                <p class="font-bold text-text-main">진행 예정인 이벤트가 없습니다.</p>
                <p class="text-text-muted text-sm mt-1">곧 새로운 소식으로 찾아올게요!</p>
            </div>
            @endforelse
        </div>

        <div id="event-ongoing" class="event-section grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
            @forelse($ongoingEvents as $event)
            <div onclick="openEventDetail({{ $event->id }})" class="group cursor-pointer">
                <div class="aspect-event overflow-hidden rounded-3xl bg-gray-100 mb-6 shadow-sm border border-gray-50">
                    <img src="{{ $event->banner_image_url ? Storage::url($event->banner_image_url) : 'https://images.unsplash.com/photo-1518310383802-640c2de311b2?w=1000' }}" class="w-full h-full object-cover group-hover:scale-105 transition-all duration-700" alt="{{ $event->title }}" />
                </div>
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xs font-bold text-primary block uppercase">Event</span>
                            @if($event->type === '응모형')
                            <span class="px-1.5 py-0.5 bg-text-main text-white text-[10px] font-black rounded">응모형</span>
                            @endif
                        </div>
                        <h3 class="text-xl md:text-2xl font-extrabold text-text-main mb-2 group-hover:text-primary transition-colors">{{ $event->title }}</h3>
                        <p class="text-sm text-text-muted">
                            {{ $event->start_at ? $event->start_at->format('Y.m.d') : '상시 진행' }}
                            @if($event->end_at) - {{ $event->end_at->format('Y.m.d') }} @endif
                        </p>
                    </div>
                    @if($event->end_at)
                        @php
                            // 남은 일수 계산 (오늘 기준)
                            $days = now()->startOfDay()->diffInDays($event->end_at->startOfDay(), false);
                        @endphp
                        @if($days >= 0)
                            <span class="px-3 py-1 bg-primary/10 text-primary text-[10px] font-bold rounded-full">D-{{ intval($days) }}</span>
                        @endif
                    @else
                        <span class="px-3 py-1 bg-green-500/10 text-green-600 text-[10px] font-bold rounded-full">ING</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center bg-gray-50 rounded-3xl border border-dashed border-gray-300">
                <span class="material-symbols-outlined text-5xl text-gray-300 mb-4">event_busy</span>
                <p class="font-bold text-text-main">현재 진행 중인 이벤트가 없습니다.</p>
                <p class="text-text-muted text-sm mt-1">곧 새로운 이벤트로 찾아올게요! </p>
            </div>
            @endforelse
        </div>

        <div id="event-winner" class="event-section hidden grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
            @forelse($winnerEvents as $event)
            <div onclick="openEventDetail({{ $event->id }}, true)" class="group cursor-pointer">
                <div class="aspect-event overflow-hidden rounded-3xl bg-gray-100 mb-6 shadow-sm border border-gray-50">
                    <img src="{{ $event->banner_image_url ? Storage::url($event->banner_image_url) : 'https://images.unsplash.com/photo-1518310383802-640c2de311b2?w=1000' }}" class="w-full h-full object-cover group-hover:scale-105 transition-all duration-700" alt="{{ $event->title }}" />
                </div>
                <div class="flex justify-between items-start">
                    <div>
                        <span class="text-xs font-bold text-primary mb-2 block uppercase">Winner Announcement</span>
                        <h3 class="text-xl md:text-2xl font-extrabold text-text-main mb-2 group-hover:text-primary transition-colors">{{ $event->title }}</h3>
                        <p class="text-sm text-text-muted">당첨자 발표 안내</p>
                    </div>
                    <span class="px-3 py-1 bg-yellow-500/10 text-yellow-600 text-[10px] font-bold rounded-full">공지</span>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center bg-gray-50 rounded-3xl border border-dashed border-gray-300">
                <span class="material-symbols-outlined text-5xl text-gray-300 mb-4">campaign</span>
                <p class="font-bold text-text-main">당첨자 발표 내역이 아직 없습니다.</p>
                <p class="text-text-muted text-sm mt-1">곧 즐거운 소식으로 찾아올게요! </p>
            </div>
            @endforelse
        </div>

        <div id="event-ended" class="event-section hidden grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($endedEvents as $event)
            <div class="opacity-60 grayscale-[50%]">
                <div class="aspect-event overflow-hidden rounded-2xl bg-gray-100 mb-4">
                    <img src="{{ $event->banner_image_url ? Storage::url($event->banner_image_url) : 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=600' }}" class="w-full h-full object-cover" />
                </div>
                <h4 class="font-bold text-text-main">{{ $event->title }}</h4>
                <p class="text-xs text-text-muted mt-1">종료된 이벤트입니다.</p>
            </div>
            @empty
            <div class="col-span-full py-20 text-center bg-gray-50 rounded-3xl border border-dashed border-gray-300">
                <p class="font-bold text-text-main">종료된 이벤트가 없습니다.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-20 text-center" id="load-more-container">
            <button onclick="loadMoreEvents()" id="btn-load-more" class="px-10 py-4 bg-white border border-gray-200 rounded-full text-sm font-bold shadow-sm hover:border-primary transition-all">과거 이벤트 더보기 +</button>
        </div>
    </section>
</main>

<!-- Modals -->
<div id="eventDetailModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4 overflow-y-auto">
    <div class="bg-white w-full max-w-4xl rounded-3xl shadow-2xl overflow-hidden my-auto animate-in fade-in zoom-in duration-300 max-h-[90vh] flex flex-col">
        <div class="relative h-[200px] md:h-[350px] shrink-0">
            <img id="edImage" src="" class="w-full h-full object-cover" />
            <button onclick="closeEventDetail()" class="absolute top-6 right-6 size-10 bg-black/20 text-white rounded-full flex items-center justify-center text-2xl hover:bg-primary transition-colors">&times;</button>
        </div>
        <div class="p-8 md:p-12 overflow-y-auto custom-scrollbar flex-1">
            <div class="flex items-center gap-2 mb-4">
                <span class="px-2 py-0.5 bg-primary/10 text-primary text-[10px] font-bold rounded uppercase">Active Event</span>
                <span id="edPeriod" class="text-xs text-text-muted"></span>
            </div>
            <h2 id="edTitle" class="text-2xl md:text-4xl font-extrabold text-text-main mb-6 leading-tight"></h2>
            <div id="edDescription" class="prose max-w-none text-text-main space-y-6 whitespace-pre-wrap">
                <!-- 동적으로 컨텐츠가 삽입됩니다 -->
            </div>

            <!-- 응모형 이벤트 전용 버튼 영역 -->
            <div id="edApplyArea" class="hidden mt-10">
                <button id="btnApplyEvent" onclick="submitParticipation()" class="w-full py-4 bg-primary text-white font-bold rounded-2xl hover:bg-red-600 transition-all shadow-lg shadow-primary/20 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">how_to_reg</span>
                    이벤트 응모하기
                </button>
                <div id="appliedMsg" class="hidden w-full py-4 bg-gray-50 border border-gray-100 text-text-muted font-bold rounded-2xl text-center flex flex-col items-center justify-center gap-1">
                    <div class="flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-green-500">task_alt</span>
                        이벤트 응모 완료
                    </div>
                    <button onclick="cancelParticipation()" class="text-[11px] text-text-muted underline hover:text-primary transition-colors mt-1">취소하기</button>
                </div>
                <p id="loginRequiredMsg" class="hidden mt-3 text-center text-xs font-bold text-text-muted">
                    응모를 위해 <a href="{{ route('login') }}" class="text-primary underline">로그인</a>이 필요합니다.
                </p>
            </div>
            
            <div id="edWinners" class="hidden mt-10 p-6 bg-gray-50 rounded-2xl border border-gray-100">
                <p class="text-[12px] font-bold text-primary mb-4 uppercase tracking-wider flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">workspace_premium</span>
                    당첨자 명단
                </p>
                <div id="edWinnersList" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 max-h-[250px] overflow-y-auto pr-2 custom-scrollbar">
                    <!-- 당첨자 이름이 들어갑니다 -->
                </div>
            </div>

            <button onclick="closeEventDetail()" class="w-full mt-10 py-4 bg-text-main text-white font-bold rounded-2xl hover:bg-primary transition-all shadow-lg shadow-black/10">확인</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    // 컨트롤러에서 넘어온 이벤트 데이터를 모두 JS 객체로 변환하여 저장
    const eventsData = {!! json_encode($eventsData) !!};
    
    // 더보기 상태 관리
    let currentTab = 'ongoing';
    let pages = { upcoming: 1, ongoing: 1, winner: 1, ended: 1 };
    let hasMore = {!! json_encode($hasMore) !!};

    // 응모 정보 관리
    const isLoggedIn = @json(auth()->check());
    let participatedEventIds = new Set(@json($participatedEventIds));
    let currentEventId = null;

    // Swiper 인스턴스 저장용
    let heroSwiper = null;

    function initHeroSwiper() {
        const swiperEl = document.querySelector('.hero-swiper');
        if (!swiperEl) return;

        // 이미 인스턴스가 있다면 파괴 후 재생성
        if (heroSwiper) {
            heroSwiper.destroy(true, true);
        }

        heroSwiper = new Swiper('.hero-swiper', {
            loop: true,
            speed: 800,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.hero-next',
                prevEl: '.hero-prev',
            },
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            }
        });
    }

    function switchEventTab(t) {
        currentTab = t;
        document.querySelectorAll('.event-section').forEach(s => s.classList.add('hidden'));
        document.getElementById('event-' + t).classList.remove('hidden');
        document.getElementById('event-' + t).classList.add('animate-in', 'fade-in', 'duration-500');

        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('bg-text-main', 'text-white', 'border-text-main');
            b.classList.add('bg-white', 'text-text-muted', 'border-gray-200');
        });
        document.getElementById('tab-' + t).classList.remove('bg-white', 'text-text-muted', 'border-gray-200');
        document.getElementById('tab-' + t).classList.add('bg-text-main', 'text-white', 'border-text-main');
        
        updateLoadMoreButton();
    }

    function updateLoadMoreButton() {
        const container = document.getElementById('load-more-container');
        if (hasMore[currentTab]) {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }

    function openEventDetail(id, showWinner = false) {
        const ev = eventsData[id];
        if (!ev) return;

        currentEventId = id;

        // 이미지 적용
        const imageUrl = ev.banner_url || (ev.banner_image_url ? '/storage/' + ev.banner_image_url : 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?q=80&w=2070');
        document.getElementById('edImage').src = imageUrl;
        
        // 제목 적용
        document.getElementById('edTitle').innerText = ev.title;
        
        // 응모 영역 초기화 및 설정
        const applyArea = document.getElementById('edApplyArea');
        const btnApply = document.getElementById('btnApplyEvent');
        const appliedMsg = document.getElementById('appliedMsg');
        const loginMsg = document.getElementById('loginRequiredMsg');

        applyArea.classList.add('hidden');
        btnApply.classList.add('hidden');
        appliedMsg.classList.add('hidden');
        loginMsg.classList.add('hidden');

        // 진행 중인 '응모형' 이벤트인 경우에만 응모 버튼 표시
        const now = new Date();
        const isStarted = !ev.start_at || new Date(ev.start_at) <= now;
        const isEnded = ev.end_at && new Date(ev.end_at) < now;

        if (!showWinner && ev.type === '응모형' && isStarted && !isEnded) {
            applyArea.classList.remove('hidden');
            if (!isLoggedIn) {
                btnApply.classList.remove('hidden');
                btnApply.disabled = true;
                btnApply.classList.add('opacity-50', 'cursor-not-allowed');
                loginMsg.classList.remove('hidden');
            } else if (participatedEventIds.has(id)) {
                appliedMsg.classList.remove('hidden');
            } else {
                btnApply.classList.remove('hidden');
                btnApply.disabled = false;
                btnApply.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        // 당첨자 영역 초기화
        const winnersArea = document.getElementById('edWinners');
        const winnersList = document.getElementById('edWinnersList');
        winnersArea.classList.add('hidden');
        winnersList.innerHTML = '';
        
        // 당첨자 렌더링 헬퍼 함수
        const renderWinners = (winners) => {
            winners.forEach(winner => {
                const name = winner.name;
                let maskedName = name;
                if (name.length > 1) {
                    maskedName = name[0] + '*'.repeat(name.length - 2) + (name.length > 2 ? name[name.length - 1] : '');
                }
                const el = document.createElement('div');
                el.className = 'px-3 py-2 bg-white border border-gray-100 rounded-xl text-center text-sm font-extrabold text-text-main shadow-sm';
                el.innerText = maskedName;
                winnersList.appendChild(el);
            });
            winnersArea.classList.remove('hidden');
        };

        // 기간 및 상세 내용 적용
        if (showWinner) {
            // 당첨자 발표 탭에서 직접 클릭한 경우에만 당첨자 정보 노출! ✨
            document.getElementById('edPeriod').innerText = '당첨자 발표';
            document.getElementById('edDescription').innerHTML = ev.winner_announcement || '<p class="text-lg font-medium">당첨자 명단을 확인해 주세요!</p>';
            
            if (ev.winners && ev.winners.length > 0) {
                renderWinners(ev.winners);
            }
        } else {
            // 일반 상세 보기인 경우에는 당첨자가 있어도 보여주지 않음! 😊
            const formatDate = (dateStr) => {
                const d = new Date(dateStr);
                return `${d.getFullYear()}.${String(d.getMonth() + 1).padStart(2, '0')}.${String(d.getDate()).padStart(2, '0')}`;
            };
            const period = (ev.start_at ? formatDate(ev.start_at) : '상시 진행') + (ev.end_at ? ' - ' + formatDate(ev.end_at) : '');
            document.getElementById('edPeriod').innerText = period;
            
            // 일반 설명(description) 보여줌
            document.getElementById('edDescription').innerHTML = ev.description || `<p class="text-lg font-medium leading-relaxed">${ev.summary || '상세 내용이 없습니다.'}</p>`;
        }

        const modal = document.getElementById('eventDetailModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeEventDetail() {
        const modal = document.getElementById('eventDetailModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    function submitParticipation() {
        if (!isLoggedIn) {
            showAlert('로그인 후 이용 가능합니다.', '알림');
            return;
        }

        if (!currentEventId) return;

        const $btn = $('#btnApplyEvent');
        const $appliedMsg = $('#appliedMsg');
        
        $btn.prop('disabled', true).text('처리 중...');

        $.ajax({
            url: `/event/${currentEventId}/participate`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                if (res.success) {
                    showToast('이벤트 응모가 완료되었습니다.', 'check_circle', 'bg-green-600');
                    participatedEventIds.add(currentEventId);
                    $btn.addClass('hidden');
                    $appliedMsg.removeClass('hidden');
                }
            },
            error: function(xhr) {
                const msg = xhr.responseJSON ? xhr.responseJSON.message : '요청 처리 중 오류가 발생했습니다.';
                showAlert(msg, '오류', 'error');
                $btn.prop('disabled', false).html('<span class="material-symbols-outlined">how_to_reg</span> 이벤트 응모하기');
            }
        });
    }

    function cancelParticipation() {
        if (!currentEventId) return;

        showConfirm('정말 응모를 취소하시겠습니까?', { title: '응모 취소', confirmText: '취소하기' }).then((confirmed) => {
            if (!confirmed) return;

            const $btn = $('#btnApplyEvent');
            const $appliedMsg = $('#appliedMsg');

            $.ajax({
                url: `/event/${currentEventId}/participate`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    if (res.success) {
                        showToast('응모가 취소되었습니다.');
                        participatedEventIds.delete(currentEventId);
                        $appliedMsg.addClass('hidden');
                        $btn.removeClass('hidden');
                        $btn.prop('disabled', false).html('<span class="material-symbols-outlined">how_to_reg</span> 이벤트 응모하기');
                    }
                },
                error: function(xhr) {
                    const msg = xhr.responseJSON ? xhr.responseJSON.message : '요청 처리 중 오류가 발생했습니다.';
                    showAlert(msg, '오류', 'error');
                }
            });
        });
    }

    function loadMoreEvents() {
        if (!hasMore[currentTab]) return;
        
        pages[currentTab]++;
        const $btn = $('#btn-load-more');
        $btn.text('불러오는 중...').prop('disabled', true);

        $.ajax({
            url: `/event/more?tab=${currentTab}&page=${pages[currentTab]}`,
            method: 'GET',
            success: function(res) {
                hasMore[currentTab] = res.has_more;
                updateLoadMoreButton();
                
                res.events.forEach(ev => {
                    eventsData[ev.id] = ev; // 모달용 데이터 추가
                    renderEvent(ev, currentTab);
                });
            },
            error: function() {
                alert('이벤트를 불러오는 중 오류가 발생했습니다.');
            },
            complete: function() {
                $btn.text('과거 이벤트 더보기 +').prop('disabled', false);
            }
        });
    }

    function renderEvent(ev, tab) {
        let html = '';
        const imgUrl = ev.banner_url || (tab === 'ended' ? 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=600' : 'https://images.unsplash.com/photo-1518310383802-640c2de311b2?w=1000');
        
        if (tab === 'upcoming') {
            const dStr = ev.start_at ? new Date(ev.start_at).toLocaleDateString('ko-KR').replace(/\s/g, '').slice(0, -1) : '-';
            let badge = '';
            if (ev.start_at) {
                const targetDate = new Date(ev.start_at);
                targetDate.setHours(0,0,0,0);
                const today = new Date();
                today.setHours(0,0,0,0);
                const days = Math.round((targetDate - today) / (1000 * 60 * 60 * 24));
                badge = `<span class="px-3 py-1 bg-primary/10 text-primary text-[10px] font-bold rounded-full">${days <= 0 ? 'D-DAY' : 'D-' + days}</span>`;
            }
            const partBadge = ev.type === '응모형' ? '<span class="px-1.5 py-0.5 bg-text-main text-white text-[10px] font-black rounded">응모형</span>' : '';
            html = `
            <div onclick="openEventDetail(${ev.id})" class="group cursor-pointer animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="aspect-event overflow-hidden rounded-3xl bg-gray-100 mb-6 shadow-sm border border-gray-50">
                    <img src="${imgUrl}" class="w-full h-full object-cover group-hover:scale-105 transition-all duration-700" alt="${ev.title}" />
                </div>
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xs font-bold text-primary block uppercase tracking-wide">Coming Soon</span>
                            ${partBadge}
                        </div>
                        <h3 class="text-xl md:text-2xl font-extrabold text-text-main mb-2 group-hover:text-primary transition-colors">${ev.title}</h3>
                        <p class="text-sm text-text-muted">${dStr} 오픈 예정</p>
                    </div>
                    ${badge}
                </div>
            </div>`;
            $(`#event-upcoming`).append(html);
        } else if (tab === 'ongoing') {
            const dStr = ev.start_at ? new Date(ev.start_at).toLocaleDateString('ko-KR').replace(/\s/g, '').slice(0, -1) : '상시 진행';
            const eStr = ev.end_at ? ` - ${new Date(ev.end_at).toLocaleDateString('ko-KR').replace(/\s/g, '').slice(0, -1)}` : '';
            let badge = '';
            if (ev.end_at) {
                const days = Math.floor((new Date(ev.end_at) - new Date()) / (1000 * 60 * 60 * 24));
                if (days >= 0) badge = `<span class="px-3 py-1 bg-primary/10 text-primary text-[10px] font-bold rounded-full">D-${days}</span>`;
            } else {
                badge = `<span class="px-3 py-1 bg-green-500/10 text-green-600 text-[10px] font-bold rounded-full">ING</span>`;
            }
            const partBadge = ev.type === '응모형' ? '<span class="px-1.5 py-0.5 bg-text-main text-white text-[10px] font-black rounded">응모형</span>' : '';
            html = `
            <div onclick="openEventDetail(${ev.id})" class="group cursor-pointer animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="aspect-event overflow-hidden rounded-3xl bg-gray-100 mb-6 shadow-sm border border-gray-50">
                    <img src="${imgUrl}" class="w-full h-full object-cover group-hover:scale-105 transition-all duration-700" alt="${ev.title}" />
                </div>
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xs font-bold text-primary block uppercase">Event</span>
                            ${partBadge}
                        </div>
                        <h3 class="text-xl md:text-2xl font-extrabold text-text-main mb-2 group-hover:text-primary transition-colors">${ev.title}</h3>
                        <p class="text-sm text-text-muted">${dStr}${eStr}</p>
                    </div>
                    ${badge}
                </div>
            </div>`;
            $(`#event-ongoing`).append(html);
        } else if (tab === 'winner') {
            html = `
            <div onclick="openEventDetail(${ev.id}, true)" class="group cursor-pointer animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="aspect-event overflow-hidden rounded-3xl bg-gray-100 mb-6 shadow-sm border border-gray-50">
                    <img src="${imgUrl}" class="w-full h-full object-cover group-hover:scale-105 transition-all duration-700" alt="${ev.title}" />
                </div>
                <div class="flex justify-between items-start">
                    <div>
                        <span class="text-xs font-bold text-primary mb-2 block uppercase">Winner Announcement</span>
                        <h3 class="text-xl md:text-2xl font-extrabold text-text-main mb-2 group-hover:text-primary transition-colors">${ev.title}</h3>
                        <p class="text-sm text-text-muted">당첨자 발표 안내</p>
                    </div>
                    <span class="px-3 py-1 bg-yellow-500/10 text-yellow-600 text-[10px] font-bold rounded-full">공지</span>
                </div>
            </div>`;
            $(`#event-winner`).append(html);
        } else if (tab === 'ended') {
            html = `
            <div class="opacity-60 grayscale-[50%] animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="aspect-event overflow-hidden rounded-2xl bg-gray-100 mb-4">
                    <img src="${imgUrl}" class="w-full h-full object-cover" />
                </div>
                <h4 class="font-bold text-text-main">${ev.title}</h4>
                <p class="text-xs text-text-muted mt-1">종료된 이벤트입니다.</p>
            </div>`;
            $(`#event-ended`).append(html);
        }
    }

    window.onclick = function(e) {
        if(e.target.id === 'eventDetailModal') closeEventDetail();
    }
    
    // 초기 더보기 버튼 상태 세팅
    document.addEventListener('DOMContentLoaded', function() {
        updateLoadMoreButton();
        initHeroSwiper();
    });
</script>
@endpush
