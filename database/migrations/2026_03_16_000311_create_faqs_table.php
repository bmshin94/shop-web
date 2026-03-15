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
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('category')->comment('카테고리 (member, order, delivery, return, product 등)');
            $table->string('question')->comment('질문');
            $table->text('answer')->comment('답변');
            $table->boolean('is_visible')->default(true)->comment('노출 여부');
            $table->integer('sort_order')->default(0)->comment('정렬 순서');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
