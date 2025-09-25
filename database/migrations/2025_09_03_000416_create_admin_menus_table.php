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
        Schema::create('admin_menus', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('icon')->nullable();
            $table->string('route')->nullable();
            $table->string('url')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('permission')->nullable();
            $table->string('badge_text')->nullable();
            $table->string('badge_color')->default('primary');
            $table->text('description')->nullable();
            $table->string('target', 10)->default('_self');
            $table->boolean('is_external')->default(false);
            $table->enum('menu_type', ['main', 'sidebar', 'both'])->default('both');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('parent_id')->references('id')->on('admin_menus')->onDelete('cascade');
            $table->index(['parent_id', 'sort_order']);
            $table->index(['is_active', 'parent_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_menus');
    }
};
