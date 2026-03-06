@extends('layouts.app')

@section('title', '장바구니 - Active Women\'s Premium Store')

@section('content')
    <main class="flex-1 bg-background-alt pb-20">
      <!-- Page Title -->
      <div class="pt-12 pb-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <h2 class="text-3xl font-extrabold text-text-main tracking-tight">
            장바구니 <span class="text-primary ml-1">3</span>
          </h2>
        </div>
      </div>

      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
          <!-- 장바구니 리스트 영역 -->
          <div class="flex-grow">
            <!-- Empty Cart State -->
            <div id="emptyCartState"
              class="hidden bg-white rounded-2xl shadow-sm border border-gray-100 p-16 flex-col items-center justify-center text-center">
              <span class="material-symbols-outlined text-6xl text-gray-200 mb-4 block">shopping_cart</span>
              <h3 class="text-xl font-bold text-text-main mb-2">장바구니에 담긴 상품이 없습니다.</h3>
              <p class="text-sm text-text-muted mb-8">액티브 우먼의 다양한 상품을 만나보세요!</p>
              <a href="/product-list"
                class="inline-flex items-center justify-center px-8 py-3 bg-primary text-white font-bold rounded-xl hover:bg-red-600 transition-colors shadow-lg hover:shadow-primary/30">
                쇼핑 계속하기
              </a>
            </div>

            <div id="cartContentContainer" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
              <!-- 전체선택 & 선택삭제 툴바 -->
              <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-6">
                <label class="flex items-center gap-3 cursor-pointer group">
                  <input type="checkbox" id="selectAll"
                    class="rounded border-gray-300 text-primary w-5 h-5 focus:ring-primary focus:ring-offset-0"
                    checked />
                  <span id="selectAllText"
                    class="font-bold text-text-main group-hover:text-primary transition-colors">전체 선택 (3/3)</span>
                </label>
                <button id="deleteSelectedBtn"
                  class="text-sm text-text-muted hover:text-primary font-bold transition-colors">
                  선택 삭제
                </button>
              </div>

              <div id="cartItemList" class="space-y-8">
                <!-- Cart Item 1 -->
                <div class="cart-item flex flex-col sm:flex-row gap-4 sm:gap-6 items-start border-b border-gray-50 pb-8"
                  data-id="1" data-original-price="165000" data-price="148500" data-qty="1">
                  <div class="flex items-center pt-1">
                    <input type="checkbox"
                      class="item-checkbox rounded border-gray-300 text-primary w-5 h-5 focus:ring-primary" checked />
                  </div>
                  <div class="flex gap-4 sm:gap-6 w-full">
                    <a href="/product-detail"
                      class="w-24 h-32 sm:w-32 sm:h-40 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100 block group relative">
                      <div
                        class="w-full h-full bg-cover bg-center transition-transform duration-500 group-hover:scale-105"
                        style="
                            background-image: url(&quot;https://lh3.googleusercontent.com/aida-public/AB6AXuDabAzzyO-1GPR9w9KUZy2t-akxd-pTry06ye-7EmfLIj4HsSh3Qsl3-4SPzOjkEiIvcTGGDyNJEuMpEpxJVMBh2D4z-w70xv-_n1ulP9ym_oYqWuFOnJqPl25Vm9FlyjgGTi65HS6qSzlRQgslgoXVASr5mvobAhP-rUuwV34o5MwDa2O-Tj9-CB71iqI7UuUDKfOzXILS0hUApxV--IBEjQ9t7EFGHTyyK8Vxetjz5EeEdY7nQBJbqJ9qIk1KAJSZqHHXH55EY1c&quot;);
                          "></div>
                    </a>
                    <div class="flex-grow flex flex-col justify-between">
                      <div>
                        <div class="flex justify-between items-start gap-4">
                          <a href="/product-detail"
                            class="font-bold text-lg text-text-main hover:text-primary transition-colors pr-4">위켄드 워리어
                            셋업</a>
                          <button class="btn-remove text-gray-400 hover:text-text-main p-1 -mr-2 -mt-2">
                            <span class="material-symbols-outlined text-xl">close</span>
                          </button>
                        </div>
                        <div class="mt-2 p-3 bg-background-alt rounded-lg flex justify-between items-center text-sm">
                          <span class="text-text-muted font-medium">단품: Black / M</span>
                          <button class="btn-option text-xs font-bold text-primary hover:underline"
                            data-product="위켄드 워리어 셋업">
                            옵션 변경
                          </button>
                        </div>
                      </div>
                      <div class="flex justify-between items-end mt-4">
                        <div class="flex items-center border border-gray-200 rounded-lg bg-white overflow-hidden h-9">
                          <button
                            class="qty-minus w-9 h-full flex items-center justify-center hover:bg-gray-50 text-text-main font-bold focus:outline-none transition-colors">
                            <span class="material-symbols-outlined text-sm">remove</span>
                          </button>
                          <span
                            class="qty-display w-10 h-full flex items-center justify-center border-x border-gray-200 text-sm font-bold bg-gray-50/50">1</span>
                          <button
                            class="qty-plus w-9 h-full flex items-center justify-center hover:bg-gray-50 text-text-main font-bold focus:outline-none transition-colors">
                            <span class="material-symbols-outlined text-sm">add</span>
                          </button>
                        </div>
                        <div class="text-right">
                          <div class="item-original-price-display text-xs text-gray-400 line-through mb-0.5">
                            ₩165,000
                          </div>
                          <div class="item-price-display text-xl font-bold text-text-main">
                            ₩148,500
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Cart Item 2 -->
                <div class="cart-item flex flex-col sm:flex-row gap-4 sm:gap-6 items-start border-b border-gray-50 pb-8"
                  data-id="2" data-original-price="98000" data-price="98000" data-qty="1">
                  <div class="flex items-center pt-1">
                    <input type="checkbox"
                      class="item-checkbox rounded border-gray-300 text-primary w-5 h-5 focus:ring-primary" checked />
                  </div>
                  <div class="flex gap-4 sm:gap-6 w-full">
                    <a href="/product-detail"
                      class="w-24 h-32 sm:w-32 sm:h-40 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100 block group relative">
                      <div
                        class="w-full h-full bg-cover bg-center transition-transform duration-500 group-hover:scale-105"
                        style="
                            background-image: url(&quot;https://lh3.googleusercontent.com/aida-public/AB6AXuDG_KhKcmbL_tfD6o7Wodrv4S7ibX6ujwxndVGPgfKaIBYNTgJemxUf40EOkeIaRYUIpj4Ev6Ms7KW87nUBN0O2-_HjjU3XY6CIl9GHOnTsDi0DbDZZXY2loY2aWcN96JZOjSQ2uEKSZvAunpehA8VyNinHagNltBDPAkBIhe0i-DI1Zq8OGpc5uor1d9BwAoXZR_5E1hfFRSN4YmsMjijZ7Gly2NVRLkbKyBUnn8i98Zhc2BjTQt_-XWoDpQLGYPkEI9CwI5vRdtY&quot;);
                          "></div>
                    </a>
                    <div class="flex-grow flex flex-col justify-between">
                      <div>
                        <div class="flex justify-between items-start gap-4">
                          <a href="/product-detail"
                            class="font-bold text-lg text-text-main hover:text-primary transition-colors pr-4">모닝 런 글로우
                            자켓</a>
                          <button class="btn-remove text-gray-400 hover:text-text-main p-1 -mr-2 -mt-2">
                            <span class="material-symbols-outlined text-xl">close</span>
                          </button>
                        </div>
                        <div class="mt-2 p-3 bg-background-alt rounded-lg flex justify-between items-center text-sm">
                          <span class="text-text-muted font-medium">단품: White / S</span>
                          <button class="btn-option text-xs font-bold text-primary hover:underline"
                            data-product="모닝 런 글로우 자켓">
                            옵션 변경
                          </button>
                        </div>
                      </div>
                      <div class="flex justify-between items-end mt-4">
                        <div class="flex items-center border border-gray-200 rounded-lg bg-white overflow-hidden h-9">
                          <button
                            class="qty-minus w-9 h-full flex items-center justify-center hover:bg-gray-50 text-text-main font-bold focus:outline-none transition-colors">
                            <span class="material-symbols-outlined text-sm">remove</span>
                          </button>
                          <span
                            class="qty-display w-10 h-full flex items-center justify-center border-x border-gray-200 text-sm font-bold bg-gray-50/50">1</span>
                          <button
                            class="qty-plus w-9 h-full flex items-center justify-center hover:bg-gray-50 text-text-main font-bold focus:outline-none transition-colors">
                            <span class="material-symbols-outlined text-sm">add</span>
                          </button>
                        </div>
                        <div class="text-right">
                          <div class="item-original-price-display hidden text-xs text-gray-400 line-through mb-0.5">
                          </div>
                          <div class="item-price-display text-xl font-bold text-text-main">
                            ₩98,000
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Cart Item 3 -->
                <div class="cart-item flex flex-col sm:flex-row gap-4 sm:gap-6 items-start" data-id="3"
                  data-original-price="98000" data-price="98000" data-qty="1">
                  <div class="flex items-center pt-1">
                    <input type="checkbox"
                      class="item-checkbox rounded border-gray-300 text-primary w-5 h-5 focus:ring-primary" checked />
                  </div>
                  <div class="flex gap-4 sm:gap-6 w-full">
                    <a href="/product-detail"
                      class="w-24 h-32 sm:w-32 sm:h-40 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100 block group relative">
                      <div
                        class="w-full h-full bg-cover bg-center transition-transform duration-500 group-hover:scale-105"
                        style="
                            background-image: url(&quot;https://lh3.googleusercontent.com/aida-public/AB6AXuDcCwiVYOZ2XgfbLcwNfv1CndtwSuCXp93p0COmlecQ1tiFvX1yHwnncV-Mk_jv50p4Xxpiiflipp5Eir5XRuvNHNisCCLSdPLL_Woyw8J9hJMROPJ1pFlO8i7_kvDB-t6Zg-TEaXfbOb2TCLJGkpDcSw5raqesusBSrsrN4BTI_-aA0Omr6D5iv310qKxBat9vEcf4kdLytn3w0R-cPV9qo2AahQmCb6qkGTUyW78SKki6dYgAhWn-k3DpIFl8lj3b_kmSpn4re1w&quot;);
                          "></div>
                    </a>
                    <div class="flex-grow flex flex-col justify-between">
                      <div>
                        <div class="flex justify-between items-start gap-4">
                          <a href="/product-detail"
                            class="font-bold text-lg text-text-main hover:text-primary transition-colors pr-4">에코 그립 요가
                            매트</a>
                          <button class="btn-remove text-gray-400 hover:text-text-main p-1 -mr-2 -mt-2">
                            <span class="material-symbols-outlined text-xl">close</span>
                          </button>
                        </div>
                        <div class="mt-2 p-3 bg-background-alt rounded-lg flex justify-between items-center text-sm">
                          <span class="text-text-muted font-medium">단품: Pink</span>
                          <button class="btn-option text-xs font-bold text-primary hover:underline"
                            data-product="에코 그립 요가 매트">
                            옵션 변경
                          </button>
                        </div>
                      </div>
                      <div class="flex justify-between items-end mt-4">
                        <div class="flex items-center border border-gray-200 rounded-lg bg-white overflow-hidden h-9">
                          <button
                            class="qty-minus w-9 h-full flex items-center justify-center hover:bg-gray-50 text-text-main font-bold focus:outline-none transition-colors">
                            <span class="material-symbols-outlined text-sm">remove</span>
                          </button>
                          <span
                            class="qty-display w-10 h-full flex items-center justify-center border-x border-gray-200 text-sm font-bold bg-gray-50/50">1</span>
                          <button
                            class="qty-plus w-9 h-full flex items-center justify-center hover:bg-gray-50 text-text-main font-bold focus:outline-none transition-colors">
                            <span class="material-symbols-outlined text-sm">add</span>
                          </button>
                        </div>
                        <div class="text-right">
                          <div class="item-original-price-display hidden text-xs text-gray-400 line-through mb-0.5">
                          </div>
                          <div class="item-price-display text-xl font-bold text-text-main">
                            ₩98,000
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- 옵션 선택 상품 더 담기 유도 -->
              <div
                class="mt-10 py-6 px-4 bg-primary/5 rounded-xl border border-primary/10 flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <span class="material-symbols-outlined text-primary text-2xl">local_shipping</span>
                  <div>
                    <h4 class="font-bold text-text-main text-sm">
                      배송비 걱정 없어요!
                    </h4>
                    <p class="text-xs text-text-muted mt-0.5">
                      현재 장바구니 결제 금액 50,000원 이상 무조건
                      <strong>무료배송</strong>
                    </p>
                  </div>
                </div>
                <a href="/product-list" class="text-sm font-bold text-primary hover:text-red-700 hover:underline">상품
                  더 담기</a>
              </div>
            </div>
          </div>

          <!-- 주문 결제 금액 영역 -->
          <div class="w-full lg:w-[400px] flex-shrink-0">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 sticky top-[130px]">
              <h3 id="summaryItemCount" class="font-bold text-xl text-text-main border-b border-gray-200 pb-4 mb-6">
                주문 요약 (총 3건)
              </h3>

              <div class="space-y-4 text-base mb-8">
                <div class="flex justify-between text-text-muted font-medium">
                  <span>상품 총 금액</span>
                  <span id="summaryOriginalTotal" class="text-text-main">₩361,000</span>
                </div>
                <div class="flex justify-between text-text-muted font-medium">
                  <span>할인 적용 금액 (번들/회원)</span>
                  <span id="summaryDiscount" class="text-red-500 font-bold">- ₩16,500</span>
                </div>
                <div class="flex justify-between text-text-muted font-medium">
                  <span>배송비</span>
                  <span id="summaryShipping" class="text-text-main font-bold">무료</span>
                </div>
              </div>

              <div class="bg-background-alt p-4 rounded-xl mb-6 flex flex-col text-sm border border-gray-100">
                <div class="flex justify-between items-center font-bold text-text-main mb-2">
                  <span>쿠폰/적립금</span>
                </div>
                <div class="text-xs text-text-muted mt-1">
                  ※ 결제(Checkout) 단계에서 보유 금액과 쿠폰을 <br />적용할 수 있습니다.
                </div>
              </div>

              <div class="border-t-2 border-text-main pt-6 mb-8">
                <div class="flex justify-between items-end">
                  <span class="font-bold text-text-main text-lg">최종 결제 금액</span>
                  <span id="summaryFinalTotal"
                    class="font-extrabold text-3xl text-primary tracking-tight">₩344,500</span>
                </div>
                <p id="summaryPoints" class="text-right text-xs font-bold text-primary/80 mt-2">
                  최대 3,445원 적립 예정
                </p>
              </div>

              <div class="space-y-3">
                <button
                  class="w-full bg-primary text-white font-extrabold text-lg rounded-xl py-4 hover:bg-red-600 transition-colors shadow-lg hover:shadow-primary/30 focus:outline-none focus:ring-4 focus:ring-primary/20 transform hover:-translate-y-0.5 relative overflow-hidden group">
                  <span id="checkoutBtnText" class="relative z-10">결제하기 (3)</span>
                  <div
                    class="absolute inset-0 bg-white/20 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-500 ease-in-out">
                  </div>
                </button>
                <!-- 네이버페이, 카카오페이 등 외부결제 버튼 공간 -->
                <div class="grid grid-cols-2 gap-2 mt-4">
                  <button
                    class="w-full bg-[#fae100] text-[#3c1e1e] font-bold text-sm rounded-lg py-3 hover:bg-[#ebd300] transition-colors relative">
                    <span
                      class="absolute left-4 top-1/2 -translate-y-1/2 rounded-full w-5 h-5 bg-[#3c1e1e] text-[#fae100] text-[10px] flex items-center justify-center font-extrabold">K</span>
                    <span class="pl-4">카카오페이</span>
                  </button>
                  <button
                    class="w-full bg-[#03c75a] text-white font-bold text-sm rounded-lg py-3 hover:bg-[#02b351] transition-colors relative">
                    <span
                      class="absolute left-4 top-1/2 -translate-y-1/2 font-extrabold text-[#03c75a] bg-white rounded-sm w-5 h-5 flex items-center justify-center text-[13px] tracking-tighter">N</span>
                    <span class="pl-4">네이버페이</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
@endsection

@push('scripts')

@endpush
