<?php

namespace App\Services;

use App\Models\User;
use App\Models\Product;

class TaxCalculationService
{
    protected $config;

    public function __construct()
    {
        $this->config = config('tax');
    }

    /**
     * Calculate tax for an order
     */
    public function calculateTax($subtotal, $location = null, $cartItems = [], $userId = null)
    {
        // Check if Bangladesh tax-free system is enabled
        if ($this->isBangladeshTaxFree($location)) {
            return [
                'rate' => 0,
                'amount' => 0,
                'breakdown' => [],
                'message' => $this->config['display']['tax_free_message'] ?? 'Tax-free shopping!',
            ];
        }

        if (!$this->config['dynamic']['enabled']) {
            return $this->calculateSimpleTax($subtotal);
        }

        $taxRate = $this->getDynamicTaxRate($subtotal, $location, $cartItems, $userId);
        $taxAmount = ($subtotal * $taxRate) / 100;

        return [
            'rate' => $taxRate,
            'amount' => round($taxAmount, 2),
            'breakdown' => $this->getTaxBreakdown($subtotal, $location, $cartItems, $userId),
        ];
    }

    /**
     * Check if Bangladesh tax-free system applies
     */
    protected function isBangladeshTaxFree($location)
    {
        // Check if all countries including BD are tax-exempt
        $exemptCountries = $this->config['exemptions']['countries'] ?? [];
        
        if (in_array('BD', $exemptCountries)) {
            return true;
        }

        // Check if location is Bangladesh area and tax is disabled
        if ($location && in_array($location, ['dhaka', 'chittagong', 'rajshahi', 'khulna', 'barisal', 'sylhet', 'rangpur', 'mymensingh'])) {
            return $this->config['default_rate'] == 0 && !$this->config['dynamic']['enabled'];
        }

        return false;
    }

    /**
     * Get dynamic tax rate based on multiple factors
     */
    protected function getDynamicTaxRate($subtotal, $location, $cartItems, $userId)
    {
        $baseRate = $this->getAmountBasedRate($subtotal);
        $locationRate = $this->getLocationBasedRate($location);
        $productRate = $this->getProductBasedRate($cartItems);
        
        // Use the highest applicable rate as base
        $taxRate = max($baseRate, $locationRate, $productRate);
        
        // Apply special discounts
        $taxRate = $this->applySpecialDiscounts($taxRate, $subtotal, $cartItems, $userId);
        
        return max(0, $taxRate); // Ensure tax rate is not negative
    }

    /**
     * Calculate tax based on order amount
     */
    protected function getAmountBasedRate($subtotal)
    {
        $rates = $this->config['dynamic']['by_amount']['rates'];
        
        foreach ($rates as $tier) {
            if ($subtotal >= $tier['min'] && ($tier['max'] === null || $subtotal < $tier['max'])) {
                return $tier['rate'];
            }
        }
        
        return $this->config['default_rate'];
    }

    /**
     * Calculate tax based on location
     */
    protected function getLocationBasedRate($location)
    {
        if (!$this->config['dynamic']['by_location']['enabled'] || !$location) {
            return 0;
        }

        $locationRates = $this->config['dynamic']['by_location'];
        
        return $locationRates[$location] ?? $locationRates['other'] ?? 0;
    }

    /**
     * Calculate tax based on product types in cart
     */
    protected function getProductBasedRate($cartItems)
    {
        if (!$this->config['dynamic']['by_product_type']['enabled'] || empty($cartItems)) {
            return 0;
        }

        $totalValue = 0;
        $taxableValue = 0;
        $categoryRates = $this->config['dynamic']['by_product_type']['categories'];

        foreach ($cartItems as $item) {
            $itemValue = $item['price'] * $item['quantity'];
            $totalValue += $itemValue;

            // Get product category (this would need to be passed in cartItems or fetched)
            $category = $item['category'] ?? 'other';
            $categoryRate = $categoryRates[$category] ?? $this->config['default_rate'];
            
            $taxableValue += ($itemValue * $categoryRate) / 100;
        }

        return $totalValue > 0 ? ($taxableValue / $totalValue) * 100 : 0;
    }

