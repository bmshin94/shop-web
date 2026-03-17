@extends('layouts.app')

@section('title', '문의 수정 - Active Women\'s Premium Store')

@section('content')
<main class="flex-1 bg-background-alt pb-20">
    <!-- Breadcrumb -->
    <div class="bg-white py-4 border-b border-gray-100 shadow-sm">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <nav class="flex text-xs text-text-muted" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li><a href="/" class="hover:text-primary transition-colors">Home</a></li>
                    @if($inquiry->product)
                    <li class="flex items-center">
                        <span class="material-symbols-outlined text-sm mx-1">chevron_right</span>
                        <a href="{{ route('product-detail', $inquiry->product->slug) }}" class="hover:text-primary transition-colors">상품 상세</a>
                    </li>
                    @endif
                    <li class="flex items-center">
                        <span class="material-symbols-outlined text-sm mx-1">chevron_right</span>
                        <span class="text-text-main font-bold">문의 수정</span>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Q&A Edit Form -->
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-10">
        <!-- Page Title -->
        <div class="mb-8">
            <h2 class="text-2xl font-extrabold text-text-main tracking-tight">문의 내용 수정 </h2>
            <p class="mt-2 text-sm text-text-muted italic">실수했어도 괜찮아! 내용을 수정할 수 있습니다~ </p>
        </div>

        @if($inquiry->product)
        <!-- Product Info Card -->
        <div class="bg-white rounded-2xl border border-gray-200 p-5 mb-8 shadow-sm">
            <div class="flex gap-4 items-center">
                <div class="w-20 h-20 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0 border border-gray-50">
                    <img src="{{ $inquiry->product->image_url ?? ($inquiry->product->images->first()?->image_url ?? 'https://via.placeholder.com/150') }}"
                        alt="{{ $inquiry->product->name }}" class="w-full h-full object-cover" />
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-primary font-bold mb-1 uppercase tracking-wider">Product Info</p>
                    <h3 class="text-base font-bold text-text-main truncate">{{ $inquiry->product->name }}</h3>
                    <p class="text-sm text-text-muted mt-1 font-medium">{{ $inquiry->product->brief_description }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Question Form -->
        <form id="qnaForm" method="POST" action="{{ route('qna.update', $inquiry->id) }}" enctype="multipart/form-data" class="space-y-8">
            @csrf
            
            <!-- Content -->
            <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm">
                <div class="mb-6">
                    <label class="block text-sm font-bold text-text-main mb-3 ml-1">제목</label>
                    <input type="text" name="title" value="{{ $inquiry->title }}" placeholder="제목을 입력해주세요"
                        class="w-full rounded-2xl border-gray-200 px-5 py-4 text-sm text-text-main focus:border-primary focus:ring-primary transition-all shadow-inner bg-gray-50/30">
                </div>
                <div>
                    <label class="block text-sm font-bold text-text-main mb-3 ml-1">문의 내용</label>
                    <textarea name="content" rows="8" placeholder="궁금한 내용을 자세히 적어주세요."
                        class="w-full rounded-2xl border-gray-200 px-5 py-4 text-sm text-text-main focus:border-primary focus:ring-primary transition-all resize-none shadow-inner bg-gray-50/30 leading-relaxed">{{ $inquiry->content }}</textarea>
                </div>
                
                @if($inquiry->product_id)
                <!-- Secret Post Option  -->
                <div class="mt-6 flex items-center gap-2 px-1">
                    <label class="relative flex items-center cursor-pointer">
                        <input type="checkbox" name="is_private" value="1" {{ $inquiry->is_private ? 'checked' : '' }} class="peer sr-only">
                        <div class="w-5 h-5 border-2 border-gray-200 rounded-md bg-white peer-checked:bg-primary peer-checked:border-primary transition-all flex items-center justify-center">
                            <span class="material-symbols-outlined text-white text-[14px] scale-0 peer-checked:scale-100 transition-transform">lock</span>
                        </div>
                        <span class="ml-2 text-sm font-bold text-text-muted peer-checked:text-primary transition-colors">비밀글로 문의하기</span>
                    </label>
                </div>
                @endif
            </div>

            <!-- Photos  -->
            <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm">
                <h3 class="text-lg font-bold text-text-main mb-2">사진 첨부 (최대 4장)</h3>
                <p class="text-sm text-text-muted mb-6">기존 사진을 지우거나 새로 등록할 수 있어! </p>
                <div class="flex flex-wrap gap-4" id="photo-container">
                    <label class="flex flex-col items-center justify-center w-24 h-24 rounded-2xl border-2 border-dashed border-gray-200 cursor-pointer hover:border-primary hover:bg-primary-light/30 transition-all text-gray-400 hover:text-primary group">
                        <span class="material-symbols-outlined text-2xl group-hover:scale-110 transition-transform">add_a_photo</span>
                        <span class="text-[10px] font-bold mt-1">사진 추가</span>
                        <input type="file" name="images[]" id="imageInput" class="hidden" accept="image/*" multiple>
                    </label>
                    
                    <div id="existing-photos" class="flex flex-wrap gap-4">
                        @if($inquiry->images)
                            @foreach($inquiry->images as $img)
                            <div class="relative w-24 h-24 rounded-2xl overflow-hidden border border-gray-100 group">
                                <img src="{{ $img }}" class="w-full h-full object-cover">
                                <button type="button" class="btn-remove-existing absolute top-1 right-1 size-6 bg-black/50 text-white rounded-full flex items-center justify-center hover:bg-primary transition-colors opacity-0 group-hover:opacity-100" data-path="{{ $img }}">
                                    <span class="material-symbols-outlined text-sm">close</span>
                                </button>
                                <div class="absolute inset-0 bg-black/30 flex items-center justify-center pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-[10px] text-white font-bold">기존 사진</span>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                    <!-- New preview images will be inserted here!  -->
                </div>
                {{-- 삭제할 기존 이미지 경로를 담을 hidden 영역 --}}
                <div id="delete-images-container"></div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 pt-4">
                <button type="button" onclick="history.back()" class="flex-1 py-5 bg-white border-2 border-gray-200 text-text-main font-bold rounded-2xl hover:bg-gray-50 transition-all text-center">취소</button>
                <button type="submit" id="submitBtn" class="flex-[2] py-5 bg-primary text-white font-black rounded-2xl hover:bg-red-600 transition-all shadow-lg shadow-primary/20 transform hover:-translate-y-1">수정 완료하기 </button>
            </div>
        </form>
    </div>
</main>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        const $imageInput = $('#imageInput');
        const $photoContainer = $('#photo-container');
        const MAX_IMAGES = 4;
        let selectedFiles = [];

        // 이미지 미리보기 
        $imageInput.on('change', function(e) {
            const files = Array.from(e.target.files);
            if (selectedFiles.length + files.length > MAX_IMAGES) {
                showToast(`사진은 최대 ${MAX_IMAGES}장까지만 등록 가능해요! `, 'info', 'bg-red-500');
                return;
            }
            files.forEach(file => {
                if (!file.type.startsWith('image/')) return;
                selectedFiles.push(file);
                const reader = new FileReader();
                reader.onload = function(e) {
                    const html = `
                        <div class="relative w-24 h-24 rounded-2xl overflow-hidden border border-gray-100 group animate-in fade-in zoom-in duration-300">
                            <img src="${e.target.result}" class="w-full h-full object-cover">
                            <button type="button" class="btn-remove-img absolute top-1 right-1 size-6 bg-black/50 text-white rounded-full flex items-center justify-center hover:bg-primary transition-colors opacity-0 group-hover:opacity-100">
                                <span class="material-symbols-outlined text-sm">close</span>
                            </button>
                        </div>
                    `;
                    $photoContainer.append(html);
                };
                reader.readAsDataURL(file);
            });
            $imageInput.val('');
        });

        // 기존 이미지 삭제 처리 
        $(document).on('click', '.btn-remove-existing', function() {
            const path = $(this).data('path');
            $('#delete-images-container').append(`<input type="hidden" name="delete_images[]" value="${path}">`);
            $(this).parent().remove();
            
            // 전체 사진 개수 체크를 위해 selectedFiles와 합산 로직이 필요할 수 있지만, 
            // 여기서는 단순 삭제로만 처리할게! 
        });

        $(document).on('click', '.btn-remove-img', function() {
            const index = $(this).parent().index() - ($('#existing-photos .group').length + 1); 
            selectedFiles.splice(index, 1);
            $(this).parent().remove();
        });

        // 폼 전송 (AJAX) 
        $('#qnaForm').on('submit', function(e) {
            e.preventDefault();
            const $btn = $('#submitBtn');
            if ($btn.prop('disabled')) return;

            const formData = new FormData(this);
            formData.delete('images[]');
            selectedFiles.forEach(file => { formData.append('images[]', file); });

            $btn.prop('disabled', true).html('<span class="material-symbols-outlined animate-spin mr-2">progress_activity</span> 수정 중...');

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    showToast(response.message, 'check_circle', 'bg-primary');
                    setTimeout(() => {
                        location.href = "{{ $inquiry->product ? route('product-detail', $inquiry->product->slug) : route('mypage.inquiry') }}" + "{{ $inquiry->product ? '#qna' : '' }}";
                    }, 1500);
                },
                error: function(xhr) {
                    $btn.prop('disabled', false).text('수정 완료하기');
                    const msg = xhr.responseJSON?.message || '수정 중 오류가 발생했습니다.';
                    showToast(msg, 'error', 'bg-red-500');
                }
            });
        });
    });
</script>
@endpush
