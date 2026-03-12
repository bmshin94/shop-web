@extends('layouts.app')

@section('title', '문의 작성 - Active Women\'s Premium Store')

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
                        <a href="{{ route('product-detail', ['slug' => 'fake-product']) }}" class="hover:text-primary transition-colors">상품 상세</a>
                    </li>
                    <li class="flex items-center">
                        <span class="material-symbols-outlined text-sm mx-1">chevron_right</span>
                        <span class="text-text-main font-bold">문의 작성</span>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Q&A Write Form -->
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-10">
        <!-- Page Title -->
        <div class="mb-8">
            <h2 class="text-2xl font-extrabold text-text-main tracking-tight">상품 문의 작성 </h2>
            <p class="mt-2 text-sm text-text-muted italic">상품에 대해 궁금한 점을 카리나에게 물어보세요! 친절하게 답해줄게~ </p>
        </div>

        <!-- Product Info Card -->
        <div class="bg-white rounded-2xl border border-gray-200 p-5 mb-8 shadow-sm">
            <div class="flex gap-4 items-center">
                <div class="w-20 h-20 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0 border border-gray-50">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBBGlHCHqpU42xe1u1nLQnFERzQJOA7muFfDQ_jSqXFxwt2Qxr2iT_3nqpzXFQvdA7kxjmLVeJtijR2g1hJOeG6-K6F9eFwjzwrcYbk3-T4Gg5MrS8mhcXkscPSd_3e5y8sWa3SRvjNR5gw00r3uT1TkKPWnhWkHu6wMi_rvlIR5WjU0MrCPcRVwQ_rXJqUt2R-E8aL-H_g43iAKNkkHFekCx_I_Vwq2kh3cAPi_cVPBhBOiCk1S_FKQCFq2ldTPOoN2vBH8pBD0"
                        alt="상품 이미지" class="w-full h-full object-cover" />
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-primary font-bold mb-1 uppercase tracking-wider">Product Info</p>
                    <h3 class="text-base font-bold text-text-main truncate">위켄드 워리어 셋업</h3>
                    <p class="text-sm text-text-muted mt-1 font-medium">오트밀 화이트 / M 사이즈</p>
                </div>
            </div>
        </div>

        <!-- Question Form -->
        <form id="qnaForm" class="space-y-8">
            <!-- Category -->
            <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm">
                <h3 class="text-lg font-bold text-text-main mb-6">어떤 점이 궁금해? </h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @foreach(['size' => ['label' => '사이즈', 'icon' => 'straighten'], 'delivery' => ['label' => '배송', 'icon' => 'local_shipping'], 'restock' => ['label' => '재입고', 'icon' => 'inventory'], 'etc' => ['label' => '기타', 'icon' => 'help']] as $key => $data)
                    <button type="button" onclick="selectCategory('{{ $key }}', this)"
                        class="q-btn flex flex-col items-center gap-2 rounded-2xl border-2 border-gray-100 p-5 text-sm font-bold text-text-muted transition-all hover:border-primary/30 hover:text-primary">
                        <span class="material-symbols-outlined text-2xl">{{ $data['icon'] }}</span>
                        {{ $data['label'] }}
                    </button>
                    @endforeach
                </div>
                <input type="hidden" id="category" name="category" required>
            </div>

            <!-- Content -->
            <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm">
                <div class="mb-6">
                    <label class="block text-sm font-bold text-text-main mb-3 ml-1">제목</label>
                    <input type="text" placeholder="제목을 입력해주세요 (최대 80자)" required
                        class="w-full rounded-2xl border-gray-200 px-5 py-4 text-sm text-text-main focus:border-primary focus:ring-primary transition-all shadow-inner bg-gray-50/30">
                </div>
                <div>
                    <label class="block text-sm font-bold text-text-main mb-3 ml-1">문의 내용</label>
                    <textarea rows="8" placeholder="궁금한 내용을 자세히 적어주세요. 카리나가 꼼꼼히 읽어볼게! " required
                        class="w-full rounded-2xl border-gray-200 px-5 py-4 text-sm text-text-main focus:border-primary focus:ring-primary transition-all resize-none shadow-inner bg-gray-50/30 leading-relaxed"></textarea>
                </div>
            </div>

            <!-- Photos -->
            <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm">
                <h3 class="text-lg font-bold text-text-main mb-2">사진 첨부 (선택)</h3>
                <p class="text-sm text-text-muted mb-6">상세한 상담을 위해 사진이 있다면 같이 올려줘! </p>
                <div class="flex gap-4">
                    <label class="flex flex-col items-center justify-center w-24 h-24 rounded-2xl border-2 border-dashed border-gray-200 cursor-pointer hover:border-primary hover:bg-primary-light/30 transition-all text-gray-400 hover:text-primary group">
                        <span class="material-symbols-outlined text-2xl group-hover:scale-110 transition-transform">add_a_photo</span>
                        <span class="text-[10px] font-bold mt-1">사진 추가</span>
                        <input type="file" class="hidden" accept="image/*">
                    </label>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 pt-4">
                <a href="{{ route('product-detail', ['slug' => 'fake-product']) }}" class="flex-1 py-5 bg-white border-2 border-gray-200 text-text-main font-bold rounded-2xl hover:bg-gray-50 transition-all text-center">취소</a>
                <button type="submit" class="flex-[2] py-5 bg-primary text-white font-black rounded-2xl hover:bg-red-600 transition-all shadow-lg shadow-primary/20 transform hover:-translate-y-1">문의 등록하기 </button>
            </div>
        </form>
    </div>
</main>
@endsection

@push('scripts')
<script>
    function selectCategory(cat, btn) {
        document.querySelectorAll('.q-btn').forEach(b => b.classList.remove('border-primary', 'text-primary', 'bg-primary-light/30'));
        btn.classList.add('border-primary', 'text-primary', 'bg-primary-light/30');
        document.getElementById('category').value = cat;
    }
</script>
@endpush
