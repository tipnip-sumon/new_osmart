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
        Schema::create('mlm_commissions', function (Blueprint $table) {
            $table->id();
            $table->string('reference_id')->unique(); // Unique reference for tracking
            
            // User information
            $table->unsignedBigInteger('user_id'); // Who earned the commission
            $table->unsignedBigInteger('from_user_id'); // Who generated the commission
            
            // Commission details
            $table->enum('commission_type', [
                'direct_sales', 
                'binary_bonus', 
                'matching_bonus', 
                'leadership_bonus', 
                'rank_bonus',
                'retail_profit',
                'team_volume',
                'fast_start'
            ]);
            $table->decimal('amount', 15, 2); // Commission amount
            $table->decimal('percentage', 5, 2)->nullable(); // Commission percentage used
            $table->decimal('volume', 15, 2)->nullable(); // Volume that generated commission
            
            // Source information
            $table->unsignedBigInteger('product_id')->nullable(); // Product that generated commission
            $table->unsignedBigInteger('order_id')->nullable(); // Order that generated commission
            $table->string('source_type')->nullable(); // Additional source type
            $table->unsignedBigInteger('source_id')->nullable(); // Additional source ID
            
            // Processing information
            $table->enum('status', ['pending', 'approved', 'paid', 'cancelled'])->default('pending');
            $table->timestamp('earned_at'); // When commission was earned
            $table->timestamp('approved_at')->nullable(); // When commission was approved
            $table->timestamp('paid_at')->nullable(); // When commission was paid
            $table->unsignedBigInteger('calculated_by')->nullable(); // Admin who calculated
            $table->unsignedBigInteger('approved_by')->nullable(); // Admin who approved
            $table->unsignedBigInteger('paid_by')->nullable(); // Admin who marked as paid
            
            // Payment information
            $table->string('payment_method')->nullable(); // How commission was paid
            $table->string('payment_reference')->nullable(); // Payment reference/transaction ID
            $table->text('payment_notes')->nullable(); // Payment notes
            
            // MLM specific
            $table->integer('generation_level')->nullable(); // For multi-level commissions
            $table->string('binary_leg')->nullable(); // left/right for binary commissions
            $table->decimal('left_volume', 15, 2)->nullable(); // Left leg volume at time of calculation
            $table->decimal('right_volume', 15, 2)->nullable(); // Right leg volume at time of calculation
            $table->decimal('carry_forward', 15, 2)->nullable(); // Carry forward volume
            
            // Tracking
            $table->text('calculation_details')->nullable(); // JSON with calculation breakdown
            $table->text('notes')->nullable(); // Additional notes
            $table->boolean('is_holdback')->default(false); // If commission is on holdback
            $table->date('holdback_release_date')->nullable(); // When holdback is released
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index('user_id', 'mlm_comm_user_idx');
            $table->index('from_user_id', 'mlm_comm_from_user_idx');
            $table->index('commission_type', 'mlm_comm_type_idx');
            $table->index('status', 'mlm_comm_status_idx');
            $table->index('earned_at', 'mlm_comm_earned_idx');
            $table->index(['user_id', 'commission_type'], 'mlm_comm_user_type_idx');
            $table->index(['user_id', 'status'], 'mlm_comm_user_status_idx');
            $table->index(['user_id', 'earned_at'], 'mlm_comm_user_date_idx');
            $table->index('product_id', 'mlm_comm_product_idx');
            $table->index('order_id', 'mlm_comm_order_idx');
            
            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // Note: order_id foreign key will be added when orders table is created
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('from_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('calculated_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('paid_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mlm_commissions');
    }
};
