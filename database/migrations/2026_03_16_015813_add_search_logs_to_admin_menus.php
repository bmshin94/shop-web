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
        // 1. 마지막 정렬 순서 가져오기 
        $lastSortOrder = DB::table('admin_menus')->max('sort_order') ?? 0;

        // 2. 검색 로그 관리 메뉴 추가 
        DB::table('admin_menus')->insert([
            'group_name' => '통계 및 분석',
            'name' => '검색 로그 관리',
            'route' => 'admin.search-logs.index',
            'icon' => 'monitoring',
            'permission_key' => 'search_logs',
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
        DB::table('admin_menus')->where('route', 'admin.search-logs.index')->delete();
    }
};
