@extends('layouts.admin')

@section('title', 'FAQ 관리 - Active Women 관리자')

@section('content')
<div class="px-8 py-8">
    <div class="flex items-center justify-between mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-text-main tracking-tight">FAQ 관리 ✨</h1>
            <p class="mt-2 text-sm text-text-muted">자주 묻는 질문(FAQ)을 관리합니다.</p>
        </div>
        <a href="{{ route('admin.faqs.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/20 hover:scale-105 transition-all">
            <span class="material-symbols-outlined text-[20px]">add</span>
            새 FAQ 등록 📄
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
                    <th class="px-8 py-5 text-xs font-bold text-text-muted uppercase tracking-wider">정렬</th>
                    <th class="px-8 py-5 text-xs font-bold text-text-muted uppercase tracking-wider">카테고리</th>
                    <th class="px-8 py-5 text-xs font-bold text-text-muted uppercase tracking-wider">질문</th>
                    <th class="px-8 py-5 text-xs font-bold text-text-muted uppercase tracking-wider">노출여부</th>
                    <th class="px-8 py-5 text-xs font-bold text-text-muted uppercase tracking-wider">등록일</th>
                    <th class="px-8 py-5 text-xs font-bold text-text-muted uppercase tracking-wider">관리</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($faqs as $faq)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-8 py-5 text-sm text-text-muted">{{ $faq->sort_order }}</td>
                    <td class="px-8 py-5">
                        <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded-full">
                            {{ [
                                'member' => '가입/정보',
                                'order' => '주문/결제',
                                'delivery' => '배송',
                                'return' => '반품/교환',
                                'product' => '상품문의'
                            ][$faq->category] ?? $faq->category }}
                        </span>
                    </td>
                    <td class="px-8 py-5 text-sm font-bold text-text-main">{{ $faq->question }}</td>
                    <td class="px-8 py-5">
                        @if($faq->is_visible)
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">노출중</span>
                        @else
                        <span class="px-3 py-1 bg-gray-100 text-gray-500 text-xs font-bold rounded-full">비노출</span>
                        @endif
                    </td>
                    <td class="px-8 py-5 text-sm text-text-muted">{{ $faq->created_at->format('Y-m-d') }}</td>
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.faqs.edit', $faq) }}" class="p-2 text-text-muted hover:text-primary transition-colors">
                                <span class="material-symbols-outlined">edit</span>
                            </a>
                            <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" onsubmit="return confirm('FAQ를 삭제할까요? 😢');">
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
                        등록된 FAQ가 없습니다. ✨
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($faqs->hasPages())
        <div class="px-8 py-6 border-t border-gray-50">
            {{ $faqs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
