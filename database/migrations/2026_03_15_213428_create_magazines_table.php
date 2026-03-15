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
        Schema::create('magazines', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('category')->comment('카테고리 (LIFESTYLE, WORKOUT, FASHION 등)');
            $blueprint->string('title')->comment('제목');
            $blueprint->string('author')->comment('작성자 (에디터 이름 등)');
            $blueprint->string('image_url')->comment('메인 이미지 URL');
            $blueprint->text('content')->nullable()->comment('내용 (상세 내용)');
            $blueprint->boolean('is_visible')->default(true)->comment('노출 여부');
            $blueprint->timestamp('published_at')->nullable()->comment('게시일');
            $blueprint->timestamps();
            $blueprint->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('magazines');
    }
};
