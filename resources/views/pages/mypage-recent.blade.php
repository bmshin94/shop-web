@extends('layouts.app')

@section('title', '최근 본 상품 | 마이페이지 - Active Women\'s Premium Store')

@section('content')
<main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-text-muted mb-6">
        <a href="/" class="hover:text-primary transition-colors">Home</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <a href="{{ route('mypage') }}" class="hover:text-primary transition-colors">마이페이지</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <span class="font-bold text-text-main">최근 본 상품</span>
    </nav>

    <!-- Page Title -->
    <h2 class="text-3xl font-extrabold text-text-main tracking-tight mb-8">최근 본 상품</h2>

    <div class="flex flex-col lg:flex-row gap-8 items-start">
        <!-- LNB (Left Navigation Bar) -->
        @include('partials.mypage-sidebar')

        <!-- Main Dashboard Content -->
        <div class="flex-1 w-full space-y-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
                @if($recentViews->isNotEmpty())
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 border-b border-gray-50 pb-6 gap-4">
                    <div class="flex items-center gap-6">
                        <div class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" id="check-all" class="size-6 rounded-lg border-gray-300 text-primary focus:ring-primary/20 cursor-pointer transition-all">
                            <label for="check-all" class="text-sm font-bold text-text-muted group-hover:text-primary cursor-pointer transition-colors">전체 선택</label>
                        </div>
                        <p class="text-lg font-bold text-text-main">최근 본 상품 <span class="text-primary ml-1">{{ number_format($recentViews->flatten()->count()) }}</span></p>
                    </div>
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <button id="btn-delete-selected" class="flex-1 sm:flex-none text-xs border border-gray-200 text-text-muted rounded-xl px-5 py-2.5 hover:bg-gray-50 font-bold transition-all hover:text-primary hover:border-primary/30 flex items-center justify-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px]">delete</span>
                            선택 삭제
                        </button>
                        <button id="btn-clear-recent" class="flex-1 sm:flex-none text-xs border border-gray-200 text-text-muted rounded-xl px-5 py-2.5 hover:bg-gray-50 font-bold transition-all hover:text-red-500 hover:border-red-500/30 flex items-center justify-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px]">delete_sweep</span>
                            전체 삭제
                        </button>
                    </div>
                </div>
                
                <div class="space-y-16">
                    @foreach($recentViews as $dateLabel => $views)
                    <div class="border-l-2 border-gray-100 pl-6 py-2 relative recent-group">
                        <div class="absolute left-[-5px] top-4 size-2 rounded-full bg-primary ring-4 ring-white"></div>
                        <span class="text-sm font-black text-primary block mb-8 tracking-tight uppercase">{{ $dateLabel }}</span>
                        
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-12">
                            @foreach($views as $view)
                            @php $product = $view->product; @endphp
                            <div class="group relative flex flex-col recent-item" data-id="{{ $view->id }}">
                                <div class="recent-card relative aspect-[3/4] w-full overflow-hidden rounded-lg bg-gray-200 shadow-sm border-2 border-transparent transition-all group-hover:shadow-md">
                                    <a href="{{ route('product-detail', ['slug' => $product->slug]) }}">
                                        <div class="h-full w-full bg-cover bg-center transition-transform duration-500 group-hover:scale-105 {{ $product->status === '품절' ? 'grayscale-[0.5] opacity-60' : '' }}"
                                            style="background-image: url('{{ $product->image_url ?? asset('images/placeholder.jpg') }}');"></div>
                                    </a>
                                    
                                    @if($product->status === '품절')
                                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                        <span class="bg-black/60 text-white px-3 py-1.5 rounded-lg text-[10px] font-black border border-white/20 backdrop-blur-sm">SOLD OUT</span>
                                    </div>
                                    @endif
                                    
                                    {{-- 체크박스 --}}
                                    <div class="absolute top-3 right-3 z-10">
                                        <input type="checkbox" name="recent_ids[]" value="{{ $view->id }}" 
                                               class="recent-checkbox size-6 rounded-lg border-white bg-white/50 backdrop-blur-md text-primary focus:ring-primary/20 cursor-pointer shadow-sm transition-all hover:scale-110 checked:bg-primary">
                                    </div>

                                    {{-- 배지 (BEST) --}}
                                    <div class="absolute top-3 left-3 flex flex-col gap-1.5 pointer-events-none">
                                        @if($product->is_best)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-background-dark/90 backdrop-blur-md px-2.5 py-1 text-[9px] font-black text-yellow-400 shadow-xl tracking-tighter">
                                            BEST
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-4 flex flex-1 flex-col px-1">
                                    <h4 class="text-sm font-bold text-text-main hover:text-primary transition-colors line-clamp-1">
                                        <a href="{{ route('product-detail', ['slug' => $product->slug]) }}">{{ $product->name }}</a>
                                    </h4>
                                    @if($product->brief_description)
                                    <p class="text-[11px] text-text-muted mt-1 line-clamp-1 opacity-70 whitespace-pre-wrap">
                                        {{ $product->brief_description }}
                                    </p>
                                    @endif
                                    {{-- 컬러 옵션 --}}
                                    @if($product->colors->count() > 0)
                                    <div class="flex gap-1 py-1 mt-2">
                                        @foreach($product->colors as $color)
                                        <span class="size-2.5 rounded-full ring-1 ring-gray-200 shadow-sm" style="background-color: {{ $color->hex_code }}" title="{{ $color->name }}"></span>
                                        @endforeach
                                    </div>
                                    @endif

                                    <div class="mt-2 flex items-center justify-between gap-2">
                                        <div class="flex flex-col">
                                            @if($product->discount_rate > 0)
                                            <span class="text-[10px] text-red-500 font-bold">
                                                {{ $product->discount_rate }}%
                                                <span class="text-text-muted font-normal line-through ml-1 opacity-50">₩{{ number_format($product->price) }}</span>
                                            </span>
                                            <span class="text-md font-bold text-text-main tracking-tight">₩{{ number_format($product->sale_price) }}</span>
                                            @else
                                            <span class="text-md font-bold text-text-main tracking-tight">₩{{ number_format($product->price) }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- 개별 액션 버튼 --}}
                                    <div class="mt-4 flex gap-2">
                                        <button type="button" 
                                                class="btn-add-cart flex-1 h-9 flex items-center justify-center rounded-xl border border-gray-200 bg-white text-text-main hover:bg-gray-50 transition-all active:scale-95 group/cart"
                                                data-id="{{ $product->id }}" 
                                                data-has-options="{{ $product->colors->count() > 0 || $product->sizes->count() > 0 ? 'true' : 'false' }}">
                                            <span class="material-symbols-outlined text-[18px] group-hover/cart:text-primary transition-colors">shopping_cart</span>
                                        </button>
                                        <button type="button" 
                                                class="btn-buy-now flex-1 h-9 flex items-center justify-center rounded-xl bg-text-main text-white hover:bg-black transition-all active:scale-95 group/buy"
                                                data-id="{{ $product->id }}"
                                                data-slug="{{ $product->slug }}"
                                                data-has-options="{{ $product->colors->count() > 0 || $product->sizes->count() > 0 ? 'true' : 'false' }}">
                                            <span class="material-symbols-outlined text-[18px] group-hover/buy:scale-110 transition-transform">shopping_bag</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="flex flex-col items-center justify-center py-24 text-center border border-gray-100 rounded-3xl bg-gray-50/50">
                    <div class="size-24 rounded-full bg-gradient-to-tr from-primary/5 to-primary/20 flex items-center justify-center mx-auto mb-6 shadow-inner">
                        <span class="material-symbols-outlined text-5xl text-primary/20 scale-110">visibility_off</span>
                    </div>
                    <p class="text-text-muted font-bold text-lg">최근 본 상품이 없습니다.</p>
                    <p class="text-xs text-text-muted mt-2 mb-8">오빠가 본 상품들이 여기에 기록될 거예요!</p>
                    <a href="{{ route('product-list') }}" class="px-10 py-3.5 bg-text-main text-white text-sm font-black rounded-2xl hover:bg-primary transition-all shadow-lg shadow-gray-200 active:scale-95 block w-fit mx-auto">상품 보러가기</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</main>

