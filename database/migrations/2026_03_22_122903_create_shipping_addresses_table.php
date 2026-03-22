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
        Schema::create('shipping_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade')->comment('회원 ID');
            $table->string('address_name')->comment('배송지명 (예: 우리집, 회사)');
            $table->string('recipient_name')->comment('수령인 이름');
            $table->string('phone_number')->comment('수령인 연락처');
            $table->string('zip_code', 10)->comment('우편번호');
            $table->string('address')->comment('기본 주소');
            $table->string('address_detail')->comment('상세 주소');
            $table->boolean('is_default')->default(false)->comment('기본 배송지 여부');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_addresses');
    }
};
