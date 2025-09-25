<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->default(0)->nullable();
            $table->bigInteger('plan_id')->unsigned()->default(0)->nullable();
            $table->decimal('amount', 28, 8)->default(0.00000000)->nullable(false);
            $table->decimal('actual_paid', 20, 8)->nullable()->comment('Amount actually paid by user after discounts');
            $table->decimal('token_discount', 20, 8)->default(0.00000000)->nullable(false)->comment('Discount amount from special tokens');
            $table->decimal('interest', 28, 8)->default(0.00000000)->nullable(false);
            $table->decimal('should_pay', 28, 8)->default(0.00000000)->nullable(false);
            $table->decimal('paid', 28, 8)->default(0.00000000)->nullable(false);
            $table->integer('period')->default(0)->nullable();
            $table->string('hours', 40)->nullable(false);
            $table->string('time_name', 40)->nullable(false);
            $table->integer('return_rec_time')->default(0)->nullable(false);
            $table->timestamp('next_time')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->nullable(false);
            $table->timestamp('last_time')->nullable();
            $table->boolean('status')->default(1)->nullable(false);
            $table->boolean('capital_status')->default(0)->nullable(false)->comment('1 = YES & 0 = NO');
            $table->string('trx', 40)->nullable();
            $table->string('wallet_type', 40)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invests');
    }
};
