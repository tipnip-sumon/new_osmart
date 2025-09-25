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
        Schema::create('member_kyc_verifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            
            // Personal Information
            $table->string('full_name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
            $table->string('nationality', 50)->default('Bangladeshi');
            $table->string('religion', 50)->nullable();
            $table->string('profession')->nullable();
            $table->decimal('monthly_income', 12, 2)->nullable();
            
            // Document Information
            $table->enum('document_type', ['nid', 'passport', 'driving_license', 'birth_certificate'])->nullable();
            $table->string('document_number')->nullable()->unique();
            $table->date('document_issue_date')->nullable();
            $table->date('document_expiry_date')->nullable();
            $table->string('document_issuer')->nullable(); // For passport: issuing country
            
            // NID Specific (Smart Card / Old NID)
            $table->string('nid_type')->nullable(); // 'smart_card', 'old_nid'
            $table->string('voter_id')->nullable(); // Old NID number if different
            
            // Address Information (Present)
            $table->text('present_address')->nullable();
            $table->string('present_country', 100)->default('Bangladesh');
            $table->string('present_district')->nullable();
            $table->string('present_upazila')->nullable();
            $table->string('present_union_ward')->nullable();
            $table->string('present_post_office')->nullable();
            $table->string('present_postal_code', 10)->nullable();
            
            // Address Information (Permanent)
            $table->text('permanent_address')->nullable();
            $table->string('permanent_country', 100)->default('Bangladesh');
            $table->string('permanent_district')->nullable();
            $table->string('permanent_upazila')->nullable();
            $table->string('permanent_union_ward')->nullable();
            $table->string('permanent_post_office')->nullable();
            $table->string('permanent_postal_code', 10)->nullable();
            $table->boolean('same_as_present_address')->default(false);
            
            // Contact Information
            $table->string('phone_number')->nullable();
            $table->string('alternative_phone')->nullable();
            $table->string('email_address')->nullable();
            
            // Emergency Contact
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->text('emergency_contact_address')->nullable();
            
            // Document Upload Paths
            $table->string('document_front_image')->nullable(); // NID/Passport front
            $table->string('document_back_image')->nullable();  // NID back (if applicable)
            $table->string('user_photo')->nullable();           // User's photo
            $table->string('user_signature')->nullable();       // User's signature
            $table->string('utility_bill')->nullable();         // Address proof
            $table->string('additional_documents')->nullable(); // JSON array for additional docs
            
            // Verification Status
            $table->enum('status', ['draft', 'submitted', 'under_review', 'verified', 'rejected', 'additional_info_required'])
                  ->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->text('admin_notes')->nullable();
            
            // Step Tracking
            $table->json('completed_steps')->nullable(); // Track which steps are completed
            $table->integer('current_step')->default(1);
            $table->integer('total_steps')->default(5);
            
            // Profile Comparison
            $table->json('profile_mismatches')->nullable(); // Store mismatched fields
            $table->boolean('profile_updated_from_kyc')->default(false);
            
            // Verification Details
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable(); // Admin user ID
            $table->unsignedBigInteger('rejected_by')->nullable(); // Admin user ID
            
            // Risk Assessment
            $table->enum('risk_level', ['low', 'medium', 'high'])->default('low');
            $table->text('risk_notes')->nullable();
            
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index(['user_id', 'status']);
            $table->index('document_number');
            $table->index('status');
            $table->index('submitted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_kyc_verifications');
    }
};
