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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('imp_uid')->nullable()->unique()->after('payment_method')->comment('포트원 결제 고유번호');
            $table->string('merchant_uid')->nullable()->unique()->after('imp_uid')->comment('가맹점 고유 주문번호');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['imp_uid', 'merchant_uid']);
        });
    }
};
