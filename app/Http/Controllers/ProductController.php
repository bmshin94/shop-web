<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function newArrivals()
    {
        $products = $this->getDummyProducts('new');
        $pageTitle = 'NEW ARRIVALS';
        $breadcrumb = ['Home', '스포츠웨어', '신상품'];
        return view('pages.product-list', compact('products', 'pageTitle', 'breadcrumb'));
    }

    public function bestProducts()
    {
        $products = $this->getDummyProducts('best');
        $pageTitle = 'BEST PRODUCTS';
        $breadcrumb = ['Home', '스포츠웨어', '베스트'];
        return view('pages.product-list', compact('products', 'pageTitle', 'breadcrumb'));
    }

    public function index()
    {
        $products = $this->getDummyProducts('all');
        $pageTitle = 'ALL PRODUCTS';
        $breadcrumb = ['Home', '스포츠웨어', '전체보기'];
        return view('pages.product-list', compact('products', 'pageTitle', 'breadcrumb'));
    }

    private function getDummyProducts($type = 'all')
    {
        $allProducts = [
            [
                'id' => 1,
                'name' => '위켄드 워리어 셋업',
                'description' => '탑 & 레깅스 투피스',
                'price' => 148500,
                'original_price' => 165000,
                'discount_rate' => 10,
                'image_url' => 'https://images.unsplash.com/photo-1518310383802-640c2de311b2?q=80&w=800&auto=format&fit=crop',
                'is_new' => true,
                'is_best' => false,
                'colors' => ['bg-black', 'bg-white', 'bg-primary'],
                'tags' => ['무료배송', '번들할인'],
                'overlay_tag' => 'OOTD SET'
            ],
            [
                'id' => 2,
                'name' => '모닝 런 글로우 자켓',
                'description' => '초경량 퍼포먼스 윈드브레이커',
                'price' => 98000,
                'original_price' => null,
                'discount_rate' => null,
                'image_url' => 'https://images.unsplash.com/photo-1552674605-db6ffd4facb5?q=80&w=800&auto=format&fit=crop',
                'is_new' => false,
                'is_best' => true,
                'best_rank' => 1,
                'colors' => [],
                'tags' => ['쿠폰적용가'],
                'overlay_tag' => null
            ],
            [
                'id' => 3,
                'name' => '에어리 하이웨이스트 레깅스',
                'description' => '버터같은 부드러움, 강력한 서포트',
                'price' => 59000,
                'original_price' => null,
                'discount_rate' => null,
                'image_url' => 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?q=80&w=800&auto=format&fit=crop',
                'is_new' => false,
                'is_best' => true,
                'best_rank' => 2,
                'colors' => ['bg-stone-400', 'bg-blue-900'],
                'tags' => [],
                'overlay_tag' => null
            ],
            [
                'id' => 4,
                'name' => '크로스백 서포트 브라탑',
                'description' => '미디엄 임팩트 서포트',
                'price' => 45000,
                'original_price' => null,
                'discount_rate' => null,
                'image_url' => 'https://images.unsplash.com/photo-1551806235-a05bc1ecb003?q=80&w=800&auto=format&fit=crop',
                'is_new' => true,
                'is_best' => false,
                'colors' => [],
                'tags' => [],
                'overlay_tag' => 'NEW'
            ]
        ];

        if ($type === 'new') return array_filter($allProducts, fn($p) => $p['is_new']);
        if ($type === 'best') return array_filter($allProducts, fn($p) => $p['is_best']);
        return $allProducts;
    }
}
