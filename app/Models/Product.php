<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vendor_id',
        'category_id',
        'brand_id',
        'name',
        'slug',
        'description',
        'short_description',
        'sku',
        'price',
        'sale_price',
        'stock_quantity',
        'manage_stock',
        'in_stock',
        'is_active',
        'is_featured',
        'status',
        'weight',
        'dimensions',
        'images',
        'gallery',
        'attributes',
        'sort_order',
        'meta_title',
        'meta_description',
        'meta_keywords',
        // MLM Commission Settings
        'generates_commission',
        'is_starter_kit',
        'starter_kit_tier',
        'starter_kit_level',
        'pv_points',
        'bv_points',
        'direct_commission_rate',
        // Additional columns from the extended migration
        'subcategory_id',
        'cost_price',
        'wholesale_price',
        'compare_price',
        'min_stock_level',
        'max_stock_level',
        'track_quantity',
        'allow_backorder',
        'backorder_limit',
        'barcode',
        'model_number',
        'mpn',
        'gtin',
        'size',
        'color',
        'material',
        'size_chart',
        'color_options',
        'length',
        'width',
        'height',
        'shipping_weight',
        'free_shipping',
        'shipping_cost',
        'is_digital',
        'is_virtual',
        'is_downloadable',
        'is_subscription',
        'is_customizable',
        'is_gift_card',
        'condition',
        'average_rating',
        'review_count',
        'review_count',
        'view_count',
        'purchase_count',
        'available_from',
        'available_until',
        'featured_until',
        'focus_keyword',
        'search_keywords',
        'tags',
        'price_includes_tax',
        'tax_rate',
        'tax_class',
        'has_variants',
        'variant_attributes',
        'parent_product_id',
        'external_id',
        'supplier_sku',
        'supplier_price',
        'specifications',
        'features',
        'included_items',
        'compatibility',
        'warranty_period',
        'warranty_terms',
        'support_email',
        'support_phone',
        'videos',
        'generates_commission',
        'is_starter_kit',
        'pv_points',
        'bv_points',
        'direct_commission_rate',
        'documents',
        'certificates',
        'images',
        'attributes',
        'size',
        'color',
        'material',
        'size_chart',
        'color_options',
        'free_shipping',
        'shipping_cost',
        'unit_id'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'wholesale_price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'supplier_price' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'shipping_weight' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'average_rating' => 'decimal:2',
        'dimensions' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_digital' => 'boolean',
        'is_virtual' => 'boolean',
        'is_downloadable' => 'boolean',
        'is_subscription' => 'boolean',
        'is_customizable' => 'boolean',
        'is_gift_card' => 'boolean',
        'track_quantity' => 'boolean',
        'allow_backorder' => 'boolean',
        'price_includes_tax' => 'boolean',
        'has_variants' => 'boolean',
        'free_shipping' => 'boolean',
        'images' => 'array',
        'attributes' => 'array',
        'specifications' => 'array',
        'features' => 'array',
        'included_items' => 'array',
        'compatibility' => 'array',
        'videos' => 'array',
        'documents' => 'array',
        'certificates' => 'array',
        'tags' => 'array',
        'variant_attributes' => 'array',
        'size_chart' => 'array',
        'color_options' => 'array',
        'search_keywords' => 'array',
        'available_from' => 'datetime',
        'available_until' => 'datetime',
        'featured_until' => 'datetime'
    ];

    // Relationships
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags');
    }

    public function subcategory()
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }

    // Get available attributes from the attributes column
    public function getAvailableAttributes()
    {
        try {
            if (empty($this->attributes)) {
                return collect();
            }
            
            $attributeData = is_string($this->attributes) ? json_decode($this->attributes, true) : $this->attributes;
            
            if (empty($attributeData) || !is_array($attributeData)) {
                return collect();
            }
            
            $attributes = collect();
            foreach ($attributeData as $attrName => $values) {
                if (empty($attrName) || empty($values)) {
                    continue;
                }
                
                $attribute = Attribute::where('name', $attrName)->orWhere('slug', $attrName)->first();
                if (!$attribute) {
                    continue;
                }
                
                $valuesList = is_array($values) ? $values : [$values];
                $attributeValues = AttributeValue::where('attribute_id', $attribute->id)
                    ->whereIn('value', $valuesList)
                    ->active()
                    ->orderBy('sort_order')
                    ->get();
                
                if ($attributeValues->isNotEmpty()) {
                    $attributes->put($attribute->name, [
                        'attribute' => $attribute,
                        'values' => $attributeValues
                    ]);
                }
            }
            
            return $attributes;
        } catch (\Exception $e) {
            Log::error('Error getting available attributes for product ' . $this->id . ': ' . $e->getMessage());
            return collect();
        }
    }

    // Slug mutator to ensure proper slug generation
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = $this->generateSlug($value);
        }
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = $this->generateSlug($value ?: $this->name);
    }

    private function generateSlug($name)
    {
        $slug = \Illuminate\Support\Str::slug($name);
        $originalSlug = $slug;
        $count = 1;
        
        while (static::where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        
        return $slug;
    }

    public function parentProduct()
    {
        return $this->belongsTo(Product::class, 'parent_product_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function variants()
    {
        return $this->hasMany(Product::class, 'parent_product_id');
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function userFavorites()
    {
        return $this->hasMany(UserFavorite::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class, 'collection_products');
    }

    public function affiliateClicks()
    {
        return $this->hasMany(AffiliateClick::class);
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    // Accessors
    public function getDisplayPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    public function getDiscountPercentageAttribute()
    {
        if (!$this->sale_price || !$this->price) {
            return 0;
        }

        return round((($this->price - $this->sale_price) / $this->price) * 100, 2);
    }

    public function getMainImageAttribute()
    {
        return $this->images[0] ?? asset('images/no-image.png');
    }

    public function getImageAttribute()
    {
        // Return first image or default image
        if ($this->images && is_array($this->images) && !empty($this->images)) {
            $firstImage = $this->images[0];
            
            // Handle complex image objects with URLs
            if (is_array($firstImage) && isset($firstImage['urls']['medium'])) {
                // Extract the path from the full URL
                $url = $firstImage['urls']['medium'];
                $parsedUrl = parse_url($url);
                if (isset($parsedUrl['path'])) {
                    // Remove /storage/ prefix to get the actual storage path
                    $path = ltrim($parsedUrl['path'], '/');
                    if (strpos($path, 'storage/') === 0) {
                        return substr($path, 8); // Remove 'storage/' prefix
                    }
                    return $path;
                }
            }
            
            // Handle simple string paths
            if (is_string($firstImage)) {
                return $firstImage;
            }
        }
        
        // Check if images is a string (JSON) and decode it
        if ($this->images && is_string($this->images)) {
            try {
                $decodedImages = json_decode($this->images, true);
                if (is_array($decodedImages) && !empty($decodedImages)) {
                    $firstImage = $decodedImages[0];
                    
                    // Handle complex image objects
                    if (is_array($firstImage) && isset($firstImage['urls']['medium'])) {
                        $url = $firstImage['urls']['medium'];
                        $parsedUrl = parse_url($url);
                        if (isset($parsedUrl['path'])) {
                            $path = ltrim($parsedUrl['path'], '/');
                            if (strpos($path, 'storage/') === 0) {
                                return substr($path, 8);
                            }
                            return $path;
                        }
                    }
                    
                    // Handle simple strings
                    if (is_string($firstImage)) {
                        return $firstImage;
                    }
                }
            } catch (\Exception $e) {
                // If JSON decode fails, return default
            }
        }
        
        return 'products/product1.jpg'; // Default fallback image
    }

    public function getInStockAttribute()
    {
        return $this->stock_quantity > 0;
    }

    public function getLowStockAttribute()
    {
        return $this->stock_quantity <= $this->min_stock_level;
    }

    public function getAverageRatingAttribute()
    {
        return Review::averageRating($this->id);
    }

    public function getTotalReviewsAttribute()
    {
        return Review::totalCount($this->id);
    }

    /**
     * Get the full image URL with proper fallback
     */
    public function getImageUrlAttribute()
    {
        $imagePath = $this->image;
        
        if ($imagePath) {
            // Check if the file exists in storage
            $fullPath = public_path('storage/' . $imagePath);
            if (file_exists($fullPath)) {
                return asset('storage/' . $imagePath);
            }
        }
        
        // Return default image
        return asset('assets/img/product/default.png');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock_quantity', '<=', 'min_stock_level');
    }

    public function scopeByVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('description', 'LIKE', "%{$search}%")
              ->orWhere('sku', 'LIKE', "%{$search}%")
              ->orWhere('barcode', 'LIKE', "%{$search}%");
        });
    }

    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    // Methods
    public function updateStock($quantity, $operation = 'add')
    {
        if ($operation === 'add') {
            $this->stock_quantity += $quantity;
        } else {
            $this->stock_quantity -= $quantity;
        }

        $this->stock_quantity = max(0, $this->stock_quantity);
        $this->save();

        // Create inventory movement
        $this->inventoryMovements()->create([
            'type' => $operation === 'add' ? 'stock_in' : 'stock_out',
            'quantity' => $quantity,
            'remaining_quantity' => $this->stock_quantity,
            'notes' => "Stock {$operation} via product update"
        ]);

        return $this;
    }

    public function checkStockLevels()
    {
        if ($this->inventory) {
            $this->inventory->checkStockLevels();
        }
    }

    public function canPurchase($quantity = 1)
    {
        return $this->is_active && $this->stock_quantity >= $quantity;
    }

    public function addToCategory($categoryId)
    {
        $this->category_id = $categoryId;
        $this->save();

        return $this;
    }

    public function generateSku()
    {
        $prefix = strtoupper(substr($this->vendor->firstname ?? 'VND', 0, 3));
        $suffix = str_pad($this->id, 6, '0', STR_PAD_LEFT);
        
        $this->sku = $prefix . '-' . $suffix;
        $this->save();

        return $this;
    }

    // MLM Relationships
    public function mlmSettings()
    {
        return $this->hasOne(MlmProductSetting::class);
    }

    public function mlmCommissions()
    {
        return $this->hasMany(MlmCommission::class);
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::created(function ($product) {
            // Check if any warehouses exist before creating inventory
            $defaultWarehouse = \App\Models\Warehouse::where('is_active', true)
                                                    ->where('is_default', true)
                                                    ->first();
            
            // If no default warehouse, get any active warehouse
            if (!$defaultWarehouse) {
                $defaultWarehouse = \App\Models\Warehouse::where('is_active', true)->first();
            }
            
            // Only create inventory if a warehouse exists
            if ($defaultWarehouse) {
                Inventory::create([
                    'product_id' => $product->id,
                    'warehouse_id' => $defaultWarehouse->id,
                    'quantity' => $product->stock_quantity ?? 0,
                    'reserved_quantity' => 0,
                    'min_stock_level' => $product->min_stock_level ?? 10,
                    'max_stock_level' => $product->max_stock_level ?? 1000
                ]);
            } else {
                // Log warning that no warehouse exists
                Log::warning('Product created but no warehouse exists for inventory creation', [
                    'product_id' => $product->id,
                    'product_name' => $product->name
                ]);
            }

            // Generate SKU if not provided
            if (!$product->sku) {
                $product->generateSku();
            }
        });

        static::updated(function ($product) {
            // Update inventory quantity if stock_quantity changed
            if ($product->isDirty('stock_quantity')) {
                $inventory = $product->inventory;
                if ($inventory) {
                    $inventory->quantity = $product->stock_quantity;
                    $inventory->save();
                }
            }
        });
    }
}
