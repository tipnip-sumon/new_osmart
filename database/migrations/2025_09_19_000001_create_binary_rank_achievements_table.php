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
        Schema::create('binary_rank_achievements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('rank_name');
            $table->integer('rank_level');
            
            // Binary Requirements for Achievement
            $table->decimal('required_left_points', 15, 2);
            $table->decimal('required_right_points', 15, 2);
            $table->decimal('matching_tk', 15, 2);  // 1 Point = 6 TK
            $table->decimal('point_10_percent', 15, 2); // 10% of points
            
            // Rewards & Benefits
            $table->string('tour_reward')->nullable();
            $table->string('gift_reward')->nullable();
            $table->decimal('salary_amount', 15, 2)->default(0);
            $table->integer('duration_months');
            
            // Monthly Conditions for Salary
            $table->decimal('monthly_left_points', 15, 2);
            $table->decimal('monthly_right_points', 15, 2);
            $table->decimal('monthly_matching_tk', 15, 2);
            
            // Achievement Status
            $table->boolean('is_achieved')->default(false);
            $table->timestamp('achieved_at')->nullable();
            $table->boolean('is_current_rank')->default(false);
            
            // Monthly Qualification Tracking
            $table->boolean('monthly_qualified')->default(false);
            $table->date('last_qualified_month')->nullable();
            $table->integer('consecutive_qualified_months')->default(0);
            
            // Bonus Payments
            $table->decimal('total_matching_bonus', 15, 2)->default(0);
            $table->decimal('total_salary_paid', 15, 2)->default(0);
            $table->integer('salary_months_paid')->default(0);
            
            $table->timestamps();
            
            // Indexes
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'rank_level']);
            $table->index(['is_achieved', 'is_current_rank']);
            $table->unique(['user_id', 'rank_name']);
        });
        
        // Seed the rank structure data
        Schema::table('binary_rank_achievements', function (Blueprint $table) {
            // This will be handled by seeder
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('binary_rank_achievements');
    }
};