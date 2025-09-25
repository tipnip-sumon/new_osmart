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
        Schema::create('mlm_binary_tree', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique()->comment('User in the binary tree');
            $table->unsignedBigInteger('sponsor_id')->nullable()->comment('Direct sponsor/recruiter');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('Binary parent (placement)');
            $table->unsignedBigInteger('left_child_id')->nullable()->comment('Left leg child');
            $table->unsignedBigInteger('right_child_id')->nullable()->comment('Right leg child');
            
            // Position and Placement
            $table->enum('position', ['left', 'right'])->nullable()->comment('Position under parent');
            $table->enum('placement_type', ['sponsor_choice', 'auto_left', 'auto_right', 'balanced'])->default('balanced');
            $table->integer('level')->default(1)->comment('Tree level (depth)');
            $table->string('path')->nullable()->comment('Path from root (e.g., L-R-L)');
            
            // Volume Tracking
            $table->decimal('personal_volume', 15, 2)->default(0)->comment('Personal sales volume');
            $table->decimal('left_leg_volume', 15, 2)->default(0)->comment('Total left leg volume');
            $table->decimal('right_leg_volume', 15, 2)->default(0)->comment('Total right leg volume');
            $table->decimal('total_team_volume', 15, 2)->default(0)->comment('Total team volume');
            
            // Carry Forward Volumes (for binary matching)
            $table->decimal('left_carry_forward', 15, 2)->default(0)->comment('Left leg carry forward volume');
            $table->decimal('right_carry_forward', 15, 2)->default(0)->comment('Right leg carry forward volume');
            $table->date('carry_forward_date')->nullable()->comment('Last carry forward calculation date');
            
            // Activity Tracking
            $table->boolean('is_active')->default(true)->comment('User is active in the tree');
            $table->date('last_activity_date')->nullable()->comment('Last activity/purchase date');
            $table->date('qualification_date')->nullable()->comment('Date user qualified for commissions');
            $table->string('rank')->default('member')->comment('Current MLM rank');
            
            // Binary Matching History
            $table->decimal('total_binary_earned', 15, 2)->default(0)->comment('Total binary commissions earned');
            $table->decimal('this_period_binary', 15, 2)->default(0)->comment('Binary earnings this period');
            $table->date('last_binary_date')->nullable()->comment('Last binary commission calculation');
            
            // Tree Statistics
            $table->integer('left_leg_count')->default(0)->comment('Number of people in left leg');
            $table->integer('right_leg_count')->default(0)->comment('Number of people in right leg');
            $table->integer('total_downline_count')->default(0)->comment('Total downline count');
            $table->integer('active_left_count')->default(0)->comment('Active members in left leg');
            $table->integer('active_right_count')->default(0)->comment('Active members in right leg');
            
            // Spillover Management
            $table->boolean('accepts_spillover')->default(true)->comment('Accepts spillover placements');
            $table->unsignedBigInteger('spillover_to_id')->nullable()->comment('Where to place spillovers');
            $table->enum('spillover_preference', ['left', 'right', 'balanced'])->default('balanced');
            
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('sponsor_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('parent_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('left_child_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('right_child_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('spillover_to_id')->references('id')->on('users')->onDelete('set null');
            
            // Indexes for performance
            $table->index(['sponsor_id', 'created_at']);
            $table->index(['parent_id', 'position']);
            $table->index(['level', 'is_active']);
            $table->index(['left_leg_volume', 'right_leg_volume']);
            $table->index(['rank', 'is_active']);
            $table->index('path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mlm_binary_tree');
    }
};
