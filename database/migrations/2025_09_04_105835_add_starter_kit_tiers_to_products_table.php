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
        Schema::table('products', function (Blueprint $table) {
            // Add new starter kit tier fields
            if(!Schema::hasColumn('products', 'starter_kit_tier')) {
                $table->enum('starter_kit_tier', ['basic', 'standard', 'premium', 'platinum'])->nullable();
            }
            if(!Schema::hasColumn('products', 'starter_kit_level')) {
                $table->string('starter_kit_level')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'starter_kit_tier')) {
                $table->dropColumn('starter_kit_tier');
            }
            if (Schema::hasColumn('products', 'starter_kit_level')) {
                $table->dropColumn('starter_kit_level');
            }
        });
    }
};
