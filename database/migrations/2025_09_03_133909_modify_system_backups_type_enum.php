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
        // Modify the enum to include 'manual', 'scheduled', and 'automatic' options
        DB::statement("ALTER TABLE system_backups MODIFY COLUMN type ENUM('full', 'partial', 'manual', 'scheduled', 'automatic') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE system_backups MODIFY COLUMN type ENUM('full', 'partial') NOT NULL");
    }
};
