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
                  <input type="text" id="ordererName" value="{{ $member->name }}"
                    class="w-full rounded-lg border border-gray-200 px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors"
                    placeholder="주문자 이름" />
                </div>
                <div>
                  <label class="block text-sm font-bold text-text-main mb-2">휴대폰 번호 <span
                      class="text-primary">*</span></label>
                  <input type="tel" id="ordererPhone" value="{{ $member->phone ?? '' }}"
                    class="w-full rounded-lg border border-gray-200 px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors"
                    placeholder="010-0000-0000" />
                </div>
                <div class="sm:col-span-2">
                  <label class="block text-sm font-bold text-text-main mb-2">이메일 주소 <span
                      class="text-primary">*</span></label>
                  <input type="email" id="ordererEmail" value="{{ $member->email }}"
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
                    <input type="text" id="recipientName" value="{{ $member->name }}"
                      class="w-full rounded-lg border border-gray-200 px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors" />
                  </div>
                  <div>
                    <label class="block text-sm font-bold text-text-main mb-2">휴대폰 번호 <span
                        class="text-primary">*</span></label>
                    <input type="tel" id="recipientPhone" value="{{ $member->phone ?? '' }}"
                      class="w-full rounded-lg border border-gray-200 px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors" />
                  </div>
                </div>
                <div>
                  <label class="block text-sm font-bold text-text-main mb-2">주소 <span
                      class="text-primary">*</span></label>
                  <div class="flex gap-2 mb-2">
                    <input type="text" id="recipientZipcode" value="{{ $member->zipcode ?? '' }}"
                      class="w-32 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors"
                      readonly />
                    <button type="button" id="btnPostcode"
                      class="px-4 py-3 bg-gray-100 hover:bg-gray-200 font-bold text-sm text-text-main rounded-lg transition-colors border border-gray-200">
                      우편번호 찾기
                    </button>
                  </div>
                  <input type="text" id="recipientAddress" value="{{ $member->address_base ?? '' }}"
                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm mb-2 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors"
                    readonly />
                  <input type="text" id="recipientDetailAddress" value="{{ $member->address_detail ?? '' }}"
                    class="w-full rounded-lg border border-gray-200 px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors"
                    placeholder="상세 주소를 입력해주세요" />
                </div>
                <div class="mt-2">
                  <label class="block text-sm font-bold text-text-main mb-2">배송 요청사항</label>
                  <select class="w-full rounded-lg border border-gray-200 px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-colors">
                    <option value="call">배송 전에 미리 연락주세요.</option>
                    <option value="security">부재 시 경비실에 맡겨주세요.</option>
                    <option value="door" selected>부재 시 문 앞에 놓아주세요.</option>
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
                  <input type="radio" name="payment_method" value="card" class="sr-only" checked />
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
                  <input type="radio" name="payment_method" value="vbank" class="sr-only" />
                  <span class="material-symbols-outlined text-gray-500 mb-2 text-3xl">account_balance</span>
                  <div class="font-bold text-text-main text-sm">
                    무통장입금
                  </div>
                </label>
                <label
                  class="border border-gray-200 rounded-xl p-4 text-center cursor-pointer hover:border-primary/50 transition-all opacity-70 hover:opacity-100">
                  <input type="radio" name="payment_method" value="kakaopay" class="sr-only" />
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
                  <input type="radio" name="payment_method" value="naverpay" class="sr-only" />
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
                class="bg-background-alt p-5 rounded-xl border border-gray-100 transition-all duration-300 hidden opacity-0 text-sm text-text-main">
                <div class="mb-4">
                  <label class="block font-bold mb-2">입금 은행</label>
                  <select
                    class="w-full rounded-lg border border-gray-200 px-4 py-3 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                    <option value="">은행을 선택하세요</option>
                    <option>국민은행 0000-00-00000 (예금주: 액티브우먼)</option>
                    <option>신한은행 111-111-111111 (예금주: 액티브우먼)</option>
                  </select>
                </div>
                <div>
                  <label class="block font-bold mb-2">입금자명</label>
                  <input type="text"
                    class="w-full rounded-lg border border-gray-200 px-4 py-3 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                    placeholder="입금자 성함을 입력해주세요" value="{{ $member->name }}" />
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
              <!-- 주문 상품 목록 -->
              <div class="mb-6 border-b border-gray-100 pb-6">
                <h3 class="font-bold text-lg text-text-main mb-4 flex justify-between items-center">
                  주문 상품
                  <span class="text-primary text-sm font-bold">{{ count($checkoutItems) }}건</span>
                </h3>
                <div class="space-y-4">
                  @foreach ($checkoutItems as $index => $item)
                  <div class="flex gap-3 {{ $index >= 2 ? 'hidden extra-item' : '' }}">
                    <img
                      src="{{ $item['product']->image_url }}"
                      class="w-16 h-16 rounded object-cover border border-gray-100 bg-gray-50" alt="{{ $item['product']->name }}" />
                    <div class="flex flex-col justify-center flex-grow">
                      <span class="text-sm font-bold text-text-main truncate">{{ $item['product']->name }}</span>
                      <span class="text-xs text-text-muted">
                        @if($item['color']){{ $item['color'] }}@endif
                        @if($item['color'] && $item['size']) / @endif
                        @if($item['size']){{ $item['size'] }}@endif
                        | {{ $item['quantity'] }}개
                      </span>
                    </div>
                    <div class="flex flex-col justify-center text-right">
                      <span class="text-sm font-bold text-text-main">₩{{ number_format($item['total']) }}</span>
                    </div>
                  </div>
                  @endforeach

                  @if (count($checkoutItems) > 2)
                  <div class="text-center bg-background-alt rounded-lg py-2 mt-2">
                    <button id="btnShowMoreItems"
                      class="text-xs font-bold text-text-main hover:text-primary transition-colors flex items-center justify-center w-full">
                      <span id="txtShowMore">그 외 {{ count($checkoutItems) - 2 }}개 상품 더보기</span>
                      <span id="iconShowMore"
                        class="material-symbols-outlined text-[1rem] align-middle ml-1 transition-transform duration-300">expand_more</span>
                    </button>
                  </div>
                  @endif
                </div>
              </div>

              <div class="space-y-4 text-base mb-6">
                <div class="flex justify-between text-text-muted font-medium">
                  <span>상품 총 금액</span>
                  <span class="text-text-main">₩{{ number_format($totalProductPrice) }}</span>
                </div>
                {{-- 할인 정책은 추후 추가 --}}
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
                  보유 적립금: <strong id="maxPoints">{{ number_format($member->points) }}</strong>원
                </div>
                <div class="flex justify-between text-text-muted font-medium">
                  <span>배송비</span>
                  <span class="text-text-main font-bold" id="shippingFeeText">
                    @if($shippingFee > 0)
                      ₩{{ number_format($shippingFee) }}
                    @else
                      무료
                    @endif
                  </span>
                </div>
              </div>

              <div class="border-t-2 border-text-main pt-6 mb-8">
                <div class="flex justify-between items-end">
                  <span class="font-bold text-text-main text-lg">최종 결제 금액</span>
                  <span class="font-extrabold text-3xl text-primary tracking-tight">₩<span
                      id="finalTotalAmtDisplay">{{ number_format($finalTotal) }}</span></span>
                </div>
                <p class="text-right text-xs font-bold text-primary/80 mt-2">
                  최대 <span id="rewardPoints">{{ number_format(floor($finalTotal * 0.01)) }}</span>원 적립 예정
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
                <button type="button" id="btnDoPayment"
                  class="w-full bg-primary text-white font-extrabold text-lg rounded-xl py-4 hover:bg-red-600 transition-colors shadow-lg hover:shadow-primary/30 focus:outline-none focus:ring-4 focus:ring-primary/20 transform hover:-translate-y-0.5 relative overflow-hidden group">
                  <span id="finalSubmitBtnText" class="relative z-10">{{ number_format($finalTotal) }}원 결제하기</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Coupon Modal -->
    <div id="couponModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden transform scale-95 transition-transform duration-300" id="couponModalContent">
            <div class="flex items-center justify-between p-5 border-b border-gray-100">
                <h3 class="text-lg font-bold text-text-main flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">confirmation_number</span> 쿠폰 선택
                </h3>
                <button onclick="closeCouponModal()" class="text-gray-400 hover:text-text-main transition-colors rounded-full p-1 hover:bg-gray-100">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="p-6 space-y-4 max-h-[60vh] overflow-y-auto scrollbar-hide">
                <!-- 가상 쿠폰 데이터 -->
                <div class="coupon-item p-4 border border-gray-200 rounded-xl cursor-pointer hover:border-primary transition-colors bg-gray-50 group" data-id="1" data-name="신규가입 5,000원 할인" data-discount="5000">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs font-bold text-primary bg-primary/10 px-2 py-0.5 rounded">COUPON</span>
                        <span class="text-sm font-bold text-text-main">₩5,000 할인</span>
                    </div>
                    <p class="text-sm font-bold text-text-main mb-1">신규가입 감사 쿠폰</p>
                    <p class="text-xs text-text-muted">30,000원 이상 구매 시 사용 가능</p>
                </div>
                <div class="coupon-item p-4 border border-gray-200 rounded-xl cursor-pointer hover:border-primary transition-colors bg-gray-50 group" data-id="2" data-name="MZ세대 특별 10% 할인" data-discount-rate="10">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs font-bold text-primary bg-primary/10 px-2 py-0.5 rounded">COUPON</span>
                        <span class="text-sm font-bold text-text-main">10% 할인</span>
                    </div>
                    <p class="text-sm font-bold text-text-main mb-1">MZ 세대 전용 할인 쿠폰</p>
                    <p class="text-xs text-text-muted">최대 10,000원 할인</p>
                </div>
            </div>
            <div class="p-5 border-t border-gray-100">
                <button onclick="closeCouponModal()" class="w-full py-3 bg-gray-100 text-text-main text-sm font-bold rounded-xl hover:bg-gray-200 transition-colors">취소</button>
            </div>
        </div>
    </div>

    <!-- Postcode Modal -->
    <div id="postcodeModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden transform scale-95 transition-transform duration-300 flex flex-col" id="postcodeModalContent" style="height: 550px;">
            <div class="flex items-center justify-between p-4 border-b border-gray-100">
                <h3 class="text-lg font-bold text-text-main flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary" style="font-size: 1.25rem;">location_on</span> 주소 검색
                </h3>
                <button type="button" onclick="closePostcodeModal()" class="text-gray-400 hover:text-text-main transition-colors rounded-full p-1 hover:bg-gray-100">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div id="postcodeWrap" class="w-full flex-grow relative overflow-hidden bg-white"></div>
        </div>
    </div>

    <div id="toastContainer" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[9998] flex flex-col items-center gap-3 pointer-events-none"></div>
