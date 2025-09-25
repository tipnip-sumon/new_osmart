<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if table exists before modifying
        if (Schema::hasTable('daily_point_distributions')) {
            // Update the enum to include 'plan_purchase' and 'external_addition'
            DB::statement("ALTER TABLE daily_point_distributions MODIFY COLUMN acquisition_type ENUM('product_purchase', 'direct_purchase', 'external_addition', 'plan_purchase')");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if table exists before modifying
        if (Schema::hasTable('daily_point_distributions')) {
            // Revert back to original enum
            DB::statement("ALTER TABLE daily_point_distributions MODIFY COLUMN acquisition_type ENUM('product_purchase', 'direct_purchase')");
        }
    }
};
