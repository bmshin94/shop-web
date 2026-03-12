@extends('layouts.app')

@section('title', '1:1 문의내역 | 마이페이지 - Active Women\'s Premium Store')

@section('content')
<main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-text-muted mb-6">
        <a href="/" class="hover:text-primary transition-colors">Home</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <a href="{{ route('mypage') }}" class="hover:text-primary transition-colors">마이페이지</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <span class="font-bold text-text-main">1:1 문의내역</span>
    </nav>

    <!-- Page Title -->
    <h2 class="text-3xl font-extrabold text-text-main tracking-tight mb-8">1:1 문의내역</h2>

    <div class="flex flex-col lg:flex-row gap-8 items-start">
        <!-- LNB (Left Navigation Bar) -->
        @include('partials.mypage-sidebar')

        <!-- Main Dashboard Content -->
        <div class="flex-1 w-full space-y-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
                <div class="flex justify-between items-center mb-8 border-b border-gray-50 pb-6">
                    <p class="text-lg font-bold text-text-main">나의 문의 <span class="text-primary ml-1">{{ number_format($inquiries->total()) }}</span></p>
                    <button onclick="openModal(document.getElementById('inquiryWriteModal'))" class="px-6 py-3 bg-primary text-white text-sm font-black rounded-xl hover:bg-red-600 transition-all shadow-lg shadow-primary/20 active:scale-95">문의하기</button>
                </div>

                @if($inquiries->isNotEmpty())
                <div class="border-t border-gray-100">
                    @foreach($inquiries as $inquiry)
                    <div class="border-b border-gray-50 inquiry-item">
                        <button onclick="toggleInquiry(this)" class="w-full py-6 flex flex-col sm:flex-row items-start sm:items-center justify-between hover:bg-gray-50/50 transition-colors px-4 group text-left rounded-xl">
                            <div class="flex items-start sm:items-center gap-4 flex-1">
                                @if($inquiry->status === '답변완료')
                                <span class="text-gray-500 font-bold text-[11px] bg-gray-100 px-2.5 py-1 rounded-full border border-gray-200 shrink-0 uppercase tracking-tighter">답변완료</span>
                                @else
                                <span class="text-primary font-bold text-[11px] bg-primary/5 px-2.5 py-1 rounded-full border border-primary/20 shrink-0 uppercase tracking-tighter">답변대기</span>
                                @endif
                                
                                <div class="flex-1 min-w-0">
                                    <span class="text-sm font-bold text-text-main group-hover:text-primary transition-colors block mb-1 truncate">{{ $inquiry->title }}</span>
                                    <p class="text-xs text-text-muted line-clamp-1 opacity-70">{{ Str::limit($inquiry->content, 80) }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 mt-3 sm:mt-0 ml-auto sm:ml-4">
                                <span class="text-[11px] text-gray-400 font-medium shrink-0">{{ $inquiry->created_at->format('Y.m.d') }}</span>
                                <span class="material-symbols-outlined text-gray-400 transition-transform duration-300">expand_more</span>
                            </div>
                        </button>
                        
                        {{-- 문의 내용 및 답변 영역 ✨ --}}
                        <div class="hidden bg-gray-50/50 px-8 py-8 border-t border-gray-50 animate-in slide-in-from-top-2 duration-300">
                            {{-- 질문 --}}
                            <div class="mb-8">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="size-6 rounded-full bg-text-main text-white flex items-center justify-center text-[10px] font-black">Q</span>
                                    <span class="text-xs font-bold text-text-main">나의 문의 내용</span>
                                </div>
                                <p class="text-sm text-text-main leading-relaxed whitespace-pre-wrap pl-8">{{ $inquiry->content }}</p>
                            </div>

                            {{-- 답변 --}}
                            @if($inquiry->answer)
                            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm relative">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="size-6 rounded-full bg-primary text-white flex items-center justify-center text-[10px] font-black shadow-sm shadow-primary/20">A</span>
                                    <span class="text-xs font-bold text-primary">관리자 답변</span>
                                    <span class="text-[10px] text-gray-400 font-medium ml-auto">{{ $inquiry->answered_at->format('Y.m.d H:i') }}</span>
                                </div>
                                <p class="text-sm text-text-main leading-relaxed whitespace-pre-wrap pl-8">{{ $inquiry->answer }}</p>
                            </div>
                            @else
                            <div class="bg-gray-100/50 p-6 rounded-2xl border border-dashed border-gray-200 text-center">
                                <p class="text-xs text-gray-400 font-medium">문의하신 내용을 확인 중입니다. 잠시만 기다려 주세요! ✨</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- 페이징 --}}
                <div class="mt-12">
                    {{ $inquiries->links() }}
                </div>
                @else
                <div class="flex flex-col items-center justify-center py-24 text-center border border-gray-100 rounded-3xl bg-gray-50/50">
                    <div class="size-20 rounded-full bg-white flex items-center justify-center mx-auto mb-6 shadow-sm">
                        <span class="material-symbols-outlined text-4xl text-gray-200">chat_bubble</span>
                    </div>
                    <p class="text-text-muted font-bold text-lg">문의 내역이 없습니다.</p>
                    <p class="text-xs text-text-muted mt-2 mb-10">궁금한 점이 있으시면 언제든 문의해 주세요!</p>
                    <button onclick="openModal(document.getElementById('inquiryWriteModal'))" class="px-10 py-3.5 bg-text-main text-white text-sm font-black rounded-2xl hover:bg-primary transition-all shadow-lg shadow-gray-200 active:scale-95">문의 작성하기</button>
                </div>
                @endif
            </div>
        </div>
    </div>
