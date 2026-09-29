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
        Schema::table('category_buttons', function (Blueprint $table) {
            $table->string('subtitle')->nullable()->after('icon_class');
            $table->string('badge_value')->nullable()->after('subtitle');
            $table->string('extra_info')->nullable()->after('badge_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category_buttons', function (Blueprint $table) {
            $table->dropColumn(['subtitle', 'badge_value', 'extra_info']);
        });
    }
};
