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
        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coupon_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->decimal('discount_amount', 10, 2);
            $table->decimal('order_amount', 10, 2);
            $table->string('user_ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('order_details')->nullable(); // Store order items, quantities, etc.
            $table->timestamp('used_at');
            $table->timestamps();

            // Indexes
            $table->index(['coupon_id', 'user_id']);
            $table->index(['coupon_id', 'used_at']);
            $table->index(['user_id', 'used_at']);
            $table->index('order_id');
            $table->index('used_at');

            // Foreign keys
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // Note: order_id foreign key will be added when orders table is created
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_usages');
    }
};
