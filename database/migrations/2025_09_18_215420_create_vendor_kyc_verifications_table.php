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
        Schema::create('vendor_kyc_verifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
            
            // Business Information
            $table->string('business_name')->nullable();
            $table->string('business_type')->nullable();
            $table->string('business_registration_number')->nullable();
            $table->string('tax_identification_number')->nullable();
            $table->string('business_license_number')->nullable();
            $table->date('establishment_date')->nullable();
            $table->text('business_description')->nullable();
            $table->string('website_url')->nullable();
            
            // Owner Information
            $table->string('owner_full_name')->nullable();
            $table->string('owner_father_name')->nullable();
            $table->string('owner_mother_name')->nullable();
            $table->date('owner_date_of_birth')->nullable();
            $table->enum('owner_gender', ['male', 'female', 'other'])->nullable();
            $table->enum('owner_marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
            $table->string('owner_nationality')->default('Bangladeshi');
            $table->string('owner_religion')->nullable();
            $table->string('owner_profession')->nullable();
            
            // Document Information
            $table->enum('document_type', ['nid', 'passport', 'driving_license', 'birth_certificate'])->nullable();
            $table->string('document_number')->nullable();
            $table->date('document_issue_date')->nullable();
            $table->date('document_expiry_date')->nullable();
            $table->string('document_issuer')->nullable();
            $table->enum('nid_type', ['old', 'smart'])->nullable();
            $table->string('voter_id')->nullable();
            
            // Business Address Information
            $table->text('business_present_address')->nullable();
            $table->string('business_present_country')->default('Bangladesh');
            $table->string('business_present_district')->nullable();
            $table->string('business_present_upazila')->nullable();
            $table->string('business_present_union_ward')->nullable();
            $table->string('business_present_post_office')->nullable();
            $table->string('business_present_postal_code')->nullable();
            $table->text('business_permanent_address')->nullable();
            $table->string('business_permanent_country')->default('Bangladesh');
            $table->string('business_permanent_district')->nullable();
            $table->string('business_permanent_upazila')->nullable();
            $table->string('business_permanent_union_ward')->nullable();
            $table->string('business_permanent_post_office')->nullable();
            $table->string('business_permanent_postal_code')->nullable();
            $table->boolean('same_as_business_present_address')->default(false);
            
            // Owner Address Information
            $table->text('owner_present_address')->nullable();
            $table->string('owner_present_country')->default('Bangladesh');
            $table->string('owner_present_district')->nullable();
            $table->string('owner_present_upazila')->nullable();
            $table->string('owner_present_union_ward')->nullable();
            $table->string('owner_present_post_office')->nullable();
            $table->string('owner_present_postal_code')->nullable();
            $table->text('owner_permanent_address')->nullable();
            $table->string('owner_permanent_country')->default('Bangladesh');
            $table->string('owner_permanent_district')->nullable();
            $table->string('owner_permanent_upazila')->nullable();
            $table->string('owner_permanent_union_ward')->nullable();
            $table->string('owner_permanent_post_office')->nullable();
            $table->string('owner_permanent_postal_code')->nullable();
            $table->boolean('same_as_owner_present_address')->default(false);
            
            // Contact Information
            $table->string('phone_number')->nullable();
            $table->string('alternative_phone')->nullable();
            $table->string('email_address')->nullable();
            $table->string('business_phone')->nullable();
            $table->string('business_email')->nullable();
            
            // Emergency Contact
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->text('emergency_contact_address')->nullable();
            
            // Bank Information
            $table->string('bank_account_holder_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_routing_number')->nullable();
            $table->enum('bank_account_type', ['savings', 'current', 'business'])->nullable();
            
            // Document Uploads - Store file paths
            $table->text('document_front_image')->nullable();
            $table->text('document_back_image')->nullable();
            $table->text('owner_photo')->nullable();
            $table->text('owner_signature')->nullable();
            $table->text('utility_bill')->nullable();
            $table->text('business_license')->nullable();
            $table->text('tax_certificate')->nullable();
            $table->text('bank_statement')->nullable();
            $table->json('additional_documents')->nullable();
            
            // Status and Workflow
            $table->enum('status', ['draft', 'pending', 'under_review', 'approved', 'rejected'])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->text('admin_notes')->nullable();
            
            // Step Tracking
            $table->json('completed_steps')->nullable();
            $table->integer('current_step')->default(1);
            $table->integer('total_steps')->default(6);
            
            // Profile Comparison
            $table->json('profile_mismatches')->nullable();
            $table->boolean('profile_updated_from_kyc')->default(false);
            
            // Timestamps
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('certificate_generated_at')->nullable();
            
            $table->timestamps();
            
            // Foreign key for reviewer
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index('vendor_id');
            $table->index('status');
            $table->index('submitted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_kyc_verifications');
    }
};
