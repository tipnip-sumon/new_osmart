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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type', 50)->index(); // rank_achievement, salary_payment, commission, etc.
            $table->string('category', 30)->default('general')->index(); // success, warning, info, danger
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Additional metadata
            $table->string('icon', 50)->nullable(); // Icon class for display
            $table->string('color', 20)->default('primary'); // Bootstrap color class
            $table->string('action_url')->nullable(); // URL to redirect when clicked
            $table->string('action_text', 50)->nullable(); // Button text for action
            $table->boolean('is_read')->default(false)->index();
            $table->boolean('is_important')->default(false)->index(); // High priority notifications
            $table->timestamp('read_at')->nullable();
            $table->timestamp('expires_at')->nullable(); // Auto-delete after this date
            $table->string('reference_type')->nullable(); // Model reference (e.g., 'binary_rank_achievement')
            $table->unsignedBigInteger('reference_id')->nullable(); // Model ID reference
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['user_id', 'is_read']);
            $table->index(['user_id', 'created_at']);
            $table->index(['type', 'created_at']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
