@extends('layouts.admin')

@section('title', '1:1 문의 관리')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-black text-text-main tracking-tight">1:1 문의 관리</h2>
            <p class="text-sm text-text-muted mt-1">고객님들의 소중한 문의에 정성껏 답변해 주세요. ✨</p>
        </div>
    </div>

    {{-- Filter & Search --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.inquiries.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="w-full md:w-48">
                <select name="status" class="w-full h-11 rounded-xl border-gray-200 text-sm font-bold focus:border-primary focus:ring-primary/20">
                    <option value="">전체 상태</option>
                    <option value="답변대기" {{ request('status') === '답변대기' ? 'selected' : '' }}>답변대기</option>
                    <option value="답변완료" {{ request('status') === '답변완료' ? 'selected' : '' }}>답변완료</option>
                </select>
            </div>
            <div class="flex-1 relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="제목 또는 작성자 이름으로 검색" 
                       class="w-full h-11 pl-12 pr-4 rounded-xl border-gray-200 text-sm font-medium focus:border-primary focus:ring-primary/20">
            </div>
            <button type="submit" class="h-11 px-8 bg-text-main text-white font-black rounded-xl hover:bg-black transition-all">검색</button>
            <a href="{{ route('admin.inquiries.index') }}" class="h-11 px-6 bg-gray-100 text-text-muted font-black rounded-xl hover:bg-gray-200 transition-all flex items-center justify-center">초기화</a>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-6 py-4 font-black text-text-muted uppercase tracking-tighter w-32">상태</th>
                    <th class="px-6 py-4 font-black text-text-muted uppercase tracking-tighter">문의 제목</th>
                    <th class="px-6 py-4 font-black text-text-muted uppercase tracking-tighter w-32">작성자</th>
                    <th class="px-6 py-4 font-black text-text-muted uppercase tracking-tighter w-44">등록일</th>
                    <th class="px-6 py-4 font-black text-text-muted uppercase tracking-tighter w-24 text-right">관리</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($inquiries as $inquiry)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($inquiry->status === '답변완료')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black bg-green-50 text-green-600 border border-green-100 whitespace-nowrap">답변완료</span>
                        @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black bg-amber-50 text-amber-600 border border-amber-100 whitespace-nowrap">답변대기</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.inquiries.show', $inquiry) }}" class="font-bold text-text-main hover:text-primary transition-colors block truncate max-w-md">
                            {{ $inquiry->title }}
                        </a>
                    </td>
                    <td class="px-6 py-4 font-medium text-text-main whitespace-nowrap">{{ $inquiry->member->name }}</td>
                    <td class="px-6 py-4 text-text-muted whitespace-nowrap">{{ $inquiry->created_at->format('Y.m.d H:i') }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.inquiries.show', $inquiry) }}" class="p-2 hover:bg-white rounded-lg transition-colors inline-block text-text-muted hover:text-primary">
                            <span class="material-symbols-outlined text-[20px]">edit_note</span>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-20 text-center">
                        <div class="flex flex-col items-center">
                            <span class="material-symbols-outlined text-5xl text-gray-200 mb-4">chat_bubble</span>
                            <p class="text-text-muted font-bold">등록된 문의 내역이 없습니다.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($inquiries->hasPages())
        <div class="px-6 py-4 border-t border-gray-50">
            {{ $inquiries->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
