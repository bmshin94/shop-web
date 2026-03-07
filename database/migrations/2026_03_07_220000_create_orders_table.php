<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique()->comment('주문번호');
            $table->string('customer_name')->comment('주문자명');
            $table->string('customer_email')->nullable()->comment('주문자 이메일');
            $table->string('customer_phone', 30)->comment('주문자 연락처');
            $table->string('recipient_name')->comment('수령인명');
            $table->string('recipient_phone', 30)->comment('수령인 연락처');
            $table->string('postal_code', 20)->nullable()->comment('우편번호');
            $table->string('address_line1')->comment('기본 배송지');
            $table->string('address_line2')->nullable()->comment('상세 배송지');
            $table->string('shipping_message')->nullable()->comment('배송 요청사항');
            $table->string('payment_method')->default('신용카드')->comment('결제수단');
            $table->string('payment_status')->default('결제완료')->index()->comment('결제상태');
            $table->string('order_status')->default('주문접수')->index()->comment('주문상태');
            $table->string('shipping_status')->default('배송대기')->index()->comment('배송상태');
            $table->string('courier')->nullable()->comment('택배사');
            $table->string('tracking_number')->nullable()->index()->comment('송장번호');
            $table->text('admin_memo')->nullable()->comment('관리자 메모');
            $table->unsignedInteger('subtotal_amount')->default(0)->comment('상품 합계');
            $table->unsignedInteger('shipping_amount')->default(0)->comment('배송비');
            $table->unsignedInteger('discount_amount')->default(0)->comment('할인 금액');
            $table->unsignedInteger('total_amount')->default(0)->comment('최종 결제 금액');
            $table->timestamp('ordered_at')->nullable()->index()->comment('주문 시각');
            $table->timestamp('shipped_at')->nullable()->comment('출고 시각');
            $table->timestamp('delivered_at')->nullable()->comment('배송 완료 시각');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
