@extends('layouts.app')

@section('title', '배송지 관리 - Active Women\'s Premium Store')

@section('content')
<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-text-muted mb-6">
        <a href="/" class="hover:text-primary transition-colors">Home</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <a href="{{ route('mypage') }}" class="hover:text-primary transition-colors">마이페이지</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <span class="font-bold text-text-main">배송지 관리</span>
    </nav>

    <h2 class="text-3xl font-extrabold text-text-main tracking-tight mb-8">배송지 관리</h2>

    <div class="flex flex-col lg:flex-row gap-8 items-start">
        @include('partials.mypage-sidebar')

        <div class="flex-1 w-full space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-text-main">배송지 목록 <span class="text-primary text-sm ml-1">{{ $addresses->count() }} / 5</span></h3>
                @if($addresses->count() < 5)
                <button type="button" onclick="openAddressModal()" class="flex items-center gap-2 px-5 py-2.5 bg-text-main text-white rounded-xl text-sm font-bold hover:bg-black transition-all active:scale-95 shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">add_location</span> 신규 배송지 등록
                </button>
                @endif
            </div>

            @forelse($addresses as $address)
            <div class="bg-white rounded-2xl border {{ $address->is_default ? 'border-primary shadow-md' : 'border-gray-200' }} p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-6 transition-all">
                <div class="flex-1 space-y-3">
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 bg-gray-100 rounded-lg text-xs font-black text-text-main">{{ $address->address_name }}</span>
                        @if($address->is_default)
                        <span class="px-3 py-1 bg-primary/10 rounded-lg text-xs font-black text-primary border border-primary/20">기본 배송지</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-lg font-black text-text-main mb-1">{{ $address->recipient_name }} <span class="text-sm font-medium text-text-muted ml-2">({{ $address->phone_number }})</span></p>
                        <p class="text-sm font-bold text-text-muted">[{{ $address->zip_code }}] {{ $address->address }}</p>
                        <p class="text-sm font-bold text-text-muted">{{ $address->address_detail }}</p>
                    </div>
                </div>
                <div class="flex sm:flex-col items-center sm:items-end gap-2 border-t sm:border-t-0 pt-4 sm:pt-0">
                    <div class="flex gap-2">
                        <button type="button" onclick="openAddressModal({{ json_encode($address) }})" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-text-main hover:bg-gray-50 transition-colors">수정</button>
                        @if(!$address->is_default)
                        <button type="button" onclick="deleteAddress({{ $address->id }})" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-red-500 hover:bg-red-50 transition-colors">삭제</button>
                        @endif
                    </div>
                    @if(!$address->is_default)
                    <button type="button" onclick="setDefaultAddress({{ $address->id }})" class="text-[11px] font-black text-primary hover:underline">기본 배송지로 설정</button>
                    @endif
                </div>
            </div>
            @empty
            <div class="bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200 py-20 text-center">
                <div class="size-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                    <span class="material-symbols-outlined text-gray-300 text-4xl">location_off</span>
                </div>
                <p class="text-text-muted font-bold">등록된 배송지가 없습니다.</p>
                <p class="text-sm text-text-muted/60 mt-1">자주 사용하는 배송지를 등록하고 편리하게 쇼핑하세요!</p>
            </div>
            @endforelse

            <div class="bg-primary/5 rounded-2xl p-6 border border-primary/10">
                <h4 class="text-sm font-black text-primary mb-2 flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">info</span> 배송지 관리 안내
                </h4>
                <ul class="text-[11px] font-bold text-text-muted/80 space-y-1.5 leading-relaxed">
                    <li>- 배송지는 최대 5개까지 등록 가능합니다.</li>
                    <li>- 기본 배송지는 주문 시 자동으로 입력되는 주소입니다.</li>
                    <li>- 기본 배송지는 1개만 설정 가능하며, 삭제를 원하실 경우 다른 주소를 기본 배송지로 변경 후 삭제해주세요.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Address Form Modal -->
