@extends('layouts.admin')

@section('page_title', '색상 수정')

@section('content')
<div class="space-y-6 lg:space-y-8 max-w-2xl mx-auto">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.colors.index') }}" class="flex items-center justify-center size-10 rounded-xl bg-white border border-gray-200 text-text-muted hover:text-primary hover:border-primary transition-all shadow-sm">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
        </a>
        <h3 class="text-2xl font-extrabold text-text-main">색상 정보 수정</h3>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-xl p-8 lg:p-12 relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute -top-10 -right-10 size-40 bg-primary/5 rounded-full blur-3xl pointer-events-none"></div>

        <form action="{{ route('admin.colors.update', $color) }}" method="POST" class="space-y-8 relative z-10">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div class="space-y-2">
                    <label class="text-sm font-bold text-text-main ml-1">색상 이름 <span class="text-primary">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $color->name) }}" 
                        class="w-full px-6 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-base focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all shadow-inner @error('name') border-red-500 bg-red-50/30 @enderror">
                    @error('name') <p class="text-[11px] text-red-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-text-main ml-1">색상 및 HEX 코드 <span class="text-primary">*</span></label>
                    <div class="flex items-center gap-4 p-6 bg-gray-50 rounded-2xl border border-gray-100 shadow-inner">
                        <input type="color" id="color_picker" value="{{ old('hex_code', $color->hex_code) }}" 
                            class="size-20 rounded-2xl border-4 border-white shadow-lg cursor-pointer">
                        
                        <div class="flex-1 space-y-2">
                            <div class="relative">
                                <input type="text" id="hex_input" name="hex_code" value="{{ old('hex_code', $color->hex_code) }}" 
                                    class="w-full px-5 py-4 bg-white border border-gray-200 rounded-xl text-lg font-mono font-bold focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all @error('hex_code') border-red-500 @enderror">
                            </div>
                            @error('hex_code') <p class="text-[11px] text-red-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                            <p class="text-[11px] text-text-muted font-bold ml-1 tracking-tight">수정할 색상을 선택하거나 코드를 입력해 주세요.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 py-5 bg-primary text-white text-lg font-black rounded-2xl shadow-xl shadow-primary/30 hover:bg-red-600 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-3">
                    <span class="material-symbols-outlined">save</span> 수정사항 저장하기
                </button>
                <a href="{{ route('admin.colors.index') }}" class="px-8 py-5 bg-gray-100 text-text-muted font-bold rounded-2xl hover:bg-gray-200 transition-all flex items-center justify-center">
                    취소
                </a>
            </div>
        </form>
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
