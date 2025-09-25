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
        Schema::create('general_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('site_name', 191)->default('IBL BD')->nullable(false);
            $table->string('cur_text', 191)->default('BDT')->nullable(false);
            $table->string('cur_sym', 191)->default('৳')->nullable(false);
            $table->string('email_from', 191)->nullable();
            $table->text('email_template')->nullable();
            $table->text('sms_body')->nullable();
            $table->string('sms_from', 191)->nullable();
            $table->string('base_color', 191)->default('#007bff')->nullable(false);
            $table->string('secondary_color', 191)->default('#6c757d')->nullable(false);
            $table->text('logo')->nullable();
            $table->string('loader_image', 191)->nullable();
            $table->string('admin_logo', 191)->nullable();
            $table->text('header_content')->nullable();
            $table->json('header_scripts')->nullable();
            $table->string('header_background_color', 191)->default('#ffffff')->nullable(false);
            $table->string('header_text_color', 191)->default('#000000')->nullable(false);
            $table->text('footer_content')->nullable();
            $table->json('footer_scripts')->nullable();
            $table->string('footer_background_color', 191)->default('#343a40')->nullable(false);
            $table->string('footer_text_color', 191)->default('#ffffff')->nullable(false);
            $table->text('copyright_text')->nullable();
            $table->string('meta_title', 191)->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_image')->nullable();
            $table->json('social_media_links')->nullable();
            $table->string('contact_email', 191)->nullable();
            $table->string('contact_phone', 191)->nullable();
            $table->text('contact_address')->nullable();
            $table->text('home_page_content')->nullable();
            $table->text('about_us_content')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->text('privacy_policy')->nullable();
            $table->longText('custom_css')->nullable();
            $table->longText('custom_js')->nullable();
            $table->json('notification_settings')->nullable();
            $table->json('theme_settings')->nullable();
            $table->json('widget_settings')->nullable();
            $table->text('maintenance_message')->nullable();
            $table->string('maintenance_image', 191)->nullable();
            $table->json('api_settings')->nullable();
            $table->string('timezone', 191)->default('UTC')->nullable(false);
            $table->string('date_format', 191)->default('Y-m-d')->nullable(false);
            $table->string('time_format', 191)->default('H:i:s')->nullable(false);
            $table->json('file_upload_settings')->nullable();
            $table->json('security_settings')->nullable();
            $table->json('transfer_conditions')->nullable();
            $table->json('withdrawal_conditions')->nullable();
            $table->json('referral_benefits_settings')->nullable();
            $table->text('icon')->nullable();
            $table->text('favicon')->nullable();
            $table->json('mail_config')->nullable();
            $table->json('sms_config')->nullable();
            $table->json('global_shortcodes')->nullable();
            $table->json('kv')->nullable();
            $table->json('ev')->nullable();
            $table->json('en')->nullable();
            $table->json('sv')->nullable();
            $table->json('sn')->nullable();
            $table->boolean('force_ssl')->default(0)->nullable(false);
            $table->boolean('maintenance_mode')->default(0)->nullable(false);
            $table->boolean('secure_password')->default(0)->nullable(false);
            $table->boolean('agree')->default(0)->nullable(false);
            $table->boolean('registration')->default(1)->nullable(false);
            $table->string('active_template', 191)->default('basic')->nullable(false);
            $table->json('system_info')->nullable();
            $table->decimal('deposit_commission', 8, 2)->default(0.00)->nullable(false);
            $table->decimal('investment_commission', 8, 2)->default(0.00)->nullable(false);
            $table->decimal('invest_return_commission', 8, 2)->default(0.00)->nullable(false);
            $table->decimal('signup_bonus_amount', 8, 2)->default(0.00)->nullable(false);
            $table->text('signup_bonus_control')->nullable();
            $table->text('promotional_tool')->nullable();
            $table->json('firebase_config')->nullable();
            $table->json('firebase_template')->nullable();
            $table->boolean('push_notify')->default(0)->nullable(false);
            $table->text('off_day')->nullable();
            $table->timestamp('last_cron')->nullable();
            $table->boolean('b_transfer')->default(0)->nullable(false);
            $table->decimal('f_charge', 8, 2)->default(0.00)->nullable(false);
            $table->decimal('p_charge', 8, 2)->default(0.00)->nullable(false);
            $table->boolean('holiday_withdraw')->default(0)->nullable(false);
            $table->boolean('language_switch')->default(0)->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_settings');
    }
};
