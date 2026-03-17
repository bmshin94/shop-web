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
                            class="flex size-10 items-center justify-center rounded-full bg-white/90 shadow-md hover:bg-primary hover:text-white transition-all active:scale-95 cursor-pointer z-10 {{ $product->is_wishlisted ? 'text-primary' : 'text-text-main' }}">
                            <span class="material-symbols-outlined block text-xl" style="font-variation-settings: 'FILL' {{ $product->is_wishlisted ? 1 : 0 }}">favorite</span>
                        </button>
                        <button type="button" id="share-btn"
                            class="flex size-10 items-center justify-center rounded-full bg-white/90 text-text-main shadow-md hover:text-primary transition-all active:scale-95 cursor-pointer z-10">
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
                        <p class="text-base text-text-muted break-keep leading-relaxed whitespace-pre-wrap">
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
                                    class="size-option-btn flex h-12 items-center justify-center rounded-lg border border-gray-300 bg-white font-bold text-text-main hover:border-primary hover:text-primary transition-all active:scale-95 shadow-sm">
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
                                    class="flex size-8 items-center justify-center rounded-md hover:bg-gray-100 text-gray-500 transition-all active:scale-95">
                                    <span class="material-symbols-outlined text-[18px]">remove</span>
                                </button>
                                <span id="qtyDisplay" class="text-sm font-bold text-text-main text-center w-8">1</span>
                                <button type="button" id="qtyPlus"
                                    class="flex size-8 items-center justify-center rounded-md hover:bg-gray-100 text-gray-500 transition-all active:scale-95">
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
                            class="flex h-14 w-full sm:w-auto sm:flex-1 items-center justify-center rounded-xl border-2 border-primary bg-white text-base font-bold text-primary transition-all hover:bg-primary/5 active:scale-[0.98] shadow-sm">
                            장바구니 담기
                        </button>
                        <button type="button" id="buyNowBtn"
                            class="flex h-14 w-full sm:w-auto sm:flex-grow-[2] items-center justify-center rounded-xl bg-primary text-base font-extrabold text-white transition-all hover:bg-red-600 active:scale-[0.98] shadow-lg shadow-primary/30">
                            바로 구매하기
                        </button>
                    </div>
                    <!-- Naver Pay Button -->
                    <button type="button"
                        class="mt-3 hidden lg:flex h-14 w-full items-center justify-center rounded-xl bg-[#03C75A] text-base font-bold text-white transition-all hover:opacity-90 active:scale-[0.98] shadow-sm">
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
                        Q & A <span class="ml-1 text-xs px-1.5 py-0.5 rounded-full bg-gray-100 font-normal">{{ number_format($product->inquiries->count()) }}</span>
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
                
                @php
                    $canReview = false;
                    if(auth()->check()) {
                        $canReview = \App\Models\OrderItem::where('product_id', $product->id)
                            ->whereHas('order', function ($query) {
                                $query->where('member_id', auth()->id())
                                    ->where('order_status', '구매확정');
                            })->exists();
                    }
                @endphp

                @if($canReview)
                <a href="{{ route('review.write', ['product_id' => $product->id]) }}" class="px-6 py-3 bg-text-main text-white text-sm font-bold rounded-xl hover:bg-primary transition-colors shadow-sm">리뷰 작성하기</a>
                @else
                <button type="button" onclick="showToast('구매확정 완료된 상품만 리뷰를 작성할 수 있어요! ', 'info', 'bg-gray-600')" class="px-6 py-3 bg-gray-100 text-text-muted text-sm font-bold rounded-xl hover:bg-gray-200 transition-colors shadow-sm">리뷰 작성안내</button>
                @endif
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
                    <p class="text-sm text-text-main leading-relaxed mb-4 break-keep whitespace-pre-wrap">{{ $review->content }}</p>

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
                <a href="{{ route('qna.write', ['product_id' => $product->id]) }}" class="px-6 py-3 bg-text-main text-white text-sm font-bold rounded-xl hover:bg-primary transition-colors shadow-sm">문의 작성하기</a>
            </div>
            
            <div class="space-y-4">
                @forelse($product->inquiries->sortByDesc('created_at') as $inquiry)
                <div class="border border-gray-100 rounded-2xl p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-bold {{ $inquiry->status === '답변완료' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $inquiry->status }}
                            </span>
                            <div class="flex items-center gap-1">
                                @if($inquiry->is_private)
                                <span class="material-symbols-outlined text-[14px] text-text-muted">lock</span>
                                @endif
                                <h4 class="text-base font-bold text-text-main">{{ $inquiry->title }}</h4>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-text-main">{{ Str::mask($inquiry->member->name, '*', 1) }}</p>
                            <p class="text-[10px] text-text-muted mt-0.5">{{ $inquiry->created_at->format('Y.m.d') }}</p>
                            
                            @if(auth()->check() && $inquiry->member_id === auth()->id() && !$inquiry->answer)
                            <div class="flex gap-2 mt-2 justify-end">
                                <a href="{{ route('qna.edit', $inquiry->id) }}" class="text-[10px] font-bold text-text-muted hover:text-primary transition-colors">수정</a>
                                <button type="button" onclick="deleteInquiry({{ $inquiry->id }})" class="text-[10px] font-bold text-text-muted hover:text-red-500 transition-colors">삭제</button>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    @php
                        $isOwner = auth()->check() && $inquiry->member_id === auth()->id();
                        $canView = !$inquiry->is_private || $isOwner;
                    @endphp

                    @if($canView)
                    <div class="text-sm text-text-muted leading-relaxed whitespace-pre-wrap">{{ $inquiry->content }}</div>
                    
                    @if($inquiry->images && count($inquiry->images) > 0)
                    <div class="flex gap-2 mt-4 overflow-x-auto pb-2 scrollbar-hide">
                        @foreach($inquiry->images as $img)
                        <img src="{{ $img }}" alt="Inquiry Image" class="size-20 rounded-lg object-cover border border-gray-100 cursor-zoom-in" 
                             onclick="const zm = document.getElementById('imageZoomModal'); const zi = document.getElementById('zoomImage'); zi.src = this.src; zm.style.display = 'flex'; zm.classList.remove('hidden');" />
                        @endforeach
                    </div>
                    @endif
                    @else
                    <div class="flex items-center gap-2 text-sm text-text-muted italic bg-gray-50 p-4 rounded-xl border border-dashed border-gray-200">
                        <span class="material-symbols-outlined text-base">lock</span>
                        비밀글입니다. 작성자만 확인하실 수 있어요! 
                    </div>
                    @endif
                    
                    @if($inquiry->answer && $canView)
                    <div class="mt-4 p-4 bg-gray-50 rounded-xl border-l-4 border-primary/30">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="material-symbols-outlined text-primary text-sm">subdirectory_arrow_right</span>
                            <span class="text-xs font-bold text-primary uppercase">Seller Answer</span>
                        </div>
                        <p class="text-sm text-text-main leading-relaxed whitespace-pre-wrap">{{ $inquiry->answer }}</p>
                    </div>
                    @endif
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-20 text-center bg-gray-50 rounded-3xl border border-gray-100">
                    <div class="flex items-center justify-center w-24 h-24 rounded-full bg-white mb-6 shadow-sm text-gray-300">
                        <span class="material-symbols-outlined text-5xl">forum</span>
                    </div>
                    <h3 class="text-xl font-bold text-text-main mb-2">등록된 문의가 없습니다</h3>
                    <p class="text-sm text-text-muted">이 상품에 대해 궁금한 점이 있으시면 문의를 남겨주세요.</p>
                </div>
                @endforelse
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
    window.ProductConfig = {
        basePrice: {{ $product->sale_price ?? $product->price }},
        productId: {{ $product->id }},
        initialColor: "{{ $product->colors->first()?->name ?? '' }}",
        shippingType: "{{ $product->shipping_type }}",
        shippingFee: {{ $product->shipping_fee ?? 0 }},
        isGuest: @guest true @else false @endguest,
        hasColors: @if($product->colors->count() > 0) true @else false @endif,
        hasSizes: @if($product->sizes->count() > 0) true @else false @endif,
        csrfToken: '{{ csrf_token() }}',
        routes: {
            login: "{{ route('login') }}",
            wishlistToggle: "{{ route('wishlist.toggle', $product) }}",
            cartStore: "{{ route('cart.store') }}",
            buyNow: "{{ route('buy-now') }}"
        }
    };
</script>
<script src="{{ asset('js/product-detail.js') }}"></script>
@endpush
