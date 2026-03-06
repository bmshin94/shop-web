const fs = require('fs');
const path = require('path');

const baseDir = __dirname;
const mypagePath = path.join(baseDir, 'mypage.html');

const template = fs.readFileSync(mypagePath, 'utf8');

const asideStart = template.indexOf('<!-- LNB (Left Navigation Bar) -->');
const mainContentStart = template.indexOf('<!-- Main Dashboard Content -->');
const mainContentEnd = template.indexOf('</main>');

const headerHtml = template.substring(0, asideStart);
const footerHtml = template.substring(mainContentEnd);
const dashboardHtml = template.substring(mainContentStart, mainContentEnd);

const asideTemplate = `<!-- LNB (Left Navigation Bar) -->
                <aside class="w-full lg:w-64 shrink-0 bg-white rounded-2xl shadow-sm border border-border-color p-6 sticky top-32 h-fit">
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-text-main mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">person</span> 나의 쇼핑 정보
                        </h3>
                        <ul class="space-y-3">
                            <li><a href="mypage-order-list.html" class="{class_order}">주문/배송 조회</a></li>
                            <li><a href="mypage-claim-list.html" class="{class_claim}">취소/반품/교환 내역</a></li>
                            <li><a href="mypage-refund-list.html" class="{class_refund}">환불/입금 내역</a></li>
                            <li><a href="mypage-receipt.html" class="{class_receipt}">영수증/계산서 발급</a></li>
                        </ul>
                    </div>
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-text-main mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">redeem</span> 혜택 관리
                        </h3>
                        <ul class="space-y-3">
                            <li><a href="mypage-coupon.html" class="{class_coupon} text-sm flex justify-between">쿠폰 <span class="text-primary font-bold">2장</span></a></li>
                            <li><a href="mypage-point.html" class="{class_point} text-sm flex justify-between">적립금 <span class="text-primary font-bold">12,500원</span></a></li>
                            <li><a href="mypage-deposit.html" class="{class_deposit}">예치금 내역</a></li>
                        </ul>
                    </div>
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-text-main mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">favorite</span> 관심 상품
                        </h3>
                        <ul class="space-y-3">
                            <li><a href="mypage-wishlist.html" class="{class_wishlist}">찜한 상품</a></li>
                            <li><a href="mypage-recent.html" class="{class_recent}">최근 본 상품</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-text-main mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">support_agent</span> 고객 센터
                        </h3>
                        <ul class="space-y-3">
                            <li><a href="mypage-inquiry.html" class="{class_inquiry}">1:1 문의내역</a></li>
                            <li><a href="mypage-review.html" class="{class_review}">상품 리뷰 관리</a></li>
                            <li><a href="mypage-profile.html" class="{class_profile}">회원정보 수정</a></li>
                        </ul>
                    </div>
                </aside>
`;

