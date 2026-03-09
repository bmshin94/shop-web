<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use App\Models\SizeGroup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SizeController extends Controller
{
    /**
     * 사이즈 및 그룹 목록 페이지
     */
    public function index()
    {
        $sizeGroups = SizeGroup::with('sizes')->get();
        return view('admin.sizes.index', compact('sizeGroups'));
    }

    /**
     * 사이즈 그룹 저장
     */
    public function storeGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:size_groups,name',
        ]);

        SizeGroup::create($request->only('name'));

        return redirect()->route('admin.sizes.index')->with('success', '새로운 사이즈 그룹이 등록되었습니다.');
    }

    /**
     * 사이즈 그룹 삭제
     */
    public function destroyGroup(SizeGroup $group)
    {
        if ($group->sizes()->count() > 0) {
            return redirect()->route('admin.sizes.index')->with('error', '해당 그룹에 속한 사이즈가 있어 삭제할 수 없습니다.');
        }

        $group->delete();
        return redirect()->route('admin.sizes.index')->with('success', '사이즈 그룹이 삭제되었습니다.');
    }

    /**
     * 사이즈 저장
     */
    public function store(Request $request)
    {
        $request->validate([
            'size_group_id' => 'required|exists:size_groups,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sizes')->where(fn ($q) => $q->where('size_group_id', $request->size_group_id))
            ],
            'sort_order' => 'required|integer|min:0',
        ]);

        Size::create($request->only(['size_group_id', 'name', 'sort_order']));

        return redirect()->route('admin.sizes.index')->with('success', '새로운 사이즈가 등록되었습니다.');
    }

    /**
     * 사이즈 삭제
     */
    public function destroy(Size $size)
    {
        if ($size->products()->count() > 0) {
            return redirect()->route('admin.sizes.index')->with('error', '해당 사이즈를 사용하는 상품이 있어 삭제할 수 없습니다.');
        }

        $size->delete();
        return redirect()->route('admin.sizes.index')->with('success', '사이즈가 삭제되었습니다.');
    }
}
