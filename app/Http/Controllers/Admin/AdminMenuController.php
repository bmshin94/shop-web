<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminMenu;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminMenuController extends Controller
{
    /**
     * 관리자 메뉴 목록 조회
     */
    public function index()
    {
        $menus = AdminMenu::with('children')
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        return view('admin.menus.index', compact('menus'));
    }

    /**
     * 메뉴 등록 화면
     */
    public function create()
    {
        $parentMenus = AdminMenu::whereNull('parent_id')->orderBy('sort_order')->get();
        return view('admin.menus.create', compact('parentMenus'));
    }

    /**
     * 메뉴 저장 처리
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'group_name' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'route' => 'nullable|string|max:255',
            'permission_key' => 'nullable|string|max:255',
            'sort_order' => 'required|integer',
            'is_active' => 'required|boolean',
            'parent_id' => 'nullable|exists:admin_menus,id',
        ]);

        AdminMenu::create($request->all());

        return redirect()->route('admin.menus.index')->with('success', '새로운 메뉴가 등록되었습니다.');
    }

    /**
     * 메뉴 수정 화면
     */
    public function edit(AdminMenu $menu)
    {
        $parentMenus = AdminMenu::whereNull('parent_id')
            ->where('id', '!=', $menu->id)
            ->orderBy('sort_order')
            ->get();
            
        return view('admin.menus.edit', compact('menu', 'parentMenus'));
    }

    /**
     * 메뉴 수정 처리
     */
    public function update(Request $request, AdminMenu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'group_name' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'route' => 'nullable|string|max:255',
            'permission_key' => 'nullable|string|max:255',
            'sort_order' => 'required|integer',
            'is_active' => 'required|boolean',
            'parent_id' => 'nullable|exists:admin_menus,id',
        ]);

        $menu->update($request->all());

        return redirect()->route('admin.menus.index')->with('success', '메뉴 정보가 수정되었습니다.');
    }

    /**
     * 메뉴 삭제 처리
     */
    public function destroy(AdminMenu $menu)
    {
        if ($menu->children()->count() > 0) {
            return back()->with('error', '하위 메뉴가 있는 메뉴는 삭제할 수 없습니다.');
        }

        $menu->delete();

        return redirect()->route('admin.menus.index')->with('success', '메뉴가 삭제되었습니다.');
    }
}
