@extends('layouts.app')

@section('title', '고객센터 - Active Women\'s Premium Store')

@push('styles')
<style>
    .faq-answer { max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; }
    .faq-item.active .faq-answer { max-height: 500px; transition: max-height 0.5s ease-in; }
    .faq-item.active .faq-icon { transform: rotate(180deg); }
    .kakao-bg { background-color: #FEE500; }
</style>
@endpush

@section('content')
<main class="flex-1 w-full bg-background-light">
    <div class="mx-auto flex max-w-7xl flex-col lg:flex-row px-4 sm:px-6 lg:px-8 py-12 gap-12">

        <!-- LNB (Left Sidebar) -->
        <aside class="w-full lg:w-64 flex-shrink-0 space-y-8">
            <h2 class="text-3xl font-extrabold text-text-main mb-8">CS Center</h2>
            <nav class="flex flex-col gap-1">
                <a href="{{ route('support') }}" class="px-4 py-3 bg-text-main text-white rounded-xl font-bold transition-all shadow-md shadow-black/10 flex items-center justify-between">
                    자주 묻는 질문 <span class="material-symbols-outlined text-sm">chevron_right</span>
                </a>
                <a href="{{ route('support.notice') }}" class="px-4 py-3 text-text-muted hover:bg-gray-50 hover:text-text-main rounded-xl font-bold transition-all flex items-center justify-between group">
                    공지사항 <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">chevron_right</span>
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
                <a href="https://pf.kakao.com" target="_blank" class="w-full mt-8 py-4 kakao-bg text-background-dark rounded-2xl font-bold text-sm flex items-center justify-center gap-2 hover:shadow-lg transition-all">
                    <span class="material-symbols-outlined text-lg">chat_bubble</span> 카카오톡 실시간 상담
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Search Area -->
            <form action="{{ route('support') }}" method="GET" class="bg-primary-light/30 p-10 rounded-[3rem] mb-12 border border-primary/5">
                <input type="hidden" name="category" value="{{ request('category', 'all') }}">
                <h3 class="text-xl font-black text-text-main mb-6 text-center">무엇을 도와드릴까요? </h3>
                <div class="relative max-w-2xl mx-auto">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="궁금하신 내용을 입력해 주세요" class="w-full pl-8 pr-16 py-5 rounded-full border-none shadow-xl focus:ring-2 focus:ring-primary text-text-main font-medium" />
                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center justify-center size-12 bg-primary text-white rounded-full hover:bg-red-600 transition-all shadow-lg">
                        <span class="material-symbols-outlined">search</span>
                    </button>
                </div>
            </form>

            <!-- Category Tabs -->
            <div class="flex flex-wrap gap-2 mb-10" id="faq-categories">
                <a href="{{ route('support', ['category' => 'all', 'q' => request('q')]) }}" class="px-6 py-2.5 rounded-full text-sm font-bold shadow-md transition-all {{ request('category', 'all') === 'all' ? 'bg-text-main text-white' : 'bg-white border border-gray-200 text-text-muted hover:border-text-main' }}">전체</a>
                <a href="{{ route('support', ['category' => 'member', 'q' => request('q')]) }}" class="px-6 py-2.5 rounded-full text-sm font-bold transition-all {{ request('category') === 'member' ? 'bg-text-main text-white shadow-md' : 'bg-white border border-gray-200 text-text-muted hover:border-text-main' }}">가입/정보</a>
                <a href="{{ route('support', ['category' => 'order', 'q' => request('q')]) }}" class="px-6 py-2.5 rounded-full text-sm font-bold transition-all {{ request('category') === 'order' ? 'bg-text-main text-white shadow-md' : 'bg-white border border-gray-200 text-text-muted hover:border-text-main' }}">주문/결제</a>
                <a href="{{ route('support', ['category' => 'delivery', 'q' => request('q')]) }}" class="px-6 py-2.5 rounded-full text-sm font-bold transition-all {{ request('category') === 'delivery' ? 'bg-text-main text-white shadow-md' : 'bg-white border border-gray-200 text-text-muted hover:border-text-main' }}">배송</a>
                <a href="{{ route('support', ['category' => 'return', 'q' => request('q')]) }}" class="px-6 py-2.5 rounded-full text-sm font-bold transition-all {{ request('category') === 'return' ? 'bg-text-main text-white shadow-md' : 'bg-white border border-gray-200 text-text-muted hover:border-text-main' }}">반품/교환</a>
                <a href="{{ route('support', ['category' => 'product', 'q' => request('q')]) }}" class="px-6 py-2.5 rounded-full text-sm font-bold transition-all {{ request('category') === 'product' ? 'bg-text-main text-white shadow-md' : 'bg-white border border-gray-200 text-text-muted hover:border-text-main' }}">상품문의</a>
            </div>

            <!-- FAQ List -->
            <div class="space-y-3" id="faq-list">
                @forelse($faqs as $faq)
                <div class="faq-item border border-gray-100 rounded-2xl bg-white overflow-hidden transition-all hover:border-primary/20">
                    <button onclick="toggleFaq(this)" class="w-full flex justify-between items-center p-6 text-left group">
                        <div class="flex items-center gap-4">
                            <span class="text-primary font-black text-xl">Q.</span>
                            <span class="text-text-main font-bold text-base group-hover:text-primary transition-colors">{{ $faq->question }}</span>
                        </div>
                        <span class="material-symbols-outlined faq-icon text-gray-300 transition-transform">expand_more</span>
                    </button>
                    <div class="faq-answer bg-background-alt/50 border-t border-gray-50">
                        <div class="p-8 flex gap-4">
                            <span class="text-gray-400 font-black text-xl">A.</span>
                            <div class="text-sm text-text-main leading-relaxed break-keep">{!! nl2br(e($faq->answer)) !!}</div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="py-20 text-center text-text-muted">
                    <span class="material-symbols-outlined text-5xl mb-4 block">search_off</span>
                    찾으시는 결과가 없습니다. ✨
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $faqs->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    function toggleFaq(btn) {
        const item = btn.parentElement;
        const isActive = item.classList.contains('active');
        
        // Close all others
        document.querySelectorAll('.faq-item').forEach(el => el.classList.remove('active'));
        
        if (!isActive) item.classList.add('active');
    }
</script>
@endpush
