@extends('layouts.admin')

@section('page_title', '신규 카테고리 등록')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Header with Back Button -->
    <div class="flex items-center justify-between mb-8">
        <h3 class="text-2xl font-extrabold text-text-main">새로운 카테고리 만들기</h3>
        <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-2 text-sm font-bold text-text-muted hover:text-primary transition-all font-display">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            목록으로 돌아가기
        </a>
    </div>

    <!-- Registration Form -->
    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden font-display">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="p-8 md:p-10 space-y-8">
            @csrf
            
            <!-- Category Name -->
            <div class="space-y-2">
                <label for="name" class="text-sm font-bold text-text-main flex items-center gap-1">
                    카테고리명 <span class="text-primary">*</span>
                </label>
                <input type="text" id="name" name="name" required value="{{ old('name') }}" placeholder="예: 스포츠웨어, 상의 등"
                    class="w-full px-5 py-4 bg-gray-50 border {{ $errors->has('name') ? 'border-red-500 ring-2 ring-red-500/10' : 'border-gray-200' }} rounded-2xl text-base focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
                @error('name')
                    <p class="text-xs text-red-500 font-bold mt-1 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">error</span> {{ $message }}
                    </p>
                @else
                    <p class="text-xs text-text-muted">사용자에게 보여질 카테고리 이름을 입력해주세요.</p>
                @enderror
            </div>

            <!-- Parent Category -->
            <div class="space-y-2">
                <label for="parent_id" class="text-sm font-bold text-text-main">상위 카테고리</label>
                <div class="relative group">
                    <select id="parent_id" name="parent_id"
                        class="w-full px-5 py-4 pr-12 bg-gray-50 border {{ $errors->has('parent_id') ? 'border-red-500' : 'border-gray-200' }} rounded-2xl text-base focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none appearance-none bg-none transition-all">
                        <option value="">없음 (대분류로 등록)</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                        <span class="material-symbols-outlined text-text-muted group-focus-within:text-primary transition-colors">expand_more</span>
                    </div>
                </div>
                @error('parent_id')
                    <p class="text-xs text-red-500 font-bold mt-1 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">error</span> {{ $message }}
                    </p>
                @else
                    <p class="text-xs text-text-muted">소분류로 등록하고 싶을 때만 상위 카테고리를 선택해주세요.</p>
                @enderror
            </div>

            <!-- Icon (Only for Level 1) -->
            <div id="icon-field" class="space-y-2 transition-all duration-300">
                <label for="icon" class="text-sm font-bold text-text-main flex items-center gap-1">
                    카테고리 아이콘 <span class="text-xs text-text-muted font-normal">(Material Symbols 이름)</span>
                </label>
                <div class="relative group">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 size-10 rounded-xl bg-primary/5 flex items-center justify-center pointer-events-none transition-all duration-300" id="icon-preview-container">
                        <span id="icon-preview" class="text-primary material-symbols-outlined text-[24px]">category</span>
                    </div>
                    <input type="text" id="icon" name="icon" value="{{ old('icon', 'category') }}" placeholder="예: sports_baseball, directions_run 등"
                        class="w-full pl-16 pr-5 py-4 bg-gray-50 border {{ $errors->has('icon') ? 'border-red-500' : 'border-gray-200' }} rounded-2xl text-base focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
                </div>
                <div id="icon-warning" class="hidden text-[11px] text-red-500 font-bold mt-1 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">warning</span> 존재하지 않거나 유효하지 않은 아이콘 이름입니다.
                </div>
                <p class="text-[11px] text-text-muted font-medium mt-1">
                    <a href="https://fonts.google.com/icons?icon.set=Material+Symbols" target="_blank" class="text-primary hover:underline">Material Symbols</a>의 이름을 입력하세요. 대분류일 때만 노출됩니다.
                </p>
                @error('icon')
                    <p class="text-xs text-red-500 font-bold mt-1 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">error</span> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Slug -->
            <div class="space-y-2">
                <label for="slug" class="text-sm font-bold text-text-main">슬러그 (Slug)</label>
                <div class="flex items-center gap-2">
                    <span class="text-text-muted font-mono bg-gray-100 px-3 py-4 rounded-2xl border border-gray-200 text-sm">/products/</span>
                    <input type="text" id="slug" name="slug" value="{{ old('slug') }}" placeholder="예: sportswear"
                        class="flex-1 px-5 py-4 bg-gray-50 border {{ $errors->has('slug') ? 'border-red-500' : 'border-gray-200' }} rounded-2xl text-base focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all font-mono">
                </div>
                @error('slug')
                    <p class="text-xs text-red-500 font-bold mt-1 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">error</span> {{ $message }}
                    </p>
                @else
                    <p class="text-xs text-text-muted">비워두면 카테고리명을 기반으로 자동 생성됩니다.</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Sort Order -->
                <div class="space-y-2">
                    <label for="sort_order" class="text-sm font-bold text-text-main">정렬 순서</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $nextOrderMap['root']) }}"
                        class="w-full px-5 py-4 bg-gray-50 border {{ $errors->has('sort_order') ? 'border-red-500' : 'border-gray-200' }} rounded-2xl text-base focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all text-center">
                    @error('sort_order')
                        <p class="text-xs text-red-500 font-bold mt-1 flex items-center gap-1 justify-center">
                            <span class="material-symbols-outlined text-[14px]">error</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Display Status -->
                <div class="space-y-2">
                    <label class="text-sm font-bold text-text-main mb-3 block">노출 상태</label>
                    <div class="flex items-center gap-6 p-4 bg-gray-50 rounded-2xl border {{ $errors->has('is_active') ? 'border-red-500' : 'border-gray-200' }}">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="radio" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }} class="w-5 h-5 text-primary border-gray-300 focus:ring-primary/20">
                            <span class="text-sm font-bold text-text-main group-hover:text-primary transition-colors">노출함</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="radio" name="is_active" value="0" {{ old('is_active') == '0' ? 'checked' : '' }} class="w-5 h-5 text-gray-400 border-gray-300 focus:ring-gray-400">
                            <span class="text-sm font-bold text-text-muted group-hover:text-gray-600 transition-colors">숨김</span>
                        </label>
                    </div>
                    @error('is_active')
                        <p class="text-xs text-red-500 font-bold mt-1 flex items-center gap-1 justify-center">
                            <span class="material-symbols-outlined text-[14px]">error</span> {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Form Submit & Cancel -->
            <div class="pt-6 flex flex-col sm:flex-row items-center gap-4">
                <button type="submit" class="w-full sm:flex-1 py-5 bg-primary text-white text-lg font-extrabold rounded-2xl shadow-xl shadow-primary/30 hover:bg-red-600 hover:scale-[1.01] transition-all flex items-center justify-center gap-3">
                    <span class="material-symbols-outlined">check_circle</span>
                    카테고리 등록하기
                </button>
                <a href="{{ route('admin.categories.index') }}" class="w-full sm:w-auto px-10 py-5 bg-gray-100 text-text-muted text-lg font-bold rounded-2xl hover:bg-gray-200 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">close</span>
                    취소
                </a>
            </div>
        </form>
    </div>

    <!-- Detailed Registration Guide Footer -->
    <div class="mt-12 bg-white rounded-3xl p-8 border border-gray-100 shadow-sm space-y-6 font-display">
        <div class="flex items-center gap-3 border-b border-gray-50 pb-4">
            <div class="size-10 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                <span class="material-symbols-outlined">lightbulb</span>
            </div>
            <div>
                <h4 class="text-lg font-bold text-text-main">카테고리 등록 상세 가이드</h4>
                <p class="text-xs text-text-muted">완벽한 카테고리 구성을 위해 아래 내용을 확인해보세요.</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-4">
                <div class="flex gap-3">
                    <span class="text-primary font-bold">01.</span>
                    <div>
                        <h5 class="text-sm font-bold text-text-main mb-1">이름과 슬러그(Slug)</h5>
                        <p class="text-xs text-text-muted leading-relaxed">카테고리명은 고객에게 노출되는 이름이며, 슬러그는 브라우저 주소창에 표시되는 URL 경로입니다. 슬러그를 비워두면 이름 기반으로 자동 생성되니 안심하세요.</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <span class="text-primary font-bold">02.</span>
                    <div>
                        <h5 class="text-sm font-bold text-text-main mb-1">계층 구조 설정 (2차)</h5>
                        <p class="text-xs text-text-muted leading-relaxed">'상위 카테고리'를 선택하면 해당 카테고리의 소분류(2차)로 등록됩니다. 선택하지 않을 경우 독립적인 대분류(1차)로 생성되어 메뉴 최상단에 위치하게 됩니다.</p>
                    </div>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex gap-3">
                    <span class="text-primary font-bold">03.</span>
                    <div>
                        <h5 class="text-sm font-bold text-text-main mb-1">스마트 정렬 순서</h5>
                        <p class="text-xs text-text-muted leading-relaxed">상위 카테고리를 변경하면 해당 그룹 내에서 다음에 올 수 있는 최적의 숫자가 자동 추천됩니다. 숫자가 낮을수록 사용자 화면의 좌측 혹은 상단에 먼저 노출됩니다.</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <span class="text-primary font-bold">04.</span>
                    <div>
                        <h5 class="text-sm font-bold text-text-main mb-1">노출 상태 활용</h5>
                        <p class="text-xs text-text-muted leading-relaxed">'숨김' 상태로 등록하면 관리자 화면에서만 확인이 가능합니다. 신규 기획전 카테고리를 미리 준비하거나, 시즌이 지난 카테고리를 임시로 가릴 때 유용하게 활용해보세요.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // 컨트롤러에서 넘어온 정렬 순서 지도(Map)
        const nextOrderMap = @json($nextOrderMap);
        const $parentIdSelect = $('#parent_id');
        const $sortOrderInput = $('#sort_order');

        // 상위 카테고리가 바뀔 때마다 순서번호 자동 갱신!
        $parentIdSelect.on('change', function() {
            const selectedVal = $(this).val();
            const key = selectedVal === "" ? 'root' : selectedVal;
            
            // 아이콘 필드 토글 (대분류일 때만 표시)
            if (selectedVal === "") {
                $('#icon-field').slideDown(300).removeClass('opacity-0 pointer-events-none');
            } else {
                $('#icon-field').slideUp(300).addClass('opacity-0 pointer-events-none');
            }

            if (nextOrderMap[key]) {
                $sortOrderInput.val(nextOrderMap[key]);
                
                // 시각적인 효과를 위해 살짝 강조!
                $sortOrderInput.addClass('ring-4 ring-primary/10 border-primary');
                setTimeout(() => {
                    $sortOrderInput.removeClass('ring-4 ring-primary/10 border-primary');
                }, 500);
            }
        });

        // 아이콘 실시간 미리보기
        $('#icon').on('input', function() {
            const iconName = $(this).val().trim();
            const $preview = $('#icon-preview');
            const $container = $('#icon-preview-container');
            const $warning = $('#icon-warning');
            
            if (!iconName) {
                $preview.text('category');
                $container.css('opacity', '0.2');
                $warning.addClass('hidden');
                return;
            }
            
            $preview.text(iconName);
            
            // 스마트 아이콘 판별 로직 ✨
            setTimeout(() => {
                const width = $preview[0].offsetWidth;
                const height = $preview[0].offsetHeight;
                
                // 아이콘은 1:1 비율이어야 함! (글자가 보이면 가로가 훨씬 길어짐)
                if (width > height * 1.2 || width === 0) {
                    $container.css('opacity', '0').addClass('is-invalid');
                    $warning.removeClass('hidden'); // 경고 짜잔! 🚨
                } else {
                    $container.css('opacity', '1').removeClass('is-invalid');
                    $warning.addClass('hidden'); // 아이콘이면 경고 숨김 ✨
                }
            }, 50);
        });

        // 폼 제출 시 최종 체크! 🔒
        $('form').on('submit', function(e) {
            const isParent = $('#parent_id').val() === "";
            const isIconInvalid = $('#icon-preview-container').hasClass('is-invalid');
            const iconValue = $('#icon').val().trim();

            if (isParent) {
                if (!iconValue) {
                    e.preventDefault();
                    showAlert('1차 카테고리는 아이콘 입력이 필수입니다! 💖', '알림', 'warning');
                    $('#icon').focus();
                    return false;
                }
                if (isIconInvalid) {
                    e.preventDefault();
                    showAlert('유효하지 않은 아이콘 이름입니다. 다시 확인해주세요! ✨', '오류', 'error');
                    $('#icon').focus();
                    return false;
                }
            }
        });
    });
</script>
@endpush
