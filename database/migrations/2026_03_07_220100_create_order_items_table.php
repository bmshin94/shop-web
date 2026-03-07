<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete()->comment('주문 ID');
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete()->comment('상품 ID');
            $table->string('product_name')->comment('주문 당시 상품명');
            $table->string('option_summary')->nullable()->comment('옵션 요약');
            $table->unsignedInteger('unit_price')->comment('주문 단가');
            $table->unsignedInteger('quantity')->default(1)->comment('수량');
            $table->unsignedInteger('line_total')->comment('라인 합계');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
