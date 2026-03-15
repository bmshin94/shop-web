@extends('layouts.app')

@section('title', '상품 리뷰 관리 | 마이페이지 - Active Women\'s Premium Store')

@section('content')
<main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-text-muted mb-6">
        <a href="/" class="hover:text-primary transition-colors">Home</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <a href="{{ route('mypage') }}" class="hover:text-primary transition-colors">마이페이지</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <span class="font-bold text-text-main">상품 리뷰 관리</span>
    </nav>

    <!-- Page Title -->
    <h2 class="text-3xl font-extrabold text-text-main tracking-tight mb-8">상품 리뷰 관리</h2>

    <div class="flex flex-col lg:flex-row gap-8 items-start">
        <!-- LNB (Left Navigation Bar) -->
        @include('partials.mypage-sidebar')

        <!-- Main Dashboard Content -->
        <div class="flex-1 w-full space-y-8 min-w-0">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
                
                <!-- Tabs -->
                <div class="flex border-b border-gray-200 mb-4 relative">
                    <button id="tabBtnAvailable" onclick="switchTab('available')" class="pb-3 px-6 text-sm font-bold border-b-2 border-primary text-primary transition-colors">
                        작성 가능한 리뷰 <span class="badge ml-1 bg-primary text-white text-[10px] px-1.5 py-0.5 rounded-full inline-flex leading-none align-middle items-center justify-center">{{ $availableReviews->total() }}</span>
                    </button>
                    <button id="tabBtnWritten" onclick="switchTab('written')" class="pb-3 px-6 text-sm font-medium border-b-2 border-transparent text-text-muted hover:text-text-main transition-colors">
                        내가 작성한 리뷰 <span class="badge ml-1 bg-gray-300 text-white text-[10px] px-1.5 py-0.5 rounded-full inline-flex leading-none align-middle items-center justify-center">{{ $writtenReviews->total() }}</span>
                    </button>
                </div>

                <!-- 작성 가능한 리뷰 탭 -->
                <div id="tabAvailable" class="space-y-4">
                    @forelse ($availableReviews as $product)
                    <div class="group relative flex gap-4 sm:gap-6 p-4 sm:p-5 border border-gray-100 rounded-2xl sm:rounded-3xl hover:shadow-xl transition-all bg-white overflow-hidden active:scale-[0.98]">
                        <div class="size-20 sm:size-24 bg-gray-100 rounded-xl sm:rounded-2xl overflow-hidden shrink-0 shadow-inner border border-gray-50">
                            <img src="{{ $product->image_url }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        </div>
                        <div class="flex flex-col sm:flex-row justify-between flex-1 min-w-0 py-0.5 gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="inline-flex px-2 py-0.5 rounded-full bg-primary/5 text-primary text-[10px] font-black uppercase tracking-tighter border border-primary/10">배송완료</span>
                                    <span class="text-[10px] text-green-600 font-bold flex items-center gap-0.5">
                                        <span class="material-symbols-outlined text-[12px] filled" style="font-variation-settings: 'FILL' 1;">monetization_on</span>
                                        500P 적립
                                    </span>
                                </div>
                                <h4 class="text-sm sm:text-base font-bold text-text-main group-hover:text-primary transition-colors line-clamp-1 sm:line-clamp-2 leading-tight">{{ $product->name }}</h4>
                            </div>
                            <div class="flex items-center justify-end sm:justify-center shrink-0">
                                <button onclick="openReviewModal({{ $product->id }}, '{{ addslashes($product->name) }}', '{{ $product->image_url }}')" 
                                        class="px-6 py-2.5 sm:px-8 sm:py-3 bg-text-main text-white text-[11px] sm:text-xs font-black rounded-xl hover:bg-black transition-all shadow-lg shadow-gray-200 whitespace-nowrap active:scale-95">
                                    리뷰 작성
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="py-20 text-center bg-gray-50/50 rounded-3xl border border-dashed border-gray-200">
                        <span class="material-symbols-outlined text-4xl text-gray-300 mb-3">rate_review</span>
                        <p class="text-text-muted font-bold">작성 가능한 리뷰가 없습니다.</p>
                    </div>
                    @endforelse

                    {{-- 작성 가능한 리뷰 페이징 추가!  --}}
                    @if($availableReviews->hasPages())
                    <div class="mt-12">
                        {{ $availableReviews->links() }}
                    </div>
                    @endif
                </div>

                <!-- 내가 작성한 리뷰 탭 -->
                <div id="tabWritten" class="hidden space-y-6">
                    @forelse ($writtenReviews as $review)
                    <div class="p-6 border border-gray-100 rounded-2xl bg-white shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-4">
                                <div class="size-12 rounded-lg overflow-hidden bg-gray-100 border border-gray-50">
                                    <img src="{{ $review->product->image_url }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-text-main">{{ $review->product->name }}</h4>
                                    <div class="flex items-center gap-1 mt-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                        <span class="material-symbols-outlined text-[14px] {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}" style="font-variation-settings: 'FILL' 1;">star</span>
                                        @endfor
                                        <span class="text-[11px] text-text-muted ml-2">{{ $review->created_at->format('Y.m.d') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h5 class="text-sm font-black text-text-main mb-2">{{ $review->title }}</h5>
                        <p class="text-sm text-text-muted leading-relaxed whitespace-pre-wrap">{{ $review->content }}</p>
                        
                        @if($review->images)
                        <div class="flex gap-2 mt-4 overflow-x-auto pb-2 scrollbar-hide">
                            @foreach($review->images as $img)
                            <img src="{{ $img }}" onclick="openImageZoom(this.src)" class="size-20 rounded-lg object-cover cursor-zoom-in border border-gray-100 hover:opacity-80 transition-opacity">
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @empty
                    <div class="py-20 text-center bg-gray-50/50 rounded-3xl border border-dashed border-gray-200">
                        <span class="material-symbols-outlined text-4xl text-gray-300 mb-3">history_edu</span>
                        <p class="text-text-muted font-bold">아직 작성한 리뷰가 없습니다.</p>
                    </div>
                    @endforelse

                    {{-- 내가 작성한 리뷰 페이징  --}}
                    @if($writtenReviews->hasPages())
                    <div class="mt-12">
                        {{ $writtenReviews->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>

{{-- 리뷰 작성 모달  --}}
<div id="reviewModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4 sm:p-6 opacity-0 transition-opacity duration-300">
    <div id="reviewModalContent" class="relative bg-white rounded-3xl shadow-2xl max-w-xl w-full max-h-[90vh] flex flex-col overflow-hidden scale-95 transition-transform duration-300">
        <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50 shrink-0">
            <h3 class="text-lg font-black text-text-main">리뷰 작성하기</h3>
            <button onclick="closeReviewModal()" class="size-8 flex items-center justify-center rounded-full hover:bg-gray-200 text-gray-400 transition-colors">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>
        </div>

        <form id="reviewSubmitForm" class="flex-1 overflow-y-auto p-8 space-y-10 min-h-0" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="product_id" id="modalProductId">
            <input type="hidden" name="rating" id="modalRating">

            <div class="bg-gray-50 rounded-2xl border border-gray-100 p-5 flex items-center gap-5">
                <div class="size-20 rounded-xl overflow-hidden bg-white shrink-0 border border-gray-50 shadow-sm">
                    <img id="modalProductImage" src="" class="w-full h-full object-cover">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] text-primary font-bold mb-0.5 uppercase tracking-wider">Purchased Item</p>
                    <h4 id="modalProductName" class="text-base font-bold text-text-main truncate"></h4>
                </div>
            </div>

            <div class="text-center py-4">
                <h3 class="text-lg font-bold text-text-main mb-6">상품은 만족하셨나요?</h3>
                <div class="flex justify-center gap-2 mb-4">
                    @for ($i = 1; $i <= 5; $i++)
                    <button type="button" class="star-btn text-gray-300 transition-all hover:scale-110" data-rating="{{ $i }}">
                        <span class="material-symbols-outlined text-5xl" style="font-variation-settings: 'FILL' 1;">star</span>
                    </button>
                    @endfor
                </div>
                <p id="ratingFeedback" class="text-sm font-bold text-primary">별점을 선택해주세요</p>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-text-main mb-3 ml-1">리뷰 제목</label>
                    <input type="text" name="title" placeholder="제목을 입력해주세요 (최대 50자)" 
                           class="w-full rounded-2xl border-gray-200 px-5 py-4 text-sm text-text-main focus:border-primary focus:ring-primary/20 transition-all bg-gray-50/30">
                </div>
                <div>
                    <label class="block text-sm font-bold text-text-main mb-3 ml-1">상세 후기</label>
                    <textarea name="content" rows="6" placeholder="최소 10자 이상 작성해주세요. 착용감, 사이즈 팁 등을 공유해주시면 관리자가 너무 기쁠 거야! " 
                              class="w-full rounded-2xl border-gray-200 px-5 py-4 text-sm text-text-main focus:border-primary focus:ring-primary/20 transition-all resize-none bg-gray-50/30 leading-relaxed"></textarea>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <h3 class="text-md font-bold text-text-main mb-2">포토 리뷰 (선택)</h3>
                <p class="text-xs text-text-muted mb-6 italic">멋진 착용샷을 올려주시면 포인트 500P를 줄게!</p>
                <div class="flex items-center gap-4 overflow-x-auto pb-4 scrollbar-hide" id="image-preview-wrapper">
                    <label for="reviewImageUpload" class="flex flex-col items-center justify-center w-24 h-24 rounded-2xl border-2 border-dashed border-gray-200 cursor-pointer hover:border-primary hover:bg-primary-light/30 transition-all text-gray-400 hover:text-primary group shrink-0">
                        <span class="material-symbols-outlined text-2xl group-hover:scale-110 transition-transform">add_a_photo</span>
                        <span class="text-[10px] font-bold mt-1">사진 추가</span>
                        <input type="file" id="reviewImageUpload" name="images[]" multiple class="hidden" accept="image/*">
                    </label>
                    <div id="reviewImagePreviewContainer" class="flex gap-4"></div>
                </div>
            </div>

            <div class="pt-4 flex gap-4 sticky bottom-0 bg-white pb-2 shrink-0">
                <button type="button" onclick="closeReviewModal()" class="flex-1 py-5 bg-white border-2 border-gray-200 text-text-main font-bold rounded-2xl hover:bg-gray-50 transition-all">나중에 쓸게요</button>
                <button type="submit" class="flex-[2] py-5 bg-primary text-white font-black rounded-2xl hover:bg-red-600 transition-all shadow-lg shadow-primary/30 active:scale-95">리뷰 등록하기</button>
            </div>
        </form>
    </div>
</div>

{{-- 이미지 확대 모달 --}}
<div id="imageZoomModal" class="fixed inset-0 z-[110] hidden items-center justify-center bg-black/90 backdrop-blur-md p-4 cursor-zoom-out" onclick="this.classList.add('hidden')">
    <img id="zoomImage" src="" class="max-h-full max-w-full rounded-xl shadow-2xl">
</div>

{{-- 토스트 알림 --}}
<div id="toast" class="fixed bottom-10 left-1/2 -translate-x-1/2 z-[120] px-8 py-4 bg-[#181211] text-white rounded-2xl shadow-2xl text-sm font-bold transition-all duration-500 opacity-0 translate-y-8 flex items-center gap-3">
    <span class="material-symbols-outlined text-primary">check_circle</span>
    <span id="toastMsg"></span>
</div>
@endsection

@push('scripts')
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
            btnAvail.classList.remove('border-transparent', 'text-text-muted');
            btnWritten.classList.remove('border-primary', 'text-primary');
            btnWritten.classList.add('border-transparent', 'text-text-muted');
        } else {
            available.classList.add('hidden');
            written.classList.remove('hidden');
            btnAvail.classList.remove('border-primary', 'text-primary');
            btnAvail.classList.add('border-transparent', 'text-text-muted');
            btnWritten.classList.add('border-primary', 'text-primary');
            btnWritten.classList.remove('border-transparent', 'text-text-muted');
        }
        
        // URL 파라미터 업데이트 (새로고침 시 탭 유지 목적) 
        const url = new URL(window.location);
        url.searchParams.set('tab', tab);
        window.history.replaceState({}, '', url);
    }

    $(document).ready(function() {
        // 초기 로드 시 탭 유지 로직 
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab') || 'available';
        const isWrittenPage = urlParams.has('page_written');
        const isAvailPage = urlParams.has('page_avail');

        if (activeTab === 'written' || isWrittenPage) {
            switchTab('written');
        } else {
            switchTab('available');
        }
    });

    const reviewModal = document.getElementById('reviewModal');
    const reviewModalContent = document.getElementById('reviewModalContent');
    const starBtns = document.querySelectorAll('.star-btn');
    const ratingFeedback = document.getElementById('ratingFeedback');
    let currentRating = 0;
    const feedbackTexts = ["아쉬워요", "그저 그래요", "보통이에요", "맘에 들어요", "최고에요!"];

    function openReviewModal(productId, productName, imageUrl) {
        document.getElementById('modalProductId').value = productId;
        document.getElementById('modalProductName').textContent = productName;
        document.getElementById('modalProductImage').src = imageUrl;
        document.getElementById('reviewSubmitForm').reset();
        document.getElementById('reviewImagePreviewContainer').innerHTML = '';
        resetStars();

        reviewModal.classList.remove('hidden');
        setTimeout(() => {
            reviewModal.classList.add('flex');
            reviewModal.classList.remove('opacity-0');
            reviewModalContent.classList.replace('scale-95', 'scale-100');
        }, 10);
        document.body.style.overflow = 'hidden';
    }

    function closeReviewModal() {
        reviewModal.classList.add('opacity-0');
        reviewModalContent.classList.replace('scale-100', 'scale-95');
        setTimeout(() => {
            reviewModal.classList.add('hidden');
            reviewModal.classList.remove('flex');
        }, 300);
        document.body.style.overflow = '';
    }

    function fillStars(rating) {
        starBtns.forEach((btn, index) => {
            if (index < rating) {
                btn.classList.replace('text-gray-300', 'text-yellow-400');
            } else {
                btn.classList.replace('text-yellow-400', 'text-gray-300');
            }
        });
    }

    function resetStars() {
        currentRating = 0;
        fillStars(0);
        ratingFeedback.textContent = "별점을 선택해주세요";
        document.getElementById('modalRating').value = '';
    }

    starBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            currentRating = parseInt(e.currentTarget.getAttribute('data-rating'));
            fillStars(currentRating);
            ratingFeedback.textContent = feedbackTexts[currentRating - 1];
            document.getElementById('modalRating').value = currentRating;
        });
    });

    document.getElementById('reviewImageUpload').addEventListener('change', function(e) {
        const container = document.getElementById('reviewImagePreviewContainer');
        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = (event) => {
                const div = document.createElement('div');
                div.className = 'relative w-24 h-24 rounded-2xl overflow-hidden border border-gray-100 shrink-0 group shadow-sm';
                div.innerHTML = `
                    <img src="${event.target.result}" class="w-full h-full object-cover">
                    <button type="button" onclick="this.parentElement.remove()" class="absolute top-1 right-1 size-6 bg-black/50 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="material-symbols-outlined text-[14px]">close</span>
                    </button>
                `;
                container.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    });

    document.getElementById('reviewSubmitForm').addEventListener('submit', function(e) {
        e.preventDefault();
        if (!currentRating) {
            showToast('별점을 선택해주세요!', 'error', 'bg-red-500');
            return;
        }
        const title = this.querySelector('input[name="title"]').value.trim();
        if (!title) {
            showToast('리뷰 제목을 입력해주세요!', 'error', 'bg-red-500');
            return;
        }
        const content = this.querySelector('textarea[name="content"]').value.trim();
        if (!content) {
            showToast('상세 후기를 입력해주세요!', 'error', 'bg-red-500');
            return;
        }
        if (content.length < 10) {
            showToast('상세 후기를 10자 이상 작성해주세요! ', 'info', 'bg-[#181211]');
            return;
        }

        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="animate-spin material-symbols-outlined mr-2">sync</span>등록 중...';

        fetch("{{ route('review.store') }}", {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message || '리뷰가 성공적으로 등록되었습니다! ');
                closeReviewModal();
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.message || '오류가 발생했습니다.', 'error', 'bg-red-500');
                submitBtn.disabled = false;
                submitBtn.textContent = '리뷰 등록하기';
            }
        });
    });

    function showToast(message, icon = 'check_circle', color = 'bg-[#181211]') {
        const toast = document.getElementById('toast');
        const toastMsg = document.getElementById('toastMsg');
        toastMsg.textContent = message;
        toast.classList.remove('opacity-0', 'translate-y-8');
        toast.classList.add('opacity-100', 'translate-y-0');
        setTimeout(() => {
            toast.classList.replace('opacity-100', 'opacity-0');
            toast.classList.replace('translate-y-0', 'translate-y-8');
        }, 3000);
    }

    function openImageZoom(src) {
        document.getElementById('zoomImage').src = src;
        document.getElementById('imageZoomModal').classList.remove('hidden');
    }
</script>
@endpush
