<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\ImageUploadService;
use App\Traits\HandlesImageUploads;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SubcategoryController extends Controller
{
    use HandlesImageUploads;
    
    protected $imageUploadService;

    public function __construct(ImageUploadService $imageUploadService)
    {
        $this->imageUploadService = $imageUploadService;
    }

    /**
     * Display a listing of subcategories.
     */
    public function index(Request $request)
    {
        try {
            $subcategories = $this->getSubcategoriesQuery();
            
            // Apply filters
            if ($request->filled('category_id')) {
                $subcategories = $subcategories->where('category_id', $request->category_id);
            }
            
            if ($request->filled('status')) {
                $subcategories = $subcategories->where('status', $request->status);
            }
            
            if ($request->filled('featured')) {
                $featured = $request->featured === 'yes' ? 1 : 0;
                $subcategories = $subcategories->where('is_featured', $featured);
            }
            
            if ($request->filled('search')) {
                $search = $request->search;
                $subcategories = $subcategories->filter(function($subcategory) use ($search) {
                    return stripos($subcategory['name'], $search) !== false ||
                           stripos($subcategory['slug'], $search) !== false ||
                           stripos($subcategory['description'], $search) !== false;
                });
            }
            
            // Get subcategory statistics
            $stats = $this->getSubcategoryStatistics();
            
            // Get parent categories for filter
            $categories = $this->getParentCategories();
            
            return view('admin.subcategories.index', compact('subcategories', 'stats', 'categories'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching subcategories: ' . $e->getMessage());
            return back()->with('error', 'Failed to load subcategories.');
        }
    }

    /**
     * Show the form for creating a new subcategory.
     */
    public function create()
    {
        try {
            $categories = $this->getParentCategories();
            
            Log::info('Loading subcategory create form', ['categories_count' => count($categories)]);
            
            return view('admin.subcategories.create', compact('categories'));
            
        } catch (\Exception $e) {
            Log::error('Error loading subcategory create form: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Failed to load subcategory form: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created subcategory in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'commission_type' => 'nullable|in:percentage,fixed,disabled',
            'commission_rate' => 'nullable|numeric|min:0|max:100'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            
            // Generate slug if not provided
            $slug = $request->slug ?: Str::slug($request->name);
            
            // Handle image upload and removal
            $imagePath = null;
            $imageData = null;
            if ($request->hasFile('image')) {
                try {
                    $imageData = $this->uploadSubcategoryImage($request->file('image'), 'subcategories');
                    // Use the original size path as the main image path (includes full folder structure)
                    $imagePath = $imageData['sizes']['original']['path'] ?? $imageData['filename'];
                } catch (\Exception $e) {
                    return back()->withErrors(['image' => 'Failed to upload image: ' . $e->getMessage()])->withInput();
                }
            }
            
            // Handle banner image upload using ImageUploadService
            $bannerImagePath = null;
            if ($request->hasFile('banner_image')) {
                $bannerData = $this->imageUploadService->uploadSingle($request->file('banner_image'), 'subcategories/banners');
                $bannerImagePath = $bannerData['urls']['large']; // Use large size URL for banners
            }
            
            $subcategoryData = [
                'category_id' => $request->category_id,
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'image' => $imagePath,
                'image_data' => $imageData ? json_encode($imageData) : null,
                'banner_image' => $bannerImagePath,
                'icon' => $request->icon,
                'status' => $request->boolean('is_active', true) ? 'active' : 'inactive',
                'is_featured' => $request->boolean('is_featured'),
                'sort_order' => $request->sort_order ?: 0,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'commission_type' => $request->commission_type,
                'commission_rate' => $request->commission_rate,
            ];
            
            Log::info('Subcategory data prepared for creation', $subcategoryData);
            
            $subcategory = $this->createSubcategory($subcategoryData);
            
            DB::commit();
            
            Log::info('Subcategory created successfully', ['subcategory_id' => $subcategory['id']]);
            
            return redirect()->route('admin.subcategories.index')
                           ->with('success', 'Subcategory created successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating subcategory: ' . $e->getMessage());
            return back()->with('error', 'Failed to create subcategory.')->withInput();
        }
    }

    /**
     * Display the specified subcategory.
     */
    public function show($id)
    {
        try {
            $categoryModel = $this->findSubcategory($id);
            
            if (!$categoryModel) {
                return back()->with('error', 'Subcategory not found.');
            }
            
            // Convert to array format with parent category info
            $subcategory = [
                'id' => $categoryModel->id,
                'category_id' => $categoryModel->parent_id,
                'name' => $categoryModel->name,
                'slug' => $categoryModel->slug,
                'description' => $categoryModel->description,
                'short_description' => $categoryModel->short_description,
                'image' => $categoryModel->image ? $this->formatImageUrl($categoryModel->image) : null,
                'banner_image' => $categoryModel->banner_image ? $this->formatImageUrl($categoryModel->banner_image) : null,
                'icon' => $categoryModel->icon,
                'is_active' => $categoryModel->is_active,
                'is_featured' => $categoryModel->is_featured,
                'sort_order' => $categoryModel->sort_order,
                'meta_title' => $categoryModel->meta_title,
                'meta_description' => $categoryModel->meta_description,
                'meta_keywords' => $categoryModel->meta_keywords,
                'created_at' => $categoryModel->created_at,
                'updated_at' => $categoryModel->updated_at,
            ];
            
            // Get parent category information
            $category = null;
            if ($categoryModel->parent) {
                $category = [
                    'id' => $categoryModel->parent->id,
                    'name' => $categoryModel->parent->name,
                    'slug' => $categoryModel->parent->slug
                ];
            }
            
            // Get statistics
            $stats = [
                'total_products' => $categoryModel->subcategoryProducts()->count(),
                'active_products' => $categoryModel->subcategoryProducts()->where('is_active', true)->count(),
                'featured_products' => $categoryModel->subcategoryProducts()->where('is_featured', true)->count(),
                'total_sales' => 0 // Mock data
            ];
            
            // Get recent products in this subcategory
            $recentProducts = $this->getRecentProducts($id, 5);
            
            return view('admin.subcategories.show', compact('subcategory', 'category', 'stats', 'recentProducts'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching subcategory details: ' . $e->getMessage());
            return back()->with('error', 'Failed to load subcategory details.');
        }
    }

    /**
     * Show the form for editing the specified subcategory.
     */
    public function edit($id)
    {
        try {
            $subcategoryModel = $this->findSubcategory($id);
            
            if (!$subcategoryModel) {
                return back()->with('error', 'Subcategory not found.');
            }
            
            // Convert model to array format for the view
            $subcategory = $this->formatSubcategoryForView($subcategoryModel);
            $categories = $this->getParentCategories();
            
            // Debug logging
            Log::info('Subcategory Edit Data:', [
                'subcategory_id' => $id,
                'subcategory_data' => $subcategory,
                'categories_count' => count($categories)
            ]);
            
            return view('admin.subcategories.edit', compact('subcategory', 'categories'));
            
        } catch (\Exception $e) {
            Log::error('Error loading subcategory edit form: ' . $e->getMessage());
            return back()->with('error', 'Failed to load subcategory form.');
        }
    }

    /**
     * Update the specified subcategory in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('categories', 'slug')->ignore($id)
            ],
            'description' => 'nullable|string|max:1000',
            'short_description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'commission_type' => 'nullable|in:percentage,fixed,disabled',
            'commission_rate' => 'nullable|numeric|min:0|max:100'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $subcategory = $this->findSubcategory($id);
            
            if (!$subcategory) {
                return back()->with('error', 'Subcategory not found.');
            }
            
            DB::beginTransaction();
            
            // Generate slug if not provided
            $slug = $request->slug ?: Str::slug($request->name);
            
            // Handle image upload and removal
            $imagePath = $subcategory['image'];
            $imageData = null;
            
            // Handle image removal
            if ($request->has('remove_image') && $request->remove_image) {
                // Delete existing image files
                if ($subcategory->image_data) {
                    $oldImageData = json_decode($subcategory->image_data, true);
                    if ($oldImageData) {
                        $this->deleteImageFiles($oldImageData);
                    }
                } elseif ($imagePath) {
                    // Handle legacy image deletion
                    $this->deleteLegacyImageFile($imagePath);
                }
                
                $imagePath = null;
                $imageData = null;
            }
            // Handle new image upload
            elseif ($request->hasFile('image')) {
                // Delete old image files
                if ($subcategory->image_data) {
                    $oldImageData = json_decode($subcategory->image_data, true);
                    if ($oldImageData) {
                        $this->deleteImageFiles($oldImageData);
                    }
                } elseif ($imagePath) {
                    // Handle legacy image deletion
                    $this->deleteLegacyImageFile($imagePath);
                }
                
                try {
                    $imageData = $this->uploadSubcategoryImage($request->file('image'), 'subcategories');
                    // Use the original size path as the main image path (includes full folder structure)
                    $imagePath = $imageData['sizes']['original']['path'] ?? $imageData['filename'];
                } catch (\Exception $e) {
                    return back()->withErrors(['image' => 'Failed to upload image: ' . $e->getMessage()])->withInput();
                }
            }
            
            // Handle banner image upload using ImageUploadService
            $bannerImagePath = $subcategory['banner_image'];
            if ($request->hasFile('banner_image')) {
                // Delete old banner image
                if ($bannerImagePath) {
                    $this->deleteImage($bannerImagePath);
                }
                $bannerData = $this->imageUploadService->uploadSingle($request->file('banner_image'), 'subcategories/banners');
                $bannerImagePath = $bannerData['urls']['large']; // Use large size URL for banners
            }
            
            $subcategoryData = [
                'category_id' => $request->category_id,
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'short_description' => $request->short_description,
                'image' => $imagePath,
                'image_data' => $imageData ? json_encode($imageData) : ($imagePath ? null : null),
                'banner_image' => $bannerImagePath,
                'icon' => $request->icon,
                'is_active' => $request->boolean('is_active', true),
                'is_featured' => $request->boolean('is_featured'),
                'sort_order' => $request->sort_order ?: 0,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'commission_type' => $request->commission_type,
                'commission_rate' => $request->commission_rate
            ];
            
            $this->updateSubcategory($id, $subcategoryData);
            
            DB::commit();
            
            Log::info('Subcategory updated successfully', ['subcategory_id' => $id]);
            
            return redirect()->route('admin.subcategories.show', $id)
                           ->with('success', 'Subcategory updated successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating subcategory: ' . $e->getMessage());
            return back()->with('error', 'Failed to update subcategory.')->withInput();
        }
    }

    /**
     * Remove the specified subcategory from storage.
     */
    public function destroy($id)
    {
        try {
            $subcategory = $this->findSubcategory($id);
            
            if (!$subcategory) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Subcategory not found.'
                    ], 404);
                }
                return back()->with('error', 'Subcategory not found.');
            }
            
            // Check if subcategory has products
            $productsCount = $this->getProductsCount($id);
            if ($productsCount > 0) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot delete subcategory. It contains ' . $productsCount . ' product(s).'
                    ], 422);
                }
                return back()->with('error', 'Cannot delete subcategory. It contains ' . $productsCount . ' product(s).');
            }
            
            DB::beginTransaction();
            
            // Delete images
            if ($subcategory['image']) {
                $this->deleteImage($subcategory['image']);
            }
            if ($subcategory['banner_image']) {
                $this->deleteImage($subcategory['banner_image']);
            }
            
            // Delete the subcategory
            $this->deleteSubcategory($id);
            
            DB::commit();
            
            Log::info('Subcategory deleted successfully', ['subcategory_id' => $id]);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Subcategory deleted successfully.'
                ]);
            }
            
            return redirect()->route('admin.subcategories.index')
                           ->with('success', 'Subcategory deleted successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting subcategory: ' . $e->getMessage());
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete subcategory.'
                ], 500);
            }
            
            return back()->with('error', 'Failed to delete subcategory.');
        }
    }

    /**
     * Toggle subcategory status.
     */
    public function toggleStatus($id)
    {
        try {
            Log::info('Toggle status request received', ['subcategory_id' => $id]);
            
            $subcategory = $this->findSubcategory($id);
            
            if (!$subcategory) {
                Log::warning('Subcategory not found for toggle status', ['subcategory_id' => $id]);
                return response()->json(['error' => 'Subcategory not found.'], 404);
            }
            
            $currentStatus = $subcategory->status ?? 'inactive';
            $newStatus = $currentStatus === 'active' ? 'inactive' : 'active';
            
            Log::info('Toggling subcategory status', [
                'subcategory_id' => $id,
                'current_status' => $currentStatus,
                'new_status' => $newStatus
            ]);
            
            $updated = $this->updateSubcategory($id, ['status' => $newStatus]);
            
            if ($updated) {
                Log::info('Subcategory status toggled successfully', [
                    'subcategory_id' => $id,
                    'status' => $newStatus
                ]);
                
                return response()->json([
                    'success' => true,
                    'status' => $newStatus,
                    'message' => 'Subcategory status updated successfully.'
                ]);
            } else {
                Log::error('Failed to update subcategory status', ['subcategory_id' => $id]);
                return response()->json(['error' => 'Failed to update subcategory.'], 500);
            }
            
        } catch (\Exception $e) {
            Log::error('Error toggling subcategory status: ' . $e->getMessage(), [
                'subcategory_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to update status.'], 500);
        }
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured($id)
    {
        try {
            $subcategory = $this->findSubcategory($id);
            
            if (!$subcategory) {
                return response()->json(['error' => 'Subcategory not found.'], 404);
            }
            
            $newFeatured = !$subcategory->is_featured;
            $this->updateSubcategory($id, ['is_featured' => $newFeatured]);
            
            Log::info('Subcategory featured status toggled', ['subcategory_id' => $id, 'featured' => $newFeatured]);
            
            return response()->json([
                'success' => true,
                'featured' => $newFeatured,
                'message' => 'Subcategory featured status updated successfully.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error toggling subcategory featured status: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update featured status.'], 500);
        }
    }

    /**
     * Duplicate a subcategory.
     */
    public function duplicate($id)
    {
        try {
            Log::info('Duplicate subcategory request received', ['id' => $id]);
            
            DB::beginTransaction();
            
            // Find the original subcategory using the model directly
            $originalSubcategory = Category::where('parent_id', '!=', null)->find($id);
            
            if (!$originalSubcategory) {
                Log::warning('Subcategory not found for duplication', ['id' => $id]);
                return response()->json(['error' => 'Subcategory not found.'], 404);
            }
            
            Log::info('Found subcategory for duplication', [
                'id' => $originalSubcategory->id,
                'name' => $originalSubcategory->name
            ]);
            
            // Create a duplicate using Laravel's replicate method
            $duplicate = $originalSubcategory->replicate();
            
            // Modify the duplicate's properties
            $duplicate->name = $originalSubcategory->name . ' Copy';
            $duplicate->slug = Str::slug($originalSubcategory->slug . '-copy-' . time());
            $duplicate->status = 'inactive'; // Set duplicate as inactive initially
            $duplicate->is_featured = false;
            
            // Handle image duplication if exists
            if (!empty($originalSubcategory->image)) {
                try {
                    $duplicate->image = $this->duplicateImage($originalSubcategory->image);
                } catch (\Exception $imageError) {
                    Log::warning('Failed to duplicate image, continuing without image', [
                        'error' => $imageError->getMessage()
                    ]);
                    $duplicate->image = null;
                }
            }
            
            // Save the duplicate
            $duplicate->save();
            
            DB::commit();
            
            Log::info('Subcategory duplicated successfully', [
                'original_id' => $id,
                'duplicate_id' => $duplicate->id,
                'duplicate_name' => $duplicate->name
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Subcategory duplicated successfully.',
                'duplicate_id' => $duplicate->id,
                'duplicate_name' => $duplicate->name
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error duplicating subcategory: ' . $e->getMessage(), [
                'id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'An error occurred while duplicating subcategory.'], 500);
        }
    }

    /**
     * Generate a unique slug for duplication.
     */
    private function generateUniqueSlug($baseSlug)
    {
        $slug = $baseSlug;
        $counter = 1;
        
        while (Category::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    /**
     * Duplicate an image file using HandlesImageUploads trait patterns.
     */
    private function duplicateImage($originalImagePath)
    {
        try {
            if (!$originalImagePath) {
                return null;
            }
            
            // Handle both full URLs and relative paths
            $imagePath = $originalImagePath;
            if (str_starts_with($originalImagePath, 'http://') || str_starts_with($originalImagePath, 'https://')) {
                // Extract path from URL
                $parsedUrl = parse_url($originalImagePath);
                $imagePath = ltrim($parsedUrl['path'] ?? '', '/');
                if (str_starts_with($imagePath, 'storage/')) {
                    $imagePath = substr($imagePath, 8); // Remove 'storage/' prefix
                }
            }
            
            // Check if the original file exists
            if (!Storage::disk('public')->exists($imagePath)) {
                Log::warning('Original image not found for duplication', ['path' => $imagePath]);
                return null;
            }
            
            // Get file info
            $pathInfo = pathinfo($imagePath);
            $extension = $pathInfo['extension'] ?? 'jpg';
            $originalName = $pathInfo['filename'] ?? 'duplicate';
            
            // Generate unique filename with timestamp (following trait pattern)
            $timestamp = time();
            $newFilename = Str::slug($originalName . '-copy') . '_' . $timestamp . '.' . $extension;
            
            // Create date-based folder structure (following trait pattern)
            $datePath = Carbon::now()->format('Y/m');
            $newFolder = 'subcategories/' . $datePath;
            $newImagePath = $newFolder . '/' . $newFilename;
            
            // Ensure directory exists
            Storage::disk('public')->makeDirectory($newFolder);
            
            // Copy the file to new location
            if (Storage::disk('public')->copy($imagePath, $newImagePath)) {
                Log::info('Image duplicated successfully', [
                    'original' => $imagePath,
                    'duplicate' => $newImagePath
                ]);
                return $newImagePath;
            } else {
                Log::error('Failed to copy image file', [
                    'original' => $imagePath,
                    'target' => $newImagePath
                ]);
                return null;
            }
            
        } catch (\Exception $e) {
            Log::error('Error duplicating image: ' . $e->getMessage(), [
                'original_path' => $originalImagePath,
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Bulk actions for subcategories.
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,feature,unfeature,delete',
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer'
        ]);

        if ($validator->fails()) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator);
        }

        try {
            $subcategoryIds = $request->ids; // Changed from subcategory_ids to ids
            $action = $request->action;
            $processedCount = 0;
            
            DB::beginTransaction();
            
            foreach ($subcategoryIds as $subcategoryId) {
                $subcategory = $this->findSubcategory($subcategoryId);
                if (!$subcategory) continue;
                
                switch ($action) {
                    case 'activate':
                        $this->updateSubcategory($subcategoryId, ['status' => 'active']);
                        $processedCount++;
                        break;
                        
                    case 'deactivate':
                        $this->updateSubcategory($subcategoryId, ['status' => 'inactive']);
                        $processedCount++;
                        break;
                        
                    case 'feature':
                        $this->updateSubcategory($subcategoryId, ['is_featured' => true]);
                        $processedCount++;
                        break;
                        
                    case 'unfeature':
                        $this->updateSubcategory($subcategoryId, ['is_featured' => false]);
                        $processedCount++;
                        break;
                        
                    case 'delete':
                        // Check if subcategory has products
                        $productsCount = $this->getProductsCount($subcategoryId);
                        if ($productsCount === 0) {
                            // Delete images
                            if ($subcategory['image']) {
                                $this->deleteImage($subcategory['image']);
                            }
                            if ($subcategory['banner_image']) {
                                $this->deleteImage($subcategory['banner_image']);
                            }
                            $this->deleteSubcategory($subcategoryId);
                            $processedCount++;
                        }
                        break;
                }
            }
            
            DB::commit();
            
            Log::info('Bulk action performed on subcategories', [
                'action' => $action,
                'processed_count' => $processedCount,
                'subcategory_ids' => $subcategoryIds
            ]);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Successfully processed {$processedCount} subcategory(s)."
                ]);
            }
            
            return back()->with('success', "Successfully processed {$processedCount} subcategory(s).");
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error performing bulk action: ' . $e->getMessage());
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to perform bulk action.'
                ], 500);
            }
            
            return back()->with('error', 'Failed to perform bulk action.');
        }
    }

    /**
     * Get subcategories by category (AJAX).
     */
    public function getByCategory($categoryId)
    {
        try {
            $subcategories = $this->getSubcategoriesByCategory($categoryId);
            
            return response()->json([
                'success' => true,
                'subcategories' => $subcategories
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching subcategories by category: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch subcategories.'], 500);
        }
    }

    /**
     * Export subcategories to CSV.
     */
    public function export()
    {
        try {
            $subcategories = $this->getSubcategoriesQuery();
            $filename = 'subcategories_export_' . date('Y-m-d_H-i-s') . '.csv';
            
            return $this->generateCsvExport($subcategories, $filename);
            
        } catch (\Exception $e) {
            Log::error('Error exporting subcategories: ' . $e->getMessage());
            return back()->with('error', 'Failed to export subcategories.');
        }
    }

    /**
     * Update sort order (AJAX).
     */
    public function updateSortOrder(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'subcategory_id' => 'required|integer',
                'sort_order' => 'required|integer|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => 'Invalid data.'], 400);
            }

            $this->updateSubcategory($request->subcategory_id, ['sort_order' => $request->sort_order]);
            
            return response()->json([
                'success' => true,
                'message' => 'Sort order updated successfully.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error updating sort order: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update sort order.'], 500);
        }
    }

    // Private helper methods

    private function getSubcategoriesQuery()
    {
        return Category::with('parent')
            ->whereNotNull('parent_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'category_id' => $category->parent_id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'short_description' => $category->short_description,
                    'image' => $category->image ? $this->formatImageUrl($category->image) : null,
                    'banner_image' => $category->banner_image ? $this->formatImageUrl($category->banner_image) : null,
                    'icon' => $category->icon,
                    'status' => $category->status ?? 'inactive',
                    'is_featured' => $category->is_featured,
                    'sort_order' => $category->sort_order,
                    'meta_title' => $category->meta_title,
                    'meta_description' => $category->meta_description,
                    'meta_keywords' => $category->meta_keywords,
                    'created_at' => $category->created_at,
                    'updated_at' => $category->updated_at,
                    'category' => $category->parent ? [
                        'id' => $category->parent->id,
                        'name' => $category->parent->name,
                        'slug' => $category->parent->slug
                    ] : null,
                    'products_count' => $category->subcategoryProducts()->count()
                ];
            });
    }
    private function getSubcategoryStatistics()
    {
        return [
            'total_subcategories' => 45,
            'active_subcategories' => 38,
            'inactive_subcategories' => 7,
            'featured_subcategories' => 12,
            'subcategories_with_products' => 25,
            'total_products' => 1250,
            'category_distribution' => [
                'Clothing' => 15,
                'Electronics' => 12,
                'Home & Garden' => 8,
                'Books' => 6,
                'Sports' => 4
            ]
        ];
    }

    private function getParentCategories()
    {
        try {
            $categories = Category::whereNull('parent_id')
                                ->whereIn('status', ['active', 'Active'])
                                ->orderBy('name')
                                ->get(['id', 'name', 'slug']);
            
            // If no categories found, return empty collection
            if ($categories->isEmpty()) {
                Log::warning('No parent categories found in database');
                // Return mock data as fallback for development
                return [
                    ['id' => 1, 'name' => 'Electronics', 'slug' => 'electronics'],
                    ['id' => 2, 'name' => 'Clothing', 'slug' => 'clothing'],
                    ['id' => 3, 'name' => 'Home & Garden', 'slug' => 'home-garden'],
                    ['id' => 4, 'name' => 'Books', 'slug' => 'books'],
                    ['id' => 5, 'name' => 'Sports', 'slug' => 'sports']
                ];
            }
            
            return $categories->toArray();
        } catch (\Exception $e) {
            Log::error('Error fetching parent categories: ' . $e->getMessage());
            // Return mock data as fallback
            return [
                ['id' => 1, 'name' => 'Electronics', 'slug' => 'electronics'],
                ['id' => 2, 'name' => 'Clothing', 'slug' => 'clothing'],
                ['id' => 3, 'name' => 'Home & Garden', 'slug' => 'home-garden'],
                ['id' => 4, 'name' => 'Books', 'slug' => 'books'],
                ['id' => 5, 'name' => 'Sports', 'slug' => 'sports']
            ];
        }
    }

    private function createSubcategory($data)
    {
        try {
            $categoryData = array_merge($data, ['parent_id' => $data['category_id']]);
            unset($categoryData['category_id']); // Remove category_id since we're using parent_id
            
            Log::info('Creating subcategory with data', $categoryData);
            
            $category = Category::create($categoryData);
            
            Log::info('Subcategory created successfully', ['id' => $category->id]);
            
            return $category;
        } catch (\Exception $e) {
            Log::error('Error in createSubcategory: ' . $e->getMessage(), [
                'data' => $data,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    private function findSubcategory($id)
    {
        return Category::with('parent')->whereNotNull('parent_id')->find($id);
    }

    private function formatSubcategoryForView($subcategory)
    {
        if (!$subcategory) {
            return null;
        }
        
        // Return data in array format for consistency with the view
        return [
            'id' => $subcategory->id,
            'name' => $subcategory->name,
            'slug' => $subcategory->slug,
            'description' => $subcategory->description,
            'short_description' => $subcategory->short_description ?? '',
            'category_id' => $subcategory->parent_id, // parent_id is the category_id for subcategories
            'image' => $subcategory->image ? $this->formatImageUrl($subcategory->image) : null,
            'banner_image' => $subcategory->banner_image ? $this->formatImageUrl($subcategory->banner_image) : null,
            'icon' => $subcategory->icon,
            'is_featured' => $subcategory->is_featured ?? false,
            'sort_order' => $subcategory->sort_order ?? 0,
            'meta_title' => $subcategory->meta_title,
            'meta_description' => $subcategory->meta_description,
            'meta_keywords' => $subcategory->meta_keywords,
            'status' => $subcategory->status ?? 'inactive',
            'created_at' => $subcategory->created_at,
            'updated_at' => $subcategory->updated_at,
        ];
    }

    /**
     * Format image URL to work properly in both local and live environments
     */
    private function formatImageUrl($imagePath)
    {
        if (!$imagePath) {
            return null;
        }
        
        // If the path already contains a full URL, return as-is
        if (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
            return $imagePath;
        }
        
        // For live server compatibility, check if storage is linked
        $publicStoragePath = public_path('storage');
        
        // If storage link exists (local/proper setup), use asset()
        if (is_link($publicStoragePath) || is_dir($publicStoragePath)) {
            // Remove any leading storage/ if present to avoid duplication
            $cleanPath = ltrim($imagePath, '/');
            if (str_starts_with($cleanPath, 'storage/')) {
                $cleanPath = substr($cleanPath, 8); // Remove 'storage/' prefix
            }
            return asset('storage/' . $cleanPath);
        }
        
        // For live servers without storage link, use direct app URL
        $appUrl = config('app.url', url('/'));
        $cleanPath = ltrim($imagePath, '/');
        if (str_starts_with($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, 8); // Remove 'storage/' prefix
        }
        
        // Return direct storage URL for live server
        return $appUrl . '/storage/' . $cleanPath;
    }

    private function updateSubcategory($id, $data)
    {
        $category = Category::whereNotNull('parent_id')->find($id);
        if ($category) {
            // Only add parent_id mapping if category_id is present in the data
            $updateData = $data;
            if (isset($data['category_id'])) {
                $updateData['parent_id'] = $data['category_id'];
                unset($updateData['category_id']); // Remove category_id as it's not a column
            }
            
            $category->update($updateData);
            return $category;
        }
        return null;
    }

    private function deleteSubcategory($id)
    {
        $category = Category::whereNotNull('parent_id')->find($id);
        if ($category) {
            $category->delete();
            return true;
        }
        return false;
    }

    private function getSubcategoriesByCategory($categoryId)
    {
        // Mock data - replace with actual database query
        return $this->getSubcategoriesQuery()
                   ->where('category_id', $categoryId)
                   ->where('is_active', true)
                   ->sortBy('sort_order')
                   ->values();
    }

    private function getProductsCount($subcategoryId)
    {
        try {
            $subcategory = Category::find($subcategoryId);
            return $subcategory ? $subcategory->subcategoryProducts()->count() : 0;
        } catch (\Exception $e) {
            Log::error('Error getting products count: ' . $e->getMessage());
            return 0;
        }
    }

    private function getRecentProducts($subcategoryId, $limit = 5)
    {
        // Mock data - replace with actual database query
        return collect([
            [
                'id' => 1, 
                'name' => 'Smartphone Galaxy S24', 
                'price' => 899.99, 
                'image' => 'subcategories/2025/08/medium/68a58264a05ef_1755677284.jpg',
                'stock' => 45,
                'is_active' => true,
                'created_at' => now()->subDays(1)
            ],
            [
                'id' => 2, 
                'name' => 'Wireless Headphones', 
                'price' => 199.99, 
                'image' => 'subcategories/2025/08/medium/68a58264a05ef_1755677284.jpg',
                'stock' => 23,
                'is_active' => true,
                'created_at' => now()->subDays(2)
            ],
            [
                'id' => 3, 
                'name' => 'Laptop Gaming Pro', 
                'price' => 1299.99, 
                'image' => 'subcategories/2025/08/medium/68a58264a05ef_1755677284.jpg',
                'stock' => 0,
                'is_active' => false,
                'created_at' => now()->subDays(3)
            ],
            [
                'id' => 4, 
                'name' => 'Smart Watch Ultra', 
                'price' => 399.99, 
                'image' => null, // This one will show placeholder
                'stock' => 12,
                'is_active' => true,
                'created_at' => now()->subDays(4)
            ],
            [
                'id' => 5, 
                'name' => 'Tablet Pro 12"', 
                'price' => 799.99, 
                'image' => 'subcategories/2025/08/medium/68a58264a05ef_1755677284.jpg',
                'stock' => 8,
                'is_active' => true,
                'created_at' => now()->subDays(5)
            ]
        ])->take($limit);
    }

    private function uploadImage($file, $directory)
    {
        try {
            // Use the trait method for subcategory images 
            $imageData = $this->uploadCategoryImage($file, $directory);
            // Return the original image path for backward compatibility
            return $imageData['filename'];
        } catch (\Exception $e) {
            Log::error('Subcategory image upload failed: ' . $e->getMessage());
            throw new \Exception('Failed to upload subcategory image: ' . $e->getMessage());
        }
    }

    private function deleteImage($path)
    {
        try {
            if ($path) {
                // Extract image data from path for the service
                $pathParts = explode('/', $path);
                $filename = end($pathParts);
                $imageData = [
                    'filename' => $filename,
                    'path' => dirname($path),
                    'folder' => explode('/', $path)[0] ?? 'subcategories'
                ];
                
                return $this->imageUploadService->deleteImage($imageData);
            }
            return true;
        } catch (\Exception $e) {
            Log::error('Image deletion failed: ' . $e->getMessage());
            return false;
        }
    }

    private function generateCsvExport($subcategories, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($subcategories) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'ID',
                'Category',
                'Name',
                'Slug',
                'Description',
                'Products Count',
                'Status',
                'Featured',
                'Sort Order',
                'Created At'
            ]);
            
            // CSV Data
            foreach ($subcategories as $subcategory) {
                fputcsv($file, [
                    $subcategory['id'],
                    $subcategory['category']['name'],
                    $subcategory['name'],
                    $subcategory['slug'],
                    $subcategory['short_description'] ?? '',
                    $subcategory['products_count'],
                    $subcategory['is_active'] ? 'Active' : 'Inactive',
                    $subcategory['is_featured'] ? 'Yes' : 'No',
                    $subcategory['sort_order'],
                    $subcategory['created_at']
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
