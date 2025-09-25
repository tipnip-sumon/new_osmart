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
        Schema::table('orders', function (Blueprint $table) {
            // Payment information fields
            $table->string('sender_number')->nullable()->after('payment_details');
            $table->string('receiver_number')->nullable()->after('sender_number');
            $table->string('transaction_id')->nullable()->after('receiver_number');
            $table->string('payment_proof')->nullable()->after('transaction_id');
            $table->json('payment_proof_data')->nullable()->after('payment_proof');
            $table->text('payment_notes')->nullable()->after('payment_proof_data');
            $table->timestamp('payment_verified_at')->nullable()->after('payment_notes');
            $table->unsignedBigInteger('payment_verified_by')->nullable()->after('payment_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'sender_number',
                'receiver_number',
                'transaction_id',
                'payment_proof',
                'payment_proof_data',
                'payment_notes',
                'payment_verified_at',
                'payment_verified_by'
            ]);
        });
    }
};
