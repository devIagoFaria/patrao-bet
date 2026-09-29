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
        Schema::create('missions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type'); // 'bet_count', 'bet_amount', 'deposit_amount'
            $table->string('game_slug')->nullable(); // Target game if specific, or null
            $table->decimal('target_value', 15, 2);
            $table->decimal('reward_amount', 10, 2);
            $table->string('period')->default('once'); // 'daily', 'weekly', 'once'
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('missions');
    }
};
