<?php

namespace App\Providers;

use App\Models\AdminMenu;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AdminMenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // 관리자 레이아웃에 메뉴 데이터를 전역으로 전달
        View::composer('layouts.admin', function ($view) {
            $menus = AdminMenu::active()
                ->whereNull('parent_id')
                ->orderBy('sort_order')
                ->get();
            
            $view->with('sidebarMenus', $menus);
        });
    }
}
