<?php

if (!function_exists('getSetting')) {
    /**
     * Get a specific setting value.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function getSetting($key, $default = null)
    {
        return \App\Models\GeneralSetting::getSetting($key, $default);
    }
}

if (!function_exists('getSettings')) {
    /**
     * Get all settings.
     *
     * @return \App\Models\GeneralSetting
     */
    function getSettings()
    {
        return \App\Models\GeneralSetting::getSettings();
    }
}

if (!function_exists('generalSettings')) {
    /**
     * Get all settings (alias for getSettings).
     *
     * @return \App\Models\GeneralSetting
     */
    function generalSettings()
    {
        return \App\Models\GeneralSetting::getSettings();
    }
}

if (!function_exists('siteName')) {
    /**
     * Get site name.
     *
     * @return string
     */
    function siteName()
    {
        return \App\Models\GeneralSetting::getSiteName();
    }
}

if (!function_exists('currencySymbol')) {
    /**
     * Get currency symbol.
     *
     * @return string
     */
    function currencySymbol()
    {
        return \App\Models\GeneralSetting::getCurrencySymbol();
    }
}

if (!function_exists('currencyText')) {
    /**
     * Get currency text.
     *
     * @return string
     */
    function currencyText()
    {
        return \App\Models\GeneralSetting::getCurrencyText();
    }
}

if (!function_exists('isMaintenanceMode')) {
    /**
     * Check if maintenance mode is active.
     *
     * @return bool
     */
    function isMaintenanceMode()
    {
        return \App\Models\GeneralSetting::isMaintenanceMode();
    }
}

if (!function_exists('isRegistrationEnabled')) {
    /**
     * Check if registration is enabled.
     *
     * @return bool
     */
    function isRegistrationEnabled()
    {
        return \App\Models\GeneralSetting::isRegistrationEnabled();
    }
}

if (!function_exists('formatCurrency')) {
    /**
     * Format currency with symbol.
     *
     * @param float $amount
     * @return string
     */
    function formatCurrency($amount)
    {
        return currencySymbol() . number_format($amount, 2);
    }
}

if (!function_exists('primaryColor')) {
    /**
     * Get primary color.
     *
     * @return string
     */
    function primaryColor()
    {
        return getSetting('base_color', '#007bff');
    }
}

if (!function_exists('secondaryColor')) {
    /**
     * Get secondary color.
     *
     * @return string
     */
    function secondaryColor()
    {
        return getSetting('secondary_color', '#6c757d');
    }
}

if (!function_exists('isEmailVerificationRequired')) {
    /**
     * Check if email verification is required.
     *
     * @return bool
     */
    function isEmailVerificationRequired()
    {
        return getSetting('ev', false);
    }
}

if (!function_exists('isSmsVerificationRequired')) {
    /**
     * Check if SMS verification is required.
     *
     * @return bool
     */
    function isSmsVerificationRequired()
    {
        return getSetting('sv', false);
    }
}

if (!function_exists('isKycVerificationRequired')) {
    /**
     * Check if KYC verification is required.
     *
     * @return bool
     */
    function isKycVerificationRequired()
    {
        return getSetting('kv', false);
    }
}

if (!function_exists('signupBonusAmount')) {
    /**
     * Get signup bonus amount.
     *
     * @return float
     */
    function signupBonusAmount()
    {
        return getSetting('signup_bonus_amount', 0);
    }
}

if (!function_exists('isSignupBonusEnabled')) {
    /**
     * Check if signup bonus is enabled.
     *
     * @return bool
     */
    function isSignupBonusEnabled()
    {
        return getSetting('signup_bonus_control', false);
    }
}

if (!function_exists('getFixedCharge')) {
    /**
     * Get fixed charge amount.
     *
     * @return float
     */
    function getFixedCharge()
    {
        return getSetting('f_charge', 0);
    }
}

if (!function_exists('getPercentageCharge')) {
    /**
     * Get percentage charge.
     *
     * @return float
     */
    function getPercentageCharge()
    {
        return getSetting('p_charge', 0);
    }
}

if (!function_exists('calculateCharge')) {
    /**
     * Calculate total charge for an amount.
     *
     * @param float $amount
     * @return float
     */
    function calculateCharge($amount)
    {
        $fixedCharge = getFixedCharge();
        $percentageCharge = getPercentageCharge();
        
        return $fixedCharge + ($amount * $percentageCharge / 100);
    }
}

if (!function_exists('isHolidayWithdrawEnabled')) {
    /**
     * Check if holiday withdraw is enabled.
     *
     * @return bool
     */
    function isHolidayWithdrawEnabled()
    {
        return getSetting('holiday_withdraw', false);
    }
}

if (!function_exists('isBalanceTransferEnabled')) {
    /**
     * Check if balance transfer is enabled.
     *
     * @return bool
     */
    function isBalanceTransferEnabled()
    {
        return getSetting('b_transfer', false);
    }
}

if (!function_exists('isDepositCommissionEnabled')) {
    /**
     * Check if deposit commission is enabled.
     *
     * @return bool
     */
    function isDepositCommissionEnabled()
    {
        return getSetting('deposit_commission', true);
    }
}

if (!function_exists('isInvestCommissionEnabled')) {
    /**
     * Check if invest commission is enabled.
     *
     * @return bool
     */
    function isInvestCommissionEnabled()
    {
        return getSetting('invest_commission', true);
    }
}

if (!function_exists('isInvestReturnCommissionEnabled')) {
    /**
     * Check if invest return commission is enabled.
     *
     * @return bool
     */
    function isInvestReturnCommissionEnabled()
    {
        return getSetting('invest_return_commission', true);
    }
}

