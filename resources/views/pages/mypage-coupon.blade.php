@extends('layouts.app')

@section('title', '보유 쿠폰 | 마이페이지 - Active Women\'s Premium Store')

@section('content')
<main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-text-muted mb-6">
        <a href="/" class="hover:text-primary transition-colors">Home</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <a href="{{ route('mypage') }}" class="hover:text-primary transition-colors">마이페이지</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <span class="font-bold text-text-main">보유 쿠폰</span>
    </nav>

    {{-- Page Title --}}
    <h2 class="text-3xl font-extrabold text-text-main tracking-tight mb-8">보유 쿠폰</h2>

    <div class="flex flex-col lg:flex-row gap-8 items-start">
        {{-- LNB --}}
        @include('partials.mypage-sidebar')

        {{-- Main Dashboard Content --}}
        <div class="flex-1 w-full space-y-6">
            
            {{-- Modern Filter Section --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <form action="{{ route('mypage.coupon') }}" method="GET" class="p-5 lg:p-8 flex flex-col lg:flex-row gap-4">
                    <div class="flex-1 relative group">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 group-focus-within:text-primary transition-colors">search</span>
                        <input type="text" name="search" value="{{ $search }}" placeholder="쿠폰명 또는 설명 검색" 
                               class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-transparent rounded-2xl text-sm focus:ring-2 focus:ring-primary/20 focus:bg-white focus:border-primary/30 transition-all outline-none font-semibold">
                    </div>
                    <div class="w-full lg:w-[200px] relative group">
                        <select name="type" class="w-full pl-4 pr-10 py-3 bg-gray-50 border border-transparent rounded-2xl text-sm focus:ring-2 focus:ring-primary/20 focus:bg-white focus:border-primary/30 transition-all outline-none font-medium appearance-none cursor-pointer !bg-none">
                            <option value="">모든 쿠폰유형</option>
                            <option value="discount" {{ $type == 'discount' ? 'selected' : '' }}>할인 쿠폰</option>
                            <option value="shipping" {{ $type == 'shipping' ? 'selected' : '' }}>배송비 쿠폰</option>
                        </select>
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 pointer-events-none group-focus-within:text-primary">expand_more</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('mypage.coupon') }}" title="초기화" class="size-11 flex items-center justify-center rounded-xl bg-gray-100 text-gray-500 hover:bg-gray-200 transition-colors shrink-0">
                            <span class="material-symbols-outlined text-xl">restart_alt</span>
                        </a>
                        <button type="submit" class="flex-1 lg:flex-none px-8 py-3 bg-primary text-white text-sm font-black rounded-xl hover:bg-red-600 transition-all shadow-lg shadow-primary/20 active:scale-95">
                            검색
                        </button>
                    </div>
                </form>
            </div>

            {{-- Coupon Summary & Registration --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <div class="flex items-center justify-between mb-8 border-b border-gray-50 pb-6">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-3xl">confirmation_number</span>
                        <p class="text-lg font-bold text-text-main">사용 가능한 쿠폰</p>
                    </div>
                    <p class="text-3xl font-black text-primary">{{ number_format($couponCount) }}<span class="text-lg text-text-main font-bold ml-1">장</span></p>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3">
                    <input type="text" id="coupon-code" placeholder="쿠폰코드를 입력하세요" 
                           class="flex-1 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 px-5 py-3.5 text-sm font-medium outline-none transition-all uppercase">
                    <button type="button" id="btn-register-coupon" class="px-10 py-3.5 bg-text-main text-white text-sm font-black rounded-2xl hover:bg-primary transition-all shadow-lg shadow-gray-200 active:scale-95 shrink-0">
                        쿠폰 등록
                    </button>
                </div>
            </div>
            
            {{-- Coupon Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($coupons as $coupon)
                <div class="group relative overflow-hidden bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 {{ !$coupon->is_active ? 'opacity-60 grayscale' : '' }}">
                    {{-- Ticket Notch Effects --}}
                    <div class="absolute -left-3 top-1/2 -translate-y-1/2 size-6 rounded-full bg-background-light border border-gray-100 z-10"></div>
                    <div class="absolute -right-3 top-1/2 -translate-y-1/2 size-6 rounded-full bg-background-light border border-gray-100 z-10"></div>
                    
                    <div class="p-6 sm:p-8 flex flex-col h-full">
                        <div class="flex justify-between items-start mb-4">
                            <span class="inline-flex px-3 py-1 {{ $coupon->raw_type === 'shipping' ? 'bg-blue-50 text-blue-600' : 'bg-primary-light text-primary' }} text-[10px] font-black uppercase tracking-wider rounded-full border border-current/10">
                                {{ $coupon->type }}
                            </span>
                            <span class="text-[11px] font-bold text-text-muted">No. {{ str_pad($coupon->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        
                        <h4 class="text-lg font-black text-text-main mb-2 transition-colors">{{ $coupon->name }}</h4>
                        <p class="text-sm text-text-muted font-medium mb-6 flex-1">{{ $coupon->description }}</p>
                        
                        <div class="pt-6 border-t border-dashed border-gray-100 flex items-center justify-between mt-auto">
                            <div class="flex items-center gap-2 text-text-muted">
                                <span class="material-symbols-outlined text-sm">schedule</span>
                                <span class="text-xs font-bold">
                                    @if($coupon->used_at)
                                        {{ $coupon->used_at }} 사용 완료
                                    @else
                                        {{ $coupon->expired_at }} 까지
                                    @endif
                                </span>
                            </div>
                            {{-- 상태 텍스트 영역 (사용자가 원한 바로 그 부분!) --}}
                            <span class="text-[11px] font-black {{ $coupon->is_active ? 'text-primary' : 'text-text-muted' }}">
                                {{ $coupon->status_text }}
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-24 text-center bg-gray-50 rounded-3xl border border-dashed border-gray-200">
                    <div class="size-20 rounded-full bg-white flex items-center justify-center mx-auto mb-6 shadow-sm">
                        <span class="material-symbols-outlined text-4xl text-gray-200">sentiment_dissatisfied</span>
                    </div>
                    <p class="text-text-muted font-bold">등록된 쿠폰 내역이 없습니다.</p>
                </div>
                @endforelse
            </div>

            {{-- Caution Note --}}
            <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100">
                <h5 class="text-sm font-black text-text-main mb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined text-gray-400 text-lg">info</span> 쿠폰 이용 안내
                </h5>
                <ul class="space-y-2 text-xs text-text-muted font-medium leading-relaxed">
                    <li>• 쿠폰은 주문 시 결제 페이지에서 선택하여 적용할 수 있습니다.</li>
                    <li>• 유효기간이 지난 쿠폰은 자동으로 소멸되며 복구되지 않습니다.</li>
                    <li>• 취소/반품 시 사용한 쿠폰의 복구 여부는 쿠폰 정책에 따라 다를 수 있습니다.</li>
                </ul>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        /**
         * 쿠폰 번호 등록 처리 (AJAX)
         */
        $('#btn-register-coupon').on('click', function() {
            const code = $('#coupon-code').val().trim();
            
            if (!code) {
                showToast('쿠폰 번호를 입력해 주세요.', 'warning', 'bg-red-500');
                return;
            }

            const $btn = $(this);
            $btn.prop('disabled', true).addClass('opacity-50');

            $.ajax({
                url: "{{ route('mypage.coupon.register') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    code: code
                },
                success: function(response) {
                    window.alertCallback = function() {
                        location.reload();
                    };
                    showAlert(response.message, '성공', 'check_circle');
                },
                error: function(xhr) {
                    const message = xhr.responseJSON ? xhr.responseJSON.message : '쿠폰 등록 중 오류가 발생했습니다.';
                    showToast(message, 'error', 'bg-red-500');
                    $btn.prop('disabled', false).removeClass('opacity-50');
                }
            });
        });
    });
</script>
@endpush
