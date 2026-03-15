@extends('layouts.admin')

@section('title', '매거진 관리 - Active Women 관리자')

@section('content')
<div class="px-8 py-8">
    <div class="flex items-center justify-between mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-text-main tracking-tight">매거진 관리 ✨</h1>
            <p class="mt-2 text-sm text-text-muted">커뮤니티 페이지에 노출될 매거진 콘텐츠를 관리합니다.</p>
        </div>
        <a href="{{ route('admin.magazines.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/20 hover:scale-105 transition-all">
            <span class="material-symbols-outlined text-[20px]">add</span>
            새 매거진 등록 📖
        </a>
    </div>

    @if(session('success'))
    <div class="mb-8 p-4 bg-green-50 border border-green-100 text-green-700 rounded-2xl flex items-center gap-3">
        <span class="material-symbols-outlined">check_circle</span>
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-8 py-5 text-xs font-bold text-text-muted uppercase tracking-wider">이미지</th>
                    <th class="px-8 py-5 text-xs font-bold text-text-muted uppercase tracking-wider">카테고리</th>
                    <th class="px-8 py-5 text-xs font-bold text-text-muted uppercase tracking-wider">제목</th>
                    <th class="px-8 py-5 text-xs font-bold text-text-muted uppercase tracking-wider">작성자</th>
                    <th class="px-8 py-5 text-xs font-bold text-text-muted uppercase tracking-wider">노출여부</th>
                    <th class="px-8 py-5 text-xs font-bold text-text-muted uppercase tracking-wider">게시일</th>
                    <th class="px-8 py-5 text-xs font-bold text-text-muted uppercase tracking-wider">관리</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($magazines as $magazine)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-8 py-5">
                        <img src="{{ $magazine->image_url }}" class="w-16 h-10 object-cover rounded-lg border border-gray-100">
                    </td>
                    <td class="px-8 py-5 text-sm font-bold text-primary">{{ $magazine->category }}</td>
                    <td class="px-8 py-5 text-sm font-bold text-text-main">{{ $magazine->title }}</td>
                    <td class="px-8 py-5 text-sm text-text-muted">{{ $magazine->author }}</td>
                    <td class="px-8 py-5">
                        @if($magazine->is_visible)
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">노출중</span>
                        @else
                        <span class="px-3 py-1 bg-gray-100 text-gray-500 text-xs font-bold rounded-full">비노출</span>
                        @endif
                    </td>
                    <td class="px-8 py-5 text-sm text-text-muted">
                        {{ $magazine->published_at ? $magazine->published_at->format('Y-m-d') : '-' }}
                    </td>
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.magazines.edit', $magazine) }}" class="p-2 text-text-muted hover:text-primary transition-colors">
                                <span class="material-symbols-outlined">edit</span>
                            </a>
                            <form action="{{ route('admin.magazines.destroy', $magazine) }}" method="POST" onsubmit="return confirm('진짜 삭제할 거야? 😢');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-text-muted hover:text-red-500 transition-colors">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-8 py-20 text-center text-text-muted">
                        등록된 매거진이 없어요! 🌸 자기가 첫 매거진을 써주세요! ✨
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($magazines->hasPages())
        <div class="px-8 py-6 border-t border-gray-50">
            {{ $magazines->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
