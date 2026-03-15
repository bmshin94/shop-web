<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 장바구니 목록
     */
    public function index()
    {
        $carts = auth()->user()->carts()->with(['product.images', 'product.colors', 'product.sizes'])->latest()->get();
        
        $totalOriginalPrice = 0;
        $totalSalePrice = 0;
        $totalDiscount = 0;

        foreach ($carts as $cart) {
            $product = $cart->product;
            $totalOriginalPrice += $product->price * $cart->quantity;
            $totalSalePrice += ($product->sale_price ?? $product->price) * $cart->quantity;
        }

        $totalDiscount = $totalOriginalPrice - $totalSalePrice;
        
        // 배송비 계산 (5만원 이상 무료배송 정책)
        $shippingFee = ($totalSalePrice >= 50000 || $carts->isEmpty()) ? 0 : 3000;
        $finalTotal = $totalSalePrice + $shippingFee;

        return view('pages.cart', compact(
            'carts', 
            'totalOriginalPrice', 
            'totalSalePrice', 
            'totalDiscount', 
            'shippingFee', 
            'finalTotal'
        ));
    }

    /**
     * 장바구니 정보 업데이트 (수량 및 옵션 변경 ✨)
     */
    public function update(Request $request, Cart $cart)
    {
        // 본인 장바구니인지 확인
        if ($cart->member_id !== auth()->id()) {
            return response()->json(['message' => '권한이 없습니다.'], 403);
        }

        $request->validate([
            'quantity' => 'nullable|integer|min:1',
            'color' => 'nullable|string',
            'size' => 'nullable|string',
        ]);

        $data = [];
        if ($request->has('quantity')) $data['quantity'] = $request->quantity;
        if ($request->has('color')) $data['color'] = $request->color;
        if ($request->has('size')) $data['size'] = $request->size;

        $cart->update($data);

        return response()->json(['status' => 'success', 'message' => '정보가 변경되었습니다.']);
    }

    /**
     * 장바구니 개별 삭제
     */
    public function destroy(Cart $cart)
    {
        if ($cart->member_id !== auth()->id()) {
            return response()->json(['message' => '권한이 없습니다.'], 403);
        }

        $cart->delete();
        return response()->json(['status' => 'success', 'message' => '상품이 삭제되었습니다.']);
    }

    /**
     * 장바구니 선택 삭제
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;
        if (empty($ids)) {
            return response()->json(['message' => '삭제할 상품을 선택해 주세요.'], 400);
        }

        auth()->user()->carts()->whereIn('id', $ids)->delete();
        return response()->json(['status' => 'success', 'message' => '선택한 상품이 삭제되었습니다.']);
    }

    /**
     * 장바구니 담기
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'color' => 'nullable|string',
            'size' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'force' => 'nullable|boolean'
        ]);

        $memberId = auth()->id();
        $productId = $request->product_id;
        $color = $request->color;
        $size = $request->size;
        $quantity = $request->quantity;

        // 동일한 상품/옵션이 있는지 확인
        $cart = Cart::where('member_id', $memberId)
            ->where('product_id', $productId)
            ->where('color', $color)
            ->where('size', $size)
            ->first();

        if ($cart && !$request->force) {
            return response()->json([
                'status' => 'duplicate',
                'message' => '이미 장바구니에 동일한 상품이 있습니다. 수량을 추가하시겠습니까?'
            ]);
        }

        if ($cart) {
            $cart->increment('quantity', $quantity);
        } else {
            Cart::create([
                'member_id' => $memberId,
                'product_id' => $productId,
                'color' => $color,
                'size' => $size,
                'quantity' => $quantity,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => '장바구니에 담았습니다.',
            'cart_count' => auth()->user()->carts()->count()
        ]);
    }
}
