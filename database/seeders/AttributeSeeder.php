<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Attribute;
use App\Models\AttributeValue;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if attributes already exist
        if (Attribute::count() > 0) {
            $this->command->info('Attributes already exist. Skipping seeding.');
            return;
        }

        // Color Attribute
        $colorAttribute = Attribute::create([
            'name' => 'Color',
            'slug' => 'color',
            'type' => 'color',
            'description' => 'Product color variations',
            'is_required' => false,
            'is_filterable' => true,
            'is_variation' => true,
            'is_global' => true,
            'sort_order' => 1,
            'status' => 'active',
        ]);

        $colors = [
            ['name' => 'Red', 'value' => '#FF0000'],
            ['name' => 'Blue', 'value' => '#0000FF'],
            ['name' => 'Green', 'value' => '#008000'],
            ['name' => 'Black', 'value' => '#000000'],
            ['name' => 'White', 'value' => '#FFFFFF'],
            ['name' => 'Yellow', 'value' => '#FFFF00'],
            ['name' => 'Orange', 'value' => '#FFA500'],
            ['name' => 'Purple', 'value' => '#800080'],
            ['name' => 'Pink', 'value' => '#FFC0CB'],
            ['name' => 'Gray', 'value' => '#808080'],
        ];

        foreach ($colors as $index => $color) {
            AttributeValue::create([
                'attribute_id' => $colorAttribute->id,
                'value' => $color['name'],
                'display_name' => $color['name'],
                'color_code' => $color['value'],
                'sort_order' => $index + 1,
                'status' => 'active',
            ]);
        }

        // Size Attribute
        $sizeAttribute = Attribute::create([
            'name' => 'Size',
            'slug' => 'size',
            'type' => 'select',
            'description' => 'Product size options',
            'is_required' => false,
            'is_filterable' => true,
            'is_variation' => true,
            'is_global' => true,
            'sort_order' => 2,
            'status' => 'active',
        ]);

        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];

        foreach ($sizes as $index => $size) {
            AttributeValue::create([
                'attribute_id' => $sizeAttribute->id,
                'value' => $size,
                'display_name' => $size,
                'sort_order' => $index + 1,
                'status' => 'active',
            ]);
        }

        // Material Attribute
        $materialAttribute = Attribute::create([
            'name' => 'Material',
            'slug' => 'material',
            'type' => 'select',
            'description' => 'Material composition',
            'is_required' => false,
            'is_filterable' => true,
            'is_variation' => false,
            'is_global' => true,
            'sort_order' => 3,
            'status' => 'active',
        ]);

        $materials = [
            'Cotton', 'Polyester', 'Leather', 'Silk', 'Wool', 
            'Denim', 'Linen', 'Plastic', 'Metal', 'Wood'
        ];

        foreach ($materials as $index => $material) {
            AttributeValue::create([
                'attribute_id' => $materialAttribute->id,
                'value' => $material,
                'display_name' => $material,
                'sort_order' => $index + 1,
                'status' => 'active',
            ]);
        }

        // Storage Attribute (for electronics)
        $storageAttribute = Attribute::create([
            'name' => 'Storage',
            'slug' => 'storage',
            'type' => 'select',
            'description' => 'Storage capacity for electronic devices',
            'is_required' => false,
            'is_filterable' => true,
            'is_variation' => true,
            'is_global' => false,
            'sort_order' => 4,
            'status' => 'active',
        ]);

        $storageOptions = ['64GB', '128GB', '256GB', '512GB', '1TB', '2TB'];

        foreach ($storageOptions as $index => $storage) {
            AttributeValue::create([
                'attribute_id' => $storageAttribute->id,
                'value' => $storage,
                'display_name' => $storage,
                'sort_order' => $index + 1,
                'status' => 'active',
            ]);
        }

        // RAM Attribute (for electronics)
        $ramAttribute = Attribute::create([
            'name' => 'RAM',
            'slug' => 'ram',
            'type' => 'select',
            'description' => 'Memory capacity for electronic devices',
            'is_required' => false,
            'is_filterable' => true,
            'is_variation' => true,
            'is_global' => false,
            'sort_order' => 5,
            'status' => 'active',
        ]);

        $ramOptions = ['4GB', '6GB', '8GB', '12GB', '16GB', '32GB'];

        foreach ($ramOptions as $index => $ram) {
            AttributeValue::create([
                'attribute_id' => $ramAttribute->id,
                'value' => $ram,
                'display_name' => $ram,
                'sort_order' => $index + 1,
                'status' => 'active',
            ]);
        }

        $this->command->info('Attributes and attribute values seeded successfully!');
    }
}
