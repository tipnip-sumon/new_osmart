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
        Schema::create('user_package_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('plan_id');
            $table->string('action_type'); // 'purchase', 'upgrade', 'payout', 'point_invalidation'
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->integer('points_acquired')->default(0);
            $table->integer('points_before')->default(0);
            $table->integer('points_after')->default(0);
            $table->integer('active_points_before')->default(0);
            $table->integer('active_points_after')->default(0);
            $table->integer('reserve_points_before')->default(0);
            $table->integer('reserve_points_after')->default(0);
            $table->decimal('payout_amount', 15, 2)->nullable();
            $table->string('purchase_source')->nullable(); // 'product', 'direct', 'upgrade'
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('package_tier')->nullable(); // '100', '200', '500', etc
            $table->json('package_details')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('payout_processed_at')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            
            $table->index(['user_id', 'action_type']);
            $table->index(['user_id', 'is_active']);
            $table->index(['plan_id', 'action_type']);
            $table->index('activated_at');
            $table->index('payout_processed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_package_histories');
    }
};
