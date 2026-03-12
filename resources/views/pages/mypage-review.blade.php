@extends('layouts.app')

@section('title', '상품 리뷰 관리 | 마이페이지 - Active Women\'s Premium Store')

@section('content')
<main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
    <!-- Breadcrumb -->
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
        <div class="flex-1 w-full space-y-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
                
                <!-- Tabs -->
                <div class="flex border-b border-gray-200 mb-8 relative">
                    <button id="tabBtnAvailable" onclick="switchTab('available')" class="pb-3 px-6 text-sm font-bold border-b-2 border-primary text-primary transition-colors">
                        작성 가능한 리뷰 <span class="badge ml-1 bg-primary text-white text-[10px] px-1.5 py-0.5 rounded-full inline-flex leading-none align-middle items-center justify-center">{{ $availableReviews->count() }}</span>
                    </button>
                    <button id="tabBtnWritten" onclick="switchTab('written')" class="pb-3 px-6 text-sm font-medium border-b-2 border-transparent text-text-muted hover:text-text-main transition-colors">
                        내가 작성한 리뷰 <span class="badge ml-1 bg-gray-300 text-white text-[10px] px-1.5 py-0.5 rounded-full inline-flex leading-none align-middle items-center justify-center">{{ $writtenReviews->count() }}</span>
                    </button>
                </div>

                <!-- 작성 가능한 리뷰 -->
                <div id="tabAvailable" class="space-y-4">
                    @forelse ($availableReviews as $product)
                    <div class="flex flex-col sm:flex-row gap-6 p-5 border border-gray-100 rounded-xl hover:shadow-md transition-shadow bg-white">
                        <div class="size-20 bg-gray-100 rounded-lg overflow-hidden shrink-0">
                            <img src="{{ $product->image_url }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex flex-col justify-center flex-1">
                            <p class="text-xs font-bold text-primary mb-1">배송완료</p>
                            <h4 class="text-base font-bold text-text-main">{{ $product->name }}</h4>
                            <p class="text-xs text-text-muted">리뷰 작성 시 적립금 500원!</p>
                        </div>
                        <div class="flex items-center">
                            <button onclick="openReviewModal({{ $product->id }}, '{{ addslashes($product->name) }}', '{{ $product->image_url }}')" 
                                class="w-full sm:w-auto px-6 py-2 bg-text-main text-white text-sm font-bold rounded-lg hover:bg-black transition-colors">리뷰 작성</button>
                        </div>
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center py-20 text-center bg-gray-50 rounded-3xl border border-gray-100">
                        <div class="flex items-center justify-center w-24 h-24 rounded-full bg-white mb-6 shadow-sm text-gray-300">
                            <span class="material-symbols-outlined text-5xl">rate_review</span>
                        </div>
                        <h3 class="text-lg font-bold text-text-main mb-2">작성 가능한 리뷰가 없습니다</h3>
                        <p class="text-sm text-text-muted">상품 구매 후 배송이 완료되면 리뷰를 작성하실 수 있습니다.</p>
                    </div>
                    @endforelse
                </div>

                <!-- 내가 작성한 리뷰 (초기 숨김) -->
                <div id="tabWritten" class="space-y-4 hidden">
                    @forelse ($writtenReviews as $review)
                    <div class="p-6 border border-gray-100 rounded-xl bg-gray-50">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <span class="text-sm font-bold text-text-main block mb-1">{{ $review->title }}</span>
                                <div class="flex items-center gap-2">
                                    <div class="flex text-yellow-400">
                                        @for ($i = 1; $i <= 5; $i++)
                                        <span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' {{ $i <= $review->rating ? 1 : 0 }}">star</span>
                                        @endfor
                                    </div>
                                    <span class="text-[11px] text-text-muted font-bold">{{ $review->product->name }}</span>
                                </div>
                            </div>
                            <span class="text-[11px] text-gray-400 font-medium">{{ $review->created_at->format('Y.m.d') }}</span>
                        </div>
                        <p class="text-sm text-text-main leading-relaxed mb-4 break-keep">{{ $review->content }}</p>
                        
                        @if($review->images && count($review->images) > 0)
                        <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide mb-4">
                            @foreach($review->images as $img)
                            <img src="{{ $img }}" alt="Review Image" class="size-20 rounded-lg object-cover border border-gray-100 cursor-pointer" onclick="openImageZoom('{{ $img }}')">
                            @endforeach
                        </div>
                        @endif

                        <div class="flex justify-end gap-2 text-[11px] text-gray-400">
                            <button class="hover:text-primary transition-colors font-bold">수정</button>
                            <span class="mx-1 text-gray-200">|</span>
                            <button class="hover:text-red-500 transition-colors font-bold">삭제</button>
                        </div>
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center py-20 text-center bg-gray-50 rounded-3xl border border-gray-100">
                        <div class="flex items-center justify-center w-24 h-24 rounded-full bg-white mb-6 shadow-sm text-gray-300">
                            <span class="material-symbols-outlined text-5xl">inventory</span>
                        </div>
                        <h3 class="text-lg font-bold text-text-main mb-2">작성한 리뷰가 없습니다</h3>
                        <p class="text-sm text-text-muted">작성하신 리뷰 내역이 여기에 표시됩니다.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Review Modal -->
