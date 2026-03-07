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
          <a href="#" class="hover:text-primary">{{ $item }}</a>
          @endif
        </li>
        @endforeach
      </ol>
    </nav>
    <div class="flex items-end justify-between">
      <h2 class="text-3xl font-extrabold text-text-main tracking-tight">
        {{ $pageTitle }}
        <span class="text-lg font-medium text-text-muted ml-2">({{ count($products) }})</span>
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
              <a href="#" data-filter-type="category" data-filter-value="전체보기"
                class="filter-category active font-bold text-primary flex justify-between items-center">
                전체보기
                <span class="bg-primary/10 text-primary px-2 py-0.5 rounded-full text-xs">{{ count($products) }}</span>
              </a>
            </li>
            <li>
              <a href="#" data-filter-type="category" data-filter-value="스포츠 브라"
                class="filter-category hover:text-primary flex justify-between items-center">
                스포츠 브라 <span class="text-xs">45</span>
              </a>
            </li>
            <li>
              <a href="#" data-filter-type="category" data-filter-value="반팔/긴팔 탑"
                class="filter-category hover:text-primary flex justify-between items-center">
                반팔/긴팔 탑 <span class="text-xs">32</span>
              </a>
            </li>
            <li>
              <a href="#" data-filter-type="category" data-filter-value="레깅스/타이츠"
                class="filter-category hover:text-primary flex justify-between items-center">
                레깅스/타이츠 <span class="text-xs">47</span>
              </a>
            </li>
          </ul>
        </div>

        <!-- Color Filter -->
        <div>
          <h3 class="mb-4 text-base font-bold text-text-main border-b border-gray-200 pb-2">
            색상
          </h3>
          <div id="color-filter" class="flex flex-wrap gap-2">
            @php
              $filterColors = [
                ['name' => 'Black', 'class' => 'bg-black'],
                ['name' => 'White', 'class' => 'bg-white border border-gray-200'],
                ['name' => 'Red', 'class' => 'bg-primary'],
                ['name' => 'Blue', 'class' => 'bg-blue-500'],
                ['name' => 'Pink', 'class' => 'bg-pink-300'],
                ['name' => 'Green', 'class' => 'bg-green-500'],
              ];
            @endphp
            @foreach($filterColors as $color)
            <button data-filter-type="color" data-filter-value="{{ $color['name'] }}"
              class="filter-color size-8 rounded-full {{ $color['class'] }} ring-2 ring-transparent ring-offset-2 hover:ring-gray-300 transition-all"></button>
            @endforeach
          </div>
        </div>

        <!-- Price Filter -->
        <div>
          <h3 class="mb-4 text-base font-bold text-text-main border-b border-gray-200 pb-2">
            가격대
          </h3>
          <div id="price-filter" class="space-y-3">
            @foreach(['~ 5만원', '5만원 ~ 10만원', '10만원 이상'] as $priceRange)
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" data-filter-type="price" data-filter-value="{{ $priceRange }}"
                class="filter-price rounded border-gray-300 text-primary focus:ring-primary" />
              <span class="text-sm text-text-muted">{{ $priceRange }}</span>
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
          <!-- 필터 태그가 JS로 동적 생성됩니다 -->
        </div>
        <div class="relative">
          <select
            class="appearance-none rounded-lg border border-gray-200 bg-white px-4 py-2 pr-10 text-sm font-medium text-text-main focus:border-primary focus:ring-1 focus:ring-primary outline-none cursor-pointer">
            <option>신상품순</option>
            <option>인기순</option>
            <option>낮은 가격순</option>
            <option>높은 가격순</option>
          </select>
        </div>
      </div>

      <!-- Grid -->
      <div class="grid gap-x-6 gap-y-10 grid-cols-2 md:grid-cols-3 xl:grid-cols-4">
        @forelse($products as $product)
        <!-- Product Card -->
        <div class="group relative flex flex-col">
          <div class="relative aspect-[3/4] w-full overflow-hidden rounded-lg bg-gray-200">
            <div class="h-full w-full bg-cover bg-center transition-transform duration-500 group-hover:scale-105"
              style="background-image: url('{{ $product['image_url'] }}');"></div>
            
            <div class="absolute right-3 top-3 rounded-full bg-white/80 p-2 backdrop-blur-sm transition-colors hover:bg-primary hover:text-white cursor-pointer z-10">
              <span class="material-symbols-outlined block text-lg">favorite</span>
            </div>

            @if($product['is_best'])
            <span class="absolute left-0 top-3 rounded-r bg-primary px-3 py-1 text-xs font-bold text-white shadow-md">BEST {{ $product['best_rank'] }}</span>
            @endif

            @if($product['overlay_tag'])
            <div class="absolute bottom-3 left-3 flex gap-1">
              <span class="rounded bg-black/70 px-2 py-1 text-[10px] font-bold text-white backdrop-blur-sm">{{ $product['overlay_tag'] }}</span>
            </div>
            @endif
          </div>

          <div class="mt-4 flex flex-1 flex-col">
            <h4 class="text-base font-bold text-text-main">
              {{ $product['name'] }}
            </h4>
            <p class="text-xs text-text-muted mb-1">
              {{ $product['description'] }}
            </p>

            <!-- Color Options -->
            @if(count($product['colors']) > 0)
            <div class="flex gap-1 py-1 mb-1">
              @foreach($product['colors'] as $colorClass)
              <span class="size-3 rounded-full {{ $colorClass }} ring-1 ring-gray-200"></span>
              @endforeach
            </div>
            @endif

            <div class="mt-1 flex items-center justify-between">
              <div class="flex flex-col">
                @if($product['discount_rate'])
                <span class="text-xs text-red-500 font-bold">
                  {{ $product['discount_rate'] }}%
                  <span class="text-text-muted font-normal line-through ml-1">₩{{ number_format($product['original_price']) }}</span>
                </span>
                @endif
                <span class="text-lg font-bold text-text-main">₩{{ number_format($product['price']) }}</span>
              </div>
            </div>

            @if(count($product['tags']) > 0)
            <div class="mt-2 flex gap-1">
              @foreach($product['tags'] as $tag)
              <span class="inline-block rounded-sm bg-gray-100 px-1.5 py-0.5 text-[10px] text-gray-600">{{ $tag }}</span>
              @endforeach
            </div>
            @endif
          </div>
        </div>
        @empty
        <div class="col-span-full py-24 text-center">
            <p class="text-text-muted">상품이 없습니다. 😊</p>
        </div>
        @endforelse
      </div>

      <!-- Pagination -->
      <div class="mt-16 flex items-center justify-center gap-2">
        <button class="flex size-10 items-center justify-center text-gray-400 hover:text-primary disabled:opacity-50" disabled>
          <span class="material-symbols-outlined">chevron_left</span>
        </button>
        <button class="flex size-10 items-center justify-center rounded-full bg-primary font-bold text-white">1</button>
        <button class="flex size-10 items-center justify-center rounded-full text-text-main hover:bg-gray-100">2</button>
        <button class="flex size-10 items-center justify-center rounded-full text-text-main hover:bg-gray-100">3</button>
        <span class="px-2 text-gray-400">...</span>
        <button class="flex size-10 items-center justify-center rounded-full text-text-main hover:bg-gray-100">12</button>
        <button class="flex size-10 items-center justify-center text-text-main hover:text-primary">
          <span class="material-symbols-outlined">chevron_right</span>
        </button>
      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
  (function () {
    const activeFiltersContainer = document.getElementById('active-filters');
    const activeFilters = new Map();

    function addFilterTag(type, value) {
      const key = type + ':' + value;
      if (activeFilters.has(key)) return;
      const tag = document.createElement('span');
      tag.className = 'inline-flex items-center gap-1 rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-text-main animate-fade-in';
      tag.dataset.filterKey = key;
      tag.innerHTML = value + '<button class="material-symbols-outlined text-[14px] hover:text-primary ml-0.5" type="button">close</button>';
      tag.querySelector('button').addEventListener('click', () => removeFilter(type, value));
      activeFiltersContainer.appendChild(tag);
      activeFilters.set(key, { type, value, element: tag });
    }

    function removeFilter(type, value) {
      const key = type + ':' + value;
      const filter = activeFilters.get(key);
      if (filter) {
        filter.element.remove();
        activeFilters.delete(key);
      }
      // UI 상태 복구 로직 (생략 - 필요 시 추가)
    }

    document.querySelectorAll('.filter-category').forEach(link => {
      link.addEventListener('click', function (e) {
        e.preventDefault();
        const value = this.dataset.filterValue;
        activeFilters.forEach((f, k) => { if (f.type === 'category') removeFilter('category', f.value); });
        document.querySelectorAll('.filter-category').forEach(l => l.classList.remove('active', 'font-bold', 'text-primary'));
        this.classList.add('active', 'font-bold', 'text-primary');
        if (value !== '전체보기') addFilterTag('category', value);
      });
    });
    // 나머지 필터 JS는 HTML과 동일하게 구현 가능
  })();
</script>
@endpush
