<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. '상품 관리' 그룹의 마지막 정렬 순서 근처로 잡기 
        $lastSortOrder = DB::table('admin_menus')
            ->where('group_name', '상품 관리')
            ->max('sort_order') ?? 10;

        // 2. 고객 리뷰 관리 메뉴 추가 ️
        DB::table('admin_menus')->insert([
            'group_name' => '상품 관리',
            'name' => '고객 리뷰 관리',
            'route' => 'admin.reviews.index',
            'icon' => 'star',
            'permission_key' => 'reviews_manage',
            'sort_order' => $lastSortOrder + 1,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('admin_menus')->where('route', 'admin.reviews.index')->delete();
    }
};
