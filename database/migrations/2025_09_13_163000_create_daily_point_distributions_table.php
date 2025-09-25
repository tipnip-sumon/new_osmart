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
        // Only create table if it doesn't exist
        if (!Schema::hasTable('daily_point_distributions')) {
            Schema::create('daily_point_distributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('distribution_date');
            $table->decimal('points_acquired', 15, 2); // Points earned today
            $table->decimal('sponsor_bonus', 15, 2)->default(0); // Direct sponsor bonus
            $table->decimal('generation_bonus', 15, 2)->default(0); // Total generation bonus
            $table->json('generation_details')->nullable(); // Level-wise breakdown
            $table->enum('acquisition_type', ['product_purchase', 'direct_purchase', 'external_addition', 'plan_purchase']); // How points were acquired
            $table->decimal('purchase_amount', 15, 2)->nullable(); // Original purchase amount
            $table->string('source')->nullable(); // Product/Plan ID or 'direct_purchase'
            $table->boolean('is_processed')->default(false); // Distribution completed
            $table->timestamp('processed_at')->nullable(); // When distribution was completed
            $table->text('processing_notes')->nullable(); // Any processing notes
            $table->timestamps();
            
            // Unique constraint: one distribution per user per day
            $table->unique(['user_id', 'distribution_date']);
            
            // Indexes for performance
            $table->index(['distribution_date', 'is_processed']);
            $table->index(['user_id', 'distribution_date']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_point_distributions');
    }
};
