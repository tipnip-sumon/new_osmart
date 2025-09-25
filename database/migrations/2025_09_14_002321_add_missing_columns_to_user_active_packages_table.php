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
        Schema::table('user_active_packages', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable()->after('notes');
            $table->unsignedBigInteger('order_id')->nullable()->after('product_id');
            $table->integer('payout_count')->default(0)->after('order_id');
            
            // Add indexes for performance
            $table->index('product_id');
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_active_packages', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
            $table->dropIndex(['order_id']);
            $table->dropColumn(['product_id', 'order_id', 'payout_count']);
        });
    }
};
