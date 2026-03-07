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
        // SQLite 테스트 환경은 컬럼 변경 시 DBAL이 필요하므로 타입 변경을 생략한다.
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            $table->longText('description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // SQLite 테스트 환경은 컬럼 변경 시 DBAL이 필요하므로 롤백도 생략한다.
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });
    }
};
