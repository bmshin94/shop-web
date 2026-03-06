@extends('layouts.app')

@section('title', '상품 상세 - Active Women\'s Premium Store')

@section('content')
    <main class="flex-1 bg-background-light">
      <!-- Breadcrumb -->
      <div class="bg-background-light py-4 border-b border-gray-100">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <nav class="flex text-xs text-text-muted" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
              <li class="inline-flex items-center">
                <a href="/" class="hover:text-primary transition-colors">Home</a>
              </li>
              <li>
                <div class="flex items-center">
                  <span class="material-symbols-outlined text-[14px] mx-1">chevron_right</span>
                  <a href="/product-list" class="hover:text-primary transition-colors">스포츠웨어</a>
                </div>
              </li>
              <li>
                <div class="flex items-center">
                  <span class="material-symbols-outlined text-[14px] mx-1">chevron_right</span>
                  <a href="/product-list" class="hover:text-primary transition-colors">탑 & 레깅스</a>
                </div>
              </li>
              <li aria-current="page">
                <div class="flex items-center">
                  <span class="material-symbols-outlined text-[14px] mx-1">chevron_right</span>
                  <span class="text-text-main font-bold">위켄드 워리어 셋업</span>
                </div>
              </li>
            </ol>
          </nav>
        </div>
      </div>

      <!-- Product Top Section (Gallery & Info) -->
      <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-12">
        <div class="lg:grid lg:grid-cols-2 lg:gap-x-12 xl:gap-x-16">
          <!-- Product Gallery (Left) -->
          <div class="flex flex-col-reverse lg:flex-row gap-4 mb-10 lg:mb-0">
            <!-- Thumbnail list -->
            <div
              class="flex lg:flex-col gap-3 overflow-x-auto lg:overflow-y-auto lg:w-20 xl:w-24 shrink-0 scrollbar-hide py-1">
              <button
                class="relative aspect-[3/4] w-20 lg:w-full overflow-hidden rounded-lg border-2 border-primary shrink-0 transition-opacity hover:opacity-90">
                <img
                  src="https://lh3.googleusercontent.com/aida-public/AB6AXuDabAzzyO-1GPR9w9KUZy2t-akxd-pTry06ye-7EmfLIj4HsSh3Qsl3-4SPzOjkEiIvcTGGDyNJEuMpEpxJVMBh2D4z-w70xv-_n1ulP9ym_oYqWuFOnJqPl25Vm9FlyjgGTi65HS6qSzlRQgslgoXVASr5mvobAhP-rUuwV34o5MwDa2O-Tj9-CB71iqI7UuUDKfOzXILS0hUApxV--IBEjQ9t7EFGHTyyK8Vxetjz5EeEdY7nQBJbqJ9qIk1KAJSZqHHXH55EY1c"
                  alt="Thumbnail 1" class="h-full w-full object-cover" />
              </button>
              <button
                class="relative aspect-[3/4] w-20 lg:w-full overflow-hidden rounded-lg border-2 border-transparent hover:border-gray-300 opacity-60 hover:opacity-100 transition-all shrink-0">
                <div class="h-full w-full bg-stone-200"></div>
              </button>
              <button
                class="relative aspect-[3/4] w-20 lg:w-full overflow-hidden rounded-lg border-2 border-transparent hover:border-gray-300 opacity-60 hover:opacity-100 transition-all shrink-0">
                <div class="h-full w-full bg-stone-300"></div>
              </button>
              <button
                class="relative aspect-[3/4] w-20 lg:w-full overflow-hidden rounded-lg border-2 border-transparent hover:border-gray-300 opacity-60 hover:opacity-100 transition-all shrink-0 flex items-center justify-center bg-gray-100">
                <span class="material-symbols-outlined text-gray-400 text-3xl">play_circle</span>
              </button>
            </div>

            <!-- Main Image -->
            <div class="relative w-full aspect-[3/4] rounded-2xl overflow-hidden bg-gray-100 group">
              <img
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuDabAzzyO-1GPR9w9KUZy2t-akxd-pTry06ye-7EmfLIj4HsSh3Qsl3-4SPzOjkEiIvcTGGDyNJEuMpEpxJVMBh2D4z-w70xv-_n1ulP9ym_oYqWuFOnJqPl25Vm9FlyjgGTi65HS6qSzlRQgslgoXVASr5mvobAhP-rUuwV34o5MwDa2O-Tj9-CB71iqI7UuUDKfOzXILS0hUApxV--IBEjQ9t7EFGHTyyK8Vxetjz5EeEdY7nQBJbqJ9qIk1KAJSZqHHXH55EY1c"
                alt="Main Product Image"
                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105 cursor-zoom-in" />

              <!-- Floating Actions -->
              <div class="absolute right-4 top-4 flex flex-col gap-3">
                <button
                  class="flex size-10 items-center justify-center rounded-full bg-white/90 text-text-main shadow-md hover:bg-primary hover:text-white transition-colors cursor-pointer z-10">
                  <span class="material-symbols-outlined block text-xl">favorite</span>
                </button>
                <button
                  class="flex size-10 items-center justify-center rounded-full bg-white/90 text-text-main shadow-md hover:text-primary transition-colors cursor-pointer z-10">
                  <span class="material-symbols-outlined block text-xl">share</span>
                </button>
              </div>
            </div>
          </div>

          <!-- Product Info (Right) -->
          <div class="flex flex-col mt-4 lg:mt-0">
            <div class="mb-6">
              <div class="flex items-center justify-between mb-2">
                <a href="#" class="text-sm font-bold text-primary hover:underline transition-colors">ACTIVE WOMEN
                  CLASSICS</a>
                <div class="flex items-center gap-1 cursor-pointer group">
                  <span
                    class="material-symbols-outlined text-yellow-500 text-sm group-hover:text-yellow-600 transition-colors">star</span>
                  <span class="text-sm font-bold text-text-main">4.8</span>
                  <a href="#reviews" class="text-sm text-text-muted hover:underline ml-1">(리뷰 143건)</a>
                </div>
              </div>
              <h1
                class="text-3xl sm:text-4xl font-extrabold text-text-main tracking-tight mb-3 break-keep leading-tight">
                위켄드 워리어 셋업
              </h1>
              <p class="text-base text-text-muted break-keep leading-relaxed">
                주말 러닝부터 브런치 모임까지 하나로 끝내는, 극강의 편안함과
                스타일을 갖춘 탑 & 레깅스 투피스.
              </p>
            </div>

            <div class="mb-6 pb-6 border-b border-gray-100">
              <div class="flex items-end gap-3 mb-3">
                <span class="text-3xl font-extrabold text-red-500 tracking-tight">10%</span>
                <div class="flex flex-col">
                  <span class="text-sm text-text-muted line-through mb-0.5">₩165,000</span>
                  <span class="text-3xl font-extrabold text-text-main tracking-tight">₩148,500</span>
                </div>
              </div>
              <!-- Badges -->
              <div class="flex flex-wrap gap-2">
                <span
                  class="inline-flex rounded-md bg-gray-100 px-2.5 py-1 text-xs font-bold text-gray-700 border border-gray-200">무료배송</span>
                <span
                  class="inline-flex rounded-md bg-primary/10 px-2.5 py-1 text-xs font-bold text-primary border border-primary/20">신규가입
                  1만원 할인</span>
              </div>
            </div>

            <!-- Options -->
            <div class="space-y-6">
              <!-- Color Option -->
              <div>
                <div class="flex justify-between items-center mb-3">
                  <h3 class="text-sm font-bold text-text-main">
                    색상:
                    <span class="text-text-muted font-normal ml-1">오트밀 화이트</span>
                  </h3>
                </div>
                <div class="flex flex-wrap gap-3">
                  <button
                    class="relative size-12 rounded-full ring-2 ring-primary ring-offset-2 overflow-hidden transition-all shadow-sm"
                    title="오트밀 화이트">
                    <span class="absolute inset-0 bg-[#f4f1eb]"></span>
                  </button>
                  <button
                    class="relative size-12 rounded-full ring-1 ring-gray-200 hover:ring-2 hover:ring-black ring-offset-2 overflow-hidden transition-all shadow-sm"
                    title="미드나잇 블랙">
                    <span class="absolute inset-0 bg-[#1a1a1a]"></span>
                  </button>
                  <button
                    class="relative size-12 rounded-full ring-1 ring-gray-200 hover:ring-2 hover:ring-slate-500 ring-offset-2 overflow-hidden transition-all shadow-sm"
                    title="스틸 그레이">
                    <span class="absolute inset-0 bg-[#6b7280]"></span>
                  </button>
                </div>
              </div>

              <!-- Size Option -->
              <div>
                <div class="flex justify-between items-center mb-3">
                  <h3 class="text-sm font-bold text-text-main">사이즈</h3>
                  <button class="text-xs text-text-muted underline hover:text-primary transition-colors">
                    사이즈 가이드
                  </button>
                </div>
                <div class="grid grid-cols-4 gap-3">
                  <button
                    class="flex h-12 items-center justify-center rounded-lg border border-gray-200 bg-gray-50 font-bold text-gray-400 cursor-not-allowed"
                    disabled>
                    S <span class="text-[10px] font-normal ml-1">(품절)</span>
                  </button>
                  <button
                    class="flex h-12 items-center justify-center rounded-lg border-2 border-primary bg-primary/5 font-bold text-primary shadow-sm">
                    M
                  </button>
                  <button
                    class="flex h-12 items-center justify-center rounded-lg border border-gray-300 bg-white font-bold text-text-main hover:border-text-main hover:bg-gray-50 transition-colors shadow-sm">
                    L
                  </button>
                  <button
                    class="flex h-12 items-center justify-center rounded-lg border border-gray-300 bg-white font-bold text-text-main hover:border-text-main hover:bg-gray-50 transition-colors shadow-sm">
                    XL
                  </button>
                </div>
              </div>

              <!-- Quantity -->
              <div class="flex items-center">
                <h3 class="w-16 text-sm font-bold text-text-main">수량</h3>
                <div id="qtyContainer"
                  class="flex items-center rounded-lg border border-gray-300 p-1 w-32 justify-between bg-white shadow-sm">
                  <button id="qtyMinus"
                    class="flex size-8 items-center justify-center rounded-md hover:bg-gray-100 text-gray-500 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">remove</span>
                  </button>
                  <span id="qtyDisplay" class="text-sm font-bold text-text-main text-center w-8">1</span>
                  <button id="qtyPlus"
                    class="flex size-8 items-center justify-center rounded-md hover:bg-gray-100 text-gray-500 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                  </button>
                </div>
              </div>
            </div>

            <!-- Total & CTA -->
            <div
              class="mt-8 pt-6 border-t border-gray-200 bg-white sticky bottom-0 z-30 lg:static lg:bg-transparent pb-4 lg:pb-0">
              <div class="flex justify-between items-end mb-4 lg:mb-6 px-4 lg:px-0">
                <span class="text-base font-bold text-text-main">총 결제 금액</span>
                <span id="totalPrice" class="text-3xl font-extrabold text-primary tracking-tight">₩148,500</span>
              </div>
              <div class="flex flex-col sm:flex-row gap-3 px-4 lg:px-0">
                <button
                  class="hidden sm:flex h-14 w-full sm:w-auto sm:flex-1 items-center justify-center rounded-xl border-2 border-primary bg-white text-base font-bold text-primary transition-colors hover:bg-primary/5 shadow-sm">
                  장바구니 담기
                </button>
                <button
                  class="flex h-14 w-full sm:w-auto sm:flex-grow-[2] items-center justify-center rounded-xl bg-primary text-base font-extrabold text-white transition-colors hover:bg-red-600 shadow-lg shadow-primary/30">
                  바로 구매하기
                </button>
              </div>
              <!-- Naver Pay Dummy -->
              <button
                class="mt-3 hidden lg:flex h-14 w-full items-center justify-center rounded-xl bg-[#03C75A] text-base font-bold text-white transition-opacity hover:opacity-90 shadow-sm">
                <span class="mr-2 font-extrabold italic font-sans text-xl tracking-tighter">N</span>
                Pay 구매하기
              </button>
            </div>
          </div>
        </div>
      </section>
      <!-- Detailed Information Tabs -->
      <section class="border-t border-gray-200 mt-12 bg-white">
        <div class="sticky top-[130px] z-40 bg-white/95 backdrop-blur-md border-b border-gray-200">
          <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <nav
              class="-mb-px flex gap-8 whitespace-nowrap overflow-x-auto scrollbar-hide text-sm sm:text-base font-bold">
              <button data-tab="details"
                class="tab-btn border-b-2 border-primary py-4 text-primary whitespace-nowrap focus:outline-none">
                상품 상세정보
              </button>
              <button data-tab="reviews"
                class="tab-btn border-b-2 border-transparent py-4 text-text-muted hover:border-gray-300 hover:text-text-main whitespace-nowrap transition-colors focus:outline-none">
                고객 리뷰
                <span class="ml-1 text-xs px-1.5 py-0.5 rounded-full bg-gray-100 font-normal">143</span>
              </button>
              <button data-tab="qna"
                class="tab-btn border-b-2 border-transparent py-4 text-text-muted hover:border-gray-300 hover:text-text-main whitespace-nowrap transition-colors focus:outline-none">
                Q & A
              </button>
              <button data-tab="shipping"
                class="tab-btn border-b-2 border-transparent py-4 text-text-muted hover:border-gray-300 hover:text-text-main whitespace-nowrap transition-colors focus:outline-none">
                배송/반품/교환
              </button>
            </nav>
          </div>
        </div>

        <!-- Tab Content Area -->
        <div class="tab-content mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-16 scroll-mt-[180px]" id="details">
          <!-- Mock Detail Content -->
          <div class="text-center prose prose-gray max-w-none mx-auto">
            <h2 class="text-3xl font-extrabold text-text-main mb-6 tracking-tight">
              WEEKEND WARRIOR SETUP
            </h2>
            <p class="text-lg text-text-muted mb-12 break-keep leading-relaxed">
              부드러운 터치감에 강력한 신축성을 더했습니다. 운동 중에도, 휴식
              중에도 완벽한 핏을 경험하세요.
            </p>

            <figure class="mb-12 cursor-zoom-in">
              <img
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuCNV_-qqJpNJSfil1ngKQvNb1r7daZ2HnrJzBpcCE2WIus_n2dKQZYnXeDVlg5zsL-OVD5lO5iiEcI-xZS6PqRzKhzM0ISso0BRU2foFo_u6Z7eaIgNQDhkf_Krs5H2aEfXHaH0pK-gD5VYyplHz8tYNboPK_WqvgcFN_58Tec2EZawv2YOlmsRnn2MzMIdFjcI7GtaEAhX6ae0KX0HiHmxAmPPI6fdp7r7FIpGhPlZZsEKQj6q__bKZrPTpUnYw2DCW6rg51UIFFo"
                alt="Lifestyle shot"
                class="w-full rounded-2xl shadow-sm object-cover aspect-video hover:scale-[1.02] transition-transform duration-500" />
            </figure>

            <div class="grid sm:grid-cols-2 gap-8 text-left mb-16">
              <div
                class="bg-background-alt p-8 rounded-2xl border border-gray-100 shadow-sm transition-transform hover:-translate-y-1">
                <span class="material-symbols-outlined text-4xl text-primary mb-4 block">tsunami</span>
                <h3 class="text-xl font-bold mb-2 text-text-main">
                  땀 흡수와 건조
                </h3>
                <p class="text-text-muted leading-relaxed">
                  독자적인 에어로 드라이 기술로 격렬한 운동 후에도 항상 쾌적한
                  상태를 유지합니다.
                </p>
              </div>
              <div
                class="bg-background-alt p-8 rounded-2xl border border-gray-100 shadow-sm transition-transform hover:-translate-y-1">
                <span class="material-symbols-outlined text-4xl text-primary mb-4 block">line_weight</span>
                <h3 class="text-xl font-bold mb-2 text-text-main">
                  4-Way 스트레치
                </h3>
                <p class="text-text-muted leading-relaxed">
                  어떤 움직임에도 제약 없는 완벽한 신축성을 자랑하는 하이테크
                  원사 사용.
                </p>
              </div>
            </div>

            <!-- Product Specs Table -->
            <h3 class="text-xl font-bold text-left mb-4 text-text-main border-b border-gray-200 pb-3">
              제품 상세 스펙
            </h3>
            <div class="overflow-x-auto">
              <table class="w-full text-left text-sm text-text-main sm:text-base border-collapse">
                <tbody class="divide-y divide-gray-200 border-b border-gray-200">
                  <tr class="hover:bg-gray-50 transition-colors">
                    <th class="py-4 font-bold w-1/3 sm:w-1/4 bg-gray-50 px-4 whitespace-nowrap">
                      소재
                    </th>
                    <td class="py-4 px-4 text-text-muted leading-relaxed">
                      Nylon 78%, Elastane 22%
                    </td>
                  </tr>
                  <tr class="hover:bg-gray-50 transition-colors">
                    <th class="py-4 font-bold w-1/3 sm:w-1/4 bg-gray-50 px-4 whitespace-nowrap">
                      핏
                    </th>
                    <td class="py-4 px-4 text-text-muted leading-relaxed">
                      하이웨이스트 & 타이트 핏 (레깅스) / 컴포트 핏 (탑)
                    </td>
                  </tr>
                  <tr class="hover:bg-gray-50 transition-colors">
                    <th class="py-4 font-bold w-1/3 sm:w-1/4 bg-gray-50 px-4 whitespace-nowrap">
                      세탁 방법
                    </th>
                    <td class="py-4 px-4 text-text-muted leading-relaxed">
                      찬물 단독 손세탁 권장 / 건조기 사용 금지
                    </td>
                  </tr>
                  <tr class="hover:bg-gray-50 transition-colors">
                    <th class="py-4 font-bold w-1/3 sm:w-1/4 bg-gray-50 px-4 whitespace-nowrap">
                      제조원/원산지
                    </th>
                    <td class="py-4 px-4 text-text-muted leading-relaxed">
                      (주)액티브우먼 / 대한민국
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Reviews Content -->
        <div class="tab-content hidden mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-16 scroll-mt-[180px]" id="reviews">
          <h2 class="text-2xl font-bold text-text-main mb-8">고객 리뷰</h2>

          <!-- [상태 1] 리뷰 데이터가 있는 경우 -->
          <div id="reviewsHasData">
            <div
              class="flex flex-col md:flex-row gap-8 mb-12 items-center md:items-start border-b border-gray-100 pb-12">
              <div class="text-center md:text-left flex-shrink-0">
                <span class="text-5xl font-extrabold text-text-main">4.8</span>
                <div class="flex mt-2 mb-1 justify-center md:justify-start">
                  <span class="material-symbols-outlined text-yellow-500">star</span>
                  <span class="material-symbols-outlined text-yellow-500">star</span>
                  <span class="material-symbols-outlined text-yellow-500">star</span>
                  <span class="material-symbols-outlined text-yellow-500">star</span>
                  <span class="material-symbols-outlined text-yellow-500">star_half</span>
                </div>
                <p class="text-sm font-bold text-text-muted">143개의 리뷰</p>
              </div>
              <div class="flex-grow w-full max-w-md space-y-2">
                <div class="flex items-center text-sm">
                  <span class="w-12 text-text-muted font-bold">5점</span>
                  <div class="flex-grow bg-gray-100 h-2 mx-3 rounded-full overflow-hidden">
                    <div class="bg-primary h-full w-[85%] rounded-full"></div>
                  </div>
                  <span class="w-8 text-right text-text-muted">121</span>
                </div>
                <div class="flex items-center text-sm">
                  <span class="w-12 text-text-muted font-bold">4점</span>
                  <div class="flex-grow bg-gray-100 h-2 mx-3 rounded-full overflow-hidden">
                    <div class="bg-primary h-full w-[10%] rounded-full"></div>
                  </div>
                  <span class="w-8 text-right text-text-muted">15</span>
                </div>
              </div>
            </div>
            <div class="space-y-8">
              <div class="border-b border-gray-100 pb-8">
                <div class="flex justify-between items-start mb-4">
                  <div>
                    <div class="flex mb-1">
                      <span class="material-symbols-outlined text-yellow-500 text-sm">star</span>
                      <span class="material-symbols-outlined text-yellow-500 text-sm">star</span>
                      <span class="material-symbols-outlined text-yellow-500 text-sm">star</span>
                      <span class="material-symbols-outlined text-yellow-500 text-sm">star</span>
                      <span class="material-symbols-outlined text-yellow-500 text-sm">star</span>
                    </div>
                    <span class="font-bold text-text-main mr-2">김*민</span>
                    <span class="text-xs text-text-muted">2023.10.15</span>
                  </div>
                </div>
                <p class="text-text-main leading-relaxed">
                  운동할 때 정말 편하고 땀 흡수도 잘 됩니다. 핏도 예뻐서
                  일상복으로도 자주 입게 되네요. 오트밀 컬러 화사하고 예뻐요!
                </p>
              </div>
            </div>
            <div class="mt-8 text-center">
              <button
                class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-bold text-text-main hover:bg-gray-50 transition-colors">
                리뷰 더 보기
              </button>
            </div>
          </div>

          <!-- 상태 구분선 -->
          <div class="my-12 border-t-2 border-dashed border-gray-300 relative">
            <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-white px-4 text-xs font-bold text-gray-400">⬇ 데이터
              없는 경우 (Empty State) ⬇</span>
          </div>

          <!-- [상태 2] 리뷰 데이터가 없는 경우 -->
          <div id="reviewsEmpty">
            <div class="flex flex-col items-center justify-center py-20 text-center">
              <div class="flex items-center justify-center w-24 h-24 rounded-full bg-gray-100 mb-6">
                <span class="material-symbols-outlined text-5xl text-gray-300">rate_review</span>
              </div>
              <h3 class="text-xl font-bold text-text-main mb-2">아직 작성된 리뷰가 없습니다</h3>
              <p class="text-sm text-text-muted mb-8 max-w-sm leading-relaxed">
                이 상품을 구매하신 후 첫 번째 리뷰를 남겨주세요!<br>
                소중한 후기는 다른 고객님들에게 큰 도움이 됩니다.
              </p>
              <a href="review-write.html"
                class="inline-flex items-center px-6 py-3 bg-primary text-white text-sm font-bold rounded-xl hover:bg-red-600 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-sm align-middle mr-1">edit</span>
                리뷰 작성하기
              </a>
            </div>
          </div>
        </div>

        <!-- Q&A Content -->
        <div class="tab-content hidden mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-16 scroll-mt-[180px]" id="qna">
          <div class="flex justify-between items-end mb-8 border-b border-gray-200 pb-4">
            <h2 class="text-2xl font-bold text-text-main">Q & A</h2>
            <a href="qna-write.html"
              class="px-4 py-2 bg-text-main text-white text-sm font-bold rounded hover:bg-primary transition-colors">
              문의 작성하기
            </a>
          </div>

          <!-- [상태 1] Q&A 데이터가 있는 경우 -->
          <div id="qnaHasData">
            <div class="space-y-4">
              <details class="group bg-gray-50 rounded-lg">
                <summary
                  class="flex justify-between items-center font-bold cursor-pointer list-none p-5 text-text-main">
                  <span>사이즈 문의드려요. 키 165cm에 52kg인데 M 사이즈
                    맞을까요?</span>
                  <span class="transition group-open:rotate-180 material-symbols-outlined">expand_more</span>
                </summary>
                <div class="text-text-muted p-5 pt-0 border-t border-gray-200 mt-2 bg-white rounded-b-lg">
                  <p class="font-bold mb-2">
                    A. 안녕하세요, Active Women입니다.
                  </p>
                  <p>
                    고객님의 체형에는 M 사이즈가 예쁘게 맞으실 것으로 보입니다.
                    체형에 따라 핏은 다를 수 있으니 상세 사이즈표를 참고
                    부탁드립니다. 감사합니다.
                  </p>
                </div>
              </details>
            </div>
          </div>

          <!-- 상태 구분선 -->
          <div class="my-12 border-t-2 border-dashed border-gray-300 relative">
            <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-white px-4 text-xs font-bold text-gray-400">⬇ 데이터
              없는 경우 (Empty State) ⬇</span>
          </div>

          <!-- [상태 2] Q&A 데이터가 없는 경우 -->
          <div id="qnaEmpty">
            <div class="flex flex-col items-center justify-center py-20 text-center">
              <div class="flex items-center justify-center w-24 h-24 rounded-full bg-gray-100 mb-6">
                <span class="material-symbols-outlined text-5xl text-gray-300">forum</span>
              </div>
              <h3 class="text-xl font-bold text-text-main mb-2">등록된 문의가 없습니다</h3>
              <p class="text-sm text-text-muted mb-8 max-w-sm leading-relaxed">
                이 상품에 대해 궁금한 점이 있으시면<br>
                문의를 남겨주세요. 빠르게 답변드리겠습니다.
              </p>
              <a href="qna-write.html"
                class="inline-flex items-center px-6 py-3 bg-text-main text-white text-sm font-bold rounded-xl hover:bg-primary transition-colors shadow-sm">
                <span class="material-symbols-outlined text-sm align-middle mr-1">edit_note</span>
                문의 작성하기
              </a>
            </div>
          </div>
        </div>

        <!-- Shipping Content -->
        <div class="tab-content hidden mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-16 scroll-mt-[180px]" id="shipping">
          <h2 class="text-2xl font-bold text-text-main mb-8 border-b border-gray-200 pb-4">
            배송/반품/교환 안내
          </h2>
          <div class="space-y-8 text-sm text-text-main leading-relaxed">
            <div>
              <h3 class="font-bold text-lg mb-3">배송 안내</h3>
              <ul class="list-disc pl-5 space-y-1 text-text-muted">
                <li>
                  배송비: 기본 배송비 3,000원 (50,000원 이상 구매 시 무료배송)
                </li>
                <li>
                  출고일: 평일 오후 2시 이전 결제 완료 건에 한해 당일 출고
                </li>
                <li>
                  배송기간: 출고 후 1~3 영업일 이내 수령 가능 (제주/도서산간
                  지역 추가 소요)
                </li>
              </ul>
            </div>
            <div>
              <h3 class="font-bold text-lg mb-3">교환/반품 안내</h3>
              <ul class="list-disc pl-5 space-y-1 text-text-muted">
                <li>
                  단순 변심에 의한 교환/반품은 상품 수령 후 7일 이내 가능
                  (배송비 고객 부담)
                </li>
                <li>
                  상품 불량 및 오배송의 경우 수령 후 30일 이내 교환/반품 가능
                  (배송비 당사 부담)
                </li>
                <li>
                  교환/반품 불가 사유: 상품 택(Tag) 제거, 오염/사용 흔적 발생,
                  포장 훼손 시
                </li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- Recommended Related Products -->
      <section class="bg-background-alt py-16 border-t border-gray-200">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div class="mb-10 flex items-center justify-between border-b border-gray-200 pb-4">
            <div>
              <h3 class="text-2xl font-bold tracking-tight text-text-main sm:text-3xl">
                함께 스타일링하기 좋은 아이템
              </h3>
              <p class="mt-2 text-sm text-text-muted">
                이 상품과 함께 구매된 상품들입니다.
              </p>
            </div>
            <a class="group hidden sm:flex items-center text-sm font-bold text-text-muted transition-colors hover:text-primary"
              href="#">
              전체보기
              <span
                class="material-symbols-outlined ml-1 text-base transition-transform group-hover:translate-x-1">arrow_forward</span>
            </a>
          </div>

          <div class="grid gap-x-6 gap-y-10 grid-cols-2 lg:grid-cols-4">
            <!-- Related Product 1 -->
            <div class="group relative flex flex-col">
              <div class="relative aspect-[3/4] w-full overflow-hidden rounded-lg bg-gray-200 shadow-sm">
                <div class="h-full w-full bg-cover bg-center transition-transform duration-700 group-hover:scale-105"
                  data-alt="White sneakers on concrete" style="
                      background-image: url(&quot;https://lh3.googleusercontent.com/aida-public/AB6AXuBidFzAz4iG1s-WKgcTwynKVQ_ZR-4gqctASr63hXIbNQ3HYVQJYS1EQdeK23UeapGCdpwohFzArAyvymPwarilN_Fqtm6o_FZgLUSfWxwsHUywpjpmDYOaMMgkRf6P8UjehaSYj-MUuPBEwKfFoYGrmWzI-HiK8OFqdANtgOmkytBWZWI5DJn8kHzUd1KuA7nJdCL7g-RE5b40xzTOhrpVNS6Hdjmaod7h2P8rE7vpepor6DRTbXQCKLR8Oqpx2C16ogFNQMXuEIQ&quot;);
                    "></div>
                <div
                  class="absolute right-3 top-3 rounded-full bg-white/80 p-2 backdrop-blur-sm transition-colors hover:bg-primary hover:text-white cursor-pointer z-10 shadow-sm">
                  <span class="material-symbols-outlined block text-lg">favorite</span>
                </div>
              </div>
              <div class="mt-4 flex flex-1 flex-col">
                <h4 class="text-base font-bold text-text-main group-hover:text-primary transition-colors">
                  클라우드 러너 프로
                </h4>
                <p class="text-sm font-bold text-text-muted mb-1 mt-1">
                  ₩159,000
                </p>
              </div>
            </div>

            <!-- Related Product 2 -->
            <div class="group relative flex flex-col">
              <div class="relative aspect-[3/4] w-full overflow-hidden rounded-lg bg-gray-200 shadow-sm">
                <div class="h-full w-full bg-cover bg-center transition-transform duration-700 group-hover:scale-105"
                  data-alt="Sports Watch" style="
                      background-image: url(&quot;https://lh3.googleusercontent.com/aida-public/AB6AXuAsb-HQWEoNL8U4L728bwSh5o7A54Ni9rDVC8BMJdv_GNRcVxnKmJGGYR6JIc3PxXjO2busIWRNZ8ipOhfcmKc-5XAsMSPC8nIZfeVIGtILcKq1_GBJWw8yCa4C7_5TN5OB0Ci5j5AfoKIUSjitwW5QN3G9Lh-dpS2gGFPl3Y0kJXSGPEhkJJFGuXQnyEYU-rncMhE1dt4A-BWg4mONN4C5at3TDP2DvVjqx4F5nGzTn_3_xnEXTAMLg6jcE7L4eWvoxYiy14IJpI8&quot;);
                    "></div>
                <div
                  class="absolute right-3 top-3 rounded-full bg-white/80 p-2 backdrop-blur-sm transition-colors hover:bg-primary hover:text-white cursor-pointer z-10 shadow-sm">
                  <span class="material-symbols-outlined block text-lg">favorite</span>
                </div>
              </div>
              <div class="mt-4 flex flex-1 flex-col">
                <h4 class="text-base font-bold text-text-main group-hover:text-primary transition-colors">
                  스마트 밴드 V3
                </h4>
                <p class="text-sm font-bold text-text-muted mb-1 mt-1">
                  ₩219,000
                </p>
              </div>
            </div>

            <!-- Related Product 3 -->
            <div class="group relative flex flex-col hidden sm:flex">
              <div class="relative aspect-[3/4] w-full overflow-hidden rounded-lg bg-gray-200 shadow-sm">
                <div class="h-full w-full bg-cover bg-center transition-transform duration-700 group-hover:scale-105"
                  data-alt="Yoga mat" style="
                      background-image: url(&quot;https://lh3.googleusercontent.com/aida-public/AB6AXuDcCwiVYOZ2XgfbLcwNfv1CndtwSuCXp93p0COmlecQ1tiFvX1yHwnncV-Mk_jv50p4Xxpiiflipp5Eir5XRuvNHNisCCLSdPLL_Woyw8J9hJMROPJ1pFlO8i7_kvDB-t6Zg-TEaXfbOb2TCLJGkpDcSw5raqesusBSrsrN4BTI_-aA0Omr6D5iv310qKxBat9vEcf4kdLytn3w0R-cPV9qo2AahQmCb6qkGTUyW78SKki6dYgAhWn-k3DpIFl8lj3b_kmSpn4re1w&quot;);
                    "></div>
                <div
                  class="absolute right-3 top-3 rounded-full bg-white/80 p-2 backdrop-blur-sm transition-colors hover:bg-primary hover:text-white cursor-pointer z-10 shadow-sm">
                  <span class="material-symbols-outlined block text-lg">favorite</span>
                </div>
              </div>
              <div class="mt-4 flex flex-1 flex-col">
                <h4 class="text-base font-bold text-text-main group-hover:text-primary transition-colors">
                  에코 그립 요가 매트
                </h4>
                <p class="text-sm font-bold text-text-muted mb-1 mt-1">
                  ₩98,000
                </p>
              </div>
            </div>

            <!-- Related Product 4 -->
            <div class="group relative flex flex-col hidden sm:flex">
              <div class="relative aspect-[3/4] w-full overflow-hidden rounded-lg bg-gray-200 shadow-sm">
                <div class="h-full w-full bg-cover bg-center transition-transform duration-700 group-hover:scale-105"
                  data-alt="Skincare" style="
                      background-image: url(&quot;https://lh3.googleusercontent.com/aida-public/AB6AXuA_UoC5Rz9hIJrMW0TSR2dOYjaQNExC7fNGm3SJhmkIfuEz8uxDZSaNjFE5y9IpITmRWNEU_3hPtYjUTy22KvpOAyir30Njwy373-xEUNIXwd4XBOvZK2SIN-yZ2EhEPlOaR7SVkcL-MdV38V3dtjrY1FsvopAHWPLh99b3NHUObiFLuK6qEP3Xg_6MNXoGb2k_lotxdk4XNx-P8Kro1NY4rJKVh1KAakayDB84SptuCzHkVxvTFhQm47-lmbSxb8Sf5Xy6Ja8eTUM&quot;);
                    "></div>
                <div
                  class="absolute right-3 top-3 rounded-full bg-white/80 p-2 backdrop-blur-sm transition-colors hover:bg-primary hover:text-white cursor-pointer z-10 shadow-sm">
                  <span class="material-symbols-outlined block text-lg">favorite</span>
                </div>
              </div>
              <div class="mt-4 flex flex-1 flex-col">
                <h4 class="text-base font-bold text-text-main group-hover:text-primary transition-colors">
                  하이드레이션 부스트 미스트
                </h4>
                <p class="text-sm font-bold text-text-muted mb-1 mt-1">
                  ₩36,000
                </p>
              </div>
            </div>
          </div>
          <div class="mt-8 flex justify-center sm:hidden">
            <button
              class="w-full max-w-sm rounded-lg border border-gray-300 py-3 text-sm font-bold text-text-main hover:bg-gray-50 transition-colors">
              더 보기
            </button>
          </div>
        </div>
      </section>
    </main>
@endsection

@push('scripts')

@endpush
