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
        Schema::table('events', function (Blueprint $table) {
            $table->string('type')->default('일반')->comment('이벤트 유형 (일반, 응모형)')->change();
        });
        
        // 기존 데이터 업데이트
        \DB::table('events')->where('type', '참여형')->update(['type' => '응모형']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('type')->default('일반')->comment('이벤트 유형 (일반, 참여형)')->change();
        });
        
        \DB::table('events')->where('type', '응모형')->update(['type' => '참여형']);
    }
};
