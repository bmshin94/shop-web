import os

base_dir = r"d:\project\shop-web"
mypage_path = os.path.join(base_dir, "mypage.html")

with open(mypage_path, "r", encoding="utf-8") as f:
    template = f.read()

aside_start = template.find('<!-- LNB (Left Navigation Bar) -->')
main_content_start = template.find('<!-- Main Dashboard Content -->')
main_content_end = template.find('</main>')

header_html = template[:aside_start]
footer_html = template[main_content_end:]
dashboard_html = template[main_content_start:main_content_end]

aside_template = """<!-- LNB (Left Navigation Bar) -->
                <aside class="w-full lg:w-64 shrink-0 bg-white rounded-2xl shadow-sm border border-border-color p-6 sticky top-32">
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
"""

pages = {
    "class_order": {
        "filename": "mypage-order-list.html",
        "title": "주문/배송 조회",
        "content": """
                    <div class="bg-white rounded-2xl shadow-sm border border-border-color p-6 sm:p-8">
                        <h3 class="text-xl font-bold text-text-main mb-6">주문/배송 조회</h3>
                        <div class="flex flex-wrap gap-2 mb-6">
                            <button class="px-4 py-2 text-sm font-bold rounded-lg border border-primary text-primary bg-primary-light transition-colors">1개월</button>
                            <button class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 text-text-main hover:bg-gray-50 transition-colors">3개월</button>
                            <button class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 text-text-main hover:bg-gray-50 transition-colors">6개월</button>
                            <button class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 text-text-main hover:bg-gray-50 transition-colors">12개월</button>
                        </div>
                        <div class="flex flex-col items-center justify-center py-20 text-center border-t border-gray-100">
                            <span class="material-symbols-outlined text-5xl text-gray-300 mb-4">inventory_2</span>
                            <p class="text-text-muted font-medium text-lg">조회 기간 동안 주문한 내역이 없습니다.</p>
                        </div>
                    </div>
"""
    },
    "class_claim": {
        "filename": "mypage-claim-list.html",
        "title": "취소/반품/교환 내역",
        "content": """
                    <div class="bg-white rounded-2xl shadow-sm border border-border-color p-6 sm:p-8">
                        <h3 class="text-xl font-bold text-text-main mb-6">취소/반품/교환 내역</h3>
                        <p class="text-sm text-text-muted mb-6 bg-gray-50 p-4 rounded-xl">최근 3개월 간의 클레임 내역입니다. 이전 내역은 기간 검색을 이용해주세요.</p>
                        <div class="flex flex-col items-center justify-center py-16 text-center border-t border-gray-100">
                            <p class="text-text-muted font-medium">취소/반품/교환 신청 내역이 없습니다.</p>
                        </div>
                    </div>
"""
    },
    "class_refund": {
        "filename": "mypage-refund-list.html",
        "title": "환불/입금 내역",
        "content": """
                    <div class="bg-white rounded-2xl shadow-sm border border-border-color p-6 sm:p-8">
                        <h3 class="text-xl font-bold text-text-main mb-6">환불/입금 내역</h3>
                        <div class="overflow-x-auto rounded-xl border border-gray-100">
                            <table class="w-full text-left border-collapse min-w-[500px]">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-100 text-sm font-bold text-text-main">
                                        <th class="py-4 px-6 text-center">접수일자</th>
                                        <th class="py-4 px-6 text-center">유형</th>
                                        <th class="py-4 px-6">내용</th>
                                        <th class="py-4 px-6 text-center">금액</th>
                                        <th class="py-4 px-6 text-center">상태</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="5" class="py-12 text-center text-text-muted font-medium">해당하는 환불/입금 내역이 존재하지 않습니다.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
"""
    },
    "class_receipt": {
        "filename": "mypage-receipt.html",
        "title": "영수증/계산서 발급",
        "content": """
                    <div class="bg-white rounded-2xl shadow-sm border border-border-color p-6 sm:p-8">
                        <h3 class="text-xl font-bold text-text-main mb-6">영수증/계산서 발급</h3>
                        <p class="text-sm text-text-muted mb-6 bg-gray-50 p-4 rounded-xl">신용카드 매출전표, 현금영수증, 세금계산서 발급 내역을 확인하고 인쇄할 수 있습니다.</p>
                        <div class="flex flex-col items-center justify-center py-12 text-center border border-gray-100 rounded-xl">
                            <span class="material-symbols-outlined text-4xl text-gray-300 mb-3">receipt_long</span>
                            <p class="text-text-muted font-medium">발급 가능한 증빙 서류 내역이 없습니다.</p>
                        </div>
                    </div>
"""
    },
    "class_coupon": {
        "filename": "mypage-coupon.html",
        "title": "쿠폰",
        "content": """
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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
"""
    },
    "class_point": {
        "filename": "mypage-point.html",
        "title": "적립금",
        "content": """
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
                    <div class="bg-white rounded-2xl shadow-sm border border-border-color p-6 sm:p-8">
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
"""
    },
    "class_deposit": {
        "filename": "mypage-deposit.html",
        "title": "예치금 내역",
        "content": """
                    <div class="bg-white rounded-2xl shadow-sm border border-border-color p-6 sm:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-text-main">예치금 조회</h3>
                            <p class="text-3xl font-extrabold text-primary">0<span class="text-lg text-text-main font-bold ml-1">원</span></p>
                        </div>
                        <p class="text-sm text-text-muted mb-8 bg-gray-50 p-4 rounded-xl">예치금은 고객님의 계좌로 언제든지 환불이 가능합니다. 예치금 환불은 1:1 문의를 통해 신청해주세요.</p>
                        
                        <div class="border-t border-gray-100 pt-8 text-center">
                            <p class="text-text-muted font-medium mb-4">발생한 예치금 내역이 없습니다.</p>
                        </div>
                    </div>
"""
    },
    "class_wishlist": {
        "filename": "mypage-wishlist.html",
        "title": "찜한 상품",
        "content": """
                    <div class="bg-white rounded-2xl shadow-sm border border-border-color p-6 sm:p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-bold text-text-main">찜한 상품</h3>
                            <button class="text-sm border border-gray-300 text-text-main rounded px-3 py-1.5 hover:bg-gray-50 font-bold transition-colors">선택 삭제</button>
                        </div>
                        <div class="flex flex-col items-center justify-center py-20 text-center border-t border-gray-100">
                            <span class="material-symbols-outlined text-5xl text-gray-300 mb-4">favorite</span>
                            <p class="text-text-muted font-medium text-lg">아직 찜한 상품이 없습니다.</p>
                            <a href="product-list.html" class="mt-4 px-6 py-2 bg-text-main text-white font-bold rounded-lg hover:bg-black transition-colors block w-fit mx-auto">상품 보러가기</a>
                        </div>
                    </div>
"""
    },
    "class_recent": {
        "filename": "mypage-recent.html",
        "title": "최근 본 상품",
        "content": """
                    <div class="bg-white rounded-2xl shadow-sm border border-border-color p-6 sm:p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-bold text-text-main">최근 본 상품</h3>
                            <button class="text-sm border border-gray-300 text-text-main rounded px-3 py-1.5 hover:bg-gray-50 font-bold transition-colors">전체 삭제</button>
                        </div>
                        <div class="flex flex-col items-center justify-center py-20 text-center border-t border-gray-100">
                            <span class="material-symbols-outlined text-5xl text-gray-300 mb-4">visibility_off</span>
                            <p class="text-text-muted font-medium text-lg">최근 본 상품이 없습니다.</p>
                        </div>
                    </div>
"""
    },
    "class_inquiry": {
        "filename": "mypage-inquiry.html",
        "title": "1:1 문의내역",
        "content": """
                    <div class="bg-white rounded-2xl shadow-sm border border-border-color p-6 sm:p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-bold text-text-main">1:1 문의내역</h3>
                            <button class="px-5 py-2.5 bg-primary text-white font-bold rounded-lg hover:bg-red-600 transition-colors shadow-sm">문의하기</button>
                        </div>
                        <div class="border-t border-gray-100 mt-2">
                            <a href="#" class="py-5 border-b border-gray-50 flex flex-col sm:flex-row items-start sm:items-center justify-between hover:bg-gray-50 transition-colors px-2 group">
                                <div class="flex items-start sm:items-center gap-4">
                                    <span class="text-primary font-bold text-sm bg-primary-light px-2 py-1 rounded border border-primary/20 shrink-0 mt-1 sm:mt-0">답변대기</span>
                                    <div>
                                        <span class="text-sm font-bold text-text-main group-hover:text-primary transition-colors block mb-1">배송지 변경 관련해서 문의드립니다.</span>
                                        <p class="text-xs text-text-muted">안녕하세요, 어제 주문했는데 주소를 잘못 입력했어요...</p>
                                    </div>
                                </div>
                                <span class="text-xs text-gray-400 mt-2 sm:mt-0 shrink-0">2026.03.02</span>
                            </a>
                            <a href="#" class="py-5 border-b border-gray-50 flex flex-col sm:flex-row items-start sm:items-center justify-between hover:bg-gray-50 transition-colors px-2 group">
                                <div class="flex items-start sm:items-center gap-4">
                                    <span class="text-gray-500 font-bold text-sm bg-gray-100 px-2 py-1 rounded border border-gray-200 shrink-0 mt-1 sm:mt-0">답변완료</span>
                                    <div>
                                        <span class="text-sm font-bold text-text-main group-hover:text-primary transition-colors block mb-1 text-gray-500">품절 상품 재입고 일정이 어떻게 되나요?</span>
                                    </div>
                                </div>
                                <span class="text-xs text-gray-400 mt-2 sm:mt-0 shrink-0">2026.02.20</span>
                            </a>
                        </div>
                        <div class="flex justify-center mt-8 gap-1">
                            <button class="size-8 flex items-center justify-center rounded border border-border-color text-text-main hover:bg-gray-50 font-bold text-sm shadow-sm bg-white">1</button>
                        </div>
                    </div>
"""
    },
    "class_review": {
        "filename": "mypage-review.html",
        "title": "상품 리뷰 관리",
        "content": """
                    <div class="bg-white rounded-2xl shadow-sm border border-border-color p-6 sm:p-8">
                        <h3 class="text-xl font-bold text-text-main mb-6">상품 리뷰 관리</h3>
                        
                        <!-- Tabs -->
                        <div class="flex border-b border-gray-200 mb-8">
                            <button class="pb-3 px-6 text-sm font-bold border-b-2 border-primary text-primary transition-colors">작성 가능한 리뷰 <span class="ml-1 bg-primary text-white text-[10px] px-1.5 py-0.5 rounded-full">1</span></button>
                            <button class="pb-3 px-6 text-sm font-medium border-b-2 border-transparent text-text-muted hover:text-text-main transition-colors">내가 작성한 리뷰</button>
                        </div>

                        <!-- Review Item -->
                        <div class="flex flex-col sm:flex-row gap-6 p-6 border border-gray-100 rounded-xl hover:shadow-md transition-shadow bg-white">
                            <div class="size-24 bg-gray-100 rounded-lg overflow-hidden shrink-0">
                                <img src="https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?w=200&h=200&fit=crop" alt="상품 이미지" class="w-full h-full object-cover">
                            </div>
                            <div class="flex flex-col justify-center flex-1">
                                <p class="text-xs font-bold text-primary mb-1">배송완료 (2026.02.15)</p>
                                <a href="#" class="text-lg font-bold text-text-main hover:text-primary transition-colors mb-2">에어 컴포트 스포츠 브라탑</a>
                                <p class="text-sm text-text-muted">리뷰를 작성하시고 적립금 500원을 받아가세요!</p>
                            </div>
                            <div class="flex items-center">
                                <button class="px-6 py-3 bg-text-main text-white font-bold rounded-xl hover:bg-black transition-colors min-w-[120px]">리뷰 작성</button>
                            </div>
                        </div>
                    </div>
"""
    },
    "class_profile": {
        "filename": "mypage-profile.html",
        "title": "회원정보 수정",
        "content": """
                    <div class="bg-white rounded-2xl shadow-sm border border-border-color p-6 sm:p-8 lg:p-12 mb-8 max-w-3xl mx-auto">
                        <div class="text-center mb-10">
                            <span class="material-symbols-outlined text-5xl text-primary mb-4">lock</span>
                            <h3 class="text-2xl font-extrabold text-text-main mb-2">비밀번호 재확인</h3>
                            <p class="text-text-muted text-sm">고객님의 소중한 개인정보를 보호하기 위해 비밀번호를 다시 확인합니다.</p>
                        </div>
                        
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-bold text-text-main mb-2">이메일(아이디)</label>
                                <input type="text" value="s*f*t@gmail.com" disabled class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-500 text-sm focus:ring-0">
                            </div>
                            <div>
                                <label for="password" class="block text-sm font-bold text-text-main mb-2">비밀번호</label>
                                <input type="password" id="password" placeholder="비밀번호를 입력해주세요" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-text-main text-sm focus:ring-primary focus:border-primary">
                            </div>
                            <button class="w-full py-4 bg-text-main text-white font-bold rounded-xl hover:bg-black transition-colors mt-2">확인</button>
                        </div>
                        
                        <div class="mt-8 pt-8 border-t border-gray-100 flex justify-end">
                            <a href="#" class="text-xs text-gray-400 hover:text-gray-600 underline font-medium">회원 탈퇴를 원하시나요?</a>
                        </div>
                    </div>
"""
    }
}

for page_key, data in pages.items():
    classes = {k: "text-sm font-medium text-text-main hover:text-primary transition-colors" for k in pages.keys()}
    classes[page_key] = "text-sm font-bold text-primary hover:underline"
    
    current_aside = aside_template.format(**classes)
    
    content_area = "<!-- Main Dashboard Content -->\n<div class=\"flex-1 w-full space-y-8\">\n" + data["content"] + "\n</div>\n"
    
    full_html = header_html + current_aside + "\n\n" + content_area + footer_html
    
    full_html = full_html.replace("<title>마이페이지", f"<title>{data['title']} | 마이페이지")
    
    out_path = os.path.join(base_dir, data["filename"])
    with open(out_path, "w", encoding="utf-8") as out_f:
        out_f.write(full_html)

classes_all_inactive = {k: "text-sm font-medium text-text-main hover:text-primary transition-colors" for k in pages.keys()}
empty_aside = aside_template.format(**classes_all_inactive)
mypage_final = header_html + empty_aside + "\n\n" + dashboard_html + footer_html

with open(mypage_path, "w", encoding="utf-8") as my_f:
    my_f.write(mypage_final)

print("Generated 12 Mypage sub-pages and updated LNBs successfully!")
