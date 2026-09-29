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
        Schema::table('users', function (Blueprint $table) {
            // Per-affiliate commission plan: 'revshare' (% of referred deposits) or 'cpa' (fixed amount paid once per referral).
            $table->string('commission_model')->default('revshare')->after('affiliate_commission_paid');
            $table->decimal('commission_value', 10, 2)->nullable()->after('commission_model');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['commission_model', 'commission_value']);
        });
    }
};
