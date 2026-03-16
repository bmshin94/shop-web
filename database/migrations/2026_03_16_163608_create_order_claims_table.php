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
        // 1. 교환/반품 신청 메인 테이블
        Schema::create('order_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('claim_number')->unique(); // 신청 번호 (C + 날짜 + 랜덤)
            $table->string('type'); // exchange, return
            $table->string('reason_type'); // 사유 유형
            $table->text('reason_detail')->nullable(); // 상세 사유
            $table->string('status')->default('접수'); // 접수, 승인, 거절, 완료
            $table->text('admin_memo')->nullable(); // 관리자 메모
            $table->timestamp('processed_at')->nullable(); // 처리 완료 시각
            $table->timestamps();
        });

        // 2. 신청 건에 포함된 상품 상세 테이블
        Schema::create('order_claim_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_claim_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_item_id')->constrained()->onDelete('cascade');
            $table->integer('quantity'); // 신청 수량
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_claim_items');
        Schema::dropIfExists('order_claims');
    }
};