<div id="reviewModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden transform scale-95 transition-transform duration-300" id="reviewModalContent">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-5 border-b border-gray-100">
            <h3 class="text-lg font-bold text-text-main flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">rate_review</span> 리뷰 작성
            </h3>
            <button onclick="closeReviewModal()" class="text-gray-400 hover:text-text-main transition-colors rounded-full p-1 hover:bg-gray-100">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form id="reviewSubmitForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="product_id" id="modalProductId">
            <input type="hidden" name="rating" id="modalRating">

            <!-- Modal Body -->
            <div class="p-6 space-y-6">
                <!-- Product Info Summary -->
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <div class="size-12 bg-white rounded-md overflow-hidden shrink-0 border border-gray-200">
                        <img src="" id="modalProductImage" alt="상품" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <p class="text-xs font-bold text-text-main line-clamp-1" id="modalProductName">상품명</p>
                        <p class="text-xs text-text-muted mt-0.5">구매한 상품</p>
                    </div>
                </div>

                <!-- Star Rating -->
                <div>
                    <label class="block text-sm font-bold text-text-main mb-3 text-center">상품은 만족하셨나요?</label>
                    <div class="flex justify-center gap-1" id="starRatingContainer">
                        @for ($i = 1; $i <= 5; $i++)
                        <button type="button" class="star-btn text-gray-300 hover:text-yellow-400 transition-colors" data-rating="{{ $i }}">
                            <span class="material-symbols-outlined text-4xl" style="font-variation-settings: 'FILL' 1;">star</span>
                        </button>
                        @endfor
                    </div>
                    <p class="text-center text-xs font-bold text-primary mt-2 hidden" id="ratingFeedback">최고에요!</p>
                </div>

                <!-- Title & Content -->
                <div class="space-y-4">
                    <input type="text" name="title" id="reviewTitle" required
                        class="w-full rounded-xl border border-gray-200 p-4 text-sm text-text-main focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                        placeholder="리뷰 제목을 입력해주세요 (최대 50자)">
                    <textarea name="content" id="reviewText" rows="4" required
                        class="w-full rounded-xl border border-gray-200 p-4 text-sm text-text-main focus:border-primary focus:ring-1 focus:ring-primary resize-none transition-colors"
                        placeholder="솔직한 평가를 남겨주세요. (최소 10자 이상)"></textarea>
                </div>

                <!-- Photo Upload -->
                <div>
                    <input type="file" name="images[]" id="reviewImageUpload" accept="image/*" multiple class="hidden">
                    <button type="button" onclick="document.getElementById('reviewImageUpload').click()"
                        class="flex items-center justify-center gap-2 w-full py-3 border border-dashed border-gray-300 rounded-xl text-sm font-bold text-text-muted hover:bg-gray-50 transition-colors">
                        <span class="material-symbols-outlined">add_a_photo</span> 사진 첨부하기
                    </button>
                    <div id="reviewImagePreviewContainer" class="flex flex-wrap gap-2 mt-3"></div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-5 border-t border-gray-100 flex gap-3">
                <button type="button" onclick="closeReviewModal()" class="flex-1 px-4 py-3 bg-white border border-gray-300 text-text-main text-sm font-bold rounded-xl hover:bg-gray-50 transition-colors">취소</button>
                <button type="submit" class="flex-1 px-4 py-3 bg-primary text-white text-sm font-bold rounded-xl hover:bg-red-600 transition-colors shadow-md">등록하기</button>
            </div>
        </form>
    </div>
</div>

<!-- Image Zoom Modal -->
<div id="imageZoomModal" class="fixed inset-0 z-[200] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4 cursor-pointer" onclick="this.classList.add('hidden')">
    <img id="zoomImage" src="" class="max-h-full max-w-full rounded-lg shadow-2xl">
</div>

<!-- Toast Popup -->
<div id="toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[150] flex items-center justify-center gap-2 bg-text-main text-white px-6 py-3 rounded-full text-sm font-bold shadow-2xl transition-all duration-300 opacity-0 translate-y-8 pointer-events-none">
    <span class="material-symbols-outlined text-lg text-green-400" id="toastIcon">check_circle</span>
    <span id="toastMsg">처리되었습니다.</span>
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
    }

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
        
        // Reset form
        document.getElementById('reviewSubmitForm').reset();
        document.getElementById('reviewImagePreviewContainer').innerHTML = '';
        resetStars();

        reviewModal.classList.remove('hidden');
        setTimeout(() => {
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
        ratingFeedback.classList.add('hidden');
        document.getElementById('modalRating').value = '';
    }

    starBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            currentRating = parseInt(e.currentTarget.getAttribute('data-rating'));
            fillStars(currentRating);
            ratingFeedback.textContent = feedbackTexts[currentRating - 1];
            ratingFeedback.classList.remove('hidden');
            document.getElementById('modalRating').value = currentRating;
        });
    });

    // Image Preview
    document.getElementById('reviewImageUpload').addEventListener('change', function(e) {
        const container = document.getElementById('reviewImagePreviewContainer');
        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = (event) => {
                const div = document.createElement('div');
                div.className = 'relative size-20 rounded-lg overflow-hidden border border-gray-200 shrink-0 group';
                div.innerHTML = `<img src="${event.target.result}" class="w-full h-full object-cover">`;
                container.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    });

    // Form Submit
    document.getElementById('reviewSubmitForm').addEventListener('submit', function(e) {
        e.preventDefault();
        if (!currentRating) {
            alert('별점을 선택해주세요!');
            return;
        }

        const formData = new FormData(this);
        fetch("{{ route('review.store') }}", {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message);
                closeReviewModal();
                setTimeout(() => location.reload(), 1500);
            }
        });
    });

    function showToast(message) {
        const toast = document.getElementById('toast');
        document.getElementById('toastMsg').textContent = message;
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
