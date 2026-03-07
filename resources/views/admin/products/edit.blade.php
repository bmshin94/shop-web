@extends('layouts.admin')

@section('page_title', '상품 정보 수정')

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

    /* CKEditor 5 Premium Custom Styling ✨ */
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
    /* Tailwind Reset Fix for CKEditor Lists 😊 */
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
        <h3 class="text-2xl font-extrabold text-text-main">상품 정보 수정</h3>
        <a href="{{ route('admin.products.index') }}" class="flex items-center gap-2 text-sm font-bold text-text-muted hover:text-primary transition-all font-display">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            목록으로 돌아가기
        </a>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden font-display relative">
        <form id="product-form" action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="p-8 md:p-10 space-y-10">
            @csrf
            @method('PUT')
            
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
                                            <option value="{{ $child->id }}" {{ old('category_id', $product->category_id) == $child->id ? 'selected' : '' }}>{{ $child->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none"><span class="material-symbols-outlined text-text-muted">expand_more</span></div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main flex items-center gap-1">상품명 <span class="text-primary">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" placeholder="상품 이름을 입력해주세요" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-base focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-bold text-text-main">슬러그 (Slug)</label>
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-bold text-text-muted font-mono bg-gray-100 px-3 py-4 rounded-2xl border border-gray-200 tracking-tight">/products/</span>
                        <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" class="flex-1 px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-base focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
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
                            <input type="text" id="price" name="price" value="{{ old('price', $product->price) }}" inputmode="numeric" class="w-full pl-10 pr-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-base focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none format-number">
                            <span class="absolute left-4 top-4 text-text-muted font-bold">₩</span>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main">할인 판매가</label>
                        <div class="relative">
                            <input type="text" id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" inputmode="numeric" class="w-full pl-10 pr-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-base focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none format-number">
                            <span class="absolute left-4 top-4 text-text-muted font-bold">₩</span>
                        </div>
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
                        <input type="text" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" inputmode="numeric" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-base focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none text-center format-number">
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
                                    <input type="radio" name="status" value="{{ $status }}" {{ old('status', $product->status) == $status ? 'checked' : '' }} class="w-5 h-5 text-primary border-gray-300 focus:ring-primary/20">
                                    <span class="text-sm font-bold text-text-muted group-hover:text-text-main">{{ $status }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="space-y-3">
                        <label class="text-sm font-bold text-text-main">상품 태그</label>
                        <div class="flex gap-4">
                            <label class="flex-1 flex items-center justify-center gap-2 p-4 bg-gray-50 rounded-2xl border border-gray-200 cursor-pointer hover:bg-white hover:border-blue-500 transition-all group">
                                <input type="checkbox" name="is_new" value="1" {{ old('is_new', $product->is_new) ? 'checked' : '' }} class="w-5 h-5 text-blue-500 border-gray-300 rounded focus:ring-blue-500/20">
                                <span class="text-sm font-bold text-text-muted group-hover:text-text-main">NEW</span>
                            </label>
                            <label class="flex-1 flex items-center justify-center gap-2 p-4 bg-gray-50 rounded-2xl border border-gray-200 cursor-pointer hover:bg-white hover:border-amber-500 transition-all group">
                                <input type="checkbox" name="is_best" value="1" {{ old('is_best', $product->is_best) ? 'checked' : '' }} class="w-5 h-5 text-amber-500 border-gray-300 rounded focus:ring-amber-500/20">
                                <span class="text-sm font-bold text-text-muted group-hover:text-text-main">BEST</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 4: Product Images -->
            <div class="space-y-6">
                <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">image</span>
                        <h4 class="text-lg font-bold text-text-main">상품 이미지 (최대 4개)</h4>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 lg:gap-6">
                    @for($i = 1; $i <= 4; $i++)
                        @php
                            $existingImage = $product->images->where('sort_order', $i - 1)->first() ?? $product->images->skip($i-1)->first();
                        @endphp
                        
                        <div id="slot-{{ $i }}" class="upload-slot group {{ $existingImage ? 'has-image' : '' }}" onclick="document.getElementById('file-{{ $i }}').click()">
                            <input type="file" id="file-{{ $i }}" name="images[]" class="hidden" accept="image/*" onchange="previewImage(this, {{ $i }})">
                            
                            <div class="slot-placeholder flex flex-col items-center p-4 {{ $existingImage ? 'hidden' : '' }}">
                                <span class="material-symbols-outlined text-gray-300 text-[32px] mb-2 group-hover:text-primary transition-colors">{{ $i == 1 ? 'add_a_photo' : 'add_photo_alternate' }}</span>
                                <p class="text-[11px] font-bold text-gray-400 group-hover:text-primary">{{ $i == 1 ? '대표 이미지' : '상세 ' . ($i-1) }}</p>
                            </div>
                            
                            <!-- 기존 이미지 출력 (DB 경로이거나 외부 URL인 경우 모두 처리) -->
                            @if($existingImage)
                                @if(Str::startsWith($existingImage->image_path, 'http'))
                                    <img id="preview-{{ $i }}" class="preview-img" src="{{ $existingImage->image_path }}">
                                @else
                                    <img id="preview-{{ $i }}" class="preview-img" src="{{ asset($existingImage->image_path) }}">
                                @endif
                                <input type="hidden" name="existing_images[{{ $i }}]" value="{{ $existingImage->id }}">
                            @else
                                <img id="preview-{{ $i }}" class="preview-img hidden" src="">
                            @endif

                            <button type="button" class="remove-btn" onclick="removeImage(event, {{ $i }}, {{ $existingImage ? $existingImage->id : 'null' }})">
                                <span class="material-symbols-outlined text-[18px]">close</span>
                            </button>
                        </div>
                    @endfor
                </div>
                <!-- 삭제할 이미지 ID를 모아두는 숨겨진 영역 -->
                <div id="removed-images-container"></div>
                <p class="text-[11px] text-text-muted/80 mt-1.5 font-bold tracking-tight leading-relaxed">이미지 파일만 등록 가능합니다. 삭제를 누르면 기존 이미지가 지워집니다.</p>
                @error('images') <p class="text-[11px] text-red-500 font-bold mt-1 tracking-tight">{{ $message }}</p> @enderror
            </div>

            <!-- Section 5: Media & Content (CKEditor 5 ✨) -->
            <div class="space-y-6">
                <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">article</span>
                        <h4 class="text-lg font-bold text-text-main">상품 상세 설명</h4>
                    </div>
                </div>
                <div id="editor-container" class="space-y-2">
                    <!-- Textarea for CKEditor -->
                    <textarea id="editor" name="description">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>

            <!-- Buttons -->
            <div class="pt-6 flex flex-col sm:flex-row items-center gap-4">
                <button type="submit" class="w-full sm:flex-1 py-5 bg-primary text-white text-lg font-extrabold rounded-2xl shadow-xl shadow-primary/30 hover:bg-red-600 transition-all flex items-center justify-center gap-3">
                    <span class="material-symbols-outlined">save</span> 수정 내용 저장
                </button>

                <a href="{{ route('admin.products.index') }}" class="w-full sm:w-auto px-10 py-5 bg-gray-100 text-text-muted text-lg font-bold rounded-2xl hover:bg-gray-200 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">close</span> 취소
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<!-- CKEditor 5 Custom Build (기능 최대화!) ✨ -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/translations/ko.js"></script>
<script>
    // --- 무적의 에디터 로더! ---
    function startCKEditor() {
        if (typeof ClassicEditor !== 'undefined') {
            console.log('CKEditor 5 Loading...');
            
            // Base64 이미지 업로드 어댑터 플러그인 정의 😊
            function SpecialUploadAdapterPlugin(editor) {
                editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                    return {
                        upload: () => {
                            return loader.file.then(file => new Promise((resolve, reject) => {
                                const reader = new FileReader();
                                reader.onload = function() {
                                    resolve({ default: reader.result }); // Base64 형태로 에디터에 삽입! ✨
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
                    extraPlugins: [SpecialUploadAdapterPlugin], // 이미지 업로드 어댑터 장착! 🚀
                    toolbar: {
                        items: [
                            'heading', '|',
                            'bold', 'italic', 'strikethrough', 'underline', 'code', '|',
                            'bulletedList', 'numberedList', 'todoList', '|',
                            'outdent', 'indent', '|',
                            'link', 'uploadImage', 'blockQuote', 'insertTable', 'mediaEmbed', '|',
                            'undo', 'redo'
                        ],
                        shouldNotGroupWhenFull: false // 한 줄로 합치기! 😊
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
                .then(editor => { console.log('CKEditor 5 is Ready!'); })
                .catch(error => { console.error('CKEditor Error:', error); });
        } else {
            // 아직 로딩 중이면 100ms 뒤에 다시 시도!
            setTimeout(startCKEditor, 100);
        }
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

        window.removeImage = function(event, slotNum, imageId) {
            event.stopPropagation();
            $(`#file-${slotNum}`).val('');
            $(`#preview-${slotNum}`).attr('src', '').addClass('hidden');
            $(`#slot-${slotNum} .slot-placeholder`).removeClass('hidden');
            $(`#slot-${slotNum}`).removeClass('has-image');
            
            // 기존 이미지가 있었고 삭제를 누른 경우, 삭제할 ID 배열에 추가
            if (imageId) {
                $('#removed-images-container').append(`<input type="hidden" name="remove_images[]" value="${imageId}">`);
            }
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
    });
</script>
@endpush
