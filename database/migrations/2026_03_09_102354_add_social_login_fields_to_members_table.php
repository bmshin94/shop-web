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
        Schema::table('members', function (Blueprint $table) {
            $table->string('provider')->nullable()->after('status')->comment('소셜 제공자');
            $table->string('provider_id')->nullable()->after('provider')->comment('소셜 고유 ID');
            $table->string('avatar')->nullable()->after('phone')->comment('소셜 프로필 이미지');
            
            // 일반 로그인 회원은 비밀번호가 필수지만, 소셜 회원은 null 가능하도록 수정 (이미 필수라면)
            // members 테이블 생성 시 password가 nullable이 아니었으므로 수정 필요
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['provider', 'provider_id', 'avatar']);
            $table->string('password')->nullable(false)->change();
        });
    }
};
