<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

// Additional model imports for package system
use App\Models\UserActivePackage;
use App\Models\UserPackageHistory;
use App\Models\UserDailyCashback;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        // Basic user information
        'firstname',
        'lastname',
        'username',
        'name', // Keep for backward compatibility
        'email',
        'password',
        'phone',
        'mobile', // Alternative mobile field
        'avatar',
        'date_of_birth',
        'gender',
        
        // Address information
        'address',
        'city',
        'state',
        'country',
        'district',
        'upazila',
        'union_ward',
        'postal_code',
        
        // User roles and status
        'role',
        'status',
        
        // Sponsor/Referral system
        'sponsor', // Sponsor username/ID
        'sponsor_id',
        'ref_by', // Referrer username
        'referral_code',
        'referral_hash', // Referral hash
        
        // MLM Binary Tree fields
        'position', // left/right position
        'placement_type', // auto/manual placement
        'upline_id', // Binary parent ID
        'upline_username', // Binary parent username
        'marketing_consent', // Marketing email consent
        
        // Verification statuses (these exist in the database)
        'ev', // Email verification status (0/1)
        'sv', // SMS verification status (0/1)
        'kv', // KYC verification status (0/1)
        
        // Financial balances (these exist in the database)
        'balance',
        'deposit_wallet',
        'interest_wallet',
        'commission_rate',
        'total_earnings',
        'matching_commission',
        'monthly_sales_volume',
        'daily_sales_volume',
        'total_sales_volume',
        'available_balance',
        'pending_balance',
        'withdrawn_amount',
        
        // Point system for binary matching
        'reserve_points',
        'active_points',
        'total_points_earned',
        'total_points_used',
        
        // MLM rank and PV system
        'rank',
        'total_pv',        // KYC system
        'kyc_status',
        'kyc_submitted_at',
        'kyc_verified_at',
        'kyc_rejected_at',
        'kyc_rejection_reason',
        'kyc_documents',
        'identity_type',
        'identity_number',
        'identity_document',
        'address_document',
        'selfie_document',
        
        // Banking information
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'bank_routing_number',
        'bank_swift_code',
        
        // Login and security tracking
        'last_login_at',
        'last_login_ip',
        'last_login_user_agent',
        'login_count',
        'login_attempts',
        'locked_until',
        
        // Session tracking
        'current_session_id',
        'session_created_at',
        'session_ip_address',
        'session_user_agent',
        'last_activity_at',
        
        // Two-factor authentication
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_recovery_codes',
        
        // User preferences and notes
        'preferences',
        'notes',
        
        // Status flags (these exist in the database)
        'is_active',
        'is_featured',
        'is_verified_vendor',
        
        // Vendor-specific fields
        'shop_name',
        'shop_description',
        'shop_logo',
        'shop_banner',
        'shop_address',
        'business_license',
        'tax_id',
        
        // Subscription management
        'subscription_plan_id',
        'subscription_expires_at',
        'trial_ends_at',
        
        // New verification fields
        'profile_completed_at',
        'phone_verification_token',
        'phone_verification_token_expires_at',
        'required_fields_completed',
        'profile_completion_percentage'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'referral_hash', // Keep referral hash private
        'current_session_id',
        'login_attempts',
        'locked_until',
        'bank_account_number', // Sensitive banking info
        'bank_routing_number',
        'bank_swift_code',
        'identity_number', // Sensitive identity info
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // Date/time fields
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'date_of_birth' => 'date',
            'kyc_submitted_at' => 'datetime',
            'kyc_verified_at' => 'datetime',
            'kyc_rejected_at' => 'datetime',
            'last_login_at' => 'datetime',
            'locked_until' => 'datetime',
            'session_created_at' => 'datetime',
            'last_activity_at' => 'datetime',
            'subscription_expires_at' => 'datetime',
            'trial_ends_at' => 'datetime',
            
            // Password and security
            'password' => 'hashed',
            
            // Verification status fields (0/1 or boolean)
            'ev' => 'boolean', // Email verification
            'sv' => 'boolean', // SMS verification
            'kv' => 'boolean', // KYC verification
            'two_factor_enabled' => 'boolean',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_verified_vendor' => 'boolean',
            'marketing_consent' => 'boolean', // Marketing email consent
            
            // Financial fields
            'balance' => 'decimal:2',
            'deposit_wallet' => 'decimal:2',
            'interest_wallet' => 'decimal:2',
            'commission_rate' => 'decimal:2',
            'total_earnings' => 'decimal:2',
            'available_balance' => 'decimal:2',
            'pending_balance' => 'decimal:2',
            'withdrawn_amount' => 'decimal:2',
            'monthly_sales_volume' => 'decimal:2',
            'daily_sales_volume' => 'decimal:2',
            'total_sales_volume' => 'decimal:2',
            
            // Point system fields
            'reserve_points' => 'decimal:2',
            'active_points' => 'decimal:2',
            'total_points_earned' => 'decimal:2',
            'total_points_used' => 'decimal:2',
            
            // Integer fields
            'login_count' => 'integer',
            'login_attempts' => 'integer',
            
            // Array/JSON fields
            'kyc_documents' => 'array',
            'two_factor_recovery_codes' => 'array',
            'preferences' => 'array',
            'required_fields_completed' => 'array', // New verification field
            
            // New verification timestamp fields
            'profile_completed_at' => 'datetime',
            'phone_verification_token_expires_at' => 'datetime'
        ];
    }

    // User roles
    const ROLES = [
        'customer' => 'Customer',
        'vendor' => 'Vendor',
        'affiliate' => 'Affiliate',
        'admin' => 'Admin',
        'super_admin' => 'Super Admin'
    ];

    // User statuses
    const STATUSES = [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'suspended' => 'Suspended',
        'banned' => 'Banned',
        'pending' => 'Pending Approval'
    ];

    // KYC statuses
    const KYC_STATUSES = [
        'not_submitted' => 'Not Submitted',
        'pending' => 'Pending Review',
        'under_review' => 'Under Review',
        'verified' => 'Verified',
        'rejected' => 'Rejected',
        'resubmission_required' => 'Resubmission Required'
    ];

    // Identity types
    const IDENTITY_TYPES = [
        'national_id' => 'National ID',
        'passport' => 'Passport',
        'driving_license' => 'Driving License',
        'voter_id' => 'Voter ID'
    ];

    // Gender options
    const GENDERS = [
        'male' => 'Male',
        'female' => 'Female',
        'other' => 'Other',
        'prefer_not_to_say' => 'Prefer not to say'
    ];

    // Relationships
    public function sponsor()
    {
        return $this->belongsTo(User::class, 'sponsor_id');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'sponsor_id');
    }

    public function downline()
    {
        return $this->hasMany(User::class, 'sponsor_id');
    }

    /**
     * Get all downline members recursively (all levels)
     */
    public function getAllDownline()
    {
        $downline = collect();
        
        // Get direct referrals
        $directReferrals = $this->downline;
        
        foreach ($directReferrals as $referral) {
            $downline->push($referral);
            // Recursively get their downline
            $downline = $downline->merge($referral->getAllDownline());
        }
        
        return $downline;
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'vendor_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function vendorOrders()
    {
        return $this->hasMany(Order::class, 'vendor_id');
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    public function affiliateClicks()
    {
        return $this->hasMany(AffiliateClick::class);
    }

    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function notifications()
    {
        return $this->hasMany(UserNotification::class);
    }

    public function memberKyc()
    {
        return $this->hasOne(MemberKycVerification::class, 'user_id');
    }

    public function vendorKyc()
    {
        return $this->hasOne(VendorKycVerification::class, 'vendor_id');
    }

    /**
     * Get user's active packages
     */
    public function activePackages()
    {
        return $this->hasMany(UserActivePackage::class)->active();
    }

    /**
     * Get all user packages (active and inactive)
     */
    public function allPackages()
    {
        return $this->hasMany(UserActivePackage::class);
    }

    /**
     * Get user's package history
     */
    public function packageHistory()
    {
        return $this->hasMany(UserPackageHistory::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get current subscription plan (existing relationship)
     */
    public function currentPlan()
    {
        return $this->belongsTo(Plan::class, 'current_package_id');
    }

    /**
     * Get packages eligible for payout
     */
    public function eligiblePackages()
    {
        return $this->hasMany(UserActivePackage::class)->eligibleForPayout();
    }

    /**
     * Get user's daily cashback records
     */
    public function dailyCashbacks()
    {
        return $this->hasMany(UserDailyCashback::class);
    }

    // Accessors
    public function getRoleNameAttribute()
    {
        return self::ROLES[$this->role] ?? $this->role;
    }

    public function getStatusNameAttribute()
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getKycStatusNameAttribute()
    {
        return self::KYC_STATUSES[$this->kyc_status] ?? $this->kyc_status;
    }

    public function getIdentityTypeNameAttribute()
    {
        return self::IDENTITY_TYPES[$this->identity_type] ?? $this->identity_type;
    }

    public function getGenderNameAttribute()
    {
        return self::GENDERS[$this->gender] ?? $this->gender;
    }

    public function getFullNameAttribute()
    {
        // Use the actual database columns
        $firstName = $this->attributes['firstname'] ?? '';
        $lastName = $this->attributes['lastname'] ?? '';
        
        if ($firstName && $lastName) {
            return trim($firstName . ' ' . $lastName);
        }
        
        return $this->attributes['name'] ?? $this->attributes['username'] ?? '';
    }

    /**
     * Accessor for first_name to map to firstname column
     */
    public function getFirstNameAttribute()
    {
        return $this->attributes['firstname'] ?? '';
    }

    /**
     * Accessor for last_name to map to lastname column
     */
    public function getLastNameAttribute()
    {
        return $this->attributes['lastname'] ?? '';
    }

    public function getDisplayNameAttribute()
    {
        return $this->full_name ?: $this->username ?: $this->email;
    }

    public function getAvatarUrlAttribute()
    {
        // Priority 1: Check if user has uploaded image with image_data (new system)
        if ($this->profile_image && $this->image_data) {
            $imageData = json_decode($this->image_data, true);
            if (isset($imageData['sizes']['medium']['storage_url'])) {
                return $imageData['sizes']['medium']['storage_url'];
            }
            if (isset($imageData['sizes']['original']['storage_url'])) {
                return $imageData['sizes']['original']['storage_url'];
            }
        }
        
        // Priority 2: Check if user has avatar field (old system)
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        
        // Priority 3: Use role-based default avatar
        return $this->getDefaultAvatarByRole();
    }

    /**
     * Get default avatar based on user role
     */
    public function getDefaultAvatarByRole()
    {
        $avatarMap = [
            'customer' => 'assets/images/avatars/default-customer.svg',
            'vendor' => 'assets/images/avatars/default-vendor.svg',
            'affiliate' => 'assets/images/avatars/default-affiliate.svg',
            'admin' => 'assets/images/avatars/default-admin.svg',
            'super_admin' => 'assets/images/avatars/default-admin.svg',
        ];

        $role = $this->role ?? 'customer';
        $avatarFile = $avatarMap[$role] ?? $avatarMap['customer'];
        
        return asset($avatarFile);
    }

    /**
     * Get small avatar URL for thumbnails
     */
    public function getSmallAvatarUrlAttribute()
    {
        // Priority 1: Check if user has uploaded image with image_data (new system)
        if ($this->profile_image && $this->image_data) {
            $imageData = json_decode($this->image_data, true);
            if (isset($imageData['sizes']['small']['storage_url'])) {
                return $imageData['sizes']['small']['storage_url'];
            }
            if (isset($imageData['sizes']['medium']['storage_url'])) {
                return $imageData['sizes']['medium']['storage_url'];
            }
        }
        
        // Priority 2: Check if user has avatar field (old system)
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        
        // Priority 3: Use role-based default avatar
        return $this->getDefaultAvatarByRole();
    }

    /**
     * Get large avatar URL for profile pages
     */
    public function getLargeAvatarUrlAttribute()
    {
        // Priority 1: Check if user has uploaded image with image_data (new system)
        if ($this->profile_image && $this->image_data) {
            $imageData = json_decode($this->image_data, true);
            if (isset($imageData['sizes']['large']['storage_url'])) {
                return $imageData['sizes']['large']['storage_url'];
            }
            if (isset($imageData['sizes']['original']['storage_url'])) {
                return $imageData['sizes']['original']['storage_url'];
            }
        }
        
        // Priority 2: Check if user has avatar field (old system)
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        
        // Priority 3: Use role-based default avatar
        return $this->getDefaultAvatarByRole();
    }

    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country
        ]);

        return implode(', ', $parts);
    }

    public function getAgeAttribute()
    {
        if ($this->date_of_birth) {
            return $this->date_of_birth->age;
        }
        
        return null;
    }

    public function getIsEmailVerifiedAttribute()
    {
        return $this->ev == 1 || $this->email_verified_at !== null;
    }

    public function getIsSmsVerifiedAttribute()
    {
        return $this->sv == 1 || $this->phone_verified_at !== null;
    }

    public function getIsKycVerifiedAttribute()
    {
        return $this->kv == 1 || $this->kyc_status === 'verified';
    }

    public function getIsFullyVerifiedAttribute()
    {
        return $this->is_email_verified && $this->is_sms_verified && $this->is_kyc_verified;
    }

    public function getIsProfileCompletedAttribute()
    {
        return $this->profile_completed_at !== null;
    }

    public function getCanTransferAttribute()
    {
        return $this->is_email_verified && $this->is_sms_verified && $this->is_kyc_verified && $this->is_profile_completed;
    }

    public function getProfileCompletionPercentageAttribute()
    {
        $requiredFields = $this->getRequiredProfileFields();
        $completedFields = 0;
        $totalFields = count($requiredFields);

        foreach ($requiredFields as $field) {
            if (!empty($this->{$field})) {
                $completedFields++;
            }
        }

        // Add verification statuses
        if ($this->is_email_verified) $completedFields++;
        if ($this->is_sms_verified) $completedFields++;
        if ($this->is_kyc_verified) $completedFields++;
        
        $totalFields += 3; // Add 3 for verification statuses

        return $totalFields > 0 ? round(($completedFields / $totalFields) * 100) : 0;
    }

    public function getRequiredProfileFields()
    {
        return [
            'name',
            'email', 
            'phone',
            'date_of_birth',
            'gender',
            'country',
            'district',
            'upazila',
            'union_ward',
            'address'
        ];
    }

    public function getMissingRequiredFieldsAttribute()
    {
        $requiredFields = $this->getRequiredProfileFields();
        $missingFields = [];

        foreach ($requiredFields as $field) {
            if (empty($this->{$field})) {
                $missingFields[] = $field;
            }
        }

        // Add verification requirements
        if (!$this->is_email_verified) $missingFields[] = 'email_verification';
        if (!$this->is_sms_verified) $missingFields[] = 'phone_verification';
        if (!$this->is_kyc_verified) $missingFields[] = 'kyc_verification';

        return $missingFields;
    }

    public function checkAndUpdateProfileCompletion()
    {
        $completion = $this->profile_completion_percentage;
        $missing = $this->missing_required_fields;

        // Update the profile completion percentage
        $this->profile_completion_percentage = $completion;
        $this->required_fields_completed = $missing;

        // Mark profile as completed if 100%
        if ($completion >= 100 && empty($missing) && !$this->profile_completed_at) {
            $this->profile_completed_at = now();
        } elseif ($completion < 100 && $this->profile_completed_at) {
            $this->profile_completed_at = null;
        }

        return $this->save();
    }

    public function getIsAdminAttribute()
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }

    public function getIsVendorAttribute()
    {
        return $this->role === 'vendor';
    }

    public function getIsAffiliateAttribute()
    {
        return $this->role === 'affiliate';
    }

    public function getIsCustomerAttribute()
    {
        return $this->role === 'customer';
    }

    public function getTotalWalletBalanceAttribute()
    {
        return ($this->balance ?? 0) + ($this->deposit_wallet ?? 0) + ($this->interest_wallet ?? 0) + ($this->available_balance ?? 0);
    }

    public function getCanWithdrawAttribute()
    {
        return $this->available_balance > 0 && 
               $this->is_email_verified && 
               $this->is_sms_verified && 
               $this->is_kyc_verified && 
               $this->is_profile_completed &&
               $this->is_active &&
               !$this->locked_until;
    }

    public function getCommissionRatePercentAttribute()
    {
        return ($this->commission_rate * 100) . '%';
    }

    public function getReferralLinkAttribute()
    {
        $referralParam = $this->referral_code ?: $this->username;
        return url('/register?ref=' . $referralParam);
    }

    public function getReferralHashLinkAttribute()
    {
        if ($this->referral_hash) {
            return url('/register?hash=' . $this->referral_hash);
        }
        return $this->referral_link;
    }

    public function getTotalReferralsAttribute()
    {
        return $this->referrals()->count();
    }

    public function getActiveReferralsAttribute()
    {
        return $this->referrals()->where('is_active', true)->count();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeVendors($query)
    {
        return $query->where('role', 'vendor');
    }

    public function scopeCustomers($query)
    {
        return $query->where('role', 'customer');
    }

    public function scopeAffiliates($query)
    {
        return $query->where('role', 'affiliate');
    }

    public function scopeAdmins($query)
    {
        return $query->whereIn('role', ['admin', 'super_admin']);
    }

    public function scopeEmailVerified($query)
    {
        return $query->where(function($q) {
            $q->where('ev', 1)->orWhereNotNull('email_verified_at');
        });
    }

    public function scopeSmsVerified($query)
    {
        return $query->where(function($q) {
            $q->where('sv', 1)->orWhereNotNull('phone_verified_at');
        });
    }

    public function scopeFullyVerified($query)
    {
        return $query->where(function($q) {
            $q->where('ev', 1)->orWhereNotNull('email_verified_at');
        })->where(function($q) {
            $q->where('sv', 1)->orWhereNotNull('phone_verified_at');
        })->where(function($q) {
            $q->where('kv', 1)->orWhere('kyc_status', 'verified');
        });
    }

    public function scopeByUsername($query, $username)
    {
        return $query->where('username', $username);
    }

    public function scopeByReferrer($query, $referrer)
    {
        return $query->where('ref_by', $referrer);
    }

    public function scopeHasBalance($query)
    {
        return $query->where(function($q) {
            $q->where('balance', '>', 0)
              ->orWhere('deposit_wallet', '>', 0)
              ->orWhere('interest_wallet', '>', 0)
              ->orWhere('available_balance', '>', 0);
        });
    }

    public function scopeNotLocked($query)
    {
        return $query->where(function($q) {
            $q->whereNull('locked_until')->orWhere('locked_until', '<', now());
        });
    }

    public function scopeVerifiedPhone($query)
    {
        return $query->where(function($q) {
            $q->where('sv', 1)->orWhereNotNull('phone_verified_at');
        });
    }

    public function scopeKycVerified($query)
    {
        return $query->where(function($q) {
            $q->where('kv', 1)->orWhere('kyc_status', 'verified');
        });
    }

    public function scopeKycPending($query)
    {
        return $query->whereIn('kyc_status', ['pending', 'under_review']);
    }

    public function scopeHasSponsor($query)
    {
        return $query->whereNotNull('sponsor_id');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('firstname', 'LIKE', "%{$search}%")
              ->orWhere('lastname', 'LIKE', "%{$search}%")
              ->orWhere('username', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%")
              ->orWhere('phone', 'LIKE', "%{$search}%")
              ->orWhere('mobile', 'LIKE', "%{$search}%")
              ->orWhere('referral_code', 'LIKE', "%{$search}%")
              ->orWhere('ref_by', 'LIKE', "%{$search}%")
              ->orWhere('shop_name', 'LIKE', "%{$search}%");
        });
    }

    // Methods
    public function generateReferralCode()
    {
        $name = $this->attributes['firstname'] ?: $this->attributes['name'] ?: $this->attributes['username'];
        do {
            $code = strtoupper(substr($name, 0, 3) . random_int(1000, 9999));
        } while (User::where('referral_code', $code)->exists());

        $this->referral_code = $code;
        $this->save();

        return $code;
    }

    public function generateReferralHash()
    {
        do {
            $hash = bin2hex(random_bytes(16));
        } while (User::where('referral_hash', $hash)->exists());

        $this->referral_hash = $hash;
        $this->save();

        return $hash;
    }

    public function verifyEmail()
    {
        $this->ev = 1;
        $this->email_verified_at = now();
        $this->save();

        return $this;
    }

    public function verifySms()
    {
        $this->sv = 1;
        $this->phone_verified_at = now();
        $this->save();

        return $this;
    }

    public function verifyKyc()
    {
        $this->kv = 1;
        $this->kyc_status = 'verified';
        $this->kyc_verified_at = now();
        $this->save();

        return $this;
    }

    public function lockAccount($minutes = 30, $reason = 'Security lock')
    {
        $this->locked_until = now()->addMinutes($minutes);
        $this->notes = $this->notes ? $this->notes . "\nLocked: " . $reason : "Locked: " . $reason;
        $this->save();

        return $this;
    }

    public function unlockAccount()
    {
        $this->locked_until = null;
        $this->login_attempts = 0;
        $this->save();

        return $this;
    }

    public function incrementLoginAttempts()
    {
        $this->login_attempts = ($this->login_attempts ?? 0) + 1;
        
        // Lock account after 5 failed attempts
        if ($this->login_attempts >= 5) {
            $this->lockAccount(60, 'Too many failed login attempts');
        }
        
        $this->save();

        return $this;
    }

    public function resetLoginAttempts()
    {
        $this->login_attempts = 0;
        $this->save();

        return $this;
    }

    public function updateSession($sessionId, $userAgent = null)
    {
        $this->current_session_id = $sessionId;
        $this->session_created_at = now();
        $this->session_ip_address = request()->ip();
        $this->session_user_agent = $userAgent ?: request()->userAgent();
        $this->last_activity_at = now();
        $this->save();

        return $this;
    }

    public function updateLastActivity()
    {
        $this->last_activity_at = now();
        $this->save();

        return $this;
    }

    public function submitKyc($documents = [])
    {
        $this->kyc_status = 'pending';
        $this->kyc_submitted_at = now();
        $this->kyc_documents = $documents;
        $this->save();

        // Trigger KYC submission event
        event(new \App\Events\KycSubmitted($this));

        return $this;
    }

    public function approveKyc($adminId = null)
    {
        $this->kyc_status = 'verified';
        $this->kyc_verified_at = now();
        $this->kyc_rejection_reason = null;
        $this->save();

        // Trigger KYC approval event
        event(new \App\Events\KycApproved($this));

        return $this;
    }

    public function rejectKyc($reason, $adminId = null)
    {
        $this->kyc_status = 'rejected';
        $this->kyc_rejected_at = now();
        $this->kyc_rejection_reason = $reason;
        $this->save();

        // Trigger KYC rejection event
        event(new \App\Events\KycRejected($this));

        return $this;
    }

    public function addEarnings($amount, $type = 'commission', $description = null)
    {
        $this->pending_balance += $amount;
        $this->save();

        // Create transaction record
        $this->transactions()->create([
            'type' => $type,
            'amount' => $amount,
            'status' => 'pending',
            'description' => $description,
            'created_at' => now()
        ]);

        return $this;
    }

    public function confirmEarnings($amount)
    {
        $this->pending_balance -= $amount;
        $this->available_balance += $amount;
        $this->total_earnings += $amount;
        $this->save();

        return $this;
    }

    public function withdraw($amount, $method = 'bank_transfer')
    {
        if (!$this->can_withdraw || $amount > $this->available_balance) {
            throw new \Exception('Insufficient balance or withdrawal not allowed');
        }

        $this->available_balance -= $amount;
        $this->save();

        // Create withdrawal request
        $withdrawal = $this->withdrawals()->create([
            'amount' => $amount,
            'method' => $method,
            'status' => 'pending',
            'requested_at' => now()
        ]);

        return $withdrawal;
    }

    public function activate()
    {
        $this->is_active = true;
        $this->status = 'active';
        $this->save();

        return $this;
    }

    public function deactivate()
    {
        $this->is_active = false;
        $this->status = 'inactive';
        $this->save();

        return $this;
    }

    public function suspend($reason = null)
    {
        $this->is_active = false;
        $this->status = 'suspended';
        $this->notes = $this->notes ? $this->notes . "\nSuspended: " . $reason : "Suspended: " . $reason;
        $this->save();

        return $this;
    }

    public function ban($reason = null)
    {
        $this->is_active = false;
        $this->status = 'banned';
        $this->notes = $this->notes ? $this->notes . "\nBanned: " . $reason : "Banned: " . $reason;
        $this->save();

        return $this;
    }

    public function becomeVendor($shopData = [])
    {
        $this->role = 'vendor';
        $this->shop_name = $shopData['shop_name'] ?? null;
        $this->shop_description = $shopData['shop_description'] ?? null;
        $this->shop_address = $shopData['shop_address'] ?? null;
        $this->business_license = $shopData['business_license'] ?? null;
        $this->tax_id = $shopData['tax_id'] ?? null;
        $this->save();

        return $this;
    }

    public function recordLogin($ip = null, $userAgent = null)
    {
        $this->last_login_at = now();
        $this->last_login_ip = $ip ?: request()->ip();
        $this->last_login_user_agent = $userAgent ?: request()->userAgent();
        $this->login_count = ($this->login_count ?? 0) + 1;
        $this->login_attempts = 0; // Reset failed attempts on successful login
        $this->last_activity_at = now();
        $this->save();

        return $this;
    }

    public function hasRole($role)
    {
        if (is_array($role)) {
            return in_array($this->role, $role);
        }

        return $this->role === $role;
    }

    public function hasPermission($permission)
    {
        // Implement permission checking logic based on role
        $permissions = [
            'super_admin' => ['*'], // All permissions
            'admin' => [
                'manage_users', 'manage_products', 'manage_orders', 
                'manage_categories', 'manage_settings', 'view_reports'
            ],
            'vendor' => [
                'manage_own_products', 'manage_own_orders', 
                'view_own_reports', 'manage_inventory'
            ],
            'affiliate' => [
                'view_commissions', 'view_referrals', 'generate_links'
            ],
            'customer' => [
                'place_orders', 'manage_profile', 'write_reviews'
            ]
        ];

        $rolePermissions = $permissions[$this->role] ?? [];

        return in_array('*', $rolePermissions) || in_array($permission, $rolePermissions);
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            // Set default values for fields that exist in users table
            $user->role = $user->role ?? 'customer';
            $user->status = $user->status ?? 'active';
            $user->kyc_status = $user->kyc_status ?? 'not_submitted';
            
            // Verification defaults (these exist as tinyInteger fields)
            $user->ev = $user->ev ?? 0;
            $user->sv = $user->sv ?? 0;
            $user->kv = $user->kv ?? 0;
            
            // Financial defaults (these exist in users table)
            $user->balance = $user->balance ?? 0;
            $user->deposit_wallet = $user->deposit_wallet ?? 0;
            $user->interest_wallet = $user->interest_wallet ?? 0;
        });
    }

    // MLM Relationships
    public function mlmBinaryTree()
    {
        return $this->hasOne(MlmBinaryTree::class);
    }

    public function mlmBinaryVolumes()
    {
        return $this->hasMany(MlmBinaryVolume::class);
    }

    public function mlmCommissions()
    {
        return $this->hasMany(MlmCommission::class);
    }

    public function mlmCommissionsGenerated()
    {
        return $this->hasMany(MlmCommission::class, 'from_user_id');
    }

    public function mlmCurrentRank()
    {
        return $this->hasOne(MlmUserRank::class)->where('is_current', true);
    }

    public function mlmRankHistory()
    {
        return $this->hasMany(MlmUserRank::class);
    }

    public function sponsoredUsers()
    {
        return $this->hasMany(User::class, 'sponsor_id');
    }

    public function sponsorUser()
    {
        return $this->belongsTo(User::class, 'sponsor_id');
    }

    // Direct binary tree relationships
    public function upline()
    {
        return $this->belongsTo(User::class, 'upline_id');
    }

    public function downlines()
    {
        return $this->hasMany(User::class, 'upline_id');
    }

    public function leftDownline()
    {
        return $this->hasOne(User::class, 'upline_id')->where('position', 'left');
    }

    public function rightDownline()
    {
        return $this->hasOne(User::class, 'upline_id')->where('position', 'right');
    }

    public function leftDownlines()
    {
        return $this->hasMany(User::class, 'upline_id')->where('position', 'left');
    }

    public function rightDownlines()
    {
        return $this->hasMany(User::class, 'upline_id')->where('position', 'right');
    }

    // Legacy binary tree relationships (keeping for backward compatibility)
    public function leftLegUsers()
    {
        return $this->hasMany(MlmBinaryTree::class, 'parent_id')
                   ->where('position', 'left');
    }

    public function rightLegUsers()
    {
        return $this->hasMany(MlmBinaryTree::class, 'parent_id')
                   ->where('position', 'right');
    }

    /**
     * Get all binary matching records for this user
     */
    public function binaryMatchings()
    {
        return $this->hasMany(BinaryMatching::class);
    }

    /**
     * Get the binary summary for this user
     */
    public function binarySummary()
    {
        return $this->hasOne(BinarySummary::class);
    }

    /**
     * Get binary matching records by period
     */
    public function binaryMatchingsByPeriod($period = 'daily')
    {
        return $this->binaryMatchings()->byPeriod($period);
    }

    /**
     * Get pending binary matchings
     */
    public function pendingBinaryMatchings()
    {
        return $this->binaryMatchings()->pending();
    }

    /**
     * Get processed binary matchings
     */
    public function processedBinaryMatchings()
    {
        return $this->binaryMatchings()->processed();
    }

    /**
     * Get or create binary summary
     */
    public function getOrCreateBinarySummary()
    {
        return $this->binarySummary ?: $this->binarySummary()->create([]);
    }

    /**
     * Get wallet balance by type
     */
    public function getWalletBalance($walletType)
    {
        switch ($walletType) {
            case 'main':
            case 'balance':
                return (float) ($this->balance ?? 0);
            case 'deposit':
            case 'deposit_wallet':
                return (float) ($this->deposit_wallet ?? 0);
            case 'interest':
            case 'interest_wallet':
                return (float) ($this->interest_wallet ?? 0);
            case 'bonus':
            case 'available':
                return (float) ($this->available_balance ?? 0);
            default:
                return 0.0;
        }
    }

    /**
     * Add amount to wallet
     */
    public function addToWallet($walletType, $amount)
    {
        $amount = (float) $amount;
        
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount must be greater than 0');
        }

        switch ($walletType) {
            case 'main':
            case 'balance':
                $this->balance = (float) ($this->balance ?? 0) + $amount;
                break;
            case 'deposit':
            case 'deposit_wallet':
                $this->deposit_wallet = (float) ($this->deposit_wallet ?? 0) + $amount;
                break;
            case 'interest':
            case 'interest_wallet':
                $this->interest_wallet = (float) ($this->interest_wallet ?? 0) + $amount;
                break;
            case 'bonus':
            case 'available':
                $this->available_balance = (float) ($this->available_balance ?? 0) + $amount;
                break;
            default:
                throw new \InvalidArgumentException('Invalid wallet type: ' . $walletType);
        }
        
        $this->save();
        return $this;
    }

    /**
     * Deduct amount from wallet
     */
    public function deductFromWallet($walletType, $amount)
    {
        $amount = (float) $amount;
        
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount must be greater than 0');
        }

        $currentBalance = $this->getWalletBalance($walletType);
        
        if ($currentBalance < $amount) {
            throw new \Exception("Insufficient balance in {$walletType} wallet. Available: " . formatCurrency($currentBalance) . ", Required: " . formatCurrency($amount));
        }

        switch ($walletType) {
            case 'main':
            case 'balance':
                $this->balance = (float) ($this->balance ?? 0) - $amount;
                break;
            case 'deposit':
            case 'deposit_wallet':
                $this->deposit_wallet = (float) ($this->deposit_wallet ?? 0) - $amount;
                break;
            case 'interest':
            case 'interest_wallet':
                $this->interest_wallet = (float) ($this->interest_wallet ?? 0) - $amount;
                break;
            case 'bonus':
            case 'available':
                $this->available_balance = (float) ($this->available_balance ?? 0) - $amount;
                break;
            default:
                throw new \InvalidArgumentException('Invalid wallet type: ' . $walletType);
        }
        
        $this->save();
        return $this;
    }

    /**
     * Check if user has sufficient balance in wallet
     */
    public function hasSufficientBalance($walletType, $amount)
    {
        $amount = (float) $amount;
        return $this->getWalletBalance($walletType) >= $amount;
    }

    /**
     * Get wallet type display name
     */
    public function getWalletDisplayName($walletType)
    {
        $names = [
            'main' => 'Main Wallet',
            'balance' => 'Main Wallet',
            'deposit' => 'Deposit Wallet',
            'deposit_wallet' => 'Deposit Wallet',
            'interest' => 'Interest Wallet',
            'interest_wallet' => 'Interest Wallet',
            'bonus' => 'Bonus Wallet',
            'available' => 'Bonus Wallet',
        ];

        return $names[$walletType] ?? ucfirst(str_replace('_', ' ', $walletType));
    }

    /**
     * Get all wallet balances
     */
    public function getAllWalletBalances()
    {
        return [
            'main' => $this->getWalletBalance('main'),
            'deposit_wallet' => $this->getWalletBalance('deposit_wallet'),
            'interest_wallet' => $this->getWalletBalance('interest_wallet'),
            'bonus' => $this->getWalletBalance('bonus'),
        ];
    }

    /**
     * Mini vendors assigned by this user (when user is main vendor)
     */
    public function assignedMiniVendors()
    {
        return $this->hasMany(MiniVendor::class, 'vendor_id');
    }

    /**
     * Mini vendor record where this user is the affiliate
     */
    public function miniVendorRecord()
    {
        return $this->hasOne(MiniVendor::class, 'affiliate_id');
    }

    /**
     * Check if user is a mini vendor for any main vendor
     */
    public function isMiniVendor(): bool
    {
        return $this->miniVendorRecord()->where('status', 'active')->exists();
    }

    /**
     * Check if user can assign mini vendors (is a vendor)
     */
    public function canAssignMiniVendors(): bool
    {
        return $this->user_type === 'vendor' && $this->status === 'active';
    }

    /**
     * Get active mini vendors assigned by this vendor
     */
    public function getActiveMiniVendors()
    {
        return $this->assignedMiniVendors()->active()->with('affiliate')->get();
    }

    /**
     * Get potential affiliate users from same district for mini vendor assignment
     */
    public function getPotentialMiniVendors()
    {
        // If vendor has no district set, return empty collection
        if (empty($this->district)) {
            return collect();
        }
        
        return User::where('district', $this->district)
            ->where('id', '!=', $this->id)
            ->where('role', 'affiliate')
            ->where('status', 'active')
            ->whereDoesntHave('miniVendorRecord', function($query) {
                $query->where('status', 'active');
            })
            ->select('id', 'name', 'email', 'username', 'district')
            ->get();
    }
}
