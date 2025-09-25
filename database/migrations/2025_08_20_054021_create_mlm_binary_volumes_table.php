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
        Schema::create('mlm_binary_volumes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('User earning the binary commission');
            $table->unsignedBigInteger('order_id')->nullable()->comment('Order that generated the volume');
            $table->unsignedBigInteger('product_id')->nullable()->comment('Product that generated volume');
            $table->unsignedBigInteger('generated_by_user_id')->comment('User who made the purchase');
            
            // Volume Details
            $table->decimal('volume_amount', 15, 2)->comment('Volume amount generated');
            $table->enum('leg_placement', ['left', 'right'])->comment('Which leg this volume goes to');
            $table->date('volume_date')->comment('Date volume was generated');
            $table->enum('volume_type', ['purchase', 'bonus', 'adjustment', 'carry_forward'])->default('purchase');
            
            // Binary Matching Details
            $table->decimal('matched_volume', 15, 2)->default(0)->comment('Volume that was matched');
            $table->decimal('carry_forward_volume', 15, 2)->default(0)->comment('Volume carried forward');
            $table->decimal('commission_earned', 10, 2)->default(0)->comment('Commission earned from this volume');
            $table->boolean('is_matched')->default(false)->comment('Whether this volume has been matched');
            $table->date('matched_date')->nullable()->comment('Date volume was matched');
            
            // Calculation Period
            $table->integer('calculation_week')->comment('Week number for calculation');
            $table->integer('calculation_month')->comment('Month for calculation');
            $table->integer('calculation_year')->comment('Year for calculation');
            $table->string('period_identifier')->comment('Unique period identifier (YYYY-MM-WW)');
            
            // Status and Processing
            $table->enum('status', ['pending', 'processed', 'matched', 'carried_forward', 'expired'])->default('pending');
            $table->boolean('is_processed')->default(false)->comment('Has been processed in commission run');
            $table->timestamp('processed_at')->nullable()->comment('When it was processed');
            
            // Genealogy Context
            $table->text('genealogy_path')->nullable()->comment('Path in binary tree when volume was added');
            $table->integer('tree_level')->comment('Level in tree when volume was generated');
            
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // Note: order_id foreign key will be added when orders table is created
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('generated_by_user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Indexes for performance
            $table->index(['user_id', 'volume_date', 'leg_placement'], 'mlm_bin_vol_user_date_leg_idx');
            $table->index(['period_identifier', 'is_matched'], 'mlm_bin_vol_period_matched_idx');
            $table->index(['calculation_year', 'calculation_month', 'calculation_week'], 'mlm_bin_vol_calc_period_idx');
            $table->index(['status', 'is_processed'], 'mlm_bin_vol_status_proc_idx');
            $table->index('genealogy_path', 'mlm_bin_vol_genealogy_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mlm_binary_volumes');
    }
};
