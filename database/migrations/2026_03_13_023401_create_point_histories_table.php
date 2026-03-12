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
        Schema::create('point_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->string('reason'); // 적립/사용 사유 (예: 상품 구매 확정, 신규 가입)
            $table->integer('amount'); // 변동 금액 (적립은 +, 사용은 -)
            $table->integer('balance_after'); // 변동 후 최종 잔액
            $table->timestamp('expired_at')->nullable(); // 적립금 소멸 예정일
            $table->timestamps();

            // 인덱스 추가 (조회 성능 최적화)
            $table->index(['member_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_histories');
    }
};
