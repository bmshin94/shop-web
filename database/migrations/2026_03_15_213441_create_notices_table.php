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
        Schema::create('notices', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('type')->default('일반')->comment('구분 (공지, 일반, 이벤트 등)');
            $blueprint->string('title')->comment('제목');
            $blueprint->text('content')->nullable()->comment('내용');
            $blueprint->boolean('is_important')->default(false)->comment('중요 공지 여부');
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
        Schema::dropIfExists('notices');
    }
};
