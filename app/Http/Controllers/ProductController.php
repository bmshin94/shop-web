<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * 전체 상품 목록
     */
    public function index(Request $request)
    {
        return $this->renderProductList($request, 'all');
    }

    /**
     * 신상품 목록
     */
    public function newArrivals(Request $request)
    {
        return $this->renderProductList($request, 'new');
    }

    /**
     * 베스트 상품 목록
     */
    public function bestProducts(Request $request)
    {
        return $this->renderProductList($request, 'best');
    }

    /**
     * 상품 상세 조회
     */
    public function show($slug)
    {
        $product = Product::with(['category.parent', 'colors', 'images', 'reviews.member', 'sizes', 'relatedProducts'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->where('slug', $slug)
            ->firstOrFail();

        // 1. 직접 설정된 연관 상품 가져오기
        $relatedProducts = $product->relatedProducts->map(function($p) {
            return [
                'name' => $p->name,
                'price' => $p->price,
                'image_url' => $p->image_url ?? 'https://images.unsplash.com/photo-1518310383802-640c2de311b2?q=80&w=800&auto=format&fit=crop',
                'slug' => $p->slug
            ];
        });

        return view('pages.product-detail', compact('product', 'relatedProducts'));
    }

    /**
     * 공통 상품 목록 렌더링 로직
     */
    private function renderProductList(Request $request, $type = 'all')
    {
        $categorySlug = $request->query('category');
        $selectedColors = $request->query('colors', []);
        $selectedPrices = $request->query('price_range', []);
        $sort = $request->query('sort', 'latest');
        
        $query = Product::with(['category', 'colors'])->selling();
        
        $pageTitle = 'ALL PRODUCTS';
        $breadcrumb = ['Home', '전체보기'];

        // 0. 타입별 기본 필터링 (신상품/베스트)
        if ($type === 'new') {
            $query->where('is_new', true);
            $pageTitle = 'NEW ARRIVALS';
            $breadcrumb = ['Home', '신상품'];
        } elseif ($type === 'best') {
            $query->where('is_best', true);
            $pageTitle = 'BEST PRODUCTS';
            $breadcrumb = ['Home', '베스트'];
        }

        // 1. 카테고리 필터
        if ($categorySlug) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                if ($category->level === 1) {
                    $childIds = $category->children()->pluck('id')->toArray();
                    $categoryIds = array_merge([$category->id], $childIds);
                    $query->whereIn('category_id', $categoryIds);
                } else {
                    $query->where('category_id', $category->id);
                }
                $pageTitle = strtoupper($category->name);
                $breadcrumb = $category->parent ? ['Home', $category->parent->name, $category->name] : ['Home', $category->name];
            }
        }

        // 2. 색상 필터
        if (!empty($selectedColors)) {
            $query->whereHas('colors', function($q) use ($selectedColors) {
                $q->whereIn('name', $selectedColors);
            });
        }

        // 3. 가격대 필터
        if (!empty($selectedPrices)) {
            $query->where(function($q) use ($selectedPrices) {
                foreach ($selectedPrices as $range) {
                    if ($range === '~ 5만원') {
                        $q->orWhere(function($sq) {
                            $sq->where(function($ssq) {
                                $ssq->whereNotNull('sale_price')->where('sale_price', '<', 50000);
                            })->orWhere(function($ssq) {
                                $ssq->whereNull('sale_price')->where('price', '<', 50000);
                            });
                        });
                    } elseif ($range === '5만원 ~ 10만원') {
                        $q->orWhere(function($sq) {
                            $sq->where(function($ssq) {
                                $ssq->whereNotNull('sale_price')->whereBetween('sale_price', [50000, 100000]);
                            })->orWhere(function($ssq) {
                                $ssq->whereNull('sale_price')->whereBetween('price', [50000, 100000]);
                            });
                        });
                    } elseif ($range === '10만원 이상') {
                        $q->orWhere(function($sq) {
                            $sq->where(function($ssq) {
                                $ssq->whereNotNull('sale_price')->where('sale_price', '>', 100000);
                            })->orWhere(function($ssq) {
                                $ssq->whereNull('sale_price')->where('price', '>', 100000);
                            });
                        });
                    }
                }
            });
        }

        // 4. 정렬 처리
        switch ($sort) {
            case 'popular':
                $query->orderBy('is_best', 'desc')->latest();
                break;
            case 'price_asc':
                $query->orderByRaw('COALESCE(sale_price, price) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(sale_price, price) DESC');
                break;
            case 'latest':
            default:
                $query->orderBy('is_new', 'desc')->latest();
                break;
        }

        $products = $query->paginate(16)->withQueryString();
        $products->setCollection($this->mapProductsForView($products->getCollection()));

        return view('pages.product-list', compact('products', 'pageTitle', 'breadcrumb'));
    }

    /**
     * DB 모델을 뷰에서 사용하는 배열 구조로 변환
     */
    private function mapProductsForView($collection)
    {
        return $collection->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'description' => $p->brief_description,
                'price' => $p->sale_price ?? $p->price,
                'original_price' => $p->sale_price ? $p->price : null,
                'discount_rate' => $p->discount_rate,
                'image_url' => $p->image_url ?? 'https://images.unsplash.com/photo-1518310383802-640c2de311b2?q=80&w=800&auto=format&fit=crop',
                'is_new' => $p->is_new,
                'is_best' => $p->is_best,
                'status' => $p->status,
                'is_sold_out' => $p->status === '품절',
                'best_rank' => $p->best_rank ?? null,
                'colors' => $p->colors->pluck('hex_code')->toArray(),
                'tags' => [],
                'overlay_tag' => $p->is_new ? 'NEW' : ($p->is_best ? 'BEST' : null)
            ];
        });
    }
}
