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
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['credit', 'debit'])->default('credit');
            $table->decimal('amount', 10, 2);
            $table->text('description');
            $table->string('reference_type')->nullable(); // package_activation, product_purchase, transfer_in, etc.
            $table->unsignedBigInteger('reference_id')->nullable(); // related record ID
            $table->enum('status', ['pending', 'completed', 'failed'])->default('completed');
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'type']);
            $table->index(['user_id', 'created_at']);
            $table->index('reference_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_transactions');
    }
};
