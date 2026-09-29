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
        Schema::create('category_buttons', function (Blueprint $table) {
            $table->id();
            $table->string('group'); // sidebar, sports
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('image_path')->nullable();
            $table->string('icon_class')->nullable();
            $table->string('action_type')->default('none'); // link, scroll, alert, play, none
            $table->text('action_value')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_buttons');
    }
};
