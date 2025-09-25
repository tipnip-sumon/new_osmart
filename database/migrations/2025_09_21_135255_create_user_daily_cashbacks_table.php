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
        Schema::create('user_daily_cashbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->decimal('cashback_amount', 8, 2);
            $table->date('cashback_date');
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->text('remarks')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['user_id', 'cashback_date']);
            $table->index(['plan_id', 'cashback_date']);
            $table->index('status');
            
            // Unique constraint to prevent duplicate cashbacks for same user on same day
            $table->unique(['user_id', 'plan_id', 'cashback_date'], 'unique_user_plan_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_daily_cashbacks');
    }
};
