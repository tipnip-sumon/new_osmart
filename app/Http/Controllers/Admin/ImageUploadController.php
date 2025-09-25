<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImageUploadController extends Controller
{
    protected ImageUploadService $imageUploadService;

    public function __construct(ImageUploadService $imageUploadService)
    {
        $this->imageUploadService = $imageUploadService;
    }

    /**
     * Upload multiple images via AJAX
     */
    public function uploadMultiple(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'images' => 'required|array|max:10',
                'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
                'folder' => 'sometimes|string|max:50'
            ]);

            $folder = $request->input('folder', 'uploads');
            $images = $request->file('images');
            
            if (!$images || count($images) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No images were uploaded'
                ], 400);
            }
            
            $uploadedImages = $this->imageUploadService->uploadMultiple($images, $folder);
            
            return response()->json([
                'success' => true,
                'message' => 'Images uploaded successfully',
                'data' => $uploadedImages,
                'count' => count($uploadedImages)
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Image upload failed: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload images: ' . $e->getMessage(),
                'debug' => config('app.debug') ? [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ] : null
            ], 500);
        }
    }

    /**
     * Upload single image via AJAX
     */
    public function uploadSingle(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'folder' => 'sometimes|string|max:50',
            'sizes' => 'sometimes|array'
        ]);

        try {
            $folder = $request->input('folder', 'uploads');
            $sizes = $request->input('sizes');
            $image = $request->file('image');
            
            $uploadedImage = $this->imageUploadService->uploadSingle($image, $folder, $sizes);
            
            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'data' => $uploadedImage
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Delete image
     */
    public function deleteImage(Request $request): JsonResponse
    {
        $request->validate([
            'image_data' => 'required|array',
            'image_data.filename' => 'required|string',
            'image_data.path' => 'required|string'
        ]);

        try {
            $imageData = $request->input('image_data');
            $deleted = $this->imageUploadService->deleteImage($imageData);
            
            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Image deleted successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete some image files'
                ], 422);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Resize existing images or uploaded files
     */
    public function resizeImages(Request $request): JsonResponse
    {
        try {
            // Handle both uploaded files and existing image paths
            if ($request->hasFile('images')) {
                // Handle uploaded files for resizing (bulk resize format)
                if ($request->has('sizes')) {
                    // New bulk resize format
                    $request->validate([
                        'images' => 'required|array|max:20',
                        'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
                        'sizes' => 'required|array|min:1',
                        'sizes.*.width' => 'required|integer|min:50|max:2000',
                        'sizes.*.height' => 'required|integer|min:50|max:2000',
                        'sizes.*.name' => 'sometimes|string|max:50',
                        'quality' => 'sometimes|integer|min:10|max:100',
                        'format' => 'sometimes|string|in:same,jpg,png,webp'
                    ]);

                    $images = $request->file('images');
                    $sizes = $request->input('sizes');
                    $quality = (int) $request->input('quality', 85);
                    $format = $request->input('format', 'same');
                    
                    // Debug logging
                    Log::info('Bulk resize request', [
                        'images_count' => count($images),
                        'sizes_count' => count($sizes),
                        'quality' => $quality,
                        'format' => $format,
                        'sizes' => $sizes
                    ]);
                    
                    // Ensure resized directory exists
                    if (!Storage::disk('public')->exists('resized')) {
                        Storage::disk('public')->makeDirectory('resized');
                    }
                    
                    $resizedImages = [];
                    
                    foreach ($images as $imageIndex => $image) {
                        foreach ($sizes as $sizeIndex => $size) {
                            $imageManager = $this->imageUploadService->getManager()->read($image->getPathname());
                            
                            // Resize to exact dimensions
                            $imageManager->resize((int)$size['width'], (int)$size['height']);
                            
                            // Determine output format
                            $originalExtension = $image->getClientOriginalExtension();
                            $outputExtension = $format === 'same' ? $originalExtension : $format;
                            
                            // Generate unique filename
                            $sizeName = isset($size['name']) && !empty($size['name']) ? $size['name'] : $size['width'].'x'.$size['height'];
                            $basename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                            $filename = $basename . '_' . $sizeName . '_' . time() . '.' . $outputExtension;
                            $path = 'resized/' . $filename;
                            
                            // Encode with quality
                            if ($outputExtension === 'jpg' || $outputExtension === 'jpeg') {
                                $encodedImage = $imageManager->toJpeg($quality);
                            } elseif ($outputExtension === 'png') {
                                $encodedImage = $imageManager->toPng();
                            } elseif ($outputExtension === 'webp') {
                                $encodedImage = $imageManager->toWebp($quality);
                            } else {
                                $encodedImage = $imageManager->encode();
                            }
                            
                            // Save resized image
                            Storage::disk('public')->put($path, $encodedImage);
                            
                            $resizedImages[] = [
                                'original' => $image->getClientOriginalName(),
                                'filename' => $filename,
                                'path' => $path,
                                'url' => asset('storage/' . $path),
                                'size' => $sizeName,
                                'width' => (int)$size['width'],
                                'height' => (int)$size['height'],
                                'file_size' => Storage::disk('public')->size($path),
                                'format' => $outputExtension
                            ];
                        }
                    }
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Images resized successfully',
                        'data' => $resizedImages,
                        'count' => count($resizedImages)
                    ]);
                    
                } else {
                    // Legacy single size format
                    $request->validate([
                        'images' => 'required|array|max:10',
                        'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
                        'width' => 'required|integer|min:50|max:2000',
                        'height' => 'required|integer|min:50|max:2000',
                        'maintain_ratio' => 'sometimes|boolean',
                        'folder' => 'sometimes|string|max:50'
                    ]);

                    $images = $request->file('images');
                    $width = (int) $request->input('width');
                    $height = (int) $request->input('height');
                    $maintainRatio = $request->input('maintain_ratio', '1') === '1';
                    $folder = $request->input('folder', 'resized');
                    
                    $resizedImages = [];
                    
                    foreach ($images as $image) {
                        $imageManager = $this->imageUploadService->getManager()->read($image->getPathname());
                        
                        if ($maintainRatio) {
                            // Maintain aspect ratio
                            $imageManager->scale(width: $width, height: $height);
                        } else {
                            // Force exact dimensions
                            $imageManager->resize($width, $height);
                        }
                        
                        // Generate unique filename
                        $filename = time() . '_' . $image->getClientOriginalName();
                        $path = $folder . '/' . $filename;
                        
                        // Save resized image
                        Storage::disk('public')->put($path, $imageManager->encode());
                        
                        $resizedImages[] = [
                            'original_name' => $image->getClientOriginalName(),
                            'filename' => $filename,
                            'path' => $path,
                            'url' => asset('storage/' . $path),
                            'size' => Storage::disk('public')->size($path),
                            'dimensions' => [
                                'width' => $imageManager->width(),
                                'height' => $imageManager->height()
                            ]
                        ];
                    }
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Images resized successfully',
                        'data' => $resizedImages,
                        'count' => count($resizedImages)
                    ]);
                }
                
            } else {
                // Handle existing image paths (original functionality)
                $request->validate([
                    'image_paths' => 'required|array',
                    'image_paths.*' => 'required|string',
                    'sizes' => 'required|array',
                    'sizes.*.width' => 'required|integer|min:50|max:2000',
                    'sizes.*.height' => 'required|integer|min:50|max:2000'
                ]);

                $imagePaths = $request->input('image_paths');
                $sizes = $request->input('sizes');
                
                $resizedImages = $this->imageUploadService->batchResize($imagePaths, $sizes);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Images resized successfully',
                    'data' => $resizedImages,
                    'count' => count($resizedImages)
                ]);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to resize images: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Generate image thumbnails
     */
    public function generateThumbnails(Request $request): JsonResponse
    {
        // Handle both uploaded files and existing image paths
        if ($request->hasFile('images')) {
            // Handle uploaded files for thumbnail generation
            $request->validate([
                'images' => 'required|array|max:10',
                'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
                'sizes' => 'sometimes|array',
                'folder' => 'sometimes|string|max:50'
            ]);

            try {
                $images = $request->file('images');
                $sizes = $request->input('sizes', ['150x150', '300x300']); // Default sizes
                $folder = $request->input('folder', 'thumbnails');
                
                $thumbnails = [];
                
                foreach ($images as $image) {
                    $imageManager = $this->imageUploadService->getManager()->read($image->getPathname());
                    $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $image->getClientOriginalExtension();
                    
                    foreach ($sizes as $size) {
                        list($width, $height) = explode('x', $size);
                        $width = (int)$width;
                        $height = (int)$height;
                        
                        // Create thumbnail
                        $thumbnail = clone $imageManager;
                        $thumbnail->resize($width, $height);
                        
                        // Generate filename
                        $filename = $originalName . '_' . $size . '.' . $extension;
                        $path = $folder . '/' . $filename;
                        
                        // Save thumbnail
                        Storage::disk('public')->put($path, $thumbnail->encode());
                        
                        $thumbnails[] = [
                            'original_name' => $image->getClientOriginalName(),
                            'size' => $size,
                            'filename' => $filename,
                            'path' => $path,
                            'url' => asset('storage/' . $path),
                            'file_size' => Storage::disk('public')->size($path),
                            'dimensions' => [
                                'width' => $width,
                                'height' => $height
                            ]
                        ];
                    }
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Thumbnails generated successfully',
                    'data' => $thumbnails,
                    'count' => count($thumbnails)
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate thumbnails: ' . $e->getMessage()
                ], 422);
            }
        } else {
            // Handle existing image paths (original functionality)
            $request->validate([
                'image_paths' => 'required|array',
                'image_paths.*' => 'required|string',
                'width' => 'sometimes|integer|min:50|max:500',
                'height' => 'sometimes|integer|min:50|max:500'
            ]);

            try {
                $imagePaths = $request->input('image_paths');
                $width = $request->input('width', 150);
                $height = $request->input('height', 150);
                
                $thumbnails = [];
                
                foreach ($imagePaths as $imagePath) {
                    if (Storage::disk('public')->exists($imagePath)) {
                        $image = $this->imageUploadService->getManager()->read(Storage::disk('public')->path($imagePath));
                        $thumbnail = $this->imageUploadService->createThumbnail($image, $width, $height);
                        
                        $pathInfo = pathinfo($imagePath);
                        $thumbnailPath = $pathInfo['dirname'] . '/thumbnails/' . $pathInfo['basename'];
                        
                        Storage::disk('public')->put($thumbnailPath, $thumbnail->encode());
                        $thumbnails[] = $thumbnailPath;
                    }
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Thumbnails generated successfully',
                    'data' => $thumbnails,
                    'count' => count($thumbnails)
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate thumbnails: ' . $e->getMessage()
                ], 422);
            }
        }
    }

    /**
     * Get image information
     */
    public function getImageInfo(Request $request): JsonResponse
    {
        // Handle both uploaded files and existing file paths
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'required|image|max:10240'
            ]);
            
            try {
                $file = $request->file('image');
                $image = $this->imageUploadService->getManager()->read($file->getPathname());
                
                $info = [
                    'filename' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'extension' => $file->getClientOriginalExtension(),
                    'width' => $image->width(),
                    'height' => $image->height(),
                    'aspect_ratio' => round($image->width() / $image->height(), 2),
                    'color_space' => 'RGB', // Default for web images
                    'channels' => 3, // RGB = 3 channels
                    'bit_depth' => '8 bits',
                    'compression' => $file->getMimeType() === 'image/jpeg' ? 'JPEG' : 'None'
                ];
                
                return response()->json([
                    'success' => true,
                    'data' => $info
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to get image info: ' . $e->getMessage()
                ], 422);
            }
        } else {
            // Handle existing file path (original functionality)
            $request->validate([
                'image_path' => 'required|string'
            ]);

            try {
                $imagePath = $request->input('image_path');
                
                if (!Storage::disk('public')->exists($imagePath)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Image not found'
                    ], 404);
                }
                
                $fullPath = Storage::disk('public')->path($imagePath);
                $image = $this->imageUploadService->getManager()->read($fullPath);
                
                $info = [
                    'path' => $imagePath,
                    'url' => asset('storage/' . $imagePath),
                    'size' => Storage::disk('public')->size($imagePath),
                    'mime_type' => $image->origin()->mediaType(),
                    'width' => $image->width(),
                    'height' => $image->height(),
                    'aspect_ratio' => round($image->width() / $image->height(), 2),
                    'format' => $image->origin()->mediaType(),
                    'last_modified' => Storage::disk('public')->lastModified($imagePath)
                ];
                
                return response()->json([
                    'success' => true,
                    'data' => $info
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to get image info: ' . $e->getMessage()
                ], 422);
            }
        }
    }

    /**
     * Optimize images (compress and convert to WebP)
     */
    public function optimizeImages(Request $request): JsonResponse
    {
        try {
            // Handle both uploaded files and existing image paths
            if ($request->hasFile('images')) {
                // Handle uploaded files for optimization
                $request->validate([
                    'images' => 'required|array|max:10',
                    'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
                    'quality' => 'sometimes|integer|min:10|max:100',
                    'folder' => 'sometimes|string|max:50'
                ]);

                $images = $request->file('images');
                $quality = (int) $request->input('quality', 85); // Cast to integer
                $folder = $request->input('folder', 'optimized');
                
                $optimizedImages = [];
                
                foreach ($images as $image) {
                    $imageManager = $this->imageUploadService->getManager()->read($image->getPathname());
                    $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                    $originalSize = $image->getSize();
                    
                    // Optimize and compress image
                    $optimizedData = $imageManager->toJpeg($quality);
                    
                    // Generate filename
                    $filename = $originalName . '_optimized_' . time() . '.jpg';
                    $path = $folder . '/' . $filename;
                    
                    // Save optimized image
                    Storage::disk('public')->put($path, $optimizedData);
                    
                    $optimizedImages[] = [
                        'original_name' => $image->getClientOriginalName(),
                        'filename' => $filename,
                        'path' => $path,
                        'url' => asset('storage/' . $path),
                        'original_size' => $originalSize,
                        'optimized_size' => Storage::disk('public')->size($path),
                        'savings' => $originalSize - Storage::disk('public')->size($path),
                        'quality' => $quality
                    ];
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Images optimized successfully',
                    'data' => $optimizedImages,
                    'count' => count($optimizedImages)
                ]);
            } else {
                // Handle existing image paths (original functionality)
                $request->validate([
                    'image_paths' => 'required|array',
                    'image_paths.*' => 'required|string',
                    'quality' => 'sometimes|integer|min:10|max:100'
                ]);

                $imagePaths = $request->input('image_paths');
                $quality = (int) $request->input('quality', 80); // Cast to integer
                
                $optimizedImages = [];
                
                foreach ($imagePaths as $imagePath) {
                    if (Storage::disk('public')->exists($imagePath)) {
                        $image = $this->imageUploadService->getManager()->read(Storage::disk('public')->path($imagePath));
                        
                        // Convert to WebP
                        $webpData = $this->imageUploadService->convertToWebP($image, $quality);
                        
                        $pathInfo = pathinfo($imagePath);
                        $webpPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';
                        
                        Storage::disk('public')->put($webpPath, $webpData);
                        $optimizedImages[] = [
                            'original' => $imagePath,
                            'optimized' => $webpPath,
                            'original_size' => Storage::disk('public')->size($imagePath),
                            'optimized_size' => Storage::disk('public')->size($webpPath)
                        ];
                    }
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Images optimized successfully',
                    'data' => $optimizedImages,
                    'count' => count($optimizedImages)
                ]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Image optimization failed: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to optimize images: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show image upload demo page
     */
    public function demo()
    {
        try {
            return view('admin.image-upload.demo');
        } catch (\Exception $e) {
            Log::error('Error loading image upload demo page', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.products.index')
                ->with('error', 'Unable to load image upload demo. Please try again.');
        }
    }

    /**
     * Download multiple images as ZIP file
     */
    public function downloadZip(Request $request)
    {
        $request->validate([
            'paths' => 'required|array|min:1',
            'paths.*' => 'required|string'
        ]);

        try {
            $paths = $request->input('paths');
            $zip = new \ZipArchive();
            $zipFileName = 'resized_images_' . time() . '.zip';
            $zipPath = storage_path('app/temp/' . $zipFileName);
            
            // Create temp directory if it doesn't exist
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            if ($zip->open($zipPath, \ZipArchive::CREATE) !== TRUE) {
                throw new \Exception('Cannot create ZIP file');
            }

            $addedFiles = 0;
            foreach ($paths as $path) {
                $fullPath = storage_path('app/public/' . $path);
                
                if (file_exists($fullPath)) {
                    $fileName = basename($path);
                    $zip->addFile($fullPath, $fileName);
                    $addedFiles++;
                }
            }

            $zip->close();

            if ($addedFiles === 0) {
                throw new \Exception('No valid files found to archive');
            }

            // Return the ZIP file as download
            return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Error creating ZIP file', [
                'error' => $e->getMessage(),
                'paths' => $request->input('paths', [])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create ZIP file: ' . $e->getMessage()
            ], 500);
        }
    }
}
