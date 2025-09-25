<?php

namespace Database\Seeders;

use App\Models\DeliveryCharge;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DeliveryChargeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing delivery charges
        DeliveryCharge::truncate();

        // Load bangladesh locations data
        $jsonPath = public_path('data/bangladesh-locations.json');
        
        if (!File::exists($jsonPath)) {
            $this->command->error('Bangladesh locations JSON file not found at: ' . $jsonPath);
            return;
        }

        $locationsData = json_decode(File::get($jsonPath), true);
        
        if (!$locationsData) {
            $this->command->error('Failed to parse bangladesh locations JSON file');
            return;
        }

        // Define delivery charge tiers based on divisions and proximity to major cities
        $deliveryTiers = [
            // Tier 1: Major cities (Dhaka, Chittagong) - Lowest charges
            'tier1' => [
                'districts' => ['Dhaka', 'Chittagong'],
                'district_charge' => 60.00,
                'upazila_charge' => 50.00,
                'ward_charge' => 40.00,
                'delivery_time' => '1-2 days'
            ],
            
            // Tier 2: Dhaka Division (excluding Dhaka city) - Low charges
            'tier2' => [
                'districts' => ['Gazipur', 'Narayanganj', 'Manikganj', 'Munshiganj', 'Narsingdi', 'Tangail'],
                'district_charge' => 80.00,
                'upazila_charge' => 70.00,
                'ward_charge' => 60.00,
                'delivery_time' => '1-3 days'
            ],
            
            // Tier 3: Other Dhaka Division districts
            'tier3' => [
                'districts' => ['Faridpur', 'Gopalganj', 'Kishoreganj', 'Madaripur', 'Rajbari', 'Shariatpur'],
                'district_charge' => 100.00,
                'upazila_charge' => 90.00,
                'ward_charge' => 80.00,
                'delivery_time' => '2-4 days'
            ],
            
            // Tier 4: Major divisional cities
            'tier4' => [
                'districts' => ['Rajshahi', 'Khulna', 'Sylhet', 'Barisal', 'Rangpur', 'Mymensingh', 'Comilla'],
                'district_charge' => 120.00,
                'upazila_charge' => 110.00,
                'ward_charge' => 100.00,
                'delivery_time' => '2-4 days'
            ],
            
            // Tier 5: Chittagong Division (excluding Chittagong city)
            'tier5' => [
                'districts' => ['Brahmanbaria', 'Chandpur', 'Feni', 'Lakshmipur', 'Noakhali'],
                'district_charge' => 130.00,
                'upazila_charge' => 120.00,
                'ward_charge' => 110.00,
                'delivery_time' => '3-5 days'
            ],
            
            // Tier 6: Other major districts
            'tier6' => [
                'districts' => ['Bogra', 'Pabna', 'Sirajganj', 'Jessore', 'Kushtia', 'Jamalpur'],
                'district_charge' => 140.00,
                'upazila_charge' => 130.00,
                'ward_charge' => 120.00,
                'delivery_time' => '3-5 days'
            ],
            
            // Tier 7: Hill districts and remote areas
            'tier7' => [
                'districts' => ['Cox\'s Bazar', 'Bandarban', 'Rangamati', 'Khagrachhari'],
                'district_charge' => 180.00,
                'upazila_charge' => 170.00,
                'ward_charge' => 160.00,
                'delivery_time' => '4-7 days'
            ],
            
            // Tier 8: Border and hard-to-reach districts  
            'tier8' => [
                'districts' => ['Panchagarh', 'Lalmonirhat', 'Kurigram', 'Sunamganj'],
                'district_charge' => 200.00,
                'upazila_charge' => 190.00,
                'ward_charge' => 180.00,
                'delivery_time' => '5-8 days'
            ]
        ];

        // Create a tier lookup map
        $districtTierMap = [];
        foreach ($deliveryTiers as $tierId => $tier) {
            foreach ($tier['districts'] as $district) {
                $districtTierMap[$district] = $tierId;
            }
        }

        $totalCharges = 0;
        $processedDistricts = 0;

        foreach ($locationsData as $districtData) {
            $districtName = $districtData['name'];
            $processedDistricts++;
            
            // Determine tier for this district (default to tier6 if not found)
            $tierId = $districtTierMap[$districtName] ?? 'tier6';
            $tier = $deliveryTiers[$tierId];
            
            $this->command->info("Processing district: {$districtName} (Tier: {$tierId})");

            // 1. Create district-level charge
            DeliveryCharge::create([
                'district' => $districtName,
                'upazila' => null,
                'ward' => null,
                'charge' => $tier['district_charge'],
                'estimated_delivery_time' => $tier['delivery_time'],
                'is_active' => true,
                'notes' => "District-level delivery charge for {$districtName}",
                'created_by' => 1,
            ]);
            $totalCharges++;

            // 2. Process upazilas
            if (isset($districtData['upazilas']) && is_array($districtData['upazilas'])) {
                foreach ($districtData['upazilas'] as $upazilaData) {
                    if (!isset($upazilaData['name'])) continue;
                    
                    $upazilaName = $upazilaData['name'];
                    
                    // Create upazila-level charge
                    DeliveryCharge::create([
                        'district' => $districtName,
                        'upazila' => $upazilaName,
                        'ward' => null,
                        'charge' => $tier['upazila_charge'],
                        'estimated_delivery_time' => $tier['delivery_time'],
                        'is_active' => true,
                        'notes' => "Upazila-level delivery charge for {$upazilaName}, {$districtName}",
                        'created_by' => 1,
                    ]);
                    $totalCharges++;

                    // 3. Process wards/unions (process all unions for complete coverage)
                    if (isset($upazilaData['unions']) && is_array($upazilaData['unions'])) {
                        // Process ALL unions instead of limiting to first 3
                        $unionsToProcess = $upazilaData['unions'];
                        
                        foreach ($unionsToProcess as $unionName) {
                            if (empty($unionName)) continue;
                            
                            // Create ward/union-level charge
                            DeliveryCharge::create([
                                'district' => $districtName,
                                'upazila' => $upazilaName,
                                'ward' => $unionName,
                                'charge' => $tier['ward_charge'],
                                'estimated_delivery_time' => $tier['delivery_time'],
                                'is_active' => true,
                                'notes' => "Ward-level delivery charge for {$unionName}, {$upazilaName}, {$districtName}",
                                'created_by' => 1,
                            ]);
                            $totalCharges++;
                        }
                    }
                }
            }

            // Show progress every 10 districts
            if ($processedDistricts % 10 == 0) {
                $this->command->info("Processed {$processedDistricts} districts, created {$totalCharges} delivery charges so far...");
            }
        }

        // Create some special express delivery charges for major cities
        $expressCharges = [
            ['district' => 'Dhaka', 'upazila' => 'Dhanmondi', 'charge' => 30.00, 'time' => '4-6 hours', 'note' => 'Express delivery'],
            ['district' => 'Dhaka', 'upazila' => 'Gulshan', 'charge' => 30.00, 'time' => '4-6 hours', 'note' => 'Express delivery'],
            ['district' => 'Dhaka', 'upazila' => 'Banani', 'charge' => 30.00, 'time' => '4-6 hours', 'note' => 'Express delivery'],
            ['district' => 'Dhaka', 'upazila' => 'Uttara', 'charge' => 35.00, 'time' => '4-8 hours', 'note' => 'Express delivery'],
            ['district' => 'Dhaka', 'upazila' => 'Mirpur', 'charge' => 35.00, 'time' => '4-8 hours', 'note' => 'Express delivery'],
            ['district' => 'Chittagong', 'upazila' => 'Kotwali', 'charge' => 40.00, 'time' => '6-12 hours', 'note' => 'Express delivery'],
            ['district' => 'Chittagong', 'upazila' => 'Panchlaish', 'charge' => 40.00, 'time' => '6-12 hours', 'note' => 'Express delivery'],
        ];

        foreach ($expressCharges as $charge) {
            DeliveryCharge::create([
                'district' => $charge['district'],
                'upazila' => $charge['upazila'],
                'ward' => 'Express Delivery Zone',
                'charge' => $charge['charge'],
                'estimated_delivery_time' => $charge['time'],
                'is_active' => true,
                'notes' => $charge['note'] . ' - Premium service for urgent orders',
                'created_by' => 1,
            ]);
            $totalCharges++;
        }

        $this->command->info("✅ Delivery charges seeded successfully!");
        $this->command->info("📊 Total districts processed: {$processedDistricts}");
        $this->command->info("📦 Total delivery charges created: {$totalCharges}");
        $this->command->table(
            ['Tier', 'Districts', 'District Charge', 'Upazila Charge', 'Ward Charge', 'Delivery Time'],
            collect($deliveryTiers)->map(function ($tier, $tierId) {
                return [
                    $tierId,
                    implode(', ', array_slice($tier['districts'], 0, 3)) . (count($tier['districts']) > 3 ? '...' : ''),
                    '৳' . $tier['district_charge'],
                    '৳' . $tier['upazila_charge'], 
                    '৳' . $tier['ward_charge'],
                    $tier['delivery_time']
                ];
            })
        );
    }
}
