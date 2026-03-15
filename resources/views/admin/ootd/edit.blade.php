@extends('layouts.admin')

@section('title', 'OOTD 수정 - Active Women 관리자')

@section('content')
<div class="px-8 py-8 max-w-4xl">
    <div class="mb-10">
        <h1 class="text-3xl font-extrabold text-text-main tracking-tight">OOTD 수정 </h1>
        <p class="mt-2 text-sm text-text-muted">OOTD 내용을 다듬어 주세요.</p>
    </div>

    <form action="{{ route('admin.ootds.update', $ootd) }}" method="POST" class="space-y-8 bg-white p-10 rounded-3xl border border-gray-100 shadow-sm">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
                <label class="text-sm font-bold text-text-main">작성 회원 <span class="text-primary">*</span></label>
                <select name="member_id" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all appearance-none">
                    <option value="">회원을 선택해 주세요</option>
                    @foreach($members as $member)
                    <option value="{{ $member->id }}" {{ $ootd->member_id == $member->id ? 'selected' : '' }}>{{ $member->name }} ({{ $member->email }})</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-bold text-text-main">좋아요 수</label>
                <input type="number" name="likes_count" value="{{ old('likes_count', $ootd->likes_count) }}" min="0" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all">
            </div>
        </div>

        <div class="space-y-2">
            <label class="text-sm font-bold text-text-main">이미지 URL <span class="text-primary">*</span></label>
            <input type="url" name="image_url" value="{{ old('image_url', $ootd->image_url) }}" placeholder="https://images.unsplash.com/..." class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all">
        </div>

        <div class="space-y-2">
            <label class="text-sm font-bold text-text-main">상세 내용</label>
            <textarea name="content" rows="6" placeholder="스타일링에 대한 설명을 입력해 주세요 " class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all">{{ old('content', $ootd->content) }}</textarea>
        </div>

        <div class="flex items-center gap-10 pt-4">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="hidden" name="is_visible" value="0">
                <input type="checkbox" name="is_visible" value="1" {{ $ootd->is_visible ? 'checked' : '' }} class="w-5 h-5 text-primary border-gray-300 rounded focus:ring-primary/20">
                <span class="text-sm font-bold text-text-main">즉시 노출하기 </span>
            </label>
        </div>

        <div class="flex items-center justify-end gap-4 pt-10 border-t border-gray-50">
            <a href="{{ route('admin.ootds.index') }}" class="px-8 py-4 bg-gray-100 text-text-muted font-bold rounded-2xl hover:bg-gray-200 transition-all">취소</a>
            <button type="submit" class="px-10 py-4 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/20 hover:scale-105 transition-all">
                수정 완료! 
            </button>
        </div>
    </form>
</div>
@endsection
