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
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // 알림 코드 (예: WELCOME_JOIN, ORDER_CONFIRMED)
            $table->string('name');           // 관리용 템플릿 이름
            $table->string('template_id')->nullable(); // 카카오 알림톡 템플릿 ID
            $table->text('content');          // 메시지 본문 (변수 포함: #{name} 등)
            $table->json('buttons')->nullable(); // 버튼 정보 (JSON)
            $table->boolean('is_active')->default(true); // 활성화 여부
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_templates');
    }
};
