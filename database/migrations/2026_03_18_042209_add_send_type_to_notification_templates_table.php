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
        Schema::table('notification_templates', function (Blueprint $table) {
            // 발송 방식 구분 (기본값: alimtalk) 🚀
            $table->enum('send_type', ['alimtalk', 'sms'])->default('alimtalk')->after('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notification_templates', function (Blueprint $table) {
            $table->dropColumn('send_type');
        });
    }
};