const pages = {
    class_order: {
        filename: "mypage-order-list.html",
        title: "주문/배송 조회",
        content: `
                    <div class="bg-white rounded-2xl shadow-sm border border-border-color p-6 sm:p-8">
                        <h3 class="text-xl font-bold text-text-main mb-6">주문/배송 조회</h3>
                        <div class="flex flex-wrap gap-2 mb-6">
                            <button class="px-4 py-2 text-sm font-bold rounded-lg border border-primary text-primary bg-primary-light transition-colors">1개월</button>
                            <button class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 text-text-main hover:bg-gray-50 transition-colors">3개월</button>
                            <button class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 text-text-main hover:bg-gray-50 transition-colors">6개월</button>
                            <button class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 text-text-main hover:bg-gray-50 transition-colors">12개월</button>
                        </div>
                        
                        <h4 class="text-md font-bold text-primary mb-4 mt-8"><span class="material-symbols-outlined align-middle text-sm mr-1">check_circle</span>[상태 1] 데이터가 있는 경우</h4>
                        <div class="overflow-x-auto rounded-xl border border-gray-100 mb-12">
                            <table class="w-full text-left border-collapse min-w-[700px]">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-100">
                                        <th class="py-4 px-6 text-sm font-bold text-text-main text-center">주문일자<br><span class="text-xs font-normal text-text-muted">[주문번호]</span></th>
                                        <th class="py-4 px-6 text-sm font-bold text-text-main">상품정보</th>
                                        <th class="py-4 px-6 text-sm font-bold text-text-main text-center">결제금액</th>
                                        <th class="py-4 px-6 text-sm font-bold text-text-main text-center">주문상태</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="py-5 px-6 text-center">
                                            <p class="font-bold text-text-main text-sm">2026.03.01</p>
                                            <p class="text-xs text-text-muted mt-1">[260301-1234567]</p>
                                        </td>
                                        <td class="py-5 px-6">
                                            <div class="flex items-center gap-4">
                                                <div class="size-16 bg-gray-100 rounded-lg overflow-hidden shrink-0">
                                                    <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=200&h=200&fit=crop" class="w-full h-full object-cover">
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-primary mb-1">Active Women</p>
                                                    <a href="product-detail.html" class="text-sm font-bold text-text-main hover:text-primary transition-colors line-clamp-1">프리미엄 요가 레깅스 3종 세트</a>
                                                    <p class="text-xs text-text-muted mt-1">옵션: 블랙 / M 사이즈 (1개)</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-5 px-6 text-center whitespace-nowrap">
                                            <p class="font-extrabold text-text-main text-sm">54,000원</p>
                                        </td>
                                        <td class="py-5 px-6 text-center">
                                            <span class="inline-flex py-1 px-3 bg-primary-light text-primary font-bold text-xs rounded-full border border-primary/20">결제완료</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h4 class="text-md font-bold text-gray-500 mb-4"><span class="material-symbols-outlined align-middle text-sm mr-1">cancel</span>[상태 2] 데이터가 없는 경우</h4>
                        <div class="flex flex-col items-center justify-center py-20 text-center border border-gray-100 rounded-xl bg-gray-50">
                            <span class="material-symbols-outlined text-5xl text-gray-300 mb-4">inventory_2</span>
                            <p class="text-text-muted font-medium text-lg">조회 기간 동안 주문한 내역이 없습니다.</p>
                        </div>
                    </div>
`
    },
    class_claim: {
        filename: "mypage-claim-list.html",
        title: "취소/반품/교환 내역",
        content: `
                    <div class="bg-white rounded-2xl shadow-sm border border-border-color p-6 sm:p-8">
                        <h3 class="text-xl font-bold text-text-main mb-6">취소/반품/교환 내역</h3>
                        <p class="text-sm text-text-muted mb-6 bg-gray-50 p-4 rounded-xl">최근 3개월 간의 클레임 내역입니다. 이전 내역은 기간 검색을 이용해주세요.</p>
                        
                        <h4 class="text-md font-bold text-primary mb-4 mt-8"><span class="material-symbols-outlined align-middle text-sm mr-1">check_circle</span>[상태 1] 데이터가 있는 경우</h4>
                        <div class="overflow-x-auto rounded-xl border border-gray-100 mb-12">
                            <table class="w-full text-left border-collapse min-w-[700px]">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-100 text-sm font-bold text-text-main">
                                        <th class="py-4 px-6 text-center whitespace-nowrap">접수일자</th>
                                        <th class="py-4 px-6 whitespace-nowrap">상품정보</th>
                                        <th class="py-4 px-6 text-center whitespace-nowrap">클레임 유형</th>
                                        <th class="py-4 px-6 text-center whitespace-nowrap">진행상태</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="hover:bg-gray-50 transition-colors border-b border-gray-100">
                                        <td class="py-5 px-6 text-center">
                                            <p class="font-bold text-text-main text-sm">2026.02.25</p>
                                        </td>
                                        <td class="py-5 px-6">
                                            <div class="flex items-center gap-4">
                                                <div class="size-16 bg-gray-100 rounded-lg overflow-hidden shrink-0">
                                                    <img src="https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?w=200&h=200&fit=crop" class="w-full h-full object-cover">
                                                </div>
                                                <a href="product-detail.html" class="text-sm font-bold text-text-main hover:text-primary transition-colors line-clamp-1">에어 컴포트 스포츠 브라탑</a>
                                            </div>
                                        </td>
                                        <td class="py-5 px-6 text-center font-bold text-sm">반품신청</td>
                                        <td class="py-5 px-6 text-center">
                                            <span class="inline-flex py-1 px-3 bg-gray-100 text-gray-500 font-bold text-xs rounded-full border border-gray-200">반품처리중</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h4 class="text-md font-bold text-gray-500 mb-4"><span class="material-symbols-outlined align-middle text-sm mr-1">cancel</span>[상태 2] 데이터가 없는 경우</h4>
                        <div class="flex flex-col items-center justify-center py-16 text-center border border-gray-100 rounded-xl bg-gray-50">
                            <span class="material-symbols-outlined text-4xl text-gray-300 mb-3">assignment_return</span>
                            <p class="text-text-muted font-medium">취소/반품/교환 신청 내역이 없습니다.</p>
                        </div>
                    </div>
`
    },
    class_refund: {
        filename: "mypage-refund-list.html",
        title: "환불/입금 내역",
        content: `
                    <div class="bg-white rounded-2xl shadow-sm border border-border-color p-6 sm:p-8">
                        <h3 class="text-xl font-bold text-text-main mb-6">환불/입금 내역</h3>
                        
                        <h4 class="text-md font-bold text-primary mb-4 mt-8"><span class="material-symbols-outlined align-middle text-sm mr-1">check_circle</span>[상태 1] 데이터가 있는 경우</h4>
                        <div class="overflow-x-auto rounded-xl border border-gray-100 mb-12">
                            <table class="w-full text-left border-collapse min-w-[500px]">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-100 text-sm font-bold text-text-main">
                                        <th class="py-4 px-6 text-center whitespace-nowrap">일자</th>
                                        <th class="py-4 px-6 text-center whitespace-nowrap">유형</th>
                                        <th class="py-4 px-6 whitespace-nowrap">내용</th>
                                        <th class="py-4 px-6 text-center whitespace-nowrap">금액</th>
                                        <th class="py-4 px-6 text-center whitespace-nowrap">상태</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                        <td class="py-5 px-6 text-center text-sm font-medium">2026.02.26</td>
                                        <td class="py-5 px-6 text-center text-sm font-bold">환불</td>
                                        <td class="py-5 px-6 text-sm">에어 컴포트 스포츠 브라탑 반품에 따른 환불</td>
                                        <td class="py-5 px-6 text-center text-sm font-extrabold text-primary">78,000원</td>
                                        <td class="py-5 px-6 text-center"><span class="inline-flex py-1 px-3 bg-gray-100 text-gray-500 font-bold text-xs rounded-full border border-gray-200">환불완료</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h4 class="text-md font-bold text-gray-500 mb-4"><span class="material-symbols-outlined align-middle text-sm mr-1">cancel</span>[상태 2] 데이터가 없는 경우</h4>
                        <div class="overflow-x-auto rounded-xl border border-gray-100">
                            <table class="w-full text-left border-collapse min-w-[500px]">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-100 text-sm font-bold text-text-main">
                                        <th class="py-4 px-6 text-center whitespace-nowrap">접수일자</th>
                                        <th class="py-4 px-6 text-center whitespace-nowrap">유형</th>
                                        <th class="py-4 px-6 whitespace-nowrap">내용</th>
                                        <th class="py-4 px-6 text-center whitespace-nowrap">금액</th>
                                        <th class="py-4 px-6 text-center whitespace-nowrap">상태</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="5" class="py-12 text-center text-text-muted font-medium bg-gray-50">해당하는 환불/입금 내역이 존재하지 않습니다.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
`
    },
    class_receipt: {
        filename: "mypage-receipt.html",
        title: "영수증/계산서 발급",
        content: `
                    <div class="bg-white rounded-2xl shadow-sm border border-border-color p-6 sm:p-8">
                        <h3 class="text-xl font-bold text-text-main mb-6">영수증/계산서 발급</h3>
                        <p class="text-sm text-text-muted mb-6 bg-gray-50 p-4 rounded-xl">신용카드 매출전표, 현금영수증, 세금계산서 발급 내역을 확인하고 인쇄할 수 있습니다.</p>

                        <h4 class="text-md font-bold text-primary mb-4 mt-8"><span class="material-symbols-outlined align-middle text-sm mr-1">check_circle</span>[상태 1] 데이터가 있는 경우</h4>
                        <div class="overflow-x-auto rounded-xl border border-gray-100 mb-12">
                            <table class="w-full text-left border-collapse min-w-[500px]">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-100 text-sm font-bold text-text-main">
                                        <th class="py-4 px-6 text-center whitespace-nowrap">발급일자</th>
                                        <th class="py-4 px-6 whitespace-nowrap">내용(주문정보)</th>
                                        <th class="py-4 px-6 text-center whitespace-nowrap">결제금액</th>
                                        <th class="py-4 px-6 text-center whitespace-nowrap">증빙종류</th>
                                        <th class="py-4 px-6 text-center whitespace-nowrap">출력</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="hover:bg-gray-50 transition-colors border-b border-gray-100">
                                        <td class="py-4 px-6 text-center text-sm font-medium">2026.03.01</td>
                                        <td class="py-4 px-6 text-sm">[260301-1234567] 프리미엄 요가 레깅스 3종 세트</td>
                                        <td class="py-4 px-6 text-center text-sm font-bold text-text-main">54,000원</td>
                                        <td class="py-4 px-6 text-center text-sm">신용카드 매출전표</td>
                                        <td class="py-4 px-6 text-center"><button class="px-3 py-1 bg-white border border-gray-300 text-xs font-bold rounded shadow-sm hover:bg-gray-50">인쇄</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h4 class="text-md font-bold text-gray-500 mb-4"><span class="material-symbols-outlined align-middle text-sm mr-1">cancel</span>[상태 2] 데이터가 없는 경우</h4>
                        <div class="flex flex-col items-center justify-center py-12 text-center border border-gray-100 rounded-xl bg-gray-50">
                            <span class="material-symbols-outlined text-4xl text-gray-300 mb-3">receipt_long</span>
                            <p class="text-text-muted font-medium">발급 가능한 증빙 서류 내역이 없습니다.</p>
                        </div>
                    </div>
`
    },
    class_coupon: {
        filename: "mypage-coupon.html",
        title: "쿠폰",
        content: `
                    <div class="bg-white rounded-2xl shadow-sm border border-border-color p-6 sm:p-8 mb-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-text-main">나의 쿠폰</h3>
                            <p class="text-3xl font-extrabold text-primary">2<span class="text-lg text-text-main font-bold ml-1">장</span></p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <input type="text" placeholder="쿠폰 번호를 입력하세요" class="flex-1 rounded-xl border border-gray-300 focus:border-primary focus:ring-primary px-4 py-3 text-sm">
                            <button class="px-8 py-3 bg-text-main text-white font-bold rounded-xl hover:bg-black transition-colors shrink-0">쿠폰 등록</button>
                        </div>
                    </div>
                    
                    <h4 class="text-md font-bold text-primary mb-4 mt-8"><span class="material-symbols-outlined align-middle text-sm mr-1">check_circle</span>[상태 1] 혜택 데이터가 있는 경우</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-12">
                        <!-- Coupon 1 -->
                        <div class="border border-primary/20 bg-primary-light/30 rounded-2xl p-6 relative overflow-hidden">
                            <div class="absolute -right-4 -top-4 size-16 bg-primary rounded-full opacity-10"></div>
                            <span class="inline-block px-2 py-1 bg-primary text-white text-xs font-bold rounded mb-3">배송비 쿠폰</span>
                            <h4 class="text-lg font-extrabold text-text-main mb-1">무료배송 쿠폰</h4>
                            <p class="text-sm text-text-muted mb-4">5만원 이상 결제 시 사용 가능</p>
                            <p class="text-xs text-gray-500 font-medium">유효기간: 2026.03.31 까지</p>
                        </div>
                        <!-- Coupon 2 -->
                        <div class="border border-border-color border-dashed bg-white rounded-2xl p-6 relative">
                            <span class="inline-block px-2 py-1 bg-text-main text-white text-xs font-bold rounded mb-3">할인 쿠폰</span>
                            <h4 class="text-lg font-extrabold text-text-main mb-1">웰컴 10% 장바구니 할인</h4>
                            <p class="text-sm text-text-muted mb-4">전 상품 적용 가능 (최대 3만원)</p>
                            <p class="text-xs text-text-muted font-medium">유효기간: 2026.04.15 까지</p>
                        </div>
                    </div>

                    <h4 class="text-md font-bold text-gray-500 mb-4"><span class="material-symbols-outlined align-middle text-sm mr-1">cancel</span>[상태 2] 데이터가 없는 경우</h4>
                    <div class="flex flex-col items-center justify-center py-12 text-center border border-gray-100 rounded-xl bg-gray-50">
                        <p class="text-text-muted font-medium">사용 가능한 쿠폰이 없습니다.</p>
                    </div>
`
    },
    class_point: {
        filename: "mypage-point.html",
        title: "적립금",
        content: `
                    <div class="bg-white rounded-2xl shadow-sm border border-border-color p-6 sm:p-8 mb-8">
                        <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-6">
                            <h3 class="text-xl font-bold text-text-main">가용 적립금</h3>
                            <p class="text-3xl font-extrabold text-primary">12,500<span class="text-lg text-text-main font-bold ml-1">원</span></p>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-medium text-text-muted">30일 내 소멸 예정 적립금</span>
                            <span class="font-bold text-text-main">0원</span>
                        </div>
                    </div>
                    
                    <h4 class="text-md font-bold text-primary mb-4 mt-8"><span class="material-symbols-outlined align-middle text-sm mr-1">check_circle</span>[상태 1] 데이터가 있는 경우</h4>
                    <div class="bg-white rounded-2xl shadow-sm border border-border-color p-6 sm:p-8 mb-12">
                        <h4 class="text-lg font-bold text-text-main mb-4">적립/사용 내역</h4>
                        <div class="border-t border-gray-100 divide-y divide-gray-100">
                            <div class="py-4 flex justify-between items-center">
                                <div>
                                    <p class="font-bold text-sm text-text-main mb-1">상품 구매 확정 적립</p>
                                    <p class="text-xs text-text-muted">2026.02.20</p>
                                </div>
                                <span class="font-bold text-primary">+2,500원</span>
                            </div>
                            <div class="py-4 flex justify-between items-center">
                                <div>
                                    <p class="font-bold text-sm text-text-main mb-1">신규 가입 환영 적립금</p>
                                    <p class="text-xs text-text-muted">2026.01.10</p>
                                </div>
                                <span class="font-bold text-primary">+10,000원</span>
                            </div>
                        </div>
                    </div>

                    <h4 class="text-md font-bold text-gray-500 mb-4"><span class="material-symbols-outlined align-middle text-sm mr-1">cancel</span>[상태 2] 데이터가 없는 경우</h4>
                    <div class="bg-white rounded-2xl shadow-sm border border-border-color p-6 sm:p-8 text-center py-10">
                        <p class="text-text-muted font-medium">적립금 내역이 없습니다.</p>
                    </div>
`
    },
    class_deposit: {
        filename: "mypage-deposit.html",
        title: "예치금 내역",
        content: `
                    <div class="bg-white rounded-2xl shadow-sm border border-border-color p-6 sm:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-text-main">예치금 조회</h3>
                            <p class="text-3xl font-extrabold text-primary">50,000<span class="text-lg text-text-main font-bold ml-1">원</span></p>
                        </div>
                        <p class="text-sm text-text-muted mb-8 bg-gray-50 p-4 rounded-xl">예치금은 고객님의 계좌로 언제든지 환불이 가능합니다. 예치금 환불은 1:1 문의를 통해 신청해주세요.</p>
                        
                        <h4 class="text-md font-bold text-primary mb-4 mt-8"><span class="material-symbols-outlined align-middle text-sm mr-1">check_circle</span>[상태 1] 데이터가 있는 경우</h4>
                        <div class="border-t border-gray-100 divide-y divide-gray-100 mb-12">
                             <div class="py-4 flex justify-between items-center">
                                <div>
                                    <p class="font-bold text-sm text-text-main mb-1">반품 접수에 따른 환불 금액 예치</p>
                                    <p class="text-xs text-text-muted">2026.02.20</p>
                                </div>
                                <span class="font-bold text-primary">+50,000원</span>
                            </div>
                        </div>

                        <h4 class="text-md font-bold text-gray-500 mb-4"><span class="material-symbols-outlined align-middle text-sm mr-1">cancel</span>[상태 2] 데이터가 없는 경우</h4>
                        <div class="border border-gray-100 rounded-xl pt-8 pb-8 text-center bg-gray-50">
                            <p class="text-text-muted font-medium">발생한 예치금 내역이 없습니다.</p>
                        </div>
                    </div>
`
    },
    class_wishlist: {
        filename: "mypage-wishlist.html",
        title: "찜한 상품",
        content: `
                    <div class="bg-white rounded-2xl shadow-sm border border-border-color p-6 sm:p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-bold text-text-main">찜한 상품</h3>
                            <button class="text-sm border border-gray-300 text-text-main rounded px-3 py-1.5 hover:bg-gray-50 font-bold transition-colors">선택 삭제</button>
                        </div>

                        <h4 class="text-md font-bold text-primary mb-4 mt-8"><span class="material-symbols-outlined align-middle text-sm mr-1">check_circle</span>[상태 1] 데이터가 있는 경우</h4>
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                            <!-- Product -->
                            <div class="group relative">
                                <div class="aspect-[3/4] overflow-hidden rounded-xl bg-gray-100 mb-3 relative">
                                    <a href="product-detail.html"><img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=400&h=533&fit=crop" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" /></a>
                                    <button class="absolute top-2 right-2 p-1.5 rounded-full bg-white/80 text-primary hover:bg-white transition-colors">
                                        <span class="material-symbols-outlined filled text-[20px]" style="font-variation-settings: 'FILL' 1;">favorite</span>
                                    </button>
                                </div>
                                <div>
                                    <a href="product-detail.html" class="text-sm font-bold text-text-main leading-tight group-hover:underline line-clamp-2">프리미엄 요가 레깅스</a>
                                    <div class="mt-2 flex items-center gap-2">
                                        <span class="text-sm font-extrabold text-text-main">54,000원</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h4 class="text-md font-bold text-gray-500 mb-4"><span class="material-symbols-outlined align-middle text-sm mr-1">cancel</span>[상태 2] 데이터가 없는 경우</h4>
                        <div class="flex flex-col items-center justify-center py-20 text-center border border-gray-100 rounded-xl bg-gray-50">
                            <span class="material-symbols-outlined text-5xl text-gray-300 mb-4">favorite</span>
                            <p class="text-text-muted font-medium text-lg">아직 찜한 상품이 없습니다.</p>
                            <a href="product-list.html" class="mt-4 px-6 py-2 bg-text-main text-white font-bold rounded-lg hover:bg-black transition-colors block w-fit mx-auto">상품 보러가기</a>
                        </div>
                    </div>
`
    },
    class_recent: {
        filename: "mypage-recent.html",
        title: "최근 본 상품",
        content: `
                    <div class="bg-white rounded-2xl shadow-sm border border-border-color p-6 sm:p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-bold text-text-main">최근 본 상품</h3>
                            <button class="text-sm border border-gray-300 text-text-main rounded px-3 py-1.5 hover:bg-gray-50 font-bold transition-colors">전체 삭제</button>
                        </div>
                        
                        <h4 class="text-md font-bold text-primary mb-4 mt-8"><span class="material-symbols-outlined align-middle text-sm mr-1">check_circle</span>[상태 1] 데이터가 있는 경우</h4>
                        <div class="mb-12 border-l-2 border-gray-100 pl-6 py-2 relative">
                            <div class="absolute left-[-5px] top-4 size-2 rounded-full bg-primary ring-4 ring-white"></div>
                            <span class="text-sm font-bold text-primary block mb-4">오늘</span>
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                                <!-- Product -->
                                <div class="group relative">
                                    <div class="aspect-[3/4] overflow-hidden rounded-xl bg-gray-100 mb-3 relative">
                                        <a href="product-detail.html"><img src="https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?w=400&h=533&fit=crop" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" /></a>
                                    </div>
                                    <div>
                                        <a href="product-detail.html" class="text-sm font-bold text-text-main leading-tight group-hover:underline line-clamp-2">에어 컴포트 스포츠 브라탑</a>
                                        <div class="mt-2 flex items-center gap-2">
                                            <span class="text-sm font-extrabold text-text-main">39,000원</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h4 class="text-md font-bold text-gray-500 mb-4"><span class="material-symbols-outlined align-middle text-sm mr-1">cancel</span>[상태 2] 데이터가 없는 경우</h4>
                        <div class="flex flex-col items-center justify-center py-20 text-center border border-gray-100 rounded-xl bg-gray-50">
                            <span class="material-symbols-outlined text-5xl text-gray-300 mb-4">visibility_off</span>
                            <p class="text-text-muted font-medium text-lg">최근 본 상품이 없습니다.</p>
                        </div>
                    </div>
`
    },
    class_inquiry: {
        filename: "mypage-inquiry.html",
        title: "1:1 문의내역",
        content: `
                    <div class="bg-white rounded-2xl shadow-sm border border-border-color p-6 sm:p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-bold text-text-main">1:1 문의내역</h3>
                            <button onclick="openInquiryModal()" class="px-5 py-2.5 bg-primary text-white font-bold rounded-lg hover:bg-red-600 transition-colors shadow-sm">문의하기</button>
                        </div>

                        <!-- [상태 1] 데이터가 있는 경우 -->
                        <div class="border-t border-gray-100 mt-2 mb-8">
                            <div class="border-b border-gray-50 inquiry-item">
                                <button onclick="toggleInquiry(this)" class="w-full py-5 flex flex-col sm:flex-row items-start sm:items-center justify-between hover:bg-gray-50 transition-colors px-2 group text-left">
                                    <div class="flex items-start sm:items-center gap-4">
                                        <span class="text-primary font-bold text-sm bg-primary-light px-2 py-1 rounded border border-primary/20 shrink-0 mt-1 sm:mt-0">답변대기</span>
                                        <div>
                                            <span class="text-sm font-bold text-text-main group-hover:text-primary transition-colors block mb-1">배송지 변경 관련해서 문의드립니다.</span>
                                            <p class="text-xs text-text-muted">안녕하세요, 어제 주문했는데 주소를 잘못 입력했어요...</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4 mt-2 sm:mt-0">
                                        <span class="text-xs text-gray-400 shrink-0">2026.03.06</span>
                                        <span class="material-symbols-outlined text-gray-400 transition-transform">expand_more</span>
                                    </div>
                                </button>
                                <div class="hidden bg-gray-50 px-6 py-6 border-t border-gray-100">
                                    <p class="text-sm text-text-muted leading-relaxed">안녕하세요. 어제 저녁에 주문한 김에스핏입니다. 배송지 주소를 확인해보니 아파트 동/호수가 잘못 기재되어 있어서요. 아직 배송 준비 중인 것 같은데 수정이 가능할까요?</p>
                                </div>
                            </div>
                            <!-- 추가 데이터 9개 (간략화) -->
                            ${Array.from({length: 9}).map((_, i) => `
                            <div class="border-b border-gray-50 inquiry-item">
                                <button onclick="toggleInquiry(this)" class="w-full py-5 flex flex-col sm:flex-row items-start sm:items-center justify-between hover:bg-gray-50 transition-colors px-2 group text-left">
                                    <div class="flex items-start sm:items-center gap-4">
                                        <span class="text-gray-500 font-bold text-sm bg-gray-100 px-2 py-1 rounded border border-gray-200 shrink-0 mt-1 sm:mt-0">답변완료</span>
                                        <div><span class="text-sm font-bold text-text-main group-hover:text-primary transition-colors block mb-1">샘플 문의 내역 ${10-i-1}</span><p class="text-xs text-text-muted">이것은 자동 생성된 샘플 데이터입니다.</p></div>
                                    </div>
                                    <div class="flex items-center gap-4 mt-2 sm:mt-0"><span class="text-xs text-gray-400 shrink-0">2026.02.${20-i}</span><span class="material-symbols-outlined text-gray-400">expand_more</span></div>
                                </button>
                                <div class="hidden bg-gray-50 px-6 py-6 border-t border-gray-100"><p class="text-sm text-text-main">문의하신 내용에 대한 답변이 완료되었습니다. 감사합니다.</p></div>
                            </div>`).join('')}
                        </div>

                        <!-- [상태 2] 데이터가 없는 경우 -->
                        <div class="mt-8 pt-8 border-t border-gray-100">
                            <div class="flex flex-col items-center justify-center py-16 text-center border border-gray-100 rounded-xl bg-gray-50">
                                <span class="material-symbols-outlined text-4xl text-gray-300 mb-3">chat_bubble</span>
                                <p class="text-text-muted font-medium">1:1 문의 내역이 없습니다.</p>
                            </div>
                        </div>
                    </div>

                    <script>
                        function toggleInquiry(button) {
                            const content = button.nextElementSibling;
                            const icon = button.querySelector('.material-symbols-outlined:last-child');
                            content.classList.toggle('hidden');
                            icon.style.transform = content.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
                        }
                        function openInquiryModal() { alert('문의하기 모달이 열립니다! ✨'); }
                    </script>
`
    },
    class_review: {
        filename: "mypage-review.html",
        title: "상품 리뷰 관리",
        content: `
                    <div class="bg-white rounded-2xl shadow-sm border border-border-color p-6 sm:p-8">
                        <h3 class="text-xl font-bold text-text-main mb-6">상품 리뷰 관리</h3>
                        
                        <!-- Tabs -->
                        <div class="flex border-b border-gray-200 mb-8 relative">
                            <button id="tabBtnAvailable" onclick="switchTab('available')" class="pb-3 px-6 text-sm font-bold border-b-2 border-primary text-primary transition-colors">작성 가능한 리뷰 <span class="badge ml-1 bg-primary text-white text-[10px] px-1.5 py-0.5 rounded-full inline-flex leading-none align-middle items-center justify-center">10</span></button>
                            <button id="tabBtnWritten" onclick="switchTab('written')" class="pb-3 px-6 text-sm font-medium border-b-2 border-transparent text-text-muted hover:text-text-main transition-colors">내가 작성한 리뷰 <span class="badge ml-1 bg-gray-300 text-white text-[10px] px-1.5 py-0.5 rounded-full inline-flex leading-none align-middle items-center justify-center">10</span></button>
                        </div>

                        <!-- 작성 가능한 리뷰 -->
                        <div id="tabAvailable" class="space-y-4">
                            ${Array.from({length: 10}).map((_, i) => `
                            <div class="flex flex-col sm:flex-row gap-6 p-5 border border-gray-100 rounded-xl hover:shadow-md transition-shadow bg-white">
                                <div class="size-20 bg-gray-100 rounded-lg overflow-hidden shrink-0"><img src="https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?w=200" class="w-full h-full object-cover"></div>
                                <div class="flex flex-col justify-center flex-1">
                                    <p class="text-xs font-bold text-primary mb-1">배송완료</p>
                                    <h4 class="text-base font-bold text-text-main">샘플 상품 ${i+1}</h4>
                                    <p class="text-xs text-text-muted">리뷰 작성 시 적립금 500원!</p>
                                </div>
                                <div class="flex items-center"><button onclick="openReviewModal()" class="w-full sm:w-auto px-6 py-2 bg-text-main text-white text-sm font-bold rounded-lg hover:bg-black transition-colors">리뷰 작성</button></div>
                            </div>`).join('')}
                        </div>

                        <!-- 내가 작성한 리뷰 (초기 숨김) -->
                        <div id="tabWritten" class="space-y-4 hidden">
                            ${Array.from({length: 10}).map((_, i) => `
                            <div class="p-6 border border-gray-100 rounded-xl bg-gray-50">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-sm font-bold text-text-main">내가 쓴 리뷰 ${i+1}</span>
                                    <div class="flex text-yellow-400 text-xs">★★★★★</div>
                                </div>
                                <p class="text-sm text-text-main leading-relaxed">정말 만족스러운 상품이에요! 우리 자기한테도 추천해주고 싶네요~ 💖</p>
                                <div class="mt-4 flex justify-end gap-2">
                                    <button class="text-xs text-gray-400 hover:text-primary">수정</button>
                                    <span class="text-gray-200">|</span>
                                    <button class="text-xs text-gray-400 hover:text-red-500">삭제</button>
                                </div>
                            </div>`).join('')}
                        </div>
                    </div>

                    <script>
                        function switchTab(tab) {
                            const available = document.getElementById('tabAvailable');
                            const written = document.getElementById('tabWritten');
                            const btnAvail = document.getElementById('tabBtnAvailable');
                            const btnWritten = document.getElementById('tabBtnWritten');
                            
                            if (tab === 'available') {
                                available.classList.remove('hidden');
                                written.classList.add('hidden');
                                btnAvail.classList.add('border-primary', 'text-primary');
                                btnWritten.classList.remove('border-primary', 'text-primary');
                            } else {
                                available.classList.add('hidden');
                                written.classList.remove('hidden');
                                btnAvail.classList.remove('border-primary', 'text-primary');
                                btnWritten.classList.add('border-primary', 'text-primary');
                            }
                        }
                        function openReviewModal() { alert('리뷰 작성 모달이 짠! 하고 나타납니다 ✨'); }
                    </script>
`
    },
    class_profile: {
        filename: "mypage-profile.html",
        title: "회원정보 수정",
        content: `
                    <div class="bg-white rounded-2xl shadow-sm border border-border-color p-6 sm:p-8 lg:p-12 mb-8 max-w-3xl mx-auto">
                        <h4 class="text-md font-bold text-primary mb-8 px-4 py-2 border border-primary/20 bg-primary-light/30 rounded inline-block"><span class="material-symbols-outlined align-middle text-sm mr-1">check_circle</span>폼 제출 페이지는 단일 구성</h4>
                        
                        <div class="text-center mb-10">
                            <span class="material-symbols-outlined text-5xl text-primary mb-4">lock</span>
                            <h3 class="text-2xl font-extrabold text-text-main mb-2">비밀번호 재확인</h3>
                            <p class="text-text-muted text-sm">고객님의 소중한 개인정보를 보호하기 위해 비밀번호를 다시 확인합니다.</p>
                        </div>
                        
                        <div class="space-y-6 text-left">
                            <div>
                                <label class="block text-sm font-bold text-text-main mb-2">이메일(아이디)</label>
                                <input type="text" value="s*f*t@gmail.com" disabled class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-500 text-sm focus:ring-0">
                            </div>
                            <div>
                                <label for="password" class="block text-sm font-bold text-text-main mb-2">비밀번호</label>
                                <input type="password" id="password" placeholder="비밀번호를 입력해주세요" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-text-main text-sm focus:ring-primary focus:border-primary">
                            </div>
                            <button onclick="location.href='mypage-profile-edit.html'" class="w-full py-4 bg-text-main text-white font-bold rounded-xl hover:bg-black transition-colors mt-2">확인</button>
                        </div>
                        
                        <div class="mt-8 pt-8 border-t border-gray-100 flex justify-end">
                            <a href="mypage-withdraw.html" class="text-xs text-gray-400 hover:text-gray-600 underline font-medium">회원 탈퇴를 원하시나요?</a>
                        </div>
                    </div>
                    </div>
`
    },
    class_profile_edit: {
        filename: "mypage-profile-edit.html",
        title: "회원정보 수정",
        content: `
                    <div class="bg-white rounded-2xl shadow-sm border border-border-color p-6 sm:p-8 lg:p-12 mb-8 max-w-3xl mx-auto">
                        <div class="border-b border-gray-100 pb-6 mb-8 flex justify-between items-center">
                            <h3 class="text-xl font-bold text-text-main">회원정보 수정</h3>
                            <span class="text-xs text-primary font-bold"><span class="text-primary">*</span> 필수입력</span>
                        </div>
                        
                        <div class="space-y-8">
                            <!-- Basic Info -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 items-start">
                                <label class="text-sm font-bold text-text-main sm:mt-3">이메일(아이디)</label>
                                <div class="sm:col-span-2">
                                    <input type="text" value="s*f*t@gmail.com" disabled class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-500 text-sm focus:ring-0">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 items-start">
                                <label class="text-sm font-bold text-text-main sm:mt-3">이름</label>
                                <div class="sm:col-span-2">
                                    <input type="text" value="김에스핏" disabled class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-500 text-sm focus:ring-0">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 items-start border-t border-gray-50 pt-8">
                                <label class="text-sm font-bold text-text-main sm:mt-3">새 비밀번호</label>
                                <div class="sm:col-span-2 space-y-2">
                                    <input type="password" placeholder="영문, 숫자, 특수문자 조합 8-16자" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-text-main text-sm focus:ring-primary focus:border-primary">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 items-start">
                                <label class="text-sm font-bold text-text-main sm:mt-3">새 비밀번호 확인</label>
                                <div class="sm:col-span-2">
                                    <input type="password" placeholder="비밀번호를 한번 더 입력해주세요" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-text-main text-sm focus:ring-primary focus:border-primary">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 items-start border-t border-gray-50 pt-8">
                                <label class="text-sm font-bold text-text-main sm:mt-3">휴대폰 번호 <span class="text-primary">*</span></label>
                                <div class="sm:col-span-2 flex gap-2">
                                    <input type="text" value="010-1234-5678" class="flex-1 bg-white border border-gray-300 rounded-xl px-4 py-3 text-text-main text-sm focus:ring-primary focus:border-primary">
                                    <button class="px-6 py-3 bg-white border border-gray-300 text-text-main font-bold rounded-xl hover:bg-gray-50 transition-colors whitespace-nowrap text-sm">인증변경</button>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 items-start border-t border-gray-50 pt-8">
                                <label class="text-sm font-bold text-text-main sm:mt-3">기본 배송지</label>
                                <div class="sm:col-span-2 space-y-3">
                                    <div class="flex gap-2">
                                        <input type="text" value="06236" placeholder="우편번호" readonly class="w-32 bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-gray-600 text-sm focus:ring-0 cursor-default">
                                        <button class="px-6 py-3 bg-white border border-gray-300 text-text-main font-bold rounded-xl hover:bg-gray-50 transition-colors whitespace-nowrap text-sm">우편번호 찾기</button>
                                    </div>
                                    <input type="text" value="서울특별시 강남구 테헤란로 123" placeholder="기본 주소" readonly class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-gray-600 text-sm focus:ring-0 cursor-default">
                                    <input type="text" value="액티브 빌딩 4층" placeholder="상세 주소를 입력해주세요" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-text-main text-sm focus:ring-primary focus:border-primary">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 items-start pt-8 border-t border-gray-50">
                                <div class="sm:mt-1">
                                    <label class="text-sm font-bold text-text-main">마케팅 수신동의</label>
                                    <p class="text-xs text-text-muted mt-1">다양한 이벤트 및 혜택 안내</p>
                                </div>
                                <div class="sm:col-span-2 flex items-center gap-6 mt-1 sm:mt-2 text-sm">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" checked class="rounded text-primary focus:ring-primary w-5 h-5 border-gray-300">
                                        <span class="font-medium text-text-main">SMS</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" checked class="rounded text-primary focus:ring-primary w-5 h-5 border-gray-300">
                                        <span class="font-medium text-text-main">이메일</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex gap-4 mt-12">
                            <button onclick="history.back()" class="flex-1 py-4 bg-white border border-gray-300 text-text-main font-bold rounded-xl hover:bg-gray-50 transition-colors">취소</button>
                            <button onclick="alert('회원정보가 성공적으로 수정되었습니다.'); location.href='mypage-profile.html'" class="flex-1 py-4 bg-text-main text-white font-bold rounded-xl hover:bg-black transition-colors">수정완료</button>
                        </div>
                    </div>
`
    },
    class_withdraw: {
        filename: "mypage-withdraw.html",
        title: "회원 탈퇴",
        content: `
                    <div class="bg-white rounded-2xl shadow-sm border border-border-color p-6 sm:p-8 lg:p-12 mb-8 max-w-3xl mx-auto">
                        <div class="text-center mb-10">
                            <span class="material-symbols-outlined text-5xl text-gray-300 mb-4">sentiment_dissatisfied</span>
                            <h3 class="text-2xl font-extrabold text-text-main mb-2">회원 탈퇴 대기</h3>
                            <p class="text-text-muted text-sm leading-relaxed">액티브 우먼을 이용하시는 동안 불편한 점이 있으셨나요?<br>탈퇴하기 전 아래 유의사항을 반드시 확인해주세요.</p>
                        </div>
                        
                        <div class="bg-gray-50 rounded-xl p-6 mb-8 text-sm text-text-muted leading-relaxed">
                            <h4 class="font-bold text-text-main mb-3 flex items-center gap-1"><span class="material-symbols-outlined text-sm text-primary">warning</span>탈퇴 시 유의사항</h4>
                            <ul class="list-disc pl-5 space-y-2">
                                <li>탈퇴 시 보유하고 계신 쿠폰 및 적립금은 모두 소멸되며 복구가 불가능합니다. (현재 보유 적립금: <strong class="text-primary text-base">12,500원</strong> / 쿠폰: <strong class="text-primary text-base">2장</strong>)</li>
                                <li>주문내역 및 1:1 문의 등 관련 데이터는 개인정보처리방침에 따라 파기 또는 일정 기간 보관 후 파기됩니다.</li>
                                <li>동일한 이메일 등 개인정보로 재가입 시 신규 혜택은 다시 제공되지 않습니다.</li>
                                <li>현재 진행 중인 배송, 교환, 환불 건이 있는 경우 탈퇴가 불가능합니다.</li>
                            </ul>
                        </div>
                        
                        <div class="border-t border-gray-100 pt-8 mb-10">
                            <p class="text-sm font-bold text-text-main mb-4">무엇이 불편하셨나요? (선택)</p>
                            <div class="flex flex-col gap-3">
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="radio" name="reason" class="text-primary focus:ring-primary w-5 h-5 border-gray-300">
                                    <span class="text-sm text-text-main group-hover:text-primary transition-colors">상품 종류가 부족함</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="radio" name="reason" class="text-primary focus:ring-primary w-5 h-5 border-gray-300">
                                    <span class="text-sm text-text-main group-hover:text-primary transition-colors">가격 혜택이 부족함</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="radio" name="reason" class="text-primary focus:ring-primary w-5 h-5 border-gray-300">
                                    <span class="text-sm text-text-main group-hover:text-primary transition-colors">방문 빈도가 낮음</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="radio" name="reason" class="text-primary focus:ring-primary w-5 h-5 border-gray-300">
                                    <span class="text-sm text-text-main group-hover:text-primary transition-colors">기타 사유</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3 mb-10 bg-primary-light/50 p-4 rounded-xl border border-primary/20">
                            <input type="checkbox" id="agree" class="rounded text-primary focus:ring-primary w-5 h-5 border-primary/30">
                            <label for="agree" class="text-sm font-bold text-primary cursor-pointer">안내사항을 모두 확인하였으며, 이에 동의합니다.</label>
                        </div>
                        
                        <div class="flex gap-4">
                            <button onclick="history.back()" class="flex-1 py-4 bg-text-main text-white font-bold rounded-xl hover:bg-black transition-colors">계속 이용하기</button>
                            <button onclick="alert('정상적으로 회원 탈퇴가 처리되었습니다. 그동안 이용해주셔서 감사합니다.'); location.href='index.html'" class="flex-1 py-4 bg-white border border-gray-300 text-text-main font-bold rounded-xl hover:bg-gray-50 transition-colors">탈퇴하기</button>
                        </div>
                    </div>
`
    }
};

