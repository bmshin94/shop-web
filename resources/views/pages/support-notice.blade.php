@extends('layouts.app')

@section('title', '공지사항 | 고객센터 - Active Women\'s Premium Store')

@section('content')
<main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex flex-col lg:flex-row gap-12">
        <!-- SIDEBAR -->
        <aside class="w-full lg:w-64 flex-shrink-0 space-y-8">
            <h2 class="text-3xl font-extrabold text-text-main mb-8 uppercase tracking-tighter">CS Center</h2>
            <nav class="flex flex-col gap-1">
                <a href="{{ route('support') }}" class="px-4 py-3 text-text-muted hover:bg-gray-50 hover:text-text-main rounded-xl font-bold transition-all flex items-center justify-between group">
                    자주 묻는 질문 <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">chevron_right</span>
                </a>
                <a href="{{ route('support.notice') }}" class="px-4 py-3 bg-text-main text-white rounded-xl font-bold transition-all shadow-md shadow-black/10 flex items-center justify-between">
                    공지사항 <span class="material-symbols-outlined text-sm">chevron_right</span>
                </a>
                <a href="{{ route('support.exchange') }}" class="px-4 py-3 text-text-muted hover:bg-gray-50 hover:text-text-main rounded-xl font-bold transition-all flex items-center justify-between group">
                    교환/반품 안내 <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">chevron_right</span>
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
                <div class="text-xs text-text-muted space-y-1.5 mt-4">
                    <p>평일 <span class="text-text-main font-bold">09:00 - 18:00</span></p>
                    <p>점심 <span class="text-text-main font-bold">12:00 - 13:00</span></p>
                    <p>주말/공휴일 휴무</p>
                </div>
                <a href="https://pf.kakao.com" target="_blank" class="w-full mt-8 py-4 bg-kakao text-background-dark rounded-2xl font-bold text-sm flex items-center justify-center gap-2 hover:shadow-lg transition-all" style="background-color: #FEE500;">
                    <span class="material-symbols-outlined text-lg">chat_bubble</span> 실시간 상담 시작
                </a>
            </div>
        </aside>

        <!-- CONTENT -->
        <div class="flex-1">
            <div class="bg-primary-light/20 p-10 rounded-[3rem] mb-12 border border-primary/5">
                <h2 class="text-3xl font-black text-text-main tracking-tight mb-2">Notice </h2>
                <p class="text-text-muted text-sm font-medium">중요한 소식과 새로운 정보를 가장 먼저 전해드립니다.</p>
            </div>

            <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm">
                <ul class="divide-y divide-gray-100" id="notice-list">
                    @forelse($notices as $notice)
                    <li onclick="openNotice({{ json_encode(['title' => $notice->title, 'date' => ($notice->published_at ? $notice->published_at->format('Y.m.d') : $notice->created_at->format('Y.m.d')), 'content' => $notice->content]) }})" 
                        class="py-6 px-8 flex justify-between items-center hover:bg-gray-50 cursor-pointer group transition-all {{ $notice->is_important ? 'bg-primary/5' : '' }}">
                        <div class="flex items-center gap-4">
                            @if($notice->is_important)
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-primary text-white">중요</span>
                            @else
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-gray-100 text-gray-500">{{ $notice->type }}</span>
                            @endif
                            <span class="font-bold text-text-main group-hover:text-primary transition-colors">{{ $notice->title }}</span>
                        </div>
                        <span class="text-gray-400 text-sm font-medium">{{ $notice->published_at ? $notice->published_at->format('Y.m.d') : $notice->created_at->format('Y.m.d') }}</span>
                    </li>
                    @empty
                    <li class="py-20 text-center text-text-muted">
                        등록된 공지사항이 없습니다. ✨
                    </li>
                    @endforelse
                </ul>
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $notices->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>
</main>

<!-- Notice Detail Modal -->
<div id="noticeModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl p-8 md:p-12">
        <div class="flex justify-between items-start mb-6">
            <h2 id="noticeMTitle" class="text-xl md:text-2xl font-bold text-text-main leading-tight"></h2>
            <button onclick="closeModal()" class="text-gray-400 hover:text-text-main"><span class="material-symbols-outlined">close</span></button>
        </div>
        <p id="noticeMDate" class="text-xs text-gray-400 mb-8 pb-4 border-b border-gray-50"></p>
        <div id="noticeMContent" class="text-sm text-text-main leading-relaxed space-y-4 whitespace-pre-wrap"></div>
        <button onclick="closeModal()" class="w-full mt-10 py-4 bg-gray-100 text-text-main font-bold rounded-2xl hover:bg-gray-200 transition-all">확인</button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openNotice(data) {
        document.getElementById('noticeMTitle').innerText = data.title;
        document.getElementById('noticeMDate').innerText = data.date;
        document.getElementById('noticeMContent').innerHTML = data.content || '내용이 없습니다.';
        
        const m = document.getElementById('noticeModal');
        m.classList.remove('hidden'); 
        m.classList.add('flex');
    }

    function closeModal() {
        const m = document.getElementById('noticeModal');
        m.classList.add('hidden'); 
        m.classList.remove('flex');
    }

    // 모달 외부 클릭 시 닫기 ✨
    window.onclick = (e) => {
        if(e.target.id === 'noticeModal') closeModal();
    }
</script>
@endpush
