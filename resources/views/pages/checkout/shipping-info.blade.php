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
