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
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
            $table->foreignId('inventory_id')->nullable()->constrained()->onDelete('set null');
            $table->string('type'); // stock_in, stock_out, transfer, adjustment, damaged, expired, return
            $table->integer('quantity');
            $table->integer('remaining_quantity');
            $table->decimal('unit_cost', 10, 2)->default(0);
            $table->decimal('total_cost', 15, 2)->storedAs('quantity * unit_cost');
            $table->string('reference_type')->nullable(); // order, purchase_order, transfer, adjustment
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('batch_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->text('notes')->nullable();
            $table->string('reason')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->json('metadata')->nullable(); // Additional data like supplier info, etc.
            $table->timestamps();

            $table->index(['product_id', 'type']);
            $table->index(['warehouse_id', 'type']);
            $table->index(['reference_type', 'reference_id']);
            $table->index(['created_by']);
            $table->index(['is_approved', 'approved_at']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
