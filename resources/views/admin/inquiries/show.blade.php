@extends('layouts.admin')

@section('title', '문의 상세 및 답변')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.inquiries.index') }}" class="size-10 flex items-center justify-center rounded-xl bg-white border border-gray-200 text-text-muted hover:text-primary transition-all">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h2 class="text-2xl font-black text-text-main tracking-tight">문의 상세 및 답변</h2>
            <p class="text-sm text-text-muted mt-1">고객님의 문의 내용을 확인하고 답변을 작성해주세요. 📝</p>
        </div>
    </div>

    {{-- Inquiry Content --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex justify-between items-start">
            <div>
                @if($inquiry->status === '답변완료')
                <span class="inline-flex px-3 py-1 rounded-full text-[11px] font-black bg-green-50 text-green-600 border border-green-100 mb-4 uppercase">답변완료</span>
                @else
                <span class="inline-flex px-3 py-1 rounded-full text-[11px] font-black bg-amber-50 text-amber-600 border border-amber-100 mb-4 uppercase">답변대기</span>
                @endif
                <h3 class="text-xl font-black text-text-main">{{ $inquiry->title }}</h3>
            </div>
            <div class="text-right">
                <p class="text-sm font-bold text-text-main">{{ $inquiry->member->name }} 고객님</p>
                <p class="text-xs text-text-muted mt-1">{{ $inquiry->created_at->format('Y.m.d H:i') }}</p>
            </div>
        </div>
        <div class="p-8 bg-gray-50/30">
            <p class="text-sm text-text-main leading-relaxed whitespace-pre-wrap">{{ $inquiry->content }}</p>
        </div>
    </div>

    {{-- Answer Form --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
        <div class="flex items-center gap-2 mb-6">
            <span class="size-8 rounded-xl bg-primary text-white flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px]">reply</span>
            </span>
            <h4 class="text-lg font-black text-text-main">답변 작성하기</h4>
        </div>

        <form id="answerForm" class="space-y-6">
            @csrf
            @method('PATCH')
            <textarea name="answer" rows="8" placeholder="고객님께 전달할 정성스러운 답변을 입력해주세요. ✨" 
                      class="w-full px-6 py-5 rounded-2xl border-gray-200 focus:border-primary focus:ring-primary/20 text-sm font-medium transition-all resize-none" required>{{ $inquiry->answer }}</textarea>
            
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.inquiries.index') }}" class="h-14 px-8 bg-gray-100 text-text-muted text-sm font-black rounded-2xl hover:bg-gray-200 transition-all flex items-center">취소</a>
                <button type="submit" class="h-14 px-10 bg-text-main text-white text-sm font-black rounded-2xl hover:bg-black transition-all shadow-lg">답변 등록하기</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#answerForm').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $btn = $form.find('button[type="submit"]');
        
        $btn.prop('disabled', true).text('저장 중...');

        $.ajax({
            url: "{{ route('admin.inquiries.answer', $inquiry) }}",
            method: 'PATCH',
            data: $form.serialize(),
            success: function(response) {
                if (response.status === 'success') {
                    showToast(response.message, 'check_circle', 'bg-primary');
                    setTimeout(() => {
                        location.href = "{{ route('admin.inquiries.index') }}";
                    }, 1000);
                }
            },
            error: function(xhr) {
                showToast('답변 저장에 실패했습니다.', 'error', 'bg-red-500');
                $btn.prop('disabled', false).text('답변 등록하기');
            }
        });
    });
});
</script>
@endpush
