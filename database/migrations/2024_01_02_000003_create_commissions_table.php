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
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Who earned the commission
            $table->foreignId('referred_user_id')->nullable()->constrained('users')->onDelete('set null'); // Who was referred
            $table->unsignedBigInteger('order_id')->nullable(); // Related order (foreign key will be added when orders table exists)
            $table->string('commission_type'); // referral, sales, bonus, tier_bonus
            $table->integer('level')->default(1); // Commission level (1st, 2nd, 3rd level referral)
            $table->decimal('order_amount', 15, 2)->default(0); // Original order amount
            $table->decimal('commission_rate', 5, 4); // Rate applied (e.g., 0.05 for 5%)
            $table->decimal('commission_amount', 15, 2); // Calculated commission
            $table->enum('status', ['pending', 'approved', 'paid', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('earned_at');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['referred_user_id']);
            $table->index(['order_id']);
            $table->index(['commission_type', 'level']);
            $table->index(['earned_at']);
            $table->index(['status', 'approved_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};
