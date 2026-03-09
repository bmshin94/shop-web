<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admin_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('admin_menus')->onDelete('cascade');
            $table->string('group_name')->nullable()->comment('메뉴 그룹 (예: 쇼핑몰 관리)');
            $table->string('name')->comment('메뉴 명칭');
            $table->string('icon')->nullable()->comment('Material Icon 이름');
            $table->string('route')->nullable()->comment('라우트명');
            $table->string('permission_key')->nullable()->comment('권한 체크 키');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_menus');
    }
};