    /**
     * Apply special tax discounts
     */
    protected function applySpecialDiscounts($taxRate, $subtotal, $cartItems, $userId)
    {
        $specialRules = $this->config['dynamic']['special_rules'];
        
        // Bulk discount
        if ($specialRules['bulk_discount']['enabled']) {
            $totalItems = array_sum(array_column($cartItems, 'quantity'));
            if ($totalItems >= $specialRules['bulk_discount']['min_items']) {
                $discountPercent = $specialRules['bulk_discount']['discount_rate'];
                $taxRate = $taxRate * (1 - $discountPercent / 100);
            }
        }

        // First-time customer discount
        if ($specialRules['first_time_customer']['enabled'] && $userId) {
            if ($this->isFirstTimeCustomer($userId)) {
                $discountPercent = $specialRules['first_time_customer']['discount_rate'];
                $taxRate = $taxRate * (1 - $discountPercent / 100);
            }
        }

        // Premium customer discount
        if ($specialRules['premium_customer']['enabled'] && $userId) {
            if ($this->isPremiumCustomer($userId)) {
                $discountPercent = $specialRules['premium_customer']['discount_rate'];
                $taxRate = $taxRate * (1 - $discountPercent / 100);
            }
        }

        return $taxRate;
    }

    /**
     * Get tax breakdown for display
     */
    protected function getTaxBreakdown($subtotal, $location, $cartItems, $userId)
    {
        $breakdown = [];
        
        $amountRate = $this->getAmountBasedRate($subtotal);
        if ($amountRate > 0) {
            $breakdown[] = [
                'type' => 'Amount-based',
                'rate' => $amountRate,
                'description' => $this->getAmountBasedDescription($subtotal)
            ];
        }

        $locationRate = $this->getLocationBasedRate($location);
        if ($locationRate > 0) {
            $breakdown[] = [
                'type' => 'Location-based',
                'rate' => $locationRate,
                'description' => ucfirst($location) . ' area tax'
            ];
        }

        return $breakdown;
    }

    /**
     * Get description for amount-based tax
     */
    protected function getAmountBasedDescription($subtotal)
    {
        $rates = $this->config['dynamic']['by_amount']['rates'];
        
        foreach ($rates as $tier) {
            if ($subtotal >= $tier['min'] && ($tier['max'] === null || $subtotal < $tier['max'])) {
                if ($tier['rate'] == 0) {
                    return 'Tax-free for orders under ৳' . $tier['max'];
                }
                return $tier['rate'] . '% tax for orders ৳' . $tier['min'] . '+';
            }
        }
        
        return 'Standard tax rate';
    }

    /**
     * Simple tax calculation fallback
     */
    protected function calculateSimpleTax($subtotal)
    {
        $rate = $this->config['default_rate'];
        $amount = ($subtotal * $rate) / 100;

        return [
            'rate' => $rate,
            'amount' => round($amount, 2),
            'breakdown' => []
        ];
    }

    /**
     * Check if user is first-time customer
     */
    protected function isFirstTimeCustomer($userId)
    {
        // This would check if user has any previous orders
        // For now, return false as placeholder
        return false;
    }

    /**
     * Check if user is premium customer
     */
    protected function isPremiumCustomer($userId)
    {
        // This would check user's premium status
        // For now, return false as placeholder
        return false;
    }

    /**
     * Get tax information for display
     */
    public function getTaxInfo($subtotal, $location = null, $cartItems = [], $userId = null)
    {
        $taxData = $this->calculateTax($subtotal, $location, $cartItems, $userId);
        
        return [
            'label' => $this->config['label'],
            'rate' => $taxData['rate'],
            'amount' => $taxData['amount'],
            'formatted_amount' => '৳' . number_format($taxData['amount'], 2),
            'breakdown' => $taxData['breakdown'],
            'is_inclusive' => $this->config['tax_inclusive'],
            'show_breakdown' => $this->config['display']['show_tax_breakdown'],
            'is_tax_free' => $taxData['amount'] == 0,
            'message' => $taxData['message'] ?? null,
        ];
    }
}
