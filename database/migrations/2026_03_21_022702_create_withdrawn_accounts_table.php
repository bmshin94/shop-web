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
        Schema::create('withdrawn_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index()->comment('탈퇴한 이메일');
            $table->timestamp('withdrawn_at')->comment('탈퇴 일시');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawn_accounts');
    }
};
