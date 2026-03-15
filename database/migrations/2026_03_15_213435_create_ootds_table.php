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
        Schema::create('ootds', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('member_id')->constrained()->onDelete('cascade')->comment('작성 회원 ID');
            $blueprint->string('image_url')->comment('이미지 URL');
            $blueprint->text('content')->nullable()->comment('내용');
            $blueprint->integer('likes_count')->default(0)->comment('좋아요 수');
            $blueprint->boolean('is_visible')->default(true)->comment('노출 여부');
            $blueprint->timestamps();
            $blueprint->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ootds');
    }
};
