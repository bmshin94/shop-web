@extends('layouts.app')

@section('title', '1:1 문의내역 | 마이페이지 - Active Women\'s Premium Store')

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
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-text-main">1:1 문의내역</h3>
                    <button onclick="openInquiryModal()" class="px-5 py-2.5 bg-primary text-white font-bold rounded-lg hover:bg-red-600 transition-colors shadow-sm">문의하기</button>
                </div>

                <!-- [상태 1] 데이터가 있는 경우 -->
                <div class="border-t border-gray-100 mt-2 mb-8">
                    <div class="border-b border-gray-50 inquiry-item">
                        <button onclick="toggleInquiry(this)" class="w-full py-5 flex flex-col sm:flex-row items-start sm:items-center justify-between hover:bg-gray-50 transition-colors px-2 group text-left">
                            <div class="flex items-start sm:items-center gap-4">
                                <span class="text-primary font-bold text-sm bg-primary-light px-2 py-1 rounded border border-primary/20 shrink-0 mt-1 sm:mt-0">답변대기</span>
                                <div>
                                    <span class="text-sm font-bold text-text-main group-hover:text-primary transition-colors block mb-1">배송지 변경 관련해서 문의드립니다.</span>
                                    <p class="text-xs text-text-muted">안녕하세요, 어제 주문했는데 주소를 잘못 입력했어요...</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 mt-2 sm:mt-0">
                                <span class="text-xs text-gray-400 shrink-0">2026.03.06</span>
                                <span class="material-symbols-outlined text-gray-400 transition-transform">expand_more</span>
                            </div>
                        </button>
                        <div class="hidden bg-gray-50 px-6 py-6 border-t border-gray-100">
                            <p class="text-sm text-text-muted leading-relaxed">안녕하세요. 어제 저녁에 주문한 김에스핏입니다. 배송지 주소를 확인해보니 아파트 동/호수가 잘못 기재되어 있어서요. 아직 배송 준비 중인 것 같은데 수정이 가능할까요?</p>
                        </div>
                    </div>
                    
                    @for ($i = 9; $i >= 1; $i--)
                    <div class="border-b border-gray-50 inquiry-item">
                        <button onclick="toggleInquiry(this)" class="w-full py-5 flex flex-col sm:flex-row items-start sm:items-center justify-between hover:bg-gray-50 transition-colors px-2 group text-left">
                            <div class="flex items-start sm:items-center gap-4">
                                <span class="text-gray-500 font-bold text-sm bg-gray-100 px-2 py-1 rounded border border-gray-200 shrink-0 mt-1 sm:mt-0">답변완료</span>
                                <div><span class="text-sm font-bold text-text-main group-hover:text-primary transition-colors block mb-1">샘플 문의 내역 {{ $i }}</span><p class="text-xs text-text-muted">이것은 자동 생성된 샘플 데이터입니다.</p></div>
                            </div>
                            <div class="flex items-center gap-4 mt-2 sm:mt-0"><span class="text-xs text-gray-400 shrink-0">2026.02.{{ 10 + $i }}</span><span class="material-symbols-outlined text-gray-400">expand_more</span></div>
                        </button>
                        <div class="hidden bg-gray-50 px-6 py-6 border-t border-gray-100"><p class="text-sm text-text-main">문의하신 내용에 대한 답변이 완료되었습니다. 감사합니다.</p></div>
                    </div>
                    @endfor
                </div>

                <!-- [상태 2] 데이터가 없는 경우 -->
                <div class="mt-8 pt-8 border-t border-gray-100">
                    <div class="flex flex-col items-center justify-center py-16 text-center border border-gray-100 rounded-xl bg-gray-50">
                        <span class="material-symbols-outlined text-4xl text-gray-300 mb-3">chat_bubble</span>
                        <p class="text-text-muted font-medium">1:1 문의 내역이 없습니다.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    function toggleInquiry(button) {
        const content = button.nextElementSibling;
        const icon = button.querySelector('.material-symbols-outlined:last-child');
        content.classList.toggle('hidden');
        icon.style.transform = content.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
    }
    function openInquiryModal() { alert('문의하기 모달이 열립니다! '); }
</script>
@endpush
