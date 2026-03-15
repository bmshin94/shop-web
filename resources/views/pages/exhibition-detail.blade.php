@extends('layouts.app')

@section('title', $exhibition->title . ' - 기획전 상세')

@section('content')
<main class="flex-1 w-full bg-white pb-20">
    {{-- 기획전 상태 변수 설정 --}}
    @php
        $isUpcoming = $exhibition->status === '진행예정';
    @endphp

    <!-- Breadcrumb (간결한 경로 표시) -->
    <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 flex items-center gap-2 text-[11px] font-bold text-text-muted uppercase tracking-widest text-center justify-center lg:justify-start">
        <a href="/" class="hover:text-primary transition-colors">홈</a>
        <span class="material-symbols-outlined text-[14px] opacity-30">chevron_right</span>
        <a href="{{ route('exhibition.index') }}" class="hover:text-primary transition-colors">기획전</a>
        <span class="material-symbols-outlined text-[14px] opacity-30">chevron_right</span>
        <span class="text-text-main">{{ $exhibition->title }}</span>
    </nav>

    <!-- Page Header (라운드 배너 스타일) -->
    <div class="relative h-[300px] md:h-[450px] overflow-hidden mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="w-full h-full rounded-3xl overflow-hidden relative shadow-2xl">
            <img src="{{ $exhibition->banner_image_url ?? 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?q=80&w=2070' }}" 
                 class="w-full h-full object-cover {{ $isUpcoming ? 'grayscale' : '' }}" />
            
            <div class="absolute inset-0 bg-black/40 flex flex-col justify-center items-center text-center px-4 text-white">
                @if($isUpcoming)
                    <div class="mb-6 inline-flex items-center gap-2 px-6 py-2 bg-primary text-white rounded-full w-fit animate-pulse shadow-xl">
                        <span class="material-symbols-outlined text-[18px]">schedule</span>
                        <span class="text-[12px] font-black uppercase tracking-[0.2em]">Coming Soon</span>
                    </div>
                @else
                    <span class="text-xs font-black mb-4 uppercase tracking-[0.3em] opacity-80">스페셜 컬렉션</span>
                @endif

                <h2 class="text-3xl md:text-6xl font-black mb-8 uppercase tracking-tighter leading-tight drop-shadow-lg break-keep max-w-3xl">
                    {!! nl2br(e($exhibition->title)) !!}
                </h2>
                
                @if($isUpcoming)
                    <div class="bg-white/20 backdrop-blur-md px-10 py-4 rounded-full border border-white/30 font-black text-lg">
                        {{ optional($exhibition->start_at)->format('m월 d일') }} 오픈 예정
                    </div>
                @else
                    <div class="w-16 h-1 bg-primary mb-8 rounded-full"></div>
                    <p class="max-w-xl text-sm md:text-lg opacity-90 leading-relaxed break-keep font-medium">{{ $exhibition->summary }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Description Section -->
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16 border-b border-gray-50">
        <div class="max-w-3xl mx-auto text-center">
            <p class="text-text-muted text-sm md:text-base leading-loose whitespace-pre-wrap break-keep">{{ $exhibition->description }}</p>
        </div>
    </div>

    <!-- Product Grid Area (product-card와 완벽하게 UI 통일 ✨) -->
    <div id="product-list" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-20">
        
        {{-- Top Toolbar --}}
        <div class="mb-12 flex flex-col sm:flex-row items-center justify-between gap-6 border-b border-gray-100 pb-8">
            <div class="flex items-center gap-4">
                <h3 class="text-2xl md:text-3xl font-black text-text-main uppercase tracking-tighter">컬렉션 아이템</h3>
                <span class="px-3 py-1 bg-gray-50 text-[11px] font-bold text-text-muted rounded-full border border-gray-100">
                    {{ number_format($exhibition->products->count()) }} Items
                </span>
            </div>

            @if(!$isUpcoming && $exhibition->products->count() > 0)
            <div class="flex flex-wrap items-center justify-center sm:justify-end gap-4 w-full sm:w-auto">
                <label class="flex items-center gap-2.5 bg-white px-4 py-2.5 rounded-xl border border-gray-200 cursor-pointer hover:border-primary transition-all shadow-sm group">
                    <input type="checkbox" id="select-all-products" class="size-4.5 rounded border-gray-300 text-primary focus:ring-primary/20 transition-all cursor-pointer">
                    <span class="text-[13px] font-bold text-text-main group-hover:text-primary transition-colors uppercase tracking-tight">전체 선택</span>
                </label>
            </div>
            @endif
        </div>

        {{-- Grid (product-list.blade.php와 동일한 그리드 설정 ✨) --}}
        <div class="grid gap-x-6 gap-y-12 grid-cols-2 md:grid-cols-3 xl:grid-cols-4 {{ $isUpcoming ? 'opacity-50 grayscale pointer-events-none' : '' }}">
            @forelse($exhibition->products as $product)
            <div class="group relative flex flex-col h-full">
                {{-- 상품 이미지 및 배지 영역 (product-card.blade.php 구조 100% 복제 ✨) --}}
                <div class="relative aspect-[3/4] w-full overflow-hidden rounded-lg bg-gray-200 shadow-sm">
                    <a href="{{ route('product-detail', ['slug' => $product->slug]) }}">
                        <div class="h-full w-full bg-cover bg-center transition-transform duration-500 group-hover:scale-105 {{ $product->status === '품절' ? 'grayscale-[0.5] opacity-60' : '' }}"
                            style="background-image: url('{{ $product->image_url ?? 'https://via.placeholder.com/500x667' }}');"></div>
                    </a>
                    
                    @if($product->status === '품절')
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <span class="bg-black/60 text-white px-4 py-2 rounded-lg text-sm font-black border border-white/20 backdrop-blur-sm">SOLD OUT</span>
                    </div>
                    @endif

                    {{-- 기획전 전용 체크박스 (좌측 상단 고정) --}}
                    @if(!$isUpcoming)
                    <div class="absolute left-3 top-3 z-30">
                        <input type="checkbox" 
                               name="selected_products[]" 
                               value="{{ $product->id }}"
                               class="product-checkbox size-6 rounded-lg border-white/50 bg-white/20 backdrop-blur-md text-primary focus:ring-primary shadow-lg transition-all cursor-pointer checked:bg-primary"
                               data-name="{{ $product->name }}"
                               data-has-options="{{ ($product->colors->count() > 0 || $product->sizes->count() > 0) ? 'true' : 'false' }}">
                    </div>
                    @endif
                    
                    {{-- 찜(Heart) 버튼 (product-card와 동일 위치) --}}
                    <button type="button" 
                            class="btn-toggle-wishlist absolute right-3 top-3 rounded-full bg-white/90 p-2 backdrop-blur-sm transition-all hover:scale-110 active:scale-95 z-20 shadow-md group/heart" 
                            data-id="{{ $product->id }}">
                        <span class="material-symbols-outlined block text-[20px] transition-colors {{ $product->is_wishlisted ? 'filled text-red-500' : 'text-gray-400 group-hover/heart:text-red-500' }}" 
                              style="{{ $product->is_wishlisted ? "font-variation-settings: 'FILL' 1;" : '' }}">
                            favorite
                        </span>
                    </button>

                    {{-- 배지 (Best, New) - 체크박스 위치 고려하여 top-12 배치 --}}
                    <div class="absolute top-12 left-3 flex flex-col gap-1.5 pointer-events-none">
                        @if($product->is_best)
                        <span class="inline-flex items-center gap-1 rounded-full bg-background-dark/90 backdrop-blur-md px-3 py-1 text-[10px] font-black text-yellow-400 shadow-xl tracking-tighter">
                            <span class="material-symbols-outlined text-[12px] filled" style="font-variation-settings: 'FILL' 1;">star</span>
                            BEST
                        </span>
                        @endif

                        @if($product->is_new)
                        <span class="inline-flex items-center gap-1 rounded-full bg-primary/90 backdrop-blur-md px-3 py-1 text-[10px] font-black text-white shadow-xl shadow-primary/20 tracking-tighter">
                            <span class="size-1.5 rounded-full bg-white animate-pulse"></span>
                            NEW
                        </span>
                        @endif
                    </div>
                </div>

                {{-- 상품 정보 영역 (product-card.blade.php 스타일 완벽 동기화 ✨) --}}
                <div class="mt-4 flex flex-1 flex-col px-1">
                    <h4 class="text-base font-bold text-text-main hover:text-primary transition-colors line-clamp-1">
                        <a href="{{ route('product-detail', ['slug' => $product->slug]) }}">{{ $product->name }}</a>
                    </h4>
                    
                    @if($product->brief_description)
                    <p class="text-xs text-text-muted mt-1 mb-2 line-clamp-1">
                        {{ $product->brief_description }}
                    </p>
                    @endif

                    {{-- 컬러 스와치 (product-card 스타일) --}}
                    @if($product->colors->count() > 0)
                    <div class="flex gap-1 py-1 mb-2">
                        @foreach($product->colors->take(6) as $color)
                        <span class="size-3 rounded-full ring-1 ring-gray-200 shadow-sm" style="background-color: {{ $color->hex_code }}" title="{{ $color->name }}"></span>
                        @endforeach
                    </div>
                    @endif

                    <div class="mt-2 flex items-center justify-between">
                        <div class="flex flex-col">
                            @if($product->discount_rate > 0)
                            <span class="text-xs text-red-500 font-bold">
                                {{ $product->discount_rate }}%
                                <span class="text-text-muted font-normal line-through ml-1 opacity-50">₩{{ number_format($product->price) }}</span>
                            </span>
                            <span class="text-lg font-bold text-text-main tracking-tight">₩{{ number_format($product->sale_price) }}</span>
                            @else
                            <span class="text-lg font-bold text-text-main tracking-tight">₩{{ number_format($product->price) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-32 text-center bg-gray-50 rounded-3xl border border-dashed border-gray-200">
                <span class="material-symbols-outlined text-[48px] text-gray-300 mb-4 opacity-50">shopping_bag</span>
                <p class="text-sm font-bold text-text-muted">현재 연결된 컬렉션 상품이 없습니다.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Bottom Navigation -->
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-40 mt-20">
        <div class="flex flex-col items-center pt-24 border-t border-gray-100">
            <a href="{{ route('exhibition.index') }}" 
               class="group flex flex-col items-center gap-5 text-text-muted hover:text-text-main transition-all duration-500">
                <div class="size-14 rounded-full border border-gray-200 flex items-center justify-center group-hover:border-primary group-hover:bg-primary group-hover:text-white group-hover:scale-110 transition-all shadow-sm">
                    <span class="material-symbols-outlined text-[24px]">grid_view</span>
                </div>
                <div class="text-center">
                    <span class="block text-[12px] font-black uppercase tracking-[0.3em] mb-1">Back to list</span>
                    <span class="block text-[10px] font-bold opacity-50">전체 기획전 목록</span>
                </div>
            </a>
        </div>
    </div>
</main>

{{-- 멀티 선택 스티키 액션 바 --}}
<div id="selection-bar" class="fixed inset-x-0 bottom-0 z-[90] transform translate-y-full transition-transform duration-500 bg-white/80 backdrop-blur-xl border-t border-gray-100 shadow-[0_-10px_40px_rgba(0,0,0,0.05)]">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 md:py-8 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="size-12 rounded-2xl bg-primary text-white flex items-center justify-center shadow-lg shadow-primary/20">
                <span id="selected-count-badge" class="text-lg font-black">0</span>
            </div>
            <div>
                <p class="text-sm font-black text-text-main">상품이 선택되었습니다.</p>
                <p class="text-[11px] font-bold text-text-muted uppercase tracking-wider">Selected items for bulk action</p>
            </div>
        </div>
        
        <div class="flex items-center gap-3 w-full md:w-auto">
            <button type="button" id="btn-bulk-action" class="flex-1 md:flex-none px-10 py-4 bg-gray-50 text-text-main text-sm font-black rounded-2xl border border-gray-100 hover:bg-primary hover:text-white hover:border-primary transition-all" data-action="cart">
                선택 상품 장바구니
            </button>
            <button type="button" id="btn-bulk-action" class="flex-1 md:flex-none px-12 py-4 bg-text-main text-white text-sm font-black rounded-2xl hover:bg-black transition-all shadow-xl" data-action="buy">
                선택 상품 바로구매
            </button>
        </div>
    </div>
</div>

{{-- 퀵 옵션 선택 모달 (로직 유지) --}}
<div id="quick-view-modal" class="fixed inset-0 z-[100] hidden flex items-end md:items-center justify-center p-0 md:p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm modal-close opacity-0 transition-opacity duration-300" id="modal-bg"></div>
    <div class="relative bg-white w-full max-w-3xl rounded-t-[2.5rem] md:rounded-[2.5rem] shadow-2xl overflow-hidden transform transition-all duration-300 translate-y-full md:translate-y-4 opacity-0 md:scale-95" id="modal-content">
        <div class="flex flex-col h-[85vh] md:h-auto max-h-[800px]">
            <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between shrink-0">
                <div>
                    <h3 class="text-2xl font-black text-text-main tracking-tighter">상품 옵션 확인</h3>
                    <p class="text-[11px] font-bold text-text-muted uppercase tracking-wider mt-1">Select options for your items</p>
                </div>
                <button type="button" class="size-12 flex items-center justify-center rounded-full bg-gray-50 text-gray-400 hover:text-text-main transition-colors modal-close">
                    <span class="material-symbols-outlined text-[28px]">close</span>
                </button>
            </div>
            <div id="qv-product-list" class="flex-1 overflow-y-auto p-8 space-y-10 custom-scrollbar"></div>
            <div class="px-8 py-8 bg-gray-50 border-t border-gray-100 shrink-0">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold text-text-muted">총 선택 상품</span>
                        <span id="qv-total-count" class="text-lg font-black text-text-main">0개</span>
                    </div>
                    <div class="text-right">
                        <p class="text-[11px] font-bold text-text-muted uppercase mb-1">Total Amount</p>
                        <p id="qv-total-price" class="text-2xl font-black text-primary">₩0</p>
                    </div>
                </div>
                <button type="button" id="qv-final-submit" class="w-full py-5 bg-text-main text-white text-base font-black rounded-2xl hover:bg-black transition-all shadow-xl shadow-text-main/10 flex items-center justify-center gap-3">
                    <span id="qv-submit-label">선택 상품 담기</span>
                    <span class="material-symbols-outlined">arrow_forward</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .row-qty::-webkit-outer-spin-button, .row-qty::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    .row-qty { -moz-appearance: textfield; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f8fafc; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
@endpush

@push('scripts')
<script>
let currentAction = 'cart'; let selectedProductsData = [];
$(document).ready(function() {
    const $selectAll = $('#select-all-products'); const $productCheckboxes = $('.product-checkbox'); const $selectionBar = $('#selection-bar'); const $countBadge = $('#selected-count-badge');
    function updateSelectionUI() {
        const selectedCount = $('.product-checkbox:checked').length; $countBadge.text(selectedCount);
        if (selectedCount > 0) $selectionBar.removeClass('translate-y-full').addClass('translate-y-0'); else $selectionBar.removeClass('translate-y-0').addClass('translate-y-full');
        $selectAll.prop('checked', selectedCount === $productCheckboxes.length && $productCheckboxes.length > 0);
    }
    $selectAll.on('change', function() { $productCheckboxes.prop('checked', this.checked); updateSelectionUI(); });
    $productCheckboxes.on('change', updateSelectionUI);
    
    // 모달 오픈 트리거 수정 (일괄 액션만 대응)
    $(document).on('click', '#btn-bulk-action', function(e) {
        e.preventDefault(); currentAction = $(this).data('action');
        const targetIds = $('.product-checkbox:checked').map(function() { return $(this).val(); }).get();
        if (targetIds.length === 0) return;
        const label = currentAction === 'cart' ? '장바구니 담기' : '주문서 작성하기';
        $('#qv-submit-label').text(`${targetIds.length}개의 상품 ${label}`);
        loadBulkProductOptions(targetIds);
    });

    function loadBulkProductOptions(ids) {
        $.get('/products/bulk-quick-view', { ids: ids.join(',') }, function(products) {
            selectedProductsData = products; const $list = $('#qv-product-list').empty();
            products.forEach((product, index) => {
                const price = product.sale_price || product.price; let optionsHtml = '';
                if (product.colors?.length > 0) {
                    optionsHtml += `<div class="space-y-2"><label class="text-[10px] font-black text-text-muted uppercase tracking-tighter">색상</label><div class="flex flex-wrap gap-2" data-product-index="${index}" data-type="color">${product.colors.map(c => `<button type="button" class="btn-option-choice color-swatch size-7 rounded-full border-2 border-transparent ring-1 ring-gray-200 ring-offset-2 transition-all hover:scale-110" style="background-color: ${c.hex_code}" data-id="${c.id}" data-name="${c.name}"></button>`).join('')}</div></div>`;
                }
                if (product.sizes?.length > 0) {
                    optionsHtml += `<div class="space-y-2"><label class="text-[10px] font-black text-text-muted uppercase tracking-tighter">사이즈</label><div class="flex flex-wrap gap-1.5" data-product-index="${index}" data-type="size">${product.sizes.map(s => `<button type="button" class="btn-option-choice size-choice min-w-[36px] h-8 px-2.5 bg-white border border-gray-100 rounded-lg text-[10px] font-black text-text-muted hover:border-primary transition-all" data-id="${s.id}">${s.name}</button>`).join('')}</div></div>`;
                }
                $list.append(`<div class="qv-item-row flex flex-row items-start gap-6 pb-8 border-b border-gray-100 last:border-0 last:pb-0" data-index="${index}"><div class="w-28 md:w-36 aspect-[3/4] rounded-xl bg-gray-50 border border-gray-100 overflow-hidden shrink-0"><img src="${product.image_url || 'https://via.placeholder.com/100'}" class="w-full h-full object-cover"></div><div class="flex-1 space-y-4"><div><h4 class="font-black text-text-main text-base md:text-lg line-clamp-1 leading-tight">${product.name}</h4><p class="text-sm font-bold text-primary mt-1">₩${price.toLocaleString()}</p></div><div class="flex flex-col gap-4">${optionsHtml}<div class="space-y-2"><label class="text-[10px] font-black text-text-muted uppercase tracking-tighter">수량</label><div class="flex items-center w-24 h-8 bg-white rounded-lg border border-gray-200 p-0.5 shadow-sm overflow-hidden"><button type="button" class="w-7 h-full flex items-center justify-center text-gray-400 hover:text-primary hover:bg-primary/5 rounded-md" onclick="updateRowQty(${index}, -1)"><span class="material-symbols-outlined text-[14px]">remove</span></button><input type="number" class="row-qty w-0 flex-1 h-full bg-transparent text-center text-xs font-black text-text-main border-0 focus:border-0 focus:ring-0 outline-none p-0" value="1" readonly><button type="button" class="w-7 h-full flex items-center justify-center text-gray-400 hover:text-primary hover:bg-primary/5 rounded-md" onclick="updateRowQty(${index}, 1)"><span class="material-symbols-outlined text-[14px]">add</span></button></div></div></div></div></div>`);
                product.selectedColorName = null; product.selectedSizeName = null; product.quantity = 1;
            });
            $('#qv-total-count').text(`${products.length}개`); updateModalTotal(); openModal();
        });
    }
    $(document).on('click', '.btn-option-choice', function() {
        const $container = $(this).parent(); const index = $container.data('product-index'); const type = $container.data('type'); const id = $(this).data('id'); const name = $(this).text().trim();
        if (type === 'color') {
            const colorName = $(this).data('name');
            $container.find('.color-swatch').removeClass('ring-primary ring-2 scale-110').addClass('ring-gray-200 ring-1 scale-100');
            $(this).removeClass('ring-gray-200 ring-1').addClass('ring-primary ring-2 scale-110');
            selectedProductsData[index].selectedColorName = colorName;
        } else {
            $container.find('.btn-option-choice').removeClass('border-primary text-primary bg-primary/5').addClass('border-gray-100 text-text-muted bg-white');
            $(this).removeClass('border-gray-100 text-text-muted bg-white').addClass('border-primary text-primary bg-primary/5');
            selectedProductsData[index].selectedSizeName = name;
        }
    });
    window.updateRowQty = function(index, delta) {
        const $row = $(`.qv-item-row[data-index="${index}"]`); const $input = $row.find('.row-qty');
        const newVal = Math.max(1, parseInt($input.val()) + delta); $input.val(newVal);
        selectedProductsData[index].quantity = newVal; updateModalTotal();
    };
    function updateModalTotal() {
        let total = 0; selectedProductsData.forEach(p => total += (p.sale_price || p.price) * p.quantity);
        $('#qv-total-price').text('₩' + total.toLocaleString());
    }
    $('#qv-final-submit').on('click', function() {
        for (let i = 0; i < selectedProductsData.length; i++) {
            const p = selectedProductsData[i];
            if (p.colors?.length > 0 && !p.selectedColorName) { alert(`'${p.name}'의 색상을 선택해 주세요.`); return; }
            if (p.sizes?.length > 0 && !p.selectedSizeName) { alert(`'${p.name}'의 사이즈를 선택해 주세요.`); return; }
        }
        if (currentAction === 'cart') submitBulkCart(); else submitBulkBuy();
    });
    function submitBulkCart() {
        const requests = selectedProductsData.map(p => $.ajax({ url: '/cart', method: 'POST', data: { product_id: p.id, color: p.selectedColorName, size: p.selectedSizeName, quantity: p.quantity, _token: '{{ csrf_token() }}' } }));
        Promise.all(requests).then(() => { alert('장바구니에 담겼습니다.'); closeModal(); $('.product-checkbox').prop('checked', false); $('#select-all-products').prop('checked', false); updateSelectionUI(); }).catch(() => alert('실패했습니다.'));
    }
    function submitBulkBuy() {
        let url = '/checkout?';
        const params = selectedProductsData.map(p => {
            let pStr = `p[]=${p.id}&q[]=${p.quantity}`;
            if (p.selectedColorName) pStr += `&c[]=${p.selectedColorName}`;
            if (p.selectedSizeName) pStr += `&s[]=${p.selectedSizeName}`;
            return pStr;
        });
        window.location.href = url + params.join('&');
    }
    function openModal() { $('#quick-view-modal').removeClass('hidden'); setTimeout(() => { $('#modal-bg').addClass('opacity-100'); $('#modal-content').removeClass('translate-y-full md:translate-y-4 opacity-0 md:scale-95').addClass('translate-y-0 opacity-100 md:scale-100'); $('body').addClass('overflow-hidden'); }, 10); }
    function closeModal() { $('#modal-bg').removeClass('opacity-100').addClass('opacity-0'); $('#modal-content').removeClass('translate-y-0 opacity-100 md:scale-100').addClass('translate-y-full md:translate-y-4 opacity-0 md:scale-95'); setTimeout(() => { $('#quick-view-modal').addClass('hidden'); $('body').removeClass('overflow-hidden'); }, 300); }
    $('.modal-close').on('click', closeModal);
});
</script>
@endpush
