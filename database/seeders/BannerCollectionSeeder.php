<?php

namespace Database\Seeders;

use App\Models\BannerCollection;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BannerCollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BannerCollection::create([
            'title' => 'The Summer Sale',
            'description' => 'Discover the hottest trends and must-have styles of the season.',
            'button_text' => 'Shop Collection',
            'button_url' => '/collections/summer-sale',
            'image' => 'banner-collections/summer-sale.png',
            'show_countdown' => true,
            'countdown_end_date' => Carbon::now()->addDays(30),
            'background_color' => '#f8f9fa',
            'text_color' => '#333333',
            'is_active' => true,
            'sort_order' => 1
        ]);

        BannerCollection::create([
            'title' => 'Winter Collection 2024',
            'description' => 'Stay warm and stylish with our latest winter collection featuring cozy sweaters and warm coats.',
            'button_text' => 'Explore Winter',
            'button_url' => '/collections/winter-2024',
            'image' => 'banner-collections/winter-collection.png',
            'show_countdown' => false,
            'countdown_end_date' => null,
            'background_color' => '#e3f2fd',
            'text_color' => '#1976d2',
            'is_active' => false,
            'sort_order' => 2
        ]);

        BannerCollection::create([
            'title' => 'Flash Sale - 50% Off',
            'description' => 'Limited time only! Get up to 50% off on selected items. Hurry while stocks last!',
            'button_text' => 'Shop Now',
            'button_url' => '/collections/flash-sale',
            'image' => 'banner-collections/flash-sale.png',
            'show_countdown' => true,
            'countdown_end_date' => Carbon::now()->addHours(24),
            'background_color' => '#ffebee',
            'text_color' => '#d32f2f',
            'is_active' => false,
            'sort_order' => 3
        ]);
    }
}