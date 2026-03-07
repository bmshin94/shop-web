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
        Schema::create('exhibitions', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('기획전명');
            $table->string('slug')->unique()->comment('슬러그');
            $table->string('status', 20)->default('진행예정')->index()->comment('기획전 상태');
            $table->string('banner_image_url')->nullable()->comment('배너 이미지 URL');
            $table->string('summary', 255)->nullable()->comment('요약 문구');
            $table->text('description')->nullable()->comment('상세 설명');
            $table->timestamp('start_at')->nullable()->index()->comment('시작일시');
            $table->timestamp('end_at')->nullable()->index()->comment('종료일시');
            $table->unsignedInteger('sort_order')->default(0)->index()->comment('정렬 순서');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exhibitions');
    }
};
