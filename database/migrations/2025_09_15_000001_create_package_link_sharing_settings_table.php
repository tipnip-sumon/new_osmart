<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('package_link_sharing_settings', function (Blueprint $table) {
            $table->id();
            $table->string('package_name'); // starter, premium, etc
            $table->integer('daily_share_limit')->default(5); // Max links can share per day
            $table->decimal('click_reward_amount', 10, 2)->default(2.00); // Reward per click
            $table->decimal('daily_earning_limit', 10, 2)->default(10.00); // Max earnings per day
            $table->integer('total_share_limit')->nullable(); // Total shares allowed (null = unlimited)
            $table->boolean('is_active')->default(true);
            $table->json('conditions')->nullable(); // Additional conditions
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('package_link_sharing_settings');
    }
};
