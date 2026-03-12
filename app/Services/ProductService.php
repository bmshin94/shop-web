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
        $breadcrumb = ['Home', '전체보기'];

        // 1. 타입별 기본 필터링
        if ($type === 'new') {
            $query->where('is_new', true);
            $pageTitle = 'NEW ARRIVALS';
            $breadcrumb = ['Home', '신상품'];
        } elseif ($type === 'best') {
            $query->where('is_best', true);
            $pageTitle = 'BEST PRODUCTS';
            $breadcrumb = ['Home', '베스트'];
        }

        // 2. 카테고리 필터 및 브레드크럼 설정
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

        // 3. 색상 필터
        if (!empty($selectedColors)) {
            $query->whereHas('colors', function (Builder $q) use ($selectedColors) {
                $q->whereIn('colors.id', $selectedColors);
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
        $product = Product::with(['category.parent', 'colors', 'images', 'reviews.member', 'sizes.group', 'relatedProducts'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->where('slug', $slug)
            ->firstOrFail();

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
}
