@extends('layouts.app')

@section('content')
<!-- Breadcrumb & Page Title -->
<div class="bg-background-alt py-8">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <nav class="flex text-sm text-text-muted mb-4" aria-label="Breadcrumb">
      <ol class="inline-flex items-center space-x-1 md:space-x-3">
        @foreach($breadcrumb as $index => $item)
        <li class="inline-flex items-center">
          @if($index > 0)
          <span class="material-symbols-outlined text-sm mx-1">chevron_right</span>
          @endif
          @if($loop->last)
          <span class="text-text-main font-bold">{{ $item }}</span>
          @else
          <a href="/" class="hover:text-primary">{{ $item }}</a>
          @endif
        </li>
        @endforeach
      </ol>
    </nav>
    <div class="flex items-end justify-between">
      <h2 class="text-3xl font-extrabold text-text-main tracking-tight">
        {{ $pageTitle }}
        <span class="text-lg font-medium text-text-muted ml-2">({{ $products->total() }})</span>
      </h2>
      <!-- Mobile Filter Toggle -->
      <button
        class="lg:hidden flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-bold text-text-main hover:border-primary hover:text-primary">
        <span class="material-symbols-outlined text-xl">tune</span> 필터
      </button>
    </div>
  </div>
</div>

<section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
  <div class="flex flex-col lg:flex-row gap-8">
    <!-- Sidebar Filter -->
    <aside class="hidden lg:block w-64 shrink-0">
      <div class="sticky top-28 space-y-8">
        <!-- Category Filter -->
        <div>
          <h3 class="mb-4 text-base font-bold text-text-main border-b border-gray-200 pb-2">
            카테고리
          </h3>
          <ul id="category-filter" class="space-y-3 text-sm text-text-muted">
            <li>
              <a href="#" data-filter-type="category" data-filter-value=""
                class="filter-category {{ !request('category') ? 'active font-bold text-primary' : '' }} flex justify-between items-center transition-colors hover:text-primary">
                전체보기
              </a>
            </li>
            @foreach($globalCategories ?? [] as $parentCat)
            <li class="pt-2">
              <p class="text-xs font-black text-text-main uppercase tracking-wider mb-2">{{ $parentCat->name }}</p>
              <ul class="pl-2 space-y-2">
                @foreach($parentCat->children as $childCat)
                <li>
                  <a href="#" data-filter-type="category" data-filter-value="{{ $childCat->slug }}"
                    class="filter-category {{ request('category') == $childCat->slug ? 'active font-bold text-primary' : '' }} flex justify-between items-center transition-colors hover:text-primary">
                    {{ $childCat->name }}
                  </a>
                </li>
                @endforeach
              </ul>
            </li>
            @endforeach
          </ul>
        </div>

        <!-- Color Filter (Real DB Colors) -->
        <div>
          <h3 class="mb-4 text-base font-bold text-text-main border-b border-gray-200 pb-2">
            색상
          </h3>
          <div id="color-filter" class="flex flex-wrap gap-2">
            @php
              $dbColors = \App\Models\Color::orderBy('name')->get();
              $selectedColors = request('colors', []);
            @endphp
            @foreach($dbColors as $color)
            @php $isSelected = in_array($color->name, $selectedColors); @endphp
            <button data-filter-type="color" data-filter-value="{{ $color->name }}"
              title="{{ $color->name }}"
              class="filter-color size-8 rounded-full ring-2 {{ $isSelected ? 'ring-primary' : 'ring-transparent' }} ring-offset-2 hover:ring-gray-300 transition-all shadow-sm border border-gray-100" 
              style="background-color: {{ $color->hex_code }}"
              data-selected="{{ $isSelected ? 'true' : 'false' }}"></button>
            @endforeach
          </div>
        </div>

        <!-- Price Filter -->
        <div>
          <h3 class="mb-4 text-base font-bold text-text-main border-b border-gray-200 pb-2">
            가격대
          </h3>
          <div id="price-filter" class="space-y-3">
            @php $selectedPrices = request('price_range', []); @endphp
            @foreach(['~ 5만원', '5만원 ~ 10만원', '10만원 이상'] as $priceRange)
            <label class="flex items-center gap-2 cursor-pointer group">
              <input type="checkbox" data-filter-type="price" data-filter-value="{{ $priceRange }}"
                {{ in_array($priceRange, $selectedPrices) ? 'checked' : '' }}
                class="filter-price rounded border-gray-300 text-primary focus:ring-primary" />
              <span class="text-sm text-text-muted group-hover:text-text-main transition-colors">{{ $priceRange }}</span>
            </label>
            @endforeach
          </div>
        </div>
      </div>
    </aside>

    <!-- Product Grid Area -->
    <div class="flex-1">
      <!-- Top Toolbar -->
      <div class="mb-6 flex items-center justify-between">
        <div id="active-filters" class="flex flex-wrap gap-2">
        </div>
        <div class="relative">
          <select id="sort-filter"
            class="appearance-none rounded-lg border border-gray-200 bg-white px-4 py-2 pr-10 text-sm font-medium text-text-main focus:border-primary focus:ring-1 focus:ring-primary outline-none cursor-pointer">
            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>신상품순</option>
            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>인기순</option>
            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>낮은 가격순</option>
            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>높은 가격순</option>
          </select>
        </div>
      </div>

      <!-- Grid -->
      <div class="grid gap-x-6 gap-y-10 grid-cols-2 md:grid-cols-3 xl:grid-cols-4">
        @forelse($products as $product)
          <x-product-card :product="$product" />
        @empty
        <div class="col-span-full py-24 text-center">
            <p class="text-text-muted">조건에 맞는 상품이 없습니다.</p>
        </div>
        @endforelse
      </div>

      <!-- Pagination -->
      <div class="mt-16">
        {{ $products->links() }}
      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
    (function () {
      const activeFiltersContainer = document.getElementById('active-filters');
      
      function applyFilters() {
        const urlParams = new URLSearchParams(window.location.search);
        activeFiltersContainer.innerHTML = '';

        const cat = urlParams.get('category');
        if (cat) {
            const catEl = document.querySelector(`.filter-category[data-filter-value="${cat}"]`);
            if (catEl) addFilterTag('category', catEl.textContent.trim(), cat);
        }

        urlParams.getAll('colors[]').forEach(color => {
            addFilterTag('colors', color, color);
        });

        urlParams.getAll('price_range[]').forEach(price => {
            addFilterTag('price_range', price, price);
        });
      }

      function addFilterTag(type, label, value) {
        const tag = document.createElement('span');
        tag.className = 'inline-flex items-center gap-1 rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-text-main animate-fade-in border border-gray-200';
        tag.innerHTML = label + '<button class="material-symbols-outlined text-[14px] hover:text-primary ml-0.5" type="button">close</button>';
        tag.querySelector('button').addEventListener('click', () => removeFilter(type, value));
        activeFiltersContainer.appendChild(tag);
      }

      function removeFilter(type, value) {
        const urlParams = new URLSearchParams(window.location.search);
        if (type === 'category') urlParams.delete('category');
        else if (type === 'colors') {
            const current = urlParams.getAll('colors[]').filter(v => v !== value);
            urlParams.delete('colors[]');
            current.forEach(v => urlParams.append('colors[]', v));
        } else if (type === 'price_range') {
            const current = urlParams.getAll('price_range[]').filter(v => v !== value);
            urlParams.delete('price_range[]');
            current.forEach(v => urlParams.append('price_range[]', v));
        }
        urlParams.delete('page');
        window.location.href = window.location.pathname + '?' + urlParams.toString();
      }

      function updateFilter(type, value, active) {
        const urlParams = new URLSearchParams(window.location.search);
        if (type === 'category') {
            if (value) urlParams.set('category', value);
            else urlParams.delete('category');
        } else if (type === 'colors[]') {
            if (active) urlParams.append('colors[]', value);
            else {
                const current = urlParams.getAll('colors[]').filter(v => v !== value);
                urlParams.delete('colors[]');
                current.forEach(v => urlParams.append('colors[]', v));
            }
        } else if (type === 'price_range[]') {
            if (active) {
                // 중복 방지
                const current = urlParams.getAll('price_range[]');
                if (!current.includes(value)) urlParams.append('price_range[]', value);
            } else {
                const current = urlParams.getAll('price_range[]').filter(v => v !== value);
                urlParams.delete('price_range[]');
                current.forEach(v => urlParams.append('price_range[]', v));
            }
        }
        urlParams.delete('page');
        window.location.href = window.location.pathname + '?' + urlParams.toString();
      }

      document.querySelectorAll('.filter-category').forEach(link => {
        link.addEventListener('click', function (e) {
          e.preventDefault();
          updateFilter('category', this.dataset.filterValue);
        });
      });

      document.querySelectorAll('.filter-color').forEach(btn => {
        btn.addEventListener('click', function () {
          const isSelected = this.dataset.selected === 'true';
          updateFilter('colors[]', this.dataset.filterValue, !isSelected);
        });
      });

      document.querySelectorAll('.filter-price').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
          updateFilter('price_range[]', this.dataset.filterValue, this.checked);
        });
      });

      document.getElementById('sort-filter').addEventListener('change', function() {
          const urlParams = new URLSearchParams(window.location.search);
          urlParams.set('sort', this.value);
          window.location.href = window.location.pathname + '?' + urlParams.toString();
      });

      applyFilters();
    })();
</script>
@endpush
