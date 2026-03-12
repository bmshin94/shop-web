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
        Schema::table('size_groups', function (Blueprint $table) {
            $table->json('size_guide')->nullable()->after('name')->comment('사이즈 가이드 표 데이터 (headers, rows)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('size_groups', function (Blueprint $table) {
            $table->dropColumn('size_guide');
        });
    }
};
