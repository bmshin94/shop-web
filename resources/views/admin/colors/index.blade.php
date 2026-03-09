@extends('layouts.admin')

@section('page_title', '색상 관리')

@section('content')
<div class="space-y-6 lg:space-y-8">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <h3 class="text-2xl font-extrabold text-text-main">상품 색상 관리</h3>
            <p class="mt-1 text-[12px] font-bold text-text-muted">상품에 적용할 색상 옵션을 등록하고 관리합니다.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Left: Color List (Wider section) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-50 bg-gray-50/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <h4 class="text-sm font-black text-text-main uppercase tracking-tight">등록된 색상 목록 ({{ $colors->count() }})</h4>

                    <!-- Search Filter  -->
                    <form action="{{ route('admin.colors.index') }}" method="GET" class="relative group">
                        <div class="flex items-center rounded-xl bg-white border border-gray-200 px-3 py-1.5 transition-all focus-within:ring-4 focus-within:ring-primary/10 focus-within:border-primary shadow-sm">
                            <span class="material-symbols-outlined text-gray-400 text-[20px]">search</span>
                            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="색상명 또는 HEX 검색" 
                                class="border-none bg-transparent px-2 text-xs font-bold text-text-main placeholder:text-gray-400 focus:ring-0 w-40 sm:w-48">
                            @if($search)
                                <a href="{{ route('admin.colors.index') }}" class="text-gray-400 hover:text-primary transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">close</span>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-[11px] font-black text-text-muted uppercase border-b border-gray-100">
                                <th class="px-6 py-4">색상</th>
                                <th class="px-6 py-4">색상명</th>
                                <th class="px-6 py-4">HEX 코드</th>
                                <th class="px-6 py-4 text-right">관리</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($colors as $color)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="size-10 rounded-2xl ring-2 ring-white shadow-md" style="background-color: {{ $color->hex_code }}"></div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-text-main">{{ $color->name }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-bold text-text-muted font-mono bg-gray-100 px-2 py-1 rounded-md border border-gray-200">{{ $color->hex_code }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.colors.edit', $color) }}" class="p-2 text-text-muted hover:text-primary hover:bg-primary-light rounded-xl transition-all" title="수정">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                        <form action="{{ route('admin.colors.destroy', $color) }}" method="POST" class="js-confirm-submit inline" data-confirm-title="색상 삭제" data-confirm-message="'{{ $color->name }}' 색상을 삭제하시겠습니까? 해당 색상을 사용하는 상품들에서도 옵션이 제거됩니다." data-confirm-text="삭제하기">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-text-muted hover:text-red-600 hover:bg-red-50 rounded-xl transition-all" title="삭제">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="material-symbols-outlined text-4xl text-gray-200 mb-2">palette</span>
                                        <p class="text-sm font-bold text-gray-400">등록된 색상이 없습니다.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right: Color Registration Form (Sticky section) -->
        <div class="lg:col-span-1">
            <div class="sticky top-8 z-10">
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 lg:p-8">
                    <h4 class="text-lg font-bold text-text-main mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">add_circle</span> 새 색상 등록
                    </h4>
                    
                    <form action="{{ route('admin.colors.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-text-main">색상 이름 <span class="text-primary">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="예: 미드나잇 블랙" 
                                class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all @error('name') border-red-500 bg-red-50/30 @enderror">
                            @error('name') <p class="text-[11px] text-red-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-text-main">HEX 코드 <span class="text-primary">*</span></label>
                            <div class="flex gap-2">
                                <div class="relative flex-1">
                                    <input type="text" id="hex_input" name="hex_code" value="{{ old('hex_code', '#000000') }}" placeholder="#000000" 
                                        class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-mono focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all @error('hex_code') border-red-500 bg-red-50/30 @enderror">
                                </div>
                                <input type="color" id="color_picker" value="{{ old('hex_code', '#000000') }}" 
                                    class="size-[52px] rounded-2xl border border-gray-200 p-1 cursor-pointer bg-white">
                            </div>
                            @error('hex_code') <p class="text-[11px] text-red-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                            <p class="text-[10px] text-text-muted font-medium mt-1">컬러 피커를 사용하거나 직접 코드를 입력하세요.</p>
                        </div>

                        <button type="submit" class="w-full py-4 bg-primary text-white font-black rounded-2xl shadow-lg shadow-primary/20 hover:bg-red-600 transition-all flex items-center justify-center gap-2 mt-4">
                            <span class="material-symbols-outlined text-[20px]">palette</span> 색상 저장하기
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hexInput = document.getElementById('hex_input');
        const colorPicker = document.getElementById('color_picker');

        // Color Picker 선택 시 자동으로 # 포함 대문자로 입력 
        colorPicker.addEventListener('input', function() {
            hexInput.value = this.value.toUpperCase();
        });

        // 직접 입력 시에도 # 자동 보정 
        hexInput.addEventListener('input', function() {
            let val = this.value;
            if (val && !val.startsWith('#')) val = '#' + val;
            this.value = val.toUpperCase();
            
            if (/^#[0-9A-F]{6}$/i.test(val)) {
                colorPicker.value = val;
            }
        });
    });
</script>
@endpush
