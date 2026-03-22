@extends('layouts.admin')

@section('page_title', '신규 상품 등록')

@push('styles')
<style>
    /* Image Upload Zone Styles */
    .upload-slot {
        position: relative;
        aspect-ratio: 3/4;
        background-color: #f9fafb;
        border: 2px dashed #e5e7eb;
        border-radius: 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        overflow: hidden;
    }
    .upload-slot:hover { border-color: #ec3713; background-color: #fffaf9; }
    .upload-slot.has-image { border-style: solid; border-color: #f3f4f6; background-color: #fff; }
    .upload-slot.drag-over { border-color: #ec3713; background-color: #ffefe5; transform: scale(1.02); }
    
    .preview-img { width: 100%; height: 100%; object-fit: cover; }
    .remove-btn {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        border-radius: 9999px;
        padding: 0.25rem;
        display: none;
        transition: all 0.2s;
    }
    .upload-slot:hover .remove-btn { display: block; }
    .remove-btn:hover { background-color: #ec3713; }

    /* CKEditor 5 Premium Custom Styling  */
    #editor-container .ck-editor__editable {
        min-height: 450px !important;
        border-bottom-left-radius: 1.5rem !important;
        border-bottom-right-radius: 1.5rem !important;
        padding: 1.5rem 2rem !important;
        font-family: 'Pretendard', sans-serif !important;
        font-size: 15px !important;
        line-height: 1.8 !important;
        background-color: #fff !important;
    }
    #editor-container .ck.ck-editor__main>.ck-editor__editable.ck-focused {
        border-color: #ec3713 !important;
        box-shadow: 0 0 0 4px rgba(236, 55, 19, 0.1) !important;
    }
    #editor-container .ck.ck-toolbar {
        border-top-left-radius: 1.5rem !important;
        border-top-right-radius: 1.5rem !important;
        border-color: #e5e7eb !important;
        background-color: #f9fafb !important;
        padding: 0.5rem !important;
    }
    /* Tailwind Reset Fix for CKEditor Lists  */
    .ck-content ul { list-style-type: disc !important; list-style-position: inside !important; margin: 1em 0 !important; }
    .ck-content ol { list-style-type: decimal !important; list-style-position: inside !important; margin: 1em 0 !important; }
    /* Source Editing View Style */
    .ck-source-editing-area textarea { background-color: #1e293b !important; color: #f8fafc !important; font-family: monospace !important; padding: 1.5rem !important; border-radius: 0 0 1.5rem 1.5rem !important; }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <h3 class="text-2xl font-extrabold text-text-main">새로운 상품 등록</h3>
        <a href="{{ route('admin.products.index') }}" class="flex items-center gap-2 text-sm font-bold text-text-muted hover:text-primary transition-all font-display">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            목록으로 돌아가기
        </a>
    </div>

    <!-- Registration Form -->
    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden font-display relative">
        <form id="product-form" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="p-8 md:p-10 space-y-10" novalidate>
            @csrf
            
            <!-- Section 1: Basic Info -->
            <div class="space-y-6">
                <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                    <span class="material-symbols-outlined text-primary">info</span>
                    <h4 class="text-lg font-bold text-text-main">기본 정보</h4>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main flex items-center gap-1">카테고리 <span class="text-primary">*</span></label>
                        <div class="relative group">
                            <select name="category_id" class="w-full px-5 py-4 pr-12 bg-gray-50 border border-gray-200 rounded-2xl text-base focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none appearance-none bg-none transition-all">
                                <option value="">카테고리를 선택해주세요</option>
                                @foreach($categories as $parent)
                                    <optgroup label="{{ $parent->name }}">
                                        @foreach($parent->children as $child)
                                            <option value="{{ $child->id }}" {{ old('category_id') == $child->id ? 'selected' : '' }}>{{ $child->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none"><span class="material-symbols-outlined text-text-muted">expand_more</span></div>
                        </div>
                        @error('category_id') <p class="text-[11px] text-red-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main flex items-center gap-1">상품명 <span class="text-primary">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="상품 이름을 입력해주세요" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-base focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
                        @error('name') <p class="text-[11px] text-red-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                </div>
                <div class="space-y-2">
                    <label class="text-sm font-bold text-text-main">상품 간단설명</label>
                    <input type="text" name="brief_description" value="{{ old('brief_description') }}" placeholder="상품에 대한 간단한 설명을 입력해주세요" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-base focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-bold text-text-main">슬러그 (Slug)</label>
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-bold text-text-muted font-mono bg-gray-100 px-3 py-4 rounded-2xl border border-gray-200 tracking-tight">/products/</span>
                        <input type="text" name="slug" value="{{ old('slug') }}" class="flex-1 px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-base focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
                    </div>
                    <p class="text-[11px] text-text-muted/80 mt-1.5 font-bold tracking-tight">슬러그는 웹사이트 주소(URL)에 사용되는 상품의 고유 명칭입니다.</p>
                </div>
            </div>

            <!-- Section 2: Pricing & Stock -->
            <div class="space-y-6">
                <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                    <span class="material-symbols-outlined text-primary">payments</span>
                    <h4 class="text-lg font-bold text-text-main">가격 및 재고 설정</h4>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main">판매가 <span class="text-primary">*</span></label>
                        <div class="relative">
                            <input type="text" id="price" name="price" value="{{ old('price') }}" required inputmode="numeric" class="w-full pl-10 pr-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-base focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none format-number">
                            <span class="absolute left-4 top-4 text-text-muted font-bold">₩</span>
                        </div>
                        @error('price') <p class="text-[11px] text-red-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main">할인 판매가</label>
                        <div class="relative">
                            <input type="text" id="sale_price" name="sale_price" value="{{ old('sale_price') }}" inputmode="numeric" class="w-full pl-10 pr-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-base focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none format-number">
                            <span class="absolute left-4 top-4 text-text-muted font-bold">₩</span>
                        </div>
                        @error('sale_price') <p class="text-[11px] text-red-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main">예상 할인율</label>
                        <div class="relative">
                            <input type="text" id="discount_rate" readonly class="w-full px-5 py-4 bg-gray-100 border border-gray-200 rounded-2xl text-base text-primary font-extrabold text-center cursor-default">
                            <span class="absolute right-4 top-4 text-primary font-bold">%</span>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main">재고 수량 <span class="text-primary">*</span></label>
                        <input type="text" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" required inputmode="numeric" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-base focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none text-center format-number">
                        @error('stock_quantity') <p class="text-[11px] text-red-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2.5: Shipping Settings -->
            <div class="space-y-6">
                <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                    <span class="material-symbols-outlined text-primary">local_shipping</span>
                    <h4 class="text-lg font-bold text-text-main">배송 설정</h4>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-3">
                        <label class="text-sm font-bold text-text-main">배송비 구분 <span class="text-primary">*</span></label>
                        <div class="flex flex-wrap gap-4">
                            @foreach(['기본', '무료', '고정'] as $type)
                                <label class="flex-1 flex items-center justify-center gap-2 p-4 bg-gray-50 rounded-2xl border border-gray-200 cursor-pointer hover:bg-white hover:border-primary transition-all group">
                                    <input type="radio" name="shipping_type" value="{{ $type }}" {{ old('shipping_type', '기본') == $type ? 'checked' : '' }} class="shipping-type-radio w-5 h-5 text-primary border-gray-300 focus:ring-primary/20">
                                    <span class="text-sm font-bold text-text-muted group-hover:text-text-main">{{ $type }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-[11px] text-text-muted font-bold ml-1">
                            * 기본: 쇼핑몰 기본 배송 정책 적용 | 무료: 금액 상관없이 무료 | 고정: 설정된 금액 부과
                        </p>
                    </div>
                    <div id="shipping-fee-container" class="space-y-3 {{ old('shipping_type', '기본') == '고정' ? '' : 'hidden' }}">
                        <label class="text-sm font-bold text-text-main">배송비 금액 <span class="text-primary">*</span></label>
                        <div class="relative">
                            <input type="text" name="shipping_fee" value="{{ old('shipping_fee', 0) }}" inputmode="numeric" class="w-full pl-10 pr-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-base focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none format-number">
                            <span class="absolute left-4 top-4 text-text-muted font-bold">₩</span>
                        </div>
                        @error('shipping_fee') <p class="text-[11px] text-red-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 3: Status & Badges -->
            <div class="space-y-6">
                <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                    <span class="material-symbols-outlined text-primary">visibility</span>
                    <h4 class="text-lg font-bold text-text-main">노출 및 상태 설정</h4>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-3">
                        <label class="text-sm font-bold text-text-main">판매 상태</label>
                        <div class="flex flex-wrap gap-4">
                            @foreach(['판매중', '품절', '숨김'] as $status)
                                <label class="flex-1 flex items-center justify-center gap-2 p-4 bg-gray-50 rounded-2xl border border-gray-200 cursor-pointer hover:bg-white hover:border-primary transition-all group">
                                    <input type="radio" name="status" value="{{ $status }}" {{ old('status', '판매중') == $status ? 'checked' : '' }} class="w-5 h-5 text-primary border-gray-300 focus:ring-primary/20">
                                    <span class="text-sm font-bold text-text-muted group-hover:text-text-main">{{ $status }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="space-y-3">
                        <label class="text-sm font-bold text-text-main">상품 태그</label>
                        <div class="flex gap-4">
                            <label class="flex-1 flex items-center justify-center gap-2 p-4 bg-gray-50 rounded-2xl border border-gray-200 cursor-pointer hover:bg-white hover:border-blue-500 transition-all group">
                                <input type="checkbox" name="is_new" value="1" {{ old('is_new') ? 'checked' : '' }} class="w-5 h-5 text-blue-500 border-gray-300 rounded focus:ring-blue-500/20">
                                <span class="text-sm font-bold text-text-muted group-hover:text-text-main">NEW</span>
                            </label>
                            <label class="flex-1 flex items-center justify-center gap-2 p-4 bg-gray-50 rounded-2xl border border-gray-200 cursor-pointer hover:bg-white hover:border-amber-500 transition-all group">
                                <input type="checkbox" name="is_best" value="1" {{ old('is_best') ? 'checked' : '' }} class="w-5 h-5 text-amber-500 border-gray-300 rounded focus:ring-amber-500/20">
                                <span class="text-sm font-bold text-text-muted group-hover:text-text-main">BEST</span>
                            </label>
                            <label class="flex-1 flex items-center justify-center gap-2 p-4 bg-gray-50 rounded-2xl border border-gray-100 cursor-pointer hover:bg-white hover:border-primary transition-all group">
                                <input type="checkbox" name="is_hero" value="1" {{ old('is_hero') ? 'checked' : '' }} class="w-5 h-5 text-primary border-gray-300 rounded focus:ring-primary/20">
                                <span class="text-sm font-bold text-text-muted group-hover:text-text-main">HERO</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 4: Color Selection  -->
            <div class="space-y-6">
                <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">palette</span>
                        <h4 class="text-lg font-bold text-text-main">색상 옵션 설정</h4>
                    </div>
                    <button type="button" class="js-toggle-all text-[11px] font-black text-primary uppercase hover:underline" data-target="colors[]">
                        전체 선택/해제
                    </button>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-4">
                    @foreach($colors as $color)
                    <label class="relative flex flex-col items-center gap-3 p-4 rounded-2xl border border-gray-100 hover:border-primary/30 hover:bg-primary-light/30 cursor-pointer transition-all group">
                        <input type="checkbox" name="colors[]" value="{{ $color->id }}" 
                            {{ is_array(old('colors')) && in_array($color->id, old('colors')) ? 'checked' : '' }}
                            class="peer hidden">
                        <div class="size-8 rounded-full ring-2 ring-white shadow-md shrink-0 transition-transform group-hover:scale-110" style="background-color: {{ $color->hex_code }}"></div>
                        <span class="text-[12px] font-bold text-text-muted group-hover:text-text-main transition-colors">{{ $color->name }}</span>
                        
                        <!-- Checked State UI -->
                        <div class="absolute inset-0 border-2 border-primary rounded-2xl opacity-0 peer-checked:opacity-100 transition-all pointer-events-none"></div>
                        <div class="absolute -top-2 -right-2 opacity-0 peer-checked:opacity-100 transition-all scale-50 peer-checked:scale-100">
                            <div class="bg-primary text-white rounded-full p-0.5 shadow-lg flex items-center justify-center">
                                <span class="material-symbols-outlined text-[16px] font-black">check</span>
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('colors')
                    <p class="text-[11px] text-red-500 font-bold mt-1 tracking-tight">{{ $message }}</p>
                @enderror
            </div>

            <!-- Section 5: Size Selection -->
            <div class="space-y-6">
                <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                    <span class="material-symbols-outlined text-primary">straighten</span>
                    <h4 class="text-lg font-bold text-text-main">사이즈 옵션 설정</h4>
                </div>
                
                <div class="space-y-8">
                    @foreach($sizeGroups as $group)
                    <div class="p-6 bg-gray-50/50 rounded-3xl border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <h5 class="text-xs font-black text-primary uppercase flex items-center gap-2">
                                <span class="size-1.5 rounded-full bg-primary"></span>
                                {{ $group->name }} 사이즈 그룹
                            </h5>
                            <button type="button" class="js-toggle-group-all text-[10px] font-bold text-text-muted hover:text-primary transition-colors" data-group-id="{{ $group->id }}">
                                그룹 전체 선택/해제
                            </button>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-4 size-group-{{ $group->id }}">
                            @foreach($group->sizes as $size)
                            <label class="relative flex flex-col items-center gap-3 p-4 bg-white rounded-2xl border border-gray-100 hover:border-primary/30 hover:bg-primary-light/30 cursor-pointer transition-all group">
                                <input type="checkbox" name="sizes[]" value="{{ $size->id }}" 
                                    {{ is_array(old('sizes')) && in_array($size->id, old('sizes')) ? 'checked' : '' }}
                                    class="peer hidden">
                                <div class="size-8 flex items-center justify-center font-black text-text-main group-hover:text-primary transition-colors">{{ $size->name }}</div>
                                <span class="text-[10px] font-bold text-text-muted group-hover:text-text-main transition-colors">Option</span>
                                
                                <!-- Checked State UI -->
                                <div class="absolute inset-0 border-2 border-primary rounded-2xl opacity-0 peer-checked:opacity-100 transition-all pointer-events-none"></div>
                                <div class="absolute -top-2 -right-2 opacity-0 peer-checked:opacity-100 transition-all scale-50 peer-checked:scale-100">
                                    <div class="bg-primary text-white rounded-full p-0.5 shadow-lg flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[16px] font-black">check</span>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                @error('sizes')
                    <p class="text-[11px] text-red-500 font-bold mt-1 tracking-tight">{{ $message }}</p>
                @enderror
            </div>

            <!-- Section 6: Related Products -->
            <div class="space-y-6">
                <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">auto_awesome_motion</span>
                        <h4 class="text-lg font-bold text-text-main">함께 스타일링하기 좋은 아이템</h4>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <!-- Search Input -->
                    <div class="relative">
                        <input type="text" id="related-product-search" placeholder="상품명 또는 ID로 검색하여 추가하세요" autocomplete="off"
                            class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
                        <div id="search-results-wrapper" class="absolute z-50 w-full mt-2 bg-white rounded-2xl shadow-2xl border border-gray-100 hidden flex-col overflow-hidden">
                            <div id="search-results" class="max-h-60 overflow-y-auto">
                                <!-- AJAX Search Results Here -->
                            </div>
                            <div id="search-results-actions" class="p-3 bg-gray-50 border-t border-gray-100 hidden">
                                <button type="button" id="btn-add-selected-related" class="w-full py-2.5 bg-primary text-white text-sm font-bold rounded-xl hover:bg-red-600 transition-colors shadow-md">
                                    선택 항목 추가
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Products List -->
                    <div id="selected-related-products" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Selected items will be appended here -->
                    </div>
                </div>
            </div>

            <!-- Section 7: Product Images -->
            <div class="space-y-6">
                <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">image</span>
                        <h4 class="text-lg font-bold text-text-main">상품 이미지 (최대 5개)</h4>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 lg:gap-6">
                    @for($i = 1; $i <= 5; $i++)
                    <div id="slot-{{ $i }}" class="upload-slot group" onclick="document.getElementById('file-{{ $i }}').click()">
                        <input type="file" id="file-{{ $i }}" name="images[{{ $i-1 }}]" class="hidden" accept="image/*" onchange="previewImage(this, {{ $i }})">

                        <div class="slot-placeholder flex flex-col items-center p-4">
                            <span class="material-symbols-outlined text-gray-300 text-[32px] mb-2 group-hover:text-primary transition-colors">{{ $i == 1 ? 'add_a_photo' : 'add_photo_alternate' }}</span>
                            <p class="text-[11px] font-bold text-gray-400 group-hover:text-primary">{{ $i == 1 ? '대표 이미지' : '상세 ' . ($i-1) }}</p>
                        </div>
                        <img id="preview-{{ $i }}" class="preview-img hidden" src="">
                        <button type="button" class="remove-btn" onclick="removeImage(event, {{ $i }})"><span class="material-symbols-outlined text-[18px]">close</span></button>
                    </div>
                    @endfor
                </div>
                <p class="text-[11px] text-text-muted/80 mt-1.5 font-bold tracking-tight leading-relaxed">이미지 파일만 등록 가능합니다.</p>
                @error('images') <p class="text-[11px] text-red-500 font-bold mt-1 tracking-tight">{{ $message }}</p> @enderror
            </div>

            <!-- Section 5: Media & Content (CKEditor 5 ) -->
            <div class="space-y-6">
                <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">article</span>
                        <h4 class="text-lg font-bold text-text-main">상품 상세 설명</h4>
                    </div>
                    <button type="button" onclick="previewDescription()" class="px-4 py-2 bg-gray-100 text-text-main text-xs font-bold rounded-xl hover:bg-gray-200 transition-all flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                        미리보기
                    </button>
                </div>
                <div id="editor-container" class="space-y-2">
                    <!-- Textarea for CKEditor -->
                    <textarea id="editor" name="description">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Buttons -->
            <div class="pt-6 flex flex-col sm:flex-row items-center gap-4">
                <button type="submit" class="w-full sm:flex-1 py-5 bg-primary text-white text-lg font-extrabold rounded-2xl shadow-xl shadow-primary/30 hover:bg-red-600 transition-all flex items-center justify-center gap-3">
                    <span class="material-symbols-outlined">check_circle</span> 상품 등록 완료
                </button>
                <a href="{{ route('admin.products.index') }}" class="w-full sm:w-auto px-10 py-5 bg-gray-100 text-text-muted text-lg font-bold rounded-2xl hover:bg-gray-200 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">close</span> 취소
                </a>
            </div>
        </form>
    </div>

    <!-- Product Registration Guide Footer -->
    <div class="mt-12 bg-white rounded-3xl p-8 border border-gray-100 shadow-sm space-y-6">
        <div class="flex items-center gap-3 border-b border-gray-50 pb-4">
            <div class="size-10 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-[24px]">add_circle</span>
            </div>
            <div>
                <h4 class="text-lg font-bold text-text-main">상품 등록 가이드</h4>
                <p class="text-[11px] font-bold text-text-muted tracking-tight">성공적인 상품 등록을 위한 필수 체크리스트입니다.</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-4">
                <div class="flex gap-3">
                    <span class="text-primary font-black text-base">01.</span>
                    <div>
                        <h5 class="text-sm font-extrabold text-text-main mb-1">필수 정보 입력</h5>
                        <p class="text-[11px] font-bold text-text-muted tracking-tight leading-relaxed">카테고리, 상품명, 판매가, 재고 수량 등 별표(*) 표시가 된 항목은 반드시 입력해야 합니다. 누락 시 등록이 완료되지 않습니다.</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <span class="text-primary font-black text-base">02.</span>
                    <div>
                        <h5 class="text-sm font-extrabold text-text-main mb-1">이미지 최적화</h5>
                        <p class="text-[11px] font-bold text-text-muted tracking-tight leading-relaxed">이미지는 최대 5개까지 등록 가능하며, 3:4 비율에 최적화되어 있습니다. 첫 번째 슬롯에 등록한 이미지가 상품의 대표 이미지로 사용됩니다.</p>
                    </div>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex gap-3">
                    <span class="text-primary font-black text-base">03.</span>
                    <div>
                        <h5 class="text-sm font-extrabold text-text-main mb-1">슬러그(Slug) 관리</h5>
                        <p class="text-[11px] font-bold text-text-muted tracking-tight leading-relaxed">슬러그는 상품의 고유 URL 주소가 됩니다. 기본적으로 상품명을 기반으로 자동 생성되지만, 검색 엔진 최적화(SEO)를 위해 직접 수정할 수 있습니다.</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <span class="text-primary font-black text-base">04.</span>
                    <div>
                        <h5 class="text-sm font-extrabold text-text-main mb-1">상세 설명 에디터</h5>
                        <p class="text-[11px] font-bold text-text-muted tracking-tight leading-relaxed">CKEditor를 활용하여 이미지 삽입, 표 작성, 텍스트 스타일링이 가능합니다. 고객에게 상품의 매력을 충분히 어필할 수 있도록 상세하게 작성해주세요.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Description Preview Modal -->
<div id="preview-modal" class="fixed inset-0 z-[10001] hidden items-center justify-center bg-black/60 backdrop-blur-md p-4 lg:p-8">
    <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-5xl max-h-[90vh] flex flex-col overflow-hidden animate-fade-in">
        <div class="px-10 py-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
            <div class="flex items-center gap-3">
                <div class="size-10 rounded-2xl bg-primary text-white flex items-center justify-center shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined text-[24px]">visibility</span>
                </div>
                <div>
                    <h4 class="text-xl font-black text-text-main">상세 설명 미리보기</h4>
                    <p class="text-[11px] font-bold text-text-muted uppercase">User View Preview</p>
                </div>
            </div>
            <button onclick="closePreview()" class="size-12 rounded-2xl bg-white border border-gray-200 text-text-muted hover:text-primary hover:border-primary transition-all flex items-center justify-center shadow-sm">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-10 bg-white">
            <div class="mx-auto max-w-4xl">
                <div id="preview-content" class="prose prose-gray max-w-none text-text-main leading-relaxed">
                    <!-- CKEditor Content Goes Here -->
                </div>
            </div>
        </div>
        <div class="px-10 py-6 border-t border-gray-100 bg-gray-50/50 text-center">
            <p class="text-[11px] font-bold text-text-muted tracking-tight italic">사용자 상세 페이지의 실제 스타일(Typography)이 적용된 화면입니다.</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- CKEditor 5 Custom Build (기능 최대화!)  -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/translations/ko.js"></script>
<script>
    let productEditor; // 전역 변수로 저장!

    // --- 무적의 에디터 로더! ---
    function startCKEditor() {
        if (typeof ClassicEditor !== 'undefined') {
            console.log('CKEditor 5 Loading...');
            
            // Base64 이미지 업로드 어댑터 플러그인 정의 
            function SpecialUploadAdapterPlugin(editor) {
                editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                    return {
                        upload: () => {
                            return loader.file.then(file => new Promise((resolve, reject) => {
                                const reader = new FileReader();
                                reader.onload = function() {
                                    resolve({ default: reader.result }); // Base64 형태로 에디터에 삽입! 
                                };
                                reader.onerror = function(error) {
                                    reject(error);
                                };
                                reader.readAsDataURL(file);
                            }));
                        },
                        abort: () => {}
                    };
                };
            }

            ClassicEditor
                .create(document.querySelector('#editor'), {
                    extraPlugins: [SpecialUploadAdapterPlugin], // 이미지 업로드 어댑터 장착! 
                    toolbar: {
                        items: [
                            'heading', '|',
                            'bold', 'italic', 'strikethrough', 'underline', 'code', '|',
                            'bulletedList', 'numberedList', 'todoList', '|',
                            'outdent', 'indent', '|',
                            'link', 'uploadImage', 'blockQuote', 'insertTable', 'mediaEmbed', '|',
                            'undo', 'redo'
                        ],
                        shouldNotGroupWhenFull: false // 한 줄로 합치기! 
                    },
                    language: 'ko',
                    placeholder: '상품에 대한 상세하고 매력적인 설명을 입력해주세요. (이미지 삽입 가능!)',
                    image: {
                        toolbar: [
                            'imageTextAlternative', 'toggleImageCaption', 'imageStyle:inline',
                            'imageStyle:block', 'imageStyle:side'
                        ]
                    },
                    table: {
                        contentToolbar: [
                            'tableColumn', 'tableRow', 'mergeTableCells'
                        ]
                    }
                })
                .then(editor => { 
                    console.log('CKEditor 5 is Ready!'); 
                    productEditor = editor; // 에디터 인스턴스 저장
                })
                .catch(error => { console.error('CKEditor Error:', error); });
        } else {
            // 아직 로딩 중이면 100ms 뒤에 다시 시도!
            setTimeout(startCKEditor, 100);
        }
    }

    /**
     * 상세 설명 미리보기 모달 열기
     */
    window.previewDescription = function() {
        if (!productEditor) {
            showAlert('에디터가 아직 준비되지 않았습니다.', '알림', 'warning');
            return;
        }

        const data = productEditor.getData();
        if (!data || data.trim() === '') {
            showAlert('입력된 내용이 없습니다.', '알림', 'info');
            return;
        }

        $('#preview-content').html(data);
        $('#preview-modal').removeClass('hidden').addClass('flex');
        $('body').addClass('overflow-hidden');
    }

    /**
     * 미리보기 모달 닫기
     */
    window.closePreview = function() {
        $('#preview-modal').removeClass('flex').addClass('hidden');
        $('body').removeClass('overflow-hidden');
    }

    $(document).ready(function() {
        startCKEditor();

        // 이미지 미리보기 및 삭제
        window.previewImage = function(input, slotNum) {
            const file = input.files[0];
            if (file) {
                if (!file.type.startsWith('image/')) { showAlert('이미지 파일만 등록 가능합니다.', '오류', 'error'); input.value = ''; return; }
                const reader = new FileReader();
                reader.onload = function(e) {
                    $(`#preview-${slotNum}`).attr('src', e.target.result).removeClass('hidden');
                    $(`#slot-${slotNum} .slot-placeholder`).addClass('hidden');
                    $(`#slot-${slotNum}`).addClass('has-image');
                }
                reader.readAsDataURL(file);
            }
        };

        window.removeImage = function(event, slotNum) {
            event.stopPropagation();
            $(`#file-${slotNum}`).val('');
            $(`#preview-${slotNum}`).attr('src', '').addClass('hidden');
            $(`#slot-${slotNum} .slot-placeholder`).removeClass('hidden');
            $(`#slot-${slotNum}`).removeClass('has-image');
        };

        // 드래그 앤 드롭
        $('.upload-slot').on('dragover', function(e) { e.preventDefault(); $(this).addClass('drag-over'); })
            .on('dragleave drop', function(e) { e.preventDefault(); $(this).removeClass('drag-over'); })
            .on('drop', function(e) {
                const files = e.originalEvent.dataTransfer.files;
                const slotNum = $(this).attr('id').split('-')[1];
                if (files.length > 0) {
                    if (!files[0].type.startsWith('image/')) { showAlert('이미지 파일만 드래그 가능합니다.', '형식 오류', 'error'); return; }
                    document.getElementById(`file-${slotNum}`).files = files;
                    window.previewImage(document.getElementById(`file-${slotNum}`), slotNum);
                }
            });

        // 콤마 및 할인 계산
        const $price = $('#price'); const $salePrice = $('#sale_price'); const $discountRate = $('#discount_rate');
        function formatNumber(n) { return n.toString().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","); }
        $('.format-number').on('input', function() { $(this).val(formatNumber($(this).val())); calculateDiscount(); });
        function calculateDiscount() {
            const price = parseInt($price.val().replace(/,/g, "")) || 0;
            const salePrice = parseInt($salePrice.val().replace(/,/g, "")) || 0;
            if (price > 0 && salePrice > 0 && salePrice < price) { $discountRate.val(Math.round(((price - salePrice) / price) * 100)); } else { $discountRate.val(0); }
        }
        $('#product-form').on('submit', function() { $('.format-number').each(function() { $(this).val($(this).val().replace(/,/g, "")); }); });
        $('.format-number').each(function() { $(this).val(formatNumber($(this).val())); });
        calculateDiscount();

        // 미리보기 모달 외부 클릭 시 닫기
        $('#preview-modal').on('click', function(e) {
            if (e.target === this) closePreview();
        });

        // --- 옵션 전체 선택/해제 기능 ---
        // 1. 색상 전체 선택/해제
        $('.js-toggle-all').on('click', function() {
            const targetName = $(this).data('target');
            const $checkboxes = $(`input[name="${targetName}"]`);
            const allChecked = $checkboxes.length === $checkboxes.filter(':checked').length;
            
            $checkboxes.prop('checked', !allChecked);
        });

        // 2. 사이즈 그룹별 전체 선택/해제
        $('.js-toggle-group-all').on('click', function() {
            const groupId = $(this).data('group-id');
            const $checkboxes = $(`.size-group-${groupId} input[type="checkbox"]`);
            const allChecked = $checkboxes.length === $checkboxes.filter(':checked').length;
            
            $checkboxes.prop('checked', !allChecked);
        });

        // --- 연관 상품 검색 및 선택 기능 ---
        const $searchInput = $('#related-product-search');
        const $resultsWrapper = $('#search-results-wrapper');
        const $resultsContainer = $('#search-results');
        const $resultsActions = $('#search-results-actions');
        const $selectedContainer = $('#selected-related-products');
        let selectedProductIds = [];
        let searchResultsData = [];

        // 1. 검색 입력 이벤트
        $searchInput.on('input', function() {
            const query = $(this).val().trim();
            
            if (query.length < 1) {
                $resultsWrapper.addClass('hidden');
                $resultsContainer.empty();
                $resultsActions.addClass('hidden');
                searchResultsData = [];
                return;
            }

            $.get('{{ route("admin.products.search") }}', { q: query })
                .done(function(products) {
                    $resultsContainer.empty();
                    searchResultsData = products;
                    
                    if (products.length > 0) {
                        products.forEach((product) => {
                            const isAlreadySelected = selectedProductIds.includes(product.id);
                            
                            const html = `
                                <label class="search-result-item p-4 flex items-center justify-between cursor-pointer transition-colors border-b border-gray-50 last:border-b-0 hover:bg-gray-50 ${isAlreadySelected ? 'opacity-50' : ''}">
                                    <div class="flex items-center gap-3">
                                        <img src="${product.image_url}" class="size-10 rounded-lg object-cover bg-gray-100">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-text-main">${product.name}</span>
                                            <span class="text-xs text-text-muted">ID: ${product.id} | ₩${product.price.toLocaleString()}</span>
                                        </div>
                                    </div>
                                    ${isAlreadySelected 
                                        ? '<span class="text-xs font-bold text-primary">추가됨</span>' 
                                        : `<input type="checkbox" value="${product.id}" class="related-product-checkbox w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary/20">`}
                                </label>
                            `;
                            
                            $resultsContainer.append(html);
                        });
                        $resultsWrapper.removeClass('hidden').addClass('flex');
                        $resultsActions.removeClass('hidden');
                    } else {
                        $resultsContainer.append('<div class="p-8 text-center text-sm text-text-muted font-medium italic">검색 결과가 없습니다.</div>');
                        $resultsWrapper.removeClass('hidden').addClass('flex');
                        $resultsActions.addClass('hidden');
                    }
                });
        });

        // 2. 선택 항목 추가 버튼 클릭
        $('#btn-add-selected-related').on('click', function() {
            $('.related-product-checkbox:checked').each(function() {
                const id = parseInt($(this).val());
                const product = searchResultsData.find(p => p.id === id);
                if (product) {
                    addRelatedProduct(product);
                }
            });
            
            // 검색창 초기화 및 닫기
            $resultsWrapper.removeClass('flex').addClass('hidden');
            $searchInput.val('');
        });

        // 배송비 설정 토글
        $('.shipping-type-radio').on('change', function() {
            if ($(this).val() === '고정') {
                $('#shipping-fee-container').removeClass('hidden');
            } else {
                $('#shipping-fee-container').addClass('hidden');
            }
        });

        // 3. 상품 추가 함수
        window.addRelatedProduct = function(product) {
            if (selectedProductIds.includes(product.id)) return;

            selectedProductIds.push(product.id);
            const html = `
                <div class="relative p-4 bg-gray-50 rounded-2xl border border-gray-100 flex items-center gap-3 group animate-fade-in" id="related-item-${product.id}">
                    <input type="hidden" name="related_products[]" value="${product.id}">
                    <img src="${product.image_url}" class="size-12 rounded-xl object-cover bg-white shadow-sm">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-text-main truncate">${product.name}</p>
                        <p class="text-[10px] font-bold text-text-muted">ID: ${product.id}</p>
                    </div>
                    <button type="button" onclick="removeRelatedProduct(${product.id})" class="p-1 text-text-muted hover:text-red-600 transition-colors">
                        <span class="material-symbols-outlined text-[20px]">delete</span>
                    </button>
                </div>
            `;
            $selectedContainer.append(html);
        };

        // 4. 상품 제거 함수
        window.removeRelatedProduct = function(id) {
            $(`#related-item-${id}`).remove();
            selectedProductIds = selectedProductIds.filter(pid => pid !== id);
        };

        // 검색창 외부 클릭 시 닫기
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#related-product-search, #search-results-wrapper').length) {
                $resultsWrapper.removeClass('flex').addClass('hidden');
            }
        });
        
        // 검색창 포커스 시 다시 열기
        $searchInput.on('focus', function() {
            if ($(this).val().trim().length >= 1 && searchResultsData.length > 0) {
                $resultsWrapper.removeClass('hidden').addClass('flex');
            }
        });
    });
</script>
@endpush
