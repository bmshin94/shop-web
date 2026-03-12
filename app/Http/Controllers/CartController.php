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
        $carts = auth()->user()->carts()->with('product')->latest()->get();
        return view('pages.cart', compact('carts'));
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
