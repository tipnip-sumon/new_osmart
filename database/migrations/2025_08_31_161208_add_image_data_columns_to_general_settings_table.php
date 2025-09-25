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
        Schema::table('general_settings', function (Blueprint $table) {
            // Add JSON columns to store image processing data
            $table->json('logo_data')->nullable()->after('logo');
            $table->json('admin_logo_data')->nullable()->after('admin_logo');
            $table->json('favicon_data')->nullable()->after('favicon');
            $table->json('meta_image_data')->nullable()->after('meta_image');
            $table->json('maintenance_image_data')->nullable()->after('maintenance_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            // Remove the image data columns
            $table->dropColumn([
                'logo_data',
                'admin_logo_data', 
                'favicon_data',
                'meta_image_data',
                'maintenance_image_data'
            ]);
        });
    }
};