const inertClass = "text-sm font-medium text-text-main hover:text-primary transition-colors";

for (const [pageKey, data] of Object.entries(pages)) {
    let classes = {};
    for (const k of Object.keys(pages)) {
        classes[k] = inertClass;
    }

    if (pageKey === 'class_profile_edit' || pageKey === 'class_withdraw') {
        classes['class_profile'] = "text-sm font-bold text-primary hover:underline";
    } else {
        classes[pageKey] = "text-sm font-bold text-primary hover:underline";
    }

    let currentAside = asideTemplate;
    for (const [k, v] of Object.entries(classes)) {
        currentAside = currentAside.replace(`{${k}}`, v);
    }

    const contentArea = `<!-- Main Dashboard Content -->\n<div class="flex-1 w-full space-y-8">\n${data.content}\n</div>\n`;

    let fullHtml = headerHtml + currentAside + "\n\n" + contentArea + footerHtml;
    fullHtml = fullHtml.replace('<title>마이페이지', `<title>${data.title} | 마이페이지`);

    const outPath = path.join(baseDir, data.filename);
    fs.writeFileSync(outPath, fullHtml, 'utf8');
}

let classesAllInactive = {};
for (const k of Object.keys(pages)) {
    classesAllInactive[k] = inertClass;
}

let emptyAside = asideTemplate;
for (const [k, v] of Object.entries(classesAllInactive)) {
    emptyAside = emptyAside.replace(`{${k}}`, v);
}

const mypageFinal = headerHtml + emptyAside + "\n\n" + dashboardHtml + footerHtml;
fs.writeFileSync(mypagePath, mypageFinal, 'utf8');

console.log("Generated 12 Mypage sub-pages with empty/data states via Node js!");
