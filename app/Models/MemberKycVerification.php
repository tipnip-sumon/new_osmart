<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class MemberKycVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        
        // Personal Information
        'full_name', 'father_name', 'mother_name', 'date_of_birth',
        'gender', 'marital_status', 'nationality', 'religion',
        'profession', 'monthly_income',
        
        // Document Information
        'document_type', 'document_number', 'document_issue_date',
        'document_expiry_date', 'document_issuer', 'nid_type', 'voter_id',
        
        // Address Information
        'present_address', 'present_country', 'present_district',
        'present_upazila', 'present_union_ward', 'present_post_office',
        'present_postal_code',
        'permanent_address', 'permanent_country', 'permanent_district',
        'permanent_upazila', 'permanent_union_ward', 'permanent_post_office',
        'permanent_postal_code', 'same_as_present_address',
        
        // Contact Information
        'phone_number', 'alternative_phone', 'email_address',
        
        // Emergency Contact
        'emergency_contact_name', 'emergency_contact_relationship',
        'emergency_contact_phone', 'emergency_contact_address',
        
        // Document Uploads
        'document_front_image', 'document_back_image', 'user_photo',
        'user_signature', 'utility_bill', 'additional_documents',
        
        // Status
        'status', 'rejection_reason', 'admin_notes',
        
        // Step Tracking
        'completed_steps', 'current_step', 'total_steps',
        
        // Profile Comparison
        'profile_mismatches', 'profile_updated_from_kyc',
        
        // Verification
        'submitted_at', 'verified_at', 'rejected_at', 'under_review_at',
        'reviewed_at', 'reviewed_by', 'verified_by', 'rejected_by',
        'admin_remarks',
        
        // Risk Assessment
        'risk_level', 'risk_notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'document_issue_date' => 'date',
        'document_expiry_date' => 'date',
        'monthly_income' => 'decimal:2',
        'same_as_present_address' => 'boolean',
        'profile_updated_from_kyc' => 'boolean',
        'completed_steps' => 'array',
        'profile_mismatches' => 'array',
        'additional_documents' => 'array',
        'submitted_at' => 'datetime',
        'verified_at' => 'datetime',
        'rejected_at' => 'datetime',
        'under_review_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    protected $attributes = [
        'nationality' => 'Bangladeshi',
        'present_country' => 'Bangladesh',
        'permanent_country' => 'Bangladesh',
        'current_step' => 1,
        'total_steps' => 5,
        'status' => 'draft',
        'risk_level' => 'low',
    ];

    /**
     * Get the user that owns the KYC verification
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who verified the KYC
     */
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get the admin who rejected the KYC
     */
    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Get the admin who reviewed the KYC
     */
    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Check if step is completed
     */
    public function isStepCompleted(int $step): bool
    {
        $completedSteps = $this->completed_steps ?? [];
        return in_array($step, $completedSteps);
    }

    /**
     * Mark step as completed
     */
    public function completeStep(int $step): void
    {
        $completedSteps = $this->completed_steps ?? [];
        if (!in_array($step, $completedSteps)) {
            $completedSteps[] = $step;
            $this->completed_steps = $completedSteps;
        }
        
        // Update current step to next uncompleted step
        for ($i = 1; $i <= $this->total_steps; $i++) {
            if (!in_array($i, $completedSteps)) {
                $this->current_step = $i;
                return;
            }
        }
        
        // All steps completed
        $this->current_step = $this->total_steps;
    }

    /**
     * Get completion percentage
     */
    public function getCompletionPercentageAttribute(): int
    {
        $completedSteps = $this->completed_steps ?? [];
        return (int) ((count($completedSteps) / $this->total_steps) * 100);
    }

    /**
     * Check if all required documents are uploaded
     */
    public function hasAllRequiredDocuments(): bool
    {
        $required = ['document_front_image', 'user_photo'];
        
        if ($this->document_type === 'nid' && $this->nid_type === 'smart_card') {
            $required[] = 'document_back_image';
        }
        
        foreach ($required as $field) {
            if (empty($this->$field)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Compare with user profile and find mismatches
     */
    public function compareWithProfile(): array
    {
        $user = $this->user;
        $mismatches = [];

        $fieldsToCompare = [
            'name' => 'full_name',
            'email' => 'email_address',
            'phone' => 'phone_number',
            'date_of_birth' => 'date_of_birth',
            'gender' => 'gender',
            'address' => 'present_address',
            'country' => 'present_country',
            'district' => 'present_district',
            'upazila' => 'present_upazila',
            'union_ward' => 'present_union_ward',
            'postal_code' => 'present_postal_code',
        ];

        foreach ($fieldsToCompare as $profileField => $kycField) {
            $profileValue = $user->$profileField;
            $kycValue = $this->$kycField;

            // Skip empty values
            if (empty($profileValue) || empty($kycValue)) {
                continue;
            }

            // Special handling for date fields
            if ($profileField === 'date_of_birth') {
                // Convert both to the same format for comparison
                $profileDate = null;
                $kycDate = null;

                try {
                    if ($profileValue instanceof \Carbon\Carbon) {
                        $profileDate = $profileValue->format('Y-m-d');
                    } elseif (is_string($profileValue)) {
                        $profileDate = \Carbon\Carbon::parse($profileValue)->format('Y-m-d');
                    }

                    if ($kycValue instanceof \Carbon\Carbon) {
                        $kycDate = $kycValue->format('Y-m-d');
                    } elseif (is_string($kycValue)) {
                        $kycDate = \Carbon\Carbon::parse($kycValue)->format('Y-m-d');
                    }

                    // Compare formatted dates
                    if ($profileDate && $kycDate && $profileDate !== $kycDate) {
                        $mismatches[] = [
                            'field' => $profileField,
                            'profile_value' => $profileDate,
                            'kyc_value' => $kycDate,
                        ];
                    }
                } catch (\Exception $e) {
                    // If date parsing fails, fall back to string comparison
                    if ((string)$profileValue !== (string)$kycValue) {
                        $mismatches[] = [
                            'field' => $profileField,
                            'profile_value' => (string)$profileValue,
                            'kyc_value' => (string)$kycValue,
                        ];
                    }
                }
            } else {
                // Regular string comparison for other fields
                if ((string)$profileValue !== (string)$kycValue) {
                    $mismatches[] = [
                        'field' => $profileField,
                        'profile_value' => (string)$profileValue,
                        'kyc_value' => (string)$kycValue,
                    ];
                }
            }
        }

        $this->profile_mismatches = $mismatches;
        return $mismatches;
    }

    /**
     * Submit for review
     */
    public function submitForReview(): bool
    {
        if (!$this->hasAllRequiredDocuments()) {
            return false;
        }

        $this->status = 'submitted';
        $this->submitted_at = now();
        
        // Compare with profile
        $this->compareWithProfile();
        
        return $this->save();
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
