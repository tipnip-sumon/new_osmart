<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class VendorKycVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        
        // Business Information
        'business_name', 'business_type', 'business_registration_number',
        'tax_identification_number', 'business_license_number',
        'establishment_date', 'business_description', 'website_url',
        
        // Owner Information
        'owner_full_name', 'owner_father_name', 'owner_mother_name', 
        'owner_date_of_birth', 'owner_gender', 'owner_marital_status', 
        'owner_nationality', 'owner_religion', 'owner_profession',
        
        // Document Information
        'document_type', 'document_number', 'document_issue_date',
        'document_expiry_date', 'document_issuer', 'nid_type', 'voter_id',
        
        // Business Address Information
        'business_present_address', 'business_present_country', 'business_present_district',
        'business_present_upazila', 'business_present_union_ward', 'business_present_post_office',
        'business_present_postal_code',
        'business_permanent_address', 'business_permanent_country', 'business_permanent_district',
        'business_permanent_upazila', 'business_permanent_union_ward', 'business_permanent_post_office',
        'business_permanent_postal_code', 'same_as_business_present_address',
        
        // Owner Address Information  
        'owner_present_address', 'owner_present_country', 'owner_present_district',
        'owner_present_upazila', 'owner_present_union_ward', 'owner_present_post_office',
        'owner_present_postal_code',
        'owner_permanent_address', 'owner_permanent_country', 'owner_permanent_district',
        'owner_permanent_upazila', 'owner_permanent_union_ward', 'owner_permanent_post_office',
        'owner_permanent_postal_code', 'same_as_owner_present_address',
        
        // Contact Information
        'phone_number', 'alternative_phone', 'email_address',
        'business_phone', 'business_email',
        
        // Emergency Contact
        'emergency_contact_name', 'emergency_contact_relationship',
        'emergency_contact_phone', 'emergency_contact_address',
        
        // Bank Information
        'bank_account_holder_name', 'bank_name', 'bank_branch',
        'bank_account_number', 'bank_routing_number', 'bank_account_type',
        
        // Document Uploads
        'document_front_image', 'document_back_image', 'owner_photo',
        'owner_signature', 'utility_bill', 'business_license',
        'tax_certificate', 'bank_statement', 'additional_documents',
        
        // Status
        'status', 'rejection_reason', 'admin_notes',
        
        // Step Tracking
        'completed_steps', 'current_step', 'total_steps',
        
        // Profile Comparison
        'profile_mismatches', 'profile_updated_from_kyc',
        
        // Timestamps
        'submitted_at', 'approved_at', 'rejected_at', 'reviewed_at',
        'reviewed_by', 'certificate_generated_at'
    ];

    protected $casts = [
        'establishment_date' => 'date',
        'owner_date_of_birth' => 'date',
        'document_issue_date' => 'date',
        'document_expiry_date' => 'date',
        'same_as_business_present_address' => 'boolean',
        'same_as_owner_present_address' => 'boolean',
        'profile_updated_from_kyc' => 'boolean',
        'completed_steps' => 'array',
        'profile_mismatches' => 'array',
        'additional_documents' => 'array',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'certificate_generated_at' => 'datetime',
        'current_step' => 'integer',
        'total_steps' => 'integer',
    ];

    /**
     * Scope to get KYC for a specific vendor
     */
    public function scopeForVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    /**
     * Get the vendor that owns the KYC verification
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    /**
     * Get the admin who reviewed this KYC
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Check if the KYC is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if the KYC is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the KYC is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if the KYC is under review
     */
    public function isUnderReview(): bool
    {
        return $this->status === 'under_review';
    }

    /**
     * Get formatted status
     */
    public function getFormattedStatusAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->status));
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'approved' => 'success',
            'pending' => 'warning',
            'rejected' => 'danger',
            'under_review' => 'info',
            default => 'secondary'
        };
    }

    /**
     * Get status label for display
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'approved' => 'Approved',
            'pending' => 'Pending Review',
            'rejected' => 'Rejected',
            'under_review' => 'Under Review',
            'draft' => 'In Progress',
            default => ucwords(str_replace('_', ' ', $this->status))
        };
    }

    /**
     * Check completion percentage
     */
    public function getCompletionPercentageAttribute(): float
    {
        if (!$this->total_steps || $this->total_steps == 0) {
            return 0;
        }
        
        $completedCount = is_array($this->completed_steps) ? count($this->completed_steps) : 0;
        return min(100, ($completedCount / $this->total_steps) * 100);
    }

    /**
     * Check if all required documents are uploaded
     */
    public function hasAllRequiredDocuments(): bool
    {
        return !empty($this->document_front_image) &&
               !empty($this->document_back_image) &&
               !empty($this->owner_photo) &&
               !empty($this->owner_signature) &&
               !empty($this->utility_bill) &&
               !empty($this->business_license);
    }

    /**
     * Check if step is completed
     */
    public function isStepCompleted(int $step): bool
    {
        $completedSteps = is_array($this->completed_steps) ? $this->completed_steps : [];
        return in_array($step, $completedSteps);
    }

    /**
     * Mark step as completed
     */
    public function markStepCompleted(int $step): void
    {
        $completedSteps = is_array($this->completed_steps) ? $this->completed_steps : [];
        
        if (!in_array($step, $completedSteps)) {
            $completedSteps[] = $step;
            $this->completed_steps = $completedSteps;
        }
        
        // Update current step to next incomplete step
        for ($i = 1; $i <= ($this->total_steps ?? 5); $i++) {
            if (!in_array($i, $completedSteps)) {
                $this->current_step = $i;
                break;
            }
        }
        
        // If all steps completed, mark as submitted
        if (count($completedSteps) >= ($this->total_steps ?? 5)) {
            $this->current_step = ($this->total_steps ?? 5);
            if (!$this->submitted_at) {
                $this->submitted_at = now();
                $this->status = 'pending';
            }
        }
        
        $this->save();
    }

    /**
     * Compare with vendor profile and find mismatches
     */
    public function compareWithProfile(): array
    {
        $vendor = $this->vendor;
        if (!$vendor) {
            return [];
        }

        $fieldsToCompare = [
            'firstname' => 'owner_full_name',
            'email' => 'email_address',
            'mobile' => 'phone_number',
        ];

        $mismatches = [];
        foreach ($fieldsToCompare as $profileField => $kycField) {
            $profileValue = $vendor->$profileField;
            $kycValue = $this->$kycField;
            
            if (!empty($profileValue) && !empty($kycValue) && $profileValue !== $kycValue) {
                $mismatches[] = [
                    'field' => $profileField,
                    'profile_value' => $profileValue,
                    'kyc_value' => $kycValue
                ];
            }
        }

        // Store mismatches
        $this->profile_mismatches = $mismatches;
        $this->save();

        return $mismatches;
    }

    /**
     * Scope for filtering by status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Complete a step
     */
    public function completeStep(int $step): void
    {
        $this->markStepCompleted($step);
    }

    /**
     * Submit KYC for review
     */
    public function submitForReview(): bool
    {
        // Check if all required documents are uploaded
        if (!$this->hasAllRequiredDocuments()) {
            return false;
        }

        $this->status = 'pending';
        $this->submitted_at = now();
        $this->save();

        return true;
    }

    /**
     * Update vendor profile from KYC data
     */
    public function updateProfileFromKyc(): bool
    {
        $vendor = $this->vendor;
        if (!$vendor || !$this->isApproved()) {
            return false;
        }

        $updated = false;
        $fieldsToUpdate = [
            'name' => $this->owner_full_name,
            'email' => $this->email_address,
            'phone' => $this->phone_number,
            'address' => $this->business_present_address,
            'district' => $this->business_present_district,
            'upazila' => $this->business_present_upazila,
            'union_ward' => $this->business_present_union_ward,
            'postal_code' => $this->business_present_postal_code,
        ];

        foreach ($fieldsToUpdate as $profileField => $kycValue) {
            if ($kycValue && $vendor->$profileField !== $kycValue) {
                $vendor->$profileField = $kycValue;
                $updated = true;
            }
        }

        if ($updated) {
            $vendor->save();
            $this->profile_updated_from_kyc = true;
            $this->save();
        }

        return $updated;
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->total_steps = $model->total_steps ?? 6; // 6 steps for vendor KYC
            $model->current_step = $model->current_step ?? 1;
            $model->status = $model->status ?? 'draft';
        });

        static::created(function ($model) {
            // Compare with profile
            $model->compareWithProfile();
        });
    }
}