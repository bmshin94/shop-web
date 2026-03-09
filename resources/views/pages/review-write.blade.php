@extends('layouts.app')

@section('title', '리뷰 작성 - Active Women\'s Premium Store')

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
                        <a href="{{ route('product-detail') }}" class="hover:text-primary transition-colors">상품 상세</a>
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
            <h2 class="text-2xl font-extrabold text-text-main tracking-tight">리뷰 작성 </h2>
            <p class="mt-2 text-sm text-text-muted italic">자기가 산 옷 어때? 다른 사람들에게도 자랑해줘! </p>
        </div>

        <!-- Product Info Card -->
        <div class="bg-white rounded-2xl border border-gray-200 p-5 mb-8 shadow-sm">
            <div class="flex gap-4 items-center">
                <div class="w-20 h-20 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0 border border-gray-50">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBBGlHCHqpU42xe1u1nLQnFERzQJOA7muFfDQ_jSqXFxwt2Qxr2iT_3nqpzXFQvdA7kxjmLVeJtijR2g1hJOeG6-K6F9eFwjzwrcYbk3-T4Gg5MrS8mhcXkscPSd_3e5y8sWa3SRvjNR5gw00r3uT1TkKPWnhWkHu6wMi_rvlIR5WjU0MrCPcRVwQ_rXJqUt2R-E8aL-H_g43iAKNkkHFekCx_I_Vwq2kh3cAPi_cVPBhBOiCk1S_FKQCFq2ldTPOoN2vBH8pBD0"
                        alt="상품 이미지" class="w-full h-full object-cover" />
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-primary font-bold mb-1 uppercase tracking-wider">Purchase Item</p>
                    <h3 class="text-base font-bold text-text-main truncate">위켄드 워리어 셋업</h3>
                    <p class="text-sm text-text-muted mt-1 font-medium">오트밀 화이트 / M 사이즈</p>
                </div>
            </div>
        </div>

        <!-- Review Form -->
        <form id="reviewForm" class="space-y-8">
            <!-- Rating -->
            <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm text-center">
                <h3 class="text-lg font-bold text-text-main mb-4">상품은 만족하셨나요? </h3>
                <div class="flex justify-center gap-2 mb-4">
                    @for ($i = 1; $i <= 5; $i++)
                    <button type="button" onclick="setRating({{ $i }})" class="star-btn text-gray-200 transition-all hover:scale-110">
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
                    <input type="text" placeholder="제목을 입력해주세요 (최대 50자)" required
                        class="w-full rounded-2xl border-gray-200 px-5 py-4 text-sm text-text-main focus:border-primary focus:ring-primary transition-all bg-gray-50/30">
                </div>
                <div>
                    <label class="block text-sm font-bold text-text-main mb-3 ml-1">상세 후기</label>
                    <textarea rows="8" placeholder="최소 20자 이상 작성해주세요. 착용감, 사이즈 팁 등을 공유해주시면 카리나가 너무 기쁠 거야! " required
                        class="w-full rounded-2xl border-gray-200 px-5 py-4 text-sm text-text-main focus:border-primary focus:ring-primary transition-all resize-none bg-gray-50/30 leading-relaxed"></textarea>
                </div>
            </div>

            <!-- Photos -->
            <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm">
                <h3 class="text-lg font-bold text-text-main mb-2">포토 리뷰 (선택) </h3>
                <p class="text-sm text-text-muted mb-6 italic">멋진 착용샷을 올려주시면 포인트 500P를 선물로 줄게! </p>
                <div class="flex gap-4">
                    <label class="flex flex-col items-center justify-center w-24 h-24 rounded-2xl border-2 border-dashed border-gray-200 cursor-pointer hover:border-primary hover:bg-primary-light/30 transition-all text-gray-400 hover:text-primary group">
                        <span class="material-symbols-outlined text-2xl group-hover:scale-110 transition-transform">add_a_photo</span>
                        <span class="text-[10px] font-bold mt-1">사진 추가</span>
                        <input type="file" class="hidden" accept="image/*" multiple>
                    </label>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 pt-4">
                <a href="{{ route('mypage.review') }}" class="flex-1 py-5 bg-white border-2 border-gray-200 text-text-main font-bold rounded-2xl hover:bg-gray-50 transition-all text-center">나중에 쓸게요</a>
                <button type="submit" class="flex-[2] py-5 bg-primary text-white font-black rounded-2xl hover:bg-red-600 transition-all shadow-lg shadow-primary/20 transform hover:-translate-y-1">리뷰 등록하기 </button>
            </div>
        </form>
    </div>
</main>
@endsection

@push('scripts')
<script>
    const labels = ["별로예요 ", "그저 그래요 ", "보통이에요 ", "맘에 들어요 ", "아주 좋아요! "];
    function setRating(rating) {
        const stars = document.querySelectorAll('.star-btn');
        stars.forEach((s, i) => {
            s.classList.toggle('text-yellow-400', i < rating);
            s.classList.toggle('text-gray-200', i >= rating);
        });
        document.getElementById('ratingText').innerText = labels[rating - 1];
        document.getElementById('rating').value = rating;
    }
</script>
@endpush
