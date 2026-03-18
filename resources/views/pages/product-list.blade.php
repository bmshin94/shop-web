@extends('layouts.app')

@section('content')
{{-- Breadcrumb & Page Title Section --}}
<div class="bg-gray-50 border-b border-gray-100 py-10 lg:py-14">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <nav class="flex text-xs font-medium text-text-muted mb-6" aria-label="Breadcrumb">
      <ol class="inline-flex items-center space-x-2">
        @foreach($breadcrumb as $index => $item)
        <li class="flex items-center">
          @if($index > 0)
          <span class="material-symbols-outlined text-[14px] mx-2 text-gray-300">chevron_right</span>
          @endif
          @if($loop->last)
          <span class="text-text-main font-bold">{{ $item['name'] }}</span>
          @else
          <a href="{{ $item['url'] }}" class="hover:text-primary transition-colors">{{ $item['name'] }}</a>
          @endif
        </li>
        @endforeach
      </ol>
    </nav>
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
      <div>
        <h2 class="text-3xl lg:text-4xl font-black text-text-main tracking-tight mb-2">
          {{ $pageTitle }}
          <span class="text-lg font-medium text-text-muted ml-2">({{ number_format($products->total()) }})</span>
        </h2>
      </div>
      {{-- Mobile Filter Toggle --}}
      <button id="btn-mobile-filter"
        class="lg:hidden flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-black text-text-main hover:border-primary hover:text-primary transition-all shadow-sm">
        <span class="material-symbols-outlined text-xl">tune</span> 상세 필터
      </button>
    </div>
  </div>
</div>

