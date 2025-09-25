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
        Schema::create('admin_notices', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->string('type')->default('info'); // info, warning, success, danger
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0); // Higher priority shows first
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_notices');
    }
};
