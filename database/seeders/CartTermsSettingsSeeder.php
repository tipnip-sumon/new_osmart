<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\DB;

class CartTermsSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update existing general settings record with cart terms default values
        $settings = GeneralSetting::first();
        
        if ($settings) {
            $settings->update([
                'cart_terms_enabled' => true,
                'cart_terms_mandatory' => true,
                'cart_terms_text' => 'I agree with the',
                'cart_terms_link' => null, // Will use default route
                'cart_terms_link_text' => 'terms and conditions',
                'cart_privacy_enabled' => false,
                'cart_privacy_mandatory' => false,
                'cart_privacy_text' => 'I agree with the',
                'cart_privacy_link' => null, // Will use default route
                'cart_privacy_link_text' => 'privacy policy',
            ]);
            
            $this->command->info('Cart terms settings updated successfully.');
        } else {
            // If no settings exist, create new ones with defaults
            GeneralSetting::create([
                'site_name' => 'osmartbd',
                'cur_text' => 'BDT',
                'cur_sym' => '৳',
                'email_from' => 'admin@osmartbd.com',
                'base_color' => '#007bff',
                'secondary_color' => '#6c757d',
                'registration' => true,
                'ev' => false,
                'sv' => false,
                'kv' => false,
                'en' => true,
                'sn' => false,
                'force_ssl' => false,
                'maintenance_mode' => false,
                'secure_password' => false,
                'agree' => true,
                'active_template' => 'ecomus',
                // Cart terms settings
                'cart_terms_enabled' => true,
                'cart_terms_mandatory' => true,
                'cart_terms_text' => 'I agree with the',
                'cart_terms_link' => null,
                'cart_terms_link_text' => 'terms and conditions',
                'cart_privacy_enabled' => false,
                'cart_privacy_mandatory' => false,
                'cart_privacy_text' => 'I agree with the',
                'cart_privacy_link' => null,
                'cart_privacy_link_text' => 'privacy policy',
            ]);
            
            $this->command->info('General settings created with cart terms defaults.');
        }
    }
}
