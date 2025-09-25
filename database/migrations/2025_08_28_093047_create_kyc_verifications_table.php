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
        Schema::create('kyc_verifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->nullable(false);
            $table->date('date_of_birth')->nullable();
            $table->enum('document_type', ['passport', 'national_id', 'driving_license'])->nullable(false);
            $table->string('document_number', 50)->nullable(false);
            $table->string('document_front', 191)->nullable(false);
            $table->string('document_back', 191)->nullable();
            $table->string('selfie_image', 191)->nullable(false);
            $table->string('nationality', 100)->nullable(false);
            $table->text('address')->nullable(false);
            $table->string('city', 100)->nullable(false);
            $table->string('state', 100)->nullable(false);
            $table->string('postal_code', 20)->nullable(false);
            $table->string('country', 100)->nullable(false);
            $table->string('phone_number', 20)->nullable(false);
            $table->enum('status', ['pending', 'approved', 'rejected', 'under_review'])->default('pending')->nullable(false);
            $table->text('admin_remarks')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->bigInteger('reviewed_by')->unsigned()->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('under_review_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kyc_verifications');
    }
};
