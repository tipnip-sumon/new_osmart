<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('package_link_sharing_settings', function (Blueprint $table) {
            $table->unsignedBigInteger('plan_id')->nullable()->after('id');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('set null');
            
            // Make package_name nullable since we'll derive it from plan
            $table->string('package_name')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('package_link_sharing_settings', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
            $table->dropColumn('plan_id');
            
            // Restore package_name as required
            $table->string('package_name')->nullable(false)->change();
        });
    }
};
