<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Brand;
use App\Models\Attribute;
use App\Models\Tag;
use App\Models\MlmProductSetting;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    use HandlesImageUploads;
    public function __construct()
    {
        // Vendor role check will be handled in each method
    }

    /**
     * Display a listing of vendor's products.
     */
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'vendor') {
            abort(403, 'Access denied. Vendor role required.');
        }

        $query = Product::where('vendor_id', Auth::id());

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->with(['category', 'brand'])
                         ->orderBy('created_at', 'desc')
                         ->paginate(10);

        $categories = Category::where('status', 'active')->get();

        return view('vendor.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        if (Auth::user()->role !== 'vendor') {
            abort(403, 'Access denied. Vendor role required.');
        }

        try {
            // Get categories from database with error handling
            $categories = collect();
            try {
                $categories = Category::where('status', 'active')
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get();
                    
                Log::info('Categories loaded for vendor dropdown: ' . $categories->count());
                
            } catch (\Exception $e) {
                Log::warning('Failed to load categories: ' . $e->getMessage());
                $categories = collect();
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
                    ->with('values')
                    ->orderBy('name')
                    ->get();
            } catch (\Exception $e) {
                Log::warning('Failed to load attributes: ' . $e->getMessage());
                $attributes = collect();
            }

            // Get tags with error handling
            $tags = collect();
            try {
                $tags = Tag::where('is_active', 1)->orderBy('name')->get();
            } catch (\Exception $e) {
                Log::warning('Failed to load tags: ' . $e->getMessage());
                $tags = collect();
            }

            // If no categories exist, provide a warning
            if ($categories->isEmpty()) {
                Log::warning('No active categories found for vendor product creation');
            }

            // Debug: Log the data being passed to view
            Log::info('Vendor product create form data:', [
                'categories_count' => $categories->count(),
                'brands_count' => $brands->count(),
                'attributes_count' => $attributes->count(),
                'tags_count' => $tags->count()
            ]);
            
            return view('vendor.products.create', compact('categories', 'brands', 'attributes', 'tags'));
            
        } catch (\Exception $e) {
            Log::error('Error loading vendor product create form: ' . $e->getMessage());
            
            // Return with empty collections as fallback
            $categories = collect();
            $brands = collect();
            $attributes = collect();
            $tags = collect();
            
            return view('vendor.products.create', compact('categories', 'brands', 'attributes', 'tags'))
                ->with('warning', 'Some data could not be loaded. Please check your database configuration.');
        }
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'vendor') {
            abort(403, 'Access denied. Vendor role required.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'sku' => 'required|string|max:100|unique:products',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'min_stock_level' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive,draft',
            'track_quantity' => 'boolean',
            'allow_backorder' => 'boolean',
            'is_digital' => 'boolean',
            'is_featured' => 'boolean',
            'is_downloadable' => 'boolean',
            // MLM Fields
            'generates_commission' => 'boolean',
            'is_starter_kit' => 'boolean',
            'starter_kit_tier' => 'nullable|string|in:basic,standard,premium,platinum',
            'pv_points' => 'nullable|numeric|min:0',
            'bv_points' => 'nullable|numeric|min:0',
            'direct_commission_rate' => 'nullable|numeric|min:0|max:100',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = new Product();
        $product->vendor_id = Auth::id();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->sku = $request->sku;
        $product->price = $request->price;
        $product->compare_price = $request->compare_price;
        $product->cost_price = $request->cost_price;
        $product->stock_quantity = $request->stock_quantity;
        $product->min_stock_level = $request->min_stock_level ?? 0;
        $product->weight = $request->weight;
        $product->dimensions = $request->dimensions;
        $product->description = $request->description;
        $product->short_description = $request->short_description;
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;
        $product->status = $request->status;
        $product->track_quantity = $request->has('track_quantity');
        $product->allow_backorder = $request->has('allow_backorder');
        $product->is_digital = $request->has('is_digital');
        $product->is_featured = $request->has('is_featured');
        $product->is_downloadable = $request->has('is_downloadable');
        
        // MLM Fields
        $product->generates_commission = $request->has('generates_commission');
        $product->is_starter_kit = $request->has('is_starter_kit');
        $product->starter_kit_tier = $request->starter_kit_tier;
        $product->pv_points = $request->pv_points;
        $product->bv_points = $request->bv_points;
        $product->direct_commission_rate = $request->direct_commission_rate;

        // Handle image uploads
        if ($request->hasFile('images')) {
            $uploadedImages = [];
            try {
                foreach ($request->file('images') as $image) {
                    $imageData = $this->uploadProductImage($image, 'products');
                    $uploadedImages[] = $imageData;
                }
                $product->images = json_encode($uploadedImages);
            } catch (\Exception $e) {
                return back()->with('error', 'Failed to upload images: ' . $e->getMessage())->withInput();
            }
        }

        $product->save();

        // Sync tags if provided
        if ($request->has('tags') && is_array($request->tags)) {
            $product->tags()->sync($request->tags);
        }

        return redirect()->route('vendor.products.index')
                        ->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        // Ensure vendor can only view their own products
        if ($product->vendor_id !== Auth::id()) {
            abort(403, 'Access denied.');
        }

        return view('vendor.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        // Ensure vendor can only edit their own products
        if ($product->vendor_id !== Auth::id()) {
            abort(403, 'Access denied.');
        }

        $categories = Category::where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();
        $attributes = Attribute::with('values')->get();
        $tags = Tag::where('is_active', 1)->orderBy('name')->get();

        return view('vendor.products.edit', compact('product', 'categories', 'brands', 'attributes', 'tags'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Ensure vendor can only update their own products
        if ($product->vendor_id !== Auth::id()) {
            abort(403, 'Access denied.');
        }

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'brand_id' => 'nullable|exists:brands,id',
                'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
                'price' => 'required|numeric|min:0',
                'compare_price' => 'nullable|numeric|min:0',
                'cost_price' => 'nullable|numeric|min:0',
                'stock_quantity' => 'required|integer|min:0',
                'min_stock_level' => 'nullable|integer|min:0',
                'weight' => 'nullable|numeric|min:0',
                'dimensions' => 'nullable|string',
                'description' => 'nullable|string',
                'short_description' => 'nullable|string|max:500',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
                'status' => 'required|in:active,inactive,draft',
                'track_quantity' => 'boolean',
                'allow_backorder' => 'boolean',
                'is_digital' => 'boolean',
                'is_featured' => 'boolean',
                'is_downloadable' => 'boolean',
                // MLM Fields
                'generates_commission' => 'boolean',
                'is_starter_kit' => 'boolean',
                'starter_kit_tier' => 'nullable|string|in:basic,standard,premium,platinum',
                'pv_points' => 'nullable|numeric|min:0',
                'bv_points' => 'nullable|numeric|min:0',
                'direct_commission_rate' => 'nullable|numeric|min:0|max:100',
                'tags' => 'nullable|array',
                'tags.*' => 'exists:tags,id',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Update basic product fields
            $product->name = $request->name;
            $product->slug = Str::slug($request->name);
            $product->category_id = $request->category_id;
            $product->brand_id = $request->brand_id;
            $product->sku = $request->sku;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->cost_price = $request->cost_price;
            $product->stock_quantity = $request->stock_quantity;
            $product->min_stock_level = $request->min_stock_level ?? 0;
            $product->weight = $request->weight;
            $product->dimensions = $request->dimensions;
            $product->description = $request->description;
            $product->short_description = $request->short_description;
            $product->meta_title = $request->meta_title;
            $product->meta_description = $request->meta_description;
            $product->status = $request->status;
            $product->track_quantity = $request->has('track_quantity');
            $product->allow_backorder = $request->has('allow_backorder');
            $product->is_digital = $request->has('is_digital');
            $product->is_featured = $request->has('is_featured');
            $product->is_downloadable = $request->has('is_downloadable');
            
            // MLM Fields
            $product->generates_commission = $request->has('generates_commission');
            $product->is_starter_kit = $request->has('is_starter_kit');
            $product->starter_kit_tier = $request->starter_kit_tier;
            $product->pv_points = $request->pv_points;
            $product->bv_points = $request->bv_points;
            $product->direct_commission_rate = $request->direct_commission_rate;

            // Handle image uploads
            if ($request->hasFile('images')) {
                // Delete old images
                if ($product->images) {
                    try {
                        $oldImages = is_array($product->images) ? $product->images : json_decode($product->images, true);
                        if (is_array($oldImages)) {
                            foreach ($oldImages as $oldImage) {
                                if (is_array($oldImage) && isset($oldImage['sizes'])) {
                                    // New format - delete all sizes
                                    $this->deleteImageFiles($oldImage);
                                } else {
                                    // Legacy format - simple path
                                    $this->deleteLegacyImageFile($oldImage);
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        Log::warning('Failed to delete old images: ' . $e->getMessage());
                    }
                }

                $uploadedImages = [];
                try {
                    foreach ($request->file('images') as $image) {
                        $imageData = $this->uploadProductImage($image, 'products');
                        $uploadedImages[] = $imageData;
                    }
                    $product->images = json_encode($uploadedImages);
                } catch (\Exception $e) {
                    Log::error('Failed to upload images during product update: ' . $e->getMessage());
                    return back()->with('error', 'Failed to upload images: ' . $e->getMessage())->withInput();
                }
            }

            $product->save();

            // Sync tags if provided
            if ($request->has('tags') && is_array($request->tags)) {
                try {
                    $product->tags()->sync($request->tags);
                } catch (\Exception $e) {
                    Log::warning('Failed to sync tags: ' . $e->getMessage());
                    // Don't fail the entire update for tag sync issues
                }
            }

            return redirect()->route('vendor.products.index')
                            ->with('success', 'Product updated successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Product update validation failed: ', $e->errors());
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Failed to update product: ' . $e->getMessage(), [
                'product_id' => $product->id,
                'vendor_id' => Auth::id(),
                'request_data' => $request->except(['images', '_token'])
            ]);
            return back()->with('error', 'Failed to update product. Please try again. Error: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Ensure vendor can only delete their own products
        if ($product->vendor_id !== Auth::id()) {
            abort(403, 'Access denied.');
        }

        // Delete product images
        if ($product->images) {
            $images = json_decode($product->images, true);
            if (is_array($images)) {
                foreach ($images as $image) {
                    if (is_array($image) && isset($image['sizes'])) {
                        // New format - delete all sizes
                        $this->deleteImageFiles($image);
                    } else {
                        // Legacy format - simple path
                        $this->deleteLegacyImageFile($image);
                    }
                }
            }
        }

        $product->delete();

        return redirect()->route('vendor.products.index')
                        ->with('success', 'Product deleted successfully!');
    }

    /**
     * Bulk actions for products
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id'
        ]);

        $products = Product::whereIn('id', $request->product_ids)
                          ->where('vendor_id', Auth::id())
                          ->get();

        switch ($request->action) {
            case 'delete':
                foreach ($products as $product) {
                    // Delete product images
                    if ($product->images) {
                        $images = json_decode($product->images, true);
                        foreach ($images as $image) {
                            Storage::disk('public')->delete($image);
                        }
                    }
                    $product->delete();
                }
                $message = 'Selected products deleted successfully!';
                break;

            case 'activate':
                $products->each(function ($product) {
                    $product->update(['status' => 'active']);
                });
                $message = 'Selected products activated successfully!';
                break;

            case 'deactivate':
                $products->each(function ($product) {
                    $product->update(['status' => 'inactive']);
                });
                $message = 'Selected products deactivated successfully!';
                break;
        }

        return redirect()->route('vendor.products.index')
                        ->with('success', $message);
    }
}
