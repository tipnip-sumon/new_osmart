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
        Schema::create('mlm_commission_structures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            
            // Commission Rates by Level
            $table->decimal('direct_commission_rate', 5, 2)->default(0)->comment('Direct referral commission %');
            $table->decimal('level_1_commission', 5, 2)->default(0)->comment('Level 1 commission %');
            $table->decimal('level_2_commission', 5, 2)->default(0)->comment('Level 2 commission %');
            $table->decimal('level_3_commission', 5, 2)->default(0)->comment('Level 3 commission %');
            $table->decimal('level_4_commission', 5, 2)->default(0)->comment('Level 4 commission %');
            $table->decimal('level_5_commission', 5, 2)->default(0)->comment('Level 5 commission %');
            $table->decimal('level_6_commission', 5, 2)->default(0)->comment('Level 6 commission %');
            $table->decimal('level_7_commission', 5, 2)->default(0)->comment('Level 7 commission %');
            $table->decimal('level_8_commission', 5, 2)->default(0)->comment('Level 8 commission %');
            $table->decimal('level_9_commission', 5, 2)->default(0)->comment('Level 9 commission %');
            $table->decimal('level_10_commission', 5, 2)->default(0)->comment('Level 10 commission %');
            
            // Commission Types
            $table->enum('commission_type', ['percentage', 'fixed_amount', 'tiered'])->default('percentage');
            $table->decimal('max_commission_amount', 10, 2)->nullable()->comment('Maximum commission amount cap');
            $table->decimal('min_commission_amount', 10, 2)->nullable()->comment('Minimum commission amount');
            
            // Compression & Qualification Rules
            $table->boolean('compression_enabled')->default(false)->comment('Skip inactive/unqualified levels');
            $table->integer('max_compression_levels')->default(3)->comment('Maximum levels to compress');
            $table->boolean('requires_personal_sales')->default(false)->comment('Requires personal sales to earn');
            $table->decimal('min_personal_volume', 10, 2)->default(0)->comment('Minimum personal volume required');
            
            // Special Commission Rules
            $table->json('rank_requirements')->nullable()->comment('Commission requirements by rank');
            $table->json('volume_requirements')->nullable()->comment('Volume requirements for each level');
            $table->boolean('infinity_bonus')->default(false)->comment('Infinity bonus to qualified leaders');
            $table->decimal('infinity_bonus_rate', 5, 2)->default(0)->comment('Infinity bonus rate %');
            
            // Binary System Commission Rules
            $table->boolean('binary_commission_enabled')->default(false)->comment('Enable binary commission for this product');
            $table->decimal('binary_commission_rate', 5, 2)->default(0)->comment('Binary commission rate %');
            $table->decimal('left_leg_points', 10, 2)->default(0)->comment('Points assigned to left leg');
            $table->decimal('right_leg_points', 10, 2)->default(0)->comment('Points assigned to right leg');
            $table->enum('binary_point_assignment', ['equal', 'weighted', 'custom'])->default('equal')->comment('How points are assigned to legs');
            $table->decimal('binary_cap_amount', 10, 2)->nullable()->comment('Daily/weekly binary commission cap');
            $table->enum('binary_cap_period', ['daily', 'weekly', 'monthly'])->default('weekly')->comment('Binary cap period');
            $table->decimal('binary_match_percentage', 5, 2)->default(100)->comment('Percentage match for binary bonus');
            $table->boolean('carry_forward_enabled')->default(true)->comment('Carry forward unmatched volume');
            $table->integer('carry_forward_days')->default(30)->comment('Days to carry forward volume');
            
            // Commission Periods
            $table->enum('commission_period', ['weekly', 'monthly', 'quarterly'])->default('monthly');
            $table->date('effective_from')->comment('When this structure becomes effective');
            $table->date('effective_until')->nullable()->comment('When this structure expires');
            
            $table->timestamps();
            
            // Foreign key and indexes
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->index(['product_id', 'effective_from', 'effective_until'], 'mlm_comm_product_dates_idx');
            $table->index('commission_type', 'mlm_comm_type_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mlm_commission_structures');
    }
};
