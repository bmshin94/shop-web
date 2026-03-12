@extends('layouts.admin')

@section('page_title', '적립금 관리')

@section('content')
<div class="space-y-6">
    {{-- Header & Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 rounded-2xl bg-primary/5 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">payments</span>
                </div>
                <span class="text-[11px] font-bold text-text-muted uppercase">총 누적 적립액</span>
            </div>
            <p class="text-3xl font-black text-text-main">{{ number_format(\App\Models\PointHistory::where('amount', '>', 0)->sum('amount')) }}원</p>
        </div>
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">shopping_bag</span>
                </div>
                <span class="text-[11px] font-bold text-text-muted uppercase">총 사용액</span>
            </div>
            <p class="text-3xl font-black text-text-main">{{ number_format(abs(\App\Models\PointHistory::where('amount', '<', 0)->sum('amount'))) }}원</p>
        </div>
        <div class="flex items-center justify-end">
            <button onclick="openManualModal()" 
                    class="px-8 py-4 bg-primary text-white rounded-2xl text-sm font-black hover:bg-red-600 transition-all shadow-xl shadow-primary/20 active:scale-95 flex items-center gap-2">
                <span class="material-symbols-outlined">add_circle</span> 적립금 수동 지급/차감
            </button>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
        <form action="{{ route('admin.points.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1 relative group">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-muted text-[18px] group-focus-within:text-primary transition-colors">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="회원명, 이메일, 적립사유 검색" 
                       class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all font-medium">
            </div>
            <div class="w-full lg:w-[200px] relative">
                <select name="type" class="w-full px-4 py-3 pr-10 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 appearance-none outline-none transition-all cursor-pointer !bg-none">
                    <option value="">모든 변동유형</option>
                    <option value="plus" {{ request('type') === 'plus' ? 'selected' : '' }}>적립 (+)</option>
                    <option value="minus" {{ request('type') === 'minus' ? 'selected' : '' }}>차감 (-)</option>
                </select>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-text-muted text-[18px] pointer-events-none">expand_more</span>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.points.index') }}" class="px-6 py-3 bg-gray-100 text-text-muted rounded-xl text-sm font-bold hover:bg-gray-200 transition-colors text-center">초기화</a>
                <button type="submit" class="px-8 py-3 bg-text-main text-white rounded-xl text-sm font-bold hover:bg-black transition-colors">검색</button>
            </div>
        </form>
    </div>

    {{-- Table Section --}}
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-4 text-[11px] font-bold text-text-muted uppercase tracking-wider">변동일시</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-text-muted uppercase tracking-wider">회원정보</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-text-muted uppercase tracking-wider">적립/사용 사유</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-text-muted uppercase tracking-wider text-right">변동금액</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-text-muted uppercase tracking-wider text-right">잔액</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($histories as $history)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="text-xs font-bold text-text-main">{{ $history->created_at->format('Y.m.d') }}</p>
                            <p class="text-[10px] text-text-muted">{{ $history->created_at->format('H:i:s') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="size-8 rounded-full bg-gray-100 flex items-center justify-center text-[10px] font-black text-text-muted uppercase">
                                    {{ mb_substr($history->member->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-text-main">{{ $history->member->name }}</p>
                                    <p class="text-[10px] text-text-muted">{{ $history->member->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs font-medium text-text-main">
                            {{ $history->reason }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-sm font-black {{ $history->amount > 0 ? 'text-primary' : 'text-text-main' }}">
                                {{ $history->amount > 0 ? '+' : '' }}{{ number_format($history->amount) }}원
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-xs font-bold text-text-muted">
                            {{ number_format($history->balance_after) }}원
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center text-sm text-text-muted font-medium">
                            변동 내역이 없습니다.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($histories->hasPages())
        <div class="px-6 py-4 border-t border-gray-50">
            {{ $histories->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Manual Adjustment Modal (Searchable Version) --}}
<div id="manual-point-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-all">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden">
        <div class="px-8 pt-8 pb-4 flex items-center justify-between border-b border-gray-50">
            <h3 class="text-xl font-black text-text-main">적립금 수동 지급/차감</h3>
            <button onclick="closeManualModal()" class="size-10 flex items-center justify-center rounded-xl hover:bg-gray-100 transition-colors text-text-muted">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <form action="{{ route('admin.points.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            {{-- Member Search Input --}}
            <div class="space-y-2 relative">
                <label class="text-xs font-bold text-text-muted ml-1">대상 회원 검색 <span class="text-red-500">*</span></label>
                
                {{-- Selected Member Card --}}
                <div id="selected-member-display" class="hidden items-center justify-between p-4 bg-primary/5 border border-primary/20 rounded-2xl mb-2">
                    <div class="flex items-center gap-3">
                        <div class="size-10 rounded-full bg-primary text-white flex items-center justify-center text-xs font-black" id="selected-member-initial"></div>
                        <div>
                            <p class="text-sm font-black text-text-main" id="selected-member-name"></p>
                            <p class="text-[11px] font-bold text-text-muted" id="selected-member-email"></p>
                        </div>
                    </div>
                    <button type="button" onclick="resetMemberSelection()" class="text-xs font-bold text-primary hover:underline">변경</button>
                </div>

                <div id="member-search-container" class="relative group">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary transition-colors">person_search</span>
                    <input type="text" id="member-search-input" placeholder="회원 이름 또는 이메일 입력 (2자 이상)" autocomplete="off"
                           class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all">
                    
                    {{-- Search Results Dropdown --}}
                    <div id="search-results" class="absolute left-0 right-0 top-full mt-2 bg-white border border-gray-100 rounded-2xl shadow-2xl z-50 hidden max-h-60 overflow-y-auto divide-y divide-gray-50">
                        <!-- Results will be injected here -->
                    </div>
                </div>
                <input type="hidden" name="member_id" id="target-member-id">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="amount" class="text-xs font-bold text-text-muted ml-1">변동 금액 <span class="text-red-500">*</span></label>
                    <div class="relative group">
                        <input type="number" name="amount" id="amount" placeholder="예: 1000" 
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-black text-text-muted">원</span>
                    </div>
                    <p class="text-[10px] text-text-muted ml-1">차감 시 마이너스(-) 기호를 붙여주세요.</p>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-text-muted ml-1">현재 보유 적립금</label>
                    <div class="px-4 py-3 bg-gray-100 rounded-2xl text-sm font-black text-text-muted" id="current-points-display">
                        회원 선택 시 표시
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                <label for="reason" class="text-xs font-bold text-text-muted ml-1">지급/차감 사유 <span class="text-red-500">*</span></label>
                <textarea name="reason" id="reason" rows="2" placeholder="정확한 사유를 입력해 주세요." 
                          class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all"></textarea>
            </div>

            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeManualModal()" 
                        class="flex-1 py-4 bg-gray-100 text-text-muted text-sm font-black rounded-2xl hover:bg-gray-200 transition-all text-center">취소</button>
                <button type="submit" class="flex-[2] py-4 bg-primary text-white text-sm font-black rounded-2xl hover:bg-red-600 transition-all shadow-xl shadow-primary/20 active:scale-95">반영하기</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let searchTimer = null;

    /**
     * 수동 적립금 모달 열기
     */
    function openManualModal() {
        $('#manual-point-modal').removeClass('hidden').addClass('flex');
        $('body').addClass('overflow-hidden');
    }

    /**
     * 수동 적립금 모달 닫기
     */
    function closeManualModal() {
        $('#manual-point-modal').removeClass('flex').addClass('hidden');
        $('body').removeClass('overflow-hidden');
        resetMemberSelection();
        $('#amount, #reason').val('');
    }

    /**
     * 회원 선택 상태 리셋
     */
    function resetMemberSelection() {
        $('#selected-member-display').hide();
        $('#member-search-container').show();
        $('#target-member-id').val('');
        $('#member-search-input').val('').focus();
        $('#current-points-display').text('회원 선택 시 표시');
    }

    /**
     * 회원 선택 완료
     */
    function selectMember(id, name, email, points) {
        $('#target-member-id').val(id);
        $('#selected-member-name').text(name);
        $('#selected-member-email').text(email);
        $('#selected-member-initial').text(name.substring(0, 1));
        $('#current-points-display').text(new Intl.NumberFormat().format(points) + '원');
        
        $('#member-search-container').hide();
        $('#selected-member-display').css('display', 'flex');
        $('#search-results').hide();
    }

    $(document).ready(function() {
        /**
         * 실시간 회원 검색 (Debounce 적용)
         */
        $('#member-search-input').on('input', function() {
            const q = $(this).val().trim();
            const $results = $('#search-results');

            clearTimeout(searchTimer);

            if (q.length < 2) {
                $results.hide();
                return;
            }

            searchTimer = setTimeout(() => {
                $.ajax({
                    url: "{{ route('admin.points.search-members') }}",
                    data: { q: q },
                    success: function(data) {
                        if (data.length > 0) {
                            let html = '';
                            data.forEach(member => {
                                html += `
                                    <div onclick="selectMember(${member.id}, '${member.name}', '${member.email}', ${member.points})" 
                                         class="px-5 py-3 hover:bg-primary/5 cursor-pointer transition-colors group">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-black text-text-main group-hover:text-primary">${member.name}</p>
                                                <p class="text-[11px] font-bold text-text-muted">${member.email}</p>
                                            </div>
                                            <span class="text-xs font-bold text-text-muted bg-gray-100 px-2 py-1 rounded-lg">현재 ${new Intl.NumberFormat().format(member.points)}원</span>
                                        </div>
                                    </div>
                                `;
                            });
                            $results.html(html).show();
                        } else {
                            $results.html('<div class="px-5 py-8 text-center text-xs text-text-muted font-bold">검색 결과가 없습니다.</div>').show();
                        }
                    }
                });
            }, 300); // 0.3초 대기 후 서버 요청! (명품 디바운싱)
        });

        // 결과창 외부 클릭 시 닫기
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#member-search-container').length) {
                $('#search-results').hide();
            }
        });
    });
</script>
@endpush
