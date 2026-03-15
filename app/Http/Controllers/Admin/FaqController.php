<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * FAQ 목록 조회
     */
    public function index()
    {
        $faqs = Faq::orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.faq.index', compact('faqs'));
    }

    /**
     * FAQ 등록 폼
     */
    public function create()
    {
        return view('admin.faq.create');
    }

    /**
     * FAQ 등록 처리
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:50',
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'is_visible' => 'boolean',
            'sort_order' => 'integer',
        ]);

        Faq::create($validated);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ가 성공적으로 등록되었습니다. ✨');
    }

    /**
     * FAQ 수정 폼
     */
    public function edit(Faq $faq)
    {
        return view('admin.faq.edit', compact('faq'));
    }

    /**
     * FAQ 수정 처리
     */
    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:50',
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'is_visible' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $faq->update($validated);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ가 성공적으로 수정되었습니다. ✨');
    }

    /**
     * FAQ 삭제 처리
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ가 삭제되었습니다. 😢');
    }
}
