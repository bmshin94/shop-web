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
        Schema::table('products', function (Blueprint $table) {
            $table->string('shipping_type')->default('기본')->after('status')->comment('배송비 구분: 기본, 무료, 고정');
            $table->integer('shipping_fee')->default(0)->after('shipping_type')->comment('고정 배송비 금액');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['shipping_type', 'shipping_fee']);
        });
    }
};
