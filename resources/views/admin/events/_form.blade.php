@php
    $event = $event ?? null;
    $statusOptions = $statusOptions ?? [];
    $formAction = $formAction ?? '';
    $formMethod = $formMethod ?? 'POST';
    $submitLabel = $submitLabel ?? '저장';

    $startAtValue = old('start_at', optional($event?->start_at)->format('Y-m-d'));
    $endAtValue = old('end_at', optional($event?->end_at)->format('Y-m-d'));
@endphp

@if($formAction)
<form action="{{ $formAction }}" method="POST" class="space-y-5" @if($hasFile ?? false) enctype="multipart/form-data" @endif>
    @csrf
    @if($formMethod !== 'POST')
        @method($formMethod)
    @endif
@else
<div class="space-y-5">
@endif

    <div class="space-y-2">
        <label class="text-sm font-bold text-text-main">이벤트명 <span class="text-primary">*</span></label>
        <input
            type="text"
            name="title"
            value="{{ old('title', $event?->title) }}"
            placeholder="예) 3월 봄맞이 혜택전"
            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
        @error('title')
            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="space-y-2">
            <label class="text-sm font-bold text-text-main">슬러그 <span class="text-primary">*</span></label>
            <input
                type="text"
                name="slug"
                value="{{ old('slug', $event?->slug) }}"
                placeholder="event-spring-special"
                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
            <p class="text-[11px] font-bold text-text-muted">비워두면 이벤트명 기반으로 자동 생성됩니다.</p>
            @error('slug')
                <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label class="text-sm font-bold text-text-main">이벤트 유형 <span class="text-primary">*</span></label>
            <select name="type" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 appearance-none outline-none">
                @foreach(\App\Models\Event::TYPES as $type)
                    <option value="{{ $type }}" {{ old('type', $event?->type ?? \App\Models\Event::TYPE_GENERAL) === $type ? 'selected' : '' }}>
                        {{ $type }} ({{ $type === '응모형' ? '회원 응모 필요' : '단순 정보 안내' }})
                    </option>
                @endforeach
            </select>
            @error('type')
                <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="space-y-2">
            <label class="text-sm font-bold text-text-main">정렬 순서</label>
            <input
                type="number"
                min="0"
                max="9999"
                name="sort_order"
                value="{{ old('sort_order', $event?->sort_order ?? 0) }}"
                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
            @error('sort_order')
                <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-3 pt-8">
            <label class="relative inline-flex items-center cursor-pointer group">
                <input type="checkbox" name="is_hero" value="1" class="sr-only peer" {{ old('is_hero', $event?->is_hero) ? 'checked' : '' }}>
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                <span class="ms-3 text-sm font-bold text-text-main group-hover:text-primary transition-colors">히어로 영역(상단 배너) 노출</span>
            </label>
        </div>
    </div>

    <div class="space-y-2">
        <label class="text-sm font-bold text-text-main">배너 이미지</label>
        
        <div class="relative group">
            <input
                type="file"
                name="banner_image"
                id="banner_image_input"
                accept="image/*"
                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                onchange="handleImagePreview(this)">
            
            <div id="drop_zone" class="w-full min-h-[160px] p-6 bg-gray-50 border-2 border-dashed border-gray-300 rounded-2xl flex flex-col items-center justify-center gap-3 transition-colors group-hover:bg-primary/5 group-hover:border-primary/30 text-center">
                <!-- Preview Image -->
                <img id="image_preview" 
                     src="{{ $event?->banner_image_url ? Storage::url($event->banner_image_url) : '' }}" 
                     class="{{ $event?->banner_image_url ? 'block' : 'hidden' }} max-h-[200px] object-contain rounded-lg shadow-sm mb-2" 
                     alt="미리보기">
                
                <!-- Upload Icon & Text -->
                <div id="upload_placeholder" class="{{ $event?->banner_image_url ? 'hidden' : 'block' }}">
                    <div class="size-12 rounded-full bg-white shadow-sm flex items-center justify-center text-text-muted mx-auto mb-3 group-hover:text-primary group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-[24px]">cloud_upload</span>
                    </div>
                    <p class="text-sm font-bold text-text-main">클릭하거나 이미지를 여기로 드래그하세요</p>
                    <p class="mt-1 text-[11px] font-bold text-text-muted">권장 사이즈: 1200 x 675px (16:9 비율)</p>
                    <p class="text-[11px] font-bold text-text-muted">PNG, JPG, WEBP (최대 2MB)</p>
                </div>
                
                @if($event?->banner_image_url)
                <p id="upload_hint_text" class="text-[11px] font-bold text-text-muted z-20 relative bg-white/80 px-2 py-1 rounded">새 이미지를 업로드하면 기존 이미지는 교체됩니다.</p>
                @endif
            </div>
        </div>

        @error('banner_image')
            <p class="text-[12px] font-bold text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="space-y-2">
        <label class="text-sm font-bold text-text-main">요약 문구</label>
        <textarea name="summary" rows="2" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">{{ old('summary', $event?->summary) }}</textarea>
        @error('summary')
            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="space-y-2">
        <label class="text-sm font-bold text-text-main">상세 설명</label>
        <textarea name="description" rows="8" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">{{ old('description', $event?->description) }}</textarea>
        @error('description')
            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
        @enderror
    </div>

    @if(isset($event))
    <div class="space-y-4 pt-4 border-t border-gray-100 mt-4">
        <div class="flex items-center justify-between">
            <label class="text-sm font-bold text-text-main">당첨자 설정</label>
            @if($event->type === \App\Models\Event::TYPE_PARTICIPATION)
            <a href="{{ route('admin.events.participants', array_merge(['event' => $event->id], request()->query())) }}" class="text-[11px] font-bold text-primary hover:underline flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">group</span>
                응모자 명단에서 선정하기
            </a>
            @endif
        </div>
        
        <!-- Member Search Area -->
        <div class="relative group">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-muted text-[18px] group-focus-within:text-primary transition-colors pointer-events-none">person_search</span>
            <input
                type="text"
                id="member_search_input"
                placeholder="이름, 이메일, 휴대폰 번호로 회원 검색"
                class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all">
            
            <!-- Search Results Dropdown -->
            <div id="search_results_dropdown" class="absolute left-0 right-0 top-full mt-2 bg-white border border-gray-100 rounded-2xl shadow-xl z-50 hidden max-h-[300px] overflow-y-auto">
                <div id="search_results_list" class="divide-y divide-gray-50">
                    <!-- Dynamic results here -->
                </div>
            </div>
        </div>

        <!-- Selected Winners List -->
        <div class="bg-gray-50/50 border border-gray-100 rounded-2xl p-4">
            <p class="text-[12px] font-bold text-text-muted mb-3 uppercase tracking-wider">선택된 당첨자 명단 (<span id="winner_count">{{ $event->winners->count() }}</span>명)</p>
            <div id="selected_winners_list" class="flex flex-wrap gap-2">
                @foreach($event->winners as $winner)
                <div class="winner-tag inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-gray-200 rounded-xl text-xs font-bold text-text-main shadow-sm animate-enter" data-id="{{ $winner->id }}">
                    <input type="hidden" name="winner_ids[]" value="{{ $winner->id }}">
                    <span>{{ $winner->name }} ({{ $winner->email }})</span>
                    <button type="button" onclick="removeWinner(this)" class="text-text-muted hover:text-red-500 transition-colors">
                        <span class="material-symbols-outlined text-[16px]">close</span>
                    </button>
                </div>
                @endforeach
                
                @if($event->winners->isEmpty())
                <p id="no_winners_text" class="text-xs text-text-muted py-2">검색을 통해 당첨자를 추가해 주세요.</p>
                @endif
            </div>
        </div>

        <div class="space-y-2">
            <label class="text-sm font-bold text-text-muted">당첨자 발표 안내 문구 (선택)</label>
            <textarea name="winner_announcement" rows="4" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none" placeholder="당첨자 발표와 함께 노출할 안내 문구를 입력하세요.">{{ old('winner_announcement', $event->winner_announcement) }}</textarea>
            @error('winner_announcement')
                <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="space-y-2">
            <label class="text-sm font-bold text-text-main">시작 일시</label>
            <div class="relative group">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-muted text-[18px] group-focus-within:text-primary transition-colors pointer-events-none">calendar_today</span>
                <input
                    type="text"
                    name="start_at"
                    value="{{ $startAtValue }}"
                    class="datepicker w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
            </div>
            @error('start_at')
                <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label class="text-sm font-bold text-text-main">종료 일시</label>
            <div class="relative group">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-muted text-[18px] group-focus-within:text-primary transition-colors pointer-events-none">calendar_month</span>
                <input
                    type="text"
                    name="end_at"
                    value="{{ $endAtValue }}"
                    class="datepicker w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
            </div>
            @error('end_at')
                <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    @if($errors->any())
        <div class="rounded-2xl bg-red-50 border border-red-100 px-4 py-3 text-[12px] font-bold text-red-600">
            {{ $errors->first() }}
        </div>
    @endif

    <button type="submit" class="w-full px-5 py-4 bg-primary text-white rounded-2xl text-sm font-extrabold hover:bg-red-600 transition-colors shadow-lg shadow-primary/20">
        {{ $submitLabel }}
    </button>
@if($formAction)
</form>
@else
</div>
@endif
