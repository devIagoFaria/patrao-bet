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
            $table->decimal('affiliate_commission_unpaid', 15, 2)->default(0.00)->after('balance');
            $table->decimal('affiliate_commission_paid', 15, 2)->default(0.00)->after('affiliate_commission_unpaid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['affiliate_commission_unpaid', 'affiliate_commission_paid']);
        });
    }
};
