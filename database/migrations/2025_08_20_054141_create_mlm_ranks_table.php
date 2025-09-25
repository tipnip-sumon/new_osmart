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
        Schema::create('mlm_ranks', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Rank name (e.g., Member, Bronze, Silver)');
            $table->string('slug')->unique()->comment('URL-friendly rank identifier');
            $table->text('description')->nullable()->comment('Rank description');
            $table->integer('level')->unique()->comment('Rank level (1=lowest, higher=better)');
            $table->string('color_code')->nullable()->comment('Color for rank display');
            $table->string('icon')->nullable()->comment('Icon for rank');
            $table->string('badge_image')->nullable()->comment('Badge image URL');
            
            // Qualification Requirements
            $table->decimal('min_personal_volume', 10, 2)->default(0)->comment('Minimum personal volume required');
            $table->decimal('min_team_volume', 15, 2)->default(0)->comment('Minimum team volume required');
            $table->decimal('min_left_leg_volume', 15, 2)->default(0)->comment('Min left leg volume (binary)');
            $table->decimal('min_right_leg_volume', 15, 2)->default(0)->comment('Min right leg volume (binary)');
            $table->integer('min_direct_referrals')->default(0)->comment('Minimum direct referrals required');
            $table->integer('min_active_legs')->default(0)->comment('Minimum active legs required');
            $table->integer('min_qualified_legs')->default(0)->comment('Minimum qualified legs required');
            
            // Advanced Requirements
            $table->json('rank_requirements')->nullable()->comment('Complex rank requirements');
            $table->integer('qualification_period_months')->default(1)->comment('Months to maintain qualifications');
            $table->boolean('requires_autoship')->default(false)->comment('Requires active autoship');
            $table->decimal('min_autoship_volume', 10, 2)->default(0)->comment('Minimum autoship volume');
            
            // Benefits and Bonuses
            $table->decimal('rank_bonus_amount', 10, 2)->default(0)->comment('One-time rank achievement bonus');
            $table->decimal('monthly_bonus_amount', 10, 2)->default(0)->comment('Monthly bonus for maintaining rank');
            $table->decimal('car_bonus_amount', 10, 2)->default(0)->comment('Monthly car bonus');
            $table->decimal('travel_bonus_amount', 10, 2)->default(0)->comment('Annual travel bonus');
            $table->json('additional_benefits')->nullable()->comment('Other benefits (JSON)');
            
            // Commission Enhancements
            $table->decimal('commission_multiplier', 3, 2)->default(1.00)->comment('Commission rate multiplier');
            $table->boolean('infinity_bonus_eligible')->default(false)->comment('Eligible for infinity bonus');
            $table->decimal('infinity_bonus_rate', 5, 2)->default(0)->comment('Infinity bonus rate');
            $table->integer('max_compression_levels')->default(0)->comment('Max levels for compression');
            $table->boolean('override_bonus_eligible')->default(false)->comment('Eligible for override bonuses');
            
            // Recognition and Status
            $table->boolean('leadership_rank')->default(false)->comment('Is this a leadership rank');
            $table->boolean('public_recognition')->default(false)->comment('Gets public recognition');
            $table->string('recognition_title')->nullable()->comment('Special title for this rank');
            $table->json('recognition_rewards')->nullable()->comment('Recognition rewards');
            
            // Maintenance and Retention
            $table->integer('grace_period_months')->default(0)->comment('Grace period before demotion');
            $table->boolean('lifetime_rank')->default(false)->comment('Lifetime achievement rank');
            $table->decimal('retention_volume_percentage', 5, 2)->default(100)->comment('% of volume needed to maintain');
            
            // Status
            $table->boolean('is_active')->default(true)->comment('Rank is active/available');
            $table->date('effective_from')->nullable()->comment('When rank becomes available');
            $table->date('effective_until')->nullable()->comment('When rank expires');
            
            $table->timestamps();
            
            // Indexes
            $table->index(['level', 'is_active']);
            $table->index(['min_personal_volume', 'min_team_volume']);
            $table->index('effective_from');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mlm_ranks');
    }
};
