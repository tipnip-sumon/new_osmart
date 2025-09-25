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
        // This column was already added in the previous migration
        // 2025_09_03_184446_add_enhanced_matching_features_to_commission_settings_table.php
        // We'll check if it exists before adding it to avoid errors
        if (!Schema::hasColumn('commission_settings', 'enable_multi_level')) {
            Schema::table('commission_settings', function (Blueprint $table) {
                $table->boolean('enable_multi_level')->default(false)->after('priority');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Since we're being careful not to add the column if it exists,
        // we should also be careful when removing it
        if (Schema::hasColumn('commission_settings', 'enable_multi_level')) {
            Schema::table('commission_settings', function (Blueprint $table) {
                $table->dropColumn('enable_multi_level');
            });
        }
    }
};
