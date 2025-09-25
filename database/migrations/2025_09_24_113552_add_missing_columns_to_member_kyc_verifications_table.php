<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('member_kyc_verifications', function (Blueprint $table) {
            // Add missing timestamp columns
            if (!Schema::hasColumn('member_kyc_verifications', 'under_review_at')) {
                $table->timestamp('under_review_at')->nullable()->after('rejected_at');
            }
            
            if (!Schema::hasColumn('member_kyc_verifications', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->after('under_review_at');
            }
            
            if (!Schema::hasColumn('member_kyc_verifications', 'reviewed_by')) {
                $table->unsignedBigInteger('reviewed_by')->nullable()->after('reviewed_at');
                $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('member_kyc_verifications', 'admin_remarks')) {
                $table->text('admin_remarks')->nullable()->after('reviewed_by');
            }
        });
        
        // Update status enum to include 'approved' and 'pending' if needed
        DB::statement("ALTER TABLE member_kyc_verifications MODIFY COLUMN status ENUM('draft', 'pending', 'submitted', 'under_review', 'approved', 'verified', 'rejected', 'additional_info_required') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member_kyc_verifications', function (Blueprint $table) {
            // Remove added columns if they exist
            if (Schema::hasColumn('member_kyc_verifications', 'under_review_at')) {
                $table->dropColumn('under_review_at');
            }
            
            if (Schema::hasColumn('member_kyc_verifications', 'reviewed_at')) {
                $table->dropColumn('reviewed_at');
            }
            
            if (Schema::hasColumn('member_kyc_verifications', 'reviewed_by')) {
                $table->dropForeign(['reviewed_by']);
                $table->dropColumn('reviewed_by');
            }
            
            if (Schema::hasColumn('member_kyc_verifications', 'admin_remarks')) {
                $table->dropColumn('admin_remarks');
            }
        });
        
        // Revert status enum to original
        DB::statement("ALTER TABLE member_kyc_verifications MODIFY COLUMN status ENUM('draft', 'submitted', 'under_review', 'verified', 'rejected', 'additional_info_required') DEFAULT 'draft'");
    }
};
