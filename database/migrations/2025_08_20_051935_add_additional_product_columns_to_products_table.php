<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Brand and Category relationships
            $table->unsignedBigInteger('brand_id')->nullable()->after('category_id');
            $table->unsignedBigInteger('subcategory_id')->nullable()->after('brand_id');
            
            // Pricing fields
            $table->decimal('cost_price', 10, 2)->nullable()->after('sale_price')->comment('Product cost price for profit calculation');
            $table->decimal('wholesale_price', 10, 2)->nullable()->after('cost_price')->comment('Wholesale price');
            $table->decimal('compare_price', 10, 2)->nullable()->after('wholesale_price')->comment('Compare at price for discounts');
            
            // Inventory management
            $table->integer('min_stock_level')->default(0)->after('stock_quantity')->comment('Minimum stock alert level');
            $table->integer('max_stock_level')->nullable()->after('min_stock_level')->comment('Maximum stock level');
            $table->boolean('track_quantity')->default(true)->after('max_stock_level')->comment('Whether to track inventory');
            $table->boolean('allow_backorder')->default(false)->after('track_quantity')->comment('Allow orders when out of stock');
            $table->integer('backorder_limit')->nullable()->after('allow_backorder')->comment('Maximum backorder quantity');
            
            // Product identification
            $table->string('barcode')->nullable()->after('sku')->comment('Product barcode/UPC');
            $table->string('model_number')->nullable()->after('barcode')->comment('Manufacturer model number');
            $table->string('mpn')->nullable()->after('model_number')->comment('Manufacturer Part Number');
            $table->string('gtin')->nullable()->after('mpn')->comment('Global Trade Item Number');
            
            // Physical properties
            $table->string('size')->nullable()->after('weight')->comment('Product size (S, M, L, XL, etc.)');
            $table->string('color')->nullable()->after('size')->comment('Product color');
            $table->string('material')->nullable()->after('color')->comment('Product material');
            $table->json('size_chart')->nullable()->after('material')->comment('Size chart data');
            $table->json('color_options')->nullable()->after('size_chart')->comment('Available color options');
            
            // Shipping and dimensions
            $table->decimal('length', 8, 2)->nullable()->after('dimensions')->comment('Length in cm');
            $table->decimal('width', 8, 2)->nullable()->after('length')->comment('Width in cm');
            $table->decimal('height', 8, 2)->nullable()->after('width')->comment('Height in cm');
            $table->decimal('shipping_weight', 8, 2)->nullable()->after('height')->comment('Shipping weight in kg');
            $table->boolean('free_shipping')->default(false)->after('shipping_weight')->comment('Free shipping available');
            $table->decimal('shipping_cost', 8, 2)->nullable()->after('free_shipping')->comment('Fixed shipping cost');
            
            // Product type and features
            $table->boolean('is_digital')->default(false)->after('is_featured')->comment('Digital product (no shipping)');
            $table->boolean('is_virtual')->default(false)->after('is_digital')->comment('Virtual product (services)');
            $table->boolean('is_downloadable')->default(false)->after('is_virtual')->comment('Downloadable product');
            $table->boolean('is_subscription')->default(false)->after('is_downloadable')->comment('Subscription product');
            $table->boolean('is_customizable')->default(false)->after('is_subscription')->comment('Can be customized');
            $table->boolean('is_gift_card')->default(false)->after('is_customizable')->comment('Gift card product');
            
            // Product conditions and ratings
            $table->enum('condition', ['new', 'used', 'refurbished', 'damaged'])->default('new')->after('is_gift_card');
            $table->decimal('average_rating', 3, 2)->default(0)->after('condition')->comment('Average customer rating');
            $table->integer('review_count')->default(0)->after('average_rating')->comment('Total number of reviews');
            $table->integer('view_count')->default(0)->after('review_count')->comment('Product view count');
            $table->integer('purchase_count')->default(0)->after('view_count')->comment('Total purchases');
            
            // Dates and scheduling
            $table->timestamp('available_from')->nullable()->after('purchase_count')->comment('Product availability start date');
            $table->timestamp('available_until')->nullable()->after('available_from')->comment('Product availability end date');
            $table->timestamp('featured_until')->nullable()->after('available_until')->comment('Featured status end date');
            
            // SEO and marketing
            $table->string('focus_keyword')->nullable()->after('meta_keywords')->comment('Primary SEO keyword');
            $table->text('search_keywords')->nullable()->after('focus_keyword')->comment('Additional search keywords');
            $table->json('tags')->nullable()->after('search_keywords')->comment('Product tags');
            
            // Pricing rules and discounts
            $table->boolean('price_includes_tax')->default(true)->after('tags')->comment('Whether price includes tax');
            $table->decimal('tax_rate', 5, 2)->nullable()->after('price_includes_tax')->comment('Tax rate percentage');
            $table->string('tax_class')->nullable()->after('tax_rate')->comment('Tax classification');
            
            // Product variants and options
            $table->boolean('has_variants')->default(false)->after('tax_class')->comment('Product has variations');
            $table->json('variant_attributes')->nullable()->after('has_variants')->comment('Attributes that create variants');
            $table->unsignedBigInteger('parent_product_id')->nullable()->after('variant_attributes')->comment('Parent product for variants');
            
            // External integrations
            $table->string('external_id')->nullable()->after('parent_product_id')->comment('External system ID');
            $table->string('supplier_sku')->nullable()->after('external_id')->comment('Supplier SKU');
            $table->decimal('supplier_price', 10, 2)->nullable()->after('supplier_sku')->comment('Supplier price');
            
            // Product specifications
            $table->json('specifications')->nullable()->after('supplier_price')->comment('Technical specifications');
            $table->json('features')->nullable()->after('specifications')->comment('Product features list');
            $table->json('included_items')->nullable()->after('features')->comment('Items included with product');
            $table->json('compatibility')->nullable()->after('included_items')->comment('Compatibility information');
            
            // Warranty and support
            $table->string('warranty_period')->nullable()->after('compatibility')->comment('Warranty period');
            $table->text('warranty_terms')->nullable()->after('warranty_period')->comment('Warranty terms and conditions');
            $table->string('support_email')->nullable()->after('warranty_terms')->comment('Product support email');
            $table->string('support_phone')->nullable()->after('support_email')->comment('Product support phone');
            
            // Additional media
            $table->json('videos')->nullable()->after('support_phone')->comment('Product videos');
            $table->json('documents')->nullable()->after('videos')->comment('Product documents/manuals');
            $table->json('certificates')->nullable()->after('documents')->comment('Product certificates');
            
            // Foreign key constraints
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');
            $table->foreign('subcategory_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('parent_product_id')->references('id')->on('products')->onDelete('cascade');
            
            // Indexes for better performance
            $table->index(['brand_id', 'category_id']);
            $table->index(['status', 'is_active']);
            $table->index(['price', 'sale_price']);
            $table->index(['condition', 'average_rating']);
            $table->index(['available_from', 'available_until']);
            $table->index('external_id');
            $table->index('parent_product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['brand_id']);
            $table->dropForeign(['subcategory_id']);
            $table->dropForeign(['parent_product_id']);
            
            // Drop all added columns
            $table->dropColumn([
                'brand_id', 'subcategory_id', 'cost_price', 'wholesale_price', 'compare_price',
                'min_stock_level', 'max_stock_level', 'track_quantity', 'allow_backorder', 'backorder_limit',
                'barcode', 'model_number', 'mpn', 'gtin', 'size', 'color', 'material', 'size_chart', 'color_options',
                'length', 'width', 'height', 'shipping_weight', 'free_shipping', 'shipping_cost',
                'is_digital', 'is_virtual', 'is_downloadable', 'is_subscription', 'is_customizable', 'is_gift_card',
                'condition', 'average_rating', 'review_count', 'view_count', 'purchase_count',
                'available_from', 'available_until', 'featured_until', 'focus_keyword', 'search_keywords', 'tags',
                'price_includes_tax', 'tax_rate', 'tax_class', 'has_variants', 'variant_attributes', 'parent_product_id',
                'external_id', 'supplier_sku', 'supplier_price', 'specifications', 'features', 'included_items',
                'compatibility', 'warranty_period', 'warranty_terms', 'support_email', 'support_phone',
                'videos', 'documents', 'certificates'
            ]);
        });
    }
};
