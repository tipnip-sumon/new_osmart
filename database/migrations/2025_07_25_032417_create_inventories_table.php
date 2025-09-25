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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->integer('reserved_quantity')->default(0);
            $table->integer('available_quantity')->storedAs('quantity - reserved_quantity');
            $table->integer('min_stock_level')->default(10);
            $table->integer('max_stock_level')->default(1000);
            $table->integer('reorder_point')->default(20);
            $table->integer('reorder_quantity')->default(100);
            $table->decimal('cost_per_unit', 10, 2)->default(0);
            $table->decimal('total_value', 15, 2)->storedAs('quantity * cost_per_unit');
            $table->string('location')->nullable(); // Rack/Bin location
            $table->string('batch_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->date('manufacturing_date')->nullable();
            $table->string('condition')->default('new'); // new, used, damaged, expired
            $table->text('notes')->nullable();
            $table->timestamp('last_counted_at')->nullable();
            $table->integer('count_variance')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['product_id', 'warehouse_id']);
            $table->index(['warehouse_id', 'quantity']);
            $table->index(['product_id', 'is_active']);
            $table->index(['expiry_date']);
            $table->index(['condition']);
            $table->index(['last_counted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
