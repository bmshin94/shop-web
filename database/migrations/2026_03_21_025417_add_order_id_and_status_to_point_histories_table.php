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
        Schema::table('point_histories', function (Blueprint $table) {
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null')->after('member_id')->comment('관련 주문 ID');
            $table->enum('status', ['적립완료', '적립대기', '취소'])->default('적립완료')->after('amount')->comment('적립 상태');
        });
    }

    public function down(): void
    {
        Schema::table('point_histories', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn(['order_id', 'status']);
        });
    }
};
