@extends('layouts.app')

@section('title', '리뷰 작성 - ' . $product->name)

@section('content')
<main class="flex-1 bg-background-alt pb-20">
    <!-- Breadcrumb -->
    <div class="bg-white py-4 border-b border-gray-100 shadow-sm">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <nav class="flex text-xs text-text-muted" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li><a href="/" class="hover:text-primary transition-colors">Home</a></li>
                    <li class="flex items-center">
                        <span class="material-symbols-outlined text-sm mx-1">chevron_right</span>
                        <a href="{{ route('product-detail', ['slug' => $product->slug]) }}" class="hover:text-primary transition-colors">상품 상세</a>
                    </li>
                    <li class="flex items-center">
                        <span class="material-symbols-outlined text-sm mx-1">chevron_right</span>
                        <span class="text-text-main font-bold">리뷰 작성</span>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Review Write Form -->
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-10">
        <!-- Page Title -->
        <div class="mb-8">
            <h2 class="text-3xl font-extrabold text-text-main tracking-tight">리뷰 작성</h2>
            <p class="mt-2 text-sm text-text-muted italic">구매하신 상품은 어떠셨나요? 솔직한 후기를 들려주세요!</p>
        </div>

        <!-- Product Info Card -->
        <div class="bg-white rounded-3xl border border-gray-100 p-6 mb-8 shadow-sm flex items-center gap-5">
            <div class="size-24 rounded-2xl overflow-hidden bg-gray-100 shrink-0 border border-gray-50 shadow-inner">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover" />
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs text-primary font-bold mb-1 uppercase tracking-wider">Purchased Item</p>
                <h3 class="text-xl font-bold text-text-main truncate">{{ $product->name }}</h3>
                <p class="text-sm text-text-muted mt-1 font-medium">{{ $product->category->name ?? 'Premium Item' }}</p>
            </div>
        </div>

        <!-- Review Form -->
        <form id="reviewForm" class="space-y-8" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            
            <!-- Rating -->
            <div class="bg-white rounded-3xl border border-gray-100 p-10 shadow-sm text-center">
                <h3 class="text-lg font-bold text-text-main mb-6">상품은 만족하셨나요?</h3>
                <div class="flex justify-center gap-2 mb-4" id="starRatingContainer">
                    @for ($i = 1; $i <= 5; $i++)
                    <button type="button" data-rating="{{ $i }}" class="star-btn text-gray-300 transition-all hover:scale-110">
                        <span class="material-symbols-outlined text-5xl" style="font-variation-settings: 'FILL' 1;">star</span>
                    </button>
                    @endfor
                </div>
                <p id="ratingText" class="text-sm font-bold text-primary">별점을 선택해주세요</p>
                <input type="hidden" id="rating" name="rating" required>
            </div>

            <!-- Content -->
            <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm">
                <div class="mb-6">
                    <label class="block text-sm font-bold text-text-main mb-3 ml-1">리뷰 제목</label>
                    <input type="text" name="title" placeholder="제목을 입력해주세요 (최대 50자)" required
                        class="w-full rounded-2xl border-gray-200 px-5 py-4 text-sm text-text-main focus:border-primary focus:ring-primary transition-all bg-gray-50/30">
                </div>
                <div>
                    <label class="block text-sm font-bold text-text-main mb-3 ml-1">상세 후기</label>
                    <textarea name="content" rows="8" placeholder="최소 10자 이상 작성해주세요. 착용감, 사이즈 팁 등을 공유해주시면 관리자가 너무 기쁠 거야! " required
                        class="w-full rounded-2xl border-gray-200 px-5 py-4 text-sm text-text-main focus:border-primary focus:ring-primary transition-all resize-none bg-gray-50/30 leading-relaxed"></textarea>
                </div>
            </div>

            <!-- Photos -->
            <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm">
                <h3 class="text-lg font-bold text-text-main mb-2">포토 리뷰 (선택) </h3>
                <p class="text-sm text-text-muted mb-6 italic">멋진 착용샷을 올려주시면 포인트 500P를 선물로 줄게! </p>
                <div class="flex flex-wrap gap-4" id="image-preview-container">
                    <label class="flex flex-col items-center justify-center w-24 h-24 rounded-2xl border-2 border-dashed border-gray-200 cursor-pointer hover:border-primary hover:bg-primary-light/30 transition-all text-gray-400 hover:text-primary group">
                        <span class="material-symbols-outlined text-2xl group-hover:scale-110 transition-transform">add_a_photo</span>
                        <span class="text-[10px] font-bold mt-1">사진 추가</span>
                        <input type="file" name="images[]" class="hidden" accept="image/*" multiple id="review-images">
                    </label>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 pt-4">
                <a href="{{ route('product-detail', ['slug' => $product->slug]) }}" class="flex-1 py-5 bg-white border-2 border-gray-200 text-text-main font-bold rounded-2xl hover:bg-gray-50 transition-all text-center">나중에 쓸게요</a>
                <button type="submit" class="flex-[2] py-5 bg-primary text-white font-black rounded-2xl hover:bg-red-600 transition-all shadow-lg shadow-primary/30 transform hover:-translate-y-1">리뷰 등록하기 </button>
            </div>
        </form>
    </div>
</main>

<div id="toastContainer" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[9998] flex flex-col items-center gap-3 pointer-events-none"></div>
@endsection

@push('scripts')
<script>
    const labels = ["아쉬워요", "그저 그래요", "보통이에요", "맘에 들어요", "최고에요!"];
    const starBtns = document.querySelectorAll('.star-btn');
    let currentRating = 0;

    function fillStars(rating) {
        starBtns.forEach((btn, index) => {
            if (index < rating) {
                btn.classList.replace('text-gray-300', 'text-yellow-400');
            } else {
                btn.classList.replace('text-yellow-400', 'text-gray-300');
            }
        });
    }

    starBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            currentRating = parseInt(e.currentTarget.getAttribute('data-rating'));
            fillStars(currentRating);
            document.getElementById('ratingText').innerText = labels[currentRating - 1];
            document.getElementById('rating').value = currentRating;
        });
    });

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

    // 이미지 미리보기 로직
    document.getElementById('review-images').addEventListener('change', function(e) {
        const container = document.getElementById('image-preview-container');
        // 기존 미리보기 삭제 (업로드 버튼 제외)
        const previews = container.querySelectorAll('.preview-item');
        previews.forEach(p => p.remove());

        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'preview-item w-24 h-24 rounded-2xl overflow-hidden border border-gray-100 relative';
                div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                container.appendChild(div);
            }
            reader.readAsDataURL(file);
        });
    });

    // 폼 제출 로직
    document.getElementById('reviewForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!document.getElementById('rating').value) {
            showToast("별점을 선택해주세요!", "error", "bg-red-500");
            return;
        }

        const formData = new FormData(this);
        
        fetch("{{ route('review.store') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message);
                setTimeout(() => {
                    location.href = data.redirect;
                }, 1500);
            } else {
                showToast(data.message || "오류가 발생했습니다.", "error", "bg-red-500");
            }
        })
        .catch(err => {
            showToast("리뷰 등록 중 오류가 발생했습니다.", "error", "bg-red-500");
        });
    });
</script>
@endpush
