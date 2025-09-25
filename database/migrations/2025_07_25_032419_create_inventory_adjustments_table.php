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
        Schema::create('inventory_adjustments', function (Blueprint $table) {
            $table->id();
            $table->string('adjustment_number')->unique();
            $table->foreignId('inventory_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
            $table->string('type'); // increase, decrease, count_correction, damage, expiry
            $table->integer('quantity_before');
            $table->integer('quantity_after');
            $table->integer('adjustment_quantity')->storedAs('quantity_after - quantity_before');
            $table->decimal('unit_cost', 10, 2)->default(0);
            $table->decimal('cost_impact', 15, 2)->storedAs('(quantity_after - quantity_before) * unit_cost');
            $table->string('reason'); // damaged, expired, theft, count_discrepancy, supplier_return, etc.
            $table->text('notes')->nullable();
            $table->string('batch_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('requested_at');
            $table->timestamp('approved_at')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected, completed
            $table->json('attachments')->nullable(); // Photos, documents
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            $table->index(['inventory_id', 'status']);
            $table->index(['product_id', 'type']);
            $table->index(['warehouse_id', 'status']);
            $table->index(['requested_by']);
            $table->index(['approved_by']);
            $table->index(['status', 'requested_at']);
            $table->index(['reason']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_adjustments');
    }
};
