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
        Schema::table('banner_collections', function (Blueprint $table) {
            $table->json('image_data')->nullable()->after('image');
            $table->json('images_data')->nullable()->after('image_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banner_collections', function (Blueprint $table) {
            $table->dropColumn(['image_data', 'images_data']);
        });
    }
};
