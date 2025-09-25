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
            // MLM Point Systems
            if (!Schema::hasColumn('products', 'pv_points')) {
                $table->decimal('pv_points', 10, 2)->default(0)->nullable()->comment('Personal Volume points');
            }
            if (!Schema::hasColumn('products', 'bv_points')) {
                $table->decimal('bv_points', 10, 2)->default(0)->nullable()->comment('Business Volume points');
            }
            if (!Schema::hasColumn('products', 'cv_points')) {
                $table->decimal('cv_points', 10, 2)->default(0)->nullable()->comment('Commissionable Volume points');
            }
            if (!Schema::hasColumn('products', 'qv_points')) {
                $table->decimal('qv_points', 10, 2)->default(0)->nullable()->comment('Qualifying Volume points');
            }

            // Commission Rates
            if (!Schema::hasColumn('products', 'direct_commission_rate')) {
                $table->decimal('direct_commission_rate', 5, 2)->nullable()->comment('Direct commission rate percentage');
            }
            if (!Schema::hasColumn('products', 'level_1_commission')) {
                $table->decimal('level_1_commission', 5, 2)->nullable()->comment('Level 1 commission rate');
            }
            if (!Schema::hasColumn('products', 'level_2_commission')) {
                $table->decimal('level_2_commission', 5, 2)->nullable()->comment('Level 2 commission rate');
            }
            if (!Schema::hasColumn('products', 'level_3_commission')) {
                $table->decimal('level_3_commission', 5, 2)->nullable()->comment('Level 3 commission rate');
            }
            if (!Schema::hasColumn('products', 'level_4_commission')) {
                $table->decimal('level_4_commission', 5, 2)->nullable()->comment('Level 4 commission rate');
            }
            if (!Schema::hasColumn('products', 'level_5_commission')) {
                $table->decimal('level_5_commission', 5, 2)->nullable()->comment('Level 5 commission rate');
            }

            // MLM Product Types and Settings
            if (!Schema::hasColumn('products', 'is_starter_kit')) {
                $table->boolean('is_starter_kit')->default(false)->comment('Product is a starter/enrollment kit');
            }
            if (!Schema::hasColumn('products', 'is_autoship_eligible')) {
                $table->boolean('is_autoship_eligible')->default(false)->comment('Eligible for autoship program');
            }
            if (!Schema::hasColumn('products', 'generates_commission')) {
                $table->boolean('generates_commission')->default(true)->comment('Product generates MLM commissions');
            }
            if (!Schema::hasColumn('products', 'requires_qualification')) {
                $table->boolean('requires_qualification')->default(false)->comment('Requires qualification to purchase');
            }

            // Point Calculation Settings
            if (!Schema::hasColumn('products', 'point_calculation_method')) {
                $table->enum('point_calculation_method', ['fixed', 'percentage', 'tiered'])->default('percentage')->nullable()->comment('How points are calculated');
            }
            if (!Schema::hasColumn('products', 'point_percentage')) {
                $table->decimal('point_percentage', 5, 2)->default(70)->nullable()->comment('Percentage of price for points');
            }
            if (!Schema::hasColumn('products', 'minimum_rank_required')) {
                $table->string('minimum_rank_required')->nullable()->comment('Minimum rank required to purchase');
            }
            if (!Schema::hasColumn('products', 'minimum_volume_required')) {
                $table->decimal('minimum_volume_required', 10, 2)->default(0)->nullable()->comment('Minimum volume required');
            }
            if (!Schema::hasColumn('products', 'counts_towards_qualification')) {
                $table->boolean('counts_towards_qualification')->default(true)->nullable()->comment('Counts towards rank qualification');
            }

            // Bonus and Incentive Settings
            if (!Schema::hasColumn('products', 'fast_start_bonus')) {
                $table->decimal('fast_start_bonus', 10, 2)->nullable()->comment('Fast start bonus amount');
            }
            if (!Schema::hasColumn('products', 'team_bonus_rate')) {
                $table->decimal('team_bonus_rate', 5, 2)->nullable()->comment('Team bonus rate percentage');
            }
            if (!Schema::hasColumn('products', 'leadership_bonus_rate')) {
                $table->decimal('leadership_bonus_rate', 5, 2)->nullable()->comment('Leadership bonus rate percentage');
            }
            if (!Schema::hasColumn('products', 'eligible_for_car_bonus')) {
                $table->boolean('eligible_for_car_bonus')->default(false)->nullable()->comment('Eligible for car bonus program');
            }
            if (!Schema::hasColumn('products', 'eligible_for_travel_bonus')) {
                $table->boolean('eligible_for_travel_bonus')->default(false)->nullable()->comment('Eligible for travel bonus program');
            }

            // Purchase Limitations
            if (!Schema::hasColumn('products', 'max_purchase_per_month')) {
                $table->integer('max_purchase_per_month')->nullable()->comment('Maximum purchases per month');
            }
            if (!Schema::hasColumn('products', 'max_purchase_per_order')) {
                $table->integer('max_purchase_per_order')->nullable()->comment('Maximum quantity per order');
            }
            if (!Schema::hasColumn('products', 'first_order_only')) {
                $table->boolean('first_order_only')->default(false)->nullable()->comment('Available only on first order');
            }
            if (!Schema::hasColumn('products', 'days_between_purchases')) {
                $table->integer('days_between_purchases')->nullable()->comment('Minimum days between purchases');
            }

            // Autoship Settings
            if (!Schema::hasColumn('products', 'autoship_required')) {
                $table->boolean('autoship_required')->default(false)->nullable()->comment('Autoship required for commission');
            }
            if (!Schema::hasColumn('products', 'autoship_frequency_days')) {
                $table->integer('autoship_frequency_days')->nullable()->comment('Autoship frequency in days');
            }
            if (!Schema::hasColumn('products', 'autoship_discount_rate')) {
                $table->decimal('autoship_discount_rate', 5, 2)->nullable()->comment('Autoship discount percentage');
            }
            if (!Schema::hasColumn('products', 'autoship_volume_counts')) {
                $table->boolean('autoship_volume_counts')->default(true)->nullable()->comment('Autoship counts towards volume');
            }

            // Binary Tree Settings
            if (!Schema::hasColumn('products', 'placement_type')) {
                $table->enum('placement_type', ['left', 'right', 'auto', 'sponsor_choice'])->nullable()->comment('Binary tree placement');
            }
            if (!Schema::hasColumn('products', 'affects_binary_tree')) {
                $table->boolean('affects_binary_tree')->default(true)->nullable()->comment('Purchase affects binary tree structure');
            }
            if (!Schema::hasColumn('products', 'left_leg_points')) {
                $table->decimal('left_leg_points', 10, 2)->default(0)->nullable()->comment('Points for left leg (binary)');
            }
            if (!Schema::hasColumn('products', 'right_leg_points')) {
                $table->decimal('right_leg_points', 10, 2)->default(0)->nullable()->comment('Points for right leg (binary)');
            }

            // Recognition and Achievement
            if (!Schema::hasColumn('products', 'recognition_points')) {
                $table->decimal('recognition_points', 10, 2)->default(0)->nullable()->comment('Points for recognition programs');
            }
            if (!Schema::hasColumn('products', 'contributes_to_rank_advancement')) {
                $table->boolean('contributes_to_rank_advancement')->default(true)->nullable()->comment('Purchase contributes to rank advancement');
            }
            if (!Schema::hasColumn('products', 'point_tiers')) {
                $table->json('point_tiers')->nullable()->comment('Tiered point structure');
            }
            if (!Schema::hasColumn('products', 'achievement_rewards')) {
                $table->json('achievement_rewards')->nullable()->comment('Achievement rewards configuration');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $columns = [
                'pv_points', 'bv_points', 'cv_points', 'qv_points',
                'direct_commission_rate', 'level_1_commission', 'level_2_commission', 'level_3_commission', 'level_4_commission', 'level_5_commission',
                'is_starter_kit', 'is_autoship_eligible', 'generates_commission', 'requires_qualification',
                'point_calculation_method', 'point_percentage', 'minimum_rank_required', 'minimum_volume_required', 'counts_towards_qualification',
                'fast_start_bonus', 'team_bonus_rate', 'leadership_bonus_rate', 'eligible_for_car_bonus', 'eligible_for_travel_bonus',
                'max_purchase_per_month', 'max_purchase_per_order', 'first_order_only', 'days_between_purchases',
                'autoship_required', 'autoship_frequency_days', 'autoship_discount_rate', 'autoship_volume_counts',
                'placement_type', 'affects_binary_tree', 'left_leg_points', 'right_leg_points',
                'recognition_points', 'contributes_to_rank_advancement', 'point_tiers', 'achievement_rewards'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
