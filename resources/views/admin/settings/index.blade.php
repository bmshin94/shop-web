@extends('layouts.admin')

@section('page_title', '기본 설정')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 lg:p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="size-12 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-[28px]">settings</span>
            </div>
            <div>
                <h3 class="text-2xl font-extrabold text-text-main">기본 설정</h3>
                <p class="mt-1 text-[12px] font-bold text-text-muted">쇼핑몰 운영 기본값과 주문 정책을 관리합니다.</p>
            </div>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-8">
            @csrf
            @method('PATCH')

            <section class="space-y-4">
                <h4 class="text-base font-extrabold text-text-main">사이트 정보</h4>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main">쇼핑몰명</label>
                        <input type="text" name="mall_name" value="{{ old('mall_name', $settings['mall_name']) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
                        @error('mall_name')
                            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main">상호명</label>
                        <input type="text" name="business_name" value="{{ old('business_name', $settings['business_name']) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
                        @error('business_name')
                            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main">사업자등록번호</label>
                        <input type="text" name="business_number" value="{{ old('business_number', $settings['business_number']) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
                        @error('business_number')
                            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="space-y-4">
                <h4 class="text-base font-extrabold text-text-main">고객센터 정보</h4>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main">고객센터 전화번호</label>
                        <input type="text" name="customer_center_phone" value="{{ old('customer_center_phone', $settings['customer_center_phone']) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
                        @error('customer_center_phone')
                            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main">고객센터 이메일</label>
                        <input type="email" name="customer_center_email" value="{{ old('customer_center_email', $settings['customer_center_email']) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
                        @error('customer_center_email')
                            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="space-y-4">
                <h4 class="text-base font-extrabold text-text-main">주문/정책 설정</h4>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main">기본 배송비 (원)</label>
                        <input type="number" min="0" max="1000000" name="shipping_fee" value="{{ old('shipping_fee', $settings['shipping_fee']) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
                        @error('shipping_fee')
                            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main">무료배송 기준금액 (원)</label>
                        <input type="number" min="0" max="10000000" name="free_shipping_threshold" value="{{ old('free_shipping_threshold', $settings['free_shipping_threshold']) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
                        @error('free_shipping_threshold')
                            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main">미결제 자동취소 시간 (시간)</label>
                        <input type="number" min="1" max="720" name="order_auto_cancel_hours" value="{{ old('order_auto_cancel_hours', $settings['order_auto_cancel_hours']) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
                        @error('order_auto_cancel_hours')
                            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="space-y-4">
                <h4 class="text-base font-extrabold text-text-main">적립금/포인트 정책</h4>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main">포인트 적립률 (%)</label>
                        <input type="number" min="0" max="100" step="0.1" name="point_earn_rate" value="{{ old('point_earn_rate', $settings['point_earn_rate']) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
                        @error('point_earn_rate')
                            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main">신규 가입 축하금 (P)</label>
                        <input type="number" min="0" name="welcome_points" value="{{ old('welcome_points', $settings['welcome_points']) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
                        @error('welcome_points')
                            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main">리뷰 작성 적립금 (P)</label>
                        <input type="number" min="0" name="review_reward_points" value="{{ old('review_reward_points', $settings['review_reward_points']) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
                        @error('review_reward_points')
                            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main">최소 사용 가능 금액 (원)</label>
                        <input type="number" min="0" name="min_use_points" value="{{ old('min_use_points', $settings['min_use_points']) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
                        @error('min_use_points')
                            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-text-main">적립금 유효기간 (개월)</label>
                        <input type="number" min="1" max="120" name="point_expiry_months" value="{{ old('point_expiry_months', $settings['point_expiry_months']) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
                        @error('point_expiry_months')
                            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="space-y-4">
                <div class="flex items-center justify-between">
                    <h4 class="text-base font-extrabold text-text-main">택배사 관리</h4>
                    <button type="button" id="btnAddCourier" class="flex items-center gap-1 text-[12px] font-black text-primary hover:text-red-600 transition-colors">
                        <span class="material-symbols-outlined text-sm">add_circle</span> 택배사 추가
                    </button>
                </div>
                <div id="courierContainer" class="space-y-3">
                    @php
                        $couriers = old('couriers', $settings['couriers'] ?? []);
                    @endphp
                    @foreach((array)$couriers as $index => $courier)
                    @php if(!is_array($courier)) continue; @endphp
                    <div class="courier-item grid grid-cols-1 md:grid-cols-[1fr_2fr_auto] gap-3 items-start bg-gray-50/50 p-4 rounded-2xl border border-gray-100">
                        <div class="space-y-1">
                            <input type="text" name="couriers[{{ $index }}][name]" value="{{ $courier['name'] ?? '' }}" placeholder="택배사명" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold focus:border-primary outline-none">
                        </div>
                        <div class="space-y-1">
                            <input type="text" name="couriers[{{ $index }}][url]" value="{{ $courier['url'] ?? '' }}" placeholder="추적 URL ({tracking_number} 포함)" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold focus:border-primary outline-none">
                        </div>
                        <button type="button" class="btn-remove-courier size-9 flex items-center justify-center rounded-xl bg-red-50 text-red-500 hover:bg-red-100 transition-colors">
                            <span class="material-symbols-outlined text-lg">delete</span>
                        </button>
                    </div>
                    @endforeach
                </div>
                <p class="text-[11px] font-bold text-text-muted leading-relaxed">
                    송장번호가 들어갈 위치에 <code class="text-primary">{tracking_number}</code>를 입력해 주세요.
                </p>
            </section>

            <section class="space-y-3">
                <h4 class="text-base font-extrabold text-text-main">알림 발송 설정</h4>
                <label class="inline-flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="alimtalk_test_mode" value="0">
                    <input
                        type="checkbox"
                        name="alimtalk_test_mode"
                        value="1"
                        {{ old('alimtalk_test_mode', $settings['alimtalk_test_mode'] ?? true) ? 'checked' : '' }}
                        class="size-4 rounded border-gray-300 text-primary focus:ring-primary">
                    <span class="text-sm font-bold text-text-main">알림 발송 테스트 모드 활성화</span>
                </label>
                <p class="text-[12px] font-bold text-text-muted">활성화 시 실제 알림톡/문자가 발송되지 않고 로그만 기록됩니다. 서비스 오픈 시 반드시 해제해 주세요.</p>
                @error('alimtalk_test_mode')
                    <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
                @enderror
            </section>

            <section class="space-y-3">
                <h4 class="text-base font-extrabold text-text-main">운영 모드</h4>
                <label class="inline-flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="maintenance_mode" value="0">
                    <input
                        type="checkbox"
                        name="maintenance_mode"
                        value="1"
                        {{ old('maintenance_mode', $settings['maintenance_mode']) ? 'checked' : '' }}
                        class="size-4 rounded border-gray-300 text-primary focus:ring-primary">
                    <span class="text-sm font-bold text-text-main">점검 모드 사용</span>
                </label>
                <p class="text-[12px] font-bold text-text-muted">활성화하면 점검 상태를 노출하기 위한 플래그 값으로 저장됩니다.</p>
                @error('maintenance_mode')
                    <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
                @enderror
            </section>

            @if($errors->any())
                <div class="rounded-2xl bg-red-50 border border-red-100 px-4 py-3 text-[12px] font-bold text-red-600">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="pt-2">
                <button type="submit" class="w-full lg:w-auto px-8 py-3 bg-primary text-white rounded-2xl text-sm font-extrabold hover:bg-red-600 transition-colors shadow-lg shadow-primary/20">
                    기본 설정 저장
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const courierContainer = document.getElementById('courierContainer');
        const btnAddCourier = document.getElementById('btnAddCourier');

        btnAddCourier.addEventListener('click', () => {
            const index = 0; // 새로 추가되는 항목의 임시 인덱스
            const html = `
                <div class="courier-item grid grid-cols-1 md:grid-cols-[1fr_2fr_auto] gap-3 items-start bg-gray-50/50 p-4 rounded-2xl border border-gray-100 animate-in fade-in slide-in-from-top-2 duration-200">
                    <div class="space-y-1">
                        <input type="text" name="couriers[${index}][name]" value="" placeholder="택배사명" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold focus:border-primary outline-none">
                    </div>
                    <div class="space-y-1">
                        <input type="text" name="couriers[${index}][url]" value="" placeholder="추적 URL ({tracking_number} 포함)" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold focus:border-primary outline-none">
                    </div>
                    <button type="button" class="btn-remove-courier size-9 flex items-center justify-center rounded-xl bg-red-50 text-red-500 hover:bg-red-100 transition-colors">
                        <span class="material-symbols-outlined text-lg">delete</span>
                    </button>
                </div>
            `;
            courierContainer.insertAdjacentHTML('afterbegin', html);
            reindexCouriers(); // 맨 위에 추가했으니 인덱스 싹~ 다시 정리! 
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
@endpush
