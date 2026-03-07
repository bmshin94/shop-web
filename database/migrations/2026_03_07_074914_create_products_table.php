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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade')->comment('카테고리 ID');
            $table->string('name')->comment('상품명');
            $table->string('slug')->unique()->comment('상품 슬러그');
            $table->text('description')->nullable()->comment('상품 설명');
            $table->integer('price')->comment('판매가(정가)');
            $table->integer('sale_price')->nullable()->comment('할인 판매가');
            $table->integer('stock_quantity')->default(0)->comment('재고 수량');
            $table->string('status')->default('판매중')->comment('상태(판매중, 품절, 숨김)');
            $table->string('image_url')->nullable()->comment('대표 이미지 URL');
            $table->boolean('is_new')->default(false)->comment('신상품 여부');
            $table->boolean('is_best')->default(false)->comment('베스트 상품 여부');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
