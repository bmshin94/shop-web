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
            $table->string('postal_code', 10)->nullable()->after('phone');
            $table->string('address_line1')->nullable()->after('postal_code');
            $table->string('address_line2')->nullable()->after('address_line1');
            $table->boolean('marketing_sms')->default(false)->after('status');
            $table->boolean('marketing_email')->default(false)->after('marketing_sms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['postal_code', 'address_line1', 'address_line2', 'marketing_sms', 'marketing_email']);
        });
    }
};
