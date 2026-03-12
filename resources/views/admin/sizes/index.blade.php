@extends('layouts.admin')

@section('page_title', '사이즈 옵션 관리')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-extrabold text-text-main">사이즈 옵션 관리</h3>
            <p class="text-sm text-text-muted mt-1 font-medium">상품에 적용할 사이즈 그룹과 세부 사이즈를 관리합니다.</p>
        </div>
        <button onclick="$('#add-group-modal').removeClass('hidden').addClass('flex')" class="px-6 py-3 bg-primary text-white text-sm font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-red-600 transition-all flex items-center gap-2">
            <span class="material-symbols-outlined">add_circle</span>
            새 그룹 추가
        </button>
    </div>

    <!-- Size Groups Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        @forelse($sizeGroups as $group)
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="size-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-primary border border-gray-100">
                        <span class="material-symbols-outlined">straighten</span>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-text-main">{{ $group->name }}</h4>
                        <p class="text-[11px] text-text-muted font-bold uppercase tracking-wider">Size Group</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="openEditGroupModal({{ $group->id }}, '{{ $group->name }}', {{ json_encode($group->size_guide) }})" class="p-2 hover:bg-gray-100 rounded-lg transition-colors text-text-muted" title="그룹 수정 및 가이드 관리">
                        <span class="material-symbols-outlined">edit</span>
                    </button>
                    <button onclick="openAddSizeModal({{ $group->id }}, '{{ $group->name }}')" class="p-2 hover:bg-primary-light hover:text-primary rounded-lg transition-colors text-text-muted" title="사이즈 추가">
                        <span class="material-symbols-outlined">add</span>
                    </button>
                    <form action="{{ route('admin.sizes.groups.destroy', $group) }}" method="POST" class="js-confirm-submit" data-confirm-message="'{{ $group->name }}' 그룹을 삭제하시겠습니까?">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 hover:bg-red-50 hover:text-red-600 rounded-lg transition-colors text-text-muted" title="그룹 삭제">
                            <span class="material-symbols-outlined">delete</span>
                        </button>
                    </form>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @forelse($group->sizes as $size)
                    <div class="relative group p-4 rounded-2xl border border-gray-100 bg-white hover:border-primary/20 hover:bg-primary-light/10 transition-all text-center">
                        <p class="text-lg font-black text-text-main mb-1">{{ $size->name }}</p>
                        <p class="text-[10px] text-text-muted font-bold tracking-tight">순서: {{ $size->sort_order }}</p>
                        
                        <!-- Delete Size Button -->
                        <form action="{{ route('admin.sizes.destroy', $size) }}" method="POST" class="absolute -top-2 -right-2 opacity-0 group-hover:opacity-100 transition-all scale-50 group-hover:scale-100">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white rounded-full p-1 shadow-lg hover:bg-red-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[14px] font-black">close</span>
                            </button>
                        </form>
                    </div>
                    @empty
                    <div class="col-span-full py-10 text-center border-2 border-dashed border-gray-100 rounded-2xl">
                        <p class="text-sm text-text-muted font-medium italic">등록된 사이즈가 없습니다.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-24 text-center bg-white rounded-3xl border-2 border-dashed border-gray-200">
            <span class="material-symbols-outlined text-6xl text-gray-200 mb-4">ruler</span>
            <p class="text-lg font-bold text-text-muted">사이즈 그룹을 먼저 추가해주세요.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Edit Group Modal -->
<div id="edit-group-modal" class="fixed inset-0 z-[10000] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden">
        <form id="edit-group-form" action="" method="POST" class="p-8">
            @csrf
            @method('PATCH')
            <h4 class="text-xl font-bold text-text-main mb-6">사이즈 그룹 수정 및 가이드 관리</h4>
            <div class="space-y-6 mb-8">
                <div>
                    <label class="block text-xs font-bold text-text-muted uppercase tracking-wider mb-2 ml-1">그룹 명칭</label>
                    <input type="text" name="name" id="edit-group-name" required class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-muted uppercase tracking-wider mb-2 ml-1">사이즈 가이드 헤더 (콤마 구분)</label>
                    <input type="text" name="size_guide_headers" id="edit-guide-headers" placeholder="예: 사이즈, 가슴, 허리, 기장" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-muted uppercase tracking-wider mb-2 ml-1">사이즈 가이드 데이터 (행 구분: 줄바꿈, 열 구분: 콤마)</label>
                    <textarea name="size_guide_rows" id="edit-guide-rows" rows="5" placeholder="예: S, 80, 60, 95&#10;M, 85, 65, 97" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all resize-none"></textarea>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <button type="button" onclick="$('#edit-group-modal').addClass('hidden').removeClass('flex')" class="px-6 py-4 bg-gray-100 text-text-muted text-sm font-bold rounded-xl hover:bg-gray-200 transition-colors">취소</button>
                <button type="submit" class="px-6 py-4 bg-primary text-white text-sm font-bold rounded-xl hover:bg-red-600 transition-colors shadow-lg shadow-primary/20">수정하기</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Group Modal -->
