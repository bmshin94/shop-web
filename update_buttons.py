import re

def update_buttons(filepath, is_wishlist):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # 1. Update "선택 삭제" button
    pattern_del = r'class="text-sm border border-border-color text-text-main rounded px-3 py-1\.5 hover:bg-background-alt font-bold transition-colors">\s*선택\s*삭제\s*</button>'
    new_del = 'class="text-sm border border-gray-200 text-gray-600 bg-white rounded-lg px-4 py-2 hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition-all shadow-sm font-bold flex items-center gap-1.5"><span class="material-symbols-outlined text-[18px]">delete</span>선택 삭제</button>'
    content = re.sub(pattern_del, new_del, content)
    
    # 2. Update individual buttons
    if is_wishlist:
        pattern_item = r'class="btn-remove-item absolute top-2 right-2 p-1\.5 rounded-full bg-white/80 text-primary hover:bg-white transition-colors"'
    else:
        pattern_item = r'class="btn-remove-item absolute top-2 right-2 p-1\.5 rounded-full bg-white/80 text-text-muted hover:text-primary transition-colors"'
        
    new_item = 'class="btn-remove-item absolute top-2 right-2 p-1.5 rounded-full bg-white shadow hover:bg-red-50 hover:text-red-500 text-gray-400 transition-all z-10 opacity-0 group-hover:opacity-100 flex items-center justify-center"'
    content = re.sub(pattern_item, new_item, content)

    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)

update_buttons('d:/project/shop-web/mypage-recent.html', False)
update_buttons('d:/project/shop-web/mypage-wishlist.html', True)
print("Updated successfully")
