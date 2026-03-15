<?php

namespace App\Http\Controllers;

use App\Models\Magazine;
use App\Models\Ootd;
use App\Models\Notice;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    /**
     * 커뮤니티 메인 페이지
     */
    public function index()
    {
        // 매거진 데이터 (6개씩 페이징)
        $magazines = Magazine::where('is_visible', true)
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        $magList = $magazines->getCollection()->map(function ($m) {
            return [
                'id' => $m->id,
                'cat' => $m->category,
                'title' => $m->title,
                'author' => $m->author,
                'date' => $m->published_at ? $m->published_at->format('Y.m.d') : $m->created_at->format('Y.m.d'),
                'img' => $m->image_url,
                'content' => $m->content
            ];
        });

        // OOTD 데이터 (10개씩 페이징)
        $ootds = Ootd::with('member')
            ->where('is_visible', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $ootdList = $ootds->getCollection()->map(function ($o) {
            return [
                'id' => $o->id,
                'user' => $o->member ? '@' . ($o->member->username ?? $o->member->name) : '@익명',
                'likes' => $o->likes_count,
                'img' => $o->image_url,
                'content' => $o->content,
                'insta' => $o->instagram_url,
                'liked' => $o->isLikedBy(auth()->user()),
                'is_mine' => auth()->id() === $o->member_id
            ];
        });

        // 공지사항 데이터 (10개씩 페이징! )
        $notices = Notice::where('is_visible', true)
            ->orderBy('is_important', 'desc')
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10); // 공지사항 페이징 적용! 

        $noticeList = $notices->getCollection()->map(function ($n) {
            return [
                'id' => $n->id,
                'type' => $n->type,
                'title' => $n->title,
                'date' => $n->published_at ? $n->published_at->format('Y.m.d') : $n->created_at->format('Y.m.d'),
                'content' => $n->content
            ];
        });

        return view('pages.community', [
            'magazines' => $magList,
            'hasMoreMag' => $magazines->hasMorePages(),
            'ootds' => $ootdList,
            'hasMoreOotd' => $ootds->hasMorePages(),
            'notices' => $noticeList,
            'hasMoreNotice' => $notices->hasMorePages() // 공지사항 더보기 여부 
        ]);
    }

    /**
     * 매거진 더보기 (AJAX)
     */
    public function moreMagazines(Request $request)
    {
        $magazines = Magazine::where('is_visible', true)
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        $data = $magazines->getCollection()->map(function ($m) {
            return [
                'id' => $m->id,
                'cat' => $m->category,
                'title' => $m->title,
                'author' => $m->author,
                'date' => $m->published_at ? $m->published_at->format('Y.m.d') : $m->created_at->format('Y.m.d'),
                'img' => $m->image_url,
                'content' => $m->content
            ];
        });

        return response()->json([
            'data' => $data,
            'hasMore' => $magazines->hasMorePages()
        ]);
    }

    /**
     * OOTD 더보기 (AJAX)
     */
    public function moreOotds(Request $request)
    {
        $ootds = Ootd::with('member')
            ->where('is_visible', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = $ootds->getCollection()->map(function ($o) {
            return [
                'id' => $o->id,
                'user' => $o->member ? '@' . ($o->member->username ?? $o->member->name) : '@익명',
                'likes' => $o->likes_count,
                'img' => $o->image_url,
                'content' => $o->content,
                'insta' => $o->instagram_url,
                'liked' => $o->isLikedBy(auth()->user()),
                'is_mine' => auth()->id() === $o->member_id
            ];
        });

        return response()->json([
            'data' => $data,
            'hasMore' => $ootds->hasMorePages()
        ]);
    }

    /**
     * 공지사항 더보기 (AJAX) 
     */
    public function moreNotices(Request $request)
    {
        $notices = Notice::where('is_visible', true)
            ->orderBy('is_important', 'desc')
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = $notices->getCollection()->map(function ($n) {
            return [
                'id' => $n->id,
                'type' => $n->type,
                'title' => $n->title,
                'date' => $n->published_at ? $n->published_at->format('Y.m.d') : $n->created_at->format('Y.m.d'),
                'content' => $n->content
            ];
        });

        return response()->json([
            'data' => $data,
            'hasMore' => $notices->hasMorePages()
        ]);
    }
}
