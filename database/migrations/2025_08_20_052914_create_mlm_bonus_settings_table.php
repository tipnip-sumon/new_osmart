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
        Schema::create('mlm_bonus_settings', function (Blueprint $table) {
            $table->id();
            $table->string('setting_key')->unique();
            $table->string('setting_name');
            $table->text('description')->nullable();
            $table->string('setting_type')->default('percentage'); // percentage, fixed, boolean, array
            $table->decimal('value', 10, 2)->default(0);
            $table->decimal('min_value', 10, 2)->nullable();
            $table->decimal('max_value', 10, 2)->nullable();
            $table->string('category'); // sponsor_commission, binary_matching, unilevel, generation, rank, club, daily_cashback
            $table->string('subcategory')->nullable(); // For specific types within categories
            $table->integer('level')->nullable(); // For generation/unilevel levels
            $table->decimal('threshold_amount', 12, 2)->nullable(); // Minimum amount required
            $table->integer('threshold_count')->nullable(); // Minimum count required
            $table->string('calculation_method')->default('percentage'); // percentage, fixed, sliding_scale
            $table->boolean('is_active')->default(true);
            $table->boolean('is_editable')->default(true);
            $table->boolean('requires_kyc')->default(false);
            $table->boolean('requires_rank')->default(false);
            $table->string('rank_required')->nullable();
            $table->json('conditions')->nullable(); // Additional conditions
            $table->json('additional_settings')->nullable();
            $table->text('formula')->nullable(); // Calculation formula
            $table->timestamps();

            // Indexes for better performance
            $table->index(['category', 'is_active']);
            $table->index(['setting_type', 'is_active']);
            $table->index(['level', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mlm_bonus_settings');
    }
};
