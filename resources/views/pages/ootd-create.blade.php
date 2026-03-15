@extends('layouts.app')

@section('title', 'Style OOTD 등록 - Active Women')

@section('content')
<main class="flex-1 w-full bg-white pb-20 pt-10">
    <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
        <div class="mb-10 text-center">
            <h1 class="text-3xl font-extrabold text-text-main tracking-tight mb-2">Style OOTD 등록</h1>
            <p class="text-text-muted text-sm">나만의 멋진 스타일링을 공유해 보세요.</p>
        </div>

        <form action="{{ route('ootd.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 bg-gray-50/50 p-8 rounded-3xl border border-gray-100 shadow-sm">
            @csrf
            <!-- Image File Upload -->
            <div class="space-y-4">
                <label class="block text-sm font-bold text-text-main ml-1">스타일 사진 <span class="text-primary">*</span></label>
                <div class="relative" id="drop-zone">
                    <input type="file" name="image_file" id="image_file" accept="image/*" 
                        class="hidden" onchange="previewImage(this)">
                    <label for="image_file" class="flex flex-col items-center justify-center w-full aspect-4-5 bg-white border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer hover:border-primary/50 hover:bg-primary/5 transition-all overflow-hidden group relative">
                        <div id="upload-placeholder" class="flex flex-col items-center justify-center space-y-3">
                            <span class="material-symbols-outlined text-[48px] text-gray-300 group-hover:text-primary transition-colors">add_photo_alternate</span>
                            <span class="text-sm font-bold text-gray-400 group-hover:text-primary transition-colors">사진을 선택하거나 여기로 드래그하세요.</span>
                        </div>
                        <img id="preview-img" src="" class="hidden w-full h-full object-cover">
                        <!-- Drag Over Overlay -->
                        <div id="drag-overlay" class="absolute inset-0 bg-primary/10 backdrop-blur-[2px] hidden items-center justify-center border-4 border-primary border-dashed rounded-2xl animate-pulse">
                            <div class="bg-white/90 px-6 py-3 rounded-full shadow-lg">
                                <span class="text-primary font-bold">파일을 내려놓으세요.</span>
                            </div>
                        </div>
                    </label>
                </div>
                <p class="mt-2 text-[10px] text-text-muted ml-1">JPG, PNG 파일만 가능하며, 최대 5MB까지 업로드 가능합니다.</p>
            </div>

            <!-- Content -->
            <div class="space-y-2">
                <label class="block text-sm font-bold text-text-main ml-1">스타일 설명 <span class="text-primary">*</span></label>
                <textarea name="content" rows="4" placeholder="스타일링에 대한 설명을 입력하세요. #ActiveWomen #OOTD"
                    class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all text-sm outline-none resize-none"></textarea>
            </div>

            <!-- Instagram Link -->
            <div class="space-y-2">
                <label class="block text-sm font-bold text-text-main ml-1">인스타그램 게시물 링크 <span class="text-text-muted font-normal text-xs">(선택)</span></label>
                <div class="relative">
                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-primary font-bold">@</span>
                    <input type="url" name="instagram_url" placeholder="https://www.instagram.com/p/..." 
                        class="w-full pl-10 pr-5 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all text-sm outline-none">
                </div>
                <p class="mt-1 text-[10px] text-text-muted ml-1">인스타그램 링크 입력 시 해당 게시물로 연결됩니다.</p>
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full py-5 bg-primary text-white font-bold rounded-2xl shadow-xl shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all text-lg tracking-tight">
                    등록하기
                </button>
                <a href="{{ route('community') }}" class="block text-center mt-6 text-sm text-text-muted font-bold hover:text-text-main transition-colors">
                    돌아가기
                </a>
            </div>
        </form>
    </div>
</main>

<script>
    const dropZone = document.getElementById('drop-zone');
    const dragOverlay = document.getElementById('drag-overlay');
    const fileInput = document.getElementById('image_file');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => dragOverlay.classList.replace('hidden', 'flex'), false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => dragOverlay.classList.replace('flex', 'hidden'), false);
    });

    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        previewImage(fileInput);
    }

    function previewImage(input) {
        const placeholder = document.getElementById('upload-placeholder');
        const previewImg = document.getElementById('preview-img');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewImg.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            previewImg.src = '';
            previewImg.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }
    }
</script>
@endsection
