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
        Schema::create('popups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('title');
            $table->string('description')->nullable();
            $table->longText('content');
            
            // Updated enum values to match form
            $table->enum('type', ['newsletter', 'promotion', 'announcement', 'exit_intent', 'cookie_consent', 'age_verification', 'warning', 'info', 'promotional', 'discount', 'survey', 'social_proof']);
            $table->enum('trigger_type', ['immediate', 'delay', 'scroll', 'exit_intent', 'page_visit', 'time_delay', 'scroll_percentage', 'page_views', 'return_visitor', 'inactivity']);
            $table->integer('trigger_value')->nullable();
            
            // Position enum to match form
            $table->enum('position', ['center', 'top', 'bottom', 'top_left', 'top_right', 'bottom_left', 'bottom_right', 'top_center', 'bottom_center', 'fullscreen'])->default('center');
            $table->enum('size', ['small', 'medium', 'large', 'extra_large', 'custom'])->default('medium');
            
            // Add modal_size field that form expects
            $table->enum('modal_size', ['small', 'medium', 'large', 'fullscreen'])->default('medium');
            
            // Animation field that form expects
            $table->enum('animation', ['fade', 'slide_up', 'slide_down', 'slide_left', 'slide_right', 'zoom'])->default('fade');
            
            // Frequency field that form expects
            $table->enum('frequency', ['always', 'once_per_session', 'once_per_day', 'once_per_week', 'once_per_month'])->default('always');
            
            // Priority field
            $table->integer('priority')->default(1);
            
            $table->enum('status', ['active', 'inactive', 'scheduled', 'expired'])->default('active');
            $table->boolean('is_active')->default(true);
            $table->boolean('show_once')->default(false);
            
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            
            // JSON fields for targeting
            $table->json('target_pages')->nullable();
            $table->json('exclude_pages')->nullable();
            $table->json('target_audience')->nullable();
            $table->json('device_targeting')->nullable();
            $table->json('target_devices')->nullable();
            $table->json('target_users')->nullable();
            
            // Display and analytics fields
            $table->integer('frequency_limit')->default(1);
            $table->integer('delay_seconds')->default(0);
            $table->unsignedBigInteger('show_count')->default(0);
            $table->integer('max_displays')->nullable();
            $table->string('conversion_goal')->nullable();
            $table->integer('displays')->default(0);
            $table->integer('conversions')->default(0);
            $table->integer('clicks')->default(0);
            $table->unsignedBigInteger('click_count')->default(0);
            $table->unsignedBigInteger('conversion_count')->default(0);
            $table->unsignedBigInteger('close_count')->default(0);
            
            // Color and styling fields
            $table->string('background_color', 7)->default('#ffffff');
            $table->string('text_color', 7)->default('#333333');
            $table->string('button_color', 7)->default('#007bff');
            $table->string('border_color', 7)->nullable();
            $table->string('overlay_color')->default('rgba(0,0,0,0.5)');
            $table->decimal('overlay_opacity', 3, 2)->default(0.5);
            
            // Button configuration
            $table->string('button_text')->default('Close');
            $table->string('button_url')->nullable();
            $table->boolean('close_button')->default(true);
            $table->boolean('overlay')->default(true);
            
            // Animation fields
            $table->string('animation_in')->default('fadeIn');
            $table->string('animation_out')->default('fadeOut');
            
            // Display behavior
            $table->boolean('show_close_button')->default(true);
            $table->integer('auto_close')->nullable();
            $table->integer('close_delay')->nullable();
            $table->boolean('sound_enabled')->default(false);
            $table->integer('cookie_lifetime')->default(7);
            
            // Image fields
            $table->string('image')->nullable();
            $table->json('image_data')->nullable();
            
            // Metadata
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['status', 'start_date', 'end_date']);
            $table->index(['type', 'status']);
            $table->index(['trigger_type']);
            $table->index(['is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('popups');
    }
};
