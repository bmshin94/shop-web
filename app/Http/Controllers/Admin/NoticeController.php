<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    /**
     * 공지사항 목록 조회
     */
    public function index()
    {
        $notices = Notice::orderBy('is_important', 'desc')
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.notice.index', compact('notices'));
    }

    /**
     * 공지사항 등록 폼
     */
    public function create()
    {
        return view('admin.notice.create');
    }

    /**
     * 공지사항 등록 처리
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_important' => 'boolean',
            'is_visible' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        Notice::create($validated);

        return redirect()->route('admin.notices.index')->with('success', '공지사항이 성공적으로 등록되었어! ');
    }

    /**
     * 공지사항 수정 폼
     */
    public function edit(Notice $notice)
    {
        return view('admin.notice.edit', compact('notice'));
    }

    /**
     * 공지사항 수정 처리
     */
    public function update(Request $request, Notice $notice)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_important' => 'boolean',
            'is_visible' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $notice->update($validated);

        return redirect()->route('admin.notices.index')->with('success', '공지사항이 성공적으로 수정되었어! ');
    }

    /**
     * 공지사항 삭제 처리
     */
    public function destroy(Notice $notice)
    {
        $notice->delete();

        return redirect()->route('admin.notices.index')->with('success', '공지사항이 성공적으로 삭제되었어! ');
    }
}
