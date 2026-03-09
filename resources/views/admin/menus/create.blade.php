@extends('layouts.admin')

@section('page_title', '새 메뉴 등록')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <h3 class="text-2xl font-extrabold text-text-main">새 메뉴 등록</h3>
        <a href="{{ route('admin.menus.index') }}" class="text-sm font-bold text-text-muted hover:text-primary transition-colors flex items-center gap-1">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            목록으로
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
        <form action="{{ route('admin.menus.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-black text-text-muted uppercase mb-2 ml-1">부모 메뉴</label>
                    <select name="parent_id" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none appearance-none transition-all">
                        <option value="">최상위 메뉴</option>
                        @foreach($parentMenus as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-black text-text-muted uppercase mb-2 ml-1">메뉴 그룹 (선택)</label>
                    <input type="text" name="group_name" value="{{ old('group_name') }}" placeholder="예: 쇼핑몰 관리" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-black text-text-muted uppercase mb-2 ml-1">메뉴 명칭 *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="예: 상품 관리" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-black text-text-muted uppercase mb-2 ml-1">아이콘 (Material Icon)</label>
                    <input type="text" name="icon" value="{{ old('icon') }}" placeholder="예: inventory_2" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
                </div>
            </div>

            <div>
                <label class="block text-xs font-black text-text-muted uppercase mb-2 ml-1">라우트명 (Route Name)</label>
                <input type="text" name="route" value="{{ old('route') }}" placeholder="예: admin.products.index" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
            </div>

            <div>
                <label class="block text-xs font-black text-text-muted uppercase mb-2 ml-1">권한 체크 키 (Permission Key)</label>
                <input type="text" name="permission_key" value="{{ old('permission_key') }}" placeholder="예: products" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-black text-text-muted uppercase mb-2 ml-1">정렬 순서</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" required class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-black text-text-muted uppercase mb-2 ml-1">상태</label>
                    <div class="flex gap-4">
                        <label class="flex-1 flex items-center justify-center gap-2 p-4 bg-gray-50 rounded-2xl border border-gray-200 cursor-pointer hover:bg-white hover:border-primary transition-all group">
                            <input type="radio" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }} class="w-4 h-4 text-primary border-gray-300 focus:ring-primary/20">
                            <span class="text-sm font-bold text-text-muted group-hover:text-text-main">활성</span>
                        </label>
                        <label class="flex-1 flex items-center justify-center gap-2 p-4 bg-gray-50 rounded-2xl border border-gray-200 cursor-pointer hover:bg-white hover:border-red-500 transition-all group">
                            <input type="radio" name="is_active" value="0" {{ old('is_active') == '0' ? 'checked' : '' }} class="w-4 h-4 text-red-500 border-gray-300 focus:ring-red-500/20">
                            <span class="text-sm font-bold text-text-muted group-hover:text-text-main">비활성</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full py-5 bg-primary text-white text-lg font-black rounded-2xl shadow-xl shadow-primary/30 hover:bg-red-600 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">check_circle</span>
                    메뉴 등록 완료
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
