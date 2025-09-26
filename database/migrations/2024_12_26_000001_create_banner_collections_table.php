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
        Schema::create('banner_collections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('button_text')->default('Shop Collection');
            $table->string('button_url')->nullable();
            $table->string('image')->nullable();
            $table->boolean('show_countdown')->default(true);
            $table->timestamp('countdown_end_date')->nullable();
            $table->string('background_color')->default('#f8f9fa');
            $table->string('text_color')->default('#333333');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banner_collections');
    }
};