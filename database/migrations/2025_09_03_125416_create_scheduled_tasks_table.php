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
        Schema::create('scheduled_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('command')->nullable();
            $table->string('schedule'); // Cron expression or human readable
            $table->string('cron_expression')->nullable();
            $table->timestamp('last_run')->nullable();
            $table->timestamp('next_run')->nullable();
            $table->enum('status', ['active', 'paused', 'disabled'])->default('active');
            $table->integer('run_count')->default(0);
            $table->integer('failure_count')->default(0);
            $table->text('last_output')->nullable();
            $table->text('last_error')->nullable();
            $table->integer('timeout')->default(300); // seconds
            $table->boolean('prevent_overlapping')->default(true);
            $table->json('metadata')->nullable(); // Additional task-specific data
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            
            $table->index(['status', 'next_run']);
            $table->index('last_run');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduled_tasks');
    }
};
