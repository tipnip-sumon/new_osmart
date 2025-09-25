<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;

class ShippingCalculationService
{
    /**
     * Calculate shipping cost based on method, location, products, and user
     */
    public function calculateShippingCost($shippingMethod, $cartItems, $subtotal, $userId = null)
    {
        $shippingConfig = config('shipping');
        
        // Get base shipping rate for the method
        $baseRate = $shippingConfig['options'][$shippingMethod]['rate'] ?? 60;
        
        // Check if eligible for free shipping
        if ($this->isEligibleForFreeShipping($shippingMethod, $cartItems, $subtotal, $userId)) {
            return 0;
        }
        
        return $baseRate;
    }
    
    /**
     * Check if order is eligible for free shipping
     */
    public function isEligibleForFreeShipping($shippingMethod, $cartItems, $subtotal, $userId = null)
    {
        $freeShippingConfig = config('shipping.free_shipping');
        
        if (!$freeShippingConfig['enabled']) {
            return false;
        }
        
        // Check location-based free shipping
        if ($this->checkLocationBasedFreeShipping($shippingMethod, $subtotal, $freeShippingConfig)) {
            return true;
        }
        
        // Check product-based free shipping
        if ($this->checkProductBasedFreeShipping($cartItems, $freeShippingConfig)) {
            return true;
        }
        
        // Check user-based free shipping
        if ($this->checkUserBasedFreeShipping($userId, $freeShippingConfig)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Check location-based free shipping conditions
     */
    private function checkLocationBasedFreeShipping($shippingMethod, $subtotal, $config)
    {
        $locationConfig = $config['by_location'][$shippingMethod] ?? null;
        
        if (!$locationConfig || !$locationConfig['enabled']) {
            return false;
        }
        
        $minOrder = $locationConfig['minimum_order'] ?? 0;
        $maxOrder = $locationConfig['maximum_order'] ?? null;
        
        // Check minimum order amount
        if ($subtotal < $minOrder) {
            return false;
        }
        
        // Check maximum order amount (if set)
        if ($maxOrder !== null && $subtotal > $maxOrder) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Check product-based free shipping conditions
     */
    private function checkProductBasedFreeShipping($cartItems, $config)
    {
        $productConfig = $config['by_product'] ?? null;
        
        if (!$productConfig || !$productConfig['enabled']) {
            return false;
        }
        
        $eligibleProductIds = $productConfig['product_ids'] ?? [];
        $eligibleCategories = $productConfig['categories'] ?? [];
        $eligibleTags = $productConfig['tags'] ?? [];
        
        foreach ($cartItems as $item) {
            $productId = $item['product_id'] ?? null;
            
            if (!$productId) {
                continue;
            }
            
            // Check if product ID is in eligible list
            if (in_array($productId, $eligibleProductIds)) {
                return true;
            }
            
            // Check product category and tags (requires database lookup)
            if (!empty($eligibleCategories) || !empty($eligibleTags)) {
                $product = Product::find($productId);
                
                if ($product) {
                    // Check category
                    if (!empty($eligibleCategories) && in_array($product->category_id, $eligibleCategories)) {
                        return true;
                    }
                    
                    // Check tags (assuming products have a tags field or relationship)
                    if (!empty($eligibleTags) && $product->tags) {
                        $productTags = is_string($product->tags) ? json_decode($product->tags, true) : $product->tags;
                        if (is_array($productTags) && array_intersect($eligibleTags, $productTags)) {
                            return true;
                        }
                    }
                }
            }
        }
        
        return false;
    }
    
    /**
     * Check user-based free shipping conditions
     */
    private function checkUserBasedFreeShipping($userId, $config)
    {
        $userConfig = $config['by_user'] ?? null;
        
        if (!$userConfig || !$userConfig['enabled'] || !$userId) {
            return false;
        }
        
        $user = User::find($userId);
        
        if (!$user) {
            return false;
        }
        
        // Check if premium user
        if ($userConfig['premium_users'] && $this->isPremiumUser($user)) {
            return true;
        }
        
        // Check if first order
        if ($userConfig['first_order'] && $this->isFirstOrder($user)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Check if user is premium/VIP
     */
    private function isPremiumUser($user)
    {
        // Add your logic for determining premium users
        // This could be based on user type, subscription, total orders, etc.
        return $user->user_type === 'premium' || $user->is_vip ?? false;
    }
    
    /**
     * Check if this is user's first order
     */
    private function isFirstOrder($user)
    {
        // Check if user has any previous orders
        return $user->orders()->count() === 0;
    }
    
    /**
     * Get free shipping message for display
     */
    public function getFreeShippingMessage($shippingMethod, $subtotal, $userId = null)
    {
        $config = config('shipping.free_shipping.by_location')[$shippingMethod] ?? null;
        
        if (!$config || !$config['enabled']) {
            return null;
        }
        
        $currency = config('shipping.currency', '৳');
        $minOrder = $config['minimum_order'] ?? 0;
        $maxOrder = $config['maximum_order'] ?? null;
        
        if ($subtotal >= $minOrder && ($maxOrder === null || $subtotal <= $maxOrder)) {
            return "✅ You qualify for FREE shipping!";
        } elseif ($subtotal < $minOrder) {
            $remaining = $minOrder - $subtotal;
            return "Add {$currency}{$remaining} more for FREE shipping in " . ucfirst(str_replace('_', ' ', $shippingMethod));
        } elseif ($maxOrder !== null && $subtotal > $maxOrder) {
            return "Premium orders over {$currency}{$maxOrder} use express shipping.";
        }
        
        return null;
    }
}
