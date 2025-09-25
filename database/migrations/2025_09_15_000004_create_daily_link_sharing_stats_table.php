<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('daily_link_sharing_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('stat_date');
            $table->string('package_name');
            $table->integer('shares_count')->default(0); // How many links shared today
            $table->integer('clicks_count')->default(0); // Total clicks received today
            $table->integer('unique_clicks_count')->default(0); // Unique clicks received today
            $table->decimal('earnings_amount', 10, 2)->default(0); // Total earned today
            $table->boolean('daily_limit_used')->default(false); // Has daily share limit been reached
            $table->boolean('earning_limit_reached')->default(false); // Has daily earning limit been reached
            $table->timestamps();
            
            $table->unique(['user_id', 'stat_date']);
            $table->index(['user_id', 'package_name']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_link_sharing_stats');
    }
};
