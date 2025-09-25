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
        Schema::table('popups', function (Blueprint $table) {
            // Only add image column if it doesn't exist
            if (!Schema::hasColumn('popups', 'image')) {
                $table->string('image')->nullable();
            }
            
            // Only add image_data column if it doesn't exist
            if (!Schema::hasColumn('popups', 'image_data')) {
                $table->json('image_data')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('popups', function (Blueprint $table) {
            // Only drop columns if they exist
            if (Schema::hasColumn('popups', 'image_data')) {
                $table->dropColumn('image_data');
            }
            
            if (Schema::hasColumn('popups', 'image')) {
                $table->dropColumn('image');
            }
        });
    }
};
