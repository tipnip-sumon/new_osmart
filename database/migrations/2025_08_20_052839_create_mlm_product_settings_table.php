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
        Schema::create('mlm_product_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            
            // Point Systems
            $table->decimal('pv_points', 10, 2)->default(0)->comment('Personal Volume points');
            $table->decimal('bv_points', 10, 2)->default(0)->comment('Business Volume points');
            $table->decimal('cv_points', 10, 2)->default(0)->comment('Commissionable Volume points');
            $table->decimal('qv_points', 10, 2)->default(0)->comment('Qualifying Volume points');
            
            // Point Calculation
            $table->enum('point_calculation_method', ['fixed', 'percentage', 'tiered'])->default('percentage');
            $table->decimal('point_percentage', 5, 2)->default(70)->comment('Percentage of price for points');
            $table->json('point_tiers')->nullable()->comment('Tiered point structure');
            
            // Product Categories
            $table->boolean('is_starter_kit')->default(false)->comment('Product is a starter/enrollment kit');
            $table->boolean('is_autoship_eligible')->default(false)->comment('Eligible for autoship program');
            $table->boolean('generates_commission')->default(true)->comment('Product generates MLM commissions');
            $table->boolean('requires_qualification')->default(false)->comment('Requires qualification to purchase');
            
            // Qualification Requirements
            $table->string('minimum_rank_required')->nullable()->comment('Minimum rank required to purchase');
            $table->decimal('minimum_volume_required', 10, 2)->default(0)->comment('Minimum volume required');
            $table->boolean('counts_towards_qualification')->default(true)->comment('Counts towards rank qualification');
            
            // Purchase Limits
            $table->integer('max_purchase_per_month')->nullable()->comment('Maximum purchases per month');
            $table->integer('max_purchase_per_order')->nullable()->comment('Maximum quantity per order');
            $table->boolean('first_order_only')->default(false)->comment('Available only on first order');
            $table->integer('days_between_purchases')->nullable()->comment('Minimum days between purchases');
            
            // Binary Tree Settings
            $table->enum('placement_type', ['left', 'right', 'auto', 'sponsor_choice'])->nullable()->comment('Binary tree placement');
            $table->boolean('affects_binary_tree')->default(true)->comment('Purchase affects binary tree structure');
            $table->decimal('left_leg_points', 10, 2)->default(0)->comment('Points for left leg (binary)');
            $table->decimal('right_leg_points', 10, 2)->default(0)->comment('Points for right leg (binary)');
            
            // Recognition & Rewards
            $table->integer('recognition_points')->default(0)->comment('Points for recognition programs');
            $table->json('achievement_rewards')->nullable()->comment('Rewards for achieving sales targets');
            $table->boolean('contributes_to_rank_advancement')->default(true)->comment('Purchase contributes to rank advancement');
            
            // MLM Product Lifecycle
            $table->timestamp('mlm_launch_date')->nullable()->comment('MLM program launch date for product');
            $table->timestamp('mlm_end_date')->nullable()->comment('MLM program end date');
            $table->boolean('grandfathered_commissions')->default(false)->comment('Grandfathered commission structure');
            
            $table->timestamps();
            
            // Foreign key and indexes
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unique('product_id');
            $table->index(['generates_commission', 'is_starter_kit'], 'mlm_settings_commission_kit_idx');
            $table->index(['minimum_rank_required', 'minimum_volume_required'], 'mlm_settings_rank_volume_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mlm_product_settings');
    }
};
