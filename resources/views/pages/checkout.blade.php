@extends('layouts.app')

@section('title', '주문/결제 - Active Women\'s Premium Store')

@section('content')
    <main class="flex-1 pb-20">
      <!-- Page Title -->
      <div class="pt-12 pb-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <h2 class="text-3xl font-extrabold text-text-main tracking-tight">
            주문/결제
          </h2>
        </div>
      </div>

      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
          <!-- 입력 폼 영역 (좌측) -->
          <div class="flex-grow space-y-6">
            <!-- 주문자 정보 -->
            <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
              <h3 class="font-bold text-xl text-text-main mb-6 flex items-center">
                <span class="material-symbols-outlined mr-2 text-primary">person</span>주문자 정보
              </h3>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-bold text-text-main mb-2">이름 <span
                      class="text-primary">*</span></label>
                  <input type="text" id="ordererName" value="신백민"
                    class="w-full rounded-lg border border-gray-200 px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors"
                    placeholder="주문자 이름" />
                </div>
                <div>
                  <label class="block text-sm font-bold text-text-main mb-2">휴대폰 번호 <span
                      class="text-primary">*</span></label>
                  <input type="tel" id="ordererPhone" value="010-1234-5678"
                    class="w-full rounded-lg border border-gray-200 px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors"
                    placeholder="010-0000-0000" />
                </div>
                <div class="sm:col-span-2">
                  <label class="block text-sm font-bold text-text-main mb-2">이메일 주소 <span
                      class="text-primary">*</span></label>
                  <input type="email" id="ordererEmail" value="user@example.com"
                    class="w-full rounded-lg border border-gray-200 px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors"
                    placeholder="이메일 주소" />
                </div>
              </div>
            </section>

            <!-- 배송 정보 -->
            <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
              <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-xl text-text-main flex items-center">
                  <span class="material-symbols-outlined mr-2 text-primary">local_shipping</span>배송지 정보
                </h3>
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="checkbox" id="sameAsOrderer"
                    class="rounded border-gray-300 text-primary w-4 h-4 focus:ring-primary" checked />
                  <span class="text-sm font-medium text-text-main">주문자와 동일</span>
                </label>
              </div>
              <div class="grid grid-cols-1 gap-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-bold text-text-main mb-2">받는 사람 <span
                        class="text-primary">*</span></label>
                    <input type="text" id="recipientName" value="신백민"
                      class="w-full rounded-lg border border-gray-200 px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors" />
                  </div>
                  <div>
                    <label class="block text-sm font-bold text-text-main mb-2">휴대폰 번호 <span
                        class="text-primary">*</span></label>
                    <input type="tel" id="recipientPhone" value="010-1234-5678"
                      class="w-full rounded-lg border border-gray-200 px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors" />
                  </div>
                </div>
                <div>
                  <label class="block text-sm font-bold text-text-main mb-2">주소 <span
                      class="text-primary">*</span></label>
                  <div class="flex gap-2 mb-2">
                    <input type="text" id="recipientZipcode" value="06236"
                      class="w-32 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors"
                      readonly />
                    <button type="button"
                      class="px-4 py-3 bg-gray-100 hover:bg-gray-200 font-bold text-sm text-text-main rounded-lg transition-colors border border-gray-200">
                      우편번호 찾기
                    </button>
                  </div>
                  <input type="text" id="recipientAddress" value="서울특별시 강남구 테헤란로 123"
                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm mb-2 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors"
                    readonly />
                  <input type="text" id="recipientDetailAddress" value="액티브타워 10층"
                    class="w-full rounded-lg border border-gray-200 px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors"
                    placeholder="상세 주소를 입력해주세요" />
                </div>
                <div class="mt-2">
                  <label class="block text-sm font-bold text-text-main mb-2">배송 요청사항</label>
                  <select <option value="call">배송 전에 미리 연락주세요.</option>
                    <option value="security">부재 시 경비실에 맡겨주세요.</option>
                    <option value="door">부재 시 문 앞에 놓아주세요.</option>
                    <option value="custom">직접 입력</option>
                  </select>
                  <input type="text" id="customShippingMsg"
                    class="w-full rounded-lg border border-gray-200 px-4 py-3 text-sm mt-2 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors hidden"
                    placeholder="배송 요청사항을 직접 입력해주세요" />
                </div>
              </div>
            </section>

            <!-- 결제 수단 -->
            <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
              <h3 class="font-bold text-xl text-text-main mb-6 flex items-center">
                <span class="material-symbols-outlined mr-2 text-primary">credit_card</span>결제 수단
              </h3>

              <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                <label
                  class="border-2 border-primary bg-primary/5 rounded-xl p-4 text-center cursor-pointer transition-all relative overflow-hidden">
                  <input type="radio" name="payment_method" class="sr-only" checked />
                  <span class="material-symbols-outlined text-primary mb-2 text-3xl">credit_card</span>
                  <div class="font-bold text-primary text-sm">
                    신용/체크카드
                  </div>
                  <div class="absolute top-2 right-2">
                    <span class="material-symbols-outlined text-primary text-sm">check_circle</span>
                  </div>
                </label>
                <label
                  class="border border-gray-200 rounded-xl p-4 text-center cursor-pointer hover:border-primary/50 transition-all opacity-70 hover:opacity-100">
                  <input type="radio" name="payment_method" class="sr-only" />
                  <span class="material-symbols-outlined text-gray-500 mb-2 text-3xl">account_balance</span>
                  <div class="font-bold text-text-main text-sm">
                    무통장입금
                  </div>
                </label>
                <label
                  class="border border-gray-200 rounded-xl p-4 text-center cursor-pointer hover:border-primary/50 transition-all opacity-70 hover:opacity-100">
                  <input type="radio" name="payment_method" class="sr-only" />
                  <div class="flex justify-center mb-2">
                    <span
                      class="rounded-full w-8 h-8 bg-[#3c1e1e] text-[#fae100] text-sm flex items-center justify-center font-extrabold">K</span>
                  </div>
                  <div class="font-bold text-text-main text-sm">
                    카카오페이
                  </div>
                </label>
                <label
                  class="border border-gray-200 rounded-xl p-4 text-center cursor-pointer hover:border-primary/50 transition-all opacity-70 hover:opacity-100">
                  <input type="radio" name="payment_method" class="sr-only" />
                  <div class="flex justify-center mb-2">
                    <span
                      class="font-extrabold text-[#03c75a] border border-[#03c75a] bg-white rounded-sm w-8 h-8 flex items-center justify-center text-lg tracking-tighter">N</span>
                  </div>
                  <div class="font-bold text-text-main text-sm">
                    네이버페이
                  </div>
                </label>
              </div>

              <!-- 카드 결제 세부 폼 -->
              <div id="cardPaymentForm"
                class="bg-background-alt p-5 rounded-xl border border-gray-100 transition-all duration-300">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div class="sm:col-span-2">
                    <select
                      class="w-full rounded-lg border border-gray-200 px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors">
                      <option value="">카드를 선택해주세요</option>
                      <option>KB국민카드</option>
                      <option>신한카드</option>
                      <option>우리카드</option>
                      <option>삼성카드</option>
                      <option>현대카드</option>
                    </select>
                  </div>
                  <div class="sm:col-span-2">
                    <select
                      class="w-full rounded-lg border border-gray-200 px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors">
                      <option>할부선택 (5만원 이상 가능)</option>
                      <option>일시불</option>
                      <option>2개월 무이자</option>
                      <option>3개월 무이자</option>
                    </select>
                  </div>
                </div>
              </div>

              <!-- 무통장입금 추가 폼 -->
              <div id="bankTransferForm"
                class="bg-background-alt p-5 rounded-xl border border-gray-100 transition-all duration-300 hidden opacity-0 text-sm">
                <div class="mb-4">
                  <label class="block font-bold text-text-main mb-2">입금 은행</label>
                  <select
                    class="w-full rounded-lg border border-gray-200 px-4 py-3 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                    <option value="">은행을 선택하세요</option>
                    <option>국민은행 0000-00-00000 (예금주: 액티브우먼)</option>
                    <option>신한은행 111-111-111111 (예금주: 액티브우먼)</option>
                  </select>
                </div>
                <div>
                  <label class="block font-bold text-text-main mb-2">입금자명</label>
                  <input type="text"
                    class="w-full rounded-lg border border-gray-200 px-4 py-3 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                    placeholder="입금자 성함을 입력해주세요" />
                </div>
              </div>

              <!-- 기타 페이 폼(네이버/카카오페이 안내) -->
              <div id="simplePayForm"
                class="bg-background-alt p-5 rounded-xl border border-gray-100 transition-all duration-300 hidden opacity-0 text-sm text-center">
                <p class="text-text-muted">결제하기 버튼을 누르시면 해당 페이 결제창으로 이동합니다.</p>
              </div>
            </section>
          </div>

          <!-- 결제 요약 측면 영역 -->
          <div class="w-full lg:w-[400px] flex-shrink-0">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 sticky top-[130px]">
              <!-- 주문 상품 목록 간략 표시 -->
              <div class="mb-6 border-b border-gray-100 pb-6">
                <h3 class="font-bold text-lg text-text-main mb-4 flex justify-between items-center">
                  주문 상품
                  <span class="text-primary text-sm font-bold">3건</span>
                </h3>
                <div class="space-y-4">
                  <div class="flex gap-3">
                    <img
                      src="https://lh3.googleusercontent.com/aida-public/AB6AXuDabAzzyO-1GPR9w9KUZy2t-akxd-pTry06ye-7EmfLIj4HsSh3Qsl3-4SPzOjkEiIvcTGGDyNJEuMpEpxJVMBh2D4z-w70xv-_n1ulP9ym_oYqWuFOnJqPl25Vm9FlyjgGTi65HS6qSzlRQgslgoXVASr5mvobAhP-rUuwV34o5MwDa2O-Tj9-CB71iqI7UuUDKfOzXILS0hUApxV--IBEjQ9t7EFGHTyyK8Vxetjz5EeEdY7nQBJbqJ9qIk1KAJSZqHHXH55EY1c"
                      class="w-16 h-16 rounded object-cover border border-gray-100 bg-gray-50" alt="" />
                    <div class="flex flex-col justify-center flex-grow">
                      <span class="text-sm font-bold text-text-main truncate">위켄드 워리어 셋업</span>
                      <span class="text-xs text-text-muted">Black / M | 1개</span>
                    </div>
                    <div class="flex flex-col justify-center text-right">
                      <span class="text-sm font-bold text-text-main">₩148,500</span>
                    </div>
                  </div>
                  <div class="flex gap-3">
                    <img
                      src="https://lh3.googleusercontent.com/aida-public/AB6AXuDG_KhKcmbL_tfD6o7Wodrv4S7ibX6ujwxndVGPgfKaIBYNTgJemxUf40EOkeIaRYUIpj4Ev6Ms7KW87nUBN0O2-_HjjU3XY6CIl9GHOnTsDi0DbDZZXY2loY2aWcN96JZOjSQ2uEKSZvAunpehA8VyNinHagNltBDPAkBIhe0i-DI1Zq8OGpc5uor1d9BwAoXZR_5E1hfFRSN4YmsMjijZ7Gly2NVRLkbKyBUnn8i98Zhc2BjTQt_-XWoDpQLGYPkEI9CwI5vRdtY"
                      class="w-16 h-16 rounded object-cover border border-gray-100 bg-gray-50" alt="" />
                    <div class="flex flex-col justify-center flex-grow">
                      <span class="text-sm font-bold text-text-main truncate">모닝 런 글로우 자켓</span>
                      <span class="text-xs text-text-muted">White / S | 1개</span>
                    </div>
                    <div class="flex flex-col justify-center text-right">
                      <span class="text-sm font-bold text-text-main">₩98,000</span>
                    </div>
                  </div>
                  <!-- 에코 그립 요가 매트를 숨김 처리한 영역 -->
                  <div id="hiddenItemsContainer"
                    class="hidden flex gap-3 slide-down mt-4 border-t border-gray-100 pt-4">
                    <img src="https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?auto=format&fit=crop&q=80&w=200"
                      class="w-16 h-16 rounded object-cover border border-gray-100 bg-gray-50" alt="" />
                    <div class="flex flex-col justify-center flex-grow">
                      <span class="text-sm font-bold text-text-main truncate">에코 그립 요가 매트</span>
                      <span class="text-xs text-text-muted">Purple / Free | 1개</span>
                    </div>
                    <div class="flex flex-col justify-center text-right">
                      <span class="text-sm font-bold text-text-main">₩48,000</span>
                    </div>
                  </div>

                  <div class="text-center bg-background-alt rounded-lg py-2 mt-2">
                    <button id="btnShowMoreItems"
                      class="text-xs font-bold text-text-main hover:text-primary transition-colors flex items-center justify-center w-full">
                      <span id="txtShowMore">그 외 1개 상품 더보기</span>
                      <span id="iconShowMore"
                        class="material-symbols-outlined text-[1rem] align-middle ml-1 transition-transform duration-300">expand_more</span>
                    </button>
                  </div>
                </div>
              </div>

              <div class="space-y-4 text-base mb-6">
                <div class="flex justify-between text-text-muted font-medium">
                  <span>상품 총 금액</span>
                  <span class="text-text-main">₩361,000</span>
                </div>
                <div class="flex justify-between text-text-muted font-medium">
                  <span>할인 적용 금액</span>
                  <span class="text-red-500 font-bold">- ₩16,500</span>
                </div>
                <div class="flex justify-between text-text-muted font-medium items-center">
                  <span>쿠폰 할인</span>
                  <div class="flex items-center gap-3">
                    <span class="text-red-500 font-bold hidden" id="couponAppliedText">- ₩<span
                        id="couponDiscountAmt">0</span></span>
                    <button id="btnCouponModal"
                      class="text-xs border border-primary text-primary px-3 py-1.5 rounded bg-white hover:bg-primary/5 font-bold transition-colors">
                      쿠폰 조회
                    </button>
                  </div>
                </div>
                <div class="flex justify-between text-text-muted font-medium items-center">
                  <span>적립금 사용</span>
                  <div class="flex items-center gap-2">
                    <input type="text" id="pointInput"
                      class="w-20 text-right border border-gray-200 rounded px-2 py-1.5 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary text-text-main bg-white transition-all"
                      placeholder="0" value="" />
                    <span class="text-sm text-text-main font-bold">원</span>
                    <button id="btnUseAllPoints"
                      class="text-xs bg-gray-800 text-white px-3 py-1.5 rounded font-bold hover:bg-black transition-colors">
                      전액사용
                    </button>
                  </div>
                </div>
                <div class="text-right text-xs text-text-muted -mt-2">
                  보유 적립금: <strong id="maxPoints">12,500</strong>원
                </div>
                <div class="flex justify-between text-text-muted font-medium">
                  <span>배송비</span>
                  <span class="text-text-main font-bold">무료</span>
                </div>
              </div>

              <div class="border-t-2 border-text-main pt-6 mb-8">
                <div class="flex justify-between items-end">
                  <span class="font-bold text-text-main text-lg">최종 결제 금액</span>
                  <span class="font-extrabold text-3xl text-primary tracking-tight">₩<span
                      id="finalTotalAmt">344,500</span></span>
                </div>
                <p class="text-right text-xs font-bold text-primary/80 mt-2">
                  최대 3,445원 적립 예정
                </p>
              </div>

              <div class="mb-4">
                <label class="flex items-start gap-2 cursor-pointer p-3 bg-gray-50 rounded-lg border border-gray-200">
                  <input type="checkbox" id="agreeTerms"
                    class="mt-0.5 rounded border-gray-300 text-primary focus:ring-primary w-4 h-4" />
                  <span class="text-xs text-text-main font-medium leading-relaxed">상기 결제정보를 확인하였으며, 구매진행에 동의합니다.
                    (필수)</span>
                </label>
              </div>

              <div class="space-y-3">
                <button
                  class="w-full bg-primary text-white font-extrabold text-lg rounded-xl py-4 hover:bg-red-600 transition-colors shadow-lg hover:shadow-primary/30 focus:outline-none focus:ring-4 focus:ring-primary/20 transform hover:-translate-y-0.5 relative overflow-hidden group">
                  <span id="finalSubmitBtnText" class="relative z-10">344,500원 결제하기</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
@endsection

@push('scripts')

@endpush
