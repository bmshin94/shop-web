<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    /**
     * ProductController 생성자
     * 
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * 전체 상품 목록
     */
    public function index(Request $request)
    {
        $data = $this->productService->getFilteredProducts($request, 'all');
        return view('pages.product-list', $data);
    }

    /**
     * 신상품 목록
     */
    public function newArrivals(Request $request)
    {
        $data = $this->productService->getFilteredProducts($request, 'new');
        return view('pages.product-list', $data);
    }

    /**
     * 베스트 상품 목록
     */
    public function bestProducts(Request $request)
    {
        $data = $this->productService->getFilteredProducts($request, 'best');
        return view('pages.product-list', $data);
    }

    /**
     * 상품 상세 조회
     */
    public function show($slug)
    {
        $data = $this->productService->getProductDetail($slug);
        return view('pages.product-detail', $data);
    }

    /**
     * 상품 통합 검색 ✨🔍
     */
    public function search(Request $request)
    {
        $data = $this->productService->searchProducts($request);
        return view('pages.product-list', $data);
    }

    /**
     * 실시간 검색 제안 (AJAX Autocomplete) ✨🚀
     */
    public function autocomplete(Request $request)
    {
        $keyword = $request->query('q', '');
        $suggestions = $this->productService->getSearchSuggestions($keyword);
        
        return response()->json($suggestions);
    }

    /**
     * 퀵 뷰를 위한 단수 상품 옵션 정보 조회
     */
    public function getQuickViewData($id)
    {
        try {
            $product = \App\Models\Product::with(['colors', 'sizes', 'images'])->findOrFail($id);
            
            return response()->json([
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'sale_price' => $product->sale_price,
                'image_url' => $product->images->first()?->image_url,
                'colors' => $product->colors,
                'sizes' => $product->sizes,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => '상품 정보를 불러올 수 없습니다.'], 404);
        }
    }

    /**
     * 멀티 퀵 뷰를 위한 여러 상품의 옵션 정보 조회
     */
    public function getBulkQuickViewData(Request $request)
    {
        try {
            $ids = explode(',', $request->query('ids', ''));
            if (empty($ids)) {
                return response()->json(['error' => '선택된 상품이 없습니다.'], 400);
            }

            // 전달받은 모든 상품 ID에 대해 상세 정보와 옵션을 한꺼번에 로드
            $products = \App\Models\Product::with(['colors', 'sizes', 'images'])
                ->whereIn('id', $ids)
                ->get()
                ->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'sale_price' => $product->sale_price,
                        'image_url' => $product->images->first()?->image_url,
                        'colors' => $product->colors,
                        'sizes' => $product->sizes,
                    ];
                });
            
            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json(['error' => '상품 정보를 불러올 수 없습니다.'], 500);
        }
    }
}
