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
        Schema::create('inventory_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained()->onDelete('cascade');
            $table->string('type'); // low_stock, out_of_stock, overstock, expiring_soon, expired, price_change, movement_anomaly
            $table->string('priority'); // low, medium, high, critical
            $table->text('message');
            $table->json('data')->nullable(); // Additional alert data
            $table->boolean('is_resolved')->default(false);
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();

            $table->index(['inventory_id', 'type']);
            $table->index(['type', 'priority']);
            $table->index(['is_resolved']);
            $table->index(['priority', 'created_at']);
            $table->index(['notified_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_alerts');
    }
};
