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
        Schema::table('sizes', function (Blueprint $table) {
            $table->foreignId('size_group_id')->nullable()->after('id')->constrained()->onDelete('set null')->comment('사이즈 그룹 ID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sizes', function (Blueprint $table) {
            $table->dropForeign(['size_group_id']);
            $table->dropColumn('size_group_id');
        });
    }
};
