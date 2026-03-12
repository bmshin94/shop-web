@extends('layouts.app')

@section('title', $product->name . ' - Active Women\'s Premium Store')

@push('styles')
<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    
    @keyframes scaleIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    @keyframes toastIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes toastOut { from { opacity: 1; transform: translateY(0); } to { opacity: 0; transform: translateY(20px); } }
    
    .toast-enter { animation: toastIn 0.3s ease-out forwards; }
    .toast-exit { animation: toastOut 0.3s ease-in forwards; }
    
    .tab-btn.active { border-color: #ec3713; color: #ec3713; }
    .thumb-btn.active { border-color: #ec3713; opacity: 1; }
</style>
@endpush

@section('content')
<main class="flex-1 bg-background-light">
    <!-- Breadcrumb -->
    <div class="bg-background-light py-4 border-b border-gray-100">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <nav class="flex text-xs text-text-muted" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="/" class="hover:text-primary transition-colors">Home</a>
                    </li>
                    @if($product->category && $product->category->parent)
                    <li>
                        <div class="flex items-center">
                            <span class="material-symbols-outlined text-[14px] mx-1">chevron_right</span>
                            <a href="{{ route('product-list', ['category' => $product->category->parent->slug]) }}" class="hover:text-primary transition-colors">{{ $product->category->parent->name }}</a>
                        </div>
                    </li>
                    @endif
                    @if($product->category)
                    <li>
                        <div class="flex items-center">
                            <span class="material-symbols-outlined text-[14px] mx-1">chevron_right</span>
                            <a href="{{ route('product-list', ['category' => $product->category->slug]) }}" class="hover:text-primary transition-colors">{{ $product->category->name }}</a>
                        </div>
                    </li>
                    @endif
                    <li aria-current="page">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined text-[14px] mx-1">chevron_right</span>
                            <span class="text-text-main font-bold line-clamp-1">{{ $product->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <a href="{{ route('product-list', ['category' => $product->category->slug ?? '']) }}" class="hidden sm:flex items-center gap-1.5 text-xs font-bold text-text-muted hover:text-primary transition-colors group">
                <span class="material-symbols-outlined text-[18px] group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
                목록으로 돌아가기
            </a>
        </div>
    </div>

    <!-- Product Top Section -->
    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-12">
        <div class="lg:grid lg:grid-cols-2 lg:gap-x-12 xl:gap-x-16 lg:items-stretch">
            
            <!-- Product Gallery (Left) -->
            <!-- lg:self-start 를 추가하여 오른쪽 영역이 길어져도 이미지가 늘어나지 않게 고정합니다. -->
            <div class="flex flex-col-reverse lg:flex-row gap-4 mb-10 lg:mb-0 lg:items-stretch lg:self-start">
                <!-- Thumbnail list -->
                <div class="flex lg:flex-col gap-3 overflow-x-auto lg:overflow-y-hidden lg:w-20 xl:w-24 shrink-0 scrollbar-hide py-1">
                    @forelse($product->images->sortBy('sort_order') as $index => $img)
                    <button type="button"
                        class="thumb-btn {{ $index === 0 ? 'active border-primary' : 'border-transparent opacity-60' }} relative aspect-[3/4] w-20 lg:w-full overflow-hidden rounded-lg border-2 shrink-0 transition-all hover:opacity-100 shadow-sm">
                        <img src="{{ Str::startsWith($img->image_path, 'http') ? $img->image_path : asset($img->image_path) }}" alt="Product Image {{ $index + 1 }}" class="h-full w-full object-cover" />
                    </button>
                    @empty
                    <button type="button"
                        class="thumb-btn active relative aspect-[3/4] w-20 lg:w-full overflow-hidden rounded-lg border-2 border-primary shrink-0 transition-all shadow-sm">
                        <img src="{{ $product->image_url ?? 'https://images.unsplash.com/photo-1518310383802-640c2de311b2?q=80&w=800&auto=format&fit=crop' }}" alt="Default Image" class="h-full w-full object-cover" />
                    </button>
                    @endforelse
                </div>

                <!-- Main Image (썸네일 높이에 칼맞춤!) -->
                <div class="relative flex-1 rounded-2xl overflow-hidden bg-gray-100 group shadow-sm lg:self-stretch lg:min-h-full">
                    <div class="aspect-[3/4] lg:aspect-none lg:h-full w-full">
                        <img id="main-product-image"
                            src="{{ $product->image_url }}"
                            alt="{{ $product->name }}"
                            class="lg:absolute lg:inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-105 cursor-zoom-in" />
                    </div>

                    <!-- Floating Actions (Wishlist & Share) -->
                    <div class="absolute right-4 top-4 flex flex-col gap-3">
                        <button type="button" id="wishlist-btn"
                            class="flex size-10 items-center justify-center rounded-full bg-white/90 shadow-md hover:bg-primary hover:text-white transition-colors cursor-pointer z-10 {{ $product->is_wishlisted ? 'text-primary' : 'text-text-main' }}">
                            <span class="material-symbols-outlined block text-xl" style="font-variation-settings: 'FILL' {{ $product->is_wishlisted ? 1 : 0 }}">favorite</span>
                        </button>
                        <button type="button" id="share-btn"
                            class="flex size-10 items-center justify-center rounded-full bg-white/90 text-text-main shadow-md hover:text-primary transition-colors cursor-pointer z-10">
                            <span class="material-symbols-outlined block text-xl">share</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product Info (Right) -->
            <div class="flex flex-col mt-4 lg:mt-0 h-full">
                <!-- Top Content Area -->
                <div class="flex-1 pb-8">
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-bold text-primary uppercase tracking-wider">{{ $product->category->name ?? 'Premium Store' }}</span>
                            <div class="flex items-center gap-1 cursor-pointer group">
                                <span class="material-symbols-outlined text-yellow-500 text-sm group-hover:text-yellow-600 transition-colors">star</span>
                                <span class="text-sm font-bold text-text-main">{{ number_format($product->average_rating, 1) }}</span>
                                <a href="#reviews" id="top-review-link" class="text-sm text-text-muted hover:underline ml-1">(리뷰 {{ number_format($product->review_count) }}건)</a>
                            </div>
                        </div>
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-text-main tracking-tight mb-3 break-keep leading-tight">
                            {{ $product->name }}
                        </h1>
                        @if($product->brief_description)
                        <p class="text-base text-text-muted break-keep leading-relaxed">
                            {{ $product->brief_description }}
                        </p>
                        @endif
                    </div>

                    <div class="mb-6 pb-6 border-b border-gray-100">
                        <div class="flex items-end gap-3 mb-3">
                            @if($product->sale_price && $product->discount_rate > 0)
                            <span class="text-3xl font-extrabold text-red-500 tracking-tight">{{ $product->discount_rate }}%</span>
                            @endif
                            <div class="flex flex-col">
                                @if($product->sale_price)
                                <span class="text-sm text-text-muted line-through mb-0.5">₩{{ number_format($product->price) }}</span>
                                <span class="text-3xl font-extrabold text-text-main tracking-tight">₩{{ number_format($product->sale_price) }}</span>
                                @else
                                <span class="text-3xl font-extrabold text-text-main tracking-tight">₩{{ number_format($product->price) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @if($product->status === '품절')
                            <span class="inline-flex rounded-md bg-red-600 px-2.5 py-1 text-xs font-black text-white shadow-sm">SOLD OUT</span>
                            @endif
                            @if($product->is_new)
                            <span class="inline-flex rounded-md bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-600 border border-blue-100">NEW</span>
                            @endif
                            @if($product->is_best)
                            <span class="inline-flex rounded-md bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-600 border border-amber-100">BEST</span>
                            @endif
                            <span class="inline-flex rounded-md bg-gray-100 px-2.5 py-1 text-xs font-bold text-gray-700 border border-gray-200">{{ $product->shipping_info }}</span>
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="space-y-6">
                        <!-- Color Option -->
                        @if($product->colors->count() > 0)
                        <div>
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-sm font-bold text-text-main">
                                    색상:
                                    <span id="colorLabel" class="text-text-muted font-normal ml-1">{{ $product->colors->first()->name }}</span>
                                </h3>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                @foreach($product->colors as $index => $color)
                                <button type="button"
                                    class="color-btn relative size-12 rounded-full ring-offset-2 overflow-hidden transition-all shadow-sm {{ $index === 0 ? 'ring-2 ring-primary' : 'ring-1 ring-gray-200 hover:ring-2 hover:ring-primary' }}"
                                    title="{{ $color->name }}" data-color-name="{{ $color->name }}">
                                    <span class="absolute inset-0" style="background-color: {{ $color->hex_code }}"></span>
                                </button>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Size Option -->
                        @if($product->sizes->count() > 0)
                        <div>
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-sm font-bold text-text-main">사이즈</h3>
                                <button type="button" id="sizeGuideBtn" class="text-xs text-text-muted underline hover:text-primary transition-colors">
                                    사이즈 가이드
                                </button>
                            </div>
                            <div class="grid grid-cols-4 gap-3">
                                @foreach($product->sizes as $size)
                                <button type="button"
                                    class="size-option-btn flex h-12 items-center justify-center rounded-lg border border-gray-300 bg-white font-bold text-text-main hover:border-primary hover:text-primary transition-all shadow-sm">
                                    {{ $size->name }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Quantity -->
                        <div class="flex items-center">
                            <h3 class="w-16 text-sm font-bold text-text-main">수량</h3>
                            <div id="qtyContainer"
                                class="flex items-center rounded-lg border border-gray-300 p-1 w-32 justify-between bg-white shadow-sm">
                                <button type="button" id="qtyMinus"
                                    class="flex size-8 items-center justify-center rounded-md hover:bg-gray-100 text-gray-500 transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">remove</span>
                                </button>
                                <span id="qtyDisplay" class="text-sm font-bold text-text-main text-center w-8">1</span>
                                <button type="button" id="qtyPlus"
                                    class="flex size-8 items-center justify-center rounded-md hover:bg-gray-100 text-gray-500 transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">add</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Action Area (모바일 장바구니 버튼 상시 노출!) -->
                <div class="mt-8 pt-6 border-t border-gray-200 bg-white sticky bottom-0 z-30 lg:static lg:bg-transparent pb-4 lg:pb-0 lg:mt-auto">
                    @if($product->status === '판매중')
                    <div class="flex flex-col mb-4 lg:mb-6 px-4 lg:px-0">
                        <div class="flex justify-between items-end">
                            <span class="text-base font-bold text-text-main">총 결제 금액</span>
                            <span id="totalPrice" class="text-3xl font-extrabold text-primary tracking-tight">₩{{ number_format(($product->sale_price ?? $product->price)) }}</span>
                        </div>
                        <p id="shippingFeeInfo" class="text-right text-[11px] font-bold text-text-muted mt-1 hidden"></p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 px-4 lg:px-0">
                        <!-- hidden sm:flex 를 flex 로 변경하여 모바일에서도 장바구니 버튼 노출 -->
                        <button type="button" id="addToCartBtn"
                            class="flex h-14 w-full sm:w-auto sm:flex-1 items-center justify-center rounded-xl border-2 border-primary bg-white text-base font-bold text-primary transition-colors hover:bg-primary/5 shadow-sm">
                            장바구니 담기
                        </button>
                        <button type="button" id="buyNowBtn"
                            class="flex h-14 w-full sm:w-auto sm:flex-grow-[2] items-center justify-center rounded-xl bg-primary text-base font-extrabold text-white transition-colors hover:bg-red-600 shadow-lg shadow-primary/30">
                            바로 구매하기
                        </button>
                    </div>
                    <!-- Naver Pay Button -->
                    <button type="button"
                        class="mt-3 hidden lg:flex h-14 w-full items-center justify-center rounded-xl bg-[#03C75A] text-base font-bold text-white transition-opacity hover:opacity-90 shadow-sm">
                        <span class="mr-2 font-extrabold italic font-sans text-xl tracking-tighter text-white">N</span>
                        <span class="text-white">Pay 구매하기</span>
                    </button>
                    @else
                    <div class="px-4 lg:px-0">
                        <button type="button" disabled
                            class="flex h-14 w-full items-center justify-center rounded-xl bg-gray-200 text-base font-bold text-gray-500 cursor-not-allowed shadow-inner">
                            상품이 품절되었습니다
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Tabs Section -->
    <section class="border-t border-gray-200 mt-12 bg-white">
        <div class="sticky top-[116px] z-40 bg-white/95 backdrop-blur-md border-b border-gray-200">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <nav class="-mb-px flex gap-8 whitespace-nowrap overflow-x-auto scrollbar-hide text-sm sm:text-base font-bold">
                    <button type="button" data-tab="details" 
                        class="tab-btn border-b-2 border-primary py-4 text-primary whitespace-nowrap focus:outline-none">
                        상품 상세정보
                    </button>
                    <button type="button" data-tab="reviews" 
                        class="tab-btn border-b-2 border-transparent py-4 text-text-muted hover:border-gray-300 hover:text-text-main whitespace-nowrap transition-colors focus:outline-none">
                        고객 리뷰 <span class="ml-1 text-xs px-1.5 py-0.5 rounded-full bg-gray-100 font-normal">{{ number_format($product->review_count) }}</span>
                    </button>
                    <button type="button" data-tab="qna" 
                        class="tab-btn border-b-2 border-transparent py-4 text-text-muted hover:border-gray-300 hover:text-text-main whitespace-nowrap transition-colors focus:outline-none">
                        Q & A
                    </button>
                    <button type="button" data-tab="shipping" 
                        class="tab-btn border-b-2 border-transparent py-4 text-text-muted hover:border-gray-300 hover:text-text-main whitespace-nowrap transition-colors focus:outline-none">
                        배송/반품/교환
                    </button>
                </nav>
            </div>
        </div>

        <div class="tab-content mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-16 scroll-mt-[200px]" id="details">
            <div class="prose prose-gray max-w-none mx-auto text-text-main leading-relaxed">
                {!! $product->description !!}
            </div>
        </div>

        <div class="tab-content hidden mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-16 scroll-mt-[200px]" id="reviews">
            <div class="flex items-center justify-between mb-8 border-b border-gray-100 pb-6">
                <div>
                    <h2 class="text-2xl font-bold text-text-main">고객 리뷰 <span class="text-primary ml-1">{{ number_format($product->review_count) }}</span></h2>
                    <div class="flex items-center gap-1 mt-2">
                        @php $fullStars = floor($product->average_rating); @endphp
                        @for($i=1; $i<=5; $i++)
                        <span class="material-symbols-outlined text-sm {{ $i <= $fullStars ? 'text-yellow-500' : 'text-gray-200' }}" 
                              style="font-variation-settings: 'FILL' {{ $i <= $fullStars ? 1 : 0 }}">star</span>
                        @endfor
                        <span class="text-sm font-bold text-text-main ml-1">{{ number_format($product->average_rating, 1) }} / 5.0</span>
                    </div>
                </div>
                <a href="{{ route('review.write', ['product_id' => $product->id]) }}" class="px-6 py-3 bg-text-main text-white text-sm font-bold rounded-xl hover:bg-primary transition-colors shadow-sm">리뷰 작성하기</a>
            </div>

            <div id="review-list">
                @forelse($product->reviews->sortByDesc('created_at') as $index => $review)
                <div class="review-item py-8 border-b border-gray-100 last:border-0 {{ $index >= 5 ? 'hidden' : '' }}">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="size-10 rounded-full bg-gray-100 flex items-center justify-center text-text-muted">
                                <span class="material-symbols-outlined text-xl">person</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-text-main">{{ Str::mask($review->member->name, '*', 1) }}</p>
                                <p class="text-xs text-text-muted">{{ $review->created_at->format('Y.m.d') }}</p>
                            </div>

                        </div>
                        <div class="flex items-center gap-0.5">
                            @for($i=1; $i<=5; $i++)
                            <span class="material-symbols-outlined text-[14px] {{ $i <= $review->rating ? 'text-yellow-500' : 'text-gray-200' }}"
                                  style="font-variation-settings: 'FILL' {{ $i <= $review->rating ? 1 : 0 }}">star</span>
                            @endfor
                        </div>
                    </div>
                    <h4 class="text-base font-bold text-text-main mb-2">{{ $review->title }}</h4>
                    <p class="text-sm text-text-main leading-relaxed mb-4 break-keep">{{ $review->content }}</p>

                    @if($review->images && count($review->images) > 0)
                    <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
                        @foreach($review->images as $img)
                        <img src="{{ $img }}" alt="Review Image" class="size-20 rounded-lg object-cover border border-gray-100 cursor-zoom-in" 
                             onclick="const zm = document.getElementById('imageZoomModal'); const zi = document.getElementById('zoomImage'); zi.src = this.src; zm.style.display = 'flex'; zm.classList.remove('hidden');" />
                        @endforeach
                    </div>
                    @endif
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-20 text-center bg-gray-50 rounded-3xl border border-gray-100">
                    <div class="flex items-center justify-center w-24 h-24 rounded-full bg-white mb-6 shadow-sm text-gray-300">
                        <span class="material-symbols-outlined text-5xl">rate_review</span>
                    </div>
                    <h3 class="text-xl font-bold text-text-main mb-2">아직 작성된 리뷰가 없습니다</h3>
                    <p class="text-sm text-text-muted mb-8 max-w-sm leading-relaxed">이 상품을 구매하신 후 첫 번째 리뷰를 남겨주세요!</p>
                    <a href="{{ route('review.write', ['product_id' => $product->id]) }}" class="inline-flex items-center px-6 py-3 bg-primary text-white text-sm font-bold rounded-xl hover:bg-red-600 transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-sm align-middle mr-1">edit</span> 리뷰 작성하기
                    </a>
                </div>
                @endforelse
            </div>

            @if($product->review_count > 5)
            <div class="mt-10 text-center">
                <button type="button" id="loadMoreReviews" 
                    class="inline-flex items-center px-8 py-4 bg-white border-2 border-gray-200 text-text-main text-sm font-bold rounded-2xl hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
                    리뷰 더보기 <span class="material-symbols-outlined ml-2 text-sm transition-transform">keyboard_arrow_down</span>
                </button>
            </div>
            @endif
            </div>
        <div class="tab-content hidden mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-16 scroll-mt-[200px]" id="qna">
            <div class="flex justify-between items-end mb-8 border-b border-gray-200 pb-4">
                <h2 class="text-2xl font-bold text-text-main">Q & A</h2>
                <a href="{{ route('qna.write') }}" class="px-4 py-2 bg-text-main text-white text-sm font-bold rounded hover:bg-primary transition-colors">문의 작성하기</a>
            </div>
            <div class="flex flex-col items-center justify-center py-20 text-center bg-gray-50 rounded-3xl border border-gray-100">
                <div class="flex items-center justify-center w-24 h-24 rounded-full bg-white mb-6 shadow-sm text-gray-300">
                    <span class="material-symbols-outlined text-5xl">forum</span>
                </div>
                <h3 class="text-xl font-bold text-text-main mb-2">등록된 문의가 없습니다</h3>
                <p class="text-sm text-text-muted">궁금한 점이 있으시면 문의를 남겨주세요.</p>
            </div>
        </div>

        <div class="tab-content hidden mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-16 scroll-mt-[200px]" id="shipping">
            <h2 class="text-2xl font-bold text-text-main mb-8 border-b border-gray-200 pb-4">배송/반품/교환 안내</h2>
            <div class="space-y-8 text-sm text-text-main leading-relaxed">
                <div>
                    <h3 class="font-bold text-lg mb-3">배송 안내</h3>
                    <ul class="list-disc pl-5 space-y-1 text-text-muted">
                        <li>배송비: 기본 배송비 3,000원 (50,000원 이상 구매 시 무료배송)</li>
                        <li>출고일: 평일 오후 2시 이전 결제 완료 건에 한해 당일 출고</li>
                        <li>배송기간: 출고 후 1~3 영업일 이내 수령 가능</li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-3">교환/반품 안내</h3>
                    <ul class="list-disc pl-5 space-y-1 text-text-muted">
                        <li>단순 변심에 의한 교환/반품은 상품 수령 후 7일 이내 가능 (배송비 고객 부담)</li>
                        <li>상품 불량 및 오배송의 경우 수령 후 30일 이내 교환/반품 가능</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Recommended Related Products -->
    @if($relatedProducts->count() > 0)
    <section class="bg-background-alt py-16 border-t border-gray-200">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-10 flex items-center justify-between border-b border-gray-200 pb-4">
                <div>
                    <h3 class="text-2xl font-bold tracking-tight text-text-main sm:text-3xl">함께 스타일링하기 좋은 아이템</h3>
                    <p class="mt-2 text-sm text-text-muted">이 상품과 함께 구매된 상품들입니다.</p>
                </div>
            </div>
            <div class="grid gap-x-6 gap-y-10 grid-cols-2 lg:grid-cols-4">
                @foreach($relatedProducts as $rel)
                <div class="group relative flex flex-col cursor-pointer" onclick="location.href='{{ route('product-detail', ['slug' => $rel['slug']]) }}'">
                    <div class="relative aspect-[3/4] w-full overflow-hidden rounded-lg bg-white shadow-sm border border-gray-100">
                        <div class="h-full w-full bg-cover bg-center transition-transform duration-700 group-hover:scale-105"
                            style="background-image: url('{{ $rel['image_url'] }}');"></div>
                        <div class="absolute right-3 top-3 rounded-full bg-white/80 p-2 backdrop-blur-sm transition-colors hover:bg-primary hover:text-white cursor-pointer z-10 shadow-sm">
                            <span class="material-symbols-outlined block text-lg">favorite</span>
                        </div>
                    </div>
                    <div class="mt-4 flex flex-1 flex-col px-1">
                        <h4 class="text-base font-bold text-text-main group-hover:text-primary transition-colors">{{ $rel['name'] }}</h4>
                        <p class="text-sm font-extrabold text-text-main mb-1 mt-1">₩{{ number_format($rel['price']) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</main>

<!-- Modals & Toasts -->
<div id="imageZoomModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/80 backdrop-blur-sm cursor-zoom-out">
    <button id="zoomClose" class="absolute top-5 right-5 z-10 flex size-10 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/40 transition-colors">
        <span class="material-symbols-outlined text-2xl">close</span>
    </button>
    <img id="zoomImage" src="" alt="Zoomed" class="max-h-[90vh] max-w-[90vw] object-contain rounded-lg shadow-2xl transition-transform duration-300" />
</div>

<!-- 사이즈 가이드 모달 부활! -->
<div id="sizeGuideModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full max-h-[85vh] overflow-y-auto animate-[scaleIn_0.25s_ease-out]">
        <div class="sticky top-0 bg-white z-10 flex items-center justify-between p-6 border-b border-gray-100">
            <h3 class="text-xl font-extrabold text-text-main">사이즈 가이드</h3>
            <button id="sizeGuideClose" class="flex size-8 items-center justify-center rounded-full hover:bg-gray-100 transition-colors">
                <span class="material-symbols-outlined text-xl text-gray-500">close</span>
            </button>
        </div>
        <div class="p-6">
            @if($product->size_group && $product->size_group->size_guide)
                <p class="text-sm text-text-muted mb-4">* 단위: cm / 약간의 오차가 있을 수 있습니다.</p>
                <div class="overflow-x-auto scrollbar-hide">
                    <table class="w-full text-sm text-center border-collapse min-w-[400px]">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                @foreach($product->size_group->size_guide['headers'] as $header)
                                    <th class="py-3 px-2 font-bold text-text-main">{{ $header }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($product->size_group->size_guide['rows'] as $row)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    @foreach($row as $cell)
                                        <td class="py-3 px-2 text-text-muted font-medium">{{ $cell }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-12 text-center">
                    <span class="material-symbols-outlined text-4xl text-gray-200 mb-2">ruler</span>
                    <p class="text-sm text-text-muted italic">등록된 사이즈 가이드 정보가 없습니다.</p>
                </div>
            @endif
            
            <div class="mt-6 p-4 bg-amber-50 rounded-xl border border-amber-200">
                <p class="text-sm text-amber-800"><span class="font-bold"> Tip:</span> 평소 착용하시는 사이즈와 실측 데이터를 비교하여 선택해주세요.</p>
            </div>
        </div>
    </div>
</div>

<!-- 장바구니 성공 안내 모달 -->
<div id="cartSuccessModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="relative bg-white rounded-3xl shadow-2xl max-w-sm w-full overflow-hidden animate-[scaleIn_0.2s_ease-out]">
        <button type="button" data-modal-close class="absolute top-4 right-4 size-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400 transition-colors">
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
                <button type="button" data-modal-close class="px-6 py-4 bg-gray-100 text-text-muted text-sm font-bold rounded-2xl hover:bg-gray-200 transition-colors">쇼핑 계속하기</button>
                <a href="{{ route('cart.index') }}" class="px-6 py-4 bg-text-main text-white text-sm font-bold rounded-2xl hover:bg-black transition-all shadow-lg text-center flex items-center justify-center">장바구니 확인</a>
            </div>
        </div>
    </div>
</div>

<!-- 장바구니 중복 확인 모달 -->
<div id="cartConfirmModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="relative bg-white rounded-3xl shadow-2xl max-w-sm w-full overflow-hidden animate-[scaleIn_0.2s_ease-out]">
        <button type="button" data-modal-close class="absolute top-4 right-4 size-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400 transition-colors">
            <span class="material-symbols-outlined text-xl">close</span>
        </button>
        <div class="p-8 text-center">
            <div class="size-16 bg-primary/10 text-primary rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-3xl">shopping_cart_checkout</span>
            </div>
            <h4 class="text-xl font-bold text-text-main mb-2">장바구니 확인</h4>
            <p class="text-sm text-text-muted leading-relaxed mb-8">
                이미 장바구니에 동일한 상품이 있습니다.<br>선택하신 수량을 추가하시겠습니까?
            </p>
            <div class="grid grid-cols-2 gap-3">
                <button type="button" id="cartConfirmCancel" data-modal-close class="px-6 py-4 bg-gray-100 text-text-muted text-sm font-bold rounded-2xl hover:bg-gray-200 transition-colors">취소</button>
                <button type="button" id="cartConfirmProceed" class="px-6 py-4 bg-primary text-white text-sm font-bold rounded-2xl hover:bg-red-600 transition-all shadow-lg shadow-primary/20">수량 추가</button>
            </div>
        </div>
    </div>
</div>

<div id="toastContainer" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[9998] flex flex-col items-center gap-3 pointer-events-none"></div>

<button id="backToTop" class="fixed bottom-6 right-6 z-[100] flex size-12 items-center justify-center rounded-full bg-primary text-white shadow-lg shadow-primary/30 transition-all duration-300 opacity-0 translate-y-4 pointer-events-none hover:bg-red-600">
    <span class="material-symbols-outlined text-xl">arrow_upward</span>
</button>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const BASE_PRICE = {{ $product->sale_price ?? $product->price }};
        let quantity = 1;
        let selectedSize = "";
        let selectedColor = "{{ $product->colors->first()?->name ?? '' }}"; // 초기값 설정

        function showToast(message, icon = "check_circle", color = "bg-text-main") {
            const container = document.getElementById("toastContainer");
            if (!container) return;
            const toast = document.createElement("div");
            toast.className = `flex items-center gap-3 ${color} text-white px-6 py-3.5 rounded-xl shadow-2xl text-sm font-bold pointer-events-auto toast-enter`;
            toast.innerHTML = `<span class="material-symbols-outlined text-lg">${icon}</span><span>${message}</span>`;
            container.appendChild(toast);
            setTimeout(() => {
                toast.classList.remove("toast-enter");
                toast.classList.add("toast-exit");
                toast.addEventListener("animationend", () => toast.remove());
            }, 2500);
        }

        // Modal Functions (전역에서 접근 가능하도록 상단 배치)
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
        window.openModal = openModal;
        window.closeModal = closeModal;

        // 모달 닫기 버튼들 이벤트 연결
        document.querySelectorAll('[data-modal-close]').forEach(btn => {
            btn.addEventListener('click', function() {
                const modal = this.closest('.fixed');
                closeModal(modal);
            });
        });

        // 모달 외부 클릭 시 닫기 통합 관리
        [document.getElementById('cartSuccessModal'), document.getElementById('cartConfirmModal'), document.getElementById('sizeGuideModal')].forEach(modal => {
            if (modal) {
                modal.addEventListener("click", (e) => {
                    if (e.target === modal) closeModal(modal);
                });
            }
        });

        // Tab Switching
        const tabBtns = document.querySelectorAll(".tab-btn");
        const tabContents = document.querySelectorAll(".tab-content");
        function activateTab(targetId) {
            tabBtns.forEach(b => {
                b.classList.remove("border-primary", "text-primary");
                b.classList.add("border-transparent", "text-text-muted");
            });
            tabContents.forEach(c => c.classList.add("hidden"));

            const activeBtn = document.querySelector(`.tab-btn[data-tab="${targetId}"]`);
            const activeContent = document.getElementById(targetId);
            
            if (activeBtn) {
                activeBtn.classList.add("border-primary", "text-primary");
                activeBtn.classList.remove("border-transparent", "text-text-muted");
            }
            if (activeContent) activeContent.classList.remove("hidden");
        }

        tabBtns.forEach(btn => {
            btn.addEventListener("click", () => {
                const targetId = btn.getAttribute("data-tab");
                activateTab(targetId);
                history.replaceState(null, "", "#" + targetId);
                // 스크롤은 수동 클릭 시에만 부드럽게 이동
                document.getElementById(targetId)?.scrollIntoView({ behavior: "smooth" });
            });
        });

        // 초기 해시 체크
        if (window.location.hash) {
            const hash = window.location.hash.substring(1);
            if (['details', 'reviews', 'qna', 'shipping'].includes(hash)) {
                activateTab(hash);
            }
        }

        // Top Review Link Click -> Activate Review Tab
        const topReviewLink = document.getElementById("top-review-link");
        if (topReviewLink) {
            topReviewLink.addEventListener("click", (e) => {
                e.preventDefault();
                activateTab("reviews");
                document.getElementById("reviews").scrollIntoView({ behavior: "smooth" });
            });
        }

        // Load More Reviews
        const loadMoreBtn = document.getElementById("loadMoreReviews");
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener("click", () => {
                const hiddenReviews = document.querySelectorAll(".review-item.hidden");
                for (let i = 0; i < 5 && i < hiddenReviews.length; i++) {
                    hiddenReviews[i].classList.remove("hidden");
                }
                if (document.querySelectorAll(".review-item.hidden").length === 0) {
                    loadMoreBtn.parentElement.classList.add("hidden");
                }
            });
        }

        // Thumbnail Gallery
        const thumbBtns = document.querySelectorAll(".thumb-btn");
        const mainImg = document.getElementById("main-product-image");
        thumbBtns.forEach(btn => {
            btn.addEventListener("click", () => {
                thumbBtns.forEach(b => b.classList.remove("active", "border-primary", "opacity-100"));
                btn.classList.add("active", "border-primary", "opacity-100");
                const src = btn.querySelector("img").src;
                if (mainImg) {
                    mainImg.style.opacity = "0.5";
                    setTimeout(() => { mainImg.src = src; mainImg.style.opacity = "1"; }, 150);
                }
            });
        });

        // Image Zoom
        const zoomModal = document.getElementById("imageZoomModal");
        const zoomImage = document.getElementById("zoomImage");
        if (mainImg && zoomModal && zoomImage) {
            mainImg.addEventListener("click", () => { zoomImage.src = mainImg.src; openModal(zoomModal); });
            document.getElementById("zoomClose").addEventListener("click", () => closeModal(zoomModal));
        }

        // Color Selection
        const colorBtns = document.querySelectorAll(".color-btn");
        colorBtns.forEach(btn => {
            btn.addEventListener("click", () => {
                colorBtns.forEach(b => b.classList.remove("ring-2", "ring-primary"));
                btn.classList.add("ring-2", "ring-primary");
                selectedColor = btn.getAttribute("data-color-name");
                const label = document.getElementById("colorLabel");
                if (label) label.textContent = selectedColor;
            });
        });

        // Size Selection
        const sizeBtns = document.querySelectorAll(".size-option-btn");
        sizeBtns.forEach(btn => {
            btn.addEventListener("click", () => {
                sizeBtns.forEach(b => {
                    b.classList.remove("border-2", "border-primary", "bg-primary/5", "text-primary", "shadow-sm");
                    b.classList.add("border", "border-gray-300", "bg-white", "text-text-main");
                });
                btn.classList.remove("border", "border-gray-300", "bg-white", "text-text-main");
                btn.classList.add("border-2", "border-primary", "bg-primary/5", "text-primary", "shadow-sm");
                selectedSize = btn.textContent.trim();
            });
        });

        // 8. Size Guide
        const sizeGuideModal = document.getElementById("sizeGuideModal");
        const sgBtn = document.getElementById("sizeGuideBtn");
        const sgClose = document.getElementById("sizeGuideClose");
        if (sizeGuideModal && sgBtn) {
            sgBtn.addEventListener("click", () => openModal(sizeGuideModal));
        }
        if (sizeGuideModal && sgClose) {
            sgClose.addEventListener("click", () => closeModal(sizeGuideModal));
        }

        // 9. Quantity & Total
        function updateQuantity(newQty) {
            quantity = Math.max(1, Math.min(99, newQty));
            const totalItemPrice = BASE_PRICE * quantity;
            let shippingFee = 0;

            const shippingType = "{{ $product->shipping_type }}";
            const fixedFee = {{ $product->shipping_fee ?? 0 }};

            if (shippingType === '무료') {
                shippingFee = 0;
            } else if (shippingType === '고정') {
                shippingFee = fixedFee;
            } else {
                // 기본 (5만원 이상 무료, 미만 시 3,000원)
                shippingFee = totalItemPrice >= 50000 ? 0 : 3000;
            }

            const qDisp = document.getElementById("qtyDisplay");
            const tPrice = document.getElementById("totalPrice");
            const sInfo = document.getElementById("shippingFeeInfo");

            if (qDisp) qDisp.textContent = quantity;
            if (tPrice) tPrice.textContent = "₩" + (totalItemPrice + shippingFee).toLocaleString();
            
            if (sInfo) {
                if (shippingFee > 0) {
                    sInfo.textContent = `(배송비 ₩${shippingFee.toLocaleString()} 포함)`;
                    sInfo.classList.remove('hidden');
                } else {
                    sInfo.textContent = "(무료배송 적용됨)";
                    sInfo.classList.remove('hidden');
                }
            }
        }
        
        // 초기 실행 (배송비 포함 금액 표시)
        updateQuantity(1);
        const qPlus = document.getElementById("qtyPlus");
        const qMinus = document.getElementById("qtyMinus");
        if (qPlus) qPlus.addEventListener("click", () => updateQuantity(quantity + 1));
        if (qMinus) qMinus.addEventListener("click", () => updateQuantity(quantity - 1));

        // Wishlist Toggle
        const wBtn = document.getElementById("wishlist-btn");
        if (wBtn) {
            wBtn.addEventListener("click", function() {
                @guest
                    showToast("로그인이 필요한 서비스입니다", "login", "bg-red-500");
                    setTimeout(() => location.href = "{{ route('login') }}", 1500);
                    return;
                @endguest

                const icon = this.querySelector(".material-symbols-outlined");
                
                fetch("{{ route('wishlist.toggle', $product) }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'added') {
                        this.classList.add("text-primary");
                        this.classList.remove("text-text-main");
                        icon.style.fontVariationSettings = "'FILL' 1";
                    } else {
                        this.classList.remove("text-primary");
                        this.classList.add("text-text-main");
                        icon.style.fontVariationSettings = "'FILL' 0";
                    }
                    showToast(data.message, data.status === 'added' ? "favorite" : "heart_broken", data.status === 'added' ? "bg-primary" : "bg-gray-600");
                    
                    // 찜 카운트 업데이트 (wishlistCount 사용)
                    const wishlistBadges = document.querySelectorAll(".header-wishlist-count");
                    if (data.wishlistCount !== undefined) {
                        wishlistBadges.forEach(badge => {
                            badge.textContent = data.wishlistCount;
                            if (data.wishlistCount > 0) {
                                badge.classList.remove("hidden");
                                badge.classList.add("flex");
                            } else {
                                badge.classList.remove("flex");
                                badge.classList.add("hidden");
                            }
                        });
                    }
                });
            });
        }

        // Share
        const sBtn = document.getElementById("share-btn");
        if (sBtn) {
            sBtn.addEventListener("click", () => {
                navigator.clipboard.writeText(location.href).then(() => {
                    showToast("링크가 클립보드에 복사되었습니다", "content_copy", "bg-green-600");
                });
            });
        }

        // Add to Cart
        const cBtn = document.getElementById("addToCartBtn");
        if (cBtn) {
            cBtn.addEventListener("click", () => {
                @guest
                    showToast("로그인이 필요한 서비스입니다", "login", "bg-red-500");
                    setTimeout(() => location.href = "{{ route('login') }}", 1500);
                    return;
                @endguest

                @if($product->colors->count() > 0)
                if (!selectedColor) {
                    showToast("색상을 선택해주세요", "error", "bg-red-500");
                    return;
                }
                @endif

                @if($product->sizes->count() > 0)
                if (!selectedSize) {
                    showToast("사이즈를 선택해주세요", "error", "bg-red-500");
                    return;
                }
                @endif

                const addToCart = (force = false) => {
                    fetch("{{ route('cart.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            product_id: {{ $product->id }},
                            color: selectedColor,
                            size: selectedSize,
                            quantity: quantity,
                            force: force
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'duplicate') {
                            const cartModal = document.getElementById("cartConfirmModal");
                            openModal(cartModal);
                            
                            // 모달 버튼 이벤트 바인딩 (일회성)
                            const proceedBtn = document.getElementById("cartConfirmProceed");
                            const cancelBtn = document.getElementById("cartConfirmCancel");
                            
                            const onProceed = () => {
                                closeModal(cartModal);
                                addToCart(true);
                                cleanup();
                            };
                            const onCancel = () => {
                                closeModal(cartModal);
                                cleanup();
                            };
                            const cleanup = () => {
                                proceedBtn.removeEventListener("click", onProceed);
                                cancelBtn.removeEventListener("click", onCancel);
                            };
                            
                            proceedBtn.addEventListener("click", onProceed);
                            cancelBtn.addEventListener("click", onCancel);

                        } else if (data.status === 'success') {
                            // 토스트 대신 성공 모달 띄우기
                            openModal(document.getElementById("cartSuccessModal"));
                            
                            // 장바구니 아이콘 카운트 업데이트 (Header)
                            const cartBadges = document.querySelectorAll(".header-cart-count");
                            cartBadges.forEach(badge => {
                                badge.textContent = data.cart_count;
                                badge.classList.remove("hidden");
                            });
                        } else {
                            showToast("처리에 실패했습니다", "error", "bg-red-500");
                        }
                    });
                };

                addToCart();
            });
        }

        // Buy Now
        const buyNowBtn = document.getElementById("buyNowBtn");
        if (buyNowBtn) {
            buyNowBtn.addEventListener("click", () => {
                @guest
                    showToast("로그인이 필요한 서비스입니다", "login", "bg-red-500");
                    setTimeout(() => location.href = "{{ route('login') }}", 1500);
                    return;
                @endguest

                @if($product->colors->count() > 0)
                if (!selectedColor) {
                    showToast("색상을 선택해주세요", "error", "bg-red-500");
                    return;
                }
                @endif

                @if($product->sizes->count() > 0)
                if (!selectedSize) {
                    showToast("사이즈를 선택해주세요", "error", "bg-red-500");
                    return;
                }
                @endif

                fetch("{{ route('buy-now') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        product_id: {{ $product->id }},
                        color: selectedColor,
                        size: selectedSize,
                        quantity: quantity
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.redirect) {
                        location.href = data.redirect;
                    } else {
                        showToast("처리에 실패했습니다", "error", "bg-red-500");
                    }
                })
                .catch(err => {
                    showToast("오류가 발생했습니다", "error", "bg-red-500");
                });
            });
        }

        // Other Utilities
        const btt = document.getElementById("backToTop");
        if (btt) {
            window.addEventListener("scroll", () => {
                if (window.scrollY > 600) btt.classList.replace("opacity-0", "opacity-100"), btt.classList.remove("pointer-events-none");
                else btt.classList.replace("opacity-100", "opacity-0"), btt.classList.add("pointer-events-none");
            });
            btt.addEventListener("click", () => window.scrollTo({ top: 0, behavior: "smooth" }));
        }
    });
</script>
@endpush
