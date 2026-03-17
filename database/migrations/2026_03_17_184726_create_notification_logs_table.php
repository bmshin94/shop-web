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
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->nullable()->constrained()->nullOnDelete();
            $table->string('notification_type'); // 알림 종류 (예: WelcomeNotification)
            $table->string('channel');           // 채널 (alimtalk, sms 등)
            $table->string('recipient');         // 수신번호
            $table->text('message');             // 전송 메시지 내용
            $table->string('status')->default('대기'); // 대기, 성공, 실패
            $table->text('error_message')->nullable(); // 실패 원인
            $table->json('api_response')->nullable();  // API 응답 원본
            $table->timestamp('sent_at')->nullable();  // 실제 발송 완료 시간
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
