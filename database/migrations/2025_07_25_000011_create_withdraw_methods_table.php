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
        Schema::create('withdraw_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['bank_transfer', 'paypal', 'stripe', 'wise', 'skrill', 'payoneer', 'cryptocurrency', 'mobile_money', 'check', 'cash_pickup']);
            $table->string('gateway_name')->nullable();
            $table->json('gateway_config')->nullable();
            $table->string('processing_time')->default('1-3 business days');
            $table->decimal('min_amount', 12, 2)->default(0);
            $table->decimal('max_amount', 12, 2)->nullable();
            $table->decimal('fixed_charge', 8, 2)->default(0);
            $table->decimal('percentage_charge', 5, 2)->default(0);
            $table->char('currency', 3)->default('USD');
            $table->json('supported_currencies')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_instant')->default(false);
            $table->boolean('requires_verification')->default(false);
            $table->boolean('auto_approval')->default(false);
            $table->text('instructions')->nullable();
            $table->json('required_fields')->nullable();
            $table->boolean('test_mode')->default(false);
            $table->json('credentials')->nullable();
            $table->string('webhook_url')->nullable();
            $table->string('logo')->nullable();
            $table->integer('sort_order')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['is_active', 'sort_order']);
            $table->index(['type', 'is_active']);
            $table->index(['currency']);
            $table->index(['is_instant']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdraw_methods');
    }
};
