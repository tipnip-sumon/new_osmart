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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('image');
            $table->string('mobile_image')->nullable();
            $table->string('link_url')->nullable();
            $table->string('link_text')->nullable();
            $table->enum('position', ['header', 'hero', 'sidebar', 'footer', 'popup', 'category_top', 'category_bottom', 'product_detail', 'checkout', 'floating']);
            $table->enum('type', ['promotional', 'informational', 'seasonal', 'product_showcase', 'newsletter', 'social_media', 'announcement']);
            $table->enum('status', ['active', 'inactive', 'scheduled', 'expired'])->default('active');
            $table->integer('sort_order')->default(0);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->json('target_audience')->nullable();
            $table->json('device_targeting')->nullable();
            $table->unsignedBigInteger('click_count')->default(0);
            $table->unsignedBigInteger('impression_count')->default(0);
            $table->unsignedBigInteger('conversion_count')->default(0);
            $table->string('background_color', 7)->nullable();
            $table->string('text_color', 7)->nullable();
            $table->string('button_color', 7)->nullable();
            $table->string('button_text_color', 7)->nullable();
            $table->decimal('overlay_opacity', 3, 2)->default(0.5);
            $table->string('animation_type')->default('none');
            $table->integer('display_duration')->nullable();
            $table->boolean('auto_close')->default(false);
            $table->boolean('show_close_button')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['status', 'position', 'sort_order']);
            $table->index(['type', 'status']);
            $table->index(['start_date', 'end_date']);
            $table->index(['click_count']);
            $table->index(['impression_count']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
