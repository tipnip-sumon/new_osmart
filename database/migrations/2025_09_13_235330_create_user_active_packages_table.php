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
        Schema::create('user_active_packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('plan_id');
            $table->string('package_tier'); // '100', '200', '500', etc
            $table->decimal('amount_invested', 15, 2);
            $table->integer('points_allocated');
            $table->integer('points_remaining');
            $table->integer('points_used_for_payout')->default(0);
            $table->decimal('total_payout_received', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('activated_at');
            $table->timestamp('last_payout_at')->nullable();
            $table->timestamp('next_payout_eligible_at')->nullable();
            $table->json('package_details')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
            
            // Ensure a user can't have duplicate packages of same tier
            $table->unique(['user_id', 'package_tier'], 'unique_user_package_tier');
            
            $table->index(['user_id', 'is_active']);
            $table->index(['user_id', 'package_tier']);
            $table->index('next_payout_eligible_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_active_packages');
    }
};