<div id="addressModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="px-8 py-6 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
            <h3 id="modalTitle" class="text-lg font-black text-text-main">배송지 추가</h3>
            <button onclick="closeAddressModal()" class="size-10 rounded-xl flex items-center justify-center hover:bg-white transition-colors">
                <span class="material-symbols-outlined text-text-muted">close</span>
            </button>
        </div>
        <form id="addressForm" class="p-8 space-y-5">
            <input type="hidden" id="address_id" name="id">
            
            <div class="space-y-1.5">
                <label class="text-xs font-black text-text-muted ml-1">배송지 별칭 <span class="text-primary">*</span></label>
                <input type="text" id="address_name" name="address_name" placeholder="예: 우리집, 회사" required class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-xs font-black text-text-muted ml-1">받는 사람 <span class="text-primary">*</span></label>
                    <input type="text" id="recipient_name" name="recipient_name" required class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-black text-text-muted ml-1">연락처 <span class="text-primary">*</span></label>
                    <input type="tel" id="phone_number" name="phone_number" required placeholder="010-0000-0000" class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-black text-text-muted ml-1">주소 <span class="text-primary">*</span></label>
                <div class="flex gap-2 mb-2">
                    <input type="text" id="zip_code" name="zip_code" readonly required placeholder="우편번호" class="flex-1 px-5 py-3.5 bg-gray-100 border border-gray-200 rounded-2xl text-sm font-bold outline-none cursor-not-allowed">
                    <button type="button" onclick="execDaumPostcode()" class="px-6 bg-text-main text-white text-xs font-bold rounded-2xl hover:bg-black transition-colors whitespace-nowrap">주소 찾기</button>
                </div>
                <input type="text" id="address" name="address" readonly required placeholder="기본 주소" class="w-full px-5 py-3.5 bg-gray-100 border border-gray-200 rounded-2xl text-sm font-bold outline-none cursor-not-allowed mb-2">
                <input type="text" id="address_detail" name="address_detail" required placeholder="상세 주소 (건물명, 동/호수 등)" class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none">
            </div>

            <label class="flex items-center gap-3 cursor-pointer group mt-2">
                <input type="checkbox" id="is_default" name="is_default" value="1" class="size-5 rounded-md border-gray-300 text-primary focus:ring-primary/20 cursor-pointer">
                <span class="text-sm font-bold text-text-muted group-hover:text-text-main transition-colors">기본 배송지로 설정</span>
            </label>

            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeAddressModal()" class="flex-1 py-4 bg-gray-100 text-text-muted text-sm font-bold rounded-2xl hover:bg-gray-200 transition-colors whitespace-nowrap">취소</button>
                <button type="submit" class="flex-[2] py-4 bg-primary text-white text-sm font-black rounded-2xl shadow-lg shadow-primary/20 hover:bg-red-600 transition-all active:scale-95 whitespace-nowrap">저장하기</button>
            </div>
        </form>
    </div>
</div>

<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script>
    function openAddressModal(data = null) {
        const modal = $('#addressModal');
        const form = $('#addressForm')[0];
        form.reset();
        
        if (data) {
            $('#modalTitle').text('배송지 수정');
            $('#address_id').val(data.id);
            $('#address_name').val(data.address_name);
            $('#recipient_name').val(data.recipient_name);
            $('#phone_number').val(data.phone_number);
            $('#zip_code').val(data.zip_code);
            $('#address').val(data.address);
            $('#address_detail').val(data.address_detail);
            $('#is_default').prop('checked', data.is_default);
        } else {
            $('#modalTitle').text('배송지 추가');
            $('#address_id').val('');
            $('#is_default').prop('checked', false);
        }
        
        modal.removeClass('hidden').addClass('flex');
        $('body').addClass('overflow-hidden');
    }

    function closeAddressModal() {
        $('#addressModal').removeClass('flex').addClass('hidden');
        $('body').removeClass('overflow-hidden');
    }

    function execDaumPostcode() {
        new daum.Postcode({
            oncomplete: function(data) {
                let addr = '';
                if (data.userSelectedType === 'R') { addr = data.roadAddress; } 
                else { addr = data.jibunAddress; }
                $('#zip_code').val(data.zonecode);
                $('#address').val(addr);
                $('#address_detail').focus();
            }
        }).open();
    }

    $('#addressForm').on('submit', function(e) {
        e.preventDefault();
        const id = $('#address_id').val();
        const url = id ? `/mypage/shipping-address/${id}` : '/mypage/shipping-address';
        const method = id ? 'PUT' : 'POST';
        
        const formData = {
            address_name: $('#address_name').val(),
            recipient_name: $('#recipient_name').val(),
            phone_number: $('#phone_number').val(),
            zip_code: $('#zip_code').val(),
            address: $('#address').val(),
            address_detail: $('#address_detail').val(),
            is_default: $('#is_default').is(':checked') ? 1 : 0,
            _token: '{{ csrf_token() }}'
        };

        $.ajax({
            url: url,
            method: method,
            data: formData,
            success: function(res) {
                if (res.success) {
                    location.reload();
                } else {
                    showToast(res.message, 'error', 'bg-red-500');
                }
            },
            error: function(xhr) {
                const msg = xhr.responseJSON ? xhr.responseJSON.message : '오류가 발생했습니다.';
                showToast(msg, 'error', 'bg-red-500');
            }
        });
    });

    function deleteAddress(id) {
        showConfirm('정말로 이 배송지를 삭제하시겠습니까?').then(res => {
            if (res) {
                $.ajax({
                    url: `/mypage/shipping-address/${id}`,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        location.reload();
                    }
                });
            }
        });
    }

    function setDefaultAddress(id) {
        $.ajax({
            url: `/mypage/shipping-address/${id}/default`,
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                location.reload();
            }
        });
    }
</script>
@endsection
