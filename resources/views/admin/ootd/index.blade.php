@extends('layouts.admin')

@section('title', 'OOTD 관리 - Active Women 관리자')

@section('content')
<div class="px-8 py-8">
    <div class="flex items-center justify-between mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-text-main tracking-tight">OOTD 관리 📸✨</h1>
            <p class="mt-2 text-sm text-text-muted">고객님들의 멋진 스타일링(OOTD) 콘텐츠를 관리합니다.</p>
        </div>
        <a href="{{ route('admin.ootds.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/20 hover:scale-105 transition-all">
            <span class="material-symbols-outlined text-[20px]">add</span>
            새 OOTD 등록 📸
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
                    <th class="px-8 py-5 text-xs font-bold text-text-muted uppercase tracking-wider">작성자</th>
                    <th class="px-8 py-5 text-xs font-bold text-text-muted uppercase tracking-wider">좋아요</th>
                    <th class="px-8 py-5 text-xs font-bold text-text-muted uppercase tracking-wider">노출여부</th>
                    <th class="px-8 py-5 text-xs font-bold text-text-muted uppercase tracking-wider">등록일</th>
                    <th class="px-8 py-5 text-xs font-bold text-text-muted uppercase tracking-wider">관리</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($ootds as $ootd)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-8 py-5">
                        <img src="{{ $ootd->image_url }}" class="w-12 h-16 object-cover rounded-lg border border-gray-100">
                    </td>
                    <td class="px-8 py-5 text-sm font-bold text-text-main">
                        {{ $ootd->member ? $ootd->member->name : '탈퇴 회원' }}
                        <div class="text-[10px] text-text-muted font-normal">{{ $ootd->member ? '@' . ($ootd->member->username ?? $ootd->member->email) : '' }}</div>
                    </td>
                    <td class="px-8 py-5 text-sm font-bold text-primary">
                        <div class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">favorite</span>
                            {{ number_format($ootd->likes_count) }}
                        </div>
                    </td>
                    <td class="px-8 py-5">
                        @if($ootd->is_visible)
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">노출중</span>
                        @else
                        <span class="px-3 py-1 bg-gray-100 text-gray-500 text-xs font-bold rounded-full">비노출</span>
                        @endif
                    </td>
                    <td class="px-8 py-5 text-sm text-text-muted">
                        {{ $ootd->created_at->format('Y-m-d') }}
                    </td>
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.ootds.edit', $ootd) }}" class="p-2 text-text-muted hover:text-primary transition-colors">
                                <span class="material-symbols-outlined">edit</span>
                            </a>
                            <form action="{{ route('admin.ootds.destroy', $ootd) }}" method="POST" onsubmit="return confirm('OOTD를 삭제할까요? 😢');">
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
                        등록된 OOTD가 없어요! ✨ 
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($ootds->hasPages())
        <div class="px-8 py-6 border-t border-gray-50">
            {{ $ootds->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
