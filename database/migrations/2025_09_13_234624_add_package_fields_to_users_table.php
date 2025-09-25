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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('current_package_id')->nullable()->after('subscription_plan_id');
            $table->string('current_package_tier')->nullable()->after('current_package_id');
            $table->integer('accumulated_points')->default(0)->after('current_package_tier');
            $table->integer('pending_payout_points')->default(0)->after('accumulated_points');
            $table->timestamp('package_activated_at')->nullable()->after('pending_payout_points');
            $table->timestamp('last_package_upgrade_at')->nullable()->after('package_activated_at');
            $table->timestamp('next_payout_eligible_at')->nullable()->after('last_package_upgrade_at');
            $table->boolean('payout_locked')->default(false)->after('next_payout_eligible_at');
            $table->decimal('total_package_investment', 15, 2)->default(0)->after('payout_locked');
            
            $table->foreign('current_package_id')->references('id')->on('plans')->onDelete('set null');
            $table->index('current_package_tier');
            $table->index('package_activated_at');
            $table->index('next_payout_eligible_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['current_package_id']);
            $table->dropIndex(['current_package_tier']);
            $table->dropIndex(['package_activated_at']);
            $table->dropIndex(['next_payout_eligible_at']);
            
            $table->dropColumn([
                'current_package_id',
                'current_package_tier',
                'accumulated_points',
                'pending_payout_points',
                'package_activated_at',
                'last_package_upgrade_at',
                'next_payout_eligible_at',
                'payout_locked',
                'total_package_investment'
            ]);
        });
    }
};
