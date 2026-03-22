@extends('layouts.admin')

@section('page_title', '카테고리 관리')

@push('styles')
<style>
    /* 드래그 중인 행 스타일 */
    .sortable-ghost { opacity: 0.2; background-color: #fef2f2 !important; border: 2px dashed #ec3713 !important; }
    .sortable-chosen { background-color: #fff !important; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); z-index: 100; }
    .drag-handle, .drag-handle-child { cursor: grab; }
    .drag-handle:active, .drag-handle-child:active { cursor: grabbing; }
    
    /* 반응형 계층형 리스트 그리드 설정  */
    .category-row { 
        display: grid; 
        align-items: center;
        grid-template-columns: 40px 1fr 50px 80px; /* 기본(모바일): 핸들 | 이름 | 상태 | 관리 */
        gap: 8px;
    }
    
    @media (min-width: 640px) {
        .category-row {
            grid-template-columns: 40px 1fr 60px 50px 80px; /* sm: + 상품수 */
            gap: 10px;
        }
    }

    @media (min-width: 768px) {
        .category-row {
            grid-template-columns: 48px 1.5fr 80px 80px 60px 100px; /* md: + 구분 추가 (핸들 | 이름 | 구분 | 상품수 | 상태 | 관리) */
            gap: 12px;
        }
    }

    @media (min-width: 1024px) {
        .category-row {
            grid-template-columns: 48px 2fr 100px 80px 1fr 80px 80px 120px; /* lg: 전체 노출 (핸들 | 이름 | 구분 | 상품수 | 슬러그 | 정렬 | 상태 | 관리) */
            gap: 16px;
        }
    }

    .child-container { border-left: 2px solid #f1f5f9; margin-left: 12px; }
    @media (min-width: 768px) { .child-container { margin-left: 24px; } }

    /* Modal Animation */
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.95) translateY(10px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    .modal-animate-in { animation: modalIn 0.2s ease-out forwards; }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Top Action Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <h3 class="text-lg lg:text-xl font-extrabold text-text-main">전체 카테고리 <span class="text-primary ml-1">{{ $categories->count() }}</span></h3>
            <div class="relative hidden sm:block">
                <input type="text" placeholder="검색..." class="w-48 lg:w-64 pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                <span class="material-symbols-outlined absolute left-3 top-2 text-text-muted">search</span>
            </div>
        </div>
        <div class="flex items-center gap-2 lg:gap-3">
            <button id="save-order" class="hidden flex-1 md:flex-none items-center justify-center gap-2 px-4 lg:px-6 py-2.5 lg:py-3 bg-gray-900 text-white text-sm lg:text-base font-bold rounded-xl shadow-lg hover:bg-black transition-all">
                <span class="material-symbols-outlined text-[20px]">save</span>
                <span class="hidden sm:inline">순서 저장</span>
                <span class="sm:hidden">저장</span>
            </button>
            <a href="{{ route('admin.categories.create') }}" class="flex-1 md:flex-none flex items-center justify-center gap-2 px-4 lg:px-6 py-2.5 lg:py-3 bg-primary text-white text-sm lg:text-base font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-red-600 transition-all">
                <span class="material-symbols-outlined text-[20px]">add</span>
                등록
            </a>
        </div>
    </div>

    <!-- Category Table Header -->
    <div class="bg-white rounded-t-2xl lg:rounded-t-3xl border-x border-t border-gray-100 overflow-hidden shadow-sm">
        <div class="category-row bg-gray-50/50 border-b border-gray-100 px-4 lg:px-6 py-3 lg:py-4">
            <div class="flex justify-center"><span class="material-symbols-outlined text-gray-300 text-[18px]">drag_indicator</span></div>
            <div class="text-[11px] font-bold text-text-muted uppercase">카테고리명</div>
            <div class="hidden md:block text-center text-[11px] font-bold text-text-muted uppercase">구분</div>
            <div class="hidden sm:block text-center text-[11px] font-bold text-text-muted uppercase">상품수</div>
            <div class="hidden lg:block text-[11px] font-bold text-text-muted uppercase">슬러그</div>
            <div class="hidden lg:block text-center text-[11px] font-bold text-text-muted uppercase">정렬</div>
            <div class="text-center text-[11px] font-bold text-text-muted uppercase">상태</div>
            <div class="text-center text-[11px] font-bold text-text-muted uppercase">관리</div>
        </div>
    </div>

    <!-- Category List -->
    <div id="parent-sortable" class="bg-white rounded-b-2xl lg:rounded-b-3xl border-x border-b border-gray-100 shadow-sm divide-y divide-gray-50 overflow-hidden">
        @forelse($categories as $parent)
        <div class="parent-group" data-id="{{ $parent->id }}">
            <!-- Parent Row -->
            <div class="category-row px-4 lg:px-6 py-3 lg:py-4 hover:bg-gray-50/50 transition-colors bg-white">
                <div class="flex items-center justify-center">
                    <span class="material-symbols-outlined text-gray-300 drag-handle hover:text-primary transition-colors text-[20px] lg:text-[22px]">drag_indicator</span>
                </div>
                <div class="flex items-center gap-2 lg:gap-3 min-w-0">
                    <div class="size-7 lg:size-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-bold shrink-0">
                        @if($parent->icon)
                            <span class="material-symbols-outlined text-[18px] lg:text-[20px]">{{ $parent->icon }}</span>
                        @else
                            <span class="text-[11px]">{{ mb_substr($parent->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs lg:text-sm font-bold text-text-main truncate">
                            {{ $parent->name }}
                            <span class="md:hidden text-[11px] font-bold text-primary ml-1 tracking-tight">({{ number_format($parent->children->sum('products_count')) }})</span>
                        </p>
                        <p class="text-[11px] font-bold text-text-muted tracking-tight">ID: {{ $parent->id }}</p>
                    </div>
                </div>
                <div class="hidden md:flex justify-center">
                    <span class="px-1.5 lg:px-2 py-0.5 bg-primary/10 text-primary text-[11px] font-bold rounded uppercase tracking-tight">대분류</span>
                </div>
                <div class="hidden sm:block text-center">
                    <span class="text-[11px] lg:text-sm font-bold text-text-main tracking-tight">{{ number_format($parent->children->sum('products_count')) }}</span>
                </div>
                <div class="hidden lg:block truncate pr-4">
                    <span class="text-[11px] font-mono text-text-muted bg-white border border-gray-100 px-2 py-0.5 rounded">/{{ $parent->slug }}</span>
                </div>
                <div class="hidden lg:block text-center">
                    <span class="parent-order text-[11px] lg:text-sm font-bold text-text-main bg-gray-100 px-3 py-1 rounded-full tracking-tight">{{ $parent->sort_order }}</span>
                </div>
                <div class="text-center">
                    @if($parent->is_active)
                        <span class="px-1.5 lg:px-2 py-0.5 bg-green-100 text-green-600 text-[11px] font-bold rounded-full whitespace-nowrap tracking-tight">노출</span>
                    @else
                        <span class="px-1.5 lg:px-2 py-0.5 bg-gray-100 text-gray-400 text-[11px] font-bold rounded-full whitespace-nowrap tracking-tight">숨김</span>
                    @endif
                </div>
                <div class="flex items-center justify-center gap-1 lg:gap-2">
                    <a href="{{ route('admin.categories.edit', $parent->id) }}" title="수정" class="size-7 lg:size-8 rounded-lg bg-white border border-gray-100 text-text-muted hover:bg-primary/10 hover:text-primary transition-all flex items-center justify-center shadow-sm">
                        <span class="material-symbols-outlined text-[16px] lg:text-[18px]">edit</span>
                    </a>
                    <button type="button" onclick="openDeleteModal('{{ $parent->id }}', '{{ $parent->name }}', true)" title="삭제" class="size-7 lg:size-8 rounded-lg bg-white border border-gray-100 text-text-muted hover:bg-red-50 hover:text-red-600 transition-all flex items-center justify-center shadow-sm">
                        <span class="material-symbols-outlined text-[16px] lg:text-[18px]">delete</span>
                    </button>
                    <form id="delete-form-{{ $parent->id }}" action="{{ route('admin.categories.destroy', $parent->id) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>

            <!-- Children Container -->
            <div class="child-sortable child-container bg-gray-50/10 divide-y divide-gray-50/50">
                @foreach($parent->children as $child)
                <div class="category-row px-4 lg:px-6 py-2 lg:py-3 bg-transparent group" data-id="{{ $child->id }}">
                    <div class="flex items-center justify-center">
                        <span class="material-symbols-outlined text-gray-200 drag-handle-child hover:text-gray-400 transition-colors text-[16px] lg:text-[18px]">drag_handle</span>
                    </div>
                    <div class="flex items-center gap-2 lg:gap-3 pl-5 lg:pl-8 relative min-w-0">
                        <span class="material-symbols-outlined text-gray-300 text-[16px] lg:text-[18px] absolute left-0">subdirectory_arrow_right</span>
                        <div class="min-w-0">
                            <p class="text-xs lg:text-sm font-bold text-text-main/80 truncate">
                                {{ $child->name }}
                                <span class="md:hidden text-[11px] font-bold text-primary ml-1 tracking-tight">({{ number_format($child->products_count ?? 0) }})</span>
                            </p>
                            <p class="text-[11px] font-bold text-text-muted tracking-tight">ID: {{ $child->id }}</p>
                        </div>
                    </div>
                    <div class="hidden md:flex justify-center">
                        <span class="px-1.5 lg:px-2 py-0.5 bg-gray-100 text-gray-500 text-[11px] font-bold rounded uppercase tracking-tight">소분류</span>
                    </div>
                    <div class="hidden sm:block text-center">
                        <span class="text-[11px] font-bold text-text-main/70 tracking-tight">{{ number_format($child->products_count ?? 0) }}</span>
                    </div>
                    <div class="hidden lg:block truncate pr-4">
                        <span class="text-[11px] font-mono text-text-muted bg-white border border-gray-100 px-2 py-0.5 rounded">/{{ $child->slug }}</span>
                    </div>
                    <div class="hidden lg:block text-center">
                        <span class="child-order text-[11px] font-bold text-text-muted bg-white border border-gray-200 px-2 py-0.5 rounded-full tracking-tight">{{ $child->sort_order }}</span>
                    </div>
                    <div class="text-center">
                        @if($child->is_active)
                            <span class="px-1 lg:px-1.5 py-0.5 bg-green-50 text-green-500 text-[11px] font-bold rounded-full tracking-tight">노출</span>
                        @else
                            <span class="px-1 lg:px-1.5 py-0.5 bg-gray-100 text-gray-400 text-[11px] font-bold rounded-full tracking-tight">숨김</span>
                        @endif
                    </div>
                    <div class="flex items-center justify-center gap-1 lg:gap-2 md:opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="{{ route('admin.categories.edit', $child->id) }}" title="수정" class="size-6 lg:size-7 rounded-md bg-white border border-gray-100 text-text-muted hover:text-primary transition-all flex items-center justify-center shadow-sm">
                            <span class="material-symbols-outlined text-[14px]">edit</span>
                        </a>
                        <button type="button" onclick="openDeleteModal('{{ $child->id }}', '{{ $child->name }}', false)" title="삭제" class="size-6 lg:size-7 rounded-md bg-white border border-gray-100 text-text-muted hover:bg-red-50 hover:text-red-600 transition-all flex items-center justify-center shadow-sm">
                            <span class="material-symbols-outlined text-[14px]">delete</span>
                        </button>
                        <form id="delete-form-{{ $child->id }}" action="{{ route('admin.categories.destroy', $child->id) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div class="px-6 py-20 text-center">
            <span class="material-symbols-outlined text-gray-200 text-[60px] mb-4">category</span>
            <p class="text-text-muted text-[11px] font-bold tracking-tight">등록된 카테고리가 없습니다.</p>
        </div>
        @endforelse
    </div>

    <!-- Detailed Guide Footer -->
    <div class="bg-white rounded-2xl lg:rounded-3xl p-6 lg:p-8 border border-gray-100 shadow-sm space-y-6">
        <div class="flex items-center gap-3 border-b border-gray-50 pb-4">
            <div class="size-9 lg:size-10 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px] lg:text-[24px]">menu_book</span>
            </div>
            <div>
                <h4 class="text-base lg:text-lg font-bold text-text-main">카테고리 관리 센터 이용 가이드</h4>
                <p class="text-[11px] font-bold text-text-muted tracking-tight">쇼핑몰의 뼈대를 튼튼하게 관리하는 방법을 확인해보세요.</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
            <div class="space-y-4">
                <div class="flex gap-3">
                    <span class="text-primary font-black text-sm lg:text-base">01.</span>
                    <div>
                        <h5 class="text-xs lg:text-sm font-extrabold text-text-main mb-1">계층형 카테고리 구조</h5>
                        <p class="text-[11px] font-bold text-text-muted tracking-tight leading-relaxed">우리 쇼핑몰은 대분류(1차)와 소분류(2차)로 구성됩니다. 소분류 등록 시 반드시 상위 카테고리를 선택해야 하며, 계층 구조는 상품 필터링과 내비게이션의 기준이 됩니다.</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <span class="text-primary font-black text-sm lg:text-base">02.</span>
                    <div>
                        <h5 class="text-xs lg:text-sm font-extrabold text-text-main mb-1">그룹 드래그 앤 드롭 정렬</h5>
                        <p class="text-[11px] font-bold text-text-muted tracking-tight leading-relaxed">대분류를 잡고 이동하면 하위 소분류들도 가족처럼 함께 이동합니다. 각 대분류 내부에서도 소분류들끼리 별도로 순서를 바꿀 수 있으니 아주 편리합니다.</p>
                    </div>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex gap-3">
                    <span class="text-primary font-black text-sm lg:text-base">03.</span>
                    <div>
                        <h5 class="text-xs lg:text-sm font-extrabold text-text-main mb-1">슬러그(Slug)의 중요성</h5>
                        <p class="text-[11px] font-bold text-text-muted tracking-tight leading-relaxed">슬러그는 상품 상세 페이지의 URL 고유 경로(예: /products/tops)로 사용됩니다. 영문 소문자와 하이픈(-) 사용을 권장하며, 중복된 슬러그는 자동으로 방지됩니다.</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <span class="text-primary font-black text-sm lg:text-base">04.</span>
                    <div>
                        <h5 class="text-xs lg:text-sm font-extrabold text-text-main mb-1">카테고리 삭제 시 유의사항</h5>
                        <p class="text-[11px] font-bold text-text-muted tracking-tight leading-relaxed">대분류를 삭제하면 하위에 포함된 모든 소분류들도 함께 삭제됩니다. 삭제된 데이터는 복구가 불가능하므로 신중하게 결정해주세요.</p>
                    </div>
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
            <h4 id="modal-title" class="text-xl font-bold text-text-main mb-2">카테고리 삭제</h4>
            <p id="modal-desc" class="text-[11px] font-bold text-text-muted tracking-tight leading-relaxed">정말 삭제하시겠습니까?<br>하위 카테고리도 모두 함께 삭제됩니다.</p>
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

<form id="reorder-form" action="{{ route('admin.categories.reorder') }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="order" id="order-input">
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    let currentDeleteId = null;

    function openDeleteModal(id, name, isParent) {
        currentDeleteId = id;
        $('#modal-title').text(`'${name}' 삭제`);
        if (isParent) {
            $('#modal-desc').html('정말 삭제하시겠습니까?<br>대분류 삭제 시 하위 소분류들도 모두 함께 삭제됩니다.');
        } else {
            $('#modal-desc').html('정말 삭제하시겠습니까?<br>삭제된 데이터는 복구할 수 없습니다.');
        }
        $('#delete-modal').removeClass('hidden').addClass('flex');
        $('body').addClass('overflow-hidden');
    }

    function closeDeleteModal() {
        $('#delete-modal').removeClass('flex').addClass('hidden');
        $('body').removeClass('overflow-hidden');
        currentDeleteId = null;
    }

    $(document).ready(function() {
        // 모달 외부 클릭 시 닫기 
        $('#delete-modal').on('click', function(e) {
            if (e.target === this) closeDeleteModal();
        });

        // 진짜 삭제 버튼 클릭! 
        $('#confirm-delete-btn').on('click', function() {
            if (currentDeleteId) {
                document.getElementById(`delete-form-${currentDeleteId}`).submit();
            }
        });

        const parentEl = document.getElementById('parent-sortable');
        const saveBtn = $('#save-order');
        
        if (parentEl) {
            Sortable.create(parentEl, {
                handle: '.drag-handle',
                animation: 250,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                onEnd: function() {
                    saveBtn.removeClass('hidden').addClass('flex');
                    updateDisplayOrders();
                }
            });
        }

        $('.child-sortable').each(function() {
            Sortable.create(this, {
                handle: '.drag-handle-child',
                animation: 200,
                ghostClass: 'sortable-ghost',
                onEnd: function() {
                    saveBtn.removeClass('hidden').addClass('flex');
                    updateDisplayOrders();
                }
            });
        });

        function updateDisplayOrders() {
            $('.parent-group').each(function(index) {
                $(this).find('.parent-order').first().text(index + 1);
                $(this).find('.child-order').each(function(childIndex) {
                    $(this).text(childIndex + 1);
                });
            });
        }
        
        saveBtn.on('click', function() {
            const order = [];
            $('.parent-group').each(function() {
                order.push($(this).data('id')); 
                $(this).find('.child-sortable [data-id]').each(function() {
                    order.push($(this).data('id')); 
                });
            });
            
            $('#order-input').val(JSON.stringify(order));
            $('#reorder-form').submit();
        });
    });
</script>
@endpush
