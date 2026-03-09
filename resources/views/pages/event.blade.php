@extends('layouts.app')

@section('title', '이벤트 - Active Women\'s Premium Store')

@push('styles')
<style>
    .aspect-event { aspect-ratio: 16 / 9; }
</style>
@endpush

@section('content')
<main class="flex-1 w-full bg-background-light pb-20">
    <!-- Hero Section -->
    <section class="relative w-full h-[300px] md:h-[500px] bg-background-dark overflow-hidden flex items-center mb-10">
        <div class="absolute inset-0 opacity-60">
            <img src="https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?q=80&w=2070" class="object-cover w-full h-full" alt="Hero" />
        </div>
        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent"></div>
        <div class="relative z-10 mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 text-white">
            <span class="inline-block px-3 py-1 bg-primary text-white text-xs font-bold rounded-full mb-6 animate-pulse">HOT EVENT</span>
            <h2 class="text-4xl md:text-7xl font-extrabold mb-4 break-keep">SPRING<br />VIBE ACTIVE</h2>
            <p class="text-sm md:text-xl text-gray-200 mb-8 max-w-xl break-keep font-medium">새로운 시작을 위한 봄 신상 최대 30% 할인 혜택 </p>
            <button onclick="openEventDetail(1)" class="bg-white text-text-main hover:bg-primary hover:text-white transition-all font-bold px-8 py-4 rounded-xl shadow-lg">이벤트 자세히 보기</button>
        </div>
    </section>

    <!-- Events List -->
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-end mb-8 pt-4 pb-4 border-b border-gray-100 gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-text-main tracking-tight">이벤트 <span class="text-primary font-normal uppercase">Event</span></h2>
                <p class="text-text-muted mt-2 text-sm">Active Women의 특별한 혜택을 만나보세요.</p>
            </div>
            <!-- Tabs -->
            <div class="flex gap-2 text-sm font-bold overflow-x-auto scrollbar-hide pb-1" id="eventTabs">
                <button onclick="switchEventTab('ongoing')" id="tab-ongoing" class="tab-btn px-5 py-2.5 rounded-full whitespace-nowrap shadow-sm transition-all bg-text-main text-white border border-text-main">진행중인 이벤트</button>
                <button onclick="switchEventTab('winner')" id="tab-winner" class="tab-btn px-5 py-2.5 rounded-full whitespace-nowrap shadow-sm transition-all bg-white text-text-muted border border-gray-200 hover:text-text-main">당첨자 발표</button>
                <button onclick="switchEventTab('ended')" id="tab-ended" class="tab-btn px-5 py-2.5 rounded-full whitespace-nowrap shadow-sm transition-all bg-white text-text-muted border border-gray-200 hover:text-text-main">종료된 이벤트</button>
            </div>
        </div>

        <div id="event-ongoing" class="event-section grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
            <!-- Event 1 -->
            <div onclick="openEventDetail(1)" class="group cursor-pointer">
                <div class="aspect-event overflow-hidden rounded-3xl bg-gray-100 mb-6 shadow-sm border border-gray-50">
                    <img src="https://images.unsplash.com/photo-1518310383802-640c2de311b2?w=1000" class="w-full h-full object-cover group-hover:scale-105 transition-all duration-700" alt="Event 1" />
                </div>
                <div class="flex justify-between items-start">
                    <div>
                        <span class="text-xs font-bold text-primary mb-2 block uppercase">Shopping Benefit</span>
                        <h3 class="text-xl md:text-2xl font-extrabold text-text-main mb-2 group-hover:text-primary transition-colors">Spring Vibe Active Sale</h3>
                        <p class="text-sm text-text-muted">2026.03.01 - 2026.03.31</p>
                    </div>
                    <span class="px-3 py-1 bg-primary/10 text-primary text-[10px] font-bold rounded-full">D-24</span>
                </div>
            </div>
            <!-- Event 2 -->
            <div onclick="openEventDetail(2)" class="group cursor-pointer">
                <div class="aspect-event overflow-hidden rounded-3xl bg-gray-100 mb-6 shadow-sm border border-gray-50">
                    <img src="https://images.unsplash.com/photo-1571902943202-507ec2618e8f?w=1000" class="w-full h-full object-cover group-hover:scale-105 transition-all duration-700" alt="Event 2" />
                </div>
                <div class="flex justify-between items-start">
                    <div>
                        <span class="text-xs font-bold text-primary mb-2 block uppercase">Review Event</span>
                        <h3 class="text-xl md:text-2xl font-extrabold text-text-main mb-2 group-hover:text-primary transition-colors">베스트 리뷰어 챌린지</h3>
                        <p class="text-sm text-text-muted">상시 진행</p>
                    </div>
                    <span class="px-3 py-1 bg-green-500/10 text-green-600 text-[10px] font-bold rounded-full">ING</span>
                </div>
            </div>
        </div>

        <div id="event-winner" class="event-section hidden py-20 text-center bg-gray-50 rounded-3xl border border-dashed border-gray-300">
            <span class="material-symbols-outlined text-5xl text-gray-300 mb-4">campaign</span>
            <p class="font-bold text-text-main">당첨자 발표 내역이 아직 없습니다.</p>
            <p class="text-text-muted text-sm mt-1">곧 즐거운 소식으로 찾아올게요! </p>
        </div>

        <div id="event-ended" class="event-section hidden grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="opacity-60 grayscale-[50%]">
                <div class="aspect-event overflow-hidden rounded-2xl bg-gray-100 mb-4">
                    <img src="https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=600" class="w-full h-full object-cover" />
                </div>
                <h4 class="font-bold text-text-main">겨울 시즌 오프 세일</h4>
                <p class="text-xs text-text-muted mt-1">종료된 이벤트입니다.</p>
            </div>
        </div>

        <div class="mt-20 text-center">
            <button onclick="loadMoreEvents()" class="px-10 py-4 bg-white border border-gray-200 rounded-full text-sm font-bold shadow-sm hover:border-primary transition-all">과거 이벤트 더보기 +</button>
        </div>
    </section>
