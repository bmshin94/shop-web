@extends('layouts.admin')

@section('page_title', '상품 상세 조회')

@section('content')
<div class="space-y-6 lg:space-y-8">
    <!-- Header: Action Buttons -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.index') }}" class="flex items-center justify-center size-10 rounded-xl bg-white border border-gray-200 text-text-muted hover:text-primary hover:border-primary transition-all shadow-sm">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <h3 class="text-2xl font-extrabold text-text-main">상품 상세 정보</h3>
                <p class="mt-1 text-[12px] font-bold text-text-muted">상품 번호 #{{ number_format($product->id) }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.edit', $product) }}" class="px-5 py-2.5 bg-text-main text-white rounded-xl text-sm font-bold hover:bg-black transition-all flex items-center gap-2 shadow-lg shadow-black/10">
                <span class="material-symbols-outlined text-[18px]">edit</span> 정보 수정
            </a>
            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="js-confirm-submit" data-confirm-title="상품 삭제" data-confirm-message="정말로 이 상품을 삭제하시겠습니까? 연결된 이미지 데이터도 모두 삭제됩니다." data-confirm-text="영구 삭제">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-5 py-2.5 bg-red-50 text-red-600 border border-red-100 rounded-xl text-sm font-bold hover:bg-red-100 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">delete</span> 상품 삭제
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Left: Product Images -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-3xl p-4 shadow-sm border border-gray-100 relative group">
                @if($product->image_url)
                    <div class="aspect-[3/4] rounded-2xl overflow-hidden bg-gray-50 border border-gray-50">
                        <img id="main-product-image" 
                             src="{{ Str::startsWith($product->image_url, 'http') ? $product->image_url : asset($product->image_url) }}" 
                             class="w-full h-full object-cover transition-opacity duration-300">
                    </div>
                @else
                    <div class="aspect-[3/4] rounded-2xl overflow-hidden bg-gray-100 flex flex-col items-center justify-center text-gray-300">
                        <span class="material-symbols-outlined text-5xl mb-2">image_not_supported</span>
                        <span class="text-[11px] font-bold tracking-tight">이미지 없음</span>
                    </div>
                @endif
                
                <!-- Status Badge Overlay -->
                <div class="absolute top-6 left-6 flex flex-col gap-1.5">
                    @if($product->status == '판매중')
                        <span class="px-2.5 py-1 bg-green-500/90 backdrop-blur text-white text-[11px] font-bold rounded-lg shadow-sm">판매중</span>
                    @elseif($product->status == '품절')
                        <span class="px-2.5 py-1 bg-red-500/90 backdrop-blur text-white text-[11px] font-bold rounded-lg shadow-sm">품절</span>
                    @else
                        <span class="px-2.5 py-1 bg-gray-600/90 backdrop-blur text-white text-[11px] font-bold rounded-lg shadow-sm">숨김</span>
                    @endif
                    
                    @if($product->is_new)
                        <span class="px-2.5 py-1 bg-blue-500/90 backdrop-blur text-white text-[11px] font-bold rounded-lg shadow-sm">NEW</span>
                    @endif
                    @if($product->is_best)
                        <span class="px-2.5 py-1 bg-amber-500/90 backdrop-blur text-white text-[11px] font-bold rounded-lg shadow-sm">BEST</span>
                    @endif
                </div>
            </div>
            
            <!-- Additional Images (If any) -->
            @if($product->images && $product->images->count() > 0)
            <div class="grid grid-cols-4 gap-3">
                @foreach($product->images->sortBy('sort_order') as $index => $img)
                @php $fullPath = Str::startsWith($img->image_path, 'http') ? $img->image_path : asset($img->image_path); @endphp
                <div class="thumb-item bg-white rounded-2xl p-1.5 shadow-sm border-2 {{ $img->image_path == $product->image_url ? 'border-primary' : 'border-gray-100' }} aspect-square overflow-hidden cursor-pointer hover:border-primary/50 transition-all"
                     onclick="changeMainImage('{{ $fullPath }}', this)">
                    <img src="{{ $fullPath }}" class="w-full h-full object-cover rounded-xl">
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Right: Details -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Basic Info Card -->
            <div class="bg-white rounded-3xl p-6 lg:p-8 shadow-sm border border-gray-100 relative overflow-hidden">
                <!-- Background decoration -->
                <div class="absolute -top-10 -right-10 size-40 bg-gray-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>

                <div class="relative z-10 space-y-6">
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 mb-1">
                            @if($product->category)
                                <span class="text-[10px] font-bold text-text-muted/60 uppercase tracking-tighter">{{ $product->category->parent->name ?? '독립분류' }}</span>
                                <span class="text-gray-300 text-[10px] material-symbols-outlined">chevron_right</span>
                                <span class="text-[11px] font-bold text-primary tracking-tight">{{ $product->category->name }}</span>
                            @else
                                <span class="text-[11px] font-bold text-text-muted tracking-tight">미지정 카테고리</span>
                            @endif
                        </div>
                        <h1 class="text-2xl lg:text-3xl font-black text-text-main leading-tight">{{ $product->name }}</h1>
                        @if($product->brief_description)
                            <p class="text-lg font-bold text-text-muted leading-relaxed mt-1">{{ $product->brief_description }}</p>
                        @endif
                        <p class="text-[12px] font-bold text-text-muted font-mono tracking-tight bg-gray-50 px-3 py-1.5 rounded-lg inline-block border border-gray-100 mt-2">
                            /products/{{ $product->slug }}
                        </p>
                    </div>

                    <div class="pt-6 border-t border-gray-50 grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Price Info -->
                        <div class="space-y-4">
                            <div>
                                <p class="text-[11px] font-bold text-text-muted mb-1 uppercase">정상 판매가</p>
                                <p class="text-lg font-bold text-text-main {{ $product->sale_price ? 'line-through text-gray-400' : '' }}">₩{{ number_format($product->price) }}</p>
                            </div>
                            @if($product->sale_price)
                            <div>
                                <p class="text-[11px] font-bold text-primary mb-1 uppercase flex items-center gap-1">
                                    할인 판매가
                                    @if($product->discount_rate > 0)
                                        <span class="px-1.5 py-0.5 bg-red-100 text-red-600 text-[9px] rounded font-black">{{ $product->discount_rate }}% OFF</span>
                                    @endif
                                </p>
                                <p class="text-2xl lg:text-3xl font-black text-primary">₩{{ number_format($product->sale_price) }}</p>
                            </div>
                            @endif
                        </div>

                        <!-- Stock & System Info -->
                        <div class="space-y-4 sm:border-l border-gray-50 sm:pl-6">
                            <div>
                                <p class="text-[11px] font-bold text-text-muted mb-1 uppercase">재고 현황</p>
                                <div class="flex items-center gap-2">
                                    <div class="px-3 py-1.5 bg-gray-50 rounded-lg border border-gray-100 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[16px] {{ $product->stock_quantity <= 5 ? 'text-red-500' : 'text-text-main' }}">inventory_2</span>
                                        <span class="text-sm font-extrabold {{ $product->stock_quantity <= 5 ? 'text-red-600' : 'text-text-main' }}">{{ number_format($product->stock_quantity) }}개</span>
                                    </div>
                                    @if($product->stock_quantity <= 5)
                                        <span class="text-[10px] font-bold text-red-500 tracking-tight">재고 부족 임박!</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold text-text-muted mb-1 uppercase">배송 설정</p>
                                <div class="flex items-center gap-2">
                                    <div class="px-3 py-1.5 bg-gray-50 rounded-lg border border-gray-100 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[16px] text-text-main">local_shipping</span>
                                        <span class="text-[12px] font-bold text-text-main">{{ $product->shipping_type }} ({{ $product->shipping_info }})</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold text-text-muted mb-1 uppercase">등록 일시</p>
                                <p class="text-[12px] font-bold text-text-main">{{ $product->created_at->format('Y년 m월 d일 H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Colors  -->
                    <div class="pt-6 border-t border-gray-50">
                        <p class="text-[11px] font-bold text-text-muted mb-3 uppercase">설정된 색상 옵션</p>
                        <div class="flex flex-wrap gap-2">
                            @forelse($product->colors as $color)
                                <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-50 rounded-xl border border-gray-100 shadow-sm">
                                    <div class="size-4 rounded-full ring-1 ring-gray-200" style="background-color: {{ $color->hex_code }}"></div>
                                    <span class="text-[12px] font-bold text-text-main">{{ $color->name }}</span>
                                </div>
                            @empty
                                <p class="text-[12px] font-bold text-gray-400 italic">설정된 색상이 없습니다.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Selected Sizes -->
                    <div class="pt-6 border-t border-gray-50">
                        <p class="text-[11px] font-bold text-text-muted mb-3 uppercase">설정된 사이즈 옵션</p>
                        <div class="flex flex-wrap gap-2">
                            @forelse($product->sizes as $size)
                                <div class="flex items-center gap-2 px-4 py-1.5 bg-gray-50 rounded-xl border border-gray-100 shadow-sm">
                                    <span class="text-[12px] font-black text-primary">{{ $size->name }}</span>
                                    <span class="text-[10px] font-bold text-text-muted">{{ $size->group->name ?? '기본' }}</span>
                                </div>
                            @empty
                                <p class="text-[12px] font-bold text-gray-400 italic">설정된 사이즈가 없습니다.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Related Products -->
                    <div class="pt-6 border-t border-gray-50">
                        <p class="text-[11px] font-bold text-text-muted mb-3 uppercase flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">auto_awesome_motion</span>
                            함께 스타일링하기 좋은 아이템
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @forelse($product->relatedProducts as $rel)
                                <a href="{{ route('admin.products.show', $rel) }}" class="flex items-center gap-3 p-3 bg-gray-50 hover:bg-primary-light/10 rounded-xl border border-gray-100 hover:border-primary/30 transition-all group">
                                    <img src="{{ Str::startsWith($rel->image_url, 'http') ? $rel->image_url : asset($rel->image_url) }}" class="size-12 rounded-lg object-cover bg-white shadow-sm">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-text-main truncate group-hover:text-primary transition-colors">{{ $rel->name }}</p>
                                        <p class="text-[10px] text-text-muted font-medium mt-0.5">ID: {{ $rel->id }} | ₩{{ number_format($rel->price) }}</p>
                                    </div>
                                </a>
                            @empty
                                <div class="col-span-full py-4 text-center border-2 border-dashed border-gray-100 rounded-xl">
                                    <p class="text-[12px] font-bold text-gray-400 italic">설정된 연관 상품이 없습니다.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description Card -->
            <div class="bg-white rounded-3xl p-6 lg:p-8 shadow-sm border border-gray-100">
                <h4 class="text-base font-bold text-text-main mb-6 flex items-center gap-2 pb-4 border-b border-gray-50">
                    <span class="material-symbols-outlined text-primary text-[20px]">article</span> 상세 설명
                </h4>
                <div class="prose prose-sm max-w-none text-text-main leading-relaxed">
                    {!! $product->description !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Product Detail Guide Footer -->
    <div class="mt-12 bg-white rounded-3xl p-8 border border-gray-100 shadow-sm space-y-6">
        <div class="flex items-center gap-3 border-b border-gray-50 pb-4">
            <div class="size-10 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-[24px]">visibility</span>
            </div>
            <div>
                <h4 class="text-lg font-bold text-text-main">상품 상세 정보 가이드</h4>
                <p class="text-[11px] font-bold text-text-muted tracking-tight">등록된 상품 정보를 꼼꼼하게 검토하고 관리하는 방법입니다.</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-4">
                <div class="flex gap-3">
                    <span class="text-primary font-black text-base">01.</span>
                    <div>
                        <h5 class="text-sm font-extrabold text-text-main mb-1">이미지 갤러리 확인</h5>
                        <p class="text-[11px] font-bold text-text-muted tracking-tight leading-relaxed">왼쪽의 썸네일을 클릭하면 상단에서 큰 이미지를 확인할 수 있습니다. 고객에게 보여질 이미지 순서와 퀄리티를 최종적으로 점검하세요.</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <span class="text-primary font-black text-base">02.</span>
                    <div>
                        <h5 class="text-sm font-extrabold text-text-main mb-1">실시간 가격 및 할인율</h5>
                        <p class="text-[11px] font-bold text-text-muted tracking-tight leading-relaxed">정상가와 할인 판매가가 올바르게 설정되었는지 확인하세요. 할인율은 소수점 첫째 자리에서 반올림되어 표시됩니다.</p>
                    </div>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex gap-3">
                    <span class="text-primary font-black text-base">03.</span>
                    <div>
                        <h5 class="text-sm font-extrabold text-text-main mb-1">재고 및 상태 모니터링</h5>
                        <p class="text-[11px] font-bold text-text-muted tracking-tight leading-relaxed">현재 재고 수량과 판매 상태(판매중, 품절, 숨김)를 확인하세요. 재고가 부족할 경우 시스템에서 붉은색 경고 메시지를 표시합니다.</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <span class="text-primary font-black text-base">04.</span>
                    <div>
                        <h5 class="text-sm font-extrabold text-text-main mb-1">옵션 데이터 검토</h5>
                        <p class="text-[11px] font-bold text-text-muted tracking-tight leading-relaxed">설정된 색상과 사이즈 옵션이 카테고리에 적합하게 매칭되었는지 확인하세요. 정보가 잘못되었다면 상단의 '정보 수정' 버튼을 통해 즉시 변경 가능합니다.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    /**
     * 썸네일 클릭 시 메인 이미지를 변경합니다.
     */
    function changeMainImage(src, element) {
        const mainImg = document.getElementById('main-product-image');
        if (!mainImg) return;

        // 1. 메인 이미지 변경 (페이드 효과)
        mainImg.style.opacity = '0';
        setTimeout(() => {
            mainImg.src = src;
            mainImg.style.opacity = '1';
        }, 200);

        // 2. 썸네일 선택 상태(보더) 업데이트
        document.querySelectorAll('.thumb-item').forEach(item => {
            item.classList.remove('border-primary');
            item.classList.add('border-gray-100');
        });
        element.classList.remove('border-gray-100');
        element.classList.add('border-primary');
    }
</script>
@endpush
