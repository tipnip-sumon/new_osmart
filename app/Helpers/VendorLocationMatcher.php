<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class VendorLocationMatcher
{
    /**
     * Find vendors that match the user's location (country, district, upazila)
     *
     * @param User $user
     * @return Collection
     */
    public static function getMatchingVendors(User $user): Collection
    {
        try {
            // Get user's location details
            $userCountry = $user->country;
            $userDistrict = $user->district;
            $userUpazila = $user->upazila;

            // If user doesn't have location data, return empty collection
            if (empty($userCountry) || empty($userDistrict) || empty($userUpazila)) {
                Log::info('User location incomplete', [
                    'user_id' => $user->id,
                    'country' => $userCountry,
                    'district' => $userDistrict,
                    'upazila' => $userUpazila
                ]);
                return collect();
            }

            // Find vendors with matching location
            $matchingVendors = User::where('role', 'vendor')
                ->where('status', 'active')
                ->where('country', $userCountry)
                ->where('district', $userDistrict)
                ->where('upazila', $userUpazila)
                ->select([
                    'id', 
                    'name', 
                    'shop_name', 
                    'shop_description',
                    'shop_logo',
                    'phone',
                    'email',
                    'country',
                    'district', 
                    'upazila',
                    'city',
                    'address'
                ])
                ->get();

            Log::info('Location-based vendor matching', [
                'user_id' => $user->id,
                'user_location' => [
                    'country' => $userCountry,
                    'district' => $userDistrict,
                    'upazila' => $userUpazila
                ],
                'matching_vendors_count' => $matchingVendors->count(),
                'vendor_ids' => $matchingVendors->pluck('id')->toArray()
            ]);

            return $matchingVendors;

        } catch (\Exception $e) {
            Log::error('Error in vendor location matching', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return collect();
        }
    }

    /**
     * Check if user has matching vendors in their location
     *
     * @param User $user
     * @return bool
     */
    public static function hasMatchingVendors(User $user): bool
    {
        return self::getMatchingVendors($user)->isNotEmpty();
    }

    /**
     * Get user's complete location string for display
     *
     * @param User $user
     * @return string
     */
    public static function getUserLocationString(User $user): string
    {
        $locationParts = array_filter([
            $user->upazila,
            $user->district,
            $user->country
        ]);

        return implode(', ', $locationParts);
    }

    /**
     * Get vendor's complete location string for display
     *
     * @param User $vendor
     * @return string
     */
    public static function getVendorLocationString(User $vendor): string
    {
        $locationParts = array_filter([
            $vendor->upazila,
            $vendor->district,
            $vendor->country
        ]);

        return implode(', ', $locationParts);
    }

    /**
     * Get vendor fund request methods/options
     * This would be where you define how users can request funds from vendors
     *
     * @param User $vendor
     * @return array
     */
    public static function getVendorFundOptions(User $vendor): array
    {
        return [
            'vendor_id' => $vendor->id,
            'vendor_name' => $vendor->name,
            'shop_name' => $vendor->shop_name ?? $vendor->name,
            'shop_description' => $vendor->shop_description,
            'contact_phone' => $vendor->phone,
            'contact_email' => $vendor->email,
            'location' => self::getVendorLocationString($vendor),
            'payment_methods' => [
                'bkash',
                'nagad',
                'rocket',
                'upay',
                'bank_transfer'
            ]
        ];
    }

    /**
     * Get default company fund options
     *
     * @return array
     */
    public static function getCompanyFundOptions(): array
    {
        return [
            'vendor_id' => null,
            'vendor_name' => 'Company',
            'shop_name' => config('app.name', 'OSmart BD'),
            'shop_description' => 'Direct company fund request',
            'contact_phone' => config('company.phone', ''),
            'contact_email' => config('company.email', ''),
            'location' => 'Head Office',
            'payment_methods' => [
                'bkash',
                'nagad',
                'rocket',
                'upay',
                'bank_transfer'
            ]
        ];
    }
}