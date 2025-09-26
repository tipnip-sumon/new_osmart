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
        Schema::table('general_settings', function (Blueprint $table) {
            // Cart checkout terms and conditions settings
            $table->boolean('cart_terms_enabled')->default(true)->after('agree');
            $table->boolean('cart_terms_mandatory')->default(true)->after('cart_terms_enabled');
            $table->text('cart_terms_text')->nullable()->after('cart_terms_mandatory');
            $table->string('cart_terms_link', 500)->nullable()->after('cart_terms_text');
            $table->string('cart_terms_link_text', 191)->default('terms and conditions')->after('cart_terms_link');
            $table->boolean('cart_privacy_enabled')->default(false)->after('cart_terms_link_text');
            $table->boolean('cart_privacy_mandatory')->default(false)->after('cart_privacy_enabled');
            $table->text('cart_privacy_text')->nullable()->after('cart_privacy_mandatory');
            $table->string('cart_privacy_link', 500)->nullable()->after('cart_privacy_text');
            $table->string('cart_privacy_link_text', 191)->default('privacy policy')->after('cart_privacy_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn([
                'cart_terms_enabled',
                'cart_terms_mandatory',
                'cart_terms_text',
                'cart_terms_link',
                'cart_terms_link_text',
                'cart_privacy_enabled',
                'cart_privacy_mandatory',
                'cart_privacy_text',
                'cart_privacy_link',
                'cart_privacy_link_text'
            ]);
        });
    }
};
