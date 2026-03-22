@extends('layouts.admin')

@section('page_title', '상품 관리')

@push('styles')
<style>
    /* 반응형 상품 리스트 그리드 설정  */
    .product-row { 
        display: grid; 
        align-items: center;
        grid-template-columns: 60px 1fr 80px 80px; /* 모바일: 이미지 | 이름/정보 | 상태 | 관리 */
        gap: 8px;
    }
    
    @media (min-width: 768px) {
        .product-row {
            grid-template-columns: 80px 1.5fr 100px 100px 80px 100px; /* md: + 카테고리 | 가격 | 상태 | 관리 */
            gap: 12px;
        }
    }

    @media (min-width: 1024px) {
        .product-row {
            grid-template-columns: 80px 2fr 120px 140px 70px 70px 80px 100px 110px; /* lg: HERO 컬럼 포함 9컬럼 대응 */
            gap: 12px;
        }
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <h3 class="text-lg lg:text-xl font-extrabold text-text-main">상품 관리 <span class="text-primary ml-1">{{ $products->total() }}</span></h3>
            <div id="hero-counter-badge" class="flex items-center gap-2 px-3 py-1.5 rounded-full {{ ($heroCount ?? 0) > 10 ? 'bg-red-50 text-red-600' : 'bg-primary/5 text-primary' }} transition-colors duration-300">
                <span class="material-symbols-outlined text-[18px]">rocket_launch</span>
                <span class="text-[11px] font-black tracking-tight uppercase">Hero <span id="hero-count-number">{{ $heroCount ?? 0 }}</span>/10</span>
            </div>
        </div>
        <div class="flex items-center gap-2 lg:gap-3">
            <a href="{{ route('admin.products.create') }}" class="flex-1 md:flex-none flex items-center justify-center gap-2 px-4 lg:px-6 py-2.5 lg:py-3 bg-primary text-white text-sm lg:text-base font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-red-600 transition-all">
                <span class="material-symbols-outlined text-[20px]">add</span>
                신규 상품 등록
            </a>
        </div>
    </div>

    <!-- Advanced Filter Card (Open by default but Collapsible) -->
    <div class="bg-white rounded-2xl lg:rounded-3xl shadow-sm border border-gray-100 mb-6 overflow-hidden transition-all duration-300" id="filter-card">
        <!-- Filter Header -->
        <div class="px-6 py-5 flex items-center justify-between bg-gray-50/50 border-b border-gray-100 cursor-pointer hover:bg-gray-100/50 transition-colors" id="filter-toggle">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[24px]">tune</span>
                <span class="text-base font-black text-text-main uppercase tracking-tight">상세 검색 필터</span>
                @if(request()->anyFilled(['search', 'category_id', 'status', 'min_price', 'max_price', 'min_stock', 'max_stock', 'is_new', 'is_best']))
                    <span class="ml-2 px-2 py-0.5 bg-primary/10 text-primary text-[10px] font-bold rounded-full">적용중</span>
                @endif
            </div>
            <span class="material-symbols-outlined text-text-muted transition-transform duration-300 rotate-180" id="filter-arrow">expand_more</span>
        </div>

        <!-- Filter Content (Visible by default) -->
        <div class="p-8 border-t border-gray-50" id="filter-content">
            <form action="{{ route('admin.products.index') }}" method="GET" class="space-y-6">
                
                <!-- 1st Row: 핵심 필터 (폰트 크기 업!) -->
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <!-- 상품명 -->
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-muted ml-1">상품명 검색</label>
                        <div class="relative group">
                            <span class="absolute left-4 top-3.5 text-text-muted material-symbols-outlined text-[22px] group-focus-within:text-primary transition-colors">search</span>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="상품명을 입력하세요" 
                                class="w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-base font-bold text-text-main focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all placeholder:text-gray-400">
                        </div>
                    </div>
                    
                    <!-- 카테고리 -->
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-muted ml-1">카테고리 선택</label>
                        <div class="relative">
                            <select name="category_id" class="w-full pl-5 pr-12 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-base font-bold text-text-main focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none appearance-none bg-none transition-all cursor-pointer">
                                <option value="">모든 카테고리</option>
                                @foreach($categories as $parent)
                                    <optgroup label="{{ $parent->name }}">
                                        @foreach($parent->children as $child)
                                            <option value="{{ $child->id }}" {{ request('category_id') == $child->id ? 'selected' : '' }}>{{ $child->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <span class="absolute right-4 top-4 text-text-muted material-symbols-outlined text-[22px] pointer-events-none">expand_more</span>
                        </div>
                    </div>

                    <!-- 상태 -->
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-muted ml-1">판매 상태</label>
                        <div class="relative">
                            <select name="status" class="w-full pl-5 pr-12 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-base font-bold text-text-main focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none appearance-none bg-none transition-all cursor-pointer">
                                <option value="">모든 상태</option>
                                <option value="판매중" {{ request('status') == '판매중' ? 'selected' : '' }}>판매중</option>
                                <option value="품절" {{ request('status') == '품절' ? 'selected' : '' }}>품절</option>
                                <option value="숨김" {{ request('status') == '숨김' ? 'selected' : '' }}>숨김</option>
                            </select>
                            <span class="absolute right-4 top-4 text-text-muted material-symbols-outlined text-[22px] pointer-events-none">expand_more</span>
                        </div>
                    </div>
                </div>

                <!-- 2nd Row: 범위형 데이터 & 태그 -->
                <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 pt-6 border-t border-gray-50">
                    <div class="flex flex-wrap items-center gap-8">
                        
                        <!-- 판매가 범위 -->
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-black text-text-main">판매가</span>
                            <div class="flex items-center gap-2">
                                <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="최소" class="w-28 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:bg-white focus:border-primary outline-none text-center">
                                <span class="text-gray-400 font-bold">~</span>
                                <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="최대" class="w-28 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:bg-white focus:border-primary outline-none text-center">
                            </div>
                        </div>

                        <!-- 재고 범위 -->
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-black text-text-main">재고</span>
                            <div class="flex items-center gap-2">
                                <input type="number" name="min_stock" value="{{ request('min_stock') }}" placeholder="최소" class="w-24 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:bg-white focus:border-primary outline-none text-center">
                                <span class="text-gray-400 font-bold">~</span>
                                <input type="number" name="max_stock" value="{{ request('max_stock') }}" placeholder="최대" class="w-24 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:bg-white focus:border-primary outline-none text-center">
                            </div>
                        </div>

                        <!-- 구분 태그 -->
                        <div class="flex items-center gap-4 bg-gray-50 px-4 py-2 rounded-2xl border border-gray-100">
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" name="is_new" value="1" {{ request('is_new') ? 'checked' : '' }} class="size-5 text-blue-500 rounded-lg border-gray-300 focus:ring-0">
                                <span class="text-sm font-black text-text-muted group-hover:text-blue-500 transition-colors">NEW</span>
                            </label>
                            <div class="w-px h-4 bg-gray-200"></div>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" name="is_best" value="1" {{ request('is_best') ? 'checked' : '' }} class="size-5 text-amber-500 rounded-lg border-gray-300 focus:ring-0">
                                <span class="text-sm font-black text-text-muted group-hover:text-amber-500 transition-colors">BEST</span>
                            </label>
                        </div>

                    </div>

                    <!-- 검색 액션 -->
                    <div class="flex items-center gap-3 w-full lg:w-auto">
                        <a href="{{ route('admin.products.index') }}" class="px-5 py-3 bg-white border border-gray-200 text-text-muted rounded-2xl font-bold hover:bg-gray-50 transition-all shadow-sm" title="초기화">
                            <span class="material-symbols-outlined text-[20px] block">refresh</span>
                        </a>
                        <button type="submit" class="flex-1 lg:flex-none px-10 py-3 bg-text-main text-white rounded-2xl text-sm font-black hover:bg-black transition-all shadow-lg shadow-black/10 transform active:scale-95">
                            검색하기
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Product Table Header -->
    <div class="bg-white rounded-t-2xl lg:rounded-t-3xl border-x border-t border-gray-100 overflow-hidden shadow-sm">
        <div class="product-row bg-gray-50/50 border-b border-gray-100 px-4 lg:px-6 py-3 lg:py-4">
            <div class="text-center text-sm font-black text-text-main uppercase">이미지</div>
            <div class="text-sm font-black text-text-main uppercase">상품정보</div>
            <div class="hidden md:block text-center text-sm font-black text-text-main uppercase">카테고리</div>
            <div class="hidden md:block text-right text-sm font-black text-text-main uppercase">판매가</div>
            <div class="hidden lg:block text-center text-sm font-black text-text-main uppercase">재고</div>
            <div class="hidden lg:block text-center text-sm font-black text-text-main uppercase">구분</div>
            <div class="hidden lg:block text-center text-sm font-black text-text-main uppercase">HERO</div>
            <div class="text-center text-sm font-black text-text-main uppercase">상태</div>
            <div class="text-center text-sm font-black text-text-main uppercase">관리</div>
        </div>
    </div>

    <!-- Product List -->
    <div class="bg-white rounded-b-2xl lg:rounded-b-3xl border-x border-b border-gray-100 shadow-sm divide-y divide-gray-50 overflow-hidden">
        @forelse($products as $product)
        <div class="product-row px-4 lg:px-6 py-3 lg:py-4 hover:bg-gray-50/50 transition-colors bg-white relative group">
            <!-- 1. 이미지 -->
            <a href="{{ route('admin.products.show', ['product' => $product->id, 'return_url' => request()->fullUrl()]) }}" class="flex items-center justify-center cursor-pointer">
                <div class="size-12 lg:size-14 rounded-xl overflow-hidden bg-gray-100 border border-gray-100 group-hover:border-primary transition-colors">
                    @if($product->image_url)
                        @if(Str::startsWith($product->image_url, 'http'))
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @endif
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <span class="material-symbols-outlined text-xl">image</span>
                        </div>
                    @endif
                </div>
            </a>

            <!-- 2. 상품명 -->
            <div class="min-w-0">
                <a href="{{ route('admin.products.show', ['product' => $product->id, 'return_url' => request()->fullUrl()]) }}" class="text-xs lg:text-sm font-bold text-text-main hover:text-primary transition-colors truncate block">{{ $product->name }}</a>
                <div class="flex items-center gap-2 mt-0.5">                    <p class="text-[11px] font-bold text-text-muted tracking-tight">ID: {{ $product->id }}</p>
                    <span class="lg:hidden text-[11px] font-bold text-primary tracking-tight">
                        @if($product->sale_price && $product->discount_rate > 0)
                            <span class="text-white bg-red-500 px-1 py-0.5 rounded text-[9px] mr-1">{{ $product->discount_rate }}%</span>
                        @endif
                        ₩{{ number_format($product->sale_price ?? $product->price) }}
                    </span>
                </div>
            </div>

            <!-- 3. 카테고리 (md+) -->
            <div class="hidden md:flex flex-col items-center justify-center text-center">
                @if($product->category)
                    <span class="text-[10px] font-bold text-text-muted/60 uppercase tracking-tighter">
                        {{ $product->category->parent->name ?? '독립분류' }}
                    </span>
                    <span class="text-[11px] font-bold text-text-main tracking-tight">
                        {{ $product->category->name }}
                    </span>
                @else
                    <span class="text-[11px] font-bold text-text-muted tracking-tight">미지정</span>
                @endif
            </div>

            <!-- 4. 판매가 (md+) -->
            <div class="hidden md:block text-right">
                @if($product->sale_price)
                    <div class="flex items-center justify-end gap-1.5 mb-0.5">
                        @if($product->discount_rate > 0)
                            <span class="px-1.5 py-0.5 bg-red-50 text-red-600 text-[9px] font-bold rounded tracking-tight">{{ $product->discount_rate }}%</span>
                        @endif
                        <p class="text-[11px] text-text-muted line-through font-bold tracking-tight">₩{{ number_format($product->price) }}</p>
                    </div>
                    <p class="text-[11px] lg:text-sm font-bold text-primary tracking-tight">₩{{ number_format($product->sale_price) }}</p>
                @else
                    <p class="text-[11px] lg:text-sm font-bold text-text-main tracking-tight">₩{{ number_format($product->price) }}</p>
                @endif
            </div>

            <!-- 5. 재고 (lg+) -->
            <div class="hidden lg:block text-center">
                <span class="text-[11px] lg:text-sm font-bold {{ $product->stock_quantity <= 5 ? 'text-red-500' : 'text-text-main' }} tracking-tight">
                    {{ number_format($product->stock_quantity) }}
                </span>
            </div>

            <!-- 6. 태그 (lg+) -->
            <div class="hidden lg:flex items-center justify-center gap-1">
                @if($product->is_new)
                    <span class="inline-flex items-center justify-center px-1.5 py-0.5 bg-blue-50 text-blue-500 text-[11px] font-bold rounded tracking-tight leading-none h-[18px]">NEW</span>
                @endif
                @if($product->is_best)
                    <span class="inline-flex items-center justify-center px-1.5 py-0.5 bg-amber-50 text-amber-500 text-[11px] font-bold rounded tracking-tight leading-none h-[18px]">BEST</span>
                @endif
            </div>

            <!-- 6.5. HERO (lg+) -->
            <div class="hidden lg:flex items-center justify-center">
                <button type="button" 
                    onclick="toggleHero('{{ $product->id }}', this)" 
                    class="hero-toggle-btn relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $product->is_hero ? 'bg-primary' : 'bg-gray-200' }}">
                    <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $product->is_hero ? 'translate-x-5' : 'translate-x-0' }}"></span>
                </button>
            </div>

            <!-- 7. 상태 (항시) -->
            <div class="text-center">
                @php
                    $statusColor = match($product->status) {
                        '판매중' => 'bg-green-100 text-green-600',
                        '품절' => 'bg-red-100 text-red-600',
                        '숨김' => 'bg-gray-100 text-gray-400',
                        default => 'bg-gray-50 text-gray-500',
                    };
                @endphp
                <span class="px-1.5 lg:px-2 py-0.5 {{ $statusColor }} text-[11px] font-bold rounded-full whitespace-nowrap tracking-tight">
                    {{ $product->status }}
                </span>
            </div>

            <!-- 8. 관리 (항시) -->
            <div class="flex items-center justify-center gap-1 lg:gap-2 flex-nowrap">
                <a href="{{ route('admin.products.edit', ['product' => $product->id, 'return_url' => request()->fullUrl()]) }}" title="수정" class="size-7 lg:size-8 rounded-lg bg-white border border-gray-100 text-text-muted hover:bg-primary/10 hover:text-primary transition-all flex items-center justify-center shadow-sm">
                    <span class="material-symbols-outlined text-[16px] lg:text-[18px]">edit</span>
                </a>
                <button type="button" onclick="openDeleteModal('{{ $product->id }}', '{{ $product->name }}')" title="삭제" class="size-7 lg:size-8 rounded-lg bg-white border border-gray-100 text-text-muted hover:bg-red-50 hover:text-red-600 transition-all flex items-center justify-center shadow-sm">
                    <span class="material-symbols-outlined text-[16px] lg:text-[18px]">delete</span>
                </button>
                <form id="delete-form-{{ $product->id }}" action="{{ route('admin.products.destroy', $product) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="return_url" value="{{ request()->fullUrl() }}">
                </form>
            </div>
        </div>
        @empty
        <div class="px-6 py-20 text-center">
            <div class="size-20 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-gray-200 text-[40px]">inventory_2</span>
            </div>
            <p class="text-text-muted text-[11px] font-bold tracking-tight">등록된 상품이 없습니다.</p>
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 mt-4 text-primary text-[11px] font-bold hover:underline tracking-tight">
                첫 번째 상품을 등록해보세요
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination  -->
    <div class="mt-10 mb-6">
        {{ $products->links() }}
    </div>

    <!-- Product Guide Footer -->
    <div class="bg-white rounded-2xl lg:rounded-3xl p-6 lg:p-8 border border-gray-100 shadow-sm space-y-6">
        <div class="flex items-center gap-3 border-b border-gray-50 pb-4">
            <div class="size-9 lg:size-10 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px] lg:text-[24px]">inventory</span>
            </div>
            <div>
                <h4 class="text-base lg:text-lg font-bold text-text-main">상품 관리 센터 이용 가이드</h4>
                <p class="text-[11px] font-bold text-text-muted tracking-tight">효율적인 상품 운영을 위한 팁을 확인해보세요.</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
            <div class="space-y-4">
                <div class="flex gap-3">
                    <span class="text-primary font-black text-sm lg:text-base">01.</span>
                    <div>
                        <h5 class="text-xs lg:text-sm font-extrabold text-text-main mb-1">노출 및 판매 상태</h5>
                        <p class="text-[11px] font-bold text-text-muted tracking-tight leading-relaxed">'판매중' 상태의 상품만 사용자 화면에서 구매할 수 있습니다. '품절'은 노출되지만 구매가 차단되며, '숨김'은 사용자 화면에서 완전히 제외됩니다.</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <span class="text-primary font-black text-sm lg:text-base">02.</span>
                    <div>
                        <h5 class="text-xs lg:text-sm font-extrabold text-text-main mb-1">직관적인 정보 표시</h5>
                        <p class="text-[11px] font-bold text-text-muted tracking-tight leading-relaxed">카테고리는 '대분류 &gt; 소분류' 계층으로 표시됩니다. NEW 또는 BEST 태그가 설정된 상품은 '구분' 항목에 컬러 뱃지로 시각화되어 쉽게 파악할 수 있습니다.</p>
                    </div>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex gap-3">
                    <span class="text-primary font-black text-sm lg:text-base">03.</span>
                    <div>
                        <h5 class="text-xs lg:text-sm font-extrabold text-text-main mb-1">재고 수량 경고</h5>
                        <p class="text-[11px] font-bold text-text-muted tracking-tight leading-relaxed">남은 재고 수량이 5개 이하로 떨어지면 숫자가 붉은색으로 강조 표시됩니다. 품절이 발생하기 전에 미리 재고를 확보하여 주문을 관리하세요.</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <span class="text-primary font-black text-sm lg:text-base">04.</span>
                    <div>
                        <h5 class="text-xs lg:text-sm font-extrabold text-text-main mb-1">할인 가격 및 정렬</h5>
                        <p class="text-[11px] font-bold text-text-muted tracking-tight leading-relaxed">할인이 적용된 상품은 정가에 취소선이 그어지고 할인 판매가가 돋보이게 표시됩니다. 목록은 최신 등록순으로 10개씩 페이징되어 나타납니다.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full overflow-hidden modal-animate-in">
        <div class="p-8 text-center">
            <div class="size-16 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-6">
                <span class="material-symbols-outlined text-[32px]">delete_forever</span>
            </div>
            <h4 id="modal-title" class="text-xl font-bold text-text-main mb-2">상품 삭제</h4>
            <p id="modal-desc" class="text-[11px] font-bold text-text-muted tracking-tight leading-relaxed">정말 삭제하시겠습니까?<br>삭제된 데이터와 이미지는 복구할 수 없습니다.</p>
        </div>
        <div class="flex border-t border-gray-100">
            <button onclick="closeDeleteModal()" class="flex-1 px-6 py-4 text-sm font-bold text-text-muted hover:bg-gray-50 transition-colors border-r border-gray-100">
                취소
            </button>
            <button id="confirm-delete-btn" class="flex-1 px-6 py-4 text-sm font-bold text-red-600 hover:bg-red-50 transition-colors">
                삭제하기
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentDeleteId = null;

    function openDeleteModal(id, name) {
        currentDeleteId = id;
        $('#modal-title').text(`'${name}' 삭제`);
        $('#delete-modal').removeClass('hidden').addClass('flex');
        $('body').addClass('overflow-hidden');
    }

    function closeDeleteModal() {
        $('#delete-modal').removeClass('flex').addClass('hidden');
        $('body').removeClass('overflow-hidden');
        currentDeleteId = null;
    }

    $(document).ready(function() {
        // CSRF Token Setup for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // 히어로 토글 함수 (전역 등록)
        window.toggleHero = function(productId, btn) {
            const $btn = $(btn);
            const $dot = $btn.find('span');
            
            // 시각적 상태 즉시 변경은 하지 않고 서버 응답을 기다립니다 (안정성 우선!)
            
            $.post(`/admin/products/${productId}/toggle-hero`)
                .done(function(response) {
                    if (response.success) {
                        // 실제 상태로 업데이트
                        if (response.is_hero) {
                            $btn.removeClass('bg-gray-200').addClass('bg-primary');
                            $dot.removeClass('translate-x-0').addClass('translate-x-5');
                        } else {
                            $btn.removeClass('bg-primary').addClass('bg-gray-200');
                            $dot.removeClass('translate-x-5').addClass('translate-x-0');
                        }
                        
                        // 카운터 갱신
                        $('#hero-count-number').text(response.hero_count);
                        const $badge = $('#hero-counter-badge');
                        $badge.removeClass('bg-red-50 text-red-600 animate-pulse').addClass('bg-primary/5 text-primary');
                        
                        showToast(response.message, 'rocket_launch');
                    }
                })
                .fail(function(xhr) {
                    const response = xhr.responseJSON;
                    if (xhr.status === 422 && response && response.message) {
                        showToast(response.message, 'warning', 'bg-amber-500');
                    } else {
                        showToast('상태 변경에 실패했습니다. 다시 시도해주세요.', 'error', 'bg-red-500');
                    }
                });
        };

        // 모달 외부 클릭 시 닫기
        $('#delete-modal').on('click', function(e) {
            if (e.target === this) closeDeleteModal();
        });

        // 진짜 삭제 버튼 클릭!
        $('#confirm-delete-btn').on('click', function() {
            if (currentDeleteId) {
                document.getElementById(`delete-form-${currentDeleteId}`).submit();
            }
        });

        const $filterToggle = $('#filter-toggle');
        const $filterContent = $('#filter-content');
        const $filterArrow = $('#filter-arrow');

        // 초기 상태 로드 (localStorage)
        const isFilterCollapsed = localStorage.getItem('admin_product_filter_collapsed');
        if (isFilterCollapsed === 'true') {
            $filterContent.hide();
            $filterArrow.removeClass('rotate-180');
            $filterToggle.removeClass('border-gray-50').addClass('border-transparent');
        }

        // 검색 필터 아코디언 토글 로직
        $filterToggle.on('click', function() {
            $filterContent.slideToggle(300, function() {
                // 애니메이션 완료 후 상태 저장
                localStorage.setItem('admin_product_filter_collapsed', $filterContent.is(':hidden'));
            });
            $filterArrow.toggleClass('rotate-180');
            $(this).toggleClass('border-transparent border-gray-50');
        });
    });
</script>
@endpush
