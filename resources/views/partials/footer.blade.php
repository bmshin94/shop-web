<footer class="border-t border-background-alt bg-white pb-8 pt-16">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="grid gap-12 lg:grid-cols-4">
      <div class="lg:col-span-1">
        <div class="flex items-center gap-2 mb-4">
          <div class="flex size-6 items-center justify-center rounded-full bg-primary text-white">
            <span class="material-symbols-outlined text-sm">stat_1</span>
          </div>
          <span class="text-lg font-bold text-text-main">Active Women</span>
        </div>
        <p class="mb-6 text-sm leading-relaxed text-text-muted break-keep">
          {{ $siteSettings['footer_description'] ?? '프리미엄 스포츠 기어와 뷰티 에센셜로 완성하는 액티브 라이프스타일. 퍼포먼스를 위한 디자인, 당신을 위한 스타일.' }}
        </p>
        <div class="flex gap-4">
          <a class="text-text-muted hover:text-primary" href="#"><span class="material-symbols-outlined">thumb_up</span></a>
          <a class="text-text-muted hover:text-primary" href="#"><span class="material-symbols-outlined">photo_camera</span></a>
          <a class="text-text-muted hover:text-primary" href="#"><span class="material-symbols-outlined">play_circle</span></a>
        </div>
      </div>
      <div>
        <h4 class="mb-4 font-bold text-text-main">쇼핑하기</h4>
        <ul class="space-y-3 text-sm text-text-muted">
          <li><a class="hover:text-primary" href="/product-list">신상품</a></li>
          <li><a class="hover:text-primary" href="/product-list">베스트 셀러</a></li>
          <li><a class="hover:text-primary" href="/product-list">스포츠웨어</a></li>
          <li><a class="hover:text-primary" href="/product-list">뷰티 &amp; 케어</a></li>
          <li><a class="hover:text-primary" href="/product-list">액세서리</a></li>
        </ul>
      </div>
      <div>
        <h4 class="mb-4 font-bold text-text-main">고객지원</h4>
        <ul class="space-y-3 text-sm text-text-muted">
          <li><a class="hover:text-primary" href="/mypage/order-list">주문배송 조회</a></li>
          <li><a class="hover:text-primary" href="/mypage/claim-list">교환/반품 신청</a></li>
          <li><a class="hover:text-primary" href="/support/notice">사이즈 가이드</a></li>
          <li><a class="hover:text-primary" href="/qna/write">1:1 문의</a></li>
          <li><a class="hover:text-primary" href="/support">자주 묻는 질문</a></li>
        </ul>
      </div>
      <div>
        <h4 class="mb-4 font-bold text-text-main">뉴스레터</h4>
        <p class="mb-4 text-sm text-text-muted">새로운 드롭 소식과 혜택을 놓치지 마세요.</p>
        <form class="flex gap-2">
          <input
            class="w-full rounded-lg border border-gray-200 bg-background-alt px-4 py-2 text-sm text-text-main focus:border-primary focus:ring-1 focus:ring-primary"
            placeholder="이메일 주소 입력" type="email" />
          <button class="rounded-lg bg-primary px-4 py-2 font-bold text-white hover:bg-red-600" type="submit">
            <span class="material-symbols-outlined text-lg">arrow_forward</span>
          </button>
        </form>
      </div>
    </div>
    <div class="mt-16 border-t border-gray-100 pt-8 text-xs text-text-muted leading-relaxed">
      <div class="grid md:grid-cols-2 gap-4">
        <div>
          <p class="font-bold text-text-main mb-2">{{ $siteSettings['company_name'] ?? '(주)액티브우먼' }}</p>
          <p>대표이사: {{ $siteSettings['ceo_name'] ?? '김액티브' }} | 사업자등록번호: {{ $siteSettings['business_number'] ?? '123-45-67890' }}</p>
          <p>통신판매업신고: {{ $siteSettings['ecommerce_number'] ?? '2024-서울강남-1234' }} | 개인정보관리책임자: {{ $siteSettings['privacy_officer'] ?? '이담당' }}</p>
          <p>주소: {{ $siteSettings['address'] ?? '서울특별시 강남구 테헤란로 123, 액티브타워 10층' }}</p>
          <p class="mt-2">고객센터: {{ $siteSettings['customer_center'] ?? '1544-0000 (평일 09:00 - 18:00)' }}</p>
        </div>
        <div class="md:text-right flex flex-col justify-between">
          <div class="flex gap-2 justify-start md:justify-end mb-2">
            <a class="font-bold text-text-main" href="#">이용약관</a>
            <span class="text-gray-300">|</span>
            <a class="font-bold text-primary" href="#">개인정보처리방침</a>
            <span class="text-gray-300">|</span>
            <a class="font-bold text-text-main" href="#">사업자정보확인</a>
          </div>
          <p>© 2024 Active Women Store. All rights reserved.</p>
          <p class="mt-1">에스크로 서비스 가입 사실 확인</p>
        </div>
      </div>
    </div>
  </div>
</footer>
