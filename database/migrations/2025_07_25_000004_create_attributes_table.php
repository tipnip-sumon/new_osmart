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
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['text', 'textarea', 'number', 'decimal', 'boolean', 'date', 'datetime', 'select', 'multiselect', 'radio', 'checkbox', 'color', 'image', 'file', 'url', 'email']);
            $table->string('display_name')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_required')->default(false);
            $table->boolean('is_filterable')->default(false);
            $table->boolean('is_variation')->default(false);
            $table->boolean('is_global')->default(false);
            $table->integer('sort_order')->default(0);
            $table->enum('status', ['active', 'inactive', 'draft'])->default('active');
            $table->json('validation_rules')->nullable();
            $table->text('default_value')->nullable();
            $table->string('placeholder')->nullable();
            $table->text('help_text')->nullable();
            $table->boolean('admin_only')->default(false);
            $table->string('frontend_type')->nullable();
            $table->json('options')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['status', 'sort_order']);
            $table->index(['type', 'status']);
            $table->index(['is_global', 'status']);
            $table->index(['is_filterable']);
            $table->index(['is_variation']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};
