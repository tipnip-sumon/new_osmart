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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('transaction_id')->unique();
            $table->string('payment_method');
            $table->string('gateway')->nullable();
            $table->decimal('amount', 12, 2);
            $table->decimal('fee', 8, 2)->default(0);
            $table->decimal('net_amount', 12, 2);
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled', 'refunded', 'approved', 'rejected'])->default('pending');
            $table->enum('type', ['payment', 'refund', 'partial_refund', 'fund_addition'])->default('payment');
            $table->string('currency', 3)->default('BDT');
            $table->json('gateway_response')->nullable();
            $table->string('gateway_transaction_id')->nullable();
            $table->text('notes')->nullable();
            $table->string('sender_number')->nullable(); // For mobile payments
            $table->string('receipt_path')->nullable(); // For receipt uploads
            $table->text('description')->nullable(); // Transaction description
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->foreignId('refunded_by')->nullable()->constrained('users');
            $table->text('failure_reason')->nullable();
            $table->text('refund_reason')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['order_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['transaction_id']);
            $table->index(['gateway_transaction_id']);
            $table->index(['status', 'created_at']);
            $table->index(['payment_method']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