<div id="add-group-modal" class="fixed inset-0 z-[10000] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full overflow-hidden">
        <form action="{{ route('admin.sizes.groups.store') }}" method="POST" class="p-8">
            @csrf
            <h4 class="text-xl font-bold text-text-main mb-6">새 사이즈 그룹 추가</h4>
            <div class="space-y-4 mb-8">
                <div>
                    <label class="block text-xs font-bold text-text-muted uppercase tracking-wider mb-2 ml-1">그룹 명칭</label>
                    <input type="text" name="name" required placeholder="예: 의류, 신발, 하의 등" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <button type="button" onclick="$('#add-group-modal').addClass('hidden').removeClass('flex')" class="px-6 py-4 bg-gray-100 text-text-muted text-sm font-bold rounded-xl hover:bg-gray-200 transition-colors">취소</button>
                <button type="submit" class="px-6 py-4 bg-primary text-white text-sm font-bold rounded-xl hover:bg-red-600 transition-colors shadow-lg shadow-primary/20">추가하기</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Size Modal -->
<div id="add-size-modal" class="fixed inset-0 z-[10000] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full overflow-hidden">
        <form action="{{ route('admin.sizes.store') }}" method="POST" class="p-8">
            @csrf
            <input type="hidden" name="size_group_id" id="modal-group-id">
            <h4 class="text-xl font-bold text-text-main mb-2">새 사이즈 추가</h4>
            <p id="modal-group-name" class="text-xs font-bold text-primary mb-6 uppercase tracking-wider">-</p>
            
            <div class="space-y-4 mb-8">
                <div>
                    <label class="block text-xs font-bold text-text-muted uppercase tracking-wider mb-2 ml-1">사이즈 명칭</label>
                    <input type="text" name="name" required placeholder="예: S, M, 230 등" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-muted uppercase tracking-wider mb-2 ml-1">정렬 순서</label>
                    <input type="number" name="sort_order" value="0" required class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <button type="button" onclick="$('#add-size-modal').addClass('hidden').removeClass('flex')" class="px-6 py-4 bg-gray-100 text-text-muted text-sm font-bold rounded-xl hover:bg-gray-200 transition-colors">취소</button>
                <button type="submit" class="px-6 py-4 bg-primary text-white text-sm font-bold rounded-xl hover:bg-red-600 transition-colors shadow-lg shadow-primary/20">추가하기</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openEditGroupModal(id, name, guide) {
        const $form = $('#edit-group-form');
        $form.attr('action', `/admin/sizes/groups/${id}`);
        $('#edit-group-name').val(name);
        
        if (guide) {
            $('#edit-guide-headers').val(guide.headers.join(', '));
            $('#edit-guide-rows').val(guide.rows.map(row => row.join(', ')).join('\n'));
        } else {
            $('#edit-guide-headers').val('');
            $('#edit-guide-rows').val('');
        }
        
        $('#edit-group-modal').removeClass('hidden').addClass('flex');
    }

    function openAddSizeModal(groupId, groupName) {
        $('#modal-group-id').val(groupId);
        $('#modal-group-name').text(groupName + ' 그룹에 사이즈 추가');
        $('#add-size-modal').removeClass('hidden').addClass('flex');
    }

    $(document).ready(function() {
        // 모달 배경 클릭 시 닫기
        $('#add-group-modal, #add-size-modal').on('click', function(e) {
            if (e.target === this) {
                $(this).addClass('hidden').removeClass('flex');
            }
        });
    });
</script>
@endpush
