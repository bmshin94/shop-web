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
                'wishlistCount' => auth()->user()->wishlists()->count()
            ]);
        } else {
            Wishlist::create([
                'member_id' => $memberId,
                'product_id' => $product->id
            ]);
            return response()->json([
                'status' => 'added',
                'message' => '위시리스트에 추가되었습니다.',
                'wishlistCount' => auth()->user()->wishlists()->count()
            ]);
        }
    }

    /**
     * 선택한 찜 항목 삭제
     */
    public function destroySelected(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return response()->json([
                'status' => 'error',
                'message' => '삭제할 항목을 선택해주세요.'
            ], 422);
        }

        Wishlist::where('member_id', auth()->id())
            ->whereIn('id', $ids)
            ->delete();

        return response()->json([
            'status' => 'success',
            'message' => '선택한 항목이 삭제되었습니다.',
            'wishlistCount' => auth()->user()->wishlists()->count()
        ]);
    }

    /**
     * 찜 목록 전체 삭제 ✨
     */
    public function clearAll()
    {
        $member = auth()->user();
        
        Wishlist::where('member_id', $member->id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => '찜 목록이 모두 삭제되었습니다.',
            'wishlistCount' => 0
        ]);
    }
}
