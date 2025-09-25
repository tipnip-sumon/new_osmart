<?php

namespace App\Helpers;

use App\Models\User;

class ProfileVerificationHelper
{
    /**
     * Check if user profile is complete
     */
    public static function isProfileComplete(User $user): bool
    {
        return $user->profile_completed_at !== null;
    }

    /**
     * Check if user is fully verified (email + phone + kyc + profile)
     */
    public static function isFullyVerified(User $user): bool
    {
        return $user->is_email_verified && 
               $user->is_sms_verified && 
               $user->is_kyc_verified && 
               self::isProfileComplete($user);
    }

    /**
     * Check if user can perform withdrawals
     */
    public static function canWithdraw(User $user): bool
    {
        return self::isFullyVerified($user) && 
               $user->is_active && 
               !$user->locked_until && 
               $user->available_balance > 0;
    }

    /**
     * Check if user can perform transfers
     */
    public static function canTransfer(User $user): bool
    {
        return self::isFullyVerified($user) && 
               $user->is_active && 
               !$user->locked_until;
    }

    /**
     * Get required profile fields based on user role
     */
    public static function getRequiredFields(User $user): array
    {
        $baseFields = [
            'name',
            'email', 
            'phone',
            'date_of_birth',
            'gender',
            'country'
        ];

        // Add Bangladesh location fields if country is Bangladesh
        if ($user->country === 'Bangladesh') {
            $baseFields = array_merge($baseFields, [
                'district',
                'upazila', 
                'union_ward'
            ]);
        }

        // Add address for all users
        $baseFields[] = 'address';

        // Add role-specific fields
        if ($user->role === 'vendor') {
            $baseFields = array_merge($baseFields, [
                'shop_name',
                'shop_description',
                'shop_address'
            ]);
        }

        return $baseFields;
    }

    /**
     * Get missing required fields
     */
    public static function getMissingFields(User $user): array
    {
        $requiredFields = self::getRequiredFields($user);
        $missingFields = [];

        foreach ($requiredFields as $field) {
            if (empty($user->{$field})) {
                $missingFields[] = $field;
            }
        }

        return $missingFields;
    }

    /**
     * Get missing verifications
     */
    public static function getMissingVerifications(User $user): array
    {
        $missing = [];

        if (!$user->is_email_verified) {
            $missing[] = 'email_verification';
        }

        if (!$user->is_sms_verified) {
            $missing[] = 'phone_verification';
        }

        if (!$user->is_kyc_verified) {
            $missing[] = 'kyc_verification';
        }

        return $missing;
    }

    /**
     * Calculate profile completion percentage
     */
    public static function calculateCompletionPercentage(User $user): int
    {
        $requiredFields = self::getRequiredFields($user);
        $completedFields = 0;
        $totalFields = count($requiredFields);

        // Check required fields
        foreach ($requiredFields as $field) {
            if (!empty($user->{$field})) {
                $completedFields++;
            }
        }

        // Add verification statuses (3 additional items)
        $totalFields += 3;
        if ($user->is_email_verified) $completedFields++;
        if ($user->is_sms_verified) $completedFields++;
        if ($user->is_kyc_verified) $completedFields++;

        return $totalFields > 0 ? round(($completedFields / $totalFields) * 100) : 0;
    }

    /**
     * Get user verification status summary
     */
    public static function getVerificationStatus(User $user): array
    {
        $missingFields = self::getMissingFields($user);
        $missingVerifications = self::getMissingVerifications($user);
        $completionPercentage = self::calculateCompletionPercentage($user);

        return [
            'is_profile_complete' => self::isProfileComplete($user),
            'is_fully_verified' => self::isFullyVerified($user),
            'can_withdraw' => self::canWithdraw($user),
            'can_transfer' => self::canTransfer($user),
            'completion_percentage' => $completionPercentage,
            'missing_fields' => $missingFields,
            'missing_verifications' => $missingVerifications,
            'required_actions' => self::getRequiredActions($user),
            'verification_steps' => self::getVerificationSteps($user)
        ];
    }

    /**
     * Get required actions for user to complete verification
     */
    public static function getRequiredActions(User $user): array
    {
        $actions = [];
        $missingFields = self::getMissingFields($user);
        $missingVerifications = self::getMissingVerifications($user);

        // Profile completion actions
        if (!empty($missingFields)) {
            $actions[] = [
                'type' => 'profile_completion',
                'title' => 'Complete Profile Information',
                'description' => 'Fill in missing required fields: ' . implode(', ', self::formatFieldNames($missingFields)),
                'url' => route('member.profile'),
                'priority' => 'high',
                'icon' => 'fe-user'
            ];
        }

        // Verification actions
        if (in_array('email_verification', $missingVerifications)) {
            $actions[] = [
                'type' => 'email_verification',
                'title' => 'Verify Email Address',
                'description' => 'Check your email and click the verification link',
                'url' => route('verification.notice'),
                'priority' => 'high',
                'icon' => 'fe-mail'
            ];
        }

        if (in_array('phone_verification', $missingVerifications)) {
            $actions[] = [
                'type' => 'phone_verification',
                'title' => 'Verify Phone Number',
                'description' => 'Verify your phone number with OTP',
                'url' => route('member.profile'),
                'priority' => 'high',
                'icon' => 'fe-phone'
            ];
        }

        if (in_array('kyc_verification', $missingVerifications)) {
            $actions[] = [
                'type' => 'kyc_verification',
                'title' => 'Complete KYC Verification',
                'description' => 'Submit your identity documents for verification',
                'url' => route('member.kyc.index'),
                'priority' => 'high',
                'icon' => 'fe-file-text'
            ];
        }

        return $actions;
    }

