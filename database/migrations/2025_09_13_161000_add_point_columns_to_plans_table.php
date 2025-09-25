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
        // Add columns only if they don't exist
        if (!Schema::hasColumn('plans', 'point_based')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->boolean('point_based')->default(false)->after('status');
            });
        }
        
        if (!Schema::hasColumn('plans', 'points_reward')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->decimal('points_reward', 10, 2)->nullable()->after('point_based');
            });
        }
        
        if (!Schema::hasColumn('plans', 'point_price')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->decimal('point_price', 10, 2)->nullable()->after('points_reward');
            });
        }
        
        if (!Schema::hasColumn('plans', 'minimum_points')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->decimal('minimum_points', 10, 2)->nullable()->after('point_price');
            });
        }
        
        if (!Schema::hasColumn('plans', 'maximum_points')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->decimal('maximum_points', 10, 2)->nullable()->after('minimum_points');
            });
        }
        
        if (!Schema::hasColumn('plans', 'wallet_purchase')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->boolean('wallet_purchase')->default(true)->after('maximum_points');
            });
        }
        
        if (!Schema::hasColumn('plans', 'point_purchase')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->boolean('point_purchase')->default(false)->after('wallet_purchase');
            });
        }
        
        if (!Schema::hasColumn('plans', 'sponsor_commission')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->boolean('sponsor_commission')->default(true)->after('point_purchase');
            });
        }
        
        if (!Schema::hasColumn('plans', 'generation_commission')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->boolean('generation_commission')->default(true)->after('sponsor_commission');
            });
        }
        
        if (!Schema::hasColumn('plans', 'binary_matching')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->boolean('binary_matching')->default(false)->after('generation_commission');
            });
        }
        
        if (!Schema::hasColumn('plans', 'category')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->string('category')->nullable()->after('binary_matching');
            });
        }
        
        if (!Schema::hasColumn('plans', 'features')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->json('features')->nullable()->after('category');
            });
        }
        
        if (!Schema::hasColumn('plans', 'purchase_type')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->string('purchase_type')->default('one_time')->after('features');
            });
        }
        
        if (!Schema::hasColumn('plans', 'point_to_taka_rate')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->decimal('point_to_taka_rate', 5, 2)->default(6.00)->after('purchase_type');
            });
        }
        
        if (!Schema::hasColumn('plans', 'point_terms')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->text('point_terms')->nullable()->after('point_to_taka_rate');
            });
        }
        
        if (!Schema::hasColumn('plans', 'sort_order')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->integer('sort_order')->default(0)->after('point_terms');
            });
        }
        
        if (!Schema::hasColumn('plans', 'is_popular')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->boolean('is_popular')->default(false)->after('sort_order');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn([
                'point_based',
                'points_reward',
                'point_price',
                'minimum_points',
                'maximum_points',
                'wallet_purchase',
                'point_purchase',
                'sponsor_commission',
                'generation_commission',
                'binary_matching',
                'category',
                'features',
                'purchase_type',
                'point_to_taka_rate',
                'point_terms',
                'sort_order',
                'is_popular'
            ]);
        });
    }
};
