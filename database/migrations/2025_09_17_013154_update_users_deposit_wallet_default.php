<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update all NULL deposit_wallet values to 0
        DB::table('users')
            ->whereNull('deposit_wallet')
            ->update(['deposit_wallet' => 0]);
            
        // Modify the column to have a default value of 0 and not allow null
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('deposit_wallet', 15, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('deposit_wallet', 15, 2)->nullable()->change();
        });
    }
};
