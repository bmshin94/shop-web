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
}
