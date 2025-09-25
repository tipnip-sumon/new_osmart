<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Collection;

class LocationMatcher
{
    /**
     * Check if user has location information set
     *
     * @param User $user
     * @return bool
     */
    public static function hasLocationInfo(User $user): bool
    {
        return !empty($user->country) && !empty($user->district) && !empty($user->upazila);
    }

    /**
     * Get vendors matching user location
     *
     * @param User $user
     * @return Collection
     */
    public static function getMatchingVendors(User $user): Collection
    {
        if (!self::hasLocationInfo($user)) {
            return collect();
        }

        return User::where('role', 'vendor')
            ->where('status', 'active')
            ->where('country', $user->country)
            ->where('district', $user->district)
            ->where('upazila', $user->upazila)
            ->get();
    }

    /**
     * Get user location as string
     *
     * @param User $user
     * @return string
     */
    public static function getUserLocationString(User $user): string
    {
        $parts = array_filter([
            $user->upazila,
            $user->district,
            $user->country
        ]);

        return implode(', ', $parts);
    }

    /**
     * Get fund request options based on location matching
     *
     * @param User $user
     * @return array
     */
    public static function getFundOptions(User $user): array
    {
        $matchingVendors = self::getMatchingVendors($user);
        $options = [];

        // Add matching vendors
        foreach ($matchingVendors as $vendor) {
            $options[] = [
                'vendor_id' => $vendor->id,
                'vendor_name' => $vendor->name,
                'shop_name' => $vendor->shop_name ?: $vendor->name . "'s Shop",
                'shop_description' => $vendor->shop_description,
                'contact_phone' => $vendor->phone,
                'location' => self::getUserLocationString($vendor),
                'type' => 'vendor'
            ];
        }

        // Always add company direct option
        $options[] = [
            'vendor_id' => null,
            'vendor_name' => 'OSmart BD',
            'shop_name' => 'OSmart BD - Company Direct',
            'shop_description' => 'Submit your fund request directly to the company for processing.',
            'contact_phone' => null,
            'location' => 'All Locations',
            'type' => 'company'
        ];

        return $options;
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
}