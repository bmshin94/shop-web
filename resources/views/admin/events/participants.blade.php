@extends('layouts.admin')

@section('page_title', '이벤트 응모자 명단')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.events.index', request()->query()) }}" class="size-10 rounded-2xl bg-white border border-gray-100 flex items-center justify-center text-text-muted hover:text-primary transition-colors shadow-sm">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="text-xl font-black text-text-main">{{ $event->title }}</h2>
                <p class="text-sm font-bold text-text-muted mt-1">총 {{ number_format($participants->total()) }}명의 회원이 응모하였습니다.</p>
            </div>
        </div>
        <a href="{{ route('admin.events.participants.export', $event) }}" class="flex items-center justify-center gap-2 px-6 py-3 bg-emerald-500 text-white rounded-2xl text-sm font-bold hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/20">
            <span class="material-symbols-outlined text-[20px]">download</span>
            명단 다운로드 (CSV)
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="hidden lg:grid grid-cols-[80px_1fr_1.5fr_1.2fr_1fr_100px] px-6 py-4 bg-gray-50/70 border-b border-gray-100">
            <div class="text-[11px] font-bold text-text-muted uppercase">ID</div>
            <div class="text-[11px] font-bold text-text-muted uppercase">이름</div>
            <div class="text-[11px] font-bold text-text-muted uppercase">이메일</div>
            <div class="text-[11px] font-bold text-text-muted uppercase">휴대폰</div>
            <div class="text-[11px] font-bold text-text-muted uppercase text-center">응모일시</div>
            <div class="text-right text-[11px] font-bold text-text-muted uppercase">당첨선정</div>
        </div>

        <div class="divide-y divide-gray-50">
            @forelse($participants as $p)
                @php $isWinner = in_array($p->member->id, $winnerIds); @endphp
                <div class="grid grid-cols-1 lg:grid-cols-[80px_1fr_1.5fr_1.2fr_1fr_100px] px-4 lg:px-6 py-4 hover:bg-gray-50/60 transition-colors items-center gap-2 lg:gap-0 {{ $isWinner ? 'bg-amber-50/30' : '' }}" id="row-{{ $p->member->id }}">
                    <div class="text-xs font-bold text-text-muted">#{{ $p->member->id }}</div>
                    <div class="text-sm font-extrabold text-text-main flex items-center gap-2">
                        {{ $p->member->name }}
                        <span class="winner-badge {{ $isWinner ? '' : 'hidden' }} px-1.5 py-0.5 bg-amber-100 text-amber-600 text-[9px] font-black rounded uppercase">Winner</span>
                    </div>
                    <div class="text-sm font-bold text-text-muted">{{ $p->member->email }}</div>
                    <div class="text-sm font-bold text-text-main">{{ $p->member->phone ?: '-' }}</div>
                    <div class="text-center">
                        <p class="text-[12px] font-bold text-text-muted">{{ $p->created_at->format('Y.m.d') }}</p>
                    </div>
                    <div class="text-right">
                        <label class="relative inline-flex items-center cursor-pointer group">
                            <input type="checkbox" 
                                   class="sr-only peer js-toggle-winner" 
                                   data-member-id="{{ $p->member->id }}"
                                   {{ $isWinner ? 'checked' : '' }}>
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-amber-500"></div>
                        </label>
                    </div>
                </div>
            @empty
                <div class="py-20 text-center">
                    <div class="size-16 rounded-full bg-gray-50 flex items-center justify-center text-gray-300 mx-auto mb-4">
                        <span class="material-symbols-outlined text-[32px]">group_off</span>
                    </div>
                    <p class="text-sm font-bold text-text-muted">아직 응모한 회원이 없습니다.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-10">
        {{ $participants->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.js-toggle-winner').on('change', function() {
            const $this = $(this);
            const memberId = $this.data('member-id');
            const isWinner = $this.is(':checked');
            const $row = $(`#row-${memberId}`);
            const $badge = $row.find('.winner-badge');

            $.ajax({
                url: `/admin/events/{{ $event->id }}/participants/${memberId}/toggle-winner`,
                method: 'PATCH',
                data: {
                    _token: '{{ csrf_token() }}',
                    is_winner: isWinner ? 1 : 0
                },
                success: function(res) {
                    if (res.success) {
                        if (isWinner) {
                            $row.addClass('bg-amber-50/30');
                            $badge.removeClass('hidden');
                            showToast('당첨자로 선정되었습니다.', 'star', 'bg-amber-500');
                        } else {
                            $row.removeClass('bg-amber-50/30');
                            $badge.addClass('hidden');
                            showToast('당첨 선정이 취소되었습니다.');
                        }
                    }
                },
                error: function() {
                    $this.prop('checked', !isWinner); // Revert on error
                    showAlert('상태 변경 중 오류가 발생했습니다.', '오류', 'error');
                }
            });
        });
    });
</script>
@endpush
