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
        Schema::create('plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 40)->nullable(false);
            $table->decimal('minimum', 28, 8)->default(0.00000000)->nullable(false);
            $table->decimal('maximum', 28, 8)->default(0.00000000)->nullable(false);
            $table->decimal('fixed_amount', 28, 8)->default(0.00000000)->nullable(false);
            $table->decimal('interest', 28, 8)->default(0.00000000)->nullable(false);
            $table->boolean('interest_type')->default(0)->nullable()->comment('1 = \'%\' / 0 =\'currency\'');
            $table->string('time', 40)->default('0')->nullable(false)->comment('e.g., 30 days, 60 days, etc.');
            $table->string('time_name', 40)->nullable()->comment('e.g., days, weeks, months, years');
            $table->boolean('status')->default(1)->nullable(false);
            $table->boolean('featured')->default(0)->nullable(false);
            $table->boolean('capital_back')->default(0)->nullable();
            $table->boolean('lifetime')->default(0)->nullable();
            $table->string('repeat_time', 40)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
