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
        Schema::create('operators', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('운영자명');
            $table->string('email')->unique()->comment('운영자 이메일');
            $table->string('phone', 30)->nullable()->comment('운영자 연락처');
            $table->string('password')->comment('운영자 비밀번호');
            $table->string('status', 20)->default('활성')->index()->comment('운영자 상태');
            $table->timestamp('last_login_at')->nullable()->comment('최근 로그인 일시');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operators');
    }
};