<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
  <div class="flex flex-col lg:flex-row gap-12">
    {{-- Sidebar Filter --}}
    <aside class="hidden lg:block w-64 shrink-0">
      <div class="sticky top-32 space-y-12">
        {{-- Category Filter --}}
        <div>
          <h3 class="flex items-center gap-2 text-sm font-black text-text-main uppercase tracking-widest mb-6">
            <span class="w-5 h-[2px] bg-primary"></span>
            Categories
          </h3>
          <ul id="category-filter" class="space-y-1.5">
            <li>
              <a href="#" data-filter-type="category" data-filter-value=""
                class="filter-category group flex items-center justify-between px-4 py-2.5 rounded-xl text-sm font-bold transition-all {{ !request('category') ? 'bg-text-main text-white shadow-lg' : 'text-text-muted hover:bg-gray-100 hover:text-primary' }}">
                <span>전체보기</span>
                <span class="material-symbols-outlined text-[18px] opacity-0 group-hover:opacity-100 transition-opacity">chevron_right</span>
              </a>
            </li>
            @foreach($globalCategories ?? [] as $parentCat)
            <li class="pt-4 pb-2">
              <p class="px-4 text-[11px] font-black uppercase tracking-widest mb-3 flex items-center gap-2 {{ request('category') == $parentCat->slug ? 'text-primary' : 'text-gray-400' }}">
                {{ $parentCat->name }}
                @if(request('category') == $parentCat->slug)
                <span class="size-1 bg-primary rounded-full"></span>
                @endif
              </p>
              <div class="space-y-1">
                @foreach($parentCat->children as $childCat)
                <a href="#" data-filter-type="category" data-filter-value="{{ $childCat->slug }}"
                  class="filter-category group flex items-center justify-between px-4 py-2.5 rounded-xl text-sm font-bold transition-all {{ request('category') == $childCat->slug ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-text-muted hover:bg-gray-100 hover:text-primary' }}">
                  <span>{{ $childCat->name }}</span>
                  <span class="material-symbols-outlined text-[18px] {{ request('category') == $childCat->slug ? 'opacity-100' : 'opacity-0 group-hover:opacity-100' }} transition-opacity">chevron_right</span>
                </a>
                @endforeach
              </div>
            </li>
            @endforeach
          </ul>
        </div>

        {{-- Color Filter --}}
        <div>
          <h3 class="flex items-center gap-2 text-sm font-black text-text-main uppercase tracking-widest mb-6">
            <span class="w-5 h-[2px] bg-primary"></span>
            Colors
          </h3>
          <div id="color-filter" class="grid grid-cols-5 gap-3 px-1">
            @php
              $dbColors = \App\Models\Color::orderBy('name')->get();
              $selectedColors = request('colors', []);
            @endphp
            @foreach($dbColors as $color)
            @php $isSelected = in_array($color->name, $selectedColors); @endphp
            <button data-filter-type="color" data-filter-value="{{ $color->name }}"
              title="{{ $color->name }}"
              class="filter-color relative size-8 rounded-full transition-all hover:scale-110 shadow-sm border border-gray-100 ring-offset-2 {{ $isSelected ? 'ring-2 ring-primary scale-110' : 'hover:ring-2 hover:ring-gray-200' }}" 
              style="background-color: {{ $color->hex_code }}"
              data-selected="{{ $isSelected ? 'true' : 'false' }}">
              @if($isSelected)
              <span class="material-symbols-outlined absolute inset-0 flex items-center justify-center text-white text-[14px] font-black drop-shadow-sm">check</span>
              @endif
            </button>
            @endforeach
          </div>
        </div>

        {{-- Price Filter --}}
        <div>
          <h3 class="flex items-center gap-2 text-sm font-black text-text-main uppercase tracking-widest mb-6">
            <span class="w-5 h-[2px] bg-primary"></span>
            Price Range
          </h3>
          <div id="price-filter" class="space-y-2 px-1">
            @php $selectedPrices = request('price_range', []); @endphp
            @foreach(['~ 5만원', '5만원 ~ 10만원', '10만원 이상'] as $priceRange)
            <label class="flex items-center justify-between p-3 rounded-xl border border-gray-100 bg-white cursor-pointer group transition-all hover:border-primary/30 hover:bg-gray-50">
              <div class="flex items-center gap-3">
                <input type="checkbox" data-filter-type="price" data-filter-value="{{ $priceRange }}"
                  {{ in_array($priceRange, $selectedPrices) ? 'checked' : '' }}
                  class="filter-price size-5 rounded-md border-gray-300 text-primary focus:ring-primary/20 transition-all cursor-pointer" />
                <span class="text-sm font-bold text-text-muted group-hover:text-text-main transition-colors">{{ $priceRange }}</span>
              </div>
              <span class="size-1.5 rounded-full bg-gray-200 group-hover:bg-primary transition-colors"></span>
            </label>
            @endforeach
          </div>
        </div>
      </div>
    </aside>

    {{-- Product Grid Area --}}
    <div class="flex-1">
      {{-- Top Toolbar --}}
      <div class="mb-8 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div id="active-filters" class="flex flex-wrap gap-2">
          {{-- Filter tags will be injected here --}}
        </div>
        <div class="relative w-full sm:w-auto">
          <select id="sort-filter"
            class="w-full sm:w-auto appearance-none rounded-xl border border-gray-200 bg-white px-5 py-3 pr-12 text-sm font-black text-text-main focus:border-primary focus:ring-4 focus:ring-primary/5 outline-none cursor-pointer !bg-none shadow-sm transition-all hover:border-gray-300">
            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>신상품순</option>
            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>인기순</option>
            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>낮은 가격순</option>
            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>높은 가격순</option>
          </select>
          <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">expand_more</span>
        </div>
      </div>

      {{-- Grid --}}
      <div class="grid gap-x-6 gap-y-12 grid-cols-2 md:grid-cols-3 xl:grid-cols-4">
        @forelse($products as $product)
          <x-product-card :product="$product" />
        @empty
        <div class="col-span-full py-32 text-center bg-gray-50 rounded-3xl border border-dashed border-gray-200">
            <span class="material-symbols-outlined text-5xl text-gray-300 mb-4">search_off</span>
            <p class="text-text-muted font-black text-lg">조건에 맞는 상품을 찾지 못했어요.</p>
            <p class="text-sm text-text-muted mt-2">필터를 초기화하거나 다른 검색어를 입력해 보세요!</p>
        </div>
        @endforelse
      </div>

      {{-- Pagination --}}
      <div class="mt-20">
        {{ $products->links() }}
      </div>
    </div>
  </div>
</section>

