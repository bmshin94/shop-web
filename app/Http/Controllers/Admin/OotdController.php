<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ootd;
use App\Models\Member;
use Illuminate\Http\Request;

class OotdController extends Controller
{
    /**
     * OOTD 목록 조회
     */
    public function index()
    {
        $ootds = Ootd::with('member')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.ootd.index', compact('ootds'));
    }

    /**
     * OOTD 등록 폼
     */
    public function create()
    {
        $members = Member::orderBy('name')->get();
        return view('admin.ootd.create', compact('members'));
    }

    /**
     * OOTD 등록 처리
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'image_url' => 'required|url',
            'content' => 'nullable|string',
            'likes_count' => 'integer|min:0',
            'is_visible' => 'boolean',
        ]);

        Ootd::create($validated);

        return redirect()->route('admin.ootds.index')->with('success', 'OOTD가 성공적으로 등록되었어! 📸💖');
    }

    /**
     * OOTD 수정 폼
     */
    public function edit(Ootd $ootd)
    {
        $members = Member::orderBy('name')->get();
        return view('admin.ootd.edit', compact('ootd', 'members'));
    }

    /**
     * OOTD 수정 처리
     */
    public function update(Request $request, Ootd $ootd)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'image_url' => 'required|url',
            'content' => 'nullable|string',
            'likes_count' => 'integer|min:0',
            'is_visible' => 'boolean',
        ]);

        $ootd->update($validated);

        return redirect()->route('admin.ootds.index')->with('success', 'OOTD가 성공적으로 수정되었어! 📸💖');
    }

    /**
     * OOTD 삭제 처리
     */
    public function destroy(Ootd $ootd)
    {
        $ootd->delete();

        return redirect()->route('admin.ootds.index')->with('success', 'OOTD가 성공적으로 삭제되었어! 📸💖');
    }
}
