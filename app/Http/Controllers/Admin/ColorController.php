<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ColorController extends Controller
{
    /**
     * 색상 목록 페이지
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $query = Color::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('hex_code', 'like', "%{$search}%");
        }

        $colors = $query->orderBy('name')->get();

        return view('admin.colors.index', compact('colors', 'search'));
    }

    /**
     * 새로운 색상 저장 처리
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:colors,name'],
            'hex_code' => ['required', 'string', 'unique:colors,hex_code', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/i'],
        ], [
            'name.required' => '색상 이름을 입력해 주세요.',
            'hex_code.required' => 'HEX 코드를 입력해 주세요.',
            'name.unique' => '이미 등록된 색상 이름이에요.',
            'hex_code.unique' => '이미 등록된 HEX 코드예요.',
            'hex_code.regex' => '올바른 HEX 코드 형식이어야 해요 (예: #FFFFFF).',
        ]);

        $data = $request->all();
        if (!str_starts_with($data['hex_code'], '#')) {
            $data['hex_code'] = '#' . $data['hex_code'];
        }

        Color::create($data);

        return redirect()->route('admin.colors.index')->with('success', '새로운 색상이 등록되었습니다.');
    }

    /**
     * 색상 수정 화면
     */
    public function edit(Color $color)
    {
        return view('admin.colors.edit', compact('color'));
    }

    /**
     * 색상 업데이트 처리
     */
    public function update(Request $request, Color $color)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:50', Rule::unique('colors', 'name')->ignore($color->id)],
            'hex_code' => ['required', 'string', Rule::unique('colors', 'hex_code')->ignore($color->id), 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/i'],
        ], [
            'name.required' => '색상 이름을 입력해 주세요.',
            'hex_code.required' => 'HEX 코드를 입력해 주세요.',
            'name.unique' => '이미 등록된 색상 이름이에요.',
            'hex_code.unique' => '이미 등록된 HEX 코드예요.',
            'hex_code.regex' => '올바른 HEX 코드 형식이어야 해요 (예: #FFFFFF).',
        ]);

        $data = $request->all();
        if (!str_starts_with($data['hex_code'], '#')) {
            $data['hex_code'] = '#' . $data['hex_code'];
        }

        $color->update($data);

        return redirect()->route('admin.colors.index')->with('success', '색상 정보가 수정되었습니다.');
    }

    /**
     * 색상 삭제 처리
     */
    public function destroy(Color $color)
    {
        $color->delete();
        return redirect()->route('admin.colors.index')->with('success', '색상이 성공적으로 삭제되었습니다.');
    }
}