</main>

{{-- 1:1 문의 작성 모달 ✨ --}}
<div id="inquiryWriteModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="relative bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-black text-text-main">1:1 문의하기</h3>
            <button type="button" onclick="closeModal(document.getElementById('inquiryWriteModal'))" class="size-8 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-400 transition-colors">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>
        </div>
        <form id="inquiryForm" class="p-8 space-y-6">
            @csrf
            <div>
                <label for="inquiry_title" class="block text-xs font-black text-text-muted uppercase mb-2 tracking-tighter">문의 제목</label>
                <input type="text" id="inquiry_title" name="title" placeholder="제목을 입력해 주세요" 
                       class="w-full h-12 px-4 rounded-xl border-gray-200 focus:border-primary focus:ring-primary/20 text-sm font-medium transition-all" required>
            </div>
            <div>
                <label for="inquiry_content" class="block text-xs font-black text-text-muted uppercase mb-2 tracking-tighter">문의 내용</label>
                <textarea id="inquiry_content" name="content" rows="6" placeholder="문의하실 내용을 상세히 남겨주시면 정성껏 답변해 드릴게요! ✨" 
                          class="w-full px-4 py-4 rounded-xl border-gray-200 focus:border-primary focus:ring-primary/20 text-sm font-medium transition-all resize-none" required></textarea>
            </div>
            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeModal(document.getElementById('inquiryWriteModal'))" class="flex-1 h-14 bg-gray-100 text-text-muted text-sm font-black rounded-2xl hover:bg-gray-200 transition-all">취소</button>
                <button type="submit" class="flex-1 h-14 bg-primary text-white text-sm font-black rounded-2xl hover:bg-red-600 transition-all shadow-lg shadow-primary/30">문의 등록하기</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Modal Functions ✨
    function openModal(modal) {
        if (!modal) return;
        modal.style.display = "flex";
        modal.classList.remove("hidden");
        document.body.style.overflow = "hidden";
    }
    function closeModal(modal) {
        if (!modal) return;
        modal.style.display = "none";
        modal.classList.add("hidden");
        document.body.style.overflow = "";
    }

    function toggleInquiry(button) {
        const content = button.nextElementSibling;
        const icon = button.querySelector('.material-symbols-outlined:last-child');
        
        $('.inquiry-item > div:not(.hidden)').not(content).addClass('hidden');
        $('.inquiry-item .material-symbols-outlined:last-child').not(icon).css('transform', 'rotate(0deg)');

        content.classList.toggle('hidden');
        icon.style.transform = content.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
    }

    $(document).ready(function() {
        // 모달 외부 클릭 시 닫기
        $('#inquiryWriteModal').on('click', function(e) {
            if (e.target === this) closeModal(this);
        });

        /**
         * 문의 등록 AJAX 처리 ✨
         */
        $('#inquiryForm').on('submit', function(e) {
            e.preventDefault();
            
            const $form = $(this);
            const $submitBtn = $form.find('button[type="submit"]');
            
            $submitBtn.prop('disabled', true).text('등록 중...');

            $.ajax({
                url: "{{ route('mypage.inquiry.store') }}",
                method: 'POST',
                data: $form.serialize(),
                success: function(response) {
                    if (response.status === 'success') {
                        showToast(response.message, 'check_circle', 'bg-primary');
                        closeModal(document.getElementById('inquiryWriteModal'));
                        setTimeout(() => location.reload(), 1000);
                    }
                },
                error: function(xhr) {
                    const msg = xhr.responseJSON ? xhr.responseJSON.message : '등록에 실패했습니다.';
                    showToast(msg, 'error', 'bg-red-500');
                    $submitBtn.prop('disabled', false).text('문의 등록하기');
                }
            });
        });
    });
</script>
@endpush
