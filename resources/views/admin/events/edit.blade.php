@extends('layouts.admin')

@section('page_title', '이벤트 상세 및 수정')

@push('styles')
<style>
    .event-detail-grid {
        display: grid;
        gap: 24px;
    }

    @media (min-width: 1024px) {
        .event-detail-grid {
            grid-template-columns: 1fr 1.2fr;
            align-items: start;
        }
    }
</style>
@endpush

@section('content')
<div class="space-y-6 lg:space-y-8">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.events.index', request()->query()) }}" class="flex items-center justify-center size-10 rounded-xl bg-white border border-gray-200 text-text-muted hover:text-primary hover:border-primary transition-all shadow-sm">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <h3 class="text-2xl font-extrabold text-text-main">{{ $event->title }}</h3>
                <p class="mt-1 text-[12px] font-bold text-text-muted">슬러그 /{{ $event->slug }}</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <x-admin.status-badge type="event" :value="$event->status" class="px-3 py-1.5 text-[12px]" />
            <a href="{{ route('admin.events.trash') }}" class="inline-flex items-center gap-1 rounded-full border border-gray-200 bg-white px-3 py-1.5 text-[12px] font-bold text-text-main hover:border-primary hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[14px]">delete</span>
                휴지통
            </a>
        </div>
    </div>

    <!-- 상단 요약 카드 (운영자 관리 화면 참고) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 lg:gap-6">
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <p class="text-[11px] font-bold text-text-muted uppercase">이벤트 ID</p>
            <p class="mt-3 text-2xl font-black text-text-main">#{{ number_format($event->id) }}</p>
        </div>
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <p class="text-[11px] font-bold text-text-muted uppercase">시작 일시</p>
            <p class="mt-3 text-lg font-black text-text-main">{{ optional($event->start_at)->format('Y.m.d H:i') ?: '-' }}</p>
        </div>
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <p class="text-[11px] font-bold text-text-muted uppercase">종료 일시</p>
            <p class="mt-3 text-lg font-black text-text-main">{{ optional($event->end_at)->format('Y.m.d H:i') ?: '-' }}</p>
        </div>
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <p class="text-[11px] font-bold text-text-muted uppercase">현재 상태</p>
            <p class="mt-3 text-lg font-black text-text-main">{{ $event->status }}</p>
        </div>
    </div>

    <div class="event-detail-grid">
        <div class="space-y-6">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                <h4 class="text-base font-extrabold text-text-main mb-5">이벤트 기본 정보</h4>
                <dl class="space-y-4 text-sm">
                    <div>
                        <dt class="text-[11px] font-bold text-text-muted uppercase">이벤트명</dt>
                        <dd class="mt-1 font-bold text-text-main">{{ $event->title }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-bold text-text-muted uppercase">상태</dt>
                        <dd class="mt-1"><x-admin.status-badge type="event" :value="$event->status" /></dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-bold text-text-muted uppercase">기간</dt>
                        <dd class="mt-1 font-bold text-text-main">
                            {{ optional($event->start_at)->format('Y.m.d H:i') ?: '-' }}
                            <span class="text-text-muted">~</span>
                            {{ optional($event->end_at)->format('Y.m.d H:i') ?: '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-bold text-text-muted uppercase">정렬 순서</dt>
                        <dd class="mt-1 font-bold text-text-main">{{ number_format($event->sort_order) }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-bold text-text-muted uppercase">생성일</dt>
                        <dd class="mt-1 font-bold text-text-main">{{ optional($event->created_at)->format('Y.m.d H:i') ?: '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-bold text-text-muted uppercase">수정일</dt>
                        <dd class="mt-1 font-bold text-text-main">{{ optional($event->updated_at)->format('Y.m.d H:i') ?: '-' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white rounded-3xl border border-red-100 shadow-sm p-6">
                <h4 class="text-base font-extrabold text-text-main mb-4">이벤트 삭제</h4>
                <p class="text-[12px] font-bold text-text-muted leading-relaxed">
                    이벤트를 삭제하면 목록에서 숨겨지며(soft delete), 휴지통에서 복구 또는 영구삭제할 수 있습니다.
                </p>
                <form
                    action="{{ route('admin.events.destroy', $event) }}"
                    method="POST"
                    class="mt-5 js-confirm-submit"
                    data-confirm-title="이벤트 삭제"
                    data-confirm-message="이 이벤트를 soft delete 처리하시겠습니까? 목록에서 숨김 처리됩니다."
                    data-confirm-text="삭제 처리">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-5 py-4 bg-red-50 text-red-600 border border-red-200 rounded-2xl text-sm font-extrabold hover:bg-red-100 transition-colors">
                        이벤트 삭제
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
            <h4 class="text-base font-extrabold text-text-main mb-5">이벤트 정보 수정</h4>
            <form action="{{ route('admin.events.update', array_merge(['event' => $event->id], request()->query())) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.events._form', [
                    'event' => $event,
                    'formAction' => null, // form 태그를 밖에서 감싸므로 null 처리 ✨
                    'submitLabel' => '수정하기'
                ])
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function handleImagePreview(input) {
        const preview = document.getElementById('image_preview');
        const placeholder = document.getElementById('upload_placeholder');
        const hintText = document.getElementById('upload_hint_text');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                preview.classList.add('block');
                
                if (placeholder) {
                    placeholder.classList.add('hidden');
                    placeholder.classList.remove('block');
                }
                
                if(hintText) {
                    hintText.innerText = '변경될 이미지가 미리보기로 표시됩니다.';
                    hintText.classList.add('text-primary');
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Drag and Drop styling
    const dropZone = document.getElementById('drop_zone');
    const fileInput = document.getElementById('banner_image_input');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.classList.add('border-primary', 'bg-primary/5');
        dropZone.classList.remove('border-gray-300', 'bg-gray-50');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-primary', 'bg-primary/5');
        dropZone.classList.add('border-gray-300', 'bg-gray-50');
    }

    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files && files.length > 0) {
            fileInput.files = files;
            // Trigger change event to update preview
            const event = new Event('change');
            fileInput.dispatchEvent(event);
        }
    }

    // --- Member Search & Winner Selection ---
    const $searchInput = $('#member_search_input');
    const $resultsDropdown = $('#search_results_dropdown');
    const $resultsList = $('#search_results_list');
    const $winnersList = $('#selected_winners_list');
    const $noWinnersText = $('#no_winners_text');
    const $winnerCountDisplay = $('#winner_count');

    function updateWinnerCount() {
        const count = $winnersList.find('.winner-tag').length;
        $winnerCountDisplay.text(count);
        if (count > 0) {
            $noWinnersText.hide();
        } else {
            $noWinnersText.show();
        }
    }

    let searchTimer;
    $searchInput.on('input', function() {
        clearTimeout(searchTimer);
        const keyword = $(this).val().trim();

        if (keyword.length < 2) {
            $resultsDropdown.hide();
            return;
        }

        searchTimer = setTimeout(() => {
            $.ajax({
                url: "{{ route('admin.events.search-members') }}",
                method: 'GET',
                data: { keyword },
                success: function(members) {
                    $resultsList.empty();
                    if (members.length === 0) {
                        $resultsList.append('<div class="px-4 py-3 text-sm text-text-muted">검색 결과가 없습니다.</div>');
                    } else {
                        members.forEach(member => {
                            const isAlreadySelected = $winnersList.find(`[data-id="${member.id}"]`).length > 0;
                            const btnHtml = isAlreadySelected 
                                ? '<span class="text-primary text-xs font-bold">선택됨</span>' 
                                : `<button type="button" onclick='addWinner(${JSON.stringify(member)})' class="px-3 py-1 bg-primary text-white text-xs font-bold rounded-lg hover:bg-black transition-colors">추가</button>`;
                            
                            $resultsList.append(`
                                <div class="px-4 py-3 flex items-center justify-between hover:bg-gray-50 transition-colors">
                                    <div>
                                        <p class="text-sm font-bold text-text-main">${member.name}</p>
                                        <p class="text-[11px] text-text-muted">${member.email}</p>
                                    </div>
                                    ${btnHtml}
                                </div>
                            `);
                        });
                    }
                    $resultsDropdown.show();
                }
            });
        }, 300);
    });

    // Close dropdown on click outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.relative.group').length) {
            $resultsDropdown.hide();
        }
    });

    window.addWinner = function(member) {
        if ($winnersList.find(`[data-id="${member.id}"]`).length > 0) return;

        const tag = `
            <div class="winner-tag inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-gray-200 rounded-xl text-xs font-bold text-text-main shadow-sm animate-enter" data-id="${member.id}">
                <input type="hidden" name="winner_ids[]" value="${member.id}">
                <span>${member.name} (${member.email})</span>
                <button type="button" onclick="removeWinner(this)" class="text-text-muted hover:text-red-500 transition-colors">
                    <span class="material-symbols-outlined text-[16px]">close</span>
                </button>
            </div>
        `;
        $winnersList.append(tag);
        $resultsDropdown.hide();
        $searchInput.val('');
        updateWinnerCount();
    };

    window.removeWinner = function(btn) {
        $(btn).closest('.winner-tag').remove();
        updateWinnerCount();
    };

    // Initial count
    updateWinnerCount();
</script>
@endpush
