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
        Schema::create('vip_levels', function (Blueprint $table) {
            $table->id();
            $table->integer('level_number')->unique();
            $table->string('name');
            $table->integer('required_points')->default(0);
            $table->decimal('exchange_rate', 15, 4)->default(100.0000); // e.g. 100 coins = R$ 1.00
            $table->integer('min_exchange')->default(100);
            $table->string('badge_path')->nullable();
            $table->json('benefits')->nullable(); // JSON list of unlocked benefits
            $table->decimal('cashback_percent', 5, 2)->default(1.00); // e.g. 1%
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vip_levels');
    }
};
