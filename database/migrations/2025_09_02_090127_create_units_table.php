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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('symbol', 10);
            $table->enum('type', ['weight', 'length', 'volume', 'area', 'time', 'quantity', 'temperature', 'other'])->default('quantity');
            $table->text('description')->nullable();
            $table->decimal('base_factor', 10, 6)->default(1.0); // Conversion factor to base unit
            $table->unsignedBigInteger('base_unit_id')->nullable(); // Reference to base unit
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false); // Default unit for its type
            $table->integer('sort_order')->default(0);
            $table->json('metadata')->nullable(); // For storing additional data
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['name', 'type']);
            $table->unique(['symbol', 'type']);
            $table->index(['type', 'is_active']);
            $table->index(['is_default', 'type']);
            $table->foreign('base_unit_id')->references('id')->on('units')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
