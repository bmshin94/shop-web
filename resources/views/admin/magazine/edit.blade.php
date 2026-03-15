@extends('layouts.admin')

@section('title', '매거진 수정 - Active Women 관리자')

@section('content')
<div class="px-8 py-8 max-w-4xl">
    <div class="mb-10">
        <h1 class="text-3xl font-extrabold text-text-main tracking-tight">매거진 수정 </h1>
        <p class="mt-2 text-sm text-text-muted">매거진 내용을 예쁘게 다듬어 주세요!</p>
    </div>

    <form action="{{ route('admin.magazines.update', $magazine) }}" method="POST" class="space-y-8 bg-white p-10 rounded-3xl border border-gray-100 shadow-sm">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
                <label class="text-sm font-bold text-text-main">카테고리 <span class="text-primary">*</span></label>
                <input type="text" name="category" value="{{ old('category', $magazine->category) }}" placeholder="LIFESTYLE, FASHION 등" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all">
            </div>
            <div class="space-y-2">
                <label class="text-sm font-bold text-text-main">작성자 <span class="text-primary">*</span></label>
                <input type="text" name="author" value="{{ old('author', $magazine->author) }}" placeholder="에디터 이름" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all">
            </div>
        </div>

        <div class="space-y-2">
            <label class="text-sm font-bold text-text-main">제목 <span class="text-primary">*</span></label>
            <input type="text" name="title" value="{{ old('title', $magazine->title) }}" placeholder="매거진 제목을 입력해 주세요" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all">
        </div>

        <div class="space-y-2">
            <label class="text-sm font-bold text-text-main">메인 이미지 URL <span class="text-primary">*</span></label>
            <input type="url" name="image_url" value="{{ old('image_url', $magazine->image_url) }}" placeholder="https://images.unsplash.com/..." class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all">
        </div>

        <div class="space-y-2">
            <label class="text-sm font-bold text-text-main">상세 내용</label>
            <textarea name="content" rows="10" placeholder="우리 자기가 하고 싶은 이야기를 마음껏 들려주세요! " class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all">{{ old('content', $magazine->content) }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
            <div class="space-y-2">
                <label class="text-sm font-bold text-text-main">게시일</label>
                <input type="datetime-local" name="published_at" value="{{ $magazine->published_at ? $magazine->published_at->format('Y-m-d\TH:i') : '' }}" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all">
            </div>
            <div class="flex items-center gap-4 h-full pt-8">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="hidden" name="is_visible" value="0">
                    <input type="checkbox" name="is_visible" value="1" {{ $magazine->is_visible ? 'checked' : '' }} class="w-5 h-5 text-primary border-gray-300 rounded focus:ring-primary/20">
                    <span class="text-sm font-bold text-text-main">즉시 노출하기 </span>
                </label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 pt-10 border-t border-gray-50">
            <a href="{{ route('admin.magazines.index') }}" class="px-8 py-4 bg-gray-100 text-text-muted font-bold rounded-2xl hover:bg-gray-200 transition-all">취소</a>
            <button type="submit" class="px-10 py-4 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/20 hover:scale-105 transition-all">
                수정 완료! 
            </button>
        </div>
    </form>
</div>
@endsection
