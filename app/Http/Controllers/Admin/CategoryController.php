<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    use HandlesImageUploads;
    /**
     * Display a listing of categories.
     */
    public function index(Request $request)
    {
        try {
            $query = Category::with(['parent', 'children']);
            
            // Apply filters
            if ($request->filled('status')) {
                $status = strtolower($request->status);
                $query->where('status', $status);
            }
            
            if ($request->filled('featured')) {
                $featured = $request->featured === 'yes' ? true : false;
                $query->where('is_featured', $featured);
            }
            
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('slug', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }
            
            $categories = $query->orderBy('sort_order')->orderBy('name')->get();
            
            // Get category statistics
            $stats = [
                'total' => Category::count(),
                'active' => Category::where('status', 'active')->count(),
                'inactive' => Category::where('status', 'inactive')->count(),
                'featured' => Category::where('is_featured', true)->count(),
                'root_categories' => Category::whereNull('parent_id')->count(),
                'subcategories' => Category::whereNotNull('parent_id')->count()
            ];
            
            // Sort categories if needed
            $sortBy = $request->get('sort_by', 'sort_order');
            $sortDirection = $request->get('sort_direction', 'asc');
            
            if ($sortBy !== 'sort_order') {
                if ($sortDirection === 'desc') {
                    $categories = $categories->sortByDesc($sortBy);
                } else {
                    $categories = $categories->sortBy($sortBy);
                }
            }
            
            // Convert to collection for pagination
            $categories = collect($categories);
            
            // Paginate results
            $perPage = $request->get('per_page', 15);
            $page = $request->get('page', 1);
            $total = $categories->count();
            $categories = $categories->forPage($page, $perPage);
            
            // Create pagination info
            $pagination = [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => ceil($total / $perPage),
                'from' => (($page - 1) * $perPage) + 1,
                'to' => min($page * $perPage, $total)
            ];
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'categories' => $categories->values(),
                        'stats' => $stats,
                        'pagination' => $pagination
                    ]
                ]);
            }
            
            return view('admin.categories.index', compact('categories', 'stats', 'pagination'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching categories: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch categories'
                ], 500);
            }
            
            return back()->withErrors(['error' => 'Failed to fetch categories']);
        }
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        $parentCategories = $this->getParentCategoriesForSelect();
        return view('admin.categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:1000',
                'parent_id' => 'nullable|integer|exists:categories,id',
                'sort_order' => 'nullable|integer|min:0|max:999',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
                'meta_keywords' => 'nullable|string|max:500',
                'status' => 'required|in:Active,Inactive',
                'is_featured' => 'nullable|boolean',
                'show_in_menu' => 'nullable|boolean',
                'show_in_footer' => 'nullable|boolean',
                'commission_type' => 'nullable|in:percentage,fixed,disabled',
                'commission_rate' => 'nullable|numeric|min:0|max:100'
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => $validator->errors()
                    ], 422);
                }
                return back()->withErrors($validator)->withInput();
            }

            // Generate slug if not provided
            $slug = $request->slug ?: Str::slug($request->name);
            
            // Check slug uniqueness against mock data
            if ($this->isSlugTaken($slug)) {
                $slug = $this->generateUniqueSlug($slug);
            }

            // Handle image upload
            $imagePath = null;
            $imageData = null;
            if ($request->hasFile('image')) {
                try {
                    $imageData = $this->uploadCategoryImage($request->file('image'), 'categories');
                    // Use the original size path as the main image path (includes full folder structure)
                    $imagePath = $imageData['sizes']['original']['path'] ?? $imageData['filename'];
                } catch (\Exception $e) {
                    return back()->withErrors(['image' => 'Failed to upload image: ' . $e->getMessage()])->withInput();
                }
            }

            // Map status values from form to database
            $status = strtolower($request->status); // Convert 'Active' to 'active', 'Inactive' to 'inactive'

            // Create category in database
            $category = Category::create([
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'parent_id' => $request->parent_id,
                'sort_order' => $request->sort_order ?? 0,
                'image' => $imagePath,
                'image_data' => $imageData ? json_encode($imageData) : null,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'status' => $status,
                'is_featured' => $request->boolean('is_featured'),
                'show_in_menu' => $request->boolean('show_in_menu', true),
                'show_in_footer' => $request->boolean('show_in_footer'),
                'commission_type' => $request->commission_type,
                'commission_rate' => $request->commission_rate,
            ]);
            
            // Log the creation
            Log::info('Category created successfully', ['category' => $category->toArray()]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Category created successfully',
                    'data' => [
                        'category' => $category->toArray(),
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'status' => ucfirst($category->status), // Convert back to 'Active'/'Inactive' for display
                        'id' => $category->id
                    ],
                    'redirect_url' => route('admin.categories.index')
                ]);
            }

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category created successfully');

        } catch (\Exception $e) {
            Log::error('Error creating category: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating category: ' . $e->getMessage(),
                    'errors' => ['general' => [$e->getMessage()]]
                ], 500);
            }

            return back()->withErrors(['general' => 'Error creating category: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified category.
     */
    public function show($id)
    {
        try {
            $categoryModel = $this->findCategoryById($id);
            
            if (!$categoryModel) {
                return back()->withErrors(['error' => 'Category not found']);
            }

            $category = $this->formatCategoryForView($categoryModel);

            // Get subcategories
            $subcategories = $this->getSubcategoriesByParent($id);
            
            // Get category statistics
            $stats = $this->getCategoryDetailStats($id);

            return view('admin.categories.show', compact('category', 'subcategories', 'stats'));

        } catch (\Exception $e) {
            Log::error('Error showing category: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to load category details']);
        }
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit($id)
    {
        try {
            $categoryModel = $this->findCategoryById($id);
            
            if (!$categoryModel) {
                return back()->withErrors(['error' => 'Category not found']);
            }

            $category = $this->formatCategoryForView($categoryModel);
            $parentCategories = $this->getParentCategoriesForSelect($id);
            
            return view('admin.categories.edit', compact('category', 'parentCategories'));

        } catch (\Exception $e) {
            Log::error('Error loading category edit form: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to load category']);
        }
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $category = $this->findCategoryById($id);
            
            if (!$category) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Category not found'
                    ], 404);
                }
                return back()->withErrors(['error' => 'Category not found']);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'slug' => [
                    'nullable',
                    'string',
                    'max:255',
                    Rule::unique('categories')->ignore($id)
                ],
                'description' => 'nullable|string|max:1000',
                'parent_id' => [
                    'nullable',
                    'exists:categories,id',
                    function ($attribute, $value, $fail) use ($id) {
                        if ($value == $id) {
                            $fail('A category cannot be its own parent.');
                        }
                    }
                ],
                'sort_order' => 'nullable|integer|min:0|max:999',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
                'meta_keywords' => 'nullable|string|max:500',
                'status' => 'required|in:Active,Inactive',
                'is_featured' => 'nullable|boolean',
                'show_in_menu' => 'nullable|boolean',
                'show_in_footer' => 'nullable|boolean',
                'commission_type' => 'nullable|in:percentage,fixed,disabled',
                'commission_rate' => 'nullable|numeric|min:0|max:100'
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => $validator->errors()
                    ], 422);
                }
                return back()->withErrors($validator)->withInput();
            }

            // Generate slug if not provided
            $slug = $request->slug ?: Str::slug($request->name);
            
            // Handle image upload and removal
            $imagePath = $category['image'];
            $imageData = null;
            
            // Handle image removal
            if ($request->has('remove_image') && $request->remove_image) {
                // Delete existing image files
                if ($category->image_data) {
                    $oldImageData = json_decode($category->image_data, true);
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
                if ($category->image_data) {
                    $oldImageData = json_decode($category->image_data, true);
                    if ($oldImageData) {
                        $this->deleteImageFiles($oldImageData);
                    }
                } elseif ($imagePath) {
                    // Handle legacy image deletion
                    $this->deleteLegacyImageFile($imagePath);
                }
                
                try {
                    $imageData = $this->uploadCategoryImage($request->file('image'), 'categories');
                    // Use the original size path as the main image path (includes full folder structure)
                    $imagePath = $imageData['sizes']['original']['path'] ?? $imageData['filename'];
                } catch (\Exception $e) {
                    return back()->withErrors(['image' => 'Failed to upload image: ' . $e->getMessage()])->withInput();
                }
            }

            // Update category data
            $updateData = [
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'parent_id' => $request->parent_id,
                'sort_order' => $request->sort_order ?? 0,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'status' => strtolower($request->status),
                'is_featured' => $request->boolean('is_featured'),
                'show_in_menu' => $request->boolean('show_in_menu', true),
                'show_in_footer' => $request->boolean('show_in_footer'),
                'commission_type' => $request->commission_type,
                'commission_rate' => $request->commission_rate,
            ];

            if ($imagePath) {
                $updateData['image'] = $imagePath;
            }
            
            if ($imageData) {
                $updateData['image_data'] = json_encode($imageData);
            }

            // Update the category in database
            $category->update($updateData);
            
            Log::info('Category updated successfully', ['id' => $id, 'data' => $updateData]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Category updated successfully',
                    'data' => $category->fresh()
                ]);
            }

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category updated successfully');

        } catch (\Exception $e) {
            Log::error('Error updating category: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update category'
                ], 500);
            }

            return back()->withErrors(['error' => 'Failed to update category'])->withInput();
        }
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy($id)
    {
        try {
            $category = $this->findCategoryById($id);
            
            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }

            // Check if category has subcategories
            $subcategories = $this->getSubcategoriesByParent($id);
            if ($subcategories->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category that has subcategories'
                ], 400);
            }

            // Delete image if exists
            if ($category['image']) {
                Storage::disk('public')->delete($category['image']);
            }

            // Delete from database
            Category::destroy($id);
            
            Log::info('Category deleted successfully', ['id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting category: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete category'
            ], 500);
        }
    }

    /**
     * Bulk actions for categories
     */
    public function bulkAction(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'action' => 'required|in:delete,activate,deactivate,feature,unfeature',
                'ids' => 'required|array|min:1',
                'ids.*' => 'integer'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid bulk action request',
                    'errors' => $validator->errors()
                ], 422);
            }

            $action = $request->action;
            $ids = $request->ids;
            $successCount = 0;
            $errors = [];

            foreach ($ids as $id) {
                try {
                    switch ($action) {
                        case 'delete':
                            $this->destroy($id);
                            break;
                        case 'activate':
                            $this->updateCategoryStatus($id, 'Active');
                            break;
                        case 'deactivate':
                            $this->updateCategoryStatus($id, 'Inactive');
                            break;
                        case 'feature':
                            $this->updateCategoryFeatured($id, true);
                            break;
                        case 'unfeature':
                            $this->updateCategoryFeatured($id, false);
                            break;
                    }
                    $successCount++;
                } catch (\Exception $e) {
                    $errors[] = "Failed to {$action} category ID {$id}: " . $e->getMessage();
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully processed {$successCount} categories",
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            Log::error('Error in bulk action: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to process bulk action'
            ], 500);
        }
    }

    /**
     * Toggle category status
     */
    public function toggleStatus($id)
    {
        try {
            $category = Category::findOrFail($id);
            $newStatus = $category->status === 'active' ? 'inactive' : 'active';
            $category->update(['status' => $newStatus]);

            Log::info('Category status toggled', ['category_id' => $id, 'new_status' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => 'Category status updated successfully',
                'status' => $newStatus
            ]);

        } catch (\Exception $e) {
            Log::error('Error toggling category status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update category status'
            ], 500);
        }
    }

    /**
     * Toggle category featured status
     */
    public function toggleFeatured($id)
    {
        try {
            $category = Category::findOrFail($id);
            $newFeatured = !$category->is_featured;
            $category->update(['is_featured' => $newFeatured]);

            Log::info('Category featured status toggled', ['category_id' => $id, 'is_featured' => $newFeatured]);

            return response()->json([
                'success' => true,
                'message' => 'Category featured status updated successfully',
                'is_featured' => $newFeatured
            ]);

        } catch (\Exception $e) {
            Log::error('Error toggling category featured status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update category featured status'
            ], 500);
        }
    }

    /**
     * Get parent categories for API
     */
    public function getParentCategories()
    {
        try {
            $categories = Category::select('id', 'name', 'slug', 'parent_id', 'status', 'created_at')
                ->where('status', 'active')
                ->orderBy('name')
                ->get()
                ->map(function($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'level' => $category->parent_id ? 2 : 1,
                        'parent_id' => $category->parent_id,
                        'products_count' => 0, // You can add products relationship count later
                        'is_active' => $category->status === 'active',
                        'created_at' => $category->created_at->format('Y-m-d H:i:s')
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'categories' => $categories->values(),
                    'total_count' => $categories->count(),
                    'root_count' => $categories->where('parent_id', null)->count(),
                    'sub_count' => $categories->where('parent_id', '!=', null)->count()
                ],
                'message' => 'Parent categories fetched successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching parent categories: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch parent categories'
            ], 500);
        }
    }

    /**
     * Validate category slug
     */
    public function validateSlug(Request $request)
    {
        try {
            $slug = $request->get('slug');
            $id = $request->get('id'); // For edit mode
            
            if (!$slug) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slug is required'
                ], 400);
            }

            $isAvailable = !$this->isSlugTaken($slug, $id);

            return response()->json([
                'success' => true,
                'data' => [
                    'available' => $isAvailable,
                    'slug' => $slug,
                    'message' => $isAvailable ? 'Slug is available' : 'Slug is already taken'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error validating slug: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to validate slug'
            ], 500);
        }
    }

    /**
     * Get category details for API
     */
    public function getCategoryDetails($id)
    {
        try {
            $category = $this->findCategoryById($id);
            
            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }

            $categoryDetails = array_merge($category, [
                'level' => $category['parent_id'] ? 2 : 1,
                'subcategories_count' => $this->getSubcategoriesByParent($id)->count(),
                'can_delete' => $this->getSubcategoriesByParent($id)->count() === 0
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'category' => $categoryDetails
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching category details: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch category details'
            ], 500);
        }
    }

    // Helper methods
    
    private function getCategoriesData()
    {
        // Fetch real categories from database
        return Category::with(['parent', 'children'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'image' => $category->image ? asset('storage/' . $category->image) : 'https://via.placeholder.com/60x60',
                    'parent_id' => $category->parent_id,
                    'status' => ucfirst($category->status),
                    'products_count' => $category->products()->count(),
                    'sort_order' => $category->sort_order ?? 0,
                    'is_featured' => $category->is_featured ?? false,
                    'show_in_menu' => $category->show_in_menu ?? true,
                    'show_in_footer' => $category->show_in_footer ?? false,
                    'meta_title' => $category->meta_title,
                    'meta_description' => $category->meta_description,
                    'meta_keywords' => $category->meta_keywords,
                    'created_at' => $category->created_at ? $category->created_at->format('Y-m-d') : '',
                    'updated_at' => $category->updated_at ? $category->updated_at->format('Y-m-d') : ''
                ];
            })
            ->toArray();
    }

    private function getCategoryStatistics()
    {
        $categories = collect($this->getCategoriesData());
        
        return [
            'total' => $categories->count(),
            'active' => $categories->where('status', 'Active')->count(),
            'inactive' => $categories->where('status', 'Inactive')->count(),
            'featured' => $categories->where('is_featured', true)->count(),
            'root_categories' => $categories->whereNull('parent_id')->count(),
            'subcategories' => $categories->whereNotNull('parent_id')->count(),
            'total_products' => $categories->sum('products_count'),
            'categories_with_products' => $categories->where('products_count', '>', 0)->count()
        ];
    }

    private function getParentCategoriesForSelect($excludeId = null)
    {
        $categories = collect($this->getCategoriesData())
            ->whereNull('parent_id')
            ->where('status', 'Active');
            
        if ($excludeId) {
            $categories = $categories->where('id', '!=', $excludeId);
        }
        
        return $categories->pluck('name', 'id')->toArray();
    }

    private function findCategoryById($id)
    {
        return Category::with(['parent', 'children'])->find($id);
    }

    private function getSubcategoriesByParent($parentId)
    {
        return Category::where('parent_id', $parentId)->get();
    }

    private function getCategoryDetailStats($id)
    {
        $category = $this->findCategoryById($id);
        $subcategories = $this->getSubcategoriesByParent($id);
        
        return [
            'products_count' => $category['products_count'] ?? 0,
            'subcategories_count' => $subcategories->count(),
            'total_subcategory_products' => $subcategories->sum('products_count'),
            'created_date' => $category['created_at'] ?? '',
            'last_updated' => $category['updated_at'] ?? '',
        ];
    }

    private function isSlugTaken($slug, $excludeId = null)
    {
        $query = Category::where('slug', $slug);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }

    private function generateUniqueSlug($slug)
    {
        $originalSlug = $slug;
        $counter = 1;
        
        while ($this->isSlugTaken($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    private function getNextCategoryId()
    {
        $categories = collect($this->getCategoriesData());
        return $categories->max('id') + 1;
    }

    // Note: Method removed - now using uploadCategoryImage from HandlesImageUploads trait

    private function updateCategoryStatus($id, $status)
    {
        $category = Category::findOrFail($id);
        $category->update(['status' => strtolower($status)]);
        Log::info("Category {$id} status updated to {$status}");
    }

    private function updateCategoryFeatured($id, $featured)
    {
        $category = Category::findOrFail($id);
        $category->update(['is_featured' => $featured]);
        Log::info("Category {$id} featured status updated to " . ($featured ? 'true' : 'false'));
    }

    /**
     * Display the category tree view.
     */
    public function treeView()
    {
        try {
            // Get all categories with their relationships
            $categories = Category::with(['parent', 'children'])
                ->orderBy('parent_id')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();

            // Build tree structure
            $tree = $this->buildCategoryTree($categories);

            // Calculate stats
            $stats = [
                'total' => $categories->count(),
                'root_categories' => $categories->where('parent_id', null)->count(),
                'subcategories' => $categories->where('parent_id', '!=', null)->count(),
                'active' => $categories->where('status', 'active')->count(),
                'featured' => $categories->where('is_featured', true)->count(),
            ];

            return view('admin.categories.tree', compact('tree', 'categories', 'stats'));

        } catch (\Exception $e) {
            Log::error('Error loading category tree: ' . $e->getMessage());
            
            return redirect()->route('admin.categories.index')
                ->with('error', 'Failed to load category tree. Please try again.');
        }
    }

    /**
     * Build hierarchical tree structure from flat category collection.
     */
    private function buildCategoryTree($categories, $parentId = null)
    {
        $tree = [];
        
        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {
                $category->children_tree = $this->buildCategoryTree($categories, $category->id);
                $tree[] = $category;
            }
        }
        
        return $tree;
    }

    /**
     * Format category data for view display.
     */
    private function formatCategoryForView($category)
    {
        if (!$category) {
            return null;
        }
        
        // Get products count for this category
        $productsCount = DB::table('products')->where('category_id', $category->id)->count();
        
        // Get subcategories count
        $subcategoriesCount = Category::where('parent_id', $category->id)->count();
        
        // Format image URL properly
        $imageUrl = null;
        if ($category->image) {
            // If it already starts with http, use as is
            if (str_starts_with($category->image, 'http')) {
                $imageUrl = $category->image;
            } else {
                // Otherwise, build the proper storage URL
                $imageUrl = asset('storage/' . $category->image);
            }
        }
        
        // Return data in array format for consistency with the view
        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'description' => $category->description,
            'short_description' => $category->short_description ?? '',
            'parent_id' => $category->parent_id,
            'image' => $imageUrl,
            'banner_image' => $category->banner_image,
            'icon' => $category->icon,
            'color_code' => $category->color_code,
            'sort_order' => $category->sort_order ?? 0,
            'status' => $category->status ?? 'Active',
            'is_featured' => $category->is_featured ?? false,
            'show_in_menu' => $category->show_in_menu ?? true,
            'show_in_footer' => $category->show_in_footer ?? false,
            'meta_title' => $category->meta_title,
            'meta_description' => $category->meta_description,
            'meta_keywords' => $category->meta_keywords,
            'commission_rate' => $category->commission_rate,
            'commission_type' => $category->commission_type,
            'products_count' => $productsCount,
            'subcategories_count' => $subcategoriesCount,
            'created_at' => $category->created_at,
            'updated_at' => $category->updated_at,
        ];
    }
}
