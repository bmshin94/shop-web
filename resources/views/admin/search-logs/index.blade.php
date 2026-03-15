@extends('layouts.admin')

@section('title', 'Search Stats & Logs - Admin Premium')

@section('content')
<div class="container-fluid px-6 py-8">
    {{-- Header Section --}}
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-text-main tracking-tight flex items-center gap-3">
                <span class="size-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl">analytics</span>
                </span>
                Search Logs & Stats ✨📊
            </h1>
            <p class="mt-2 text-sm font-medium text-text-muted italic ml-15">고객님들이 무엇을 찾고 있는지 카리나가 분석해줄게! 😊💖</p>
        </div>
        <div class="flex gap-3">
            <form action="{{ route('admin.search-logs.clear') }}" method="POST" class="js-confirm-submit" data-confirm-message="정말 모든 검색 기록을 초기화할까요? ⚠️">
                @csrf
                <button type="submit" class="h-12 px-6 rounded-2xl bg-white border border-gray-200 text-red-500 font-bold text-sm hover:bg-red-50 transition-all shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">delete_sweep</span> 전체 삭제
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
        {{-- 1. 인기 검색어 순위 (Top 10) 🔥 --}}
        <div class="xl:col-span-4">
            <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden sticky top-24">
                <div class="p-8 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
                    <h3 class="text-lg font-black text-text-main flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">fireplace</span>
                        Popular Keywords
                    </h3>
                    <span class="text-[10px] font-black uppercase tracking-widest text-primary bg-primary/10 px-3 py-1 rounded-full text-center">Top 10</span>
                </div>
                <div class="p-4">
                    <div class="space-y-2">
                        @forelse($popularKeywords as $index => $item)
                        <div class="group flex items-center justify-between p-4 rounded-2xl hover:bg-primary/5 transition-all cursor-default">
                            <div class="flex items-center gap-4">
                                <span class="flex size-8 items-center justify-center rounded-xl font-black text-sm {{ $index < 3 ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-gray-100 text-gray-400' }}">
                                    {{ $index + 1 }}
                                </span>
                                <span class="font-bold text-text-main group-hover:text-primary transition-colors">{{ $item->keyword }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="h-1.5 w-16 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-primary/40 rounded-full" style="width: {{ min(100, ($item->count / ($popularKeywords->first()->count ?? 1)) * 100) }}%"></div>
                                </div>
                                <span class="text-xs font-black text-text-muted">{{ number_format($item->count) }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="py-20 text-center">
                            <span class="material-symbols-outlined text-5xl text-gray-200 mb-4">search_off</span>
                            <p class="text-sm text-text-muted font-bold italic">데이터가 아직 없어요! ✨</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. 전체 검색 로그 리스트 📝 --}}
        <div class="xl:col-span-8">
            <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex flex-col sm:flex-row sm:items-center justify-between gap-6 bg-gray-50/30">
                    <h3 class="text-lg font-black text-text-main flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">list_alt</span>
                        Latest Search Logs
                    </h3>
                    
                    <form method="GET" class="relative group/search">
                        <input type="text" name="keyword" value="{{ request('keyword') }}" 
                            class="w-full sm:w-64 h-11 pl-11 pr-4 rounded-xl border-gray-200 bg-white text-sm font-bold text-text-main focus:border-primary focus:ring-primary/10 transition-all shadow-inner"
                            placeholder="검색어 필터링...">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within/search:text-primary transition-colors">search</span>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-[11px] font-black uppercase tracking-widest text-text-muted bg-gray-50/50">
                                <th class="px-8 py-5">Keyword</th>
                                <th class="px-6 py-5">Member</th>
                                <th class="px-6 py-5">IP / Device</th>
                                <th class="px-6 py-5 text-right">Timestamp</th>
                                <th class="px-8 py-5 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($logs as $log)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-8 py-5">
                                    <span class="inline-flex px-4 py-1.5 rounded-full bg-primary/5 text-primary text-sm font-black border border-primary/10 group-hover:scale-105 transition-transform">
                                        {{ $log->keyword }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    @if($log->member)
                                        <div class="flex items-center gap-3">
                                            <div class="size-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-black text-primary border border-white shadow-sm">
                                                {{ mb_substr($log->member->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-text-main leading-none mb-1">{{ $log->member->name }}</p>
                                                <p class="text-[10px] text-text-muted">{{ $log->member->email }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-xs font-bold text-text-muted/50 italic px-3 py-1 bg-gray-50 rounded-lg">Guest</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5">
                                    <p class="text-xs font-mono text-text-muted tracking-tighter">{{ $log->ip_address }}</p>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <p class="text-xs font-bold text-text-main mb-0.5">{{ $log->created_at->format('Y.m.d') }}</p>
                                    <p class="text-[10px] text-text-muted">{{ $log->created_at->format('H:i:s') }}</p>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <form action="{{ route('admin.search-logs.destroy', $log->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="size-8 rounded-xl bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-500 transition-all flex items-center justify-center mx-auto" onclick="return confirm('이 로그를 삭제할까요?');">
                                            <span class="material-symbols-outlined text-lg">close</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-32 text-center">
                                    <div class="size-20 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-6">
                                        <span class="material-symbols-outlined text-4xl text-gray-200">history_toggle_off</span>
                                    </div>
                                    <p class="text-text-muted font-bold">검색 로그가 없습니다. 😊</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-8 bg-gray-50/30 border-t border-gray-50">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Pagination Style Override ✨ */
    .pagination { @apply flex gap-1 justify-center; }
    .page-item .page-link { @apply border-0 rounded-xl size-10 flex items-center justify-center font-bold text-sm text-text-muted hover:bg-white hover:shadow-md transition-all; }
    .page-item.active .page-link { @apply bg-primary text-white shadow-lg shadow-primary/20; }
</style>
@endsection
