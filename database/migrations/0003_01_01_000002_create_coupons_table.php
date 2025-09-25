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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed', 'free_shipping', 'buy_x_get_y', 'bulk_discount']);
            $table->decimal('value', 10, 2);
            $table->decimal('minimum_amount', 10, 2)->nullable();
            $table->decimal('maximum_discount', 10, 2)->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('usage_limit_per_user')->nullable();
            $table->integer('used_count')->default(0);
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('auto_apply')->default(false);
            $table->boolean('free_shipping')->default(false);
            $table->boolean('stackable')->default(false);
            $table->boolean('first_order_only')->default(false);
            $table->integer('buy_quantity')->nullable(); // For buy X get Y offers
            $table->integer('get_quantity')->nullable(); // For buy X get Y offers
            $table->integer('bulk_min_quantity')->nullable(); // For bulk discounts
            $table->integer('priority')->default(5); // 1-10, higher number = higher priority
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->json('applicable_products')->nullable(); // Product IDs
            $table->json('applicable_categories')->nullable(); // Category IDs
            $table->json('exclude_products')->nullable(); // Product IDs to exclude
            $table->json('exclude_categories')->nullable(); // Category IDs to exclude
            $table->json('user_restrictions')->nullable(); // Specific user IDs
            $table->json('country_restrictions')->nullable(); // Country codes
            $table->text('terms_conditions')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['code', 'is_active']);
            $table->index(['type', 'is_active']);
            $table->index(['vendor_id', 'is_active']);
            $table->index(['start_date', 'end_date']);
            $table->index(['auto_apply', 'is_active']);
            $table->index('priority');

            // Foreign keys
            $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
