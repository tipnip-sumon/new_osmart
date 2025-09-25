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
        Schema::table('users', function (Blueprint $table) {
            // Add fields to track processed volumes for payout calculations
            $table->decimal('processed_daily_volume', 15, 2)->default(0)->after('daily_sales_volume');
            $table->decimal('processed_monthly_volume', 15, 2)->default(0)->after('monthly_sales_volume');
            $table->decimal('processed_total_volume', 15, 2)->default(0)->after('total_sales_volume');
            
            // Add field to track when volumes were last processed for payouts
            $table->timestamp('last_payout_processed_at')->nullable()->after('processed_total_volume');
            $table->date('last_daily_reset_date')->nullable()->after('last_payout_processed_at');
            $table->string('last_monthly_reset_period', 7)->nullable()->after('last_daily_reset_date'); // Format: YYYY-MM
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'processed_daily_volume',
                'processed_monthly_volume', 
                'processed_total_volume',
                'last_payout_processed_at',
                'last_daily_reset_date',
                'last_monthly_reset_period'
            ]);
        });
    }
};
