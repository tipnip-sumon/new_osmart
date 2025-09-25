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
        Schema::table('system_backups', function (Blueprint $table) {
            $table->integer('download_count')->default(0)->after('created_by');
            $table->timestamp('last_downloaded_at')->nullable()->after('download_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('system_backups', function (Blueprint $table) {
            $table->dropColumn(['download_count', 'last_downloaded_at']);
        });
    }
};
