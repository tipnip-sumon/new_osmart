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
        // Update the placement_type enum to include the new values
        DB::statement("ALTER TABLE users MODIFY COLUMN placement_type ENUM('auto', 'manual', 'direct', 'specific') DEFAULT 'auto'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE users MODIFY COLUMN placement_type ENUM('auto', 'manual') DEFAULT 'auto'");
    }
};
