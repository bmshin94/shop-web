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
}
