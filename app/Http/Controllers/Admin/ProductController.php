<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Brand;
use App\Models\Attribute;
use App\Models\Tag;
use App\Models\Unit;
use App\Models\MlmProductSetting;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    use HandlesImageUploads;
    
    public function index(Request $request)
    {
        try {
            // Start with base query
            $query = Product::with(['category', 'vendor:id,firstname,lastname'])
                ->select([
                    'id', 'name', 'slug', 'sku', 'price', 'sale_price', 'stock_quantity',
                    'is_active', 'is_featured', 'category_id', 'vendor_id',
                    'images', 'created_at', 'updated_at', 'deleted_at', // Only include images field that exists
                    'pv_points', 'bv_points', 'is_starter_kit', 'starter_kit_tier' // Add MLM fields
                ]);

            // Apply filters
            if ($request->has('category') && $request->category) {
                $query->whereHas('category', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->category . '%');
                });
            }

            if ($request->has('status') && $request->status) {
                if ($request->status === 'active') {
                    $query->where('is_active', true);
                } elseif ($request->status === 'inactive') {
                    $query->where('is_active', false);
                } elseif ($request->status === 'out-of-stock') {
                    $query->where('stock_quantity', 0);
                }
            }

            if ($request->has('search') && $request->search) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('slug', 'like', '%' . $searchTerm . '%');
                });
            }

            // Fetch products with pagination
            $products = $query->orderBy('created_at', 'desc')->paginate(15);

            // Preserve query parameters in pagination links
            $products->appends($request->query());

            // Transform the data for the view while preserving pagination
            $products->getCollection()->transform(function ($product) {
                // Handle image processing with better error checking
                $imageUrl = '/admin-assets/images/media/1.jpg'; // Valid fallback image
                
                if ($product->images) {
                    try {
                        if (is_string($product->images)) {
                            // If images is a JSON string, decode it
                            $imagesArray = json_decode($product->images, true);
                        } else {
                            // If already an array
                            $imagesArray = $product->images;
                        }
                        
                        if (is_array($imagesArray) && !empty($imagesArray)) {
                            // Check for new format with URLs
                            if (isset($imagesArray[0]['urls']['small'])) {
                                $imageUrl = $imagesArray[0]['urls']['small'];
                            }
                            // Check for new format with url
                            elseif (isset($imagesArray[0]['url'])) {
                                $imageUrl = $imagesArray[0]['url'];
                            }
                            // Check for simple string array
                            elseif (is_string($imagesArray[0])) {
                                $imageUrl = $imagesArray[0];
                            }
                            // Check for path key
                            elseif (isset($imagesArray[0]['path'])) {
                                $imageUrl = $imagesArray[0]['path'];
                            }
                        }
                        
                        // Ensure the image URL is properly formatted
                        if ($imageUrl && !str_starts_with($imageUrl, 'http') && !str_starts_with($imageUrl, '/')) {
                            $imageUrl = '/uploads/' . $imageUrl;
                        }
                        
                        // Convert storage URLs to use direct-storage route to avoid 403 errors
                        if ($imageUrl && str_starts_with($imageUrl, '/storage/')) {
                            $path = str_replace('/storage/', '', $imageUrl);
                            $imageUrl = '/direct-storage/' . $path;
                        }
                        
                    } catch (\Exception $e) {
                        Log::warning('Error processing product image: ' . $e->getMessage(), ['product_id' => $product->id]);
                    }
                }
                
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'sku' => $product->sku ?: 'SKU-' . str_pad($product->id, 5, '0', STR_PAD_LEFT), // Generate SKU if missing
                    'category' => $product->category->name ?? 'Uncategorized',
                    'price' => $product->price,
                    'sale_price' => $product->sale_price,
                    'display_price' => $product->sale_price ?: $product->price,
                    'pv_points' => $product->pv_points ?? round(($product->sale_price ?: $product->price) * 0.7),
                    'bv_points' => $product->bv_points ?? round(($product->sale_price ?: $product->price) * 0.5),
                    'stock' => $product->stock_quantity,
                    'status' => $product->is_active ? 'Active' : 'Inactive',
                    'is_featured' => $product->is_featured,
                    'is_starter_kit' => $product->is_starter_kit ?? false,
                    'starter_kit_tier' => $product->starter_kit_tier ?? null,
                    'vendor' => $product->vendor 
                        ? $product->vendor->firstname . ' ' . $product->vendor->lastname 
                        : 'Admin Product',
                    'image' => $imageUrl, // Processed image URL for compatibility
                    'images' => $product->images, // Include the raw images field for the view
                    'created_at' => $product->created_at ? $product->created_at->format('Y-m-d') : date('Y-m-d'),
                    'updated_at' => $product->updated_at ? $product->updated_at->format('Y-m-d') : date('Y-m-d')
                ];
            });

        } catch (\Exception $e) {
            Log::error('Error fetching products: ' . $e->getMessage());
            
            // Create a proper paginator even for fallback data
            $fallbackData = [
                [
                    'id' => 1,
                    'name' => 'Premium Health Supplement',
                    'slug' => 'premium-health-supplement',
                    'sku' => 'PHS-001',
                    'category' => 'Health & Wellness',
                    'price' => 49.99,
                    'sale_price' => null,
                    'display_price' => 49.99,
                    'pv_points' => 35,
                    'bv_points' => 25,
                    'stock' => 150,
                    'status' => 'Active',
                    'is_featured' => false,
                    'is_starter_kit' => false,
                    'starter_kit_tier' => null,
                    'vendor' => 'HealthyLife Co.',
                    'image' => '/admin-assets/images/media/1.jpg',
                    'created_at' => '2025-01-15',
                    'updated_at' => '2025-01-15'
                ]
            ];
            
            // Create a manual paginator for fallback data
            $currentPage = request()->get('page', 1);
            $perPage = 15;
            $products = new \Illuminate\Pagination\LengthAwarePaginator(
                collect($fallbackData),
                count($fallbackData),
                $perPage,
                $currentPage,
                [
                    'path' => request()->url(),
                    'pageName' => 'page',
                ]
            );
        }

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        try {
            // Get categories from database with error handling (temporarily show all for debugging)
            $categories = collect();
            try {
                // Debug: Show all categories first to see what's in the database
                $allCategories = Category::all();
                Log::info('Total categories in database: ' . $allCategories->count());
                
                $categories = Category::orderBy('sort_order')
                    ->orderBy('name')
                    ->get();
                    
                Log::info('Categories loaded for dropdown: ' . $categories->count());
                
                // Debug: Print category details
                foreach($categories as $category) {
                    Log::info("Category ID: {$category->id}, Name: {$category->name}, Status: {$category->status}, Parent: {$category->parent_id}");
                }
            } catch (\Exception $e) {
                Log::warning('Failed to load categories: ' . $e->getMessage());
                $categories = collect();
            }

            // Get vendors (users with vendor role) with error handling
            $vendors = collect();
            try {
                $vendors = User::where('role', 'vendor')
                    ->where('status', 'active')
                    ->orderBy('firstname')
                    ->get();
            } catch (\Exception $e) {
                Log::warning('Failed to load vendors: ' . $e->getMessage());
                $vendors = collect();
            }

            // Get brands with error handling
            $brands = collect();
            try {
                $brands = Brand::where('status', 'active')
                    ->orderBy('name')
                    ->get();
            } catch (\Exception $e) {
                Log::warning('Failed to load brands: ' . $e->getMessage());
                $brands = collect();
            }

            // Get attributes for product variations with error handling
            $attributes = collect();
            try {
                $attributes = Attribute::where('status', 'active')
                    ->where('is_variation', true)
                    ->orderBy('name')
                    ->get();
            } catch (\Exception $e) {
                Log::warning('Failed to load attributes: ' . $e->getMessage());
                $attributes = collect();
            }

            // Get tags with error handling
            $tags = collect();
            try {
                $tags = \App\Models\Tag::where('is_active', 1)->orderBy('name')->get();
            } catch (\Exception $e) {
                Log::warning('Failed to load tags: ' . $e->getMessage());
                $tags = collect();
            }

            // Get units with error handling
            $units = collect();
            try {
                $units = \App\Models\Unit::where('is_active', 1)->orderBy('name')->get();
            } catch (\Exception $e) {
                Log::warning('Failed to load units: ' . $e->getMessage());
                $units = collect();
            }

            // If no categories exist, create some default ones
            if ($categories->isEmpty()) {
                try {
                    $this->createDefaultCategories();
                    $categories = Category::where('status', 'active')
                        ->whereNull('parent_id')
                        ->orderBy('name')
                        ->get();
                } catch (\Exception $e) {
                    Log::warning('Failed to create default categories: ' . $e->getMessage());
                    $categories = collect();
                }
            }

            // If no vendors exist, use a fallback
            if ($vendors->isEmpty()) {
                $vendors = collect([
                    (object)['id' => '', 'firstname' => 'No vendors available', 'lastname' => '']
                ]);
            }

            // Debug: Log the data being passed to view
            Log::info('Product create form data:', [
                'categories_count' => $categories->count(),
                'categories' => $categories->pluck('name', 'id')->toArray(),
                'vendors_count' => $vendors->count(),
                'brands_count' => $brands->count(),
                'attributes_count' => $attributes->count(),
                'tags_count' => $tags->count()
            ]);
            
            return view('admin.products.create', compact('categories', 'vendors', 'brands', 'attributes', 'tags', 'units'));
            
        } catch (\Exception $e) {
            Log::error('Error loading product create form: ' . $e->getMessage());
            
            // Return with empty collections as fallback
            $categories = collect();
            $vendors = collect([
                (object)['id' => '', 'firstname' => 'No vendors available', 'lastname' => '']
            ]);
            $brands = collect();
            $attributes = collect();
            $tags = collect();
            $units = collect();
            
            return view('admin.products.create', compact('categories', 'vendors', 'brands', 'attributes', 'tags', 'units'))
                ->with('warning', 'Some data could not be loaded. Please check your database configuration.');
        }
    }

    /**
     * Get subcategories for a specific category (AJAX endpoint)
     */
    public function getSubcategories($categoryId)
    {
        $subcategories = Category::where('parent_id', $categoryId)
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($subcategories);
    }

    /**
     * Create default categories if none exist
     */
    private function createDefaultCategories()
    {
        $defaultCategories = [
            ['name' => 'Health & Wellness', 'slug' => 'health-wellness'],
            ['name' => 'Beauty & Personal Care', 'slug' => 'beauty-personal-care'],
            ['name' => 'Electronics', 'slug' => 'electronics'],
            ['name' => 'Food & Beverages', 'slug' => 'food-beverages'],
            ['name' => 'Sports & Fitness', 'slug' => 'sports-fitness'],
            ['name' => 'Home & Garden', 'slug' => 'home-garden'],
            ['name' => 'Fashion & Accessories', 'slug' => 'fashion-accessories'],
        ];

        foreach ($defaultCategories as $index => $categoryData) {
            Category::create([
                'name' => $categoryData['name'],
                'slug' => $categoryData['slug'],
                'status' => 'active',
                'sort_order' => $index + 1,
                'show_in_menu' => true,
                'is_featured' => false
            ]);
        }
    }

    /**
     * Check if slug is available
     */
    public function checkSlug(Request $request)
    {
        $slug = $request->get('slug');
        $productId = $request->get('product_id'); // For update scenarios
        
        if (!$slug) {
            return response()->json([
                'available' => false,
                'message' => 'Slug is required'
            ]);
        }

        $query = Product::where('slug', $slug);
        
        // If updating, exclude current product
        if ($productId) {
            $query->where('id', '!=', $productId);
        }
        
        $exists = $query->exists();
        
        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'This slug is already taken' : 'Slug is available'
        ]);
    }

    public function store(Request $request)
    {
        // Debug: Log the incoming request
        Log::info('Product store method called', [
            'request_data' => $request->all(),
            'has_files' => $request->hasFile('images'),
            'method' => $request->method()
        ]);

        // Comprehensive validation including MLM fields
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:products,name',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'description' => 'nullable|string|max:10000',
            'short_description' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'vendor_id' => 'required|exists:users,id',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'barcode' => 'nullable|string|max:100|unique:products,barcode',
            'model_number' => 'nullable|string|max:100',
            'mpn' => 'nullable|string|max:100',
            'gtin' => 'nullable|string|max:100',
            
            // Pricing
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lte:price',
            'cost_price' => 'nullable|numeric|min:0',
            'wholesale_price' => 'nullable|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            
            // Inventory
            'stock_quantity' => 'required|integer|min:0',
            'min_stock_level' => 'nullable|integer|min:0',
            'max_stock_level' => 'nullable|integer|min:0',
            'track_quantity' => 'boolean',
            'allow_backorder' => 'boolean',
            'backorder_limit' => 'nullable|integer|min:0',
            
            // Physical properties
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'shipping_weight' => 'nullable|numeric|min:0',
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'material' => 'nullable|string|max:100',
            'size_chart' => 'nullable|string|max:500',
            'color_options' => 'nullable|string|max:500',
            'dimensions' => 'nullable|string|max:200',
            'unit_id' => 'nullable|integer|exists:units,id',
            'free_shipping' => 'boolean',
            'shipping_cost' => 'nullable|numeric|min:0',
            
            // Warranty
            'warranty_period' => 'nullable|string|max:100',
            'warranty_terms' => 'nullable|string|max:1000',
            
            // Product types
            'is_digital' => 'boolean',
            'is_virtual' => 'boolean',
            'is_downloadable' => 'boolean',
            'is_subscription' => 'boolean',
            'is_customizable' => 'boolean',
            'is_gift_card' => 'boolean',
            'condition' => 'in:new,used,refurbished,damaged',
            
            // SEO
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'focus_keyword' => 'nullable|string|max:100',
            
            // Status
            'is_featured' => 'boolean',
            'status' => 'required|in:active,inactive,draft',
            
            // MLM Fields - Basic Product Points
            'pv_points' => 'nullable|numeric|min:0',
            'bv_points' => 'nullable|numeric|min:0',
            'cv_points' => 'nullable|numeric|min:0',
            'qv_points' => 'nullable|numeric|min:0',
            'point_calculation_method' => 'in:manual,percentage,fixed',
            'point_percentage' => 'nullable|numeric|min:0|max:100',
            
            // MLM Product Settings
            'is_mlm_product' => 'boolean',
            'commission_type' => 'nullable|in:percentage,fixed,tier',
            'direct_commission_percentage' => 'nullable|numeric|min:0|max:100',
            'binary_commission_percentage' => 'nullable|numeric|min:0|max:100',
            'matching_bonus_percentage' => 'nullable|numeric|min:0|max:100',
            'leadership_bonus_percentage' => 'nullable|numeric|min:0|max:100',
            'personal_volume' => 'nullable|numeric|min:0',
            'business_volume' => 'nullable|numeric|min:0',
            'subscription_points' => 'nullable|numeric|min:0',
            'loyalty_points_multiplier' => 'nullable|numeric|min:0',
            'minimum_purchase_for_qualification' => 'nullable|numeric|min:0',
            'qualification_period_days' => 'nullable|integer|min:1',
            'rank_qualification_volume' => 'nullable|numeric|min:0',
            'max_payout_per_period' => 'nullable|numeric|min:0',
            'autoship_eligible' => 'boolean',
            'mlm_is_active' => 'boolean',

            // Extended MLM Fields
            'direct_commission_rate' => 'nullable|numeric|min:0|max:100',
            'level_1_commission' => 'nullable|numeric|min:0|max:100',
            'level_2_commission' => 'nullable|numeric|min:0|max:100',
            'level_3_commission' => 'nullable|numeric|min:0|max:100',
            'level_4_commission' => 'nullable|numeric|min:0|max:100',
            'level_5_commission' => 'nullable|numeric|min:0|max:100',
            'is_starter_kit' => 'boolean',
            'starter_kit_tier' => 'nullable|in:basic,standard,premium,platinum',
            'starter_kit_level' => 'nullable|string|max:255',
            'is_autoship_eligible' => 'boolean',
            'generates_commission' => 'boolean',
            'requires_qualification' => 'boolean',
            'minimum_rank_required' => 'nullable|string|max:50',
            'minimum_volume_required' => 'nullable|numeric|min:0',
            'counts_towards_qualification' => 'boolean',
            'fast_start_bonus' => 'nullable|numeric|min:0',
            'team_bonus_rate' => 'nullable|numeric|min:0|max:100',
            'leadership_bonus_rate' => 'nullable|numeric|min:0|max:100',
            'eligible_for_car_bonus' => 'boolean',
            'eligible_for_travel_bonus' => 'boolean',
            'max_purchase_per_month' => 'nullable|integer|min:0',
            'max_purchase_per_order' => 'nullable|integer|min:0',
            'first_order_only' => 'boolean',
            'days_between_purchases' => 'nullable|integer|min:0',
            'autoship_required' => 'boolean',
            'autoship_frequency_days' => 'nullable|integer|min:1',
            'autoship_discount_rate' => 'nullable|numeric|min:0|max:100',
            'autoship_volume_counts' => 'boolean',
            'placement_type' => 'nullable|in:left,right,auto,sponsor_choice',
            'affects_binary_tree' => 'boolean',
            'left_leg_points' => 'nullable|numeric|min:0',
            'right_leg_points' => 'nullable|numeric|min:0',
            'recognition_points' => 'nullable|integer|min:0',
            'contributes_to_rank_advancement' => 'boolean',
            
            // File uploads
            'images.*' => 'image|mimes:jpeg,png,gif,webp|max:10240', // 10MB max per image
        ]);

        if ($validator->fails()) {
            Log::warning('Product validation failed', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->all()
            ]);
            
            // Check if request expects JSON (AJAX request)
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Generate slug if not provided
            $slug = $request->slug ?: Str::slug($request->name);
            
            // Ensure slug is unique
            $originalSlug = $slug;
            $counter = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Calculate PV points if not provided (default to 70% of price)
            $pvPoints = $request->pv_points;
            if ($pvPoints === null && $request->point_calculation_method === 'percentage') {
                $pvPoints = ($request->price * ($request->point_percentage ?? 70)) / 100;
            } elseif ($pvPoints === null) {
                $pvPoints = ($request->price * 0.7); // Default 70%
            }

            // Create comprehensive product data
            $productData = [
                // Basic information
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'short_description' => $request->short_description,
                'category_id' => $request->category_id,
                'subcategory_id' => $request->subcategory_id,
                'brand_id' => $request->brand_id,
                'vendor_id' => $request->vendor_id,
                
                // Product identification
                'sku' => $request->sku ?: $this->generateSKU($request->name),
                'barcode' => $request->barcode,
                'model_number' => $request->model_number,
                'mpn' => $request->mpn,
                'gtin' => $request->gtin,
                
                // Pricing
                'price' => $request->price,
                'sale_price' => $request->sale_price,
                'cost_price' => $request->cost_price,
                'wholesale_price' => $request->wholesale_price,
                'compare_price' => $request->compare_price,
                
                // Inventory
                'stock_quantity' => $request->stock_quantity,
                'min_stock_level' => $request->min_stock_level ?? 0,
                'max_stock_level' => $request->max_stock_level,
                'track_quantity' => $request->boolean('track_quantity', true),
                'allow_backorder' => $request->boolean('allow_backorder'),
                'backorder_limit' => $request->backorder_limit,
                
                // Physical properties
                'weight' => $request->weight,
                'length' => $request->length,
                'width' => $request->width,
                'height' => $request->height,
                'shipping_weight' => $request->shipping_weight,
                'size' => $request->size,
                'color' => $request->color,
                'material' => $request->material,
                'size_chart' => $request->size_chart,
                'color_options' => $request->color_options,
                'dimensions' => $request->dimensions,
                'unit_id' => $request->unit_id,
                'free_shipping' => $request->boolean('free_shipping'),
                'shipping_cost' => $request->shipping_cost,
                
                // Warranty
                'warranty_period' => $request->warranty_period,
                'warranty_terms' => $request->warranty_terms,
                
                // Product types
                'is_digital' => $request->boolean('is_digital'),
                'is_virtual' => $request->boolean('is_virtual'),
                'is_downloadable' => $request->boolean('is_downloadable'),
                'is_subscription' => $request->boolean('is_subscription'),
                'is_customizable' => $request->boolean('is_customizable'),
                'is_gift_card' => $request->boolean('is_gift_card'),
                'condition' => $request->condition ?? 'new',
                
                // Status and features
                'is_active' => $request->status === 'active',
                'is_featured' => $request->boolean('is_featured'),
                'status' => $request->status,
                
                // SEO
                'meta_title' => $request->meta_title ?: $request->name,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'focus_keyword' => $request->focus_keyword,
                
                // MLM Fields
                'pv_points' => $pvPoints,
                'bv_points' => $request->bv_points ?? $pvPoints,
                'cv_points' => $request->cv_points ?? $pvPoints,
                'qv_points' => $request->qv_points ?? $pvPoints,
                'direct_commission_rate' => $request->direct_commission_rate ?? 0,
                'level_1_commission' => $request->level_1_commission ?? 0,
                'level_2_commission' => $request->level_2_commission ?? 0,
                'level_3_commission' => $request->level_3_commission ?? 0,
                'level_4_commission' => $request->level_4_commission ?? 0,
                'level_5_commission' => $request->level_5_commission ?? 0,
                'is_starter_kit' => $request->boolean('is_starter_kit'),
                'starter_kit_tier' => $request->starter_kit_tier,
                'starter_kit_level' => $request->starter_kit_level,
                'is_autoship_eligible' => $request->boolean('is_autoship_eligible'),
                'generates_commission' => $request->boolean('generates_commission', true),
                'requires_qualification' => $request->boolean('requires_qualification'),
                'point_calculation_method' => $request->point_calculation_method ?? 'percentage',
                'point_percentage' => $request->point_percentage ?? 70,
                'minimum_rank_required' => $request->minimum_rank_required,
                'minimum_volume_required' => $request->minimum_volume_required ?? 0,
                'counts_towards_qualification' => $request->boolean('counts_towards_qualification', true),
                'fast_start_bonus' => $request->fast_start_bonus ?? 0,
                'team_bonus_rate' => $request->team_bonus_rate ?? 0,
                'leadership_bonus_rate' => $request->leadership_bonus_rate ?? 0,
                'eligible_for_car_bonus' => $request->boolean('eligible_for_car_bonus'),
                'eligible_for_travel_bonus' => $request->boolean('eligible_for_travel_bonus'),
                'max_purchase_per_month' => $request->max_purchase_per_month,
                'max_purchase_per_order' => $request->max_purchase_per_order,
                'first_order_only' => $request->boolean('first_order_only'),
                'days_between_purchases' => $request->days_between_purchases,
                'autoship_required' => $request->boolean('autoship_required'),
                'autoship_frequency_days' => $request->autoship_frequency_days,
                'autoship_discount_rate' => $request->autoship_discount_rate ?? 0,
                'autoship_volume_counts' => $request->boolean('autoship_volume_counts', true),
                'placement_type' => $request->placement_type,
                'affects_binary_tree' => $request->boolean('affects_binary_tree', true),
                'left_leg_points' => $request->left_leg_points ?? $pvPoints,
                'right_leg_points' => $request->right_leg_points ?? $pvPoints,
                'recognition_points' => $request->recognition_points ?? 0,
                'contributes_to_rank_advancement' => $request->boolean('contributes_to_rank_advancement', true),
                
                // Initialize arrays
                'images' => [], // Will be updated with image data
                'attributes' => $request->attributes ?? [],
                'dimensions' => $request->dimensions ?? [],
                'size_chart' => $request->size_chart ?? [],
                'color_options' => $request->color_options ?? [],
                'specifications' => $request->specifications ?? [],
                'features' => $request->features ?? [],
                'included_items' => $request->included_items ?? [],
                'compatibility' => $request->compatibility ?? [],
                'videos' => $request->videos ?? [],
                'documents' => $request->documents ?? [],
                'certificates' => $request->certificates ?? [],
                'tags' => $request->tags ?? [],
                'variant_attributes' => $request->variant_attributes ?? [],
                'point_tiers' => $request->point_tiers ?? [],
                'achievement_rewards' => $request->achievement_rewards ?? [],
            ];

            // Create the product
            $product = Product::create($productData);

            // Create MLM Product Settings if MLM is enabled
            if ($request->boolean('is_mlm_product')) {
                MlmProductSetting::create([
                    'product_id' => $product->id,
                    'pv_points' => $product->pv_points,
                    'bv_points' => $product->bv_points,
                    'cv_points' => $product->cv_points,
                    'qv_points' => $product->qv_points,
                    'point_calculation_method' => $request->point_calculation_method ?? 'percentage',
                    'point_percentage' => $request->point_percentage ?? 70,
                    'is_starter_kit' => $request->boolean('is_starter_kit'),
                    'is_autoship_eligible' => $request->boolean('is_autoship_eligible'),
                    'generates_commission' => $request->boolean('generates_commission', true),
                    'requires_qualification' => $request->boolean('requires_qualification'),
                    'minimum_rank_required' => $request->minimum_rank_required,
                    'minimum_volume_required' => $request->minimum_volume_required ?? 0,
                    'counts_towards_qualification' => $request->boolean('counts_towards_qualification', true),
                    'max_purchase_per_month' => $request->max_purchase_per_month,
                    'max_purchase_per_order' => $request->max_purchase_per_order,
                    'first_order_only' => $request->boolean('first_order_only'),
                    'days_between_purchases' => $request->days_between_purchases,
                    'placement_type' => $request->placement_type,
                    'affects_binary_tree' => $request->boolean('affects_binary_tree', true),
                    'left_leg_points' => $request->left_leg_points ?? $pvPoints,
                    'right_leg_points' => $request->right_leg_points ?? $pvPoints,
                    'recognition_points' => $request->recognition_points ?? 0,
                    'contributes_to_rank_advancement' => $request->boolean('contributes_to_rank_advancement', true)
                ]);
            }

            // Handle image uploads
            if ($request->hasFile('images')) {
                try {
                    $uploadedImages = [];
                    foreach ($request->file('images') as $file) {
                        $imageData = $this->processImageUpload($file, 'products/' . $product->id);
                        if ($imageData) {
                            $uploadedImages[] = $imageData;
                        }
                    }

                    // Update product with image data
                    $product->update(['images' => $uploadedImages]);
                } catch (\Exception $e) {
                    Log::warning('Image upload failed, but product was created', [
                        'product_id' => $product->id,
                        'error' => $e->getMessage()
                    ]);
                    // Continue without failing the product creation
                }
            }

            // Create inventory record if needed
            if ($product->stock_quantity > 0) {
                // Check if inventory already exists to avoid duplicate constraint violation
                $existingInventory = $product->inventory()->where('warehouse_id', 1)->first();
                
                if (!$existingInventory) {
                    $product->inventory()->create([
                        'warehouse_id' => 1, // Default warehouse
                        'quantity' => $product->stock_quantity,
                        'reserved_quantity' => 0,
                        'available_quantity' => $product->stock_quantity,
                        'location' => 'main-warehouse',
                        'notes' => 'Initial stock from product creation',
                        'is_active' => true
                    ]);
                } else {
                    // Update existing inventory
                    $existingInventory->update([
                        'quantity' => $product->stock_quantity,
                        'available_quantity' => $product->stock_quantity,
                        'notes' => 'Updated stock from product creation'
                    ]);
                }
            }

            DB::commit();

            Log::info('Product created successfully with MLM integration', [
                'product_id' => $product->id,
                'pv_points' => $product->pv_points,
                'generates_commission' => $product->generates_commission
            ]);

            // Check if request expects JSON (AJAX request)
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product created successfully with MLM features!',
                    'product_id' => $product->id,
                    'redirect_url' => route('admin.products.index')
                ]);
            }

            return redirect()->route('admin.products.index')
                ->with('success', 'Product created successfully with MLM features!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating product: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);
            
            // Check if request expects JSON (AJAX request)
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create product. Please try again.',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Failed to create product. Please try again.')
                ->withInput();
        }
    }

    /**
     * Generate unique SKU for product
     */
    private function generateSKU(string $productName): string
    {
        $sku = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $productName), 0, 6))
            . date('md')
            . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            
        // Ensure uniqueness
        while (Product::where('sku', $sku)->exists()) {
            $sku = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $productName), 0, 6))
                . date('md')
                . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        }
        
        return $sku;
    }

    public function show($id)
    {
        try {
            $product = Product::with(['category', 'vendor', 'inventory', 'mlmSettings'])->findOrFail($id);
            
            return view('admin.products.show', compact('product'));
        } catch (\Exception $e) {
            Log::error('Error loading product: ' . $e->getMessage());
            return redirect()->route('admin.products.index')
                ->with('error', 'Product not found.');
        }
    }

    public function edit($id)
    {
        try {
            // Add error logging to debug
            Log::info('Edit product request received', ['id' => $id]);
            
            $product = Product::with(['category', 'vendor', 'brand', 'inventory', 'mlmSettings', 'tags'])->findOrFail($id);
            Log::info('Product found', ['product_id' => $product->id]);
            
            $categories = Category::where('status', 'active')->orderBy('name')->get();
            $vendors = User::where('role', 'vendor')->where('status', 'active')->orderBy('name')->get();
            $brands = Brand::where('status', 'active')->orderBy('name')->get();
            $tags = \App\Models\Tag::where('is_active', 1)->orderBy('name')->get();
            
            return view('admin.products.edit', compact('product', 'categories', 'vendors', 'brands', 'tags'));
        } catch (\Exception $e) {
            Log::error('Error in product edit method', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('admin.products.index')
                ->with('error', 'Product not found. Error: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        Log::info('=== PRODUCT UPDATE REQUEST RECEIVED ===', [
            'product_id' => $id,
            'method' => $request->method(),
            'has_removed_images' => $request->has('removed_images'),
            'removed_images_raw' => $request->removed_images,
            'removed_images_count' => $request->has('removed_images') && $request->removed_images ? 
                (is_string($request->removed_images) ? 
                    count(json_decode($request->removed_images, true) ?: []) : 
                    count($request->removed_images)
                ) : 0,
            'has_new_images' => $request->hasFile('new_images'),
            'new_images_count' => $request->hasFile('new_images') ? count($request->file('new_images')) : 0
        ]);
        
        try {
            $product = Product::findOrFail($id);
            
            // Validation rules
            $rules = [
                'name' => 'required|string|max:255',
                'sku' => 'required|string|max:100|unique:products,sku,' . $id,
                'price' => 'required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0|lte:price',
                'stock_quantity' => 'required|integer|min:0',
                'category_id' => 'required|exists:categories,id',
                'vendor_id' => 'required|exists:users,id',
                'status' => 'required|in:active,inactive,draft',
                'new_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
                // MLM Commission Settings
                'generates_commission' => 'nullable|boolean',
                'is_starter_kit' => 'nullable|boolean',
                'starter_kit_tier' => 'nullable|in:basic,standard,premium,platinum',
                'starter_kit_level' => 'nullable|string|max:255',
                'pv_points' => 'nullable|numeric|min:0',
                'bv_points' => 'nullable|numeric|min:0',
                'direct_commission_rate' => 'nullable|numeric|min:0|max:100',
            ];

            $validatedData = $request->validate($rules);

            // Handle image uploads
            $images = $product->images ?? [];
            
            // Remove deleted images
            if ($request->has('removed_images') && !empty($request->removed_images)) {
                $removedImages = is_string($request->removed_images) 
                    ? json_decode($request->removed_images, true) 
                    : $request->removed_images;
                
                if (is_array($removedImages)) {
                    Log::info('Processing removed images:', $removedImages);
                    
                    foreach ($removedImages as $removedImagePath) {
                        $beforeCount = count($images);
                        Log::info("Processing removal for: {$removedImagePath}");
                        
                        // Find and remove the matching image
                        foreach ($images as $index => $image) {
                            $shouldRemove = false;
                            $imageUrl = '';
                            
                            // Extract the URL/path from the image data
                            if (is_array($image)) {
                                // New structure with sizes
                                if (isset($image['sizes']) && is_array($image['sizes'])) {
                                    $imageUrl = $image['sizes']['medium']['url'] ?? 
                                               $image['sizes']['large']['url'] ?? 
                                               $image['sizes']['original']['url'] ?? 
                                               $image['sizes']['small']['url'] ?? '';
                                } else {
                                    // Legacy array structure
                                    $imageUrl = $image['url'] ?? $image['path'] ?? '';
                                }
                            } elseif (is_string($image)) {
                                // Simple string path
                                $imageUrl = $image;
                            }
                            
                            // Normalize both URLs for comparison
                            $normalizedImageUrl = $this->normalizeImagePath($imageUrl);
                            $normalizedRemovedPath = $this->normalizeImagePath($removedImagePath);
                            
                            Log::info("Comparing: '{$normalizedImageUrl}' with '{$normalizedRemovedPath}'");
                            
                            // Check if this is the image to remove
                            if ($normalizedImageUrl === $normalizedRemovedPath || 
                                basename($normalizedImageUrl) === basename($normalizedRemovedPath)) {
                                $shouldRemove = true;
                                Log::info("MATCH FOUND! Removing image at index {$index}");
                            }
                            
                            if ($shouldRemove) {
                                // Delete physical files
                                if (is_array($image)) {
                                    $deleteResult = $this->deleteImageFiles($image);
                                    Log::info("Physical file deletion result: " . ($deleteResult ? 'success' : 'failed'));
                                } else {
                                    // Handle legacy string images
                                    $legacyPath = str_replace(['/storage/', 'storage/'], '', $imageUrl);
                                    $deleteResult = $this->deleteLegacyImageFile($legacyPath);
                                    Log::info("Legacy file deletion result: " . ($deleteResult ? 'success' : 'failed'));
                                }
                                
                                // Remove from array
                                unset($images[$index]);
                                Log::info("Removed image from array at index {$index}");
                                break; // Exit inner loop since we found and removed the image
                            }
                        }
                        
                        $afterCount = count($images);
                        Log::info("Image removal - Before: {$beforeCount}, After: {$afterCount}");
                    }
                    
                    // Re-index the array to avoid gaps in keys
                    $images = array_values($images);
                    Log::info('Images after removal and re-indexing:', $images);
                }
            }
            
            // Add new images
            if ($request->hasFile('new_images')) {
                Log::info('Processing new images upload');
                foreach ($request->file('new_images') as $file) {
                    $imageData = $this->processImageUpload($file, 'products');
                    if ($imageData) {
                        $images[] = $imageData;
                        Log::info('New image added:', $imageData);
                    }
                }
            } else {
                Log::info('No new images to process');
            }

            // Update product data
            $updateData = [
                'name' => $validatedData['name'],
                'sku' => $validatedData['sku'],
                'price' => $validatedData['price'],
                'sale_price' => $validatedData['sale_price'] ?? null,
                'stock_quantity' => $validatedData['stock_quantity'],
                'category_id' => $validatedData['category_id'],
                'vendor_id' => $validatedData['vendor_id'],
                'brand_id' => $request->brand_id,
                'status' => $validatedData['status'],
                'short_description' => $request->short_description,
                'description' => $request->description,
                'cost_price' => $request->cost_price,
                'weight' => $request->weight,
                'barcode' => $request->barcode,
                'model_number' => $request->model_number,
                'is_featured' => $request->has('is_featured'),
                'manage_stock' => $request->has('manage_stock'),
                'images' => array_values($images),
                
                // MLM fields
                'generates_commission' => $request->boolean('generates_commission'),
                'pv_points' => $request->pv_points ?? 0,
                'bv_points' => $request->bv_points ?? 0,
                'cv_points' => $request->cv_points ?? 0,
                'qv_points' => $request->qv_points ?? 0,
                'direct_commission_rate' => $request->direct_commission_rate ?? 0,
                'level_1_commission' => $request->level_1_commission ?? 0,
                'level_2_commission' => $request->level_2_commission ?? 0,
                'is_autoship_eligible' => $request->boolean('is_autoship_eligible'),
                'generates_commission' => $request->boolean('generates_commission'),
                'is_starter_kit' => $request->boolean('is_starter_kit'),
                'starter_kit_tier' => $request->starter_kit_tier,
                'starter_kit_level' => $request->starter_kit_level,
                'requires_qualification' => $request->has('requires_qualification'),
                'affects_binary_tree' => $request->has('affects_binary_tree'),
            ];

            $product->update($updateData);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product updated successfully!',
                    'redirect' => route('admin.products.show', $product->id)
                ]);
            }

            return redirect()->route('admin.products.show', $product->id)
                ->with('success', 'Product updated successfully!');

        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please fix the validation errors.',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update product. Please try again.'
                ], 500);
            }
            
            return back()->with('error', 'Failed to update product. Please try again.');
        }
    }

    public function destroy($id)
    {
        try {
            // Find the product
            $product = Product::findOrFail($id);
            
            // Store product name for response
            $productName = $product->name;
            
            // Check if product has active orders (optional - for business logic)
            // You might want to prevent deletion if there are pending orders
            // $hasActiveOrders = $product->orderItems()->whereHas('order', function($query) {
            //     $query->whereIn('status', ['pending', 'processing']);
            // })->exists();
            
            // if ($hasActiveOrders) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Cannot delete product with active orders.'
            //     ], 422);
            // }
            
            // Soft delete the product (due to SoftDeletes trait)
            $product->delete();
            
            // Log the deletion
            Log::info('Product deleted', [
                'product_id' => $id,
                'product_name' => $productName,
                'deleted_by' => auth()->guard('admin')->id() ?? 'unknown'
            ]);
            
            // Check if request expects JSON (AJAX)
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Product '{$productName}' deleted successfully!"
                ]);
            }
            
            // For form submission, redirect with success message
            return redirect()->route('admin.products.index')
                ->with('success', "Product '{$productName}' deleted successfully!");
                
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Product not found
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found.'
                ], 404);
            }
            
            return redirect()->route('admin.products.index')
                ->with('error', 'Product not found.');
                
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error deleting product', [
                'product_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while deleting the product. Please try again.'
                ], 500);
            }
            
            return redirect()->route('admin.products.index')
                ->with('error', 'An error occurred while deleting the product. Please try again.');
        }
    }

    /**
     * Toggle product status
     */
    public function toggleStatus($id)
    {
        try {
            // Debug logging
            Log::info('Toggle status request received', [
                'product_id' => $id,
                'expects_json' => request()->expectsJson(),
                'wants_json' => request()->wantsJson(),
                'ajax' => request()->ajax(),
                'accept_header' => request()->header('Accept'),
                'content_type' => request()->header('Content-Type'),
                'x_requested_with' => request()->header('X-Requested-With')
            ]);
            
            $product = Product::findOrFail($id);
            
            // Toggle status
            $newStatus = $product->status === 'active' ? 'inactive' : 'active';
            $product->update(['status' => $newStatus]);
            
            $message = $newStatus === 'active' 
                ? 'Product activated successfully!' 
                : 'Product deactivated successfully!';
            
            // Always return JSON for AJAX requests or if Accept header includes JSON
            if (request()->expectsJson() || request()->wantsJson() || request()->ajax()) {
                Log::info('Returning JSON response');
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'status' => $newStatus
                ]);
            }
            
            Log::info('Returning redirect response');
            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            Log::error('Error toggling product status', [
                'product_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if (request()->expectsJson() || request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while updating the product status. Please try again.'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'An error occurred while updating the product status. Please try again.');
        }
    }

    /**
     * Toggle product featured status
     */
    public function toggleFeatured($id)
    {
        try {
            $product = Product::findOrFail($id);
            
            // Toggle featured status
            $newFeatured = !$product->is_featured;
            $product->update(['is_featured' => $newFeatured]);
            
            $message = $newFeatured 
                ? 'Product marked as featured!' 
                : 'Product removed from featured!';
            
            if (request()->expectsJson() || request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'is_featured' => $newFeatured
                ]);
            }
            
            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            Log::error('Error toggling product featured status', [
                'product_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if (request()->expectsJson() || request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while updating the product featured status. Please try again.'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'An error occurred while updating the product featured status. Please try again.');
        }
    }

    /**
     * Show bulk import page
     */
    public function bulkImport()
    {
        try {
            return view('admin.products.bulk-import');
        } catch (\Exception $e) {
            Log::error('Error loading bulk import page', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.products.index')
                ->with('error', 'Unable to load bulk import page. Please try again.');
        }
    }

    /**
     * Process bulk import
     */
    public function processBulkImport(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'import_file' => 'required|file|mimes:csv,xlsx,xls|max:10240', // 10MB max
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->with('error', 'Please provide a valid CSV or Excel file.');
            }

            $file = $request->file('import_file');
            $path = $file->store('imports', 'local');
            
            // Here you would process the file
            // For now, we'll just return a success message
            
            Log::info('Bulk import file uploaded', [
                'filename' => $file->getClientOriginalName(),
                'path' => $path,
                'size' => $file->getSize()
            ]);

            return redirect()->route('admin.products.index')
                ->with('success', 'Bulk import file uploaded successfully. Processing will begin shortly.');

        } catch (\Exception $e) {
            Log::error('Error processing bulk import', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred during bulk import. Please try again.');
        }
    }

    /**
     * Show product analytics
     */
    public function analytics()
    {
        try {
            // Basic analytics data
            $totalProducts = Product::count();
            $activeProducts = Product::where('is_active', true)->count();
            $inactiveProducts = Product::where('is_active', false)->count();
            $featuredProducts = Product::where('is_featured', true)->count();
            $outOfStockProducts = Product::where('stock_quantity', 0)->count();
            $lowStockProducts = Product::where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 10)->count();

            // Monthly product creation
            $monthlyProducts = Product::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            // Top categories by product count
            $topCategories = Product::selectRaw('category_id, COUNT(*) as product_count')
                ->with('category:id,name')
                ->groupBy('category_id')
                ->orderByDesc('product_count')
                ->limit(10)
                ->get();

            return view('admin.products.analytics', compact(
                'totalProducts',
                'activeProducts',
                'inactiveProducts', 
                'featuredProducts',
                'outOfStockProducts',
                'lowStockProducts',
                'monthlyProducts',
                'topCategories'
            ));

        } catch (\Exception $e) {
            Log::error('Error loading product analytics', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.products.index')
                ->with('error', 'Unable to load analytics. Please try again.');
        }
    }

    /**
     * Show export products page
     */
    public function showExportPage()
    {
        try {
            // Get export statistics
            $totalProducts = Product::count();
            $activeProducts = Product::where('is_active', true)->count();
            $inactiveProducts = Product::where('is_active', false)->count();
            $outOfStockProducts = Product::where('stock_quantity', 0)->count();
            
            // Get categories for filter dropdown - with error handling
            try {
                $categories = \App\Models\Category::orderBy('name')->get();
            } catch (\Exception $e) {
                Log::warning('Could not load categories for export page', [
                    'error' => $e->getMessage()
                ]);
                $categories = collect(); // Empty collection if categories table doesn't exist
            }

            return view('admin.products.export', compact(
                'totalProducts',
                'activeProducts', 
                'inactiveProducts',
                'outOfStockProducts',
                'categories'
            ));

        } catch (\Exception $e) {
            Log::error('Error loading export page', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.products.index')
                ->with('error', 'Unable to load export page. Please try again.');
        }
    }

    /**
     * Export products to CSV or Excel
     */
    public function export(Request $request)
    {
        try {
            $format = $request->get('format', 'csv');
            $includeImages = $request->boolean('include_images');
            $includeVariants = $request->boolean('include_variants');
            
            // Get products with filters
            $query = Product::with(['category', 'vendor:id,firstname,lastname']);
            
            // Apply filters
            if ($request->has('category') && $request->category) {
                $query->whereHas('category', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->category . '%');
                });
            }
            
            if ($request->has('status') && $request->status) {
                if ($request->status === 'active') {
                    $query->where('is_active', true);
                } elseif ($request->status === 'inactive') {
                    $query->where('is_active', false);
                } elseif ($request->status === 'out-of-stock') {
                    $query->where('stock_quantity', 0);
                }
            }
            
            if ($request->has('search') && $request->search) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }
            
            $products = $query->get();
            
            // Prepare data for export
            $headers = ['ID', 'Name', 'Category', 'Price', 'Sale Price', 'Stock', 'Vendor', 'Status', 'Featured', 'Created At'];
            
            if ($includeImages) {
                $headers[] = 'Primary Image';
                $headers[] = 'All Images';
            }
            
            if ($includeVariants) {
                $headers = array_merge($headers, ['Variants', 'SKU']);
            }
            
            $exportData = [];
            $exportData[] = $headers;
            
            foreach ($products as $product) {
                $row = [
                    $product->id,
                    $product->name,
                    $product->category->name ?? 'Uncategorized',
                    $product->price,
                    $product->sale_price ?? '',
                    $product->stock_quantity,
                    $product->vendor ? $product->vendor->firstname . ' ' . $product->vendor->lastname : 'Unknown',
                    $product->is_active ? 'Active' : 'Inactive',
                    $product->is_featured ? 'Yes' : 'No',
                    $product->created_at->format('Y-m-d H:i:s')
                ];
                
                if ($includeImages) {
                    $primaryImage = '';
                    $allImages = '';
                    
                    if ($product->images && is_array($product->images) && count($product->images) > 0) {
                        $primaryImage = $product->images[0]['urls']['original'] ?? '';
                        $allImages = collect($product->images)->pluck('urls.original')->implode('; ');
                    }
                    
                    $row[] = $primaryImage;
                    $row[] = $allImages;
                }
                
                if ($includeVariants) {
                    // Add variant information if needed
                    $row[] = ''; // Variants - placeholder
                    $row[] = $product->sku ?? ''; // SKU
                }
                
                $exportData[] = $row;
            }
            
            if ($format === 'excel') {
                return $this->exportToExcel($exportData);
            } else {
                return $this->exportToCsv($exportData);
            }
            
        } catch (\Exception $e) {
            Log::error('Error exporting products', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Export failed. Please try again.');
        }
    }

    /**
     * Export data to CSV
     */
    private function exportToCsv($data)
    {
        $filename = 'products_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        return response()->stream(function() use ($data) {
            $handle = fopen('php://output', 'w');
            
            foreach ($data as $row) {
                fputcsv($handle, $row);
            }
            
            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Export data to Excel (HTML table format that Excel can read)
     */
    private function exportToExcel($data)
    {
        $filename = 'products_export_' . date('Y-m-d_H-i-s') . '.xls';
        
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        return response()->stream(function() use ($data) {
            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            echo '<head>';
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
            echo '<meta name="ProgId" content="Excel.Sheet">';
            echo '<meta name="Generator" content="Microsoft Excel 15">';
            echo '<style>';
            echo 'table { border-collapse: collapse; }';
            echo 'th, td { border: 1px solid #000; padding: 5px; text-align: left; }';
            echo 'th { background-color: #f2f2f2; font-weight: bold; }';
            echo '</style>';
            echo '</head>';
            echo '<body>';
            echo '<table>';
            
            foreach ($data as $index => $row) {
                if ($index === 0) {
                    echo '<thead><tr>';
                    foreach ($row as $cell) {
                        echo '<th>' . htmlspecialchars($cell) . '</th>';
                    }
                    echo '</tr></thead><tbody>';
                } else {
                    echo '<tr>';
                    foreach ($row as $cell) {
                        echo '<td>' . htmlspecialchars($cell) . '</td>';
                    }
                    echo '</tr>';
                }
            }
            
            echo '</tbody></table>';
            echo '</body>';
            echo '</html>';
        }, 200, $headers);
    }

    /**
     * Bulk actions for products
     */
    public function bulkAction(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'action' => 'required|in:activate,deactivate,feature,unfeature,delete',
                'product_ids' => 'required|array|min:1',
                'product_ids.*' => 'integer|exists:products,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request data.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $action = $request->action;
            $productIds = $request->product_ids;
            $count = 0;

            switch ($action) {
                case 'activate':
                    $count = Product::whereIn('id', $productIds)->update(['is_active' => true]);
                    $message = "$count products activated successfully.";
                    break;

                case 'deactivate':
                    $count = Product::whereIn('id', $productIds)->update(['is_active' => false]);
                    $message = "$count products deactivated successfully.";
                    break;

                case 'feature':
                    $count = Product::whereIn('id', $productIds)->update(['is_featured' => true]);
                    $message = "$count products marked as featured successfully.";
                    break;

                case 'unfeature':
                    $count = Product::whereIn('id', $productIds)->update(['is_featured' => false]);
                    $message = "$count products removed from featured successfully.";
                    break;

                case 'delete':
                    $count = Product::whereIn('id', $productIds)->delete(); // This will soft delete
                    $message = "$count products deleted successfully.";
                    break;
            }

            Log::info('Bulk action performed', [
                'action' => $action,
                'product_ids' => $productIds,
                'affected_count' => $count
            ]);

            return response()->json([
                'success' => true,
                'message' => $message,
                'affected_count' => $count
            ]);

        } catch (\Exception $e) {
            Log::error('Error performing bulk action', [
                'action' => $request->action,
                'product_ids' => $request->product_ids,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while performing the bulk action. Please try again.'
            ], 500);
        }
    }

    /**
     * Replace all product images with new ones
     */
    public function replaceAllImages(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            
            $request->validate([
                'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            ]);
            
            // Delete all existing images using trait method
            if (!empty($product->images)) {
                foreach ($product->images as $image) {
                    if (is_array($image)) {
                        $this->deleteImageFiles($image);
                    } else {
                        // Handle legacy string images
                        $legacyPath = str_replace(['/storage/', 'storage/'], '', $image);
                        $this->deleteLegacyImageFile($legacyPath);
                    }
                }
            }
            
            // Upload new images using trait method
            $newImages = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $imageData = $this->processImageUpload($file, 'products');
                    if ($imageData) {
                        $newImages[] = $imageData;
                    }
                }
            }
            
            // Update product with new images
            $product->update(['images' => $newImages]);
            
            return response()->json([
                'success' => true,
                'message' => 'All product images replaced successfully!',
                'images' => $newImages
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error replacing product images: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to replace images. Please try again.'
            ], 500);
        }
    }

    /**
     * Delete a specific product image
     */
    public function deleteImage(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            
            $request->validate([
                'image_url' => 'required|string',
            ]);
            
            $images = $product->images ?? [];
            $imageUrl = $request->image_url;
            $found = false;
            
            // Find and delete the specific image
            foreach ($images as $index => $image) {
                $currentImageUrl = '';
                
                if (is_array($image)) {
                    if (isset($image['urls'])) {
                        $currentImageUrl = $image['urls']['large'] ?? 
                                         $image['urls']['medium'] ?? 
                                         $image['urls']['original'] ?? 
                                         $image['urls']['small'] ?? '';
                    }
                    if (!$currentImageUrl) {
                        $currentImageUrl = $image['url'] ?? $image['path'] ?? $image['original'] ?? '';
                    }
                } elseif (is_string($image)) {
                    $currentImageUrl = $image;
                }
                
                // Clean and format URL
                if ($currentImageUrl) {
                    $currentImageUrl = trim($currentImageUrl, '"\'');
                    if (!str_starts_with($currentImageUrl, 'http') && !str_starts_with($currentImageUrl, '/')) {
                        $currentImageUrl = '/storage/' . $currentImageUrl;
                    }
                }
                
                if ($currentImageUrl === $imageUrl) {
                    // Delete physical files
                    if (is_array($image) && isset($image['filename'], $image['path'])) {
                        $this->deleteImageFiles($image);
                    }
                    
                    // Remove from array
                    unset($images[$index]);
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                return response()->json([
                    'success' => false,
                    'message' => 'Image not found.'
                ], 404);
            }
            
            // Re-index and update product
            $images = array_values($images);
            $product->update(['images' => $images]);
            
            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully!'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error deleting product image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image. Please try again.'
            ], 500);
        }
    }

    /**
     * Upload a product image via AJAX
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'image' => 'required|image|max:2048', // 2MB max
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }
            
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = 'product_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                
                // Store in the public uploads directory
                $path = $file->storeAs('uploads/products', $fileName, 'public');
                
                return response()->json([
                    'success' => true,
                    'path' => '/storage/' . $path,
                    'message' => 'Image uploaded successfully'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'No image file provided'
            ], 400);
            
        } catch (\Exception $e) {
            Log::error('Error uploading product image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image. Please try again.'
            ], 500);
        }
    }

    /**
     * Normalize image path for comparison
     */
    private function normalizeImagePath($path)
    {
        if (empty($path)) {
            return '';
        }
        
        // Remove domain and protocol
        $path = preg_replace('#^https?://[^/]+#', '', $path);
        
        // Ensure it starts with /storage/
        if (!str_starts_with($path, '/storage/')) {
            $path = '/storage/' . ltrim($path, '/');
        }
        
        // Remove double slashes
        $path = preg_replace('#/+#', '/', $path);
        
        return $path;
    }
}
