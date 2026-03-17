@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">알림 템플릿 관리</h1>
        <div class="flex gap-2">
            <span class="px-3 py-1 bg-primary/10 text-primary rounded-full text-xs font-bold">Total: {{ $templates->count() }}건</span>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl font-bold text-sm">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">알림 코드</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">템플릿 이름</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">카카오 템플릿 ID</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">상태</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">마지막 수정</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">관리</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($templates as $template)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs font-mono font-bold">{{ $template->code }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm font-bold text-gray-800">{{ $template->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ $template->template_id ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @if($template->is_active)
                        <span class="px-2.5 py-1 bg-green-100 text-green-700 rounded-md text-xs font-bold">활성</span>
                        @else
                        <span class="px-2.5 py-1 bg-red-100 text-red-700 rounded-md text-xs font-bold">비활성</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-xs text-gray-400">
                        {{ $template->updated_at->format('Y.m.d H:i') }}
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.notification-templates.edit', $template) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-800 text-white rounded-lg text-xs font-bold hover:bg-gray-700 transition-colors">
                            <span class="material-symbols-outlined text-[14px]">edit</span> 수정하기
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
