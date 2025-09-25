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
        Schema::table('products', function (Blueprint $table) {
            // MLM Commission Settings
            if (!Schema::hasColumn('products', 'generates_commission')) {
                $table->boolean('generates_commission')->default(true)->comment('Product generates MLM commissions');
            }
            if (!Schema::hasColumn('products', 'is_starter_kit')) {
                $table->boolean('is_starter_kit')->default(false)->comment('Is this a starter kit product');
            }
            if (!Schema::hasColumn('products', 'starter_kit_tier')) {
                $table->string('starter_kit_tier')->nullable()->comment('Starter kit tier level');
            }
            
            // Point System
            if (!Schema::hasColumn('products', 'pv_points')) {
                $table->decimal('pv_points', 10, 2)->default(0)->nullable()->comment('Personal Volume points');
            }
            if (!Schema::hasColumn('products', 'bv_points')) {
                $table->decimal('bv_points', 10, 2)->default(0)->nullable()->comment('Business Volume points');
            }
            
            // Commission Rates
            if (!Schema::hasColumn('products', 'direct_commission_rate')) {
                $table->decimal('direct_commission_rate', 5, 2)->nullable()->comment('Direct commission rate percentage');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'generates_commission',
                'is_starter_kit',
                'starter_kit_tier',
                'pv_points',
                'bv_points',
                'direct_commission_rate'
            ]);
        });
    }
};
