<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use App\Models\Exhibition;
use App\Models\Magazine;
use App\Models\Ootd;
use Illuminate\Support\Collection;

class HomeService
{
    /**
     * 메인 페이지에 필요한 모든 데이터를 한꺼번에 가져옵니다. 
     * 
     * @return array
     */
    public function getHomeData(): array
    {
        return [
            // 1. 메인 히어로 배너용 상품 (최신 판매중 상품 10개)
            'heroProducts' => $this->getHeroProducts(),
            
            // 2. 퀵 메뉴용 최상위 카테고리 (4개)
            'topCategories' => $this->getTopCategories(),
            
            // 3. 에디터 픽 (Best 상품 4개)
            'editorsPicks' => $this->getEditorsPicks(),
            
            // 4. 실시간 인기 급상승 (조회수 기준 10개)
            'trendingProducts' => $this->getTrendingProducts(),
            
            // 5. 최신 매거진 (3개) 
            'recentMagazines' => Magazine::latest()->take(3)->get(),
            
            // 6. 최신 OOTD (8개 - 프리뷰용) 
            'recentOotds' => Ootd::with('member')->latest()->take(8)->get(),

            // 7. 메인 히어로 기획전 (최신 5개)
            'heroExhibitions' => Exhibition::active()->latest()->take(5)->get(),
        ];
    }

    /**
     * 상단 히어로 배너용 상품 조회
     */
    private function getHeroProducts(): Collection
    {
        // 1. 관리자가 명시적으로 선택한 히어로 상품 우선 조회
        $heroProducts = Product::with(['images', 'category'])
            ->selling()
            ->where('is_hero', true)
            ->latest()
            ->take(10)
            ->get();

        // 2. 만약 히어로 상품이 10개 미만이라면 최신 상품으로 부족한 만큼 채우기
        if ($heroProducts->count() < 10) {
            $excludeIds = $heroProducts->pluck('id')->toArray();
            $neededCount = 10 - $heroProducts->count();

            $extraProducts = Product::with(['images', 'category'])
                ->selling()
                ->whereNotIn('id', $excludeIds)
                ->latest()
                ->take($neededCount)
                ->get();

            $heroProducts = $heroProducts->concat($extraProducts);
        }

        return $heroProducts;
    }

    /**
     * 퀵 메뉴용 최상위 카테고리 조회
     */
    private function getTopCategories(): Collection
    {
        return Category::where('level', 1)
            ->orderBy('sort_order')
            ->take(4)
            ->get();
    }

    /**
     * 에디터 추천 상품 (Best 상품) 조회
     */
    private function getEditorsPicks(): Collection
    {
        return Product::with(['images', 'category', 'colors'])
            ->selling()
            ->where('is_best', true)
            ->latest()
            ->take(4)
            ->get();
    }

    /**
     * 실시간 인기 급상승 상품 조회 (조회수 기준)
     */
    private function getTrendingProducts(): Collection
    {
        // 실제로는 view_count 같은 컬럼이 있으면 좋겠지만, 
        // 우선은 최신 상품 중 랜덤하게 10개 보여주는 걸로 시작할게! 
        return Product::with(['images', 'category', 'colors'])
            ->selling()
            ->inRandomOrder()
            ->take(10)
            ->get();
    }
}
