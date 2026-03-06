<?php
function updateButtons($filepath, $isWishlist) {
    if (!file_exists($filepath)) return;
    $content = file_get_contents($filepath);

    // 1. Update "선택 삭제" button
    $pattern_del = '/class="text-sm border border-border-color text-text-main rounded px-3 py-1\.5 hover:bg-background-alt font-bold transition-colors">\s*선택\s*삭제\s*<\/button>/';
    $new_del = 'class="text-sm border border-gray-200 text-gray-600 bg-white rounded-lg px-4 py-2 hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition-all shadow-sm font-bold flex items-center gap-1.5"><span class="material-symbols-outlined text-[18px]">delete</span>선택 삭제</button>';
    $content = preg_replace($pattern_del, $new_del, $content);

    // 2. Update individual buttons
    if ($isWishlist) {
        $pattern_item = '/class="btn-remove-item absolute top-2 right-2 p-1\.5 rounded-full bg-white\/80 text-primary hover:bg-white transition-colors"/';
    } else {
        $pattern_item = '/class="btn-remove-item absolute top-2 right-2 p-1\.5 rounded-full bg-white\/80 text-text-muted hover:text-primary transition-colors"/';
    }
    
    $new_item = 'class="btn-remove-item absolute top-2 right-2 p-1.5 rounded-full bg-white shadow-md hover:bg-red-50 hover:text-red-500 text-gray-400 transition-all z-10 opacity-0 group-hover:opacity-100 flex items-center justify-center"';
    $content = preg_replace($pattern_item, $new_item, $content);

    file_put_contents($filepath, $content);
}

updateButtons("mypage-recent.html", false);
updateButtons("mypage-wishlist.html", true);
echo "Updated successfully\n";
