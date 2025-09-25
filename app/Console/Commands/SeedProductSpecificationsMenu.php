<?php

namespace App\Console\Commands;

use App\Models\AdminMenu;
use Illuminate\Console\Command;

class SeedProductSpecificationsMenu extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'admin:seed-specifications-menu';

    /**
     * The console command description.
     */
    protected $description = 'Seed Product Specifications menu items into the admin menu';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Seeding Product Specifications menu items...');

        // Find the Products parent menu
        $productsMenu = AdminMenu::where('route', 'admin.products.index')
                                ->orWhere('title', 'Products')
                                ->whereNull('parent_id')
                                ->first();

        if (!$productsMenu) {
            // Create Products parent menu if it doesn't exist
            $productsMenu = AdminMenu::create([
                'title' => 'Products',
                'icon' => 'bx bx-package',
                'route' => null,
                'url' => null,
                'parent_id' => null,
                'sort_order' => 30,
                'is_active' => true,
                'permission' => 'admin.products.view',
                'menu_type' => 'sidebar',
                'description' => 'Product Management'
            ]);
            $this->info('Created Products parent menu');
        }

        // Check if Product Specifications menu already exists
        $specificationsMenu = AdminMenu::where('route', 'admin.products.specifications.index')
                                      ->where('parent_id', $productsMenu->id)
                                      ->first();

        if ($specificationsMenu) {
            $this->warn('Product Specifications menu already exists, updating...');
            $specificationsMenu->update([
                'title' => 'Product Specifications',
                'icon' => 'bx bx-detail',
                'route' => 'admin.products.specifications.index',
                'sort_order' => 50,
                'is_active' => true,
                'permission' => 'admin.products.view',
                'description' => 'Manage product specifications and attributes'
            ]);
        } else {
            // Create Product Specifications menu
            $specificationsMenu = AdminMenu::create([
                'title' => 'Product Specifications',
                'icon' => 'bx bx-detail',
                'route' => 'admin.products.specifications.index',
                'url' => null,
                'parent_id' => $productsMenu->id,
                'sort_order' => 50,
                'is_active' => true,
                'permission' => 'admin.products.view',
                'menu_type' => 'sidebar',
                'description' => 'Manage product specifications and attributes'
            ]);
            $this->info('Created Product Specifications menu');
        }

        // Create submenu items for specifications management
        $subMenus = [
            [
                'title' => 'All Specifications',
                'route' => 'admin.products.specifications.index',
                'icon' => 'bx bx-list-ul',
                'sort_order' => 1,
                'description' => 'View all product specifications'
            ],
            [
                'title' => 'Bulk Update',
                'route' => 'admin.products.specifications.bulk',
                'icon' => 'bx bx-edit-alt',
                'sort_order' => 2,
                'description' => 'Bulk update product specifications'
            ],
            [
                'title' => 'Missing Specs',
                'route' => 'admin.products.specifications.missing',
                'icon' => 'bx bx-error-circle',
                'sort_order' => 3,
                'description' => 'Products with missing specifications'
            ],
            [
                'title' => 'Generate Specs',
                'route' => 'admin.products.specifications.generate',
                'icon' => 'bx bx-cog',
                'sort_order' => 4,
                'description' => 'Auto-generate specifications'
            ]
        ];

        foreach ($subMenus as $subMenu) {
            $existingSubMenu = AdminMenu::where('route', $subMenu['route'])
                                       ->where('parent_id', $specificationsMenu->id)
                                       ->first();

            if ($existingSubMenu) {
                $existingSubMenu->update($subMenu);
                $this->info("Updated submenu: {$subMenu['title']}");
            } else {
                AdminMenu::create(array_merge($subMenu, [
                    'parent_id' => $specificationsMenu->id,
                    'is_active' => true,
                    'permission' => 'admin.products.view',
                    'menu_type' => 'sidebar'
                ]));
                $this->info("Created submenu: {$subMenu['title']}");
            }
        }

        $this->info('Product Specifications menu items seeded successfully!');
        $this->info('You can now access the specifications management from the admin sidebar.');
    }
}
