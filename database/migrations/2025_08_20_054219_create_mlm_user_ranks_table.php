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
        Schema::create('mlm_user_ranks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('User whose rank this tracks');
            $table->unsignedBigInteger('rank_id')->comment('MLM rank achieved');
            
            // Rank Achievement Details
            $table->date('achieved_date')->comment('Date rank was achieved');
            $table->date('effective_from')->comment('Date rank becomes effective');
            $table->date('effective_until')->nullable()->comment('Date rank expires (if temporary)');
            $table->boolean('is_current')->default(false)->comment('Current active rank');
            $table->boolean('is_lifetime')->default(false)->comment('Lifetime achievement');
            
            // Qualification Metrics When Achieved
            $table->decimal('personal_volume_at_achievement', 10, 2)->default(0)->comment('PV when rank achieved');
            $table->decimal('team_volume_at_achievement', 15, 2)->default(0)->comment('Team volume when achieved');
            $table->decimal('left_leg_volume_at_achievement', 15, 2)->default(0)->comment('Left leg volume when achieved');
            $table->decimal('right_leg_volume_at_achievement', 15, 2)->default(0)->comment('Right leg volume when achieved');
            $table->integer('direct_referrals_at_achievement')->default(0)->comment('Direct referrals when achieved');
            $table->integer('active_legs_at_achievement')->default(0)->comment('Active legs when achieved');
            
            // Maintenance Tracking
            $table->boolean('qualification_maintained')->default(true)->comment('Still maintaining qualification');
            $table->date('last_qualification_check')->nullable()->comment('Last time qualification was checked');
            $table->integer('consecutive_months_maintained')->default(0)->comment('Months continuously maintained');
            $table->date('last_maintained_date')->nullable()->comment('Last date qualification was maintained');
            
            // Rank Loss/Demotion
            $table->date('lost_date')->nullable()->comment('Date rank was lost');
            $table->enum('loss_reason', [
                'volume_drop',
                'inactivity', 
                'qualification_failure',
                'administrative',
                'voluntary',
                'promotion'
            ])->nullable()->comment('Reason rank was lost');
            $table->text('loss_notes')->nullable()->comment('Notes about rank loss');
            $table->boolean('in_grace_period')->default(false)->comment('Currently in grace period');
            $table->date('grace_period_ends')->nullable()->comment('When grace period ends');
            
            // Bonuses and Benefits Earned
            $table->decimal('rank_bonus_earned', 10, 2)->default(0)->comment('One-time rank bonus earned');
            $table->decimal('total_monthly_bonuses', 10, 2)->default(0)->comment('Total monthly bonuses earned');
            $table->decimal('total_car_bonuses', 10, 2)->default(0)->comment('Total car bonuses earned');
            $table->decimal('total_travel_bonuses', 10, 2)->default(0)->comment('Total travel bonuses earned');
            $table->boolean('bonus_paid')->default(false)->comment('Rank achievement bonus paid');
            $table->date('bonus_paid_date')->nullable()->comment('Date bonus was paid');
            
            // Administrative
            $table->text('achievement_notes')->nullable()->comment('Notes about rank achievement');
            $table->json('qualification_details')->nullable()->comment('Detailed qualification breakdown');
            $table->unsignedBigInteger('approved_by')->nullable()->comment('Admin who approved rank');
            $table->timestamp('approved_at')->nullable()->comment('When rank was approved');
            
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('rank_id')->references('id')->on('mlm_ranks')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index(['user_id', 'is_current'], 'mlm_user_ranks_user_current_idx');
            $table->index(['rank_id', 'achieved_date'], 'mlm_user_ranks_rank_date_idx');
            $table->index(['effective_from', 'effective_until'], 'mlm_user_ranks_effective_idx');
            $table->index(['qualification_maintained', 'last_qualification_check'], 'mlm_user_ranks_qual_check_idx');
            $table->unique(['user_id', 'rank_id', 'achieved_date'], 'unique_user_rank_achievement');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mlm_user_ranks');
    }
};
