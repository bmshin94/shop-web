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
        Schema::create('recent_views', function (Blueprint $create) {
            $create->id();
            $create->foreignId('member_id')->constrained()->onDelete('cascade');
            $create->foreignId('product_id')->constrained()->onDelete('cascade');
            $create->timestamp('viewed_at')->useCurrent();
            $create->timestamps();

            // 같은 회원이 같은 상품을 여러 번 봐도 마지막 시간만 갱신하기 위해 유니크 키 제안! 
            $create->unique(['member_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recent_views');
    }
};
