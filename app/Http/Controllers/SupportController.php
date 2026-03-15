<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Notice;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    /**
     * FAQ 목록 페이지
     */
    public function index(Request $request)
    {
        $query = Faq::where('is_visible', true);

        // 카테고리 필터링 ✨
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // 검색 필터링 ✨🔍
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                  ->orWhere('answer', 'like', "%{$search}%");
            });
        }

        $faqs = $query->orderBy('sort_order', 'asc')
                      ->orderBy('created_at', 'desc')
                      ->paginate(10)
                      ->withQueryString();

        return view('pages.support', compact('faqs'));
    }

    /**
     * 고객센터 공지사항 페이지 ✨📢
     */
    public function notices()
    {
        $notices = Notice::where('is_visible', true)
            ->orderBy('is_important', 'desc')
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('pages.support-notice', compact('notices'));
    }
}
