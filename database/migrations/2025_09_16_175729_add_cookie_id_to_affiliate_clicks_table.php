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
        Schema::table('affiliate_clicks', function (Blueprint $table) {
            $table->string('cookie_id', 100)->nullable()->after('ip_address');
            $table->index('cookie_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affiliate_clicks', function (Blueprint $table) {
            $table->dropIndex(['cookie_id']);
            $table->dropColumn('cookie_id');
        });
    }
};
