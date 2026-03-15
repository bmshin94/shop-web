@extends('layouts.app')

@section('title', '장바구니 - Active Women\'s Premium Store')

@section('content')
    <main class="flex-1 bg-background-alt pb-20">
      <!-- Page Title -->
      <div class="pt-12 pb-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <h2 class="text-3xl font-extrabold text-text-main tracking-tight">
            장바구니 <span class="text-primary ml-1 cart-total-count">{{ $carts->count() }}</span>
          </h2>
        </div>
      </div>

      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
          <!-- 장바구니 리스트 영역 -->
          <div class="flex-grow">
            {{-- 장바구니 비었을 때 --}}
            <div id="emptyCartState"
              class="{{ $carts->isEmpty() ? 'flex' : 'hidden' }} bg-white rounded-2xl shadow-sm border border-gray-100 p-16 flex-col items-center justify-center text-center">
              <span class="material-symbols-outlined text-6xl text-gray-200 mb-4 block">shopping_cart</span>
              <h3 class="text-xl font-bold text-text-main mb-2">장바구니에 담긴 상품이 없습니다.</h3>
              <p class="text-sm text-text-muted mb-8">액티브 우먼의 다양한 상품을 만나보세요!</p>
              <a href="{{ route('product-list') }}"
                class="inline-flex items-center justify-center px-8 py-3 bg-primary text-white font-bold rounded-xl hover:bg-red-600 transition-colors shadow-lg hover:shadow-primary/30">
                쇼핑 계속하기
              </a>
            </div>

            {{-- 장바구니 상품이 있을 때 --}}
            <div id="cartContentContainer" class="{{ $carts->isEmpty() ? 'hidden' : 'block' }} bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
              <!-- 전체선택 & 선택삭제 툴바 -->
              <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-6">
                <label class="flex items-center gap-3 cursor-pointer group">
                  <input type="checkbox" id="selectAll"
                    class="rounded border-gray-300 text-primary w-5 h-5 focus:ring-primary focus:ring-offset-0"
                    checked />
                  <span id="selectAllText"
                    class="font-bold text-text-main group-hover:text-primary transition-colors">전체 선택 (<span class="checked-count">{{ $carts->count() }}</span>/{{ $carts->count() }})</span>
                </label>
                <button type="button" id="btn-open-bulk-delete"
                  class="text-sm text-text-muted hover:text-primary font-bold transition-colors">
                  선택 삭제
                </button>
              </div>

              <div id="cartItemList" class="space-y-8">
                @foreach($carts as $cart)
                @php
                    $product = $cart->product;
                    $salePrice = $product->sale_price ?? $product->price;
                @endphp
                <div class="cart-item flex flex-col sm:flex-row gap-4 sm:gap-6 items-start border-b border-gray-50 pb-8 last:border-0 last:pb-0"
                  data-id="{{ $cart->id }}" 
                  data-product-id="{{ $product->id }}"
                  data-price="{{ $salePrice }}" 
                  data-original-price="{{ $product->price }}">
                  <div class="flex items-center pt-1">
                    <input type="checkbox"
                      class="item-checkbox rounded border-gray-300 text-primary w-5 h-5 focus:ring-primary" checked />
                  </div>
                  <div class="flex gap-4 sm:gap-6 w-full">
                    <a href="{{ route('product-detail', $product->slug) }}"
                      class="w-24 h-32 sm:w-32 sm:h-40 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100 block group relative border border-gray-100 shadow-sm">
                      <div
                        class="w-full h-full bg-cover bg-center transition-transform duration-500 group-hover:scale-105"
                        style="background-image: url('{{ optional($product->images->first())->image_url ?? 'https://via.placeholder.com/300x400' }}');"></div>
                    </a>
                    <div class="flex-grow flex flex-col justify-between">
                      <div>
                        <div class="flex justify-between items-start gap-4">
                          <a href="{{ route('product-detail', $product->slug) }}"
                            class="font-bold text-lg text-text-main hover:text-primary transition-colors pr-4">{{ $product->name }}</a>
                          <button type="button" class="btn-open-delete text-gray-400 hover:text-text-main p-1 -mr-2 -mt-2" data-id="{{ $cart->id }}">
                            <span class="material-symbols-outlined text-xl">close</span>
                          </button>
                        </div>
                        <div class="mt-2 p-3 bg-background-alt rounded-lg flex justify-between items-center text-sm border border-gray-50">
                          <span class="text-text-muted font-medium">
                            단품: 
                            <span class="item-option-text">{{ $cart->color ?: '기본색상' }} / {{ $cart->size ?: '기본사이즈' }}</span>
                          </span>
                          <button type="button" class="btn-change-option text-xs font-bold text-primary hover:underline"
                            data-id="{{ $cart->id }}" 
                            data-product-id="{{ $product->id }}"
                            data-current-color="{{ $cart->color }}"
                            data-current-size="{{ $cart->size }}">
                            옵션 변경
                          </button>
                        </div>
                      </div>
                      <div class="flex justify-between items-end mt-4">
                        <div class="flex items-center border border-gray-200 rounded-lg bg-white overflow-hidden h-9">
                          <button
                            class="qty-btn qty-minus w-9 h-full flex items-center justify-center hover:bg-gray-50 text-text-main font-bold focus:outline-none transition-colors"
                            data-id="{{ $cart->id }}" data-delta="-1">
                            <span class="material-symbols-outlined text-sm">remove</span>
                          </button>
                          <span
                            class="qty-display w-10 h-full flex items-center justify-center border-x border-gray-200 text-sm font-bold bg-gray-50/50 text-text-main">{{ $cart->quantity }}</span>
                          <button
                            class="qty-btn qty-plus w-9 h-full flex items-center justify-center hover:bg-gray-50 text-text-main font-bold focus:outline-none transition-colors"
                            data-id="{{ $cart->id }}" data-delta="1">
                            <span class="material-symbols-outlined text-sm">add</span>
                          </button>
                        </div>
                        <div class="text-right">
                          @if($product->sale_price)
                          <div class="item-original-price-display text-xs text-gray-400 line-through mb-0.5">
                            ₩{{ number_format($product->price * $cart->quantity) }}
                          </div>
                          @endif
                          <div class="item-price-display text-xl font-bold text-text-main tracking-tight">
                            ₩{{ number_format($salePrice * $cart->quantity) }}
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>

              <!-- 배송비 안내 -->
              <div
                class="mt-10 py-6 px-4 bg-primary/5 rounded-xl border border-primary/10 flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <span class="material-symbols-outlined text-primary text-2xl">local_shipping</span>
                  <div>
                    <h4 class="font-bold text-text-main text-sm">배송비 걱정 없어요!</h4>
                    <p class="text-xs text-text-muted mt-0.5">현재 장바구니 결제 금액 50,000원 이상 무조건 <strong>무료배송</strong></p>
                  </div>
                </div>
                <a href="{{ route('product-list') }}" class="text-sm font-bold text-primary hover:text-red-700 hover:underline">상품 더 담기</a>
              </div>
            </div>
          </div>

          <!-- 주문 요약 -->
          <div class="w-full lg:w-[400px] flex-shrink-0">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 sticky top-[130px]">
              <h3 class="font-bold text-xl text-text-main border-b border-gray-200 pb-4 mb-6">
                주문 요약 (총 <span class="checked-count">{{ $carts->count() }}</span>건)
              </h3>
              <div class="space-y-4 text-base mb-8">
                <div class="flex justify-between text-text-muted font-medium">
                  <span>상품 총 금액</span>
                  <span id="summaryOriginalTotal" class="text-text-main">₩{{ number_format($totalOriginalPrice) }}</span>
                </div>
                <div class="flex justify-between text-text-muted font-medium">
                  <span>할인 적용 금액</span>
                  <span id="summaryDiscount" class="text-red-500 font-bold">- ₩{{ number_format($totalDiscount) }}</span>
                </div>
                <div class="flex justify-between text-text-muted font-medium">
                  <span>배송비</span>
                  <span id="summaryShipping" class="text-text-main font-bold">{{ $shippingFee > 0 ? '₩'.number_format($shippingFee) : '무료' }}</span>
                </div>
              </div>
              <div class="border-t-2 border-text-main pt-6 mb-8">
                <div class="flex justify-between items-end">
                  <span class="font-bold text-text-main text-lg">최종 결제 금액</span>
                  <span id="summaryFinalTotal"
                    class="font-extrabold text-3xl text-primary tracking-tight">₩{{ number_format($finalTotal) }}</span>
                </div>
                <p id="summaryPoints" class="text-right text-xs font-bold text-primary/80 mt-2">최대 {{ number_format($finalTotal * 0.01) }}원 적립 예정</p>
              </div>
              <button id="btn-checkout" class="w-full bg-primary text-white font-extrabold text-lg rounded-xl py-4 hover:bg-red-600 transition-colors shadow-lg active:scale-95 transition-transform">
                결제하기 (<span class="checked-count">{{ $carts->count() }}</span>)
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>

    {{-- 1. 옵션 변경 모달 --}}
    <div id="option-modal" class="fixed inset-0 z-[100] hidden flex items-end md:items-center justify-center p-0 md:p-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm modal-close opacity-0 transition-opacity duration-300" id="modal-bg"></div>
        <div class="relative bg-white w-full max-w-lg rounded-t-[2.5rem] md:rounded-[2.5rem] shadow-2xl overflow-hidden transform transition-all duration-300 translate-y-full md:translate-y-4 opacity-0 md:scale-95" id="modal-content">
            <div class="p-8 md:p-10 text-text-main">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl font-black tracking-tighter text-text-main">옵션 변경</h3>
                    <button type="button" class="size-12 flex items-center justify-center rounded-full bg-gray-50 text-gray-400 hover:text-text-main transition-colors modal-close">
                        <span class="material-symbols-outlined text-[28px]">close</span>
                    </button>
                </div>
                <div class="flex gap-6 mb-8">
                    <div class="w-24 aspect-[3/4] rounded-2xl bg-gray-50 border border-gray-100 overflow-hidden shrink-0 shadow-sm">
                        <img src="" id="qv-image" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 space-y-2">
                        <h4 id="qv-name" class="font-black text-lg line-clamp-2">상품명</h4>
                        <p id="qv-price" class="text-xl font-bold text-primary">₩0</p>
                    </div>
                </div>
                <div id="modal-options-area" class="space-y-6"></div>
                <button type="button" id="btn-confirm-option" class="w-full py-5 bg-text-main text-white text-base font-black rounded-2xl hover:bg-black transition-all mt-10 shadow-xl">
                    옵션 변경 적용하기
                </button>
            </div>
        </div>
    </div>

    {{-- 2. 삭제 확인 모달 (투박한 confirm 대신 ✨) --}}
    <div id="delete-confirm-modal" class="fixed inset-0 z-[110] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm modal-close opacity-0 transition-opacity duration-300" id="delete-modal-bg"></div>
        <div class="relative bg-white w-full max-w-sm rounded-[2rem] shadow-2xl overflow-hidden transform transition-all duration-300 translate-y-4 opacity-0 scale-95" id="delete-modal-content">
            <div class="p-8 text-center">
                <div class="size-16 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="material-symbols-outlined text-3xl">delete_forever</span>
                </div>
                <h3 class="text-xl font-black text-text-main mb-2">상품을 삭제할까요?</h3>
                <p class="text-sm text-text-muted mb-8 leading-relaxed">장바구니에서 선택하신 상품이 <br/>영구적으로 제거됩니다.</p>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" class="py-4 bg-gray-50 text-text-muted font-bold rounded-2xl hover:bg-gray-100 transition-colors modal-close">취소</button>
                    <button type="button" id="btn-confirm-delete" class="py-4 bg-rose-500 text-white font-black rounded-2xl hover:bg-rose-600 transition-colors shadow-lg shadow-rose-500/20">삭제하기</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .qty-display { min-width: 2.5rem; }
    .btn-color-swatch.active { ring-color: var(--color-primary) !important; ring-width: 2px !important; transform: scale(1.1); }
    .btn-size-choice.active { border-color: var(--color-primary); color: var(--color-primary); background-color: rgba(var(--color-primary-rgb), 0.05); }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    let currentCartId = null;
    let selectedColorName = null;
    let selectedSizeName = null;
    let deleteMode = 'single'; // 'single' or 'bulk'
    let pendingDeleteId = null;

    /**
     * 장바구니 요약 정보 업데이트
     */
    function updateCartSummary() {
        let totalOriginal = 0;
        let totalSale = 0;
        let count = 0;

        $('.cart-item').each(function() {
            if ($(this).find('.item-checkbox').is(':checked')) {
                const qty = parseInt($(this).find('.qty-display').text());
                const price = parseInt($(this).data('price'));
                const originalPrice = parseInt($(this).data('original-price'));
                totalOriginal += originalPrice * qty;
                totalSale += price * qty;
                count++;
            }
        });

        const discount = totalOriginal - totalSale;
        const shipping = (totalSale >= 50000 || count === 0) ? 0 : 3000;
        const finalTotal = totalSale + shipping;

        $('#summaryOriginalTotal').text('₩' + totalOriginal.toLocaleString());
        $('#summaryDiscount').text('- ₩' + discount.toLocaleString());
        $('#summaryShipping').text(shipping > 0 ? '₩' + shipping.toLocaleString() : '무료');
        $('#summaryFinalTotal').text('₩' + finalTotal.toLocaleString());
        $('#summaryPoints').text(`최대 ${Math.floor(finalTotal * 0.01).toLocaleString()}원 적립 예정`);
        $('.checked-count').text(count);
        $('#selectAllText').text(`전체 선택 (${count}/${$('.cart-item').length})`);
        $('#btn-checkout').prop('disabled', count === 0).css('opacity', count === 0 ? '0.5' : '1');
    }

    // 1. 수량 변경 (AJAX)
    $('.qty-btn').on('click', function() {
        const id = $(this).data('id');
        const delta = $(this).data('delta');
        const $item = $(this).closest('.cart-item');
        const $display = $item.find('.qty-display');
        let newQty = parseInt($display.text()) + delta;

        if (newQty < 1) return;

        $.ajax({
            url: `/cart/${id}`,
            method: 'PUT',
            data: { quantity: newQty, _token: '{{ csrf_token() }}' },
            success: function() {
                $display.text(newQty);
                const price = parseInt($item.data('price'));
                const originalPrice = parseInt($item.data('original-price'));
                $item.find('.item-price-display').text('₩' + (price * newQty).toLocaleString());
                $item.find('.item-original-price-display').text('₩' + (originalPrice * newQty).toLocaleString());
                updateCartSummary();
            }
        });
    });

    // 2. 옵션 변경 모달 열기 (기존 선택 사항 표시 ✨)
    $('.btn-change-option').on('click', function() {
        currentCartId = $(this).data('id');
        const productId = $(this).data('product-id');
        const currentColor = $(this).data('currentColor');
        const currentSize = $(this).data('currentSize');
        
        selectedColorName = currentColor; // 초기값 설정 ✨
        selectedSizeName = currentSize;

        $.get(`/products/${productId}/quick-view`, function(data) {
            $('#qv-image').attr('src', data.image_url);
            $('#qv-name').text(data.name);
            $('#qv-price').text('₩' + (data.sale_price || data.price).toLocaleString());
            
            let html = '';
            // 색상 옵션
            if (data.colors?.length > 0) {
                html += `<div class="space-y-3"><label class="text-[10px] font-black text-text-muted uppercase tracking-tighter">색상</label><div class="flex flex-wrap gap-2.5">`;
                data.colors.forEach(c => {
                    const isActive = c.name === currentColor; // 현재 값 비교 ✨
                    html += `<button type="button" class="btn-color-swatch size-8 rounded-full border-2 border-transparent ring-1 ring-gray-200 ring-offset-2 transition-all hover:scale-110 ${isActive ? 'ring-primary ring-2 scale-110' : ''}" style="background-color: ${c.hex_code}" data-name="${c.name}"></button>`;
                });
                html += `</div></div>`;
            }
            // 사이즈 옵션
            if (data.sizes?.length > 0) {
                html += `<div class="space-y-3"><label class="text-[10px] font-black text-text-muted uppercase tracking-tighter">사이즈</label><div class="flex flex-wrap gap-2">`;
                data.sizes.forEach(s => {
                    const isActive = s.name === currentSize; // 현재 값 비교 ✨
                    html += `<button type="button" class="btn-size-choice min-w-[40px] h-9 px-3 bg-white border border-gray-100 rounded-lg text-[11px] font-black text-text-muted hover:border-primary transition-all ${isActive ? 'border-primary text-primary bg-primary/5' : ''}" data-name="${s.name}">${s.name}</button>`;
                });
                html += `</div></div>`;
            }
            $('#modal-options-area').html(html);
            openModal('#option-modal');
        });
    });

    $(document).on('click', '.btn-color-swatch', function() {
        $('.btn-color-swatch').removeClass('ring-primary ring-2 scale-110').addClass('ring-gray-200 ring-1 scale-100');
        $(this).removeClass('ring-gray-200 ring-1').addClass('ring-primary ring-2 scale-110');
        selectedColorName = $(this).data('name');
    });

    $(document).on('click', '.btn-size-choice', function() {
        $('.btn-size-choice').removeClass('border-primary text-primary bg-primary/5').addClass('border-gray-100 text-text-muted bg-white');
        $(this).removeClass('border-gray-100 text-text-muted bg-white').addClass('border-primary text-primary bg-primary/5');
        selectedSizeName = $(this).data('name');
    });

    $('#btn-confirm-option').on('click', function() {
        $.ajax({
            url: `/cart/${currentCartId}`,
            method: 'PUT',
            data: { color: selectedColorName, size: selectedSizeName, _token: '{{ csrf_token() }}' },
            success: function() { location.reload(); }
        });
    });

    // 3. 삭제 처리 (커스텀 모달 기반 ✨)
    // 개별 삭제 버튼
    $('.btn-open-delete').on('click', function() {
        deleteMode = 'single';
        pendingDeleteId = $(this).data('id');
        openModal('#delete-confirm-modal');
    });

    // 일괄 삭제 버튼
    $('#btn-open-bulk-delete').on('click', function() {
        const checkedCount = $('.item-checkbox:checked').length;
        if (checkedCount === 0) { alert('삭제할 상품을 선택해 주세요.'); return; }
        deleteMode = 'bulk';
        openModal('#delete-confirm-modal');
    });

    // 최종 삭제 실행 (동적 UI 업데이트 ✨)
    $('#btn-confirm-delete').on('click', function() {
        if (deleteMode === 'single') {
            $.ajax({
                url: `/cart/${pendingDeleteId}`,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function() {
                    const $item = $(`.cart-item[data-id="${pendingDeleteId}"]`);
                    closeModal();
                    
                    $item.fadeOut(300, function() {
                        $(this).remove();
                        updateAllCounts(); // 전체 수량 UI 갱신
                        updateCartSummary();
                        checkEmptyCart();
                    });
                }
            });
        } else {
            const $checkedItems = $('.item-checkbox:checked').closest('.cart-item');
            const ids = $checkedItems.map(function() { return $(this).data('id'); }).get();
            
            $.ajax({
                url: '/cart/bulk',
                method: 'DELETE',
                data: { ids: ids, _token: '{{ csrf_token() }}' },
                success: function() {
                    closeModal();
                    
                    $checkedItems.fadeOut(300, function() {
                        $(this).remove();
                        updateAllCounts(); // 전체 수량 UI 갱신
                        updateCartSummary();
                        checkEmptyCart();
                    });
                }
            });
        }
    });

    /**
     * 장바구니 전체 수량 관련 UI 일괄 갱신 ✨
     */
    function updateAllCounts() {
        const totalItems = $('.cart-item').length;
        $('.cart-total-count').text(totalItems); // 상단 타이틀 옆 숫자
        
        // 전체 선택 텍스트 영역 갱신
        const checkedCount = $('.item-checkbox:checked').length;
        $('#selectAllText').html(`전체 선택 (<span class="checked-count">${checkedCount}</span>/${totalItems})`);
    }

    /**
     * 장바구니가 비었는지 확인 후 처리
     */
    function checkEmptyCart() {
        if ($('.cart-item').length === 0) {
            setTimeout(() => location.reload(), 500); // 텅 빈 화면 표시를 위해 새로고침
        }
    }

    // 4. 모달 범용 제어
    function openModal(selector) {
        const $modal = $(selector);
        const $bg = $modal.find('[id$="-bg"]');
        const $content = $modal.find('[id$="-content"]');
        
        $modal.removeClass('hidden');
        setTimeout(() => { 
            $bg.addClass('opacity-100'); 
            $content.removeClass('translate-y-full translate-y-4 opacity-0 scale-95').addClass('translate-y-0 opacity-100 scale-100'); 
            $('body').addClass('overflow-hidden');
        }, 10);
    }

    function closeModal() {
        $('.fixed.z-\\[100\\], .fixed.z-\\[110\\]').each(function() {
            const $modal = $(this);
            const $bg = $modal.find('[id$="-bg"]');
            const $content = $modal.find('[id$="-content"]');
            
            $bg.removeClass('opacity-100');
            $content.addClass('translate-y-full opacity-0 scale-95').removeClass('translate-y-0 opacity-100 scale-100');
            
            setTimeout(() => { $modal.addClass('hidden'); $('body').removeClass('overflow-hidden'); }, 300);
        });
    }

    $('.modal-close').on('click', closeModal);

    // 5. 기타 제어
    $('#selectAll').on('change', function() { $('.item-checkbox').prop('checked', this.checked); updateCartSummary(); });
    $(document).on('change', '.item-checkbox', updateCartSummary);
    $('#btn-checkout').on('click', function() {
        const ids = $('.item-checkbox:checked').map(function() { return $(this).closest('.cart-item').data('id'); }).get();
        window.location.href = `/checkout?cart_ids=${ids.join(',')}`;
    });
});
</script>
@endpush
