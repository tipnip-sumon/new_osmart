<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AdminMenu;
use Illuminate\Support\Facades\DB;

class AdminMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing menu items
        DB::table('admin_menus')->truncate();

        $menus = [
            // Main Dashboard
            [
                'title' => 'Dashboard',
                'icon' => 'bx bx-home',
                'route' => 'admin.dashboard',
                'sort_order' => 1,
                'is_active' => true,
                'menu_type' => 'both',
            ],

            // Users Management
            [
                'title' => 'Users',
                'icon' => 'bx bx-user',
                'route' => null,
                'sort_order' => 2,
                'is_active' => true,
                'menu_type' => 'both',
                'children' => [
                    [
                        'title' => 'All Users',
                        'icon' => 'bx bx-list-ul',
                        'route' => 'admin.users.index',
                        'sort_order' => 1,
                    ],
                    [
                        'title' => 'Add User',
                        'icon' => 'bx bx-plus',
                        'route' => 'admin.users.create',
                        'sort_order' => 2,
                    ],
                    [
                        'title' => 'User Analytics',
                        'icon' => 'bx bx-line-chart',
                        'route' => 'admin.users.analytics',
                        'sort_order' => 3,
                    ],
                ]
            ],

            // Vendors Management
            [
                'title' => 'Vendors',
                'icon' => 'bx bx-store',
                'route' => null,
                'sort_order' => 3,
                'is_active' => true,
                'menu_type' => 'both',
                'children' => [
                    [
                        'title' => 'All Vendors',
                        'icon' => 'bx bx-list-ul',
                        'route' => 'admin.vendors.index',
                        'sort_order' => 1,
                    ],
                    [
                        'title' => 'Pending Approval',
                        'icon' => 'bx bx-time',
                        'route' => 'admin.vendors.pending',
                        'sort_order' => 2,
                        'badge_text' => 'New',
                        'badge_color' => 'warning',
                    ],
                    [
                        'title' => 'Approved',
                        'icon' => 'bx bx-check',
                        'route' => 'admin.vendors.approved',
                        'sort_order' => 3,
                    ],
                    [
                        'title' => 'Suspended',
                        'icon' => 'bx bx-x',
                        'route' => 'admin.vendors.suspended',
                        'sort_order' => 4,
                    ],
                    [
                        'title' => 'Commissions',
                        'icon' => 'bx bx-money',
                        'route' => 'admin.vendors.commissions',
                        'sort_order' => 5,
                    ],
                ]
            ],

            // Products Management
            [
                'title' => 'Products',
                'icon' => 'bx bx-package',
                'route' => null,
                'sort_order' => 4,
                'is_active' => true,
                'menu_type' => 'both',
                'children' => [
                    [
                        'title' => 'All Products',
                        'icon' => 'bx bx-list-ul',
                        'route' => 'admin.products.index',
                        'sort_order' => 1,
                    ],
                    [
                        'title' => 'Add Product',
                        'icon' => 'bx bx-plus',
                        'route' => 'admin.products.create',
                        'sort_order' => 2,
                    ],
                    [
                        'title' => 'Categories',
                        'icon' => 'bx bx-category',
                        'route' => 'admin.categories.index',
                        'sort_order' => 3,
                    ],
                    [
                        'title' => 'Brands',
                        'icon' => 'bx bx-tag',
                        'route' => 'admin.brands.index',
                        'sort_order' => 4,
                    ],
                    [
                        'title' => 'Attributes',
                        'icon' => 'bx bx-list-check',
                        'route' => 'admin.attributes.index',
                        'sort_order' => 5,
                    ],
                    [
                        'title' => 'Collections',
                        'icon' => 'bx bx-collection',
                        'route' => 'admin.collections.index',
                        'sort_order' => 6,
                    ],
                ]
            ],

            // Orders Management
            [
                'title' => 'Orders',
                'icon' => 'bx bx-shopping-bag',
                'route' => null,
                'sort_order' => 5,
                'is_active' => true,
                'menu_type' => 'both',
                'children' => [
                    [
                        'title' => 'All Orders',
                        'icon' => 'bx bx-list-ul',
                        'route' => 'admin.orders.index',
                        'sort_order' => 1,
                    ],
                    [
                        'title' => 'Pending Orders',
                        'icon' => 'bx bx-time',
                        'route' => 'admin.orders.pending',
                        'sort_order' => 2,
                    ],
                    [
                        'title' => 'Processing',
                        'icon' => 'bx bx-loader',
                        'route' => 'admin.orders.processing',
                        'sort_order' => 3,
                    ],
                    [
                        'title' => 'Shipped',
                        'icon' => 'bx bx-package',
                        'route' => 'admin.orders.shipped',
                        'sort_order' => 4,
                    ],
                    [
                        'title' => 'Delivered',
                        'icon' => 'bx bx-check',
                        'route' => 'admin.orders.delivered',
                        'sort_order' => 5,
                    ],
                    [
                        'title' => 'Cancelled',
                        'icon' => 'bx bx-x',
                        'route' => 'admin.orders.cancelled',
                        'sort_order' => 6,
                    ],
                ]
            ],

            // Inventory Management
            [
                'title' => 'Inventory',
                'icon' => 'bx bx-box',
                'route' => null,
                'sort_order' => 6,
                'is_active' => true,
                'menu_type' => 'both',
                'children' => [
                    [
                        'title' => 'Stock Management',
                        'icon' => 'bx bx-package',
                        'route' => 'admin.inventory.stock',
                        'sort_order' => 1,
                    ],
                    [
                        'title' => 'Low Stock',
                        'icon' => 'bx bx-error',
                        'route' => 'admin.inventory.low-stock',
                        'sort_order' => 2,
                        'badge_text' => 'Alert',
                        'badge_color' => 'danger',
                    ],
                    [
                        'title' => 'Out of Stock',
                        'icon' => 'bx bx-error-circle',
                        'route' => 'admin.inventory.out-of-stock',
                        'sort_order' => 3,
                    ],
                    [
                        'title' => 'Stock Adjustments',
                        'icon' => 'bx bx-edit',
                        'route' => 'admin.inventory.adjustments',
                        'sort_order' => 4,
                    ],
                ]
            ],

            // MLM Management
            [
                'title' => 'MLM Management',
                'icon' => 'bx bx-network-chart',
                'route' => null,
                'sort_order' => 7,
                'is_active' => true,
                'menu_type' => 'both',
                'children' => [
                    [
                        'title' => 'Network Tree',
                        'icon' => 'bx bx-sitemap',
                        'route' => 'admin.mlm.tree',
                        'sort_order' => 1,
                    ],
                    [
                        'title' => 'Binary Summary',
                        'icon' => 'bx bx-chart',
                        'route' => 'admin.mlm.binary-summary',
                        'sort_order' => 2,
                    ],
                    [
                        'title' => 'Downline Management',
                        'icon' => 'bx bx-user-check',
                        'route' => 'admin.mlm.downlines',
                        'sort_order' => 3,
                    ],
                    [
                        'title' => 'PV Points System',
                        'icon' => 'bx bx-trophy',
                        'route' => 'admin.mlm.pv-points',
                        'sort_order' => 4,
                    ],
                ]
            ],

            // Commission Management
            [
                'title' => 'Commissions',
                'icon' => 'bx bx-money',
                'route' => null,
                'sort_order' => 8,
                'is_active' => true,
                'menu_type' => 'both',
                'children' => [
                    [
                        'title' => 'Overview',
                        'icon' => 'bx bx-chart',
                        'route' => 'admin.commissions.overview',
                        'sort_order' => 1,
                    ],
                    [
                        'title' => 'Direct Sales',
                        'icon' => 'bx bx-dollar-circle',
                        'route' => 'admin.commissions.direct',
                        'sort_order' => 2,
                    ],
                    [
                        'title' => 'Binary Bonus',
                        'icon' => 'bx bx-chart',
                        'route' => 'admin.commissions.binary',
                        'sort_order' => 3,
                    ],
                    [
                        'title' => 'Matching Bonus',
                        'icon' => 'bx bx-sync',
                        'route' => 'admin.commissions.matching',
                        'sort_order' => 4,
                    ],
                ]
            ],

            // Marketing
            [
                'title' => 'Marketing',
                'icon' => 'bx bx-megaphone',
                'route' => null,
                'sort_order' => 9,
                'is_active' => true,
                'menu_type' => 'both',
                'children' => [
                    [
                        'title' => 'Banners',
                        'icon' => 'bx bx-image',
                        'route' => 'admin.banners.index',
                        'sort_order' => 1,
                    ],
                    [
                        'title' => 'Popups',
                        'icon' => 'bx bx-window',
                        'route' => 'admin.popups.index',
                        'sort_order' => 2,
                    ],
                    [
                        'title' => 'Email Campaigns',
                        'icon' => 'bx bx-mail-send',
                        'route' => 'admin.marketing.email-campaigns',
                        'sort_order' => 3,
                    ],
                    [
                        'title' => 'Coupons',
                        'icon' => 'bx bx-purchase-tag',
                        'route' => 'admin.coupons.index',
                        'sort_order' => 4,
                    ],
                ]
            ],

            // Financial Management
            [
                'title' => 'Financial',
                'icon' => 'bx bx-wallet',
                'route' => null,
                'sort_order' => 10,
                'is_active' => true,
                'menu_type' => 'both',
                'children' => [
                    [
                        'title' => 'Transactions',
                        'icon' => 'bx bx-transfer',
                        'route' => 'admin.finance.transactions',
                        'sort_order' => 1,
                    ],
                    [
                        'title' => 'User Wallets',
                        'icon' => 'bx bx-wallet',
                        'route' => 'admin.finance.wallets',
                        'sort_order' => 2,
                    ],
                    [
                        'title' => 'Withdrawal Requests',
                        'icon' => 'bx bx-download',
                        'route' => 'admin.finance.withdrawals',
                        'sort_order' => 3,
                    ],
                    [
                        'title' => 'Deposits',
                        'icon' => 'bx bx-upload',
                        'route' => 'admin.finance.deposits',
                        'sort_order' => 4,
                    ],
                    [
                        'title' => 'Refunds',
                        'icon' => 'bx bx-undo',
                        'route' => 'admin.finance.refunds',
                        'sort_order' => 5,
                    ],
                ]
            ],

            // Support
            [
                'title' => 'Support',
                'icon' => 'bx bx-support',
                'route' => null,
                'sort_order' => 11,
                'is_active' => true,
                'menu_type' => 'both',
                'children' => [
                    [
                        'title' => 'Support Tickets',
                        'icon' => 'bx bx-support',
                        'route' => 'admin.support.index',
                        'sort_order' => 1,
                    ],
                    [
                        'title' => 'All Tickets',
                        'icon' => 'bx bx-list-ul',
                        'route' => 'admin.support.tickets',
                        'sort_order' => 2,
                    ],
                    [
                        'title' => 'Live Chat',
                        'icon' => 'bx bx-chat',
                        'route' => 'admin.support.chat',
                        'sort_order' => 3,
                        'badge_text' => 'Soon',
                        'badge_color' => 'info',
                    ],
                ]
            ],

            // Website Management
            [
                'title' => 'Website',
                'icon' => 'bx bx-world',
                'route' => null,
                'sort_order' => 12,
                'is_active' => true,
                'menu_type' => 'both',
                'children' => [
                    [
                        'title' => 'Pages',
                        'icon' => 'bx bx-file',
                        'route' => 'admin.website.pages',
                        'sort_order' => 1,
                    ],
                    [
                        'title' => 'Menus',
                        'icon' => 'bx bx-menu',
                        'route' => 'admin.website.menus',
                        'sort_order' => 2,
                    ],
                    [
                        'title' => 'Themes',
                        'icon' => 'bx bx-palette',
                        'route' => 'admin.website.themes',
                        'sort_order' => 3,
                    ],
                    [
                        'title' => 'SEO Settings',
                        'icon' => 'bx bx-search',
                        'route' => 'admin.website.seo',
                        'sort_order' => 4,
                    ],
                ]
            ],

            // KYC Management
            [
                'title' => 'KYC Management',
                'icon' => 'bx bx-shield',
                'route' => null,
                'sort_order' => 13,
                'is_active' => true,
                'menu_type' => 'both',
                'children' => [
                    [
                        'title' => 'All KYC',
                        'icon' => 'bx bx-list-ul',
                        'route' => 'admin.kyc.index',
                        'sort_order' => 1,
                    ],
                    [
                        'title' => 'Pending',
                        'icon' => 'bx bx-time',
                        'route' => 'admin.kyc.pending',
                        'sort_order' => 2,
                        'badge_text' => 'Review',
                        'badge_color' => 'warning',
                    ],
                    [
                        'title' => 'Approved',
                        'icon' => 'bx bx-check',
                        'route' => 'admin.kyc.approved',
                        'sort_order' => 3,
                    ],
                    [
                        'title' => 'Rejected',
                        'icon' => 'bx bx-x',
                        'route' => 'admin.kyc.rejected',
                        'sort_order' => 4,
                    ],
                ]
            ],

            // Settings
            [
                'title' => 'Settings',
                'icon' => 'bx bx-cog',
                'route' => null,
                'sort_order' => 14,
                'is_active' => true,
                'menu_type' => 'both',
                'children' => [
                    [
                        'title' => 'General Settings',
                        'icon' => 'bx bx-cog',
                        'route' => 'admin.settings.general',
                        'sort_order' => 1,
                    ],
                    [
                        'title' => 'Payment Methods',
                        'icon' => 'bx bx-credit-card',
                        'route' => 'admin.settings.payment',
                        'sort_order' => 2,
                    ],
                    [
                        'title' => 'Shipping',
                        'icon' => 'bx bx-package',
                        'route' => 'admin.settings.shipping',
                        'sort_order' => 3,
                    ],
                    [
                        'title' => 'Tax Settings',
                        'icon' => 'bx bx-calculator',
                        'route' => 'admin.settings.tax',
                        'sort_order' => 4,
                    ],
                    [
                        'title' => 'Mail Configuration',
                        'icon' => 'bx bx-mail-send',
                        'route' => 'admin.settings.mail',
                        'sort_order' => 5,
                    ],
                    [
                        'title' => 'Fee Settings',
                        'icon' => 'bx bx-money',
                        'route' => 'admin.general-settings.fee-settings',
                        'sort_order' => 6,
                        'badge_text' => 'New',
                        'badge_color' => 'success',
                    ],
                ]
            ],

            // System Tools
            [
                'title' => 'System Tools',
                'icon' => 'bx bx-wrench',
                'route' => null,
                'sort_order' => 15,
                'is_active' => true,
                'menu_type' => 'both',
                'children' => [
                    [
                        'title' => 'Cache Management',
                        'icon' => 'bx bx-refresh',
                        'route' => 'admin.tools.cache',
                        'sort_order' => 1,
                    ],
                    [
                        'title' => 'System Logs',
                        'icon' => 'bx bx-file',
                        'route' => 'admin.tools.logs',
                        'sort_order' => 2,
                    ],
                    [
                        'title' => 'Database Backup',
                        'icon' => 'bx bx-data',
                        'route' => 'admin.tools.backup',
                        'sort_order' => 3,
                    ],
                    [
                        'title' => 'Maintenance Mode',
                        'icon' => 'bx bx-wrench',
                        'route' => 'admin.tools.maintenance',
                        'sort_order' => 4,
                    ],
                ]
            ],

            // Admin Menu Management
            [
                'title' => 'Menu Management',
                'icon' => 'bx bx-menu',
                'route' => null,
                'sort_order' => 16,
                'is_active' => true,
                'menu_type' => 'both',
                'badge_text' => 'New',
                'badge_color' => 'success',
                'children' => [
                    [
                        'title' => 'All Menus',
                        'icon' => 'bx bx-list-ul',
                        'route' => 'admin.menu.index',
                        'sort_order' => 1,
                    ],
                    [
                        'title' => 'Add Menu',
                        'icon' => 'bx bx-plus',
                        'route' => 'admin.menu.create',
                        'sort_order' => 2,
                    ],
                    [
                        'title' => 'Menu Builder',
                        'icon' => 'bx bx-sitemap',
                        'route' => 'admin.menu.builder',
                        'sort_order' => 3,
                    ],
                ]
            ],
        ];

        $this->createMenuItems($menus);
    }

    private function createMenuItems($menus, $parentId = null)
    {
        foreach ($menus as $menu) {
            $children = $menu['children'] ?? [];
            unset($menu['children']);

            $menu['parent_id'] = $parentId;
            $menuItem = AdminMenu::create($menu);

            if (!empty($children)) {
                $this->createMenuItems($children, $menuItem->id);
            }
        }
    }
}
