<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;
use Carbon\Carbon;

trait HandlesImageUploads
{
    /**
     * Process image upload with multiple sizes and optimization
     */
    protected function processImageUpload(
        UploadedFile $file, 
        string $folder, 
        array $sizes = null, 
        int $quality = 85
    ): array {
        try {
            // Default sizes for different types of images
            $defaultSizes = [
                'original' => ['width' => 1200, 'height' => 800],
                'large' => ['width' => 800, 'height' => 600],
                'medium' => ['width' => 400, 'height' => 300],
                'small' => ['width' => 200, 'height' => 150],
                'thumbnail' => ['width' => 100, 'height' => 75]
            ];
            
            if ($sizes === null) {
                $sizes = $defaultSizes;
            }

            // Create Intervention Image manager
            $manager = new ImageManager(new Driver());
            
            // Generate unique filename
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $timestamp = time();
            $filename = Str::slug($originalName) . '_' . $timestamp . '.' . $extension;
            
            // Create date-based folder structure
            $datePath = Carbon::now()->format('Y/m');
            $fullFolder = $folder . '/' . $datePath;
            
            // Ensure directory exists
            Storage::disk('public')->makeDirectory($fullFolder);
            
            $imageData = [
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'folder' => $fullFolder,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'uploaded_at' => Carbon::now()->toISOString(),
                'sizes' => []
            ];

            // Process each size
            foreach ($sizes as $sizeName => $dimensions) {
                $image = $manager->read($file->getPathname());
                
                // Resize image
                $image->resize($dimensions['width'], $dimensions['height']);
                
                // Create size-specific folder
                $sizePath = $fullFolder . '/' . $sizeName;
                Storage::disk('public')->makeDirectory($sizePath);
                
                // Encode image with proper format and quality
                $encodedImage = $this->encodeImageByFormat($image, $extension, $quality);
                
                // Save image
                $filePath = $sizePath . '/' . $filename;
                Storage::disk('public')->put($filePath, $encodedImage);
                
                // Store size information
                $imageData['sizes'][$sizeName] = [
                    'width' => $dimensions['width'],
                    'height' => $dimensions['height'],
                    'path' => $filePath,
                    'url' => url('direct-storage/' . $filePath), // Use direct-storage route
                    'storage_url' => asset('storage/' . $filePath), // Keep original URL as fallback
                    'file_size' => Storage::disk('public')->size($filePath)
                ];
            }

            return $imageData;

        } catch (\Exception $e) {
            Log::error('Image upload failed: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'folder' => $folder
            ]);
            throw new \Exception('Failed to process image upload: ' . $e->getMessage());
        }
    }

    /**
     * Upload single image with default sizes
     */
    protected function uploadSingleImage(
        UploadedFile $file, 
        string $folder, 
        array $customSizes = null
    ): array {
        // Check if the file is a PDF
        if ($file->getMimeType() === 'application/pdf') {
            return $this->processPdfUpload($file, $folder);
        }
        
        $sizes = $customSizes ?? [
            'original' => ['width' => 800, 'height' => 600],
            'medium' => ['width' => 400, 'height' => 300],
            'small' => ['width' => 200, 'height' => 150]
        ];
        
        return $this->processImageUpload($file, $folder, $sizes);
    }

    /**
     * Process PDF upload (no image processing)
     */
    protected function processPdfUpload(UploadedFile $file, string $folder): array
    {
        try {
            // Generate unique filename
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $timestamp = time();
            $filename = Str::slug($originalName) . '_' . $timestamp . '.' . $extension;
            
            // Create date-based folder structure
            $datePath = Carbon::now()->format('Y/m');
            $fullFolder = $folder . '/' . $datePath;
            
            // Ensure directory exists
            Storage::disk('public')->makeDirectory($fullFolder);
            
            // Store the PDF file
            $filePath = $fullFolder . '/' . $filename;
            Storage::disk('public')->putFileAs($fullFolder, $file, $filename);
            
            return [
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'folder' => $fullFolder,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'uploaded_at' => Carbon::now()->toISOString(),
                'sizes' => [
                    'original' => [
                        'path' => $filePath,
                        'url' => url('direct-storage/' . $filePath),
                        'storage_url' => asset('storage/' . $filePath),
                        'file_size' => Storage::disk('public')->size($filePath)
                    ]
                ]
            ];
            
        } catch (\Exception $e) {
            Log::error('PDF upload failed: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'folder' => $folder
            ]);
            throw new \Exception('Failed to process PDF upload: ' . $e->getMessage());
        }
    }

    /**
     * Upload avatar/profile image
     */
    protected function uploadAvatarImage(UploadedFile $file, string $folder = 'avatars'): array
    {
        $sizes = [
            'original' => ['width' => 400, 'height' => 400],
            'large' => ['width' => 200, 'height' => 200],
            'medium' => ['width' => 100, 'height' => 100],
            'small' => ['width' => 50, 'height' => 50]
        ];
        
        return $this->processImageUpload($file, $folder, $sizes);
    }

    /**
     * Upload banner image
     */
    protected function uploadBannerImage(UploadedFile $file, string $folder = 'banners'): array
    {
        $sizes = [
            'original' => ['width' => 1920, 'height' => 600],
            'desktop' => ['width' => 1200, 'height' => 400],
            'tablet' => ['width' => 768, 'height' => 300],
            'mobile' => ['width' => 480, 'height' => 200]
        ];
        
        return $this->processImageUpload($file, $folder, $sizes);
    }

    /**
     * Upload product image
     */
    protected function uploadProductImage(UploadedFile $file, string $folder = 'products'): array
    {
        $sizes = [
            'original' => ['width' => 1000, 'height' => 1000],
            'large' => ['width' => 600, 'height' => 600],
            'medium' => ['width' => 400, 'height' => 400],
            'small' => ['width' => 200, 'height' => 200],
            'thumbnail' => ['width' => 100, 'height' => 100]
        ];
        
        return $this->processImageUpload($file, $folder, $sizes);
    }

    /**
     * Upload category image
     */
    protected function uploadCategoryImage(UploadedFile $file, string $folder = 'categories'): array
    {
        $sizes = [
            'original' => ['width' => 600, 'height' => 400],
            'medium' => ['width' => 300, 'height' => 200],
            'small' => ['width' => 150, 'height' => 100],
            'icon' => ['width' => 64, 'height' => 64]
        ];
        
        return $this->processImageUpload($file, $folder, $sizes);
    }

    /**
     * Upload subcategory image
     */
    protected function uploadSubcategoryImage(UploadedFile $file, string $folder = 'subcategories'): array
    {
        $sizes = [
            'original' => ['width' => 600, 'height' => 400],
            'medium' => ['width' => 300, 'height' => 200],
            'small' => ['width' => 150, 'height' => 100],
            'icon' => ['width' => 64, 'height' => 64]
        ];
        
        return $this->processImageUpload($file, $folder, $sizes);
    }

    /**
     * Upload brand/logo image
     */
    protected function uploadBrandImage(UploadedFile $file, string $folder = 'brands'): array
    {
        $sizes = [
            'original' => ['width' => 800, 'height' => 800],
            'large' => ['width' => 400, 'height' => 400],
            'medium' => ['width' => 200, 'height' => 200],
            'small' => ['width' => 100, 'height' => 100],
            'thumbnail' => ['width' => 50, 'height' => 50]
        ];
        
        return $this->processImageUpload($file, $folder, $sizes, 90);
    }

    /**
     * Encode image by format with proper quality
     */
    private function encodeImageByFormat($image, string $extension, int $quality = 85)
    {
        $extension = strtolower($extension);
        
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                return $image->toJpeg($quality);
            case 'png':
                return $image->toPng();
            case 'gif':
                return $image->toGif();
            case 'webp':
                return $image->toWebp($quality);
            default:
                return $image->toJpeg($quality);
        }
    }

    /**
     * Delete image files for all sizes
     */
    protected function deleteImageFiles(array $imageData): bool
    {
        try {
            if (isset($imageData['sizes']) && is_array($imageData['sizes'])) {
                foreach ($imageData['sizes'] as $size => $sizeData) {
                    if (isset($sizeData['path']) && Storage::disk('public')->exists($sizeData['path'])) {
                        Storage::disk('public')->delete($sizeData['path']);
                    }
                }
            }
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete image files: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete legacy image file (for backward compatibility)
     */
    protected function deleteLegacyImageFile(?string $imagePath): bool
    {
        try {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            Log::error('Failed to delete legacy image file: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get image URL by size
     */
    protected function getImageUrl(array $imageData, string $size = 'medium'): ?string
    {
        if (isset($imageData['sizes'][$size]['url'])) {
            return $imageData['sizes'][$size]['url'];
        }
        
        // Fallback to original if size not found
        if (isset($imageData['sizes']['original']['url'])) {
            return $imageData['sizes']['original']['url'];
        }
        
        return null;
    }

    /**
     * Convert legacy image path to new format array
     */
    protected function convertLegacyImageData(string $legacyPath): array
    {
        return [
            'filename' => basename($legacyPath),
            'folder' => dirname($legacyPath),
            'sizes' => [
                'original' => [
                    'path' => $legacyPath,
                    'url' => asset('storage/' . $legacyPath)
                ]
            ]
        ];
    }

    /**
     * Upload payment receipt image (for mobile banking/bank transfer receipts)
     */
    protected function uploadPaymentReceiptImage(UploadedFile $file, string $folder = 'payment-receipts'): array
    {
        $sizes = [
            'original' => ['width' => 1200, 'height' => 1600], // For high-quality viewing
            'large' => ['width' => 800, 'height' => 1000],     // For modal display
            'medium' => ['width' => 400, 'height' => 500],     // For thumbnails
            'small' => ['width' => 200, 'height' => 250]       // For list view
        ];
        
        return $this->processImageUpload($file, $folder, $sizes, 95); // Higher quality for receipts
    }

    /**
     * Upload document/PDF receipt (no image processing)
     */
    protected function uploadReceiptDocument(UploadedFile $file, string $folder = 'payment-receipts'): array
    {
        // For PDF receipts, use the existing PDF upload method
        return $this->processPdfUpload($file, $folder);
    }
}
