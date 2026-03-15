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
        Schema::table('ootds', function (Blueprint $table) {
            $table->string('instagram_url')->nullable()->after('content')->comment('인스타그램 게시물 URL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ootds', function (Blueprint $table) {
            $table->dropColumn('instagram_url');
        });
    }
};
