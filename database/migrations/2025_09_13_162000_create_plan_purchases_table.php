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
        if (!Schema::hasTable('plan_purchases')) {
            Schema::create('plan_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->string('transaction_id')->unique(); // Unique transaction reference
            
            // Purchase details
            $table->string('plan_name'); // Store plan name at time of purchase
            $table->decimal('plan_price', 15, 2); // Price paid for the plan
            $table->decimal('points_received', 15, 2); // Points received from this plan
            $table->decimal('point_value_rate', 8, 2)->default(6.00); // Rate: 1 point = X taka
            
            // Payment information
            $table->enum('payment_method', ['deposit_wallet', 'points_wallet', 'bonus_wallet'])->default('deposit_wallet');
            $table->decimal('wallet_balance_before', 15, 2)->nullable(); // Balance before purchase
            $table->decimal('wallet_balance_after', 15, 2)->nullable(); // Balance after purchase
            
            // Commission tracking
            $table->decimal('sponsor_bonus_given', 15, 2)->default(0); // Bonus given to direct sponsor
            $table->decimal('generation_bonus_given', 15, 2)->default(0); // Total generation bonuses given
            $table->json('commission_breakdown')->nullable(); // Level-wise commission details
            
            // Plan features at time of purchase
            $table->json('plan_features')->nullable(); // Features available at purchase time
            $table->text('plan_description')->nullable(); // Description at purchase time
            $table->string('plan_category')->nullable(); // Category at purchase time
            
            // Purchase status and tracking
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->timestamp('purchased_at'); // When the purchase was made
            $table->timestamp('processed_at')->nullable(); // When commissions were distributed
            $table->text('processing_notes')->nullable(); // Any processing notes or errors
            
            // Validation and security
            $table->boolean('is_validated')->default(false); // Whether purchase is validated
            $table->string('validation_hash')->nullable(); // Security hash for validation
            $table->ipAddress('purchase_ip')->nullable(); // IP address of purchaser
            $table->string('user_agent')->nullable(); // User agent of purchaser
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['user_id', 'purchased_at']);
            $table->index(['plan_id', 'purchased_at']);
            $table->index(['status', 'purchased_at']);
            $table->index('transaction_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_purchases');
    }
};
