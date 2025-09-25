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
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();
        
        // Drop the existing table and recreate with correct columns
        Schema::dropIfExists('affiliate_link_shares');
        
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
        
        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_link_shares');
        
        // Recreate original table structure if needed
        Schema::create('affiliate_link_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('product_slug');
            $table->string('share_url');
            $table->string('package_type');
            $table->date('share_date');
            $table->integer('click_count')->default(0);
            $table->integer('unique_click_count')->default(0);
            $table->decimal('total_earned', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['user_id', 'share_date']);
            $table->index(['product_slug', 'share_date']);
        });
    }
};
