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
        // Add 'affiliate' to the enum type column
        DB::statement("ALTER TABLE commission_settings MODIFY COLUMN type ENUM('sponsor', 'matching', 'generation', 'rank', 'club', 'binary', 'leadership', 'affiliate')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'affiliate' from the enum type column
        DB::statement("ALTER TABLE commission_settings MODIFY COLUMN type ENUM('sponsor', 'matching', 'generation', 'rank', 'club', 'binary', 'leadership')");
    }
};
