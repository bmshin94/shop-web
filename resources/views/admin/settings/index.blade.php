@extends('layouts.admin')

@section('page_title', '기본 설정')

@section('content')
<div class="max-w-5xl mx-auto space-y-8 pb-20">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="size-14 rounded-2xl bg-primary text-white flex items-center justify-center shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-[32px]">settings_accessibility</span>
            </div>
            <div>
                <h3 class="text-2xl font-black text-text-main tracking-tight">쇼핑몰 기본 설정</h3>
                <p class="mt-1 text-sm font-bold text-text-muted">우리 쇼핑몰의 정체성과 운영 정책을 한곳에서 관리하세요. 💖</p>
            </div>
        </div>
        <button type="submit" form="settings-form" class="px-8 py-4 bg-text-main text-white rounded-2xl text-sm font-black hover:bg-black transition-all shadow-xl shadow-black/10 transform active:scale-95 flex items-center gap-2">
            <span class="material-symbols-outlined">save</span> 설정 내용 저장하기
        </button>
    </div>

    <form id="settings-form" action="{{ route('admin.settings.update') }}" method="POST" class="grid grid-cols-1 gap-8">
        @csrf
        @method('PATCH')

        <!-- 1. 사이트 기본 정보 -->
        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-500">
            <div class="px-8 py-6 bg-gray-50/50 border-b border-gray-50 flex items-center gap-3">
                <span class="material-symbols-outlined text-primary">storefront</span>
                <h4 class="text-lg font-extrabold text-text-main">사이트 및 법적 정보</h4>
            </div>
            <div class="p-8 space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-black text-text-muted ml-1 uppercase tracking-wider">쇼핑몰명 <span class="text-primary">*</span></label>
                        <input type="text" name="mall_name" value="{{ old('mall_name', $settings['mall_name']) }}" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                        @error('mall_name') <p class="text-[11px] font-bold text-red-600 ml-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-text-muted ml-1 uppercase tracking-wider">상호명 / 법인명</label>
                        <input type="text" name="business_name" value="{{ old('business_name', $settings['business_name']) }}" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-text-muted ml-1 uppercase tracking-wider">사업자등록번호</label>
                        <input type="text" name="business_number" value="{{ old('business_number', $settings['business_number']) }}" placeholder="000-00-00000" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-text-muted ml-1 uppercase tracking-wider">대표자명</label>
                        <input type="text" name="representative_name" value="{{ old('representative_name', $settings['representative_name'] ?? '') }}" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 pt-4 border-t border-gray-50">
                    <div class="space-y-2">
                        <label class="text-xs font-black text-text-muted ml-1 uppercase tracking-wider">사업장 소재지 (주소)</label>
                        <div class="flex gap-2 mb-2">
                            <input type="text" id="business_zip_code" placeholder="우편번호" class="w-32 px-5 py-4 bg-gray-100 border border-gray-200 rounded-2xl text-sm font-bold outline-none cursor-not-allowed" readonly>
                            <button type="button" onclick="execDaumPostcode()" class="px-6 bg-text-main text-white text-xs font-bold rounded-2xl hover:bg-black transition-all active:scale-95">주소 검색</button>
                        </div>
                        <input type="text" id="business_address" name="business_address" value="{{ old('business_address', $settings['business_address'] ?? '') }}" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none" placeholder="기본 주소 및 상세 주소가 입력됩니다.">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-black text-text-muted ml-1 uppercase tracking-wider">통신판매업 신고번호</label>
                            <input type="text" name="mail_order_report_number" value="{{ old('mail_order_report_number', $settings['mail_order_report_number'] ?? '') }}" placeholder="제 2026-서울강남-0000호" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black text-text-muted ml-1 uppercase tracking-wider">개인정보관리책임자</label>
                            <input type="text" name="privacy_manager" value="{{ old('privacy_manager', $settings['privacy_manager'] ?? '') }}" placeholder="홍길동 (email@example.com)" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. 고객센터 및 SEO -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-700">
                <div class="px-8 py-6 bg-gray-50/50 border-b border-gray-50 flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary">support_agent</span>
                    <h4 class="text-lg font-extrabold text-text-main">고객센터 채널 설정</h4>
                </div>
                <div class="p-8 space-y-6">
                    <div class="space-y-2">
                        <label class="text-xs font-black text-text-muted ml-1">고객센터 전화번호</label>
                        <input type="text" name="customer_center_phone" value="{{ old('customer_center_phone', $settings['customer_center_phone']) }}" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold focus:bg-white focus:border-primary outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-text-muted ml-1">고객센터 이메일</label>
                        <input type="email" name="customer_center_email" value="{{ old('customer_center_email', $settings['customer_center_email']) }}" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold focus:bg-white focus:border-primary outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-text-muted ml-1">카카오톡 상담 URL</label>
                        <input type="text" name="kakao_consult_url" value="{{ old('kakao_consult_url', $settings['kakao_consult_url'] ?? '') }}" placeholder="https://pf.kakao.com/..." class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold focus:bg-white focus:border-primary outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-text-muted ml-1">운영 시간 안내</label>
                        <textarea name="cs_hours" rows="2" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold focus:bg-white focus:border-primary outline-none transition-all resize-none">{{ old('cs_hours', $settings['cs_hours'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-700">
                <div class="px-8 py-6 bg-gray-50/50 border-b border-gray-50 flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary">travel_explore</span>
                    <h4 class="text-lg font-extrabold text-text-main">SEO (검색 최적화)</h4>
                </div>
                <div class="p-8 space-y-6">
                    <div class="space-y-2">
                        <label class="text-xs font-black text-text-muted ml-1">사이트 설명 (Description)</label>
                        <textarea name="site_description" rows="4" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold focus:bg-white focus:border-primary outline-none transition-all resize-none">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                        <p class="text-[10px] font-bold text-text-muted mt-1 px-1">구글, 네이버 등 검색 결과에 노출되는 사이트 한 줄 요약입니다.</p>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-text-muted ml-1">핵심 키워드 (Keywords)</label>
                        <input type="text" name="site_keywords" value="{{ old('site_keywords', $settings['site_keywords'] ?? '') }}" placeholder="요가복, 레깅스, 스포츠웨어" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold focus:bg-white focus:border-primary outline-none transition-all">
                        <p class="text-[10px] font-bold text-text-muted mt-1 px-1">쉼표(,)로 구분하여 입력해 주세요.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. 정책 및 운영 설정 -->
        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-8 py-6 bg-gray-50/50 border-b border-gray-50 flex items-center gap-3">
                <span class="material-symbols-outlined text-primary">policy</span>
                <h4 class="text-lg font-extrabold text-text-main">주문 및 운영 정책</h4>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="space-y-2">
                    <label class="text-xs font-black text-text-muted ml-1 uppercase">기본 배송비 (원)</label>
                    <div class="relative">
                        <input type="number" name="shipping_fee" value="{{ old('shipping_fee', $settings['shipping_fee']) }}" class="w-full pl-12 pr-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-black focus:bg-white focus:border-primary outline-none">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-text-muted font-bold">₩</span>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-black text-text-muted ml-1 uppercase">무료배송 기준 (원)</label>
                    <div class="relative">
                        <input type="number" name="free_shipping_threshold" value="{{ old('free_shipping_threshold', $settings['free_shipping_threshold']) }}" class="w-full pl-12 pr-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-black focus:bg-white focus:border-primary outline-none">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-text-muted font-bold">₩</span>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-black text-text-muted ml-1 uppercase">미결제 자동취소 (시간)</label>
                    <div class="relative">
                        <input type="number" name="order_auto_cancel_hours" value="{{ old('order_auto_cancel_hours', $settings['order_auto_cancel_hours']) }}" class="w-full pl-12 pr-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-black focus:bg-white focus:border-primary outline-none">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-text-muted font-bold">hr</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. 적립금 정책 -->
        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-8 py-6 bg-gray-50/50 border-b border-gray-50 flex items-center gap-3">
                <span class="material-symbols-outlined text-primary">payments</span>
                <h4 class="text-lg font-extrabold text-text-main">적립금/포인트 시스템</h4>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="space-y-2 px-4 py-5 bg-primary/5 rounded-2xl border border-primary/10">
                    <label class="text-[11px] font-black text-primary/70 uppercase">적립률 (%)</label>
                    <input type="number" step="0.1" name="point_earn_rate" value="{{ old('point_earn_rate', $settings['point_earn_rate']) }}" class="w-full bg-transparent border-none p-0 text-xl font-black text-primary focus:ring-0 outline-none">
                </div>
                <div class="space-y-2 px-4 py-5 bg-gray-50 rounded-2xl border border-gray-100">
                    <label class="text-[11px] font-black text-text-muted uppercase">가입 축하금 (P)</label>
                    <input type="number" name="welcome_points" value="{{ old('welcome_points', $settings['welcome_points']) }}" class="w-full bg-transparent border-none p-0 text-xl font-black text-text-main focus:ring-0 outline-none">
                </div>
                <div class="space-y-2 px-4 py-5 bg-gray-50 rounded-2xl border border-gray-100">
                    <label class="text-[11px] font-black text-text-muted uppercase">최소 사용 금액 (원)</label>
                    <input type="number" name="min_use_points" value="{{ old('min_use_points', $settings['min_use_points']) }}" class="w-full bg-transparent border-none p-0 text-xl font-black text-text-main focus:ring-0 outline-none">
                </div>
                <div class="space-y-2 px-4 py-5 bg-gray-50 rounded-2xl border border-gray-100">
                    <label class="text-[11px] font-black text-text-muted uppercase">유효기간 (개월)</label>
                    <input type="number" name="point_expiry_months" value="{{ old('point_expiry_months', $settings['point_expiry_months']) }}" class="w-full bg-transparent border-none p-0 text-xl font-black text-text-main focus:ring-0 outline-none">
                </div>
            </div>
        </div>

        <!-- 5. 택배사 및 시스템 -->
        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-8 py-6 bg-gray-50/50 border-b border-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary">local_post_office</span>
                    <h4 class="text-lg font-extrabold text-text-main">물류 및 시스템 설정</h4>
                </div>
                <button type="button" id="btnAddCourier" class="flex items-center gap-1.5 px-4 py-2 bg-primary text-white text-xs font-black rounded-xl hover:bg-black transition-all shadow-md active:scale-95">
                    <span class="material-symbols-outlined text-sm">add_circle</span> 택배사 추가
                </button>
            </div>
            <div class="p-8 space-y-8">
                <div id="courierContainer" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @php $couriers = old('couriers', $settings['couriers'] ?? []); @endphp
                    @foreach((array)$couriers as $index => $courier)
                    @php if(!is_array($courier)) continue; @endphp
                    <div class="courier-item p-5 bg-gray-50 rounded-2xl border border-gray-100 flex flex-col gap-3 group relative">
                        <button type="button" class="btn-remove-courier absolute top-3 right-3 size-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="material-symbols-outlined text-lg">close</span>
                        </button>
                        <input type="text" name="couriers[{{ $index }}][name]" value="{{ $courier['name'] ?? '' }}" placeholder="택배사명 (예: CJ대한통운)" class="w-full bg-transparent border-none p-0 text-sm font-black text-text-main focus:ring-0 outline-none">
                        <input type="text" name="couriers[{{ $index }}][url]" value="{{ $courier['url'] ?? '' }}" placeholder="배송추적 URL" class="w-full bg-transparent border-none p-0 text-[11px] font-bold text-text-muted focus:ring-0 outline-none">
                    </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-6 border-t border-gray-50">
                    <div class="space-y-4">
                        <h5 class="text-sm font-black text-text-main flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-sm">notifications_active</span> 알림 모드
                        </h5>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative">
                                <input type="hidden" name="alimtalk_test_mode" value="0">
                                <input type="checkbox" name="alimtalk_test_mode" value="1" {{ old('alimtalk_test_mode', $settings['alimtalk_test_mode'] ?? true) ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            </div>
                            <span class="text-sm font-bold text-text-main group-hover:text-primary transition-colors">알림톡 테스트 모드 활성화</span>
                        </label>
                    </div>
                    <div class="space-y-4">
                        <h5 class="text-sm font-black text-text-main flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-sm">build_circle</span> 점검 모드
                        </h5>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative">
                                <input type="hidden" name="maintenance_mode" value="0">
                                <input type="checkbox" name="maintenance_mode" value="1" {{ old('maintenance_mode', $settings['maintenance_mode']) ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                            </div>
                            <span class="text-sm font-bold text-text-main group-hover:text-amber-600 transition-colors">점검 모드 즉시 가동</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script>
    function execDaumPostcode() {
        new daum.Postcode({
            oncomplete: function(data) {
                let addr = '';
                if (data.userSelectedType === 'R') { addr = data.roadAddress; } 
                else { addr = data.jibunAddress; }
                
                document.getElementById('business_zip_code').value = data.zonecode;
                document.getElementById('business_address').value = `(${data.zonecode}) ${addr}`;
                document.getElementById('business_address').focus();
            }
        }).open();
    }

    document.addEventListener('DOMContentLoaded', () => {
        const courierContainer = document.getElementById('courierContainer');
        const btnAddCourier = document.getElementById('btnAddCourier');

        btnAddCourier.addEventListener('click', () => {
            const index = document.querySelectorAll('.courier-item').length;
            const html = `
                <div class="courier-item p-5 bg-gray-50 rounded-2xl border border-gray-100 flex flex-col gap-3 group relative animate-in zoom-in-95 duration-200">
                    <button type="button" class="btn-remove-courier absolute top-3 right-3 size-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="material-symbols-outlined text-lg">close</span>
                    </button>
                    <input type="text" name="couriers[${index}][name]" placeholder="택배사명" class="w-full bg-transparent border-none p-0 text-sm font-black text-text-main focus:ring-0 outline-none">
                    <input type="text" name="couriers[${index}][url]" placeholder="배송추적 URL" class="w-full bg-transparent border-none p-0 text-[11px] font-bold text-text-muted focus:ring-0 outline-none">
                </div>
            `;
            courierContainer.insertAdjacentHTML('beforeend', html);
        });

        courierContainer.addEventListener('click', (e) => {
            const btnRemove = e.target.closest('.btn-remove-courier');
            if (btnRemove) {
                const item = btnRemove.closest('.courier-item');
                if (item) {
                    item.remove();
                    reindexCouriers();
                }
            }
        });

        function reindexCouriers() {
            document.querySelectorAll('.courier-item').forEach((item, index) => {
                item.querySelector('input[name*="[name]"]').name = `couriers[${index}][name]`;
                item.querySelector('input[name*="[url]"]').name = `couriers[${index}][url]`;
            });
        }
    });
</script>
@endsection

