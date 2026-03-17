<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * 고객 리뷰 목록 페이지 
     */
    public function index(Request $request)
    {
        $query = Review::with(['member', 'product'])->latest();

        // 1. 검색 필터 (상품명, 리뷰 내용 등) 
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhereHas('product', function($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('member', function($mq) use ($search) {
                      $mq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // 2. 평점 필터 
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->paginate(15)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * 리뷰 상세 조회 (AJAX/모달용) 
     */
    public function show(Review $review)
    {
        $review->load(['member', 'product']);
        return response()->json($review);
    }

    /**
     * 리뷰 삭제 처리 
     */
    public function destroy(Review $review)
    {
        // 서버에 저장된 이미지들도 지워주면 좋겠지? 
        if ($review->images) {
            foreach ($review->images as $imageUrl) {
                $path = str_replace('/storage/', '', $imageUrl);
                \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
            }
        }

        $review->delete();

        return back()->with('success', '리뷰가 정상적으로 삭제되었습니다. ');
    }
}
