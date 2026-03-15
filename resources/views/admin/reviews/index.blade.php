@extends('layouts.admin')

@section('title', 'Customer Reviews Management - Admin Premium')

@section('content')
<div class="container-fluid px-6 py-8">
    {{-- Header Section --}}
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-text-main tracking-tight flex items-center gap-3">
                <span class="size-12 rounded-2xl bg-yellow-400/10 text-yellow-500 flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl">star</span>
                </span>
                Customer Reviews ✨⭐
            </h1>
            <p class="mt-2 text-sm font-medium text-text-muted italic ml-15">우리 소중한 고객님들의 찐후기를 카리나가 관리해줄게! 😍💖</p>
        </div>
    </div>

    {{-- Main Card --}}
    <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
        {{-- Filter Area --}}
        <div class="p-8 border-b border-gray-50 bg-gray-50/30">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-6 relative group/search">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        class="w-full h-12 pl-12 pr-4 rounded-2xl border-gray-200 bg-white text-sm font-bold text-text-main focus:border-primary focus:ring-primary/10 transition-all"
                        placeholder="상품명, 작성자, 리뷰 내용으로 검색...">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within/search:text-primary transition-colors">search</span>
                </div>
                <div class="md:col-span-3">
                    <select name="rating" class="w-full h-12 px-4 rounded-2xl border-gray-200 bg-white text-sm font-bold text-text-main focus:border-primary focus:ring-0 cursor-pointer">
                        <option value="">평점 전체 (All Stars)</option>
                        @foreach([5, 4, 3, 2, 1] as $r)
                        <option value="{{ $r }}" {{ request('rating') == $r ? 'selected' : '' }}>{{ $r }}점 {{ str_repeat('⭐', $r) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-3 flex gap-2">
                    <button type="submit" class="flex-1 h-12 bg-text-main text-white rounded-2xl font-black text-sm hover:bg-primary transition-all shadow-lg shadow-gray-200 active:scale-95">Filter</button>
                    @if(request()->anyFilled(['search', 'rating']))
                    <a href="{{ route('admin.reviews.index') }}" class="size-12 bg-white border border-gray-200 text-text-muted rounded-2xl flex items-center justify-center hover:bg-gray-50 transition-all" title="초기화">
                        <span class="material-symbols-outlined">restart_alt</span>
                    </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Table Area --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[11px] font-black uppercase tracking-widest text-text-muted bg-gray-50/50">
                        <th class="px-8 py-5">Product Info</th>
                        <th class="px-6 py-5">Rating & Review</th>
                        <th class="px-6 py-5">Author</th>
                        <th class="px-6 py-5 text-right">Date</th>
                        <th class="px-8 py-5 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($reviews as $review)
                    <tr class="hover:bg-gray-50/20 transition-colors group">
                        <td class="px-8 py-6 max-w-[280px]">
                            <div class="flex items-center gap-4">
                                <div class="size-14 rounded-2xl bg-gray-100 overflow-hidden border border-white shadow-sm shrink-0">
                                    <img src="{{ $review->product->image_url ?? 'https://via.placeholder.com/100' }}" class="size-full object-cover">
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-black text-text-main truncate mb-1 group-hover:text-primary transition-colors cursor-pointer" onclick="showReviewDetail({{ $review->id }})">{{ $review->product->name }}</p>
                                    <p class="text-[10px] text-text-muted font-bold tracking-tighter uppercase">ID: #{{ $review->product->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6 min-w-[320px]">
                            <div class="cursor-pointer" onclick="showReviewDetail({{ $review->id }})">
                                <div class="flex items-center gap-1 mb-2">
                                    @for($i=1; $i<=5; $i++)
                                    <span class="material-symbols-outlined text-[16px] {{ $i <= $review->rating ? 'text-yellow-400 filled' : 'text-gray-200' }}" 
                                          style="{{ $i <= $review->rating ? "font-variation-settings: 'FILL' 1" : "" }}">star</span>
                                    @endfor
                                    <span class="ml-1 text-xs font-black text-text-main">{{ $review->rating }}.0</span>
                                </div>
                                <h4 class="text-sm font-black text-text-main mb-1">{{ $review->title }}</h4>
                                <p class="text-xs text-text-muted line-clamp-2 leading-relaxed">{{ $review->content }}</p>
                                @if($review->images)
                                <span class="mt-3 inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-info/5 text-info text-[9px] font-black uppercase border border-info/10">
                                    <span class="material-symbols-outlined text-[12px]">image</span> Photo Review
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <div class="flex items-center gap-3">
                                <div class="size-9 rounded-full bg-primary/5 text-primary flex items-center justify-center text-xs font-black border border-primary/10">
                                    {{ mb_substr($review->member->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-text-main leading-none mb-1">{{ $review->member->name }}</p>
                                    <p class="text-[10px] text-text-muted">{{ $review->member->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6 text-right">
                            <p class="text-xs font-black text-text-main mb-0.5">{{ $review->created_at->format('Y.m.d') }}</p>
                            <p class="text-[10px] text-text-muted">{{ $review->created_at->format('H:i') }}</p>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="showReviewDetail({{ $review->id }})" class="size-9 rounded-xl bg-gray-50 text-gray-400 hover:bg-primary-light hover:text-primary transition-all flex items-center justify-center" title="상세보기">
                                    <span class="material-symbols-outlined text-lg">visibility</span>
                                </button>
                                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="js-confirm-submit" data-confirm-message="정말 이 리뷰를 삭제하시겠습니까? 🧹">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="size-9 rounded-xl bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-500 transition-all flex items-center justify-center" title="삭제하기">
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-32 text-center">
                            <div class="size-20 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-6 text-gray-200">
                                <span class="material-symbols-outlined text-5xl">rate_review</span>
                            </div>
                            <p class="text-text-muted font-black">아직 등록된 리뷰가 없습니다. ✨</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-8 bg-gray-50/30 border-t border-gray-50">
            {{ $reviews->links() }}
        </div>
    </div>
</div>

{{-- Detail Modal ✨📸 --}}
<div id="reviewDetailModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4 animate-in fade-in duration-200">
    <div class="relative bg-white rounded-[2.5rem] shadow-2xl max-w-2xl w-full overflow-hidden animate-in zoom-in duration-300">
        <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
            <h3 class="text-xl font-black text-text-main flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">rate_review</span>
                Review Details
            </h3>
            <button type="button" onclick="closeReviewModal()" class="size-10 flex items-center justify-center rounded-2xl hover:bg-gray-200 text-gray-400 transition-all">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div id="reviewDetailContent" class="p-10 max-h-[70vh] overflow-y-auto scrollbar-hide">
            <!-- AJAX Content 😊 -->
        </div>
    </div>
</div>

<style>
    .filled { font-variation-settings: 'FILL' 1; }
    /* Pagination Style Override ✨ */
    .pagination { @apply flex gap-1 justify-center; }
    .page-item .page-link { @apply border-0 rounded-xl size-10 flex items-center justify-center font-bold text-sm text-text-muted hover:bg-white hover:shadow-md transition-all; }
    .page-item.active .page-link { @apply bg-primary text-white shadow-lg shadow-primary/20; }
</style>
@endsection

@push('scripts')
<script>
    function showReviewDetail(id) {
        const modal = document.getElementById('reviewDetailModal');
        $.ajax({
            url: `/admin/reviews/${id}`,
            success: function(data) {
                let stars = '';
                for(let i=1; i<=5; i++) {
                    stars += `<span class="material-symbols-outlined text-lg ${i <= data.rating ? 'text-yellow-400 filled' : 'text-gray-200'}" style="${i <= data.rating ? "font-variation-settings: 'FILL' 1" : ""}">star</span>`;
                }

                let imagesHtml = '';
                if (data.images && data.images.length > 0) {
                    imagesHtml = '<div class="grid grid-cols-2 gap-4 mt-8">';
                    data.images.forEach(img => {
                        imagesHtml += `<img src="${img}" class="w-full aspect-square object-cover rounded-3xl border border-gray-100 shadow-sm">`;
                    });
                    imagesHtml += '</div>';
                }

                const html = `
                    <div class="flex items-center gap-6 mb-10 pb-8 border-b border-gray-50">
                        <div class="size-20 rounded-[1.5rem] bg-gray-50 overflow-hidden border-4 border-white shadow-xl">
                            <img src="${data.product.image_url || 'https://via.placeholder.com/150'}" class="size-full object-cover">
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-1">Product Info</p>
                            <h4 class="text-xl font-black text-text-main leading-tight mb-2">${data.product.name}</h4>
                            <div class="flex items-center gap-1">${stars}</div>
                        </div>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="size-10 rounded-2xl bg-text-main text-white flex items-center justify-center text-sm font-black shadow-lg">
                                    ${data.member.name.substring(0, 1)}
                                </div>
                                <div>
                                    <p class="text-sm font-black text-text-main leading-none mb-1">${data.member.name}</p>
                                    <p class="text-[10px] text-text-muted">${data.member.email}</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-text-muted px-4 py-1.5 bg-gray-50 rounded-full border border-gray-100">${new Date(data.created_at).toLocaleDateString('ko-KR', { year: 'numeric', month: 'long', day: 'numeric' })}</span>
                        </div>
                        
                        <div class="bg-gray-50/50 p-8 rounded-[2rem] border border-gray-100">
                            <h5 class="text-lg font-black text-text-main mb-4">"${data.title}"</h5>
                            <p class="text-text-muted text-sm leading-relaxed whitespace-pre-wrap font-medium">${data.content}</p>
                        </div>
                        ${imagesHtml}
                    </div>
                `;
                $('#reviewDetailContent').html(html);
                modal.classList.remove('hidden');
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        });
    }

    function closeReviewModal() {
        const modal = document.getElementById('reviewDetailModal');
        modal.classList.add('hidden');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    $(document).ready(function() {
        $('#reviewDetailModal').on('click', function(e) {
            if (e.target === this) closeReviewModal();
        });
    });
</script>
@endpush
