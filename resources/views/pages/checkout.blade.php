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
            @include('pages.checkout.orderer-info')

            <!-- 배송 정보 -->
            @include('pages.checkout.shipping-info')

            <!-- 결제 수단 -->
            @include('pages.checkout.payment-methods')
          </div>

          <!-- 결제 요약 측면 영역 -->
          <div class="w-full lg:w-[400px] flex-shrink-0">
            @include('pages.checkout.order-summary')
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

    <!-- Postcode Modal (기존 유지) -->
    ...
    </div>

    <!-- Shipping Address Selection Modal -->
    <div id="addressListModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden transform scale-95 transition-transform duration-300" id="addressListModalContent">
            <div class="flex items-center justify-between p-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-bold text-text-main flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">list_alt</span> 배송지 선택
                </h3>
                <button onclick="closeAddressListModal()" class="text-gray-400 hover:text-text-main transition-colors rounded-full p-1 hover:bg-gray-100">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="p-6 space-y-4 max-h-[60vh] overflow-y-auto scrollbar-hide">
                @forelse($addresses as $addr)
                <div class="address-select-item p-5 border border-gray-200 rounded-2xl cursor-pointer hover:border-primary hover:bg-primary/5 transition-all group" 
                    data-name="{{ $addr->recipient_name }}" 
                    data-phone="{{ $addr->phone_number }}" 
                    data-zip="{{ $addr->zip_code }}" 
                    data-addr="{{ $addr->address }}" 
                    data-detail="{{ $addr->address_detail }}">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-black text-text-main bg-gray-100 px-2 py-0.5 rounded">{{ $addr->address_name }}</span>
                            @if($addr->is_default)
                            <span class="text-[11px] font-black text-primary bg-primary/10 px-2 py-0.5 rounded border border-primary/20">기본</span>
                            @endif
                        </div>
                        <span class="material-symbols-outlined text-gray-300 group-hover:text-primary transition-colors">check_circle</span>
                    </div>
                    <p class="text-base font-black text-text-main mb-1">{{ $addr->recipient_name }} <span class="text-sm font-medium text-text-muted">({{ $addr->phone_number }})</span></p>
                    <p class="text-xs font-bold text-text-muted">[{{ $addr->zip_code }}] {{ $addr->address }}</p>
                    <p class="text-xs font-bold text-text-muted">{{ $addr->address_detail }}</p>
                </div>
                @empty
                <div class="py-12 text-center">
                    <span class="material-symbols-outlined text-gray-200 text-5xl mb-3">location_off</span>
                    <p class="text-text-muted font-bold">등록된 배송지가 없습니다.</p>
                    <a href="{{ route('mypage.shipping-address') }}" class="inline-block mt-4 text-primary text-sm font-bold hover:underline">배송지 관리에서 등록하기</a>
                </div>
                @endforelse
            </div>
            <div class="p-5 border-t border-gray-100 bg-gray-50/50 flex gap-3">
                <a href="{{ route('mypage.shipping-address') }}" class="flex-1 py-3 bg-white border border-gray-200 text-text-main text-sm font-bold rounded-xl hover:bg-gray-100 transition-colors text-center">배송지 관리</a>
                <button onclick="closeAddressListModal()" class="flex-1 py-3 bg-gray-100 text-text-muted text-sm font-bold rounded-xl hover:bg-gray-200 transition-colors">닫기</button>
            </div>
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
    const storeCode = "{{ env('IAMPORT_STORE_CODE') }}" || "imp31061769"; // .env에 없으면 테스트 코드로! 🕵️‍♀️
    IMP.init(storeCode); 

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

    // 4. 배송지 목록 모달 로직
    const addressListModal = document.getElementById('addressListModal');
    const addressListModalContent = document.getElementById('addressListModalContent');
    const btnAddressList = document.getElementById('btnAddressList');

    window.openAddressListModal = () => {
        addressListModal.classList.remove('hidden');
        setTimeout(() => {
            addressListModal.classList.remove('opacity-0');
            addressListModalContent.classList.replace('scale-95', 'scale-100');
        }, 10);
    };

    window.closeAddressListModal = () => {
        addressListModal.classList.add('opacity-0');
        addressListModalContent.classList.replace('scale-100', 'scale-95');
        setTimeout(() => addressListModal.classList.add('hidden'), 300);
    };

    if (btnAddressList) btnAddressList.addEventListener('click', openAddressListModal);

    // 배송지 선택 클릭 시!
    document.querySelectorAll('.address-select-item').forEach(item => {
        item.addEventListener('click', function() {
            document.getElementById('recipientName').value = this.dataset.name;
            document.getElementById('recipientPhone').value = this.dataset.phone;
            document.getElementById('recipientZipcode').value = this.dataset.zip;
            document.getElementById('recipientAddress').value = this.dataset.addr;
            document.getElementById('recipientDetailAddress').value = this.dataset.detail;
            
            // "주문자와 동일" 체크박스 해제 (다른 주소를 선택했으므로)
            const sameAsOrderer = document.getElementById('sameAsOrderer');
            if (sameAsOrderer) sameAsOrderer.checked = false;

            closeAddressListModal();
            showToast("배송지가 선택되었습니다.");
        });
    });

    // ... (이하 기존 스크립트 유지) ...
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
            
            // 결제 수단 선택 확인 (에러 방지 🕵️‍♀️)
            const paymentMethodInput = document.querySelector('input[name="payment_method"]:checked');
            if (!paymentMethodInput) {
                showToast("결제 수단을 선택해주세요.", "error", "bg-red-500");
                return;
            }

            showToast("결제 요청 중입니다... 잠시만 기다려주세요!", "pending", "bg-primary");
            
            // 실제 서버 전송 로직 (결제 모듈 호출)
            const payload = {
                recipient_name: recipientName,
                recipient_phone: recipientPhone,
                recipient_zipcode: recipientZipcode,
                recipient_address: recipientAddress,
                recipient_detail_address: document.getElementById('recipientDetailAddress').value.trim(),
                shipping_message: document.querySelector('select')?.value || 'door', 
                payment_method: paymentMethodInput.value,
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
            // 테스트 계정(imp31061769)에서는 'kcp'나 'html5_inicis'가 기본입니다. 🕵️‍♀️
            let pgProvider = 'kcp'; // 공용 테스트 계정에서 가장 안정적인 KCP로 변경! ✅
            
            if (payload.payment_method === 'kakaopay') {
                pgProvider = 'kakaopay.TC0ONETIME'; // 카카오페이 테스트 CID
            } else if (payload.payment_method === 'naverpay') {
                pgProvider = 'naverpay';
            } else if (payload.payment_method === 'vbank') {
                pgProvider = 'kcp'; // 가상계좌도 KCP로!
            }

            const reqData = {
                pg: pgProvider,
                pay_method: payload.payment_method === 'vbank' ? 'vbank' : 'card',
                merchant_uid: merchantUid,
                name: firstItemName,
                amount: finalTotal, 
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
