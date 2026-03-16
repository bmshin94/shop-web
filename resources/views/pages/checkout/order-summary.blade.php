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

  <!-- 결제 금액 요약 -->
  <div class="space-y-4 text-base mb-6">
    <div class="flex justify-between text-text-muted font-medium">
      <span>상품 총 금액</span>
      <span class="text-text-main">₩{{ number_format($totalProductPrice) }}</span>
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

  <!-- 최종 결제 금액 -->
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
