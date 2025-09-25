<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            // Weight units
            [
                'name' => 'Kilogram',
                'symbol' => 'kg',
                'type' => 'weight',
                'description' => 'Base unit of mass in the metric system',
                'base_factor' => 1.0,
                'base_unit_id' => null,
                'is_active' => true,
                'is_default' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Gram',
                'symbol' => 'g',
                'type' => 'weight',
                'description' => 'Metric unit of mass equal to 1/1000 kilogram',
                'base_factor' => 0.001,
                'base_unit_id' => 1, // Will be set after creating kg
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 2,
            ],
            [
                'name' => 'Pound',
                'symbol' => 'lb',
                'type' => 'weight',
                'description' => 'Imperial unit of weight',
                'base_factor' => 0.453592,
                'base_unit_id' => 1,
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 3,
            ],
            [
                'name' => 'Ounce',
                'symbol' => 'oz',
                'type' => 'weight',
                'description' => 'Imperial unit of weight equal to 1/16 pound',
                'base_factor' => 0.0283495,
                'base_unit_id' => 1,
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 4,
            ],

            // Length units
            [
                'name' => 'Meter',
                'symbol' => 'm',
                'type' => 'length',
                'description' => 'Base unit of length in the metric system',
                'base_factor' => 1.0,
                'base_unit_id' => null,
                'is_active' => true,
                'is_default' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Centimeter',
                'symbol' => 'cm',
                'type' => 'length',
                'description' => 'Metric unit of length equal to 1/100 meter',
                'base_factor' => 0.01,
                'base_unit_id' => 5, // Will be meter
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 2,
            ],
            [
                'name' => 'Millimeter',
                'symbol' => 'mm',
                'type' => 'length',
                'description' => 'Metric unit of length equal to 1/1000 meter',
                'base_factor' => 0.001,
                'base_unit_id' => 5,
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 3,
            ],
            [
                'name' => 'Inch',
                'symbol' => 'in',
                'type' => 'length',
                'description' => 'Imperial unit of length',
                'base_factor' => 0.0254,
                'base_unit_id' => 5,
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 4,
            ],
            [
                'name' => 'Foot',
                'symbol' => 'ft',
                'type' => 'length',
                'description' => 'Imperial unit of length equal to 12 inches',
                'base_factor' => 0.3048,
                'base_unit_id' => 5,
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 5,
            ],

            // Volume units
            [
                'name' => 'Liter',
                'symbol' => 'L',
                'type' => 'volume',
                'description' => 'Metric unit of volume',
                'base_factor' => 1.0,
                'base_unit_id' => null,
                'is_active' => true,
                'is_default' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Milliliter',
                'symbol' => 'mL',
                'type' => 'volume',
                'description' => 'Metric unit of volume equal to 1/1000 liter',
                'base_factor' => 0.001,
                'base_unit_id' => 10,
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 2,
            ],
            [
                'name' => 'Gallon',
                'symbol' => 'gal',
                'type' => 'volume',
                'description' => 'Imperial unit of volume',
                'base_factor' => 3.78541,
                'base_unit_id' => 10,
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 3,
            ],

            // Quantity units
            [
                'name' => 'Piece',
                'symbol' => 'pcs',
                'type' => 'quantity',
                'description' => 'Individual items or pieces',
                'base_factor' => 1.0,
                'base_unit_id' => null,
                'is_active' => true,
                'is_default' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Dozen',
                'symbol' => 'dz',
                'type' => 'quantity',
                'description' => 'A group of twelve items',
                'base_factor' => 12.0,
                'base_unit_id' => 13,
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 2,
            ],
            [
                'name' => 'Pack',
                'symbol' => 'pack',
                'type' => 'quantity',
                'description' => 'A package or bundle of items',
                'base_factor' => 1.0,
                'base_unit_id' => null,
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 3,
            ],
            [
                'name' => 'Box',
                'symbol' => 'box',
                'type' => 'quantity',
                'description' => 'A container or box of items',
                'base_factor' => 1.0,
                'base_unit_id' => null,
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 4,
            ],

            // Area units
            [
                'name' => 'Square Meter',
                'symbol' => 'm²',
                'type' => 'area',
                'description' => 'Base unit of area in the metric system',
                'base_factor' => 1.0,
                'base_unit_id' => null,
                'is_active' => true,
                'is_default' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Square Foot',
                'symbol' => 'ft²',
                'type' => 'area',
                'description' => 'Imperial unit of area',
                'base_factor' => 0.092903,
                'base_unit_id' => 17,
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 2,
            ],
        ];

        foreach ($units as $unit) {
            Unit::updateOrCreate(
                ['name' => $unit['name'], 'type' => $unit['type']], // Key to check for existing records
                $unit // Data to update or create
            );
        }
    }
}
