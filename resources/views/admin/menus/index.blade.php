@extends('layouts.admin')

@section('page_title', '관리자 메뉴 관리')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-extrabold text-text-main">관리자 메뉴 관리</h3>
            <p class="text-sm text-text-muted mt-1 font-medium">사이드바 메뉴 구성 및 권한 설정을 관리합니다.</p>
        </div>
        <a href="{{ route('admin.menus.create') }}" class="px-6 py-3 bg-primary text-white text-sm font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-red-600 transition-all flex items-center gap-2">
            <span class="material-symbols-outlined">add_circle</span>
            새 메뉴 추가
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="py-4 px-6 text-xs font-black text-text-muted uppercase">그룹 / 이름</th>
                        <th class="py-4 px-6 text-xs font-black text-text-muted uppercase">아이콘</th>
                        <th class="py-4 px-6 text-xs font-black text-text-muted uppercase">라우트 / 권한키</th>
                        <th class="py-4 px-6 text-xs font-black text-text-muted uppercase text-center">순서</th>
                        <th class="py-4 px-6 text-xs font-black text-text-muted uppercase text-center">상태</th>
                        <th class="py-4 px-6 text-xs font-black text-text-muted uppercase text-center">관리</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($menus as $menu)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-5 px-6">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-primary uppercase tracking-tighter mb-0.5">{{ $menu->group_name ?? '기본' }}</span>
                                    <span class="text-sm font-bold text-text-main">{{ $menu->name }}</span>
                                </div>
                            </td>
                            <td class="py-5 px-6 text-center">
                                <div class="size-10 rounded-xl bg-gray-100 flex items-center justify-center text-text-muted mx-auto">
                                    <span class="material-symbols-outlined">{{ $menu->icon }}</span>
                                </div>
                            </td>
                            <td class="py-5 px-6">
                                <div class="flex flex-col">
                                    <span class="text-[11px] font-mono font-bold text-blue-600 mb-0.5">{{ $menu->route ?? '-' }}</span>
                                    <span class="text-[11px] font-mono font-bold text-amber-600">{{ $menu->permission_key ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="py-5 px-6 text-center">
                                <span class="px-2.5 py-1 bg-gray-100 rounded-lg text-xs font-black text-text-muted">{{ $menu->sort_order }}</span>
                            </td>
                            <td class="py-5 px-6 text-center">
                                @if($menu->is_active)
                                    <span class="inline-flex px-2 py-1 rounded-md text-[10px] font-black bg-green-50 text-green-600 border border-green-100">활성</span>
                                @else
                                    <span class="inline-flex px-2 py-1 rounded-md text-[10px] font-black bg-gray-100 text-gray-400 border border-gray-200">비활성</span>
                                @endif
                            </td>
                            <td class="py-5 px-6">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.menus.edit', $menu) }}" class="p-2 hover:bg-primary-light hover:text-primary rounded-lg transition-colors text-text-muted">
                                        <span class="material-symbols-outlined">edit</span>
                                    </a>
                                    <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="js-confirm-submit" data-confirm-message="'{{ $menu->name }}' 메뉴를 삭제하시겠습니까?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 hover:bg-red-50 hover:text-red-600 rounded-lg transition-colors text-text-muted">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @foreach($menu->children as $child)
                            <tr class="hover:bg-gray-50/30 transition-colors bg-gray-50/10">
                                <td class="py-4 px-6 pl-12">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-gray-300 text-[18px]">subheader</span>
                                        <span class="text-sm font-medium text-text-main">{{ $child->name }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="material-symbols-outlined text-[18px] text-text-muted">{{ $child->icon }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-mono text-blue-500">{{ $child->route ?? '-' }}</span>
                                        <span class="text-[10px] font-mono text-amber-500">{{ $child->permission_key ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="text-[11px] font-bold text-text-muted">{{ $child->sort_order }}</span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    @if($child->is_active)
                                        <span class="text-[10px] font-bold text-green-500">활성</span>
                                    @else
                                        <span class="text-[10px] font-bold text-gray-400">비활성</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.menus.edit', $child) }}" class="p-1.5 hover:text-primary transition-colors text-text-muted">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </a>
                                        <form action="{{ route('admin.menus.destroy', $child) }}" method="POST" class="js-confirm-submit" data-confirm-message="'{{ $child->name }}' 메뉴를 삭제하시겠습니까?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 hover:text-red-600 transition-colors text-text-muted">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
