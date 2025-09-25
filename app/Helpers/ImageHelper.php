<?php

if (!function_exists('formatImageUrl')) {
    /**
     * Format image URL to work properly in both local and live environments
     * 
     * @param string|null $imagePath
     * @return string|null
     */
    function formatImageUrl($imagePath)
    {
        if (!$imagePath) {
            return null;
        }
        
        // If the path already contains a full URL, return as-is
        if (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
            return $imagePath;
        }
        
        // Clean the path
        $cleanPath = ltrim($imagePath, '/');
        if (str_starts_with($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, 8); // Remove 'storage/' prefix
        }
        
        // For live server compatibility, check if storage is linked
        $publicStoragePath = public_path('storage');
        
        // If storage link exists (local/proper setup), use asset()
        if (is_link($publicStoragePath) || is_dir($publicStoragePath)) {
            return asset('storage/' . $cleanPath);
        }
        
        // For live servers without storage link, use direct app URL
        $appUrl = config('app.url', url('/'));
        return $appUrl . '/storage/' . $cleanPath;
    }
}

if (!function_exists('getStorageUrl')) {
    /**
     * Get storage URL with proper configuration for live/local environments
     * 
     * @param string $path
     * @return string
     */
    function getStorageUrl($path = '')
    {
        $appUrl = config('app.url', url('/'));
        $cleanPath = ltrim($path, '/');
        
        return $appUrl . '/storage/' . $cleanPath;
    }
}

if (!function_exists('checkStorageLink')) {
    /**
     * Check if storage symlink exists and is working
     * 
     * @return array
     */
    function checkStorageLink()
    {
        $publicStoragePath = public_path('storage');
        $storageAppPublicPath = storage_path('app/public');
        
        return [
            'exists' => file_exists($publicStoragePath),
            'is_link' => is_link($publicStoragePath),
            'is_directory' => is_dir($publicStoragePath),
            'source_exists' => is_dir($storageAppPublicPath),
            'writable' => is_writable($storageAppPublicPath),
        ];
    }
}
