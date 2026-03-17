<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 리뷰 작성 페이지
     */
    public function create(Request $request)
    {
        $productId = $request->query('product_id');
        $product = Product::findOrFail($productId);
        $memberId = auth()->id();

        // 1. 해당 상품을 구매했는지, 그리고 상태가 '배송완료' 또는 '구매확정'인지 확인합니다. 
        $hasPurchased = \App\Models\OrderItem::where('product_id', $productId)
            ->whereHas('order', function ($query) use ($memberId) {
                $query->where('member_id', $memberId)
                    ->whereIn('order_status', ['배송완료', '구매확정']);
            })->exists();

        if (!$hasPurchased) {
            return redirect()->route('product-detail', $product->slug)
                ->with('error', '배송완료 또는 구매확정 완료된 상품만 리뷰를 작성할 수 있습니다. ');
        }

        return view('pages.review-write', compact('product'));
    }

    /**
     * 리뷰 저장
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $memberId = auth()->id();
        $productId = $request->product_id;

        // 2. 저장 시에도 한 번 더 꼼꼼하게 구매 여부와 상태를 체크합니다! 
        $hasPurchased = \App\Models\OrderItem::where('product_id', $productId)
            ->whereHas('order', function ($query) use ($memberId) {
                $query->where('member_id', $memberId)
                    ->whereIn('order_status', ['배송완료', '구매확정']);
            })->exists();

        if (!$hasPurchased) {
            return response()->json([
                'success' => false,
                'message' => '배송완료 또는 구매확정 완료된 상품만 리뷰를 남길 수 있어요!'
            ], 403);
        }

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('reviews', 'public');
                $imagePaths[] = Storage::url($path);
            }
        }

        $review = Review::create([
            'product_id' => $request->product_id,
            'member_id' => auth()->id(),
            'rating' => $request->rating,
            'title' => $request->title,
            'content' => $request->content,
            'images' => $imagePaths,
        ]);

        return response()->json([
            'success' => true,
            'message' => '리뷰가 소중하게 등록되었습니다! 감사해요!',
            'redirect' => route('product-detail', ['slug' => $review->product->slug])
        ]);
    }

    /**
     * 리뷰 데이터 조회 (수정용)
     */
    public function edit(Review $review)
    {
        // 본인 리뷰인지 확인 🛡️
        if ($review->member_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => '권한이 없습니다.'], 403);
        }

        return response()->json([
            'success' => true,
            'review' => $review,
            'product_name' => $review->product->name,
            'product_image' => $review->product->image_url,
        ]);
    }

    /**
     * 리뷰 수정 저장
     */
    public function update(Request $request, Review $review)
    {
        // 본인 리뷰인지 확인 🛡️
        if ($review->member_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => '권한이 없습니다.'], 403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $currentImages = $review->images ?? [];

        // 1. 기존 이미지 삭제 처리 🗑️
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $deletePath) {
                $storagePath = str_replace('/storage/', '', $deletePath);
                Storage::disk('public')->delete($storagePath);
                $currentImages = array_filter($currentImages, fn($img) => $img !== $deletePath);
            }
        }

        // 2. 새 이미지 업로드 📸
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('reviews', 'public');
                $currentImages[] = Storage::url($path);
            }
        }

        $review->update([
            'rating' => $request->rating,
            'title' => $request->title,
            'content' => $request->content,
            'images' => array_values($currentImages),
        ]);

        return response()->json([
            'success' => true,
            'message' => '리뷰가 성공적으로 수정되었습니다!'
        ]);
    }
}
