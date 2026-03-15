@php
    $exhibition = $exhibition ?? null;
    $statusOptions = $statusOptions ?? [];
    $formAction = $formAction ?? '';
    $formMethod = $formMethod ?? 'POST';
    $submitLabel = $submitLabel ?? '저장';

    $startAtValue = old('start_at', optional($exhibition?->start_at)->format('Y-m-d'));
    $endAtValue = old('end_at', optional($exhibition?->end_at)->format('Y-m-d'));
@endphp

<form action="{{ $formAction }}" method="POST" class="space-y-5" enctype="multipart/form-data">
    @csrf
    @if($formMethod !== 'POST')
        @method($formMethod)
    @endif

    <div class="space-y-2">
        <label class="text-sm font-bold text-text-main">기획전명</label>
        <input
            type="text"
            name="title"
            value="{{ old('title', $exhibition?->title) }}"
            placeholder="예) 3월 봄맞이 혜택전"
            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
        @error('title')
            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="space-y-2">
        <label class="text-sm font-bold text-text-main">슬러그</label>
        <input
            type="text"
            name="slug"
            value="{{ old('slug', $exhibition?->slug) }}"
            placeholder="exhibition-spring-special"
            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
        <p class="text-[11px] font-bold text-text-muted">비워두면 기획전명 기반으로 자동 생성됩니다.</p>
        @error('slug')
            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="space-y-2">
            <label class="text-sm font-bold text-text-main">정렬 순서</label>
            <input
                type="number"
                min="0"
                max="9999"
                name="sort_order"
                value="{{ old('sort_order', $exhibition?->sort_order ?? 0) }}"
                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
            @error('sort_order')
                <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="space-y-2 flex flex-col justify-end pb-3">
            <p class="text-[11px] font-bold text-text-muted italic">※ 상태값은 시작/종료 일시에 따라 자동으로 계산됩니다. 😊</p>
        </div>
    </div>

    <div class="space-y-2">
        <label class="text-sm font-bold text-text-main">배너 이미지</label>
        <div class="space-y-4">
            {{-- 드래그 앤 드롭 지원 이미지 업로드 영역 ✨ --}}
            <div id="banner-drop-zone" class="relative group">
                <input 
                    type="file" 
                    name="banner_image" 
                    id="banner-input" 
                    accept="image/*"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                
                <div id="preview-wrapper" class="w-full min-h-[200px] aspect-video bg-gray-50 border-2 border-dashed border-gray-200 rounded-3xl overflow-hidden flex flex-col items-center justify-center transition-all relative group-hover:border-primary/30 group-hover:bg-primary/5">
                    {{-- 기존 이미지 또는 미리보기 이미지 🖼️ --}}
                    <img 
                        id="banner-preview" 
                        src="{{ $exhibition?->banner_image_url ?? '' }}" 
                        class="{{ $exhibition?->banner_image_url ? 'block' : 'hidden' }} w-full h-full object-cover">
                    
                    {{-- 업로드 안내 (이미지가 없을 때만 표시) --}}
                    <div id="upload-placeholder" class="{{ $exhibition?->banner_image_url ? 'hidden' : 'flex' }} flex-col items-center justify-center text-center p-6">
                        <div class="size-16 rounded-full bg-white shadow-sm flex items-center justify-center text-gray-400 mb-4 group-hover:scale-110 group-hover:text-primary transition-all">
                            <span class="material-symbols-outlined text-[32px]">add_photo_alternate</span>
                        </div>
                        <p class="text-sm font-bold text-text-main">클릭하거나 이미지를 여기로 드래그하세요</p>
                        <p class="mt-1 text-[11px] font-bold text-text-muted">권장 사이즈: 1200 x 500 px (최대 2MB)</p>
                    </div>

                    {{-- 드래그 시 덮어씌워지는 레이어 😊 --}}
                    <div id="drag-overlay" class="absolute inset-0 bg-primary/10 backdrop-blur-[2px] items-center justify-center hidden z-10">
                        <div class="bg-white px-6 py-3 rounded-full shadow-xl flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">download</span>
                            <span class="text-sm font-black text-primary">여기에 놓아주세요!</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-2 px-1">
                <span class="material-symbols-outlined text-[16px] text-primary">info</span>
                <p class="text-[11px] font-bold text-text-main">
                    권장 사이즈: <span class="text-primary">1200 x 500 px</span> 
                    <span class="text-text-muted ml-1">(최대 2MB / JPG, PNG, WEBP 지원)</span>
                </p>
            </div>
            
            @error('banner_image')
                <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="space-y-2">
        <label class="text-sm font-bold text-text-main">요약 문구</label>
        <textarea name="summary" rows="2" placeholder="기획전을 한 줄로 설명해 주세요." class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">{{ old('summary', $exhibition?->summary) }}</textarea>
        @error('summary')
            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="space-y-2">
        <label class="text-sm font-bold text-text-main">상세 설명</label>
        <textarea name="description" rows="6" placeholder="기획전의 상세 내용을 입력해 주세요." class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">{{ old('description', $exhibition?->description) }}</textarea>
        @error('description')
            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="space-y-2">
            <label class="text-sm font-bold text-text-main">시작 일시</label>
            <div class="relative group">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-muted text-[18px] group-focus-within:text-primary transition-colors pointer-events-none">calendar_today</span>
                <input
                    type="text"
                    name="start_at"
                    value="{{ $startAtValue }}"
                    class="datepicker w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
            </div>
            @error('start_at')
                <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label class="text-sm font-bold text-text-main">종료 일시</label>
            <div class="relative group">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-muted text-[18px] group-focus-within:text-primary transition-colors pointer-events-none">calendar_month</span>
                <input
                    type="text"
                    name="end_at"
                    value="{{ $endAtValue }}"
                    class="datepicker w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
            </div>
            @error('end_at')
                <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- 연결 상품 관리 영역 ✨ --}}
    <div class="space-y-3 pt-4">
        <label class="text-sm font-bold text-text-main flex items-center gap-2">
            연결 상품 관리 
            <span class="text-[10px] font-black bg-gray-100 px-2 py-0.5 rounded text-gray-400 uppercase tracking-tighter">Products Linking</span>
        </label>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 bg-gray-50 border border-gray-200 rounded-3xl p-6">
            {{-- 왼쪽: 상품 검색 및 추가 --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between border-b border-gray-200 pb-3">
                    <h3 class="text-sm font-extrabold text-text-main flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[20px]">inventory_2</span> 
                        전체 상품 목록
                    </h3>
                </div>
                
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">search</span>
                    <input 
                        type="text" 
                        id="product-search" 
                        placeholder="상품명으로 검색..." 
                        class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-2xl text-sm focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all shadow-sm">
                </div>

                <div id="available-products" class="bg-white border border-gray-200 rounded-2xl h-[350px] overflow-y-auto overflow-x-hidden shadow-inner p-2">
                    @foreach($products as $product)
                        <div class="product-item flex items-center justify-between p-3 hover:bg-gray-50 rounded-xl transition-all border-b border-gray-100 last:border-0" 
                             data-id="{{ $product->id }}" 
                             data-name="{{ strtolower($product->name) }}"
                             data-price="{{ number_format($product->sale_price ?? $product->price) }}">
                            <div class="flex-1 pr-4">
                                <p class="text-sm font-bold text-text-main line-clamp-1 truncate product-name">{{ $product->name }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[12px] font-black text-primary product-price">₩{{ number_format($product->sale_price ?? $product->price) }}</span>
                                    <span class="text-[10px] font-bold text-gray-400">ID: {{ $product->id }}</span>
                                </div>
                            </div>
                            <button type="button" class="btn-add shrink-0 size-8 flex items-center justify-center bg-gray-100 text-text-main rounded-xl hover:bg-primary hover:text-white transition-colors">
                                <span class="material-symbols-outlined text-[18px]">add</span>
                            </button>
                        </div>
                    @endforeach
                    
                    <div id="no-result" class="hidden py-10 text-center flex-col items-center justify-center h-full">
                        <span class="material-symbols-outlined text-gray-300 text-4xl mb-2">search_off</span>
                        <p class="text-sm font-bold text-text-muted">검색 결과가 없습니다.</p>
                    </div>
                </div>
            </div>

            {{-- 오른쪽: 선택된 상품 목록 --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between border-b border-gray-200 pb-3">
                    <h3 class="text-sm font-extrabold text-text-main flex items-center gap-2">
                        <span class="material-symbols-outlined text-green-500 text-[20px]">check_circle</span> 
                        선택된 상품
                    </h3>
                    <span id="selected-count" class="px-3 py-1 bg-green-100 text-green-700 text-[11px] font-black rounded-full">
                        0개 선택됨
                    </span>
                </div>

                <div id="hidden-inputs-container"></div>

                <div id="selected-products" class="bg-white border border-gray-200 rounded-2xl h-[405px] overflow-y-auto overflow-x-hidden shadow-inner p-2 space-y-2">
                    @php
                        $selectedProductIds = old('product_ids', $exhibition?->products->pluck('id')->toArray() ?? []);
                    @endphp
                    
                    @foreach($products as $product)
                        @if(in_array($product->id, $selectedProductIds))
                            <div class="selected-item flex items-center justify-between p-3 bg-green-50/50 border border-green-100 rounded-xl" data-id="{{ $product->id }}">
                                <div class="flex-1 pr-4">
                                    <p class="text-sm font-bold text-text-main line-clamp-1 truncate">{{ $product->name }}</p>
                                    <p class="text-[11px] font-black text-primary mt-1">₩{{ number_format($product->sale_price ?? $product->price) }}</p>
                                </div>
                                <button type="button" class="btn-remove shrink-0 size-8 flex items-center justify-center bg-white text-red-500 border border-red-100 rounded-xl hover:bg-red-50 transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">close</span>
                                </button>
                            </div>
                        @endif
                    @endforeach
                    
                    <div id="empty-selection" class="{{ count($selectedProductIds) > 0 ? 'hidden' : 'flex' }} py-10 text-center flex-col items-center justify-center h-full">
                        <div class="size-12 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                            <span class="material-symbols-outlined text-gray-400 text-2xl">shopping_bag</span>
                        </div>
                        <p class="text-sm font-bold text-text-muted">선택된 상품이 없습니다.</p>
                        <p class="text-[11px] text-gray-400 mt-1">왼쪽 목록에서 추가해주세요.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="rounded-2xl bg-red-50 border border-red-100 px-4 py-3 text-[12px] font-bold text-red-600 shadow-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="space-y-4">
        {{-- 삭제 안내 문구 (수정 시에만 표시) ✨ --}}
        @if($exhibition && $exhibition->exists)
            <div class="flex items-center gap-2 px-1">
                <span class="material-symbols-outlined text-[16px] text-rose-500">warning</span>
                <p class="text-[11px] font-bold text-rose-600/70 italic">기획전 삭제 시 즉시 목록에서 제외되며, 휴지통에서 복구하거나 영구 삭제할 수 있습니다. 😊</p>
            </div>
        @endif

        <div class="flex flex-col sm:flex-row gap-3">
            {{-- 메인 저장 버튼 ✨ --}}
            <button type="submit" class="flex-1 px-5 py-4 bg-primary text-white rounded-2xl text-sm font-extrabold hover:bg-red-600 transition-colors shadow-lg shadow-primary/20">
                {{ $submitLabel }}
            </button>

            {{-- 수정 시에만 노출되는 삭제 버튼 --}}
            @if($exhibition && $exhibition->exists)
                <button 
                    type="button" 
                    onclick="if(confirm('이 기획전을 삭제하시겠습니까?')) document.getElementById('delete-exhibition-form').submit();"
                    class="px-8 py-4 bg-white border border-rose-200 text-rose-600 rounded-2xl text-sm font-extrabold hover:bg-rose-50 transition-colors">
                    기획전 삭제
                </button>
            @endif
        </div>
    </div>
</form>

{{-- 삭제 처리를 위한 숨겨진 폼 🕵️‍♀️ --}}
@if($exhibition && $exhibition->exists)
    <form id="delete-exhibition-form" action="{{ route('admin.exhibitions.destroy', $exhibition) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
@endif

@push('scripts')
<script>
    $(document).ready(function() {
        const initialSelectedIds = @json($selectedProductIds);
        initialSelectedIds.forEach(id => {
            $(`.product-item[data-id="${id}"]`).hide().addClass('is-selected');
            addHiddenInput(id);
        });
        updateCount();

        // 1. 배너 이미지 관리 (클릭 & 드래그 앤 드롭) 📸 ✨
        const $bannerInput = $('#banner-input');
        const $bannerPreview = $('#banner-preview');
        const $placeholder = $('#upload-placeholder');
        const $dragOverlay = $('#drag-overlay');

        function handleFiles(files) {
            const file = files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $bannerPreview.attr('src', e.target.result).removeClass('hidden').addClass('block');
                    $placeholder.addClass('hidden');
                }
                reader.readAsDataURL(file);
            }
        }

        // 클릭으로 선택 시
        $bannerInput.on('change', function() {
            handleFiles(this.files);
        });

        // 드래그 앤 드롭 이벤트 🚀
        const zone = document.getElementById('banner-drop-zone');

        ['dragenter', 'dragover'].forEach(eventName => {
            zone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                $dragOverlay.removeClass('hidden').addClass('flex');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            zone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                $dragOverlay.addClass('hidden').removeClass('flex');
            }, false);
        });

        zone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            // 인풋에 파일 할당 (DataTransfer 활용 ✨)
            document.getElementById('banner-input').files = files;
            handleFiles(files);
        }, false);


        // 2. 상품 실시간 검색 🔍
        $('#product-search').on('input', function() {
            const query = $(this).val().toLowerCase();
            let visibleCount = 0;

            $('.product-item').not('.is-selected').each(function() {
                const name = $(this).data('name');
                if (name.includes(query)) {
                    $(this).show();
                    visibleCount++;
                } else {
                    $(this).hide();
                }
            });

            $('#no-result').toggle(visibleCount === 0);
        });

        // 3. 상품 추가 버튼 ✨
        $(document).on('click', '.btn-add', function() {
            const $item = $(this).closest('.product-item');
            const id = $item.data('id');
            const name = $item.find('.product-name').text();
            const price = $item.find('.product-price').text();

            $item.hide().addClass('is-selected');

            const selectedHtml = `
                <div class="selected-item flex items-center justify-between p-3 bg-green-50/50 border border-green-100 rounded-xl" data-id="${id}">
                    <div class="flex-1 pr-4">
                        <p class="text-sm font-bold text-text-main line-clamp-1 truncate">${name}</p>
                        <p class="text-[11px] font-black text-primary mt-1">${price}</p>
                    </div>
                    <button type="button" class="btn-remove shrink-0 size-8 flex items-center justify-center bg-white text-red-500 border border-red-100 rounded-xl hover:bg-red-50 transition-colors">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
            `;
            $('#selected-products').append(selectedHtml);
            addHiddenInput(id);
            checkEmptySelection();
            updateCount();
            $('#product-search').trigger('input');
        });

        // 4. 상품 제거 버튼 🛑
        $(document).on('click', '.btn-remove', function() {
            const $selectedItem = $(this).closest('.selected-item');
            const id = $selectedItem.data('id');

            $selectedItem.remove();
            $(`.product-item[data-id="${id}"]`).removeClass('is-selected');
            $(`input[name="product_ids[]"][value="${id}"]`).remove();

            checkEmptySelection();
            updateCount();
            $('#product-search').trigger('input');
        });

        function addHiddenInput(id) {
            if ($(`input[name="product_ids[]"][value="${id}"]`).length === 0) {
                $('#hidden-inputs-container').append(`<input type="hidden" name="product_ids[]" value="${id}">`);
            }
        }

        function checkEmptySelection() {
            const count = $('.selected-item').length;
            if (count === 0) {
                $('#empty-selection').removeClass('hidden').addClass('flex');
            } else {
                $('#empty-selection').addClass('hidden').removeClass('flex');
            }
        }

        function updateCount() {
            const count = $('.selected-item').length;
            $('#selected-count').text(`${count}개 선택됨`);
        }
    });
</script>
@endpush
