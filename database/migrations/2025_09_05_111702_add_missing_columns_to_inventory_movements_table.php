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
        Schema::table('inventory_movements', function (Blueprint $table) {
            // Add missing columns that are referenced in the model
            $table->integer('previous_quantity')->nullable()->after('unit_cost');
            $table->integer('new_quantity')->nullable()->after('previous_quantity');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null')->after('reason');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null')->after('user_id');
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null')->after('order_id');
            $table->timestamp('movement_date')->nullable()->after('metadata');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->dropColumn([
                'previous_quantity',
                'new_quantity', 
                'user_id',
                'order_id',
                'supplier_id',
                'movement_date'
            ]);
        });
    }
};
