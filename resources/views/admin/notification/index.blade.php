@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">알림 발송 이력 관리</h1>
        <div class="flex gap-2">
            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold">Total: {{ number_format($logs->total()) }}건</span>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
        <form action="{{ route('admin.notifications.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-bold text-gray-500 mb-1">검색 (번호/내용)</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="수신번호 또는 메시지 검색" 
                    class="w-full h-10 px-4 rounded-lg border border-gray-200 focus:border-primary focus:ring-0 text-sm">
            </div>
            <div class="w-32">
                <label class="block text-xs font-bold text-gray-500 mb-1">상태</label>
                <select name="status" class="w-full h-10 px-3 rounded-lg border border-gray-200 focus:border-primary text-sm">
                    <option value="">전체상태</option>
                    <option value="성공" {{ $status == '성공' ? 'selected' : '' }}>성공</option>
                    <option value="실패" {{ $status == '실패' ? 'selected' : '' }}>실패</option>
                    <option value="대기" {{ $status == '대기' ? 'selected' : '' }}>대기</option>
                </select>
            </div>
            <div class="w-32">
                <label class="block text-xs font-bold text-gray-500 mb-1">채널</label>
                <select name="channel" class="w-full h-10 px-3 rounded-lg border border-gray-200 focus:border-primary text-sm">
                    <option value="">전체채널</option>
                    <option value="alimtalk" {{ $channel == 'alimtalk' ? 'selected' : '' }}>카카오톡</option>
                    <option value="sms" {{ $channel == 'sms' ? 'selected' : '' }}>문자(SMS)</option>
                </select>
            </div>
            <button type="submit" class="h-10 px-6 bg-gray-800 text-white font-bold rounded-lg hover:bg-gray-700 transition-colors">
                검색하기
            </button>
            <a href="{{ route('admin.notifications.index') }}" class="h-10 px-4 flex items-center bg-gray-100 text-gray-600 font-bold rounded-lg hover:bg-gray-200 transition-colors">
                초기화
            </a>
        </form>
    </div>

    <!-- List Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">수신정보</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">알림종류/채널</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">메시지 내용</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">상태</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">발송일시</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">관리</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $log->id }}</td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-bold text-gray-800">{{ $log->recipient }}</div>
                        <div class="text-xs text-gray-400">{{ $log->member ? $log->member->name : '게스트' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-700">{{ $log->notification_type }}</div>
                        <div class="text-xs {{ $log->channel == 'alimtalk' ? 'text-green-600' : 'text-blue-600' }} font-bold">
                            {{ $log->channel == 'alimtalk' ? '카카오톡' : '문자' }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-600 line-clamp-2 max-w-xs" title="{{ $log->message }}">
                            {{ $log->message }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($log->status == '성공')
                        <span class="px-2.5 py-1 bg-green-100 text-green-700 rounded-md text-xs font-bold">발송성공</span>
                        @elseif($log->status == '실패')
                        <span class="px-2.5 py-1 bg-red-100 text-red-700 rounded-md text-xs font-bold" title="{{ $log->error_message }}">발송실패</span>
                        @else
                        <span class="px-2.5 py-1 bg-gray-100 text-gray-500 rounded-md text-xs font-bold">대기중</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $log->sent_at ? $log->sent_at->format('Y-m-d H:i') : '-' }}
                    </td>
                    <td class="px-6 py-4">
                        <button type="button" onclick="showLogDetail({{ $log->id }})" class="text-xs font-bold text-primary hover:underline">상세보기</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-20 text-center text-gray-400">
                        발송 이력이 없습니다.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $logs->links() }}
    </div>
</div>

<!-- Detail Modal (간이 구현) -->
<div id="logModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 max-w-2xl w-full mx-4 shadow-2xl">
        <h2 class="text-xl font-bold mb-4">발송 상세 정보</h2>
        <div id="modalContent" class="bg-gray-50 p-4 rounded-lg overflow-auto max-h-[500px] text-xs font-mono">
            <!-- JSON content here -->
        </div>
        <div class="mt-6 text-right">
            <button onclick="document.getElementById('logModal').classList.add('hidden')" class="px-6 py-2 bg-gray-800 text-white font-bold rounded-lg">닫기</button>
        </div>
    </div>
</div>

<script>
    function showLogDetail(id) {
        fetch(`/admin/notifications/${id}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('modalContent').innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
                document.getElementById('logModal').classList.remove('hidden');
                document.getElementById('logModal').classList.add('flex');
            });
    }
</script>
@endsection
