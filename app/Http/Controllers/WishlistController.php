<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 찜하기 토글
     */
    public function toggle(Product $product)
    {
        $memberId = auth()->id();
        
        $wishlist = Wishlist::where('member_id', $memberId)
            ->where('product_id', $product->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json([
                'status' => 'removed',
                'message' => '위시리스트에서 제거되었습니다.',
                'count' => auth()->user()->wishlists()->count()
            ]);
        } else {
            Wishlist::create([
                'member_id' => $memberId,
                'product_id' => $product->id
            ]);
            return response()->json([
                'status' => 'added',
                'message' => '위시리스트에 추가되었습니다.',
                'count' => auth()->user()->wishlists()->count()
            ]);
        }
    }
}
