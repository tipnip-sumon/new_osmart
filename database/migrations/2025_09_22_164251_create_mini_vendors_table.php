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
        Schema::create('mini_vendors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id'); // Main vendor who assigns mini vendor
            $table->unsignedBigInteger('affiliate_id'); // Affiliate user who becomes mini vendor
            $table->string('district'); // District where both must be from
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->decimal('commission_rate', 5, 2)->default(3.00); // 3% commission rate
            $table->decimal('total_earned_commission', 15, 2)->default(0.00); // Total commission earned
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('last_transfer_at')->nullable();
            $table->text('notes')->nullable(); // Optional notes from vendor
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('affiliate_id')->references('id')->on('users')->onDelete('cascade');
            
            // Unique constraint - one affiliate can only be mini vendor for one main vendor
            $table->unique(['vendor_id', 'affiliate_id']);
            
            // Index for better performance
            $table->index(['vendor_id', 'status']);
            $table->index(['affiliate_id', 'status']);
            $table->index('district');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mini_vendors');
    }
};
