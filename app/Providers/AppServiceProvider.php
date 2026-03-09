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

        // 모든 뷰에서 카테고리 데이터를 사용할 수 있도록 공유합니다. 
        View::composer('*', function ($view) {
            $categories = Category::active()
                ->onlyParents()
                ->with(['children' => function ($query) {
                    $query->active();
                }])
                ->orderBy('sort_order')
                ->get();
            
            $view->with('globalCategories', $categories);
        });
    }
}
