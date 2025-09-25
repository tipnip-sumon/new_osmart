<?php

namespace App\Helpers;

class EmojiHelper
{
    /**
     * Get basic notification emojis
     */
    public static function getNotificationEmojis()
    {
        return [
            '📢' => 'Announcement',
            '🔔' => 'Bell',
            '⚠️' => 'Warning',
            '✅' => 'Success',
            '❌' => 'Error',
            'ℹ️' => 'Info',
            '🎉' => 'Celebration',
            '🚨' => 'Alert',
            '💡' => 'Idea',
            '🔥' => 'Hot'
        ];
    }

    /**
     * Get ecommerce related emojis
     */
    public static function getEcommerceEmojis()
    {
        return [
            '🛍️' => 'Shopping',
            '🛒' => 'Cart',
            '💰' => 'Money',
            '💳' => 'Payment',
            '🏷️' => 'Price Tag',
            '💸' => 'Sale',
            '🎁' => 'Gift',
            '📦' => 'Package',
            '🚚' => 'Delivery',
            '⭐' => 'Rating'
        ];
    }

    /**
     * Get fire and trending emojis
     */
    public static function getTrendingEmojis()
    {
        return [
            '🔥' => 'Hot Deal',
            '⚡' => 'Flash Sale',
            '💥' => 'Explosive Offer',
            '🚀' => 'Trending',
            '📈' => 'Growing',
            '🎯' => 'Target',
            '💎' => 'Premium',
            '🏆' => 'Winner',
            '🌟' => 'Special',
            '🎊' => 'Celebration'
        ];
    }

    /**
     * Get all emojis organized by category
     */
    public static function getAllEmojis()
    {
        return [
            'notification' => self::getNotificationEmojis(),
            'ecommerce' => self::getEcommerceEmojis(),
            'trending' => self::getTrendingEmojis()
        ];
    }

    /**
     * Get flat array of all emojis
     */
    public static function getFlatEmojis()
    {
        $all = self::getAllEmojis();
        $flat = [];
        
        foreach ($all as $category => $emojis) {
            $flat = array_merge($flat, $emojis);
        }
        
        return $flat;
    }

    /**
     * Generate HTML for emoji buttons
     */
    public static function generateEmojiButtons($category = 'all', $target = '#message', $buttonClass = 'btn-outline-secondary')
    {
        $html = '';
        
        switch ($category) {
            case 'notification':
                $emojis = self::getNotificationEmojis();
                break;
            case 'ecommerce':
                $emojis = self::getEcommerceEmojis();
                $buttonClass = 'btn-outline-primary';
                break;
            case 'trending':
                $emojis = self::getTrendingEmojis();
                $buttonClass = 'btn-outline-danger';
                break;
            default:
                $emojis = self::getFlatEmojis();
                break;
        }

        foreach ($emojis as $emoji => $title) {
            $dataTarget = $target !== '#message' ? ' data-target="' . $target . '"' : '';
            $html .= '<button type="button" class="btn ' . $buttonClass . ' emoji-btn" data-emoji="' . $emoji . '"' . $dataTarget . ' title="' . $title . '">' . $emoji . '</button>';
        }

        return $html;
    }

    /**
     * Check if text contains emoji
     */
    public static function containsEmoji($text)
    {
        return preg_match('/[\x{1F600}-\x{1F64F}]|[\x{1F300}-\x{1F5FF}]|[\x{1F680}-\x{1F6FF}]|[\x{2600}-\x{26FF}]|[\x{2700}-\x{27BF}]/u', $text);
    }

    /**
     * Count emojis in text
     */
    public static function countEmojis($text)
    {
        return preg_match_all('/[\x{1F600}-\x{1F64F}]|[\x{1F300}-\x{1F5FF}]|[\x{1F680}-\x{1F6FF}]|[\x{2600}-\x{26FF}]|[\x{2700}-\x{27BF}]/u', $text);
    }

    /**
     * Get random emoji from category
     */
    public static function getRandomEmoji($category = 'all')
    {
        switch ($category) {
            case 'notification':
                $emojis = array_keys(self::getNotificationEmojis());
                break;
            case 'ecommerce':
                $emojis = array_keys(self::getEcommerceEmojis());
                break;
            case 'trending':
                $emojis = array_keys(self::getTrendingEmojis());
                break;
            default:
                $emojis = array_keys(self::getFlatEmojis());
                break;
        }

        return $emojis[array_rand($emojis)];
    }
}
