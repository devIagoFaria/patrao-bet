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
        Schema::create('user_mission_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('mission_id')->constrained()->onDelete('cascade');
            $table->decimal('current_value', 15, 2)->default(0.00);
            $table->boolean('is_completed')->default(false);
            $table->boolean('reward_claimed')->default(false);
            $table->dateTime('reset_at')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'mission_id', 'reset_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_mission_progress');
    }
};