    /**
     * Get verification steps with status
     */
    public static function getVerificationSteps(User $user): array
    {
        return [
            [
                'step' => 1,
                'title' => 'Email Verification',
                'description' => 'Verify your email address',
                'status' => $user->is_email_verified ? 'completed' : 'pending',
                'icon' => 'fe-mail'
            ],
            [
                'step' => 2,
                'title' => 'Phone Verification',
                'description' => 'Verify your phone number',
                'status' => $user->is_sms_verified ? 'completed' : 'pending',
                'icon' => 'fe-phone'
            ],
            [
                'step' => 3,
                'title' => 'Profile Completion',
                'description' => 'Complete your profile information',
                'status' => empty(self::getMissingFields($user)) ? 'completed' : 'pending',
                'icon' => 'fe-user'
            ],
            [
                'step' => 4,
                'title' => 'KYC Verification',
                'description' => 'Submit identity documents',
                'status' => $user->is_kyc_verified ? 'completed' : 'pending',
                'icon' => 'fe-file-text'
            ]
        ];
    }

    /**
     * Update user profile completion status
     */
    public static function updateProfileCompletion(User $user): bool
    {
        $completionPercentage = self::calculateCompletionPercentage($user);
        $missingFields = self::getMissingFields($user);
        $missingVerifications = self::getMissingVerifications($user);

        // Update completion data
        $user->profile_completion_percentage = $completionPercentage;
        $user->required_fields_completed = array_merge($missingFields, $missingVerifications);

        // Mark profile as completed if all requirements are met
        if ($completionPercentage >= 100 && empty($missingFields) && empty($missingVerifications)) {
            if (!$user->profile_completed_at) {
                $user->profile_completed_at = now();
            }
        } else {
            // Reset completion if requirements are not met
            $user->profile_completed_at = null;
        }

        return $user->save();
    }

    /**
     * Format field names for display
     */
    private static function formatFieldNames(array $fields): array
    {
        $fieldMap = [
            'name' => 'Full Name',
            'email' => 'Email Address',
            'phone' => 'Phone Number',
            'date_of_birth' => 'Date of Birth',
            'gender' => 'Gender',
            'country' => 'Country',
            'district' => 'District',
            'upazila' => 'Upazila',
            'union_ward' => 'Union/Ward',
            'address' => 'Address',
            'shop_name' => 'Shop Name',
            'shop_description' => 'Shop Description',
            'shop_address' => 'Shop Address',
            'email_verification' => 'Email Verification',
            'phone_verification' => 'Phone Verification',
            'kyc_verification' => 'KYC Verification'
        ];

        return array_map(function($field) use ($fieldMap) {
            return $fieldMap[$field] ?? ucfirst(str_replace('_', ' ', $field));
        }, $fields);
    }

    /**
     * Generate phone verification token
     */
    public static function generatePhoneVerificationToken(User $user): string
    {
        $token = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        
        $user->phone_verification_token = $token;
        $user->phone_verification_token_expires_at = now()->addMinutes(10); // 10 minutes expiry
        $user->save();

        return $token;
    }

    /**
     * Verify phone with token
     */
    public static function verifyPhoneToken(User $user, string $token): bool
    {
        if (!$user->phone_verification_token || 
            !$user->phone_verification_token_expires_at ||
            $user->phone_verification_token_expires_at->isPast()) {
            return false;
        }

        if ($user->phone_verification_token === $token) {
            $user->sv = 1;
            $user->phone_verified_at = now();
            $user->phone_verification_token = null;
            $user->phone_verification_token_expires_at = null;
            $user->save();

            // Update profile completion
            self::updateProfileCompletion($user);

            return true;
        }

        return false;
    }

    /**
     * Check if user has access to feature
     */
    public static function hasAccessToFeature(User $user, string $feature): array
    {
        $response = [
            'has_access' => false,
            'message' => '',
            'missing_requirements' => []
        ];

        $status = self::getVerificationStatus($user);

        switch ($feature) {
            case 'withdraw':
            case 'withdrawal':
                $response['has_access'] = $status['can_withdraw'];
                if (!$response['has_access']) {
                    $response['message'] = 'Complete your profile verification to enable withdrawals.';
                    $response['missing_requirements'] = array_merge($status['missing_fields'], $status['missing_verifications']);
                }
                break;

            case 'transfer':
            case 'fund_transfer':
                $response['has_access'] = $status['can_transfer'];
                if (!$response['has_access']) {
                    $response['message'] = 'Complete your profile verification to enable transfers.';
                    $response['missing_requirements'] = array_merge($status['missing_fields'], $status['missing_verifications']);
                }
                break;

            case 'kyc':
                $response['has_access'] = $user->is_email_verified && $user->is_sms_verified;
                if (!$response['has_access']) {
                    $response['message'] = 'Complete email and phone verification before submitting KYC.';
                    $response['missing_requirements'] = $status['missing_verifications'];
                }
                break;

            case 'vendor_application':
                $response['has_access'] = $status['is_fully_verified'];
                if (!$response['has_access']) {
                    $response['message'] = 'Complete full verification to apply as vendor.';
                    $response['missing_requirements'] = array_merge($status['missing_fields'], $status['missing_verifications']);
                }
                break;

            default:
                $response['has_access'] = true;
                break;
        }

        return $response;
    }
}