<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if brands already exist
        if (Brand::count() > 0) {
            $this->command->info('Brands already exist. Skipping seeding.');
            return;
        }

                $brands = [
            [
                'name' => 'Apple',
                'slug' => 'apple',
                'description' => 'Leading technology company known for innovative products like iPhone, iPad, Mac, and more.',
                'logo' => 'brands/apple-logo.png',
                'website_url' => 'https://www.apple.com',
                'is_featured' => true,
                'status' => 'active',
                'meta_title' => 'Apple - Innovation at its finest',
                'meta_description' => 'Discover Apple\'s latest technology products and accessories.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Samsung',
                'slug' => 'samsung',
                'description' => 'South Korean multinational conglomerate known for smartphones, electronics, and appliances.',
                'logo' => 'brands/samsung-logo.png',
                'website_url' => 'https://www.samsung.com',
                'is_featured' => true,
                'status' => 'active',
                'meta_title' => 'Samsung - Inspire the World, Create the Future',
                'meta_description' => 'Explore Samsung\'s innovative technology and electronics.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Nike',
                'slug' => 'nike',
                'description' => 'American multinational corporation engaged in the design, development, manufacturing, and worldwide marketing of footwear, apparel, equipment, accessories, and services.',
                'logo' => 'brands/nike-logo.png',
                'website_url' => 'https://www.nike.com',
                'is_featured' => true,
                'status' => 'active',
                'meta_title' => 'Nike - Just Do It',
                'meta_description' => 'Find the latest Nike shoes, clothing and accessories.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Adidas',
                'slug' => 'adidas',
                'description' => 'German multinational corporation that designs and manufactures shoes, clothing and accessories.',
                'logo' => 'brands/adidas-logo.png',
                'website_url' => 'https://www.adidas.com',
                'is_featured' => true,
                'status' => 'active',
                'meta_title' => 'Adidas - Impossible is Nothing',
                'meta_description' => 'Shop Adidas for athletic shoes, clothing and sport accessories.',
                'sort_order' => 4,
            ],
            [
                'name' => 'Sony',
                'slug' => 'sony',
                'description' => 'Japanese multinational conglomerate corporation known for electronics, gaming, entertainment, and financial services.',
                'logo' => 'brands/sony-logo.png',
                'website_url' => 'https://www.sony.com',
                'is_featured' => true,
                'status' => 'active',
                'meta_title' => 'Sony - Be Moved',
                'meta_description' => 'Discover Sony\'s range of electronics, from cameras to headphones.',
                'sort_order' => 5,
            ],
            [
                'name' => 'LG',
                'slug' => 'lg',
                'description' => 'South Korean multinational electronics company known for home appliances, mobile communications, and vehicle components.',
                'logo' => 'brands/lg-logo.png',
                'website_url' => 'https://www.lg.com',
                'is_featured' => false,
                'status' => 'active',
                'meta_title' => 'LG - Life\'s Good',
                'meta_description' => 'Explore LG\'s innovative home appliances and electronics.',
                'sort_order' => 6,
            ],
            [
                'name' => 'HP',
                'slug' => 'hp',
                'description' => 'American multinational information technology company known for personal computers, printers, and 3D printing solutions.',
                'logo' => 'brands/hp-logo.png',
                'website_url' => 'https://www.hp.com',
                'is_featured' => false,
                'status' => 'active',
                'meta_title' => 'HP - Keep Reinventing',
                'meta_description' => 'Shop HP computers, printers, and technology solutions.',
                'sort_order' => 7,
            ],
            [
                'name' => 'Dell',
                'slug' => 'dell',
                'description' => 'American multinational computer technology company that develops, sells, repairs, and supports computers and related products and services.',
                'logo' => 'brands/dell-logo.png',
                'website_url' => 'https://www.dell.com',
                'is_featured' => false,
                'status' => 'active',
                'meta_title' => 'Dell Technologies',
                'meta_description' => 'Explore Dell\'s range of laptops, desktops, and tech solutions.',
                'sort_order' => 8,
            ],
            [
                'name' => 'Microsoft',
                'slug' => 'microsoft',
                'description' => 'American multinational technology corporation known for software, consumer electronics, personal computers, and related services.',
                'logo' => 'brands/microsoft-logo.png',
                'website_url' => 'https://www.microsoft.com',
                'is_featured' => true,
                'status' => 'active',
                'meta_title' => 'Microsoft - Empower every person and organization',
                'meta_description' => 'Explore Microsoft products and services for home and business.',
                'sort_order' => 9,
            ],
            [
                'name' => 'Google',
                'slug' => 'google',
                'description' => 'American multinational technology company specializing in Internet-related services and products.',
                'logo' => 'brands/google-logo.png',
                'website_url' => 'https://www.google.com',
                'is_featured' => false,
                'status' => 'active',
                'meta_title' => 'Google',
                'meta_description' => 'Discover Google\'s hardware and software products.',
                'sort_order' => 10,
            ],
            [
                'name' => 'Huawei',
                'slug' => 'huawei',
                'description' => 'Chinese multinational technology corporation known for telecommunications equipment and consumer electronics.',
                'logo' => 'brands/huawei-logo.png',
                'website_url' => 'https://www.huawei.com',
                'is_featured' => false,
                'status' => 'active',
                'meta_title' => 'Huawei - Building a Fully Connected, Intelligent World',
                'meta_description' => 'Explore Huawei\'s innovative smartphones and technology.',
                'sort_order' => 11,
            ],
            [
                'name' => 'Xiaomi',
                'slug' => 'xiaomi',
                'description' => 'Chinese multinational electronics company known for smartphones, mobile apps, laptops, home appliances, and consumer electronics.',
                'logo' => 'brands/xiaomi-logo.png',
                'website_url' => 'https://www.mi.com',
                'is_featured' => false,
                'status' => 'active',
                'meta_title' => 'Xiaomi - Innovation for everyone',
                'meta_description' => 'Discover Xiaomi\'s range of smart devices and electronics.',
                'sort_order' => 12,
            ],
        ];

        foreach ($brands as $brandData) {
            Brand::create($brandData);
        }

        $this->command->info('Brands seeded successfully!');
    }
}