if (!function_exists('siteLogo')) {
    /**
     * Get site logo URL.
     *
     * @return string
     */
    function siteLogo()
    {
        return \App\Models\GeneralSetting::getLogo();
    }
}

if (!function_exists('adminLogo')) { 
    /**
     * Get admin logo URL.
     *
     * @return string
     */
    function adminLogo()
    {
        return \App\Models\GeneralSetting::getAdminLogo();
    }
}

if (!function_exists('siteFavicon')) {
    /**
     * Get site favicon URL.
     *
     * @return string
     */
    function siteFavicon()
    {
        return \App\Models\GeneralSetting::getFavicon();
    }
}

if (!function_exists('siteMaintenanceImage')) {
    /**
     * Get site maintenance image URL.
     *
     * @param string $size
     * @return string|null
     */
    function siteMaintenanceImage($size = 'medium')
    {
        return \App\Models\GeneralSetting::getMaintenanceImage($size);
    }
}

if (!function_exists('seoMeta')) {
    /**
     * Get SEO meta data.
     *
     * @return array
     */
    function seoMeta()
    {
        return \App\Models\GeneralSetting::getSeoMeta();
    }
}

if (!function_exists('socialLinks')) {
    /**
     * Get social media links.
     *
     * @return array
     */
    function socialLinks()
    {
        return \App\Models\GeneralSetting::getSocialMediaLinks();
    }
}

if (!function_exists('contactInfo')) {
    /**
     * Get contact information.
     *
     * @return array
     */
    function contactInfo()
    {
        return \App\Models\GeneralSetting::getContactInfo();
    }
}

if (!function_exists('headerConfig')) {
    /**
     * Get header configuration.
     *
     * @return array
     */
    function headerConfig()
    {
        return \App\Models\GeneralSetting::getHeaderConfig();
    }
}

if (!function_exists('footerConfig')) {
    /**
     * Get footer configuration.
     *
     * @return array
     */
    function footerConfig()
    {
        return \App\Models\GeneralSetting::getFooterConfig();
    }
}

if (!function_exists('themeSettings')) {
    /**
     * Get theme settings.
     *
     * @return array
     */
    function themeSettings()
    {
        return \App\Models\GeneralSetting::getThemeSettings();
    }
}

if (!function_exists('customCss')) {
    /**
     * Get custom CSS.
     *
     * @return string
     */
    function customCss()
    {
        return \App\Models\GeneralSetting::getCustomCss();
    }
}

if (!function_exists('customJs')) {
    /**
     * Get custom JavaScript.
     *
     * @return string
     */
    function customJs()
    {
        return \App\Models\GeneralSetting::getCustomJs();
    }
}

if (!function_exists('maintenanceData')) {
    /**
     * Get maintenance page data.
     *
     * @return array
     */
    function maintenanceData()
    {
        return \App\Models\GeneralSetting::getMaintenancePageData();
    }
}

if (!function_exists('cartTermsEnabled')) {
    /**
     * Check if cart terms and conditions checkbox is enabled.
     *
     * @return bool
     */
    function cartTermsEnabled()
    {
        return (bool) getSetting('cart_terms_enabled', true);
    }
}

if (!function_exists('cartTermsMandatory')) {
    /**
     * Check if cart terms and conditions checkbox is mandatory.
     *
     * @return bool
     */
    function cartTermsMandatory()
    {
        return (bool) getSetting('cart_terms_mandatory', true);
    }
}

if (!function_exists('cartTermsText')) {
    /**
     * Get cart terms and conditions text.
     *
     * @return string
     */
    function cartTermsText()
    {
        return getSetting('cart_terms_text', 'I agree with the');
    }
}

if (!function_exists('cartTermsLink')) {
    /**
     * Get cart terms and conditions link.
     *
     * @return string|null
     */
    function cartTermsLink()
    {
        $link = getSetting('cart_terms_link');
        if (!$link) {
            return route('page.terms-conditions', [], false);
        }
        return $link;
    }
}

if (!function_exists('cartTermsLinkText')) {
    /**
     * Get cart terms and conditions link text.
     *
     * @return string
     */
    function cartTermsLinkText()
    {
        return getSetting('cart_terms_link_text', 'terms and conditions');
    }
}

if (!function_exists('cartPrivacyEnabled')) {
    /**
     * Check if cart privacy policy checkbox is enabled.
     *
     * @return bool
     */
    function cartPrivacyEnabled()
    {
        return (bool) getSetting('cart_privacy_enabled', false);
    }
}

if (!function_exists('cartPrivacyMandatory')) {
    /**
     * Check if cart privacy policy checkbox is mandatory.
     *
     * @return bool
     */
    function cartPrivacyMandatory()
    {
        return (bool) getSetting('cart_privacy_mandatory', false);
    }
}

if (!function_exists('cartPrivacyText')) {
    /**
     * Get cart privacy policy text.
     *
     * @return string
     */
    function cartPrivacyText()
    {
        return getSetting('cart_privacy_text', 'I agree with the');
    }
}

if (!function_exists('cartPrivacyLink')) {
    /**
     * Get cart privacy policy link.
     *
     * @return string|null
     */
    function cartPrivacyLink()
    {
        $link = getSetting('cart_privacy_link');
        if (!$link) {
            return route('page.privacy-policy', [], false);
        }
        return $link;
    }
}

if (!function_exists('cartPrivacyLinkText')) {
    /**
     * Get cart privacy policy link text.
     *
     * @return string
     */
    function cartPrivacyLinkText()
    {
        return getSetting('cart_privacy_link_text', 'privacy policy');
    }
}
