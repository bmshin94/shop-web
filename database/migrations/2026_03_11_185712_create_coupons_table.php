<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('쿠폰명');
            $table->string('code')->unique()->nullable()->comment('쿠폰코드');
            $table->enum('type', ['discount', 'shipping'])->default('discount')->comment('쿠폰유형');
            $table->enum('discount_type', ['percent', 'fixed'])->default('fixed')->comment('할인방식');
            $table->unsignedInteger('discount_value')->default(0)->comment('할인값');
            $table->unsignedInteger('min_order_amount')->default(0)->comment('최소주문금액');
            $table->unsignedInteger('max_discount_amount')->nullable()->comment('최대할인금액');
            $table->text('description')->nullable()->comment('쿠폰설명');
            $table->boolean('is_active')->default(true)->comment('활성화여부');
            $table->timestamp('starts_at')->nullable()->comment('사용시작일');
            $table->timestamp('ends_at')->nullable()->comment('사용종료일');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
