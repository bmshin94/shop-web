@extends('layouts.app')

@section('title', '상품 목록 - Active Women\'s Premium Store')

@section('content')
    <main class="flex-1">
      <!-- Breadcrumb & Page Title -->
      <div class="bg-background-alt py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <nav class="flex text-sm text-text-muted mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
              <li class="inline-flex items-center">
                <a href="/" class="hover:text-primary">Home</a>
              </li>
              <li>
                <div class="flex items-center">
                  <span class="material-symbols-outlined text-sm mx-1">chevron_right</span>
                  <a href="#" class="hover:text-primary">스포츠웨어</a>
                </div>
              </li>
              <li aria-current="page">
                <div class="flex items-center">
                  <span class="material-symbols-outlined text-sm mx-1">chevron_right</span>
                  <span class="text-text-main font-bold">탑 & 레깅스</span>
                </div>
              </li>
            </ol>
          </nav>
          <div class="flex items-end justify-between">
            <h2 class="text-3xl font-extrabold text-text-main tracking-tight">
              탑 & 레깅스
              <span class="text-lg font-medium text-text-muted ml-2">(124)</span>
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
                      <span class="bg-primary/10 text-primary px-2 py-0.5 rounded-full text-xs">124</span>
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
                  <button data-filter-type="color" data-filter-value="Black"
                    class="filter-color size-8 rounded-full bg-black ring-2 ring-transparent ring-offset-2 hover:ring-black transition-all"></button>
                  <button data-filter-type="color" data-filter-value="White"
                    class="filter-color size-8 rounded-full bg-white border border-gray-200 ring-2 ring-transparent ring-offset-2 hover:ring-gray-300 transition-all"></button>
                  <button data-filter-type="color" data-filter-value="Red"
                    class="filter-color size-8 rounded-full bg-primary ring-2 ring-transparent ring-offset-2 hover:ring-primary transition-all"></button>
                  <button data-filter-type="color" data-filter-value="Blue"
                    class="filter-color size-8 rounded-full bg-blue-500 ring-2 ring-transparent ring-offset-2 hover:ring-blue-500 transition-all"></button>
                  <button data-filter-type="color" data-filter-value="Pink"
                    class="filter-color size-8 rounded-full bg-pink-300 ring-2 ring-transparent ring-offset-2 hover:ring-pink-300 transition-all"></button>
                  <button data-filter-type="color" data-filter-value="Green"
                    class="filter-color size-8 rounded-full bg-green-500 ring-2 ring-transparent ring-offset-2 hover:ring-green-500 transition-all"></button>
                </div>
              </div>

              <!-- Price Filter -->
              <div>
                <h3 class="mb-4 text-base font-bold text-text-main border-b border-gray-200 pb-2">
                  가격대
                </h3>
                <div id="price-filter" class="space-y-3">
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" data-filter-type="price" data-filter-value="~ 5만원"
                      class="filter-price rounded border-gray-300 text-primary focus:ring-primary" />
                    <span class="text-sm text-text-muted">~ 5만원</span>
                  </label>
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" data-filter-type="price" data-filter-value="5만원 ~ 10만원"
                      class="filter-price rounded border-gray-300 text-primary focus:ring-primary" />
                    <span class="text-sm text-text-muted">5만원 ~ 10만원</span>
                  </label>
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" data-filter-type="price" data-filter-value="10만원 이상"
                      class="filter-price rounded border-gray-300 text-primary focus:ring-primary" />
                    <span class="text-sm text-text-muted">10만원 이상</span>
                  </label>
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
              <!-- Product 1 -->
              <div class="group relative flex flex-col">
                <div class="relative aspect-[3/4] w-full overflow-hidden rounded-lg bg-gray-200">
                  <div class="h-full w-full bg-cover bg-center transition-transform duration-500 group-hover:scale-105"
                    data-alt="Woman in white activewear posing" style="
                        background-image: url(&quot;https://lh3.googleusercontent.com/aida-public/AB6AXuDabAzzyO-1GPR9w9KUZy2t-akxd-pTry06ye-7EmfLIj4HsSh3Qsl3-4SPzOjkEiIvcTGGDyNJEuMpEpxJVMBh2D4z-w70xv-_n1ulP9ym_oYqWuFOnJqPl25Vm9FlyjgGTi65HS6qSzlRQgslgoXVASr5mvobAhP-rUuwV34o5MwDa2O-Tj9-CB71iqI7UuUDKfOzXILS0hUApxV--IBEjQ9t7EFGHTyyK8Vxetjz5EeEdY7nQBJbqJ9qIk1KAJSZqHHXH55EY1c&quot;);
                      "></div>
                  <div
                    class="absolute right-3 top-3 rounded-full bg-white/80 p-2 backdrop-blur-sm transition-colors hover:bg-primary hover:text-white cursor-pointer z-10">
                    <span class="material-symbols-outlined block text-lg">favorite</span>
                  </div>
                  <div class="absolute bottom-3 left-3 flex gap-1">
                    <span class="rounded bg-black/70 px-2 py-1 text-[10px] font-bold text-white backdrop-blur-sm">OOTD
                      SET</span>
                  </div>
                </div>
                <div class="mt-4 flex flex-1 flex-col">
                  <h4 class="text-base font-bold text-text-main">
                    위켄드 워리어 셋업
                  </h4>
                  <p class="text-xs text-text-muted mb-1">
                    탑 & 레깅스 투피스
                  </p>
                  <!-- Color Options inside card -->
                  <div class="flex gap-1 py-1 mb-1">
                    <span class="size-3 rounded-full bg-black ring-1 ring-gray-200"></span>
                    <span class="size-3 rounded-full bg-white ring-1 ring-gray-200"></span>
                    <span class="size-3 rounded-full bg-primary ring-1 ring-gray-200"></span>
                  </div>
                  <div class="mt-1 flex items-center justify-between">
                    <div class="flex flex-col">
                      <span class="text-xs text-red-500 font-bold">10%
                        <span class="text-text-muted font-normal line-through ml-1">₩165,000</span></span>
                      <span class="text-lg font-bold text-text-main">₩148,500</span>
                    </div>
                  </div>
                  <div class="mt-2 flex gap-1">
                    <span
                      class="inline-block rounded-sm bg-gray-100 px-1.5 py-0.5 text-[10px] text-gray-600">무료배송</span>
                    <span
                      class="inline-block rounded-sm bg-primary/10 px-1.5 py-0.5 text-[10px] text-primary">번들할인</span>
                  </div>
                </div>
              </div>

              <!-- Product 2 -->
              <div class="group relative flex flex-col">
                <div class="relative aspect-[3/4] w-full overflow-hidden rounded-lg bg-gray-200">
                  <div class="h-full w-full bg-cover bg-center transition-transform duration-500 group-hover:scale-105"
                    data-alt="Woman running in morning sunlight" style="
                        background-image: url(&quot;https://lh3.googleusercontent.com/aida-public/AB6AXuDG_KhKcmbL_tfD6o7Wodrv4S7ibX6ujwxndVGPgfKaIBYNTgJemxUf40EOkeIaRYUIpj4Ev6Ms7KW87nUBN0O2-_HjjU3XY6CIl9GHOnTsDi0DbDZZXY2loY2aWcN96JZOjSQ2uEKSZvAunpehA8VyNinHagNltBDPAkBIhe0i-DI1Zq8OGpc5uor1d9BwAoXZR_5E1hfFRSN4YmsMjijZ7Gly2NVRLkbKyBUnn8i98Zhc2BjTQt_-XWoDpQLGYPkEI9CwI5vRdtY&quot;);
                      "></div>
                  <div
                    class="absolute right-3 top-3 rounded-full bg-white/80 p-2 backdrop-blur-sm transition-colors hover:bg-primary hover:text-white cursor-pointer z-10 text-primary">
                    <span class="material-symbols-outlined block text-lg font-bold">favorite</span>
                  </div>
                  <span
                    class="absolute left-0 top-3 rounded-r bg-primary px-3 py-1 text-xs font-bold text-white shadow-md">BEST
                    1</span>
                </div>
                <div class="mt-4 flex flex-1 flex-col">
                  <h4 class="text-base font-bold text-text-main">
                    모닝 런 글로우 자켓
                  </h4>
                  <p class="text-xs text-text-muted mb-1">
                    초경량 퍼포먼스 윈드브레이커
                  </p>
                  <div class="mt-1 flex items-center justify-between">
                    <div class="flex flex-col">
                      <span class="text-lg font-bold text-text-main">₩98,000</span>
                    </div>
                  </div>
                  <div class="mt-2 flex gap-1">
                    <span
                      class="inline-block rounded-sm bg-gray-100 px-1.5 py-0.5 text-[10px] text-gray-600">쿠폰적용가</span>
                  </div>
                </div>
              </div>

              <!-- Product 3 -->
              <div class="group relative flex flex-col">
                <div class="relative aspect-[3/4] w-full overflow-hidden rounded-lg bg-gray-200">
                  <!-- 더미 컬러 이미지 적용 -->
                  <div class="h-full w-full bg-stone-300 transition-transform duration-500 group-hover:scale-105"></div>
                  <div
                    class="absolute right-3 top-3 rounded-full bg-white/80 p-2 backdrop-blur-sm transition-colors hover:bg-primary hover:text-white cursor-pointer z-10">
                    <span class="material-symbols-outlined block text-lg">favorite</span>
                  </div>
                </div>
                <div class="mt-4 flex flex-1 flex-col">
                  <h4 class="text-base font-bold text-text-main">
                    에어리 하이웨이스트 레깅스
                  </h4>
                  <p class="text-xs text-text-muted mb-1">
                    버터같은 부드러움, 강력한 서포트
                  </p>
                  <div class="flex gap-1 py-1 mb-1">
                    <span class="size-3 rounded-full bg-stone-400 ring-1 ring-gray-200"></span>
                    <span class="size-3 rounded-full bg-blue-900 ring-1 ring-gray-200"></span>
                  </div>
                  <div class="mt-1 flex items-center justify-between">
                    <div class="flex flex-col">
                      <span class="text-lg font-bold text-text-main">₩59,000</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Product 4 -->
              <div class="group relative flex flex-col">
                <div class="relative aspect-[3/4] w-full overflow-hidden rounded-lg bg-gray-200">
                  <div class="h-full w-full bg-slate-800 transition-transform duration-500 group-hover:scale-105"></div>
                  <div
                    class="absolute right-3 top-3 rounded-full bg-white/80 p-2 backdrop-blur-sm transition-colors hover:bg-primary hover:text-white cursor-pointer z-10">
                    <span class="material-symbols-outlined block text-lg">favorite</span>
                  </div>
                  <span
                    class="absolute left-0 top-3 rounded-r bg-gray-800 px-3 py-1 text-xs font-bold text-white shadow-md">NEW</span>
                </div>
                <div class="mt-4 flex flex-1 flex-col">
                  <h4 class="text-base font-bold text-text-main">
                    크로스백 서포트 브라탑
                  </h4>
                  <p class="text-xs text-text-muted mb-1">
                    미디엄 임팩트 서포트
                  </p>
                  <div class="mt-1 flex items-center justify-between">
                    <div class="flex flex-col">
                      <span class="text-lg font-bold text-text-main">₩45,000</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Product 5 -->
              <div class="group relative flex flex-col">
                <div class="relative aspect-[3/4] w-full overflow-hidden rounded-lg bg-gray-200">
                  <div class="h-full w-full bg-pink-100 transition-transform duration-500 group-hover:scale-105"></div>
                  <div
                    class="absolute right-3 top-3 rounded-full bg-white/80 p-2 backdrop-blur-sm transition-colors hover:bg-primary hover:text-white cursor-pointer z-10">
                    <span class="material-symbols-outlined block text-lg">favorite</span>
                  </div>
                </div>
                <div class="mt-4 flex flex-1 flex-col">
                  <h4 class="text-base font-bold text-text-main">
                    루즈핏 액티브 크롭 커버업
                  </h4>
                  <p class="text-xs text-text-muted mb-1">
                    운동 전후 가볍게 걸치기 좋은
                  </p>
                  <div class="mt-1 flex items-center justify-between">
                    <div class="flex flex-col">
                      <span class="text-lg font-bold text-text-main">₩38,000</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Pagination -->
            <div class="mt-16 flex items-center justify-center gap-2">
              <button
                class="flex size-10 items-center justify-center text-gray-400 hover:text-primary disabled:opacity-50"
                disabled>
                <span class="material-symbols-outlined">chevron_left</span>
              </button>
              <button class="flex size-10 items-center justify-center rounded-full bg-primary font-bold text-white">
                1
              </button>
              <button class="flex size-10 items-center justify-center rounded-full text-text-main hover:bg-gray-100">
                2
              </button>
              <button class="flex size-10 items-center justify-center rounded-full text-text-main hover:bg-gray-100">
                3
              </button>
              <span class="px-2 text-gray-400">...</span>
              <button class="flex size-10 items-center justify-center rounded-full text-text-main hover:bg-gray-100">
                12
              </button>
              <button class="flex size-10 items-center justify-center text-text-main hover:text-primary">
                <span class="material-symbols-outlined">chevron_right</span>
              </button>
            </div>
          </div>
        </div>
      </section>
    </main>
@endsection

@push('scripts')
  <script>
    (function () {
      const activeFiltersContainer = document.getElementById('active-filters');
      const activeFilters = new Map(); // key: "type:value", value: { type, value, element }

      // === 필터 태그 생성 ===
      function addFilterTag(type, value) {
        const key = type + ':' + value;
        if (activeFilters.has(key)) return;

        const tag = document.createElement('span');
        tag.className = 'inline-flex items-center gap-1 rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-text-main animate-fade-in';
        tag.dataset.filterKey = key;
        tag.innerHTML = value + '<button class="material-symbols-outlined text-[14px] hover:text-primary ml-0.5" type="button">close</button>';

        // 태그의 X 버튼 클릭 시 필터 해제
        tag.querySelector('button').addEventListener('click', function () {
          removeFilter(type, value);
        });

        activeFiltersContainer.appendChild(tag);
        activeFilters.set(key, { type, value, element: tag });
      }

      // === 필터 태그 제거 ===
      function removeFilterTag(type, value) {
        const key = type + ':' + value;
        const filter = activeFilters.get(key);
        if (filter) {
          filter.element.remove();
          activeFilters.delete(key);
        }
      }

      // === 필터 해제 (태그 제거 + 사이드바 상태 복구) ===
      function removeFilter(type, value) {
        removeFilterTag(type, value);

        if (type === 'category') {
          // 카테고리 해제 → 전체보기를 active로
          document.querySelectorAll('.filter-category').forEach(function (link) {
            link.classList.remove('active', 'font-bold', 'text-primary');
            link.classList.add('hover:text-primary');
          });
          const allView = document.querySelector('.filter-category[data-filter-value="전체보기"]');
          if (allView) {
            allView.classList.add('active', 'font-bold', 'text-primary');
            allView.classList.remove('hover:text-primary');
          }
        } else if (type === 'color') {
          const colorBtn = document.querySelector('.filter-color[data-filter-value="' + value + '"]');
          if (colorBtn) {
            colorBtn.classList.remove('ring-2');
            colorBtn.classList.add('ring-transparent');
            colorBtn.dataset.selected = 'false';
            // 색상별 ring 색 제거
            colorBtn.className = colorBtn.className.replace(/ring-(black|primary|blue-500|pink-300|green-500|gray-300)/g, 'ring-transparent');
          }
        } else if (type === 'price') {
          const priceCheckbox = document.querySelector('.filter-price[data-filter-value="' + value + '"]');
          if (priceCheckbox) priceCheckbox.checked = false;
        }
      }

      // === 카테고리 필터 클릭 ===
      document.querySelectorAll('.filter-category').forEach(function (link) {
        link.addEventListener('click', function (e) {
          e.preventDefault();
          const value = this.dataset.filterValue;

          // 기존 카테고리 필터 태그 모두 제거
          activeFilters.forEach(function (f, key) {
            if (f.type === 'category') removeFilterTag('category', f.value);
          });

          // 모든 카테고리 링크 비활성화 스타일
          document.querySelectorAll('.filter-category').forEach(function (l) {
            l.classList.remove('active', 'font-bold', 'text-primary');
            l.classList.add('hover:text-primary');
          });

          // 클릭한 항목 활성화
          this.classList.add('active', 'font-bold', 'text-primary');
          this.classList.remove('hover:text-primary');

          // "전체보기"가 아닌 경우에만 태그 추가
          if (value !== '전체보기') {
            addFilterTag('category', value);
          }
        });
      });

      // === 색상 필터 클릭 ===
      const colorRingMap = {
        'Black': 'ring-black',
        'White': 'ring-gray-300',
        'Red': 'ring-primary',
        'Blue': 'ring-blue-500',
        'Pink': 'ring-pink-300',
        'Green': 'ring-green-500'
      };

      document.querySelectorAll('.filter-color').forEach(function (btn) {
        btn.dataset.selected = 'false';
        btn.addEventListener('click', function () {
          const value = this.dataset.filterValue;
          const isSelected = this.dataset.selected === 'true';
          const ringColor = colorRingMap[value] || 'ring-black';

          if (isSelected) {
            // 선택 해제
            this.dataset.selected = 'false';
            this.classList.remove(ringColor);
            this.classList.add('ring-transparent');
            removeFilterTag('color', value);
          } else {
            // 선택
            this.dataset.selected = 'true';
            this.classList.remove('ring-transparent');
            this.classList.add(ringColor);
            addFilterTag('color', value);
          }
        });
      });

      // === 가격대 필터 체크박스 ===
      document.querySelectorAll('.filter-price').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
          const value = this.dataset.filterValue;
          if (this.checked) {
            addFilterTag('price', value);
          } else {
            removeFilterTag('price', value);
          }
        });
      });
    })();
  </script>
@endpush
