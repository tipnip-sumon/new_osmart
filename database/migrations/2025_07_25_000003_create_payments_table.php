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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['credit_card', 'debit_card', 'bank_transfer', 'digital_wallet', 'cryptocurrency', 'cash_on_delivery']);
            $table->string('gateway_name')->nullable();
            $table->json('gateway_config')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->integer('sort_order')->default(0);
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->text('instructions')->nullable();
            $table->decimal('processing_fee', 8, 2)->default(0);
            $table->enum('fee_type', ['fixed', 'percentage'])->default('fixed');
            $table->decimal('min_amount', 12, 2)->nullable();
            $table->decimal('max_amount', 12, 2)->nullable();
            $table->json('supported_currencies')->nullable();
            $table->boolean('test_mode')->default(false);
            $table->json('credentials')->nullable();
            $table->string('webhook_url')->nullable();
            $table->string('success_url')->nullable();
            $table->string('cancel_url')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['is_active', 'sort_order']);
            $table->index(['type', 'is_active']);
            $table->index(['is_default']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
