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
            $table->unsignedBigInteger('referred_by')->nullable()->after('id');
            $table->integer('vip_points')->default(0)->after('balance');
            $table->decimal('bonus_balance', 15, 2)->default(0.00)->after('vip_points');
            $table->decimal('bonus_rollover_target', 15, 2)->default(0.00)->after('bonus_balance');
            $table->decimal('bonus_rollover_current', 15, 2)->default(0.00)->after('bonus_rollover_target');

            $table->foreign('referred_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['referred_by']);
            $table->dropColumn([
                'referred_by',
                'vip_points',
                'bonus_balance',
                'bonus_rollover_target',
                'bonus_rollover_current'
            ]);
        });
    }
};
