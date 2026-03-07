@php
    $exhibition = $exhibition ?? null;
    $statusOptions = $statusOptions ?? [];
    $formAction = $formAction ?? '';
    $formMethod = $formMethod ?? 'POST';
    $submitLabel = $submitLabel ?? '저장';

    $startAtValue = old('start_at', optional($exhibition?->start_at)->format('Y-m-d\TH:i'));
    $endAtValue = old('end_at', optional($exhibition?->end_at)->format('Y-m-d\TH:i'));
@endphp

<form action="{{ $formAction }}" method="POST" class="space-y-5">
    @csrf
    @if($formMethod !== 'POST')
        @method($formMethod)
    @endif

    <div class="space-y-2">
        <label class="text-sm font-bold text-text-main">기획전명</label>
        <input
            type="text"
            name="title"
            value="{{ old('title', $exhibition?->title) }}"
            placeholder="예) 3월 봄맞이 혜택전"
            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
        @error('title')
            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="space-y-2">
        <label class="text-sm font-bold text-text-main">슬러그</label>
        <input
            type="text"
            name="slug"
            value="{{ old('slug', $exhibition?->slug) }}"
            placeholder="exhibition-spring-special"
            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
        <p class="text-[11px] font-bold text-text-muted">비워두면 기획전명 기반으로 자동 생성됩니다.</p>
        @error('slug')
            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="space-y-2">
            <label class="text-sm font-bold text-text-main">상태</label>
            <select name="status" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 appearance-none outline-none">
                @foreach($statusOptions as $status)
                    <option value="{{ $status }}" {{ old('status', $exhibition?->status ?? '진행예정') === $status ? 'selected' : '' }}>
                        {{ $status }}
                    </option>
                @endforeach
            </select>
            @error('status')
                <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label class="text-sm font-bold text-text-main">정렬 순서</label>
            <input
                type="number"
                min="0"
                max="9999"
                name="sort_order"
                value="{{ old('sort_order', $exhibition?->sort_order ?? 0) }}"
                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
            @error('sort_order')
                <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="space-y-2">
        <label class="text-sm font-bold text-text-main">배너 이미지 URL</label>
        <input
            type="url"
            name="banner_image_url"
            value="{{ old('banner_image_url', $exhibition?->banner_image_url) }}"
            placeholder="https://example.com/banner.jpg"
            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
        @error('banner_image_url')
            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="space-y-2">
        <label class="text-sm font-bold text-text-main">요약 문구</label>
        <textarea name="summary" rows="2" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">{{ old('summary', $exhibition?->summary) }}</textarea>
        @error('summary')
            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="space-y-2">
        <label class="text-sm font-bold text-text-main">상세 설명</label>
        <textarea name="description" rows="8" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">{{ old('description', $exhibition?->description) }}</textarea>
        @error('description')
            <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="space-y-2">
            <label class="text-sm font-bold text-text-main">시작 일시</label>
            <input
                type="datetime-local"
                name="start_at"
                value="{{ $startAtValue }}"
                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
            @error('start_at')
                <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label class="text-sm font-bold text-text-main">종료 일시</label>
            <input
                type="datetime-local"
                name="end_at"
                value="{{ $endAtValue }}"
                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none">
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
</form>

