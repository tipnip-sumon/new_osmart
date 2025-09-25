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
        Schema::create('generation_incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('from_user_id')->constrained('users')->onDelete('cascade');
            $table->integer('generation_level'); // 1-20
            $table->decimal('points', 10, 2); // Points earned (0.5, 1, 2, etc.)
            $table->decimal('amount', 15, 2); // TK amount (points * 6)
            $table->decimal('business_volume', 15, 2); // Original business that generated this income
            $table->enum('status', ['pending', 'paid', 'invalid'])->default('pending');
            $table->enum('payment_reason', ['rank_achieved', 'upgrade_completed', 'first_rank_achieved'])->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->text('remarks')->nullable();
            $table->json('meta_data')->nullable(); // Store additional data like rank info, upgrade details
            $table->timestamps();

            // Indexes for better performance
            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'generation_level']);
            $table->index(['from_user_id']);
            $table->index(['status', 'paid_at']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generation_incomes');
    }
};
