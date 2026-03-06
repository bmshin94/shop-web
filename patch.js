const fs = require('fs');
let code = fs.readFileSync('build_mypage.js', 'utf8');

// 1. 버튼 & 탈퇴 링크 경로 변경
code = code.replace(
    '<button class="w-full py-4 bg-text-main text-white font-bold rounded-xl hover:bg-black transition-colors mt-2">확인</button>',
    `<button onclick="location.href='mypage-profile-edit.html'" class="w-full py-4 bg-text-main text-white font-bold rounded-xl hover:bg-black transition-colors mt-2">확인</button>`
);

code = code.replace(
    '<a href="#" class="text-xs text-gray-400 hover:text-gray-600 underline font-medium">회원 탈퇴를 원하시나요?</a>',
    `<a href="mypage-withdraw.html" class="text-xs text-gray-400 hover:text-gray-600 underline font-medium">회원 탈퇴를 원하시나요?</a>`
);

// 2. 신규 페이지 추가
const newPagesStr = `    },
    class_profile_edit: {
        filename: "mypage-profile-edit.html",
        title: "회원정보 수정",
        content: \`
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
                            <button onclick="alert('회원정보가 성공적으로 수정되었습니다.'); location.href='mypage.html'" class="flex-1 py-4 bg-text-main text-white font-bold rounded-xl hover:bg-black transition-colors">수정완료</button>
                        </div>
                    </div>
\`
    },
    class_withdraw: {
        filename: "mypage-withdraw.html",
        title: "회원 탈퇴",
        content: \`
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
\`
`;

const insertIdx = code.indexOf('\\n    }\\n};');
if (insertIdx !== -1) {
    code = code.substring(0, insertIdx) + newPagesStr + code.substring(insertIdx);
} else {
    // try different newline strategy
    const insertIdx2 = code.indexOf('\n    }\n};');
    if (insertIdx2 !== -1) code = code.substring(0, insertIdx2) + newPagesStr + code.substring(insertIdx2);
}

// 3. LNB 활성화 클래스 처리 (class_profile_edit, class_withdraw 처리)
code = code.replace(
    /classes\[pageKey\] = \"text-sm font-bold text-primary hover:underline\";/g,
    `if (pageKey === 'class_profile_edit' || pageKey === 'class_withdraw') {
        classes['class_profile'] = "text-sm font-bold text-primary hover:underline";
    } else {
        classes[pageKey] = "text-sm font-bold text-primary hover:underline";
    }`
);

fs.writeFileSync('build_mypage.js', code, 'utf8');
console.log("Patch applied correctly!");
