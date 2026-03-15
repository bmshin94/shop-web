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
     * 메인 페이지에 필요한 모든 데이터를 한꺼번에 가져옵니다. ✨💖
     * 
     * @return array
     */
    public function getHomeData(): array
    {
        return [
            // 1. 메인 히어로 배너용 기획전 (최신순 3개)
            'heroExhibitions' => $this->getHeroExhibitions(),
            
            // 2. 퀵 메뉴용 최상위 카테고리 (4개)
            'topCategories' => $this->getTopCategories(),
            
            // 3. 에디터 픽 (Best 상품 4개)
            'editorsPicks' => $this->getEditorsPicks(),
            
            // 4. 실시간 인기 급상승 (조회수 기준 10개)
            'trendingProducts' => $this->getTrendingProducts(),
            
            // 5. 최신 매거진 (3개) ✨
            'recentMagazines' => Magazine::latest()->take(3)->get(),
            
            // 6. 최신 OOTD (8개 - 프리뷰용) 📸
            'recentOotds' => Ootd::with('member')->latest()->take(8)->get(),
        ];
    }

    /**
     * 상단 히어로 배너용 기획전 조회
     */
    private function getHeroExhibitions(): Collection
    {
        return Exhibition::active()
            ->latest()
            ->take(3)
            ->get();
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
        return Product::with(['images', 'category'])
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
        // 우선은 최신 상품 중 랜덤하게 10개 보여주는 걸로 시작할게! ✨
        return Product::with(['images', 'category'])
            ->selling()
            ->inRandomOrder()
            ->take(10)
            ->get();
    }
}
