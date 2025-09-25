<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('affiliate_link_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Who shared
            $table->string('product_slug'); // Product being shared
            $table->string('shared_url'); // The affiliate link
            $table->string('shared_platform'); // Platform where it was shared (facebook, whatsapp, etc.)
            $table->date('share_date'); // Date of sharing
            $table->integer('clicks_count')->default(0); // Total clicks
            $table->integer('unique_clicks_count')->default(0); // Unique device clicks
            $table->decimal('earnings_amount', 10, 2)->default(0); // Total earnings from this share
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['user_id', 'share_date']);
            $table->index(['product_slug', 'share_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('affiliate_link_shares');
    }
};
