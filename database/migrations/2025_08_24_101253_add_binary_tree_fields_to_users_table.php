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
            // Add binary tree fields if they don't exist
            if (!Schema::hasColumn('users', 'upline_id')) {
                $table->unsignedBigInteger('upline_id')->nullable()->after('sponsor_id');
                $table->foreign('upline_id')->references('id')->on('users')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('users', 'upline_username')) {
                $table->string('upline_username')->nullable()->after('upline_id');
            }
            
            // Add indexes for better performance
            if (!Schema::hasColumn('users', 'position')) {
                $table->enum('position', ['left', 'right'])->nullable()->after('upline_username');
            }
            
            if (!Schema::hasColumn('users', 'placement_type')) {
                $table->enum('placement_type', ['auto', 'manual'])->default('auto')->after('position');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'upline_id')) {
                $table->dropForeign(['upline_id']);
                $table->dropColumn('upline_id');
            }
            
            if (Schema::hasColumn('users', 'upline_username')) {
                $table->dropColumn('upline_username');
            }
            
            if (Schema::hasColumn('users', 'position')) {
                $table->dropColumn('position');
            }
            
            if (Schema::hasColumn('users', 'placement_type')) {
                $table->dropColumn('placement_type');
            }
        });
    }
};
