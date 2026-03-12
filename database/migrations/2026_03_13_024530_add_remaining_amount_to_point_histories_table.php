<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('point_histories', function (Blueprint $table) {
            $table->integer('remaining_amount')->default(0)->after('amount');
        });

        // 기존 적립 데이터 보정 (적립금(+)인 경우 남은 금액을 현재 금액으로 설정)
        DB::table('point_histories')
            ->where('amount', '>', 0)
            ->update(['remaining_amount' => DB::raw('amount')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('point_histories', function (Blueprint $table) {
            $table->dropColumn('remaining_amount');
        });
    }
};
