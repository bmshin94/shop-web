<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Magazine;
use Illuminate\Http\Request;

class MagazineController extends Controller
{
    /**
     * 매거진 목록 조회
     */
    public function index()
    {
        $magazines = Magazine::orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.magazine.index', compact('magazines'));
    }

    /**
     * 매거진 등록 폼
     */
    public function create()
    {
        return view('admin.magazine.create');
    }

    /**
     * 매거진 등록 처리
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:100',
            'image_url' => 'required|url',
            'content' => 'nullable|string',
            'is_visible' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        Magazine::create($validated);

        return redirect()->route('admin.magazines.index')->with('success', '매거진이 성공적으로 등록되었어! ✨💖');
    }

    /**
     * 매거진 수정 폼
     */
    public function edit(Magazine $magazine)
    {
        return view('admin.magazine.edit', compact('magazine'));
    }

    /**
     * 매거진 수정 처리
     */
    public function update(Request $request, Magazine $magazine)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:100',
            'image_url' => 'required|url',
            'content' => 'nullable|string',
            'is_visible' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $magazine->update($validated);

        return redirect()->route('admin.magazines.index')->with('success', '매거진이 성공적으로 수정되었어! ✨💖');
    }

    /**
     * 매거진 삭제 처리
     */
    public function destroy(Magazine $magazine)
    {
        $magazine->delete();

        return redirect()->route('admin.magazines.index')->with('success', '매거진이 성공적으로 삭제되었어! ✨💖');
    }
}