{{-- 장바구니 성공 안내 모달 ✨ --}}
<div id="cartSuccessModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="relative bg-white rounded-3xl shadow-2xl max-w-sm w-full overflow-hidden animate-in fade-in zoom-in duration-200">
        <button type="button" onclick="closeModal(document.getElementById('cartSuccessModal'))" class="absolute top-4 right-4 size-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400 transition-colors">
            <span class="material-symbols-outlined text-xl">close</span>
        </button>
        <div class="p-8 text-center">
            <div class="size-16 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-3xl">shopping_basket</span>
            </div>
            <h4 class="text-xl font-bold text-text-main mb-2">장바구니 담기 완료</h4>
            <p class="text-sm text-text-muted leading-relaxed mb-8">
                선택하신 상품이 장바구니에 담겼습니다.<br>지금 확인하시겠습니까?
            </p>
            <div class="grid grid-cols-2 gap-3">
                <button type="button" onclick="closeModal(document.getElementById('cartSuccessModal'))" class="px-6 py-4 bg-gray-100 text-text-muted text-sm font-bold rounded-2xl hover:bg-gray-200 transition-colors">쇼핑 계속하기</button>
                <a href="{{ route('cart.index') }}" class="px-6 py-4 bg-text-main text-white text-sm font-bold rounded-2xl hover:bg-black transition-all shadow-lg text-center flex items-center justify-center">장바구니 확인</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Modal Functions ✨
    function openModal(modal) {
        if (!modal) return;
        modal.style.display = "flex";
        modal.classList.remove("hidden");
        document.body.style.overflow = "hidden";
    }
    function closeModal(modal) {
        if (!modal) return;
        modal.style.display = "none";
        modal.classList.add("hidden");
        document.body.style.overflow = "";
    }

    $(document).ready(function() {
        // 모달 외부 클릭 시 닫기 ✨
        $('#cartSuccessModal').on('click', function(e) {
            if (e.target === this) closeModal(this);
        });

        /**
         * 전체 선택 / 해제 로직
         */
        $('#check-all').on('change', function() {
            const isChecked = $(this).prop('checked');
            $('.recent-checkbox').prop('checked', isChecked).trigger('change');
        });

        /**
         * 개별 체크박스 상태 변경 시 시각적 효과
         */
        $(document).on('change', '.recent-checkbox', function() {
            const $card = $(this).closest('.recent-item').find('.recent-card');
            if ($(this).prop('checked')) {
                $card.addClass('border-primary shadow-lg scale-[1.02]').removeClass('border-transparent');
            } else {
                $card.removeClass('border-primary shadow-lg scale-[1.02]').addClass('border-transparent');
                $('#check-all').prop('checked', false);
            }

            const total = $('.recent-checkbox').length;
            const checked = $('.recent-checkbox:checked').length;
            if (total > 0 && total === checked) {
                $('#check-all').prop('checked', true);
            }
        });

        /**
         * 선택 삭제
         */
        $('#btn-delete-selected').on('click', function() {
            const selectedIds = $('input[name="recent_ids[]"]:checked').map(function() {
                return $(this).val();
            }).get();

            if (selectedIds.length === 0) {
                showToast('삭제할 상품을 선택해 주세요.', 'warning', 'bg-red-500');
                return;
            }

            showConfirm('선택한 상품을 최근 본 목록에서 삭제하시겠습니까?').then((res) => {
                if (res) {
                    $.ajax({
                        url: "{{ route('mypage.recent.delete-selected') }}",
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        data: { ids: selectedIds },
                        success: function(response) {
                            if (response.status === 'success') {
                                showToast(response.message, 'delete', 'bg-[#181211]');
                                selectedIds.forEach(id => {
                                    $(`.recent-item[data-id="${id}"]`).fadeOut(300, function() {
                                        const $group = $(this).closest('.recent-group');
                                        $(this).remove();
                                        if ($group.find('.recent-item').length === 0) $group.fadeOut(300, function() { $(this).remove(); });
                                        if ($('.recent-item').length === 0) location.reload();
                                    });
                                });
                            }
                        }
                    });
                }
            });
        });

        /**
         * 최근 본 상품 전체 삭제
         */
        $('#btn-clear-recent').on('click', function() {
            showConfirm('최근 본 상품 내역을 모두 삭제하시겠습니까?').then((res) => {
                if (res) {
                    $.ajax({
                        url: "{{ route('mypage.recent.clear') }}",
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.status === 'success') {
                                showToast(response.message, 'delete_sweep', 'bg-[#181211]');
                                setTimeout(() => location.reload(), 800);
                            }
                        }
                    });
                }
            });
        });

        /**
         * 개별 장바구니 담기 ✨
         */
        $(document).on('click', '.btn-add-cart', function() {
            const $btn = $(this);
            const productId = $btn.data('id');
            const hasOptions = $btn.data('hasOptions');

            if (hasOptions) {
                showToast('옵션 선택이 필요한 상품입니다. 상세 페이지로 이동합니다.', 'info', 'bg-[#181211]');
                setTimeout(() => {
                    location.href = `/product-detail/${$btn.closest('.recent-item').find('a').first().attr('href').split('/').pop()}`;
                }, 1000);
                return;
            }

            $.ajax({
                url: "{{ route('cart.store') }}",
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                data: { product_id: productId, quantity: 1 },
                success: function(response) {
                    if (response.status === 'success' || response.status === 'duplicate') {
                        // 성공 모달 띄우기! 💖
                        openModal(document.getElementById('cartSuccessModal'));
                        $('.header-cart-count').removeClass('hidden').text(response.cart_count);
                    }
                }
            });
        });

        /**
         * 개별 바로구매 ✨
         */
        $(document).on('click', '.btn-buy-now', function() {
            const $btn = $(this);
            const productId = $btn.data('id');
            const slug = $btn.data('slug');
            const hasOptions = $btn.data('hasOptions');

            if (hasOptions) {
                showToast('옵션 선택이 필요한 상품입니다. 상세 페이지로 이동합니다.', 'info', 'bg-[#181211]');
                setTimeout(() => {
                    location.href = `/product-detail/${slug}`;
                }, 1000);
                return;
            }

            // 이동 안내 토스트 ✨
            showToast('결제 페이지로 이동합니다.', 'bolt', 'bg-[#181211]');

            $.ajax({
                url: "{{ route('buy-now') }}",
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                data: { product_id: productId, quantity: 1 },
                success: function(response) {
                    if (response.redirect) {
                        setTimeout(() => {
                            location.href = response.redirect;
                        }, 800);
                    }
                }
            });
        });
    });
</script>
@endpush
