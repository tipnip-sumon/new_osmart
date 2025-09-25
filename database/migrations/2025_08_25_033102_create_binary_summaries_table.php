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
        Schema::create('binary_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Current carry forward balances
            $table->decimal('left_carry_balance', 15, 2)->default(0);
            $table->decimal('right_carry_balance', 15, 2)->default(0);
            
            // Lifetime totals
            $table->decimal('lifetime_left_volume', 15, 2)->default(0);
            $table->decimal('lifetime_right_volume', 15, 2)->default(0);
            $table->decimal('lifetime_matching_bonus', 15, 2)->default(0);
            $table->decimal('lifetime_slot_bonus', 15, 2)->default(0);
            $table->decimal('lifetime_capped_amount', 15, 2)->default(0);
            
            // Current period totals
            $table->decimal('current_period_left', 15, 2)->default(0);
            $table->decimal('current_period_right', 15, 2)->default(0);
            $table->decimal('current_period_bonus', 15, 2)->default(0);
            
            // Monthly totals
            $table->decimal('monthly_left_volume', 15, 2)->default(0);
            $table->decimal('monthly_right_volume', 15, 2)->default(0);
            $table->decimal('monthly_matching_bonus', 15, 2)->default(0);
            $table->decimal('monthly_capped_amount', 15, 2)->default(0);
            
            // Weekly totals
            $table->decimal('weekly_left_volume', 15, 2)->default(0);
            $table->decimal('weekly_right_volume', 15, 2)->default(0);
            $table->decimal('weekly_matching_bonus', 15, 2)->default(0);
            $table->decimal('weekly_capped_amount', 15, 2)->default(0);
            
            // Daily totals
            $table->decimal('daily_left_volume', 15, 2)->default(0);
            $table->decimal('daily_right_volume', 15, 2)->default(0);
            $table->decimal('daily_matching_bonus', 15, 2)->default(0);
            $table->decimal('daily_capped_amount', 15, 2)->default(0);
            
            // Counting records
            $table->integer('total_matching_records')->default(0);
            $table->integer('total_slot_matches')->default(0);
            
            // Reset tracking
            $table->date('last_daily_reset')->nullable();
            $table->date('last_weekly_reset')->nullable();
            $table->date('last_monthly_reset')->nullable();
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_calculated_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->unique('user_id');
            $table->index(['is_active', 'last_calculated_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('binary_summaries');
    }
};
