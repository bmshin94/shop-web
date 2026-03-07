@extends('layouts.admin')

@section('page_title', '상품 상세 정보')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 lg:space-y-8 font-display">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.index') }}" class="flex items-center justify-center size-10 bg-white border border-gray-200 rounded-xl text-text-muted hover:text-primary hover:border-primary/30 hover:bg-red-50 transition-all shadow-sm">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <h3 class="text-xl lg:text-2xl font-extrabold text-text-main">상품 상세 정보</h3>
                <p class="text-[12px] font-bold text-text-muted tracking-tight mt-0.5">ID: {{ $product->id }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <!-- 수정/삭제 버튼 진짜 연결 완료! 😊 -->
            <a href="{{ route('admin.products.edit', $product) }}" class="px-5 py-2.5 bg-white border border-gray-200 text-text-main text-[12px] font-bold rounded-xl hover:bg-gray-50 transition-colors shadow-sm flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px]">edit</span> 수정
            </a>
            <button type="button" onclick="openDeleteModal('{{ $product->id }}', '{{ $product->name }}')" class="px-5 py-2.5 bg-red-50 text-red-600 text-[12px] font-bold rounded-xl hover:bg-red-100 transition-colors shadow-sm flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px]">delete</span> 삭제
            </button>
            <form id="delete-form-{{ $product->id }}" action="{{ route('admin.products.destroy', $product) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        
        <!-- Left: Image Gallery -->
        <div class="lg:col-span-1 space-y-4">
            <!-- Main Image -->
            <div class="bg-white rounded-3xl p-4 shadow-sm border border-gray-100 flex items-center justify-center aspect-[3/4] overflow-hidden relative group">
                @if($product->image_url)
                    @if(Str::startsWith($product->image_url, 'http'))
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-2xl transition-transform duration-500 group-hover:scale-105">
                    @else
                        <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-2xl transition-transform duration-500 group-hover:scale-105">
                    @endif
                @else
                    <div class="flex flex-col items-center justify-center text-gray-300">
                        <span class="material-symbols-outlined text-4xl mb-2">image</span>
                        <span class="text-[11px] font-bold tracking-tight">이미지 없음</span>
                    </div>
                @endif
                
                <!-- Status Badge Overlay -->
                <div class="absolute top-6 left-6 flex flex-col gap-1.5">
                    @if($product->status == '판매중')
                        <span class="px-2.5 py-1 bg-green-500/90 backdrop-blur text-white text-[11px] font-bold rounded-lg shadow-sm">판매중</span>
                    @elseif($product->status == '품절')
                        <span class="px-2.5 py-1 bg-red-500/90 backdrop-blur text-white text-[11px] font-bold rounded-lg shadow-sm">품절</span>
                    @else
                        <span class="px-2.5 py-1 bg-gray-600/90 backdrop-blur text-white text-[11px] font-bold rounded-lg shadow-sm">숨김</span>
                    @endif
                    
                    @if($product->is_new)
                        <span class="px-2.5 py-1 bg-blue-500/90 backdrop-blur text-white text-[11px] font-bold rounded-lg shadow-sm">NEW</span>
                    @endif
                    @if($product->is_best)
                        <span class="px-2.5 py-1 bg-amber-500/90 backdrop-blur text-white text-[11px] font-bold rounded-lg shadow-sm">BEST</span>
                    @endif
                </div>
            </div>
            
            <!-- Additional Images (If any) -->
            @if($product->images && $product->images->count() > 1)
            <div class="grid grid-cols-4 gap-3">
                @foreach($product->images->skip(1) as $img)
                <div class="bg-white rounded-2xl p-2 shadow-sm border border-gray-100 aspect-square overflow-hidden cursor-pointer hover:border-primary transition-colors">
                    @if(Str::startsWith($img->image_path, 'http'))
                        <img src="{{ $img->image_path }}" class="w-full h-full object-cover rounded-xl">
                    @else
                        <img src="{{ asset($img->image_path) }}" class="w-full h-full object-cover rounded-xl">
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Right: Details -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Basic Info Card -->
            <div class="bg-white rounded-3xl p-6 lg:p-8 shadow-sm border border-gray-100 relative overflow-hidden">
                <!-- Background decoration -->
                <div class="absolute -top-10 -right-10 size-40 bg-gray-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>

                <div class="relative z-10 space-y-6">
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 mb-1">
                            @if($product->category)
                                <span class="text-[10px] font-bold text-text-muted/60 uppercase tracking-tighter">{{ $product->category->parent->name ?? '독립분류' }}</span>
                                <span class="text-gray-300 text-[10px] material-symbols-outlined">chevron_right</span>
                                <span class="text-[11px] font-bold text-primary tracking-tight">{{ $product->category->name }}</span>
                            @else
                                <span class="text-[11px] font-bold text-text-muted tracking-tight">미지정 카테고리</span>
                            @endif
                        </div>
                        <h1 class="text-2xl lg:text-3xl font-black text-text-main leading-tight">{{ $product->name }}</h1>
                        <p class="text-[12px] font-bold text-text-muted font-mono tracking-tight bg-gray-50 px-3 py-1.5 rounded-lg inline-block border border-gray-100">
                            /products/{{ $product->slug }}
                        </p>
                    </div>

                    <div class="pt-6 border-t border-gray-50 grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Price Info -->
                        <div class="space-y-4">
                            <div>
                                <p class="text-[11px] font-bold text-text-muted mb-1 uppercase tracking-widest">정상 판매가</p>
                                <p class="text-lg font-bold text-text-main {{ $product->sale_price ? 'line-through text-gray-400' : '' }}">₩{{ number_format($product->price) }}</p>
                            </div>
                            @if($product->sale_price)
                            <div>
                                <p class="text-[11px] font-bold text-primary mb-1 uppercase tracking-widest flex items-center gap-1">
                                    할인 판매가
                                    @if($product->discount_rate > 0)
                                        <span class="px-1.5 py-0.5 bg-red-100 text-red-600 text-[9px] rounded font-black">{{ $product->discount_rate }}% OFF</span>
                                    @endif
                                </p>
                                <p class="text-2xl lg:text-3xl font-black text-primary">₩{{ number_format($product->sale_price) }}</p>
                            </div>
                            @endif
                        </div>

                        <!-- Stock & System Info -->
                        <div class="space-y-4 sm:border-l border-gray-50 sm:pl-6">
                            <div>
                                <p class="text-[11px] font-bold text-text-muted mb-1.5 uppercase tracking-widest">재고 현황</p>
                                <div class="flex items-center gap-2">
                                    <div class="px-3 py-1.5 bg-gray-50 rounded-lg border border-gray-100 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[16px] {{ $product->stock_quantity <= 5 ? 'text-red-500' : 'text-text-main' }}">inventory_2</span>
                                        <span class="text-sm font-extrabold {{ $product->stock_quantity <= 5 ? 'text-red-600' : 'text-text-main' }}">{{ number_format($product->stock_quantity) }}개</span>
                                    </div>
                                    @if($product->stock_quantity <= 5)
                                        <span class="text-[10px] font-bold text-red-500 tracking-tight">재고 부족 임박!</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold text-text-muted mb-1 uppercase tracking-widest">등록 일시</p>
                                <p class="text-[12px] font-bold text-text-main">{{ $product->created_at->format('Y년 m월 d일 H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold text-text-muted mb-1 uppercase tracking-widest">최근 수정</p>
                                <p class="text-[12px] font-bold text-text-main">{{ $product->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description Card -->
            <div class="bg-white rounded-3xl p-6 lg:p-8 shadow-sm border border-gray-100">
                <h4 class="text-base font-bold text-text-main mb-6 flex items-center gap-2 pb-4 border-b border-gray-50">
                    <span class="material-symbols-outlined text-primary text-[20px]">article</span> 상세 설명
                </h4>
                
                <div class="prose prose-sm max-w-none text-[13px] text-text-main leading-relaxed font-normal ck-content">
                    @if($product->description)
                        {!! $product->description !!}
                    @else
                        <div class="text-center py-10 bg-gray-50 rounded-2xl border border-gray-100 border-dashed">
                            <span class="material-symbols-outlined text-gray-300 text-3xl mb-2 block">format_ink_highlighter</span>
                            <p class="text-[12px] font-bold text-text-muted">등록된 상세 설명이 없습니다.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Custom Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full overflow-hidden modal-animate-in">
        <div class="p-8 text-center">
            <div class="size-16 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-6">
                <span class="material-symbols-outlined text-[32px]">delete_forever</span>
            </div>
            <h4 id="modal-title" class="text-xl font-bold text-text-main mb-2">상품 삭제</h4>
            <p id="modal-desc" class="text-[11px] font-bold text-text-muted tracking-tight leading-relaxed">정말 삭제하시겠습니까?<br>삭제된 데이터와 이미지는 복구할 수 없습니다.</p>
        </div>
        <div class="flex border-t border-gray-100">
            <button onclick="closeDeleteModal()" class="flex-1 px-6 py-4 text-sm font-bold text-text-muted hover:bg-gray-50 transition-colors border-r border-gray-100">
                취소
            </button>
            <button id="confirm-delete-btn" class="flex-1 px-6 py-4 text-sm font-bold text-red-600 hover:bg-red-50 transition-colors">
                삭제하기
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentDeleteId = null;

    function openDeleteModal(id, name) {
        currentDeleteId = id;
        $('#modal-title').text(`'${name}' 삭제`);
        $('#delete-modal').removeClass('hidden').addClass('flex');
        $('body').addClass('overflow-hidden');
    }

    function closeDeleteModal() {
        $('#delete-modal').removeClass('flex').addClass('hidden');
        $('body').removeClass('overflow-hidden');
        currentDeleteId = null;
    }

    $(document).ready(function() {
        // 모달 외부 클릭 시 닫기 😊
        $('#delete-modal').on('click', function(e) {
            if (e.target === this) closeDeleteModal();
        });

        // 진짜 삭제 버튼 클릭! 🚀
        $('#confirm-delete-btn').on('click', function() {
            if (currentDeleteId) {
                document.getElementById(`delete-form-${currentDeleteId}`).submit();
            }
        });
    });
</script>
@endpush