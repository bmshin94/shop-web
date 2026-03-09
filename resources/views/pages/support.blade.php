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
            <div class="bg-primary-light/30 p-10 rounded-[3rem] mb-12 border border-primary/5">
                <h3 class="text-xl font-black text-text-main mb-6 text-center">무엇을 도와드릴까요? </h3>
                <div class="relative max-w-2xl mx-auto">
                    <input type="text" id="faq-search" placeholder="궁금하신 내용을 입력해 주세요" class="w-full pl-8 pr-16 py-5 rounded-full border-none shadow-xl focus:ring-2 focus:ring-primary text-text-main font-medium" />
                    <button class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center justify-center size-12 bg-primary text-white rounded-full hover:bg-red-600 transition-all shadow-lg">
                        <span class="material-symbols-outlined">search</span>
                    </button>
                </div>
            </div>

            <!-- Category Tabs -->
            <div class="flex flex-wrap gap-2 mb-10" id="faq-categories">
                <button onclick="filterFaq('all', this)" class="cat-btn px-6 py-2.5 bg-text-main text-white rounded-full text-sm font-bold shadow-md transition-all">전체</button>
                <button onclick="filterFaq('member', this)" class="cat-btn px-6 py-2.5 bg-white border border-gray-200 text-text-muted hover:border-text-main hover:text-text-main rounded-full text-sm font-bold transition-all">가입/정보</button>
                <button onclick="filterFaq('order', this)" class="cat-btn px-6 py-2.5 bg-white border border-gray-200 text-text-muted hover:border-text-main hover:text-text-main rounded-full text-sm font-bold transition-all">주문/결제</button>
                <button onclick="filterFaq('delivery', this)" class="cat-btn px-6 py-2.5 bg-white border border-gray-200 text-text-muted hover:border-text-main hover:text-text-main rounded-full text-sm font-bold transition-all">배송</button>
                <button onclick="filterFaq('return', this)" class="cat-btn px-6 py-2.5 bg-white border border-gray-200 text-text-muted hover:border-text-main hover:text-text-main rounded-full text-sm font-bold transition-all">반품/교환</button>
            </div>

            <!-- FAQ List -->
            <div class="space-y-3" id="faq-list">
                <!-- Items will be injected by JS -->
            </div>

            <!-- Pagination -->
            <div class="mt-12 flex justify-center items-center gap-2">
                <button class="size-10 rounded-xl border border-gray-200 text-gray-400 hover:bg-gray-50 transition-all"><span class="material-symbols-outlined text-sm">chevron_left</span></button>
                <button class="size-10 rounded-xl bg-primary text-white font-bold shadow-lg shadow-primary/20">1</button>
                <button class="size-10 rounded-xl border border-transparent text-text-muted hover:bg-gray-50 font-bold transition-all">2</button>
                <button class="size-10 rounded-xl border border-gray-200 text-text-main hover:bg-gray-50 transition-all"><span class="material-symbols-outlined text-sm">chevron_right</span></button>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    const faqs = [
        { cat: "return", q: "교환이나 반품 배송비는 어떻게 되나요?", a: "단순 변심의 경우 왕복 배송비 6,000원이 부과됩니다. 상품 불량이나 오배송의 경우 Active Women이 전액 부담합니다! " },
        { cat: "delivery", q: "주문한 상품은 언제 배송되나요?", a: "오후 2시 이전 결제 완료 건은 당일 발송을 원칙으로 하고 있어요! 보통 발송 후 1~3일 내에 도착한답니다. " },
        { cat: "order", q: "무통장 입금 확인은 언제 되나요?", a: "입금 후 약 1시간 이내에 시스템에서 자동으로 확인됩니다. 성함과 금액이 일치해야 하니 꼭 확인해 주세요! " },
        { cat: "member", q: "회원 탈퇴는 어떻게 하나요?", a: "마이페이지 > 회원정보 수정 하단에서 신청하실 수 있습니다. 탈퇴 시 보유하신 적립금과 쿠폰은 모두 소멸되니 주의해 주세요! " },
        { cat: "product", q: "레깅스 세탁은 어떻게 해야 하나요?", a: "찬물에 단독 손세탁을 가장 권장드려요! 세탁기 사용 시에는 꼭 세탁망에 넣어 울코스로 돌려주세요. " },
        { cat: "return", q: "반품 신청은 어디서 하나요?", a: "마이페이지 > 주문내역에서 반품하시려는 상품의 [반품신청] 버튼을 눌러주시면 됩니다! " },
        { cat: "order", q: "주문 취소는 어떻게 하나요?", a: "배송 준비 중 단계 전까지는 마이페이지에서 직접 취소가 가능합니다. 그 이후에는 고객센터로 문의 주세요! " },
        { cat: "delivery", q: "해외 배송도 가능한가요?", a: "현재는 국내 배송 서비스만 제공하고 있습니다. 글로벌 서비스도 준비 중이니 조금만 기다려 주세요! " },
        { cat: "member", q: "비밀번호를 잊어버렸어요.", a: "로그인 화면의 [비밀번호 찾기]를 통해 가입하신 이메일이나 휴대폰 번호로 임시 비밀번호를 받으실 수 있습니다. " },
        { cat: "product", q: "품절된 상품은 언제 재입고 되나요?", a: "인기 상품의 경우 2~4주 내로 재입고됩니다. 상품 페이지의 [재입고 알림]을 신청하시면 가장 먼저 소식을 들으실 수 있어요! " }
    ];

    function renderFaq(filter = 'all') {
        const container = document.getElementById('faq-list');
        const filtered = filter === 'all' ? faqs : faqs.filter(f => f.cat === filter);
        
        container.innerHTML = filtered.map((f, i) => `
            <div class="faq-item border border-gray-100 rounded-2xl bg-white overflow-hidden transition-all hover:border-primary/20">
                <button onclick="toggleFaq(this)" class="w-full flex justify-between items-center p-6 text-left group">
                    <div class="flex items-center gap-4">
                        <span class="text-primary font-black text-xl">Q.</span>
                        <span class="text-text-main font-bold text-base group-hover:text-primary transition-colors">${f.q}</span>
                    </div>
                    <span class="material-symbols-outlined faq-icon text-gray-300 transition-transform">expand_more</span>
                </button>
                <div class="faq-answer bg-background-alt/50 border-t border-gray-50">
                    <div class="p-8 flex gap-4">
                        <span class="text-gray-400 font-black text-xl">A.</span>
                        <div class="text-sm text-text-main leading-relaxed break-keep">${f.a}</div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function toggleFaq(btn) {
        const item = btn.parentElement;
        const isActive = item.classList.contains('active');
        
        // Close all others
        document.querySelectorAll('.faq-item').forEach(el => el.classList.remove('active'));
        
        if (!isActive) item.classList.add('active');
    }

    function filterFaq(cat, btn) {
        renderFaq(cat);
        document.querySelectorAll('.cat-btn').forEach(b => {
            b.classList.remove('bg-text-main', 'text-white', 'shadow-md');
            b.classList.add('bg-white', 'text-text-muted', 'border-gray-200');
        });
        btn.classList.remove('bg-white', 'text-text-muted', 'border-gray-200');
        btn.classList.add('bg-text-main', 'text-white', 'shadow-md');
    }

    document.addEventListener('DOMContentLoaded', () => renderFaq());
</script>
@endpush
