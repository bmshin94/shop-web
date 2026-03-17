@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-4xl">
    <div class="flex items-center gap-2 mb-6 text-sm text-gray-500">
        <a href="{{ route('admin.notification-templates.index') }}" class="hover:underline">알림 템플릿 관리</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="font-bold text-gray-800">템플릿 수정</span>
    </div>

    <h1 class="text-2xl font-bold text-gray-800 mb-8">[{{ $template->code }}] 알림 문구 수정</h1>

    <form action="{{ route('admin.notification-templates.update', $template) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 space-y-6">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">템플릿 이름</label>
                    <input type="text" name="name" value="{{ old('name', $template->name) }}" 
                        class="w-full h-11 px-4 rounded-xl border border-gray-200 focus:border-primary focus:ring-0 text-sm font-bold">
                    @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">카카오 템플릿 ID (선택)</label>
                    <input type="text" name="template_id" value="{{ old('template_id', $template->template_id) }}" 
                        class="w-full h-11 px-4 rounded-xl border border-gray-200 focus:border-primary focus:ring-0 text-sm font-mono">
                    <p class="mt-1 text-xs text-gray-400">솔라피(Solapi)에 등록된 템플릿 ID를 입력하세요.</p>
                </div>
            </div>

            <div>
                <div class="flex justify-between items-end mb-2">
                    <label class="block text-sm font-bold text-gray-700">메시지 본문</label>
                    <span class="text-[11px] text-primary font-bold bg-primary/5 px-2 py-1 rounded">사용 가능 변수: #{name}, #{email}</span>
                </div>
                <textarea name="content" rows="10" 
                    class="w-full p-4 rounded-xl border border-gray-200 focus:border-primary focus:ring-0 text-sm leading-relaxed font-mono">{{ old('content', $template->content) }}</textarea>
                @error('content')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                <p class="mt-2 text-xs text-gray-400 leading-relaxed">
                    * #{name}과 같은 변수는 발송 시 실제 회원의 정보로 자동 치환됩니다.<br>
                    * 카카오톡 알림톡의 경우, 승인된 템플릿 문구와 정확히 일치해야 발송이 가능할 수 있습니다.
                </p>
            </div>

            <div class="pt-4">
                <label class="flex items-center gap-2 cursor-pointer group w-fit">
                    <div class="relative">
                        <input type="checkbox" name="is_active" class="sr-only peer" {{ old('is_active', $template->is_active) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                    </div>
                    <span class="text-sm font-bold text-gray-700 group-hover:text-primary transition-colors">이 템플릿을 사용하여 알림 발송 활성화</span>
                </label>
            </div>
        </div>

        <!-- Buttons Area -->
        <div class="flex gap-3 justify-end">
            <a href="{{ route('admin.notification-templates.index') }}" class="px-8 py-3 bg-white border border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-50 transition-colors">취소하기</a>
            <button type="submit" class="px-8 py-3 bg-gray-800 text-white font-bold rounded-xl hover:bg-gray-700 transition-colors shadow-lg">템플릿 저장하기</button>
        </div>
    </form>
</div>
@endsection
