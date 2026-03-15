<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class ProductService
{
    /**
     * 상품 목록 필터링 및 페이징 처리
     * 
     * @param Request $request
     * @param string $type ('all', 'new', 'best')
     * @return array
     */
    public function getFilteredProducts(Request $request, string $type = 'all'): array
    {
        $categorySlug = $request->query('category');
        $selectedColors = $request->query('colors', []);
        $selectedPrices = $request->query('price_range', []);
        $sort = $request->query('sort', 'latest');
        
        $query = Product::with(['category', 'colors'])->selling();
        
        $pageTitle = 'ALL PRODUCTS';
        $breadcrumb = [
            ['name' => 'Home', 'url' => route('home')],
            ['name' => '전체보기', 'url' => route('product-list')]
        ];

        // 1. 타입별 기본 필터링
        if ($type === 'new') {
            $query->where('is_new', true);
            $pageTitle = 'NEW ARRIVALS';
            $breadcrumb = [
                ['name' => 'Home', 'url' => route('home')],
                ['name' => '신상품', 'url' => route('products.new')]
            ];
        } elseif ($type === 'best') {
            $query->where('is_best', true);
            $pageTitle = 'BEST PRODUCTS';
            $breadcrumb = [
                ['name' => 'Home', 'url' => route('home')],
                ['name' => '베스트', 'url' => route('products.best')]
            ];
        }

        // 2. 카테고리 필터 및 브레드크럼 설정
        if ($categorySlug) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                if ($category->level === 1) {
                    $childIds = $category->children()->pluck('id')->toArray();
                    $categoryIds = array_merge([$category->id], $childIds);
                    $query->whereIn('category_id', $categoryIds);
                    
                    $breadcrumb = [
                        ['name' => 'Home', 'url' => route('home')],
                        ['name' => $category->name, 'url' => route('product-list', ['category' => $category->slug])]
                    ];
                } else {
                    $query->where('category_id', $category->id);
                    $parent = $category->parent;
                    $breadcrumb = [
                        ['name' => 'Home', 'url' => route('home')],
                        ['name' => $parent->name, 'url' => route('product-list', ['category' => $parent->slug])],
                        ['name' => $category->name, 'url' => route('product-list', ['category' => $category->slug])]
                    ];
                }
                $pageTitle = strtoupper($category->name);
            }
        }

        // 3. 색상 필터  (ID 대신 이름으로 검색!)
        if (!empty($selectedColors)) {
            $query->whereHas('colors', function (Builder $q) use ($selectedColors) {
                $q->whereIn('colors.name', $selectedColors);
            });
        }

        // 4. 가격 필터
        if (!empty($selectedPrices)) {
            $query->where(function (Builder $q) use ($selectedPrices) {
                foreach ($selectedPrices as $range) {
                    if (str_contains($range, '~') && !str_contains($range, '+')) {
                        $parts = explode('~', $range);
                        $min = trim($parts[0]);
                        $max = trim($parts[1]);

                        $minVal = $min === '' ? 0 : (int)filter_var($min, FILTER_SANITIZE_NUMBER_INT) * 10000;
                        $maxVal = (int)filter_var($max, FILTER_SANITIZE_NUMBER_INT) * 10000;
                        
                        $q->orWhereBetween('price', [$minVal, $maxVal]);
                    } elseif (str_contains($range, '이상')) {
                        $val = (int)filter_var($range, FILTER_SANITIZE_NUMBER_INT) * 10000;
                        $q->orWhere('price', '>=', $val);
                    }
                }
            });
        }

        // 5. 정렬
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->withCount('reviews')->orderBy('reviews_count', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        return [
            'products' => $products,
            'pageTitle' => $pageTitle,
            'breadcrumb' => $breadcrumb,
            'categorySlug' => $categorySlug,
            'selectedColors' => $selectedColors,
            'selectedPrices' => $selectedPrices,
            'sort' => $sort,
        ];
    }

    /**
     * 상품 상세 정보 조회
     * 
     * @param string $slug
     * @return array
     */
    public function getProductDetail(string $slug): array
    {
        $product = Product::with(['category.parent', 'colors', 'images', 'reviews.member', 'sizes.group', 'relatedProducts', 'inquiries.member'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->where('slug', $slug)
            ->firstOrFail();

        // 최근 본 상품 기록! 
        if (auth()->check()) {
            // 1. 로그인 회원: DB 저장/갱신
            \App\Models\RecentView::updateOrCreate(
                ['member_id' => auth()->id(), 'product_id' => $product->id],
                ['viewed_at' => now()]
            );
        } else {
            // 2. 비로그인 게스트: 쿠키 저장 
            $recentCookie = request()->cookie('recent_views', '[]');
            $viewedIds = json_decode($recentCookie, true) ?: [];
            
            // 현재 상품 ID를 배열 맨 앞으로 보내고 중복 제거! 
            array_unshift($viewedIds, $product->id);
            $viewedIds = array_unique($viewedIds);
            
            // 최대 20개까지만 유지! 
            $viewedIds = array_slice($viewedIds, 0, 20);
            
            // 쿠키에 30일 동안 저장 예약! ️
            \Illuminate\Support\Facades\Cookie::queue('recent_views', json_encode($viewedIds), 60 * 24 * 30);
        }

        // 연관 상품 가공
        $relatedProducts = $product->relatedProducts->map(function($p) {
            return [
                'name' => $p->name,
                'price' => $p->price,
                'image_url' => $p->image_url ?? 'https://images.unsplash.com/photo-1518310383802-640c2de311b2?q=80&w=800&auto=format&fit=crop',
                'slug' => $p->slug
            ];
        });

        return [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ];
    }

    /**
     * 통합 검색 처리 
     */
    public function searchProducts(Request $request): array
    {
        $keyword = $request->query('q');
        $categorySlug = $request->query('category');
        $selectedColors = $request->query('colors', []);
        $selectedPrices = $request->query('price_range', []);
        $sort = $request->query('sort', 'latest');

        $query = Product::with(['category', 'images', 'colors'])->selling();

        // 1. 검색어 기본 필터링 
        if ($keyword) {
            $this->logSearchKeyword($keyword);
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('brief_description', 'like', "%{$keyword}%")
                  ->orWhereHas('category', function ($cq) use ($keyword) {
                      $cq->where('name', 'like', "%{$keyword}%");
                  });
            });
        }

        // 2. 카테고리 필터 
        if ($categorySlug) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                if ($category->level === 1) {
                    $categoryIds = array_merge([$category->id], $category->children()->pluck('id')->toArray());
                    $query->whereIn('category_id', $categoryIds);
                } else {
                    $query->where('category_id', $category->id);
                }
            }
        }

        // 3. 색상 필터 
        if (!empty($selectedColors)) {
            $query->whereHas('colors', function ($q) use ($selectedColors) {
                $q->whereIn('colors.name', $selectedColors);
            });
        }

        // 4. 가격 필터 
        if (!empty($selectedPrices)) {
            $query->where(function ($q) use ($selectedPrices) {
                foreach ($selectedPrices as $range) {
                    if (str_contains($range, '~') && !str_contains($range, '+')) {
                        $parts = explode('~', $range);
                        $minVal = (int)filter_var($parts[0], FILTER_SANITIZE_NUMBER_INT) * 10000;
                        $maxVal = (int)filter_var($parts[1], FILTER_SANITIZE_NUMBER_INT) * 10000;
                        $q->orWhereBetween('price', [$minVal, $maxVal]);
                    } elseif (str_contains($range, '이상')) {
                        $val = (int)filter_var($range, FILTER_SANITIZE_NUMBER_INT) * 10000;
                        $q->orWhere('price', '>=', $val);
                    }
                }
            });
        }

        // 5. 정렬 처리
        switch ($sort) {
            case 'price_low': $query->orderBy('price', 'asc'); break;
            case 'price_high': $query->orderBy('price', 'desc'); break;
            case 'popular': $query->withCount('reviews')->orderBy('reviews_count', 'desc'); break;
            default: $query->latest(); break;
        }

        $products = $query->paginate(12)->withQueryString();

        return [
            'products' => $products,
            'pageTitle' => "SEARCH RESULT: \"{$keyword}\"",
            'keyword' => $keyword,
            'categorySlug' => $categorySlug,
            'selectedColors' => $selectedColors,
            'selectedPrices' => $selectedPrices,
            'sort' => $sort,
            'breadcrumb' => [
                ['name' => 'Home', 'url' => route('home')],
                ['name' => 'Search', 'url' => '#'],
            ]
        ];
    }

    /**
     * 실시간 검색 제안 (Autocomplete) 
     */
    public function getSearchSuggestions(string $keyword)
    {
        if (empty($keyword)) return collect();

        return Product::selling()
            ->where('name', 'like', "%{$keyword}%")
            ->latest()
            ->take(5)
            ->get(['id', 'name', 'slug', 'sale_price', 'price']);
    }

    /**
     * 검색어 로그 저장 
     */
    private function logSearchKeyword(string $keyword)
    {
        if (empty($keyword)) return;

        \App\Models\SearchLog::create([
            'keyword' => trim($keyword),
            'member_id' => auth()->id(),
            'ip_address' => request()->ip(),
        ]);
    }

    /**
     * 인기 검색어 조회 
     */
    public function getPopularKeywords(int $limit = 5)
    {
        return \App\Models\SearchLog::select('keyword', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('keyword')
            ->orderByDesc('count')
            ->take($limit)
            ->get();
    }
}
