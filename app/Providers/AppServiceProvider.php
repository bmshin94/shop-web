<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        // 모든 뷰에서 카테고리 데이터 및 사이트 설정 데이터를 사용할 수 있도록 공유합니다. 
        View::composer('*', function ($view) {
            // 1. 카테고리 로드
            $categories = Category::active()
                ->onlyParents()
                ->with(['children' => function ($query) {
                    $query->active();
                }])
                ->orderBy('sort_order')
                ->get();
            
            // 2. 사이트 설정 로드 (캐싱을 적용하면 성능에 좋지만, 일단 모델에서 바로 호출)
            $siteSettings = \App\Models\SiteSetting::pluck('setting_value', 'setting_key')->toArray();
            
            $view->with('globalCategories', $categories)
                 ->with('siteSettings', $siteSettings);
        });
    }
}
