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
