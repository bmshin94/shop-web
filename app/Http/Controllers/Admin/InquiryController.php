<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class InquiryController extends Controller
{
    /**
     * 문의 목록 조회 ✨
     */
    public function index(Request $request): View
    {
        $query = Inquiry::with('member')->latest();

        // 필터링: 답변 상태
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 검색: 제목 또는 회원명
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('member', function($mq) use ($search) {
                      $mq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $inquiries = $query->paginate(15)->withQueryString();

        return view('admin.inquiries.index', compact('inquiries'));
    }

    /**
     * 문의 상세 및 답변 페이지 ✨
     */
    public function show(Inquiry $inquiry): View
    {
        return view('admin.inquiries.show', compact('inquiry'));
    }

    /**
     * 답변 등록 및 수정 ✨
     */
    public function updateAnswer(Request $request, Inquiry $inquiry): JsonResponse
    {
        $request->validate([
            'answer' => 'required|string',
        ]);

        try {
            $inquiry->update([
                'answer' => $request->answer,
                'status' => '답변완료',
                'answered_at' => now(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => '답변이 성공적으로 등록되었습니다.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => '답변 등록 중 오류가 발생했습니다.'
            ], 500);
        }
    }

    /**
     * 문의 삭제 ✨
     */
    public function destroy(Inquiry $inquiry): JsonResponse
    {
        try {
            $inquiry->delete();
            return response()->json([
                'status' => 'success',
                'message' => '문의가 삭제되었습니다.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => '삭제 중 오류가 발생했습니다.'
            ], 500);
        }
    }
}