@endsection

@push('scripts')
<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<!-- Iamport V1 -->
<script src="https://cdn.iamport.kr/v1/iamport.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // 포트원 초기화
    var IMP = window.IMP; 
    IMP.init("{{ env('IAMPORT_STORE_CODE', 'imp12345678') }}"); // .env에서 식별코드 로드

    // 초기 데이터 설정
    const BASE_PRODUCT_TOTAL = {{ $totalProductPrice }};
    const SHIPPING_FEE = {{ $shippingFee }};
    const MAX_POINTS = {{ $member->points }};
    
    let appliedDiscount = 0;
    let appliedPoints = 0;
    let finalTotal = BASE_PRODUCT_TOTAL + SHIPPING_FEE;

    // Toast Function
    function showToast(message, icon = "check_circle", color = "bg-text-main") {
        const container = document.getElementById("toastContainer");
        if (!container) return;
        const toast = document.createElement("div");
        toast.className = `flex items-center gap-3 ${color} text-white px-6 py-3.5 rounded-xl shadow-2xl text-sm font-bold pointer-events-auto toast-enter`;
        toast.innerHTML = `<span class="material-symbols-outlined text-lg">${icon}</span><span>${message}</span>`;
        container.appendChild(toast);
        setTimeout(() => {
            toast.classList.add("animate-fade-out");
            setTimeout(() => toast.remove(), 300);
        }, 2500);
    }

    // 금액 업데이트 엔진
    function updateFinalAmounts() {
        const totalDiscount = appliedDiscount + appliedPoints;
        finalTotal = Math.max(0, BASE_PRODUCT_TOTAL + SHIPPING_FEE - totalDiscount);
        
        document.getElementById('finalTotalAmtDisplay').textContent = finalTotal.toLocaleString();
        document.getElementById('finalSubmitBtnText').textContent = finalTotal.toLocaleString() + '원 결제하기';
        document.getElementById('rewardPoints').textContent = Math.floor(finalTotal * 0.01).toLocaleString();
    }

    // 1. 우편번호 찾기 모달 & 로직
    const btnPostcode = document.getElementById('btnPostcode');
    const postcodeModal = document.getElementById('postcodeModal');
    const postcodeModalContent = document.getElementById('postcodeModalContent');
    const postcodeWrap = document.getElementById('postcodeWrap');

    window.closePostcodeModal = () => {
        postcodeModal.classList.add('opacity-0');
        postcodeModalContent.classList.replace('scale-100', 'scale-95');
        setTimeout(() => {
            postcodeModal.classList.add('hidden');
        }, 300);
    };

    if (btnPostcode) {
        btnPostcode.addEventListener('click', () => {
            // 모달 표시
            postcodeModal.classList.remove('hidden');
            setTimeout(() => {
                postcodeModal.classList.remove('opacity-0');
                postcodeModalContent.classList.replace('scale-95', 'scale-100');
            }, 10);

            new daum.Postcode({
                oncomplete: function(data) {
                    document.getElementById('recipientZipcode').value = data.zonecode;
                    document.getElementById('recipientAddress').value = data.address;
                    closePostcodeModal();
                    document.getElementById('recipientDetailAddress').focus();
                },
                width: '100%',
                height: '100%',
                theme: {
                    bgColor: "#FFFFFF",
                    pageBgColor: "#F9FAFB",
                    textColor: "#111827",
                    queryTextColor: "#111827",
                    postcodeTextColor: "#E63946",
                    emphTextColor: "#E63946",
                    outlineColor: "#E5E7EB"
                }
            }).embed(postcodeWrap);
        });
    }

    // 2. 적립금 사용 로직
    const pointInput = document.getElementById('pointInput');
    const btnUseAllPoints = document.getElementById('btnUseAllPoints');

    if (pointInput) {
        pointInput.addEventListener('input', function() {
            let val = parseInt(this.value.replace(/[^0-9]/g, '')) || 0;
            if (val > MAX_POINTS) {
                val = MAX_POINTS;
                showToast(`보유하신 적립금 ${MAX_POINTS.toLocaleString()}원까지만 사용 가능합니다.`, "warning", "bg-amber-500");
            }
            if (val > (BASE_PRODUCT_TOTAL + SHIPPING_FEE - appliedDiscount)) {
                val = BASE_PRODUCT_TOTAL + SHIPPING_FEE - appliedDiscount;
            }
            this.value = val;
            appliedPoints = val;
            updateFinalAmounts();
        });
    }

    if (btnUseAllPoints) {
        btnUseAllPoints.addEventListener('click', () => {
            let val = Math.min(MAX_POINTS, BASE_PRODUCT_TOTAL + SHIPPING_FEE - appliedDiscount);
            pointInput.value = val;
            appliedPoints = val;
            updateFinalAmounts();
            showToast("적립금이 전액 적용되었습니다.");
        });
    }

    // 3. 쿠폰 모달 로직
    const couponModal = document.getElementById('couponModal');
    const couponModalContent = document.getElementById('couponModalContent');
    const btnCouponModal = document.getElementById('btnCouponModal');

    window.openCouponModal = () => {
        couponModal.classList.remove('hidden');
        setTimeout(() => {
            couponModal.classList.remove('opacity-0');
            couponModalContent.classList.replace('scale-95', 'scale-100');
        }, 10);
    };

    window.closeCouponModal = () => {
        couponModal.classList.add('opacity-0');
        couponModalContent.classList.replace('scale-100', 'scale-95');
        setTimeout(() => couponModal.classList.add('hidden'), 300);
    };

    if (btnCouponModal) btnCouponModal.addEventListener('click', openCouponModal);

    document.querySelectorAll('.coupon-item').forEach(item => {
        item.addEventListener('click', function() {
            const discount = parseInt(this.dataset.discount) || 0;
            const rate = parseInt(this.dataset.discountRate) || 0;
            
            if (discount > 0) {
                appliedDiscount = discount;
            } else if (rate > 0) {
                appliedDiscount = Math.min(10000, Math.floor(BASE_PRODUCT_TOTAL * (rate / 100)));
            }

            document.getElementById('couponAppliedText').classList.remove('hidden');
            document.getElementById('couponDiscountAmt').textContent = appliedDiscount.toLocaleString();
            
            // 적립금과 중복 검사 (금액 초과 방지)
            if (appliedPoints + appliedDiscount > BASE_PRODUCT_TOTAL + SHIPPING_FEE) {
                appliedPoints = BASE_PRODUCT_TOTAL + SHIPPING_FEE - appliedDiscount;
                pointInput.value = appliedPoints;
            }

            updateFinalAmounts();
            closeCouponModal();
            showToast(`쿠폰이 적용되었습니다! (-₩${appliedDiscount.toLocaleString()})`);
        });
    });

    // 4. 결제하기 클릭 시 유효성 검사
    const btnDoPayment = document.getElementById('btnDoPayment');
    if (btnDoPayment) {
        btnDoPayment.addEventListener('click', () => {
            const recipientName = document.getElementById('recipientName').value.trim();
            const recipientPhone = document.getElementById('recipientPhone').value.trim();
            const recipientZipcode = document.getElementById('recipientZipcode').value.trim();
            const recipientAddress = document.getElementById('recipientAddress').value.trim();
            const agreeTerms = document.getElementById('agreeTerms').checked;

            if (!recipientName) {
                showToast("받는 사람 이름을 입력해주세요.", "error", "bg-red-500");
                document.getElementById('recipientName').focus();
                return;
            }
            if (!recipientPhone) {
                showToast("받는 사람 연락처를 입력해주세요.", "error", "bg-red-500");
                document.getElementById('recipientPhone').focus();
                return;
            }
            if (!recipientZipcode || !recipientAddress) {
                showToast("배송지 주소를 입력해주세요.", "error", "bg-red-500");
                return;
            }
            if (!agreeTerms) {
                showToast("결제 진행 동의(필수)에 체크해주세요.", "error", "bg-red-500");
                return;
            }

            // 모든 검증 통과
            showToast("결제 요청 중입니다... 잠시만 기다려주세요!", "pending", "bg-primary");
            
            // 실제 서버 전송 로직 (결제 모듈 호출)
            const payload = {
                recipient_name: recipientName,
                recipient_phone: recipientPhone,
                recipient_zipcode: recipientZipcode,
                recipient_address: recipientAddress,
                recipient_detail_address: document.getElementById('recipientDetailAddress').value.trim(),
                shipping_message: document.querySelector('select').value, 
                payment_method: document.querySelector('input[name="payment_method"]:checked').value,
                applied_points: appliedPoints
            };

            // 주문 번호 및 결제 정보 수집
            const merchantUid = 'ACT_' + new Date().getTime();
            const ordererName = document.getElementById('ordererName').value;
            const ordererEmail = document.getElementById('ordererEmail').value;
            const ordererPhone = document.getElementById('ordererPhone').value;
            
            // 첫번째 상품 이름 가져오기
            const firstItemName = document.querySelector('.extra-item') ? 
                document.querySelector('.truncate').innerText + ' 외 다수' : 
                document.querySelector('.truncate').innerText;

            // 아임포트 결제 요청 파라미터 구성
            // 오빠의 포트원 스크린샷을 분석한 결과 'KG이니시스' (html5_inicis) 테스트 채널 1개만 등록되어 있어요!
            // 따라서 어떤 결제수단을 누르든 일단 테스트 채널(이니시스)로 고정 호출해야 에러가 나지 않습니다.
            let pgProvider = 'html5_inicis'; 
            
            // 만약 나중에 카카오페이, 네이버페이 채널을 추가하면 활성화하세요!
            if (payload.payment_method === 'kakaopay') {
                // pgProvider = 'kakaopay'; // 현재 미등록이므로 에러 방지용 주석처리
            } else if (payload.payment_method === 'naverpay') {
                // pgProvider = 'naverpay'; // 현재 미등록이므로 에러 방지용 주석처리
            }

            const reqData = {
                pg: pgProvider,
                pay_method: payload.payment_method === 'vbank' ? 'vbank' : 'card',
                merchant_uid: merchantUid,
                name: firstItemName,
                amount: finalTotal, // 테스트이므로 아임포트에서 100원 이상 결제를 권장합니다
                buyer_email: ordererEmail,
                buyer_name: ordererName,
                buyer_tel: ordererPhone,
                buyer_addr: recipientAddress,
                buyer_postcode: recipientZipcode
            };

            IMP.request_pay(reqData, function (rsp) { // callback
                if (rsp.success) {
                    showToast("결제 승인 완료! 서버에서 안전하게 확인 중입니다...", "pending", "bg-primary");
                    
                    // 결제 성공 시 서버 검증 및 DB 저장 요청
                    payload.imp_uid = rsp.imp_uid;
                    payload.merchant_uid = rsp.merchant_uid;

                    fetch('{{ route("checkout.verify") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.message, "check_circle", "bg-primary");
                            setTimeout(() => {
                                location.href = data.redirect;
                            }, 1500);
                        } else {
                            showToast(data.message || "결제 사후 검증 중 오류가 발생했습니다.", "error", "bg-red-500");
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast("서버 통신 오류가 발생했습니다.", "error", "bg-red-500");
                    });

                } else {
                    // 결제 실패
                    showToast(`결제에 실패하였습니다. (${rsp.error_msg})`, "error", "bg-red-500");
                }
            });
        });
    }

    // "주문자와 동일" 체크박스 로직
    const sameAsOrderer = document.getElementById('sameAsOrderer');
    if (sameAsOrderer) {
        sameAsOrderer.addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('recipientName').value = document.getElementById('ordererName').value;
                document.getElementById('recipientPhone').value = document.getElementById('ordererPhone').value;
            }
        });
    }

    // 상품 더보기 토글 (기존 로직 유지)
    const btnShowMore = document.getElementById('btnShowMoreItems');
    if (btnShowMore) {
      btnShowMore.addEventListener('click', () => {
        const extras = document.querySelectorAll('.extra-item');
        const isHidden = extras[0].classList.contains('hidden');
        
        extras.forEach(el => el.classList.toggle('hidden'));
        document.getElementById('iconShowMore').style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
        document.getElementById('txtShowMore').textContent = isHidden ? '숨기기' : `그 외 ${extras.length}개 상품 더보기`;
      });
    }

    // 결제 수단 탭 전환 (기존 로직 유지)
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const cardForm = document.getElementById('cardPaymentForm');
    const bankForm = document.getElementById('bankTransferForm');
    const simpleForm = document.getElementById('simplePayForm');

    paymentRadios.forEach(radio => {
      radio.addEventListener('change', (e) => {
        [cardForm, bankForm, simpleForm].forEach(f => {
          f.classList.add('hidden', 'opacity-0');
        });
        paymentRadios.forEach(r => {
          const label = r.closest('label');
          label.classList.remove('border-2', 'border-primary', 'bg-primary/5');
          label.classList.add('border', 'border-gray-200');
          label.querySelector('.material-symbols-outlined:last-child')?.parentElement?.classList.add('hidden');
        });
        const selectedLabel = e.target.closest('label');
        selectedLabel.classList.add('border-2', 'border-primary', 'bg-primary/5');
        selectedLabel.classList.remove('border', 'border-gray-200');
        const val = e.target.value;
        let targetForm = val === 'card' ? cardForm : (val === 'vbank' ? bankForm : simpleForm);
        targetForm.classList.remove('hidden');
        setTimeout(() => targetForm.classList.remove('opacity-0'), 10);
      });
    });
  });
</script>
@endpush
