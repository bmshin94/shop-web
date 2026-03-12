@extends('layouts.admin')

@section('page_title', isset($coupon) ? '쿠폰 수정' : '새 쿠폰 등록')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* Flatpickr Admin Custom Theme */
    .flatpickr-calendar {
        border-radius: 20px !important;
        box-shadow: 0 20px 40px -15px rgba(0,0,0,0.1) !important;
        border: 1px solid #f3f4f6 !important;
        padding: 8px !important;
    }
    .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, 
    .flatpickr-day.selected:hover, .flatpickr-day.nextMonthDay.selected, 
    .flatpickr-day.prevMonthDay.selected {
        background: #ec3713 !important;
        border-color: #ec3713 !important;
        font-weight: 700 !important;
    }
    .flatpickr-months .flatpickr-month, .flatpickr-current-month .flatpickr-monthDropdown-months {
        font-weight: 800 !important;
        color: #181211 !important;
    }
    .flatpickr-weekday {
        color: #896861 !important;
        font-weight: 700 !important;
        font-size: 11px !important;
    }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Back Button & Title -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.coupons.index') }}" class="size-10 flex items-center justify-center bg-white border border-gray-200 rounded-xl text-text-muted hover:text-primary transition-all shadow-sm">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <h2 class="text-xl font-black text-text-main">{{ isset($coupon) ? '쿠폰 정보 수정' : '새로운 쿠폰 생성' }}</h2>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <form action="{{ isset($coupon) ? route('admin.coupons.update', $coupon->id) : route('admin.coupons.store') }}" method="POST" class="p-6 lg:p-8 space-y-8">
            @csrf
            @if(isset($coupon))
                @method('PUT')
            @endif

            <!-- Basic Information Section -->
            <div class="space-y-6">
                <h3 class="text-sm font-black text-primary uppercase flex items-center gap-2">
                    <span class="size-1.5 rounded-full bg-primary"></span> 기본 정보
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="name" class="text-xs font-bold text-text-muted ml-1">쿠폰명</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $coupon->name ?? '') }}" placeholder="예: 웰컴 할인 쿠폰" 
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all">
                        @error('name') <p class="text-xs text-red-500 mt-1 ml-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="space-y-2">
                        <label for="code" class="text-xs font-bold text-text-muted ml-1">쿠폰 코드 (공란 시 자동 발급 불가)</label>
                        <input type="text" name="code" id="code" value="{{ old('code', $coupon->code ?? '') }}" placeholder="예: WELCOME2026" 
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all uppercase">
                        @error('code') <p class="text-xs text-red-500 mt-1 ml-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="description" class="text-xs font-bold text-text-muted ml-1">쿠폰 상세 설명</label>
                    <textarea name="description" id="description" rows="3" placeholder="쿠폰 사용 조건 등을 입력해 주세요." 
                              class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all">{{ old('description', $coupon->description ?? '') }}</textarea>
                </div>
            </div>

            <!-- Benefit Section -->
            <div class="space-y-6 pt-8 border-t border-gray-50">
                <h3 class="text-sm font-black text-primary uppercase flex items-center gap-2">
                    <span class="size-1.5 rounded-full bg-primary"></span> 혜택 및 조건
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="type" class="text-xs font-bold text-text-muted ml-1">쿠폰 유형</label>
                        <div class="relative">
                            <select name="type" id="type" class="w-full px-4 py-3 pr-10 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 appearance-none outline-none transition-all cursor-pointer !bg-none">
                                <option value="discount" {{ old('type', $coupon->type ?? '') == 'discount' ? 'selected' : '' }}>할인 쿠폰</option>
                                <option value="shipping" {{ old('type', $coupon->type ?? '') == 'shipping' ? 'selected' : '' }}>배송비 쿠폰</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">expand_more</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="discount_type" class="text-xs font-bold text-text-muted ml-1">할인 방식</label>
                        <div class="relative">
                            <select name="discount_type" id="discount_type" class="w-full px-4 py-3 pr-10 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 appearance-none outline-none transition-all cursor-pointer !bg-none">
                                <option value="fixed" {{ old('discount_type', $coupon->discount_type ?? '') == 'fixed' ? 'selected' : '' }}>금액 할인 (원)</option>
                                <option value="percent" {{ old('discount_type', $coupon->discount_type ?? '') == 'percent' ? 'selected' : '' }}>비율 할인 (%)</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">expand_more</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="discount_value" class="text-xs font-bold text-text-muted ml-1">할인 값</label>
                        <div class="relative group">
                            <input type="number" name="discount_value" id="discount_value" value="{{ old('discount_value', $coupon->discount_value ?? 0) }}" 
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-black text-text-muted group-focus-within:text-primary transition-colors" id="discount-unit">원</span>
                        </div>
                        @error('discount_value') <p class="text-xs text-red-500 mt-1 ml-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="min_order_amount" class="text-xs font-bold text-text-muted ml-1">최소 주문 금액</label>
                        <div class="relative group">
                            <input type="number" name="min_order_amount" id="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount ?? 0) }}" 
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
                            <input type="text" name="starts_at" id="starts_at" value="{{ old('starts_at', isset($coupon->starts_at) ? $coupon->starts_at->format('Y-m-d') : '') }}" placeholder="시작일 선택"
                                   class="datepicker w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all">
                            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-[20px] group-focus-within:text-primary">calendar_today</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="ends_at" class="text-xs font-bold text-text-muted ml-1">사용 종료일</label>
                        <div class="relative group">
                            <input type="text" name="ends_at" id="ends_at" value="{{ old('ends_at', isset($coupon->ends_at) ? $coupon->ends_at->format('Y-m-d') : '') }}" placeholder="종료일 선택"
                                   class="datepicker w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all">
                            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-[20px] group-focus-within:text-primary">calendar_month</span>
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <label class="relative inline-flex items-center cursor-pointer group">
                        <input type="checkbox" name="is_active" class="sr-only peer" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
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
                    {{ isset($coupon) ? '쿠폰 정보 수정하기' : '새 쿠폰 등록하기' }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/ko.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Flatpickr 초기화 📅
        flatpickr(".datepicker", {
            locale: "ko",
            dateFormat: "Y-m-d",
            disableMobile: "true",
            animate: true
        });

        const discountTypeSelect = document.getElementById('discount_type');
        const discountUnit = document.getElementById('discount-unit');
        const maxDiscountWrapper = document.getElementById('max-discount-wrapper');

        const updateUI = () => {
            const isPercent = discountTypeSelect.value === 'percent';
            discountUnit.textContent = isPercent ? '%' : '원';
            
            // 비율 할인일 때만 최대 할인 금액 입력 필드 노출 (선택사항)
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
