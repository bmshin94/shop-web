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
        Schema::create('ootd_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->foreignId('ootd_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // 한 회원이 같은 OOTD에 좋아요를 중복으로 누를 수 없게! 🛡️✨
            $table->unique(['member_id', 'ootd_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ootd_likes');
    }
};
