<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('affiliate_link_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('link_share_id')->constrained('affiliate_link_shares')->onDelete('cascade');
            $table->foreignId('affiliate_id')->constrained('users')->onDelete('cascade'); // Who gets the reward
            $table->string('visitor_ip'); // Visitor IP for uniqueness
            $table->string('visitor_device_id')->nullable(); // Device fingerprint
            $table->string('user_agent')->nullable(); // Browser info
            $table->decimal('reward_amount', 10, 2)->default(0); // Amount earned
            $table->boolean('is_rewarded')->default(false); // If reward was given
            $table->timestamp('clicked_at');
            $table->timestamps();
            
            $table->index(['link_share_id', 'visitor_ip']);
            $table->index(['affiliate_id', 'clicked_at']);
            $table->unique(['link_share_id', 'visitor_ip', 'visitor_device_id'], 'unique_click');
        });
    }

    public function down()
    {
        Schema::dropIfExists('affiliate_link_clicks');
    }
};
