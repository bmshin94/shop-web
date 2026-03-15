@extends('layouts.admin')

@section('title', '공지사항 관리 - Active Women 관리자')

@section('content')
<div class="px-8 py-8">
    <div class="flex items-center justify-between mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-text-main tracking-tight">공지사항 관리 📢✨</h1>
            <p class="mt-2 text-sm text-text-muted">우리 고객님들께 전할 소중한 소식들을 관리합니다.</p>
        </div>
        <a href="{{ route('admin.notices.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/20 hover:scale-105 transition-all">
            <span class="material-symbols-outlined text-[20px]">add</span>
            새 공지사항 등록 📢
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
                    <th class="px-8 py-5 text-xs font-bold text-text-muted uppercase tracking-wider">구분</th>
                    <th class="px-8 py-5 text-xs font-bold text-text-muted uppercase tracking-wider">중요</th>
                    <th class="px-8 py-5 text-xs font-bold text-text-muted uppercase tracking-wider">제목</th>
                    <th class="px-8 py-5 text-xs font-bold text-text-muted uppercase tracking-wider">노출여부</th>
                    <th class="px-8 py-5 text-xs font-bold text-text-muted uppercase tracking-wider">게시일</th>
                    <th class="px-8 py-5 text-xs font-bold text-text-muted uppercase tracking-wider">관리</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($notices as $notice)
                <tr class="hover:bg-gray-50/50 transition-colors {{ $notice->is_important ? 'bg-primary/5' : '' }}">
                    <td class="px-8 py-5 text-sm font-bold text-text-muted">{{ $notice->type }}</td>
                    <td class="px-8 py-5">
                        @if($notice->is_important)
                        <span class="px-3 py-1 bg-primary text-white text-[10px] font-bold rounded-full">중요</span>
                        @else
                        <span class="text-gray-300">-</span>
                        @endif
                    </td>
                    <td class="px-8 py-5 text-sm font-bold text-text-main">
                        {{ $notice->title }}
                    </td>
                    <td class="px-8 py-5">
                        @if($notice->is_visible)
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">노출중</span>
                        @else
                        <span class="px-3 py-1 bg-gray-100 text-gray-500 text-xs font-bold rounded-full">비노출</span>
                        @endif
                    </td>
                    <td class="px-8 py-5 text-sm text-text-muted">
                        {{ $notice->published_at ? $notice->published_at->format('Y-m-d') : '-' }}
                    </td>
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.notices.edit', $notice) }}" class="p-2 text-text-muted hover:text-primary transition-colors">
                                <span class="material-symbols-outlined">edit</span>
                            </a>
                            <form action="{{ route('admin.notices.destroy', $notice) }}" method="POST" onsubmit="return confirm('공지사항을 삭제할까요? 😢');">
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
                    <td colspan="6" class="px-8 py-20 text-center text-text-muted">
                        등록된 공지사항이 없어요! ✨ 
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($notices->hasPages())
        <div class="px-8 py-6 border-t border-gray-50">
            {{ $notices->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
