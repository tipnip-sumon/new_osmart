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
            // Point system fields
            $table->decimal('reserve_points', 15, 2)->default(0)->after('interest_wallet')->comment('Points accumulated from purchases, available for binary matching when >= 100');
            $table->decimal('active_points', 15, 2)->default(0)->after('reserve_points')->comment('Points currently active in binary tree');
            $table->decimal('total_points_earned', 15, 2)->default(0)->after('active_points')->comment('Lifetime total points earned');
            $table->decimal('total_points_used', 15, 2)->default(0)->after('total_points_earned')->comment('Total points used in binary matching');
            
            // Binary tree position (if not already exists)
            if (!Schema::hasColumn('users', 'position')) {
                $table->enum('position', ['left', 'right'])->nullable()->after('sponsor_id')->comment('Position in sponsor binary tree');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'reserve_points',
                'active_points', 
                'total_points_earned',
                'total_points_used'
            ]);
            
            // Only drop position if we added it
            if (Schema::hasColumn('users', 'position')) {
                $table->dropColumn('position');
            }
        });
    }
};
