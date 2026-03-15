<?php

namespace App\Http\Controllers;

use App\Models\Ootd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OotdController extends Controller
{
    /**
     * OOTD 등록 폼 (모달로 변경되어 이제 사용하지 않지만 하위 호환을 위해 유지할 수 있어! ✨)
     */
    public function create()
    {
        return view('pages.ootd-create');
    }

    /**
     * OOTD 저장 처리 ✨💖 (AJAX 대응)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'image_file' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'content' => 'required|string|max:500',
            'instagram_url' => 'nullable|url',
        ]);

        $imagePath = null;
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('uploads/ootds', 'public');
            $imagePath = Storage::url($path);
        }

        $ootd = Ootd::create([
            'member_id' => Auth::id(),
            'image_url' => $imagePath,
            'content' => $validated['content'],
            'instagram_url' => $validated['instagram_url'],
            'likes_count' => 0,
            'is_visible' => true,
        ]);

        // AJAX 요청인 경우 JSON 응답 ✨
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => '등록되었습니다.',
                'data' => [
                    'id' => $ootd->id,
                    'user' => '@' . (Auth::user()->username ?? Auth::user()->name),
                    'img' => $ootd->image_url,
                    'likes' => 0,
                    'liked' => false,
                    'is_mine' => true
                ]
            ]);
        }

        return redirect()->route('community')->with('success', '등록되었습니다.');
    }

    /**
     * OOTD 수정 폼
     */
    public function edit(Ootd $ootd)
    {
        abort_unless(Auth::id() === $ootd->member_id, 403, '권한이 없습니다.');
        return view('pages.ootd-edit', compact('ootd'));
    }

    /**
     * OOTD 수정 처리
     */
    public function update(Request $request, Ootd $ootd)
    {
        abort_unless(Auth::id() === $ootd->member_id, 403);

        $validated = $request->validate([
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'content' => 'required|string|max:500',
            'instagram_url' => 'nullable|url',
        ]);

        $updateData = [
            'content' => $validated['content'],
            'instagram_url' => $validated['instagram_url'],
        ];

        if ($request->hasFile('image_file')) {
            if ($ootd->image_url) {
                $oldPath = str_replace('/storage/', '', $ootd->image_url);
                Storage::disk('public')->delete($oldPath);
            }
            
            $path = $request->file('image_file')->store('uploads/ootds', 'public');
            $updateData['image_url'] = Storage::url($path);
        }

        $ootd->update($updateData);

        return redirect()->route('community')->with('success', '수정되었습니다.');
    }

    /**
     * OOTD 삭제 처리
     */
    public function destroy(Ootd $ootd)
    {
        abort_unless(Auth::id() === $ootd->member_id, 403);

        if ($ootd->image_url) {
            $path = str_replace('/storage/', '', $ootd->image_url);
            Storage::disk('public')->delete($path);
        }

        $ootd->delete();

        return response()->json(['message' => '삭제되었습니다.']);
    }

    /**
     * 좋아요 토글 처리
     */
    public function toggleLike(Ootd $ootd)
    {
        if (!Auth::check()) {
            return response()->json(['message' => '로그인이 필요합니다.'], 401);
        }

        $member = Auth::user();
        $status = $member->likedOotds()->toggle($ootd->id);
        $likesCount = $ootd->likers()->count();
        $ootd->update(['likes_count' => $likesCount]);

        return response()->json([
            'liked' => count($status['attached']) > 0,
            'likes_count' => $likesCount,
            'message' => count($status['attached']) > 0 ? '좋아요가 반영되었습니다.' : '좋아요가 취소되었습니다.'
        ]);
    }
}
