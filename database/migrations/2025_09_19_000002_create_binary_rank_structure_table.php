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
        Schema::create('binary_rank_structure', function (Blueprint $table) {
            $table->id();
            $table->integer('sl_no');
            $table->string('rank_name');
            $table->decimal('left_points', 15, 2);
            $table->decimal('right_points', 15, 2);
            $table->decimal('matching_tk', 15, 2); // 1 Point = 6 TK
            $table->decimal('point_10_percent', 15, 2);
            
            // Rewards
            $table->string('tour')->nullable();
            $table->string('gift')->nullable();
            $table->decimal('salary', 15, 2);
            $table->integer('duration_months');
            
            // Monthly Conditions
            $table->decimal('monthly_left_points', 15, 2);
            $table->decimal('monthly_right_points', 15, 2);
            $table->decimal('monthly_matching_tk', 15, 2);
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique('rank_name');
            $table->index('sl_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('binary_rank_structure');
    }
};