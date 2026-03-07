@php
    $selected = collect($selectedMenuPermissions ?? [])
        ->filter(fn ($value) => is_string($value) && $value !== '')
        ->values()
        ->all();
@endphp

<div class="space-y-3">
    <div class="flex items-center justify-between gap-2">
        <label class="text-sm font-bold text-text-main">메뉴 접근 권한</label>
        <p class="text-[12px] font-bold text-text-muted">선택한 메뉴만 접근 가능합니다.</p>
    </div>

    <input type="hidden" name="menu_permissions_submitted" value="1">

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        @foreach($menuDefinitions as $menuKey => $menu)
            @php
                $label = (string) ($menu['label'] ?? $menuKey);
                $description = (string) ($menu['description'] ?? '');
                $isChecked = in_array($menuKey, old('menu_permissions', $selected), true);
            @endphp

            <label class="flex items-start gap-3 rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 cursor-pointer hover:border-primary/40 hover:bg-white transition-colors">
                <input type="checkbox" name="menu_permissions[]" value="{{ $menuKey }}" {{ $isChecked ? 'checked' : '' }} class="mt-1 rounded border-gray-300 text-primary focus:ring-primary/20">
                <span class="block">
                    <span class="block text-sm font-bold text-text-main">{{ $label }}</span>
                    @if($description !== '')
                        <span class="mt-0.5 block text-[12px] font-bold text-text-muted">{{ $description }}</span>
                    @endif
                </span>
            </label>
        @endforeach
    </div>

    @error('menu_permissions')
        <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
    @enderror
    @error('menu_permissions.*')
        <p class="text-[12px] font-bold text-red-600">{{ $message }}</p>
    @enderror
</div>

