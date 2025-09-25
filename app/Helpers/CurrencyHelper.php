<?php

if (!function_exists('formatCurrency')) {
    /**
     * Format currency amount using Bangladeshi Taka
     *
     * @param float $amount
     * @param bool $bangladeshiStyle Use Lakh/Crore format
     * @return string
     */
    function formatCurrency($amount, $bangladeshiStyle = false)
    {
        $symbol = config('currency.currency_symbol', '৳');
        $position = config('currency.symbol_position', 'before');
        $decimals = config('currency.decimal_places', 2);
        $decimalSeparator = config('currency.decimal_separator', '.');
        $thousandsSeparator = config('currency.thousands_separator', ',');
        
        if ($bangladeshiStyle) {
            return formatCurrencyBangladeshi($amount);
        }
        
        // Hide .00 for whole numbers if configured
        if (config('currency.formatting.show_zero_decimals', false) === false && $amount == floor($amount)) {
            $decimals = 0;
        }
        
        $formattedAmount = number_format($amount, $decimals, $decimalSeparator, $thousandsSeparator);
        
        if ($position === 'after') {
            return $formattedAmount . ' ' . $symbol;
        } else {
            return $symbol . ' ' . $formattedAmount;
        }
    }
}

if (!function_exists('formatCurrencyBangladeshi')) {
    /**
     * Format currency in Bangladeshi style (Lakh/Crore)
     *
     * @param float $amount
     * @return string
     */
    function formatCurrencyBangladeshi($amount)
    {
        $symbol = config('currency.currency_symbol', '৳');
        
        if ($amount >= 10000000) { // 1 Crore = 1,00,00,000
            $crores = $amount / 10000000;
            return $symbol . ' ' . number_format($crores, 2) . ' Cr';
        } elseif ($amount >= 100000) { // 1 Lakh = 1,00,000
            $lakhs = $amount / 100000;
            return $symbol . ' ' . number_format($lakhs, 2) . ' L';
        } elseif ($amount >= 1000) { // 1 Thousand
            $thousands = $amount / 1000;
            return $symbol . ' ' . number_format($thousands, 2) . 'K';
        } else {
            return $symbol . ' ' . number_format($amount, 2);
        }
    }
}

if (!function_exists('getCurrencySymbol')) {
    /**
     * Get currency symbol
     *
     * @param string $currencyCode
     * @return string
     */
    function getCurrencySymbol($currencyCode = null)
    {
        $currencyCode = $currencyCode ?: config('currency.default_currency', 'BDT');
        
        return config("currency.symbols.{$currencyCode}", config('currency.currency_symbol', '৳'));
    }
}

if (!function_exists('getCurrencyName')) {
    /**
     * Get currency name
     *
     * @param string $currencyCode
     * @return string
     */
    function getCurrencyName($currencyCode = null)
    {
        $currencyCode = $currencyCode ?: config('currency.default_currency', 'BDT');
        
        return config("currency.names.{$currencyCode}", config('currency.currency_name', 'Bangladeshi Taka'));
    }
}

if (!function_exists('convertCurrency')) {
    /**
     * Convert currency amount from one currency to another
     *
     * @param float $amount
     * @param string $fromCurrency
     * @param string $toCurrency
     * @return float
     */
    function convertCurrency($amount, $fromCurrency = 'BDT', $toCurrency = 'BDT')
    {
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }
        
        $fromRate = config("currency.exchange_rates.{$fromCurrency}", 1);
        $toRate = config("currency.exchange_rates.{$toCurrency}", 1);
        
        // Convert to base currency (BDT) first, then to target currency
        $bdtAmount = $amount / $fromRate;
        return $bdtAmount * $toRate;
    }
}

if (!function_exists('formatCurrencyInput')) {
    /**
     * Format currency for input fields (without symbol)
     *
     * @param float $amount
     * @return string
     */
    function formatCurrencyInput($amount)
    {
        $decimals = config('currency.decimal_places', 2);
        $decimalSeparator = config('currency.decimal_separator', '.');
        $thousandsSeparator = config('currency.thousands_separator', ',');
        
        return number_format($amount, $decimals, $decimalSeparator, $thousandsSeparator);
    }
}

if (!function_exists('parseCurrencyInput')) {
    /**
     * Parse currency input string to float
     *
     * @param string $input
     * @return float
     */
    function parseCurrencyInput($input)
    {
        // Remove currency symbol and spaces
        $cleaned = preg_replace('/[৳$€£¥₹\s]/', '', $input);
        
        // Handle Bangladeshi short forms
        if (stripos($cleaned, 'cr') !== false) {
            $cleaned = str_replace(['cr', 'Cr', 'CR'], '', $cleaned);
            return (float) $cleaned * 10000000; // 1 Crore
        }
        
        if (stripos($cleaned, 'l') !== false) {
            $cleaned = str_replace(['l', 'L'], '', $cleaned);
            return (float) $cleaned * 100000; // 1 Lakh
        }
        
        if (stripos($cleaned, 'k') !== false) {
            $cleaned = str_replace(['k', 'K'], '', $cleaned);
            return (float) $cleaned * 1000; // 1 Thousand
        }
        
        // Remove thousands separator and convert
        $thousandsSeparator = config('currency.thousands_separator', ',');
        $cleaned = str_replace($thousandsSeparator, '', $cleaned);
        
        return (float) $cleaned;
    }
}
