@extends('layouts.admin')

@section('page_title', isset($coupon) ? '쿠폰 수정' : '새 쿠폰 등록')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Back Button & Title -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.coupons.index') }}" class="size-10 flex items-center justify-center bg-white border border-gray-200 rounded-xl text-text-muted hover:text-primary transition-all shadow-sm">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <h2 class="text-xl font-black text-text-main">쿠폰 정보 수정</h2>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 lg:px-8 pt-8 flex justify-end">
            <span class="text-xs text-red-500 font-bold"><span class="text-red-500">*</span> 필수입력</span>
        </div>
        <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST" class="p-6 lg:p-8 space-y-8 pt-4">
            @csrf
            @method('PUT')

            <!-- Basic Information Section -->
            <div class="space-y-6">
                <h3 class="text-sm font-black text-primary uppercase flex items-center gap-2">
                    <span class="size-1.5 rounded-full bg-primary"></span> 기본 정보
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="name" class="text-xs font-bold text-text-muted ml-1">쿠폰명 <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $coupon->name) }}" placeholder="예: 웰컴 할인 쿠폰" 
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all">
                        @error('name') <p class="text-xs text-red-500 mt-1 ml-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="space-y-2">
                        <label for="code" class="text-xs font-bold text-text-muted ml-1">쿠폰 코드 (공란 시 자동 발급 불가)</label>
                        <input type="text" name="code" id="code" value="{{ old('code', $coupon->code) }}" placeholder="예: WELCOME2026" 
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all uppercase">
                        @error('code') <p class="text-xs text-red-500 mt-1 ml-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="description" class="text-xs font-bold text-text-muted ml-1">쿠폰 상세 설명</label>
                    <textarea name="description" id="description" rows="3" placeholder="쿠폰 사용 조건 등을 입력해 주세요." 
                              class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all">{{ old('description', $coupon->description) }}</textarea>
                </div>
            </div>

            <!-- Benefit Section -->
            <div class="space-y-6 pt-8 border-t border-gray-50">
                <h3 class="text-sm font-black text-primary uppercase flex items-center gap-2">
                    <span class="size-1.5 rounded-full bg-primary"></span> 혜택 및 조건
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="type" class="text-xs font-bold text-text-muted ml-1">쿠폰 유형 <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="type" id="type" class="w-full px-4 py-3 pr-10 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 appearance-none outline-none transition-all cursor-pointer !bg-none">
                                <option value="discount" {{ old('type', $coupon->type) == 'discount' ? 'selected' : '' }}>할인 쿠폰</option>
                                <option value="shipping" {{ old('type', $coupon->type) == 'shipping' ? 'selected' : '' }}>배송비 쿠폰</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">expand_more</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="discount_type" class="text-xs font-bold text-text-muted ml-1">할인 방식 <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="discount_type" id="discount_type" class="w-full px-4 py-3 pr-10 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 appearance-none outline-none transition-all cursor-pointer !bg-none">
                                <option value="fixed" {{ old('discount_type', $coupon->discount_type) == 'fixed' ? 'selected' : '' }}>금액 할인 (원)</option>
                                <option value="percent" {{ old('discount_type', $coupon->discount_type) == 'percent' ? 'selected' : '' }}>비율 할인 (%)</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">expand_more</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="discount_value" class="text-xs font-bold text-text-muted ml-1">할인 값 <span class="text-red-500">*</span></label>
                        <div class="relative group">
                            <input type="number" name="discount_value" id="discount_value" value="{{ old('discount_value', $coupon->discount_value) }}" 
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-black text-text-muted group-focus-within:text-primary transition-colors" id="discount-unit">원</span>
                        </div>
                        @error('discount_value') <p class="text-xs text-red-500 mt-1 ml-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="min_order_amount" class="text-xs font-bold text-text-muted ml-1">최소 주문 금액</label>
                        <div class="relative group">
                            <input type="number" name="min_order_amount" id="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount) }}" 
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-black text-text-muted group-focus-within:text-primary transition-colors">원</span>
                        </div>
                    </div>

                    <div class="space-y-2" id="max-discount-wrapper">
                        <label for="max_discount_amount" class="text-xs font-bold text-text-muted ml-1">최대 할인 한도 (비율 할인 시)</label>
                        <div class="relative group">
                            <input type="number" name="max_discount_amount" id="max_discount_amount" value="{{ old('max_discount_amount', $coupon->max_discount_amount ?? '') }}" placeholder="제한 없음"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-black text-text-muted group-focus-within:text-primary transition-colors">원</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schedule & Status Section -->
            <div class="space-y-6 pt-8 border-t border-gray-50">
                <h3 class="text-sm font-black text-primary uppercase flex items-center gap-2">
                    <span class="size-1.5 rounded-full bg-primary"></span> 기간 및 상태
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="starts_at" class="text-xs font-bold text-text-muted ml-1">사용 시작일</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-muted text-[18px] group-focus-within:text-primary transition-colors pointer-events-none">calendar_today</span>
                            <input type="text" name="starts_at" id="starts_at" value="{{ old('starts_at', isset($coupon->starts_at) ? $coupon->starts_at->format('Y-m-d') : '') }}" placeholder="시작일 선택"
                                   class="datepicker w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all">
                        </div>
                        <p class="text-[11px] text-text-muted ml-1 font-medium">미입력 시 즉시 사용 가능</p>
                    </div>

                    <div class="space-y-2">
                        <label for="ends_at" class="text-xs font-bold text-text-muted ml-1">사용 종료일</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-muted text-[18px] group-focus-within:text-primary transition-colors pointer-events-none">calendar_month</span>
                            <input type="text" name="ends_at" id="ends_at" value="{{ old('ends_at', isset($coupon->ends_at) ? $coupon->ends_at->format('Y-m-d') : '') }}" placeholder="종료일 선택"
                                   class="datepicker w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all">
                        </div>
                        <p class="text-[11px] text-text-muted ml-1 font-medium">미입력 시 기간 제한 없음</p>
                    </div>
                </div>

                <div class="pt-2">
                    <label class="relative inline-flex items-center cursor-pointer group">
                        <input type="checkbox" name="is_active" class="sr-only peer" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-2 peer-focus:ring-primary/10 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        <span class="ms-3 text-sm font-bold text-text-main group-hover:text-primary transition-colors">활성화 상태 (체크 시 즉시 사용 가능)</span>
                    </label>
                </div>
            </div>

            <!-- Submit Button Section -->
            <div class="pt-10 flex gap-3">
                <a href="{{ route('admin.coupons.index') }}" class="flex-1 py-4 bg-gray-100 text-text-muted text-sm font-black rounded-2xl hover:bg-gray-200 transition-all text-center">
                    취소
                </a>
                <button type="submit" class="flex-[2] py-4 bg-primary text-white text-sm font-black rounded-2xl hover:bg-red-600 transition-all shadow-xl shadow-primary/20 active:scale-95">
                    쿠폰 정보 수정하기
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof initGlobalDatepickers === 'function') {
            initGlobalDatepickers();
        }

        const discountTypeSelect = document.getElementById('discount_type');
        const discountUnit = document.getElementById('discount-unit');
        const maxDiscountWrapper = document.getElementById('max-discount-wrapper');

        const updateUI = () => {
            const isPercent = discountTypeSelect.value === 'percent';
            discountUnit.textContent = isPercent ? '%' : '원';
            
            if (isPercent) {
                maxDiscountWrapper.style.opacity = '1';
                maxDiscountWrapper.style.pointerEvents = 'auto';
            } else {
                maxDiscountWrapper.style.opacity = '0.5';
                maxDiscountWrapper.style.pointerEvents = 'none';
            }
        };

        discountTypeSelect.addEventListener('change', updateUI);
        updateUI(); // 초기 실행
    });
</script>
@endpush
@endsection
