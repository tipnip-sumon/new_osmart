<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Interfaces\ImageInterface;

class ImageUploadService
{
    protected ImageManager $manager;
    protected array $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    protected int $maxFileSize = 10240; // 10MB in KB
    protected array $defaultSizes = [
        'thumbnail' => ['width' => 150, 'height' => 150],
        'small' => ['width' => 300, 'height' => 300],
        'medium' => ['width' => 600, 'height' => 600],
        'large' => ['width' => 1200, 'height' => 1200],
    ];

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Get the image manager instance
     */
    public function getManager(): ImageManager
    {
        return $this->manager;
    }

    /**
     * Upload and process multiple images
     */
    public function uploadMultiple(array $files, string $folder = 'products', array $sizes = null): array
    {
        $uploadedImages = [];
        $sizes = $sizes ?? $this->defaultSizes;

        foreach ($files as $index => $file) {
            if ($this->validateImage($file)) {
                $imageData = $this->uploadSingle($file, $folder, $sizes);
                $imageData['is_primary'] = $index === 0;
                $imageData['sort_order'] = $index;
                $uploadedImages[] = $imageData;
            }
        }

        return $uploadedImages;
    }

    /**
     * Upload and process single image
     */
    public function uploadSingle(UploadedFile $file, string $folder = 'products', array $sizes = null): array
    {
        $sizes = $sizes ?? $this->defaultSizes;
        
        // Generate unique filename
        $filename = $this->generateFilename($file);
        $basePath = $folder . '/' . date('Y/m');
        
        // Create original image
        $image = $this->manager->read($file->getPathname());
        
        // Optimize original image
        $originalPath = $basePath . '/original/' . $filename;
        $optimizedImage = $this->optimizeImage($image);
        
        // Store original
        Storage::disk('public')->put($originalPath, $optimizedImage->encode());
        
        $imageData = [
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'folder' => $folder,
            'path' => $basePath,
            'urls' => [
                'original' => asset('storage/' . $originalPath)
            ],
            'dimensions' => [
                'original' => [
                    'width' => $image->width(),
                    'height' => $image->height()
                ]
            ]
        ];

        // Generate different sizes
        foreach ($sizes as $sizeName => $dimensions) {
            $resizedPath = $basePath . '/' . $sizeName . '/' . $filename;
            $resizedImage = $this->resizeImage($image, $dimensions['width'], $dimensions['height']);
            
            Storage::disk('public')->put($resizedPath, $resizedImage->encode());
            
            $imageData['urls'][$sizeName] = asset('storage/' . $resizedPath);
            $imageData['dimensions'][$sizeName] = [
                'width' => $resizedImage->width(),
                'height' => $resizedImage->height()
            ];
        }

        return $imageData;
    }

    /**
     * Validate uploaded image
     */
    protected function validateImage(UploadedFile $file): bool
    {
        // Check file size
        if ($file->getSize() > $this->maxFileSize * 1024) {
            throw new \InvalidArgumentException('File size too large. Maximum allowed: ' . $this->maxFileSize . 'KB');
        }

        // Check mime type
        if (!in_array($file->getMimeType(), $this->allowedMimes)) {
            throw new \InvalidArgumentException('Invalid file type. Allowed: ' . implode(', ', $this->allowedMimes));
        }

        // Check if file is actually an image
        try {
            $image = $this->manager->read($file->getPathname());
            if (!$image) {
                throw new \InvalidArgumentException('Invalid image file');
            }
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Unable to process image: ' . $e->getMessage());
        }

        return true;
    }

    /**
     * Generate unique filename
     */
    protected function generateFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        return uniqid() . '_' . time() . '.' . $extension;
    }

    /**
     * Optimize image for web
     */
    protected function optimizeImage(ImageInterface $image): ImageInterface
    {
        // Auto-orient image based on EXIF data
        $image = $image->orient();

        // Apply sharpening if needed
        if ($image->width() > 800 || $image->height() > 800) {
            $image = $image->sharpen(10);
        }

        return $image;
    }

    /**
     * Resize image maintaining aspect ratio
     */
    protected function resizeImage(ImageInterface $image, int $width, int $height): ImageInterface
    {
        return $image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize(); // Prevent upsizing
        });
    }

    /**
     * Create thumbnail with cropping
     */
    public function createThumbnail(ImageInterface $image, int $width = 150, int $height = 150): ImageInterface
    {
        return $image->cover($width, $height);
    }

    /**
     * Add watermark to image
     */
    public function addWatermark(ImageInterface $image, string $watermarkPath, string $position = 'bottom-right'): ImageInterface
    {
        if (!Storage::disk('public')->exists($watermarkPath)) {
            return $image;
        }

        $watermark = $this->manager->read(Storage::disk('public')->path($watermarkPath));
        
        // Resize watermark to 20% of image width
        $watermarkWidth = $image->width() * 0.2;
        $watermark = $watermark->resize($watermarkWidth, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        // Position watermark
        $positions = [
            'top-left' => [10, 10],
            'top-right' => [$image->width() - $watermark->width() - 10, 10],
            'bottom-left' => [10, $image->height() - $watermark->height() - 10],
            'bottom-right' => [$image->width() - $watermark->width() - 10, $image->height() - $watermark->height() - 10],
            'center' => [($image->width() - $watermark->width()) / 2, ($image->height() - $watermark->height()) / 2],
        ];

        $x = $positions[$position][0] ?? $positions['bottom-right'][0];
        $y = $positions[$position][1] ?? $positions['bottom-right'][1];

        return $image->place($watermark, 'top-left', $x, $y);
    }

    /**
     * Delete image and all its sizes
     */
    public function deleteImage(array $imageData): bool
    {
        $deleted = true;
        $basePath = $imageData['path'];
        $filename = $imageData['filename'];

        // Delete original
        $originalPath = $basePath . '/original/' . $filename;
        if (Storage::disk('public')->exists($originalPath)) {
            $deleted = $deleted && Storage::disk('public')->delete($originalPath);
        }

        // Delete all sizes
        foreach ($this->defaultSizes as $sizeName => $dimensions) {
            $sizePath = $basePath . '/' . $sizeName . '/' . $filename;
            if (Storage::disk('public')->exists($sizePath)) {
                $deleted = $deleted && Storage::disk('public')->delete($sizePath);
            }
        }

        return $deleted;
    }

    /**
     * Get image URL by size
     */
    public function getImageUrl(array $imageData, string $size = 'medium'): string
    {
        return $imageData['urls'][$size] ?? $imageData['urls']['original'] ?? '';
    }

    /**
     * Convert image to WebP format
     */
    public function convertToWebP(ImageInterface $image, int $quality = 80): string
    {
        return $image->toWebp($quality)->toString();
    }

    /**
     * Batch resize images
     */
    public function batchResize(array $imagePaths, array $sizes): array
    {
        $results = [];

        foreach ($imagePaths as $imagePath) {
            if (Storage::disk('public')->exists($imagePath)) {
                $image = $this->manager->read(Storage::disk('public')->path($imagePath));
                $pathInfo = pathinfo($imagePath);
                
                foreach ($sizes as $sizeName => $dimensions) {
                    $resizedPath = $pathInfo['dirname'] . '/' . $sizeName . '/' . $pathInfo['basename'];
                    $resizedImage = $this->resizeImage($image, $dimensions['width'], $dimensions['height']);
                    
                    Storage::disk('public')->put($resizedPath, $resizedImage->encode());
                    $results[] = $resizedPath;
                }
            }
        }

        return $results;
    }
}
