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
        Schema::create('binary_matchings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Date tracking
            $table->date('match_date');
            $table->string('period', 20)->default('daily'); // daily, weekly, monthly
            
            // Sales volumes
            $table->decimal('left_total_sale', 15, 2)->default(0);
            $table->decimal('right_total_sale', 15, 2)->default(0);
            $table->decimal('total_sale', 15, 2)->default(0);
            
            // Previous carry forward
            $table->decimal('left_carry_forward', 15, 2)->default(0);
            $table->decimal('right_carry_forward', 15, 2)->default(0);
            
            // Current period volumes (including carry)
            $table->decimal('left_current_volume', 15, 2)->default(0);
            $table->decimal('right_current_volume', 15, 2)->default(0);
            
            // Matching calculations
            $table->decimal('matching_volume', 15, 2)->default(0); // Min of left and right
            $table->decimal('matching_percentage', 5, 2)->default(0); // Based on user rank/plan
            $table->decimal('matching_bonus', 15, 2)->default(0); // Calculated bonus
            
            // Slot matching (if applicable)
            $table->integer('slot_match_count')->default(0);
            $table->decimal('slot_match_bonus', 15, 2)->default(0);
            
            // Carry forward for next period
            $table->decimal('left_carry_next', 15, 2)->default(0);
            $table->decimal('right_carry_next', 15, 2)->default(0);
            
            // Capping and limits
            $table->decimal('daily_cap_limit', 15, 2)->nullable();
            $table->decimal('weekly_cap_limit', 15, 2)->nullable();
            $table->decimal('monthly_cap_limit', 15, 2)->nullable();
            $table->decimal('capped_amount', 15, 2)->default(0);
            
            // Status and processing
            $table->enum('status', ['pending', 'processed', 'paid', 'cancelled'])->default('pending');
            $table->boolean('is_processed')->default(false);
            $table->timestamp('processed_at')->nullable();
            
            // References
            $table->string('transaction_ref', 50)->nullable();
            $table->foreignId('carry_from_id')->nullable()->constrained('binary_matchings')->onDelete('set null');
            
            // Additional tracking
            $table->json('calculation_details')->nullable(); // Store detailed calculations
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['user_id', 'match_date']);
            $table->index(['user_id', 'period', 'match_date']);
            $table->index(['status', 'is_processed']);
            $table->index('match_date');
            $table->unique(['user_id', 'match_date', 'period']); // Prevent duplicates
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('binary_matchings');
    }
};
