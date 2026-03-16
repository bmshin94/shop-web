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
