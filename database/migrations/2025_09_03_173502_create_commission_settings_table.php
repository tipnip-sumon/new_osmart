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
        Schema::create('commission_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // sponsor, matching, generation, rank, club
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->enum('type', ['sponsor', 'matching', 'generation', 'rank', 'club', 'binary', 'leadership']);
            $table->enum('calculation_type', ['fixed', 'percentage']);
            $table->decimal('value', 10, 2); // Amount or percentage
            $table->json('conditions')->nullable(); // Store conditions as JSON
            $table->json('levels')->nullable(); // For multi-level commissions
            $table->decimal('min_qualification', 10, 2)->default(0);
            $table->decimal('max_payout', 10, 2)->nullable();
            $table->integer('max_levels')->default(1);
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0); // Processing priority
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_settings');
    }
};
