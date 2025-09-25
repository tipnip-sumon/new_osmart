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
        Schema::create('monthly_rank_qualifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('rank_id');
            $table->date('qualification_month');
            
            // Points for the month
            $table->decimal('left_points', 15, 2);
            $table->decimal('right_points', 15, 2);
            $table->decimal('matched_points', 15, 2);
            $table->decimal('matching_bonus', 15, 2);
            
            // Qualification Status
            $table->boolean('qualified')->default(false);
            $table->boolean('salary_paid')->default(false);
            $table->decimal('salary_amount', 15, 2)->default(0);
            $table->timestamp('salary_paid_at')->nullable();
            
            // Processing Status
            $table->boolean('is_processed')->default(false);
            $table->timestamp('processed_at')->nullable();
            
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('rank_id')->references('id')->on('binary_rank_structure')->onDelete('cascade');
            
            // Indexes
            $table->unique(['user_id', 'rank_id', 'qualification_month'], 'monthly_rank_qual_unique');
            $table->index(['qualification_month', 'qualified']);
            $table->index(['salary_paid', 'processed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_rank_qualifications');
    }
};