@extends('layouts.admin')

@section('page_title', '이벤트 등록')

@section('content')
<div class="space-y-6 lg:space-y-8">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.events.index') }}" class="flex items-center justify-center size-10 rounded-xl bg-white border border-gray-200 text-text-muted hover:text-primary hover:border-primary transition-all shadow-sm">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <h3 class="text-2xl font-extrabold text-text-main">이벤트 등록</h3>
                <p class="mt-1 text-[12px] font-bold text-text-muted">운영 중인 이벤트를 등록하고 상태/기간을 설정합니다.</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
        @include('admin.events._form', [
            'formAction' => route('admin.events.store'),
            'formMethod' => 'POST',
            'submitLabel' => '이벤트 등록',
            'hasFile' => true,
        ])
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
                
                placeholder.classList.add('hidden');
                placeholder.classList.remove('block');
                
                if(hintText) {
                    hintText.innerText = '새로운 이미지가 선택되었습니다.';
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