{{-- Mobile Filter Drawer  --}}
<div id="mobile-filter-drawer" class="fixed inset-0 z-[100] hidden">
    <div id="filter-drawer-overlay" class="absolute inset-0 bg-black/40 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>
    <div id="filter-drawer-content" class="absolute right-0 top-0 h-full w-[85%] max-w-sm translate-x-full bg-white transition-transform duration-300 ease-in-out shadow-2xl flex flex-col">
        {{-- Header --}}
        <div class="p-6 flex items-center justify-between border-b border-gray-100">
            <h3 class="text-lg font-black text-text-main">상세 필터</h3>
            <button id="filter-drawer-close" class="flex size-10 items-center justify-center rounded-xl text-text-muted hover:bg-gray-100 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        {{-- Content --}}
        <div class="flex-1 overflow-y-auto p-6 space-y-10">
            {{-- Category --}}
            <div>
                <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Categories</h4>
                <div class="grid grid-cols-2 gap-2">
                    <a href="#" data-filter-type="category" data-filter-value=""
                       class="filter-category rounded-xl px-3 py-2.5 text-[13px] font-bold text-center border {{ !request('category') ? 'bg-primary text-white border-primary' : 'bg-gray-50 text-text-muted border-transparent' }}">전체</a>
                    @foreach($globalCategories ?? [] as $parentCat)
                        @foreach($parentCat->children as $childCat)
                        <a href="#" data-filter-type="category" data-filter-value="{{ $childCat->slug }}"
                           class="filter-category rounded-xl px-3 py-2.5 text-[13px] font-bold text-center border {{ request('category') == $childCat->slug ? 'bg-primary text-white border-primary' : 'bg-gray-50 text-text-muted border-transparent' }}">
                           {{ $childCat->name }}
                        </a>
                        @endforeach
                    @endforeach
                </div>
            </div>

            {{-- Colors --}}
            <div>
                <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Colors</h4>
                <div class="grid grid-cols-5 gap-3">
                    @php
                        $dbColors = \App\Models\Color::orderBy('name')->get();
                        $selectedColors = request('colors', []);
                    @endphp
                    @foreach($dbColors as $color)
                    @php $isSelected = in_array($color->name, $selectedColors); @endphp
                    <button data-filter-type="color" data-filter-value="{{ $color->name }}"
                        class="filter-color relative size-9 rounded-full border border-gray-100 ring-offset-2 {{ $isSelected ? 'ring-2 ring-primary' : '' }}" 
                        style="background-color: {{ $color->hex_code }}"
                        data-selected="{{ $isSelected ? 'true' : 'false' }}">
                        @if($isSelected)
                        <span class="material-symbols-outlined absolute inset-0 flex items-center justify-center text-white text-[14px] font-black">check</span>
                        @endif
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Price --}}
            <div>
                <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Price Range</h4>
                <div class="space-y-2">
                    @php $selectedPrices = request('price_range', []); @endphp
                    @foreach(['~ 5만원', '5만원 ~ 10만원', '10만원 이상'] as $priceRange)
                    <label class="flex items-center justify-between p-3 rounded-xl border border-gray-100 bg-gray-50 cursor-pointer">
                        <span class="text-sm font-bold text-text-muted">{{ $priceRange }}</span>
                        <input type="checkbox" data-filter-type="price" data-filter-value="{{ $priceRange }}"
                            {{ in_array($priceRange, $selectedPrices) ? 'checked' : '' }}
                            class="filter-price size-5 rounded-md border-gray-300 text-primary focus:ring-primary/20" />
                    </label>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const $drawer = $('#mobile-filter-drawer');
    const $drawerOverlay = $('#filter-drawer-overlay');
    const $drawerContent = $('#filter-drawer-content');

    function openFilterDrawer() {
        $drawer.removeClass('hidden');
        setTimeout(() => {
            $drawerOverlay.addClass('opacity-100');
            $drawerContent.removeClass('translate-x-full');
        }, 10);
        $('body').css('overflow', 'hidden');
    }

    function closeFilterDrawer() {
        $drawerOverlay.removeClass('opacity-100');
        $drawerContent.addClass('translate-x-full');
        setTimeout(() => {
            $drawer.addClass('hidden');
        }, 300);
        $('body').css('overflow', '');
    }

    $('#btn-mobile-filter').on('click', openFilterDrawer);
    $('#filter-drawer-close, #filter-drawer-overlay').on('click', closeFilterDrawer);

    const $activeFiltersContainer = $('#active-filters');
    
    /**
     * 배열 파라미터 가져오기 (key[], key[0] 형태 모두 매칭)
     */
    function getArrayParams(params, baseKey) {
        const values = [];
        for (const [key, val] of params.entries()) {
            if (key === baseKey + '[]' || (key.startsWith(baseKey + '[') && key.endsWith(']'))) {
                values.push(val);
            }
        }
        return values;
    }

    /**
     * 배열 파라미터 일괄 삭제
     */
    function clearArrayParams(params, baseKey) {
        const keysToDelete = [];
        for (const key of params.keys()) {
            if (key === baseKey + '[]' || (key.startsWith(baseKey + '[') && key.endsWith(']'))) {
                keysToDelete.push(key);
            }
        }
        keysToDelete.forEach(k => params.delete(k));
    }

    /**
     * 필터링 상태 적용
     */
    function applyFilters() {
        const urlParams = new URLSearchParams(window.location.search);
        $activeFiltersContainer.empty();

        const cat = urlParams.get('category');
        if (cat) {
            const $catEl = $('.filter-category[data-filter-value="' + cat + '"]');
            if ($catEl.length) {
                let pureText = '';
                if ($catEl.first().find('span:first').length) {
                    pureText = $catEl.first().find('span:first').text().trim();
                } else {
                    pureText = $catEl.first().text().trim();
                }
                addFilterTag('category', pureText, cat);
            }
        }

        const colors = getArrayParams(urlParams, 'colors');
        colors.forEach(color => addFilterTag('colors', color, color));

        const prices = getArrayParams(urlParams, 'price_range');
        prices.forEach(price => addFilterTag('price_range', price, price));
    }

    /**
     * 필터 태그 렌더링
     */
    function addFilterTag(type, label, value) {
        const $tag = $(`
            <span class="inline-flex items-center gap-1.5 rounded-xl bg-primary/5 px-4 py-2 text-xs font-black text-primary border border-primary/10 animate-in fade-in slide-in-from-left-2 duration-300">
                ${label}
                <button class="material-symbols-outlined text-[16px] hover:scale-125 transition-transform" type="button">close</button>
            </span>
        `);
        
        $tag.find('button').on('click', () => removeFilter(type, value));
        $activeFiltersContainer.append($tag);
    }

    /**
     * 필터 해제 로직
     */
    function removeFilter(type, value) {
        const urlParams = new URLSearchParams(window.location.search);
        if (type === 'category') {
            urlParams.delete('category');
        } else {
            const current = getArrayParams(urlParams, type).filter(v => v !== value);
            clearArrayParams(urlParams, type);
            current.forEach(v => urlParams.append(type + '[]', v));
        }
        urlParams.delete('page');
        window.location.href = window.location.pathname + '?' + urlParams.toString();
    }

    /**
     * 필터 업데이트 통합 함수
     */
    function updateFilter(type, value, active) {
        const urlParams = new URLSearchParams(window.location.search);
        if (type === 'category') {
            if (value) urlParams.set('category', value);
            else urlParams.delete('category');
        } else {
            let current = getArrayParams(urlParams, type);
            if (active) {
                if (!current.includes(value)) current.push(value);
            } else {
                current = current.filter(v => v !== value);
            }
            clearArrayParams(urlParams, type);
            current.forEach(v => urlParams.append(type + '[]', v));
        }
        urlParams.delete('page');
        window.location.href = window.location.pathname + '?' + urlParams.toString();
    }

    // 카테고리 필터 클릭 이벤트
    $('.filter-category').on('click', function(e) {
        e.preventDefault();
        updateFilter('category', $(this).data('filterValue'));
    });

    // 색상 필터 클릭 이벤트 
    $('.filter-color').on('click', function() {
        const isSelected = $(this).attr('data-selected') === 'true';
        updateFilter('colors', $(this).data('filterValue'), !isSelected);
    });

    // 가격 필터 변경 이벤트 
    $('.filter-price').on('change', function() {
        updateFilter('price_range', $(this).data('filterValue'), this.checked);
    });

    // 정렬 순서 변경 이벤트
    $('#sort-filter').on('change', function() {
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('sort', $(this).val());
        window.location.href = window.location.pathname + '?' + urlParams.toString();
    });

    // 검색어 하이라이트 처리 
    const keyword = "{{ $keyword ?? '' }}";
    if (keyword) {
        $('.product-card-name').each(function() {
            const name = $(this).text();
            const highlighted = name.replace(new RegExp(keyword, 'gi'), match => `<mark class="bg-primary/10 text-primary p-0">${match}</mark>`);
            $(this).html(highlighted);
        });
    }

    applyFilters();
});
</script>
@endpush
