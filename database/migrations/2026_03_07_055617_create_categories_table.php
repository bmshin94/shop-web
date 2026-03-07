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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('카테고리명');
            $table->string('slug')->unique()->comment('카테고리 슬러그');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('상위 카테고리 ID');
            $table->integer('sort_order')->default(0)->comment('정렬 순서');
            $table->boolean('is_active')->default(true)->comment('활성화 여부');
            $table->timestamps();

            // 외래키 설정 (자기 참조)
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
