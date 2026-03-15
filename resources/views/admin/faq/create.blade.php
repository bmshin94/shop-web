@extends('layouts.admin')

@section('title', 'FAQ 등록 - Active Women 관리자')

@section('content')
<div class="px-8 py-8 max-w-4xl">
    <div class="mb-10">
        <h1 class="text-3xl font-extrabold text-text-main tracking-tight">새 FAQ 등록 📄✨</h1>
        <p class="mt-2 text-sm text-text-muted">자주 묻는 질문과 답변을 정성껏 등록해 주세요.</p>
    </div>

    <form action="{{ route('admin.faqs.store') }}" method="POST" class="space-y-8 bg-white p-10 rounded-3xl border border-gray-100 shadow-sm">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
                <label class="text-sm font-bold text-text-main">카테고리 <span class="text-primary">*</span></label>
                <select name="category" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all appearance-none">
                    <option value="member">가입/정보</option>
                    <option value="order">주문/결제</option>
                    <option value="delivery">배송</option>
                    <option value="return">반품/교환</option>
                    <option value="product">상품문의</option>
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-bold text-text-main">정렬 순서</label>
                <input type="number" name="sort_order" value="0" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all">
            </div>
        </div>

        <div class="space-y-2">
            <label class="text-sm font-bold text-text-main">질문 (Question) <span class="text-primary">*</span></label>
            <input type="text" name="question" placeholder="질문 내용을 입력해 주세요" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all">
        </div>

        <div class="space-y-2">
            <label class="text-sm font-bold text-text-main">답변 (Answer) <span class="text-primary">*</span></label>
            <textarea name="answer" rows="10" placeholder="친절하고 상세한 답변을 입력해 주세요 ✨" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all"></textarea>
        </div>

        <div class="flex items-center gap-10 pt-4">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="hidden" name="is_visible" value="0">
                <input type="checkbox" name="is_visible" value="1" checked class="w-5 h-5 text-primary border-gray-300 rounded focus:ring-primary/20">
                <span class="text-sm font-bold text-text-main">즉시 노출하기 ✨</span>
            </label>
        </div>

        <div class="flex items-center justify-end gap-4 pt-10 border-t border-gray-50">
            <a href="{{ route('admin.faqs.index') }}" class="px-8 py-4 bg-gray-100 text-text-muted font-bold rounded-2xl hover:bg-gray-200 transition-all">취소</a>
            <button type="submit" class="px-10 py-4 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/20 hover:scale-105 transition-all">
                등록 완료! 💖
            </button>
        </div>
    </form>
</div>
@endsection