</main>

<!-- Modals -->
<div id="eventDetailModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4 overflow-y-auto">
    <div class="bg-white w-full max-w-4xl rounded-3xl shadow-2xl overflow-hidden my-auto animate-in fade-in zoom-in duration-300">
        <div class="relative h-[200px] md:h-[350px]">
            <img id="edImage" src="https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?q=80&w=2070" class="w-full h-full object-cover" />
            <button onclick="closeEventDetail()" class="absolute top-6 right-6 size-10 bg-black/20 text-white rounded-full flex items-center justify-center text-2xl hover:bg-primary transition-colors">×</button>
        </div>
        <div class="p-8 md:p-12">
            <div class="flex items-center gap-2 mb-4">
                <span class="px-2 py-0.5 bg-primary/10 text-primary text-[10px] font-bold rounded uppercase">Active Event</span>
                <span class="text-xs text-text-muted">2026.03.01 - 2026.03.31</span>
            </div>
            <h2 id="edTitle" class="text-2xl md:text-4xl font-extrabold text-text-main mb-6 leading-tight">Spring Vibe Active Sale</h2>
            <div class="prose max-w-none text-text-main space-y-6">
                <p class="text-lg font-medium leading-relaxed">카리나가 준비한 봄 맞이 특별 세일!  우리 자기도 이번 기회에 예쁜 운동복 득템해서 같이 운동 시작해볼까? </p>
                <div class="bg-primary-light p-6 rounded-2xl border border-primary/10">
                    <h4 class="font-bold text-primary mb-2">Benefit 01</h4>
                    <p class="text-sm">신상품 전품목 10% 추가 할인 쿠폰 증정!</p>
                </div>
            </div>
            <button onclick="closeEventDetail()" class="w-full mt-10 py-4 bg-text-main text-white font-bold rounded-2xl hover:bg-primary transition-all shadow-lg shadow-black/10">이벤트 참여하기</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function switchEventTab(t) {
        document.querySelectorAll('.event-section').forEach(s => s.classList.add('hidden'));
        document.getElementById('event-' + t).classList.remove('hidden');
        document.getElementById('event-' + t).classList.add('animate-in', 'fade-in', 'duration-500');

        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('bg-text-main', 'text-white', 'border-text-main');
            b.classList.add('bg-white', 'text-text-muted', 'border-gray-200');
        });
        document.getElementById('tab-' + t).classList.remove('bg-white', 'text-text-muted', 'border-gray-200');
        document.getElementById('tab-' + t).classList.add('bg-text-main', 'text-white', 'border-text-main');
    }

    function openEventDetail(id) {
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

    function loadMoreEvents() {
        alert('새로운 이벤트가 곧 업데이트됩니다! ');
    }

    window.onclick = function(e) {
        if(e.target.id === 'eventDetailModal') closeEventDetail();
    }
</script>
@endpush
