<?php

namespace Database\Seeders;

use App\Models\DeliveryCharge;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeliveryChargeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define delivery charges based on Bangladesh districts
        $deliveryCharges = [
            // Dhaka Division - Lower charges
            ['district' => 'Dhaka', 'charge' => 60.00, 'estimated_delivery_time' => '1-2 days'],
            ['district' => 'Gazipur', 'charge' => 80.00, 'estimated_delivery_time' => '1-2 days'],
            ['district' => 'Narayanganj', 'charge' => 80.00, 'estimated_delivery_time' => '1-2 days'],
            ['district' => 'Manikganj', 'charge' => 100.00, 'estimated_delivery_time' => '2-3 days'],
            ['district' => 'Munshiganj', 'charge' => 100.00, 'estimated_delivery_time' => '2-3 days'],
            ['district' => 'Faridpur', 'charge' => 120.00, 'estimated_delivery_time' => '2-3 days'],
            ['district' => 'Gopalganj', 'charge' => 120.00, 'estimated_delivery_time' => '2-3 days'],
            ['district' => 'Kishoreganj', 'charge' => 120.00, 'estimated_delivery_time' => '2-3 days'],
            ['district' => 'Madaripur', 'charge' => 120.00, 'estimated_delivery_time' => '2-3 days'],
            ['district' => 'Narsingdi', 'charge' => 100.00, 'estimated_delivery_time' => '2-3 days'],
            ['district' => 'Rajbari', 'charge' => 120.00, 'estimated_delivery_time' => '2-3 days'],
            ['district' => 'Shariatpur', 'charge' => 120.00, 'estimated_delivery_time' => '2-3 days'],
            ['district' => 'Tangail', 'charge' => 120.00, 'estimated_delivery_time' => '2-3 days'],

            // Chittagong Division
            ['district' => 'Chittagong', 'charge' => 150.00, 'estimated_delivery_time' => '3-4 days'],
            ['district' => 'Bandarban', 'charge' => 200.00, 'estimated_delivery_time' => '4-7 days'],
            ['district' => 'Brahmanbaria', 'charge' => 130.00, 'estimated_delivery_time' => '3-4 days'],
            ['district' => 'Chandpur', 'charge' => 130.00, 'estimated_delivery_time' => '3-4 days'],
            ['district' => 'Comilla', 'charge' => 130.00, 'estimated_delivery_time' => '3-4 days'],
            ['district' => 'Cox\'s Bazar', 'charge' => 180.00, 'estimated_delivery_time' => '4-6 days'],
            ['district' => 'Feni', 'charge' => 130.00, 'estimated_delivery_time' => '3-4 days'],
            ['district' => 'Khagrachhari', 'charge' => 180.00, 'estimated_delivery_time' => '4-6 days'],
            ['district' => 'Lakshmipur', 'charge' => 130.00, 'estimated_delivery_time' => '3-4 days'],
            ['district' => 'Noakhali', 'charge' => 130.00, 'estimated_delivery_time' => '3-4 days'],
            ['district' => 'Rangamati', 'charge' => 200.00, 'estimated_delivery_time' => '4-7 days'],

            // Rajshahi Division
            ['district' => 'Bogra', 'charge' => 150.00, 'estimated_delivery_time' => '3-5 days'],
            ['district' => 'Joypurhat', 'charge' => 150.00, 'estimated_delivery_time' => '3-5 days'],
            ['district' => 'Naogaon', 'charge' => 150.00, 'estimated_delivery_time' => '3-5 days'],
            ['district' => 'Natore', 'charge' => 150.00, 'estimated_delivery_time' => '3-5 days'],
            ['district' => 'Chapainawabganj', 'charge' => 150.00, 'estimated_delivery_time' => '3-5 days'],
            ['district' => 'Pabna', 'charge' => 150.00, 'estimated_delivery_time' => '3-5 days'],
            ['district' => 'Rajshahi', 'charge' => 150.00, 'estimated_delivery_time' => '3-5 days'],
            ['district' => 'Sirajganj', 'charge' => 150.00, 'estimated_delivery_time' => '3-5 days'],

            // Khulna Division
            ['district' => 'Bagerhat', 'charge' => 150.00, 'estimated_delivery_time' => '3-5 days'],
            ['district' => 'Chuadanga', 'charge' => 150.00, 'estimated_delivery_time' => '3-5 days'],
            ['district' => 'Jessore', 'charge' => 150.00, 'estimated_delivery_time' => '3-5 days'],
            ['district' => 'Jhenaidah', 'charge' => 150.00, 'estimated_delivery_time' => '3-5 days'],
            ['district' => 'Khulna', 'charge' => 150.00, 'estimated_delivery_time' => '3-5 days'],
            ['district' => 'Kushtia', 'charge' => 150.00, 'estimated_delivery_time' => '3-5 days'],
            ['district' => 'Magura', 'charge' => 150.00, 'estimated_delivery_time' => '3-5 days'],
            ['district' => 'Meherpur', 'charge' => 150.00, 'estimated_delivery_time' => '3-5 days'],
            ['district' => 'Narail', 'charge' => 150.00, 'estimated_delivery_time' => '3-5 days'],
            ['district' => 'Satkhira', 'charge' => 150.00, 'estimated_delivery_time' => '3-5 days'],

            // Sylhet Division
            ['district' => 'Habiganj', 'charge' => 160.00, 'estimated_delivery_time' => '3-5 days'],
            ['district' => 'Moulvibazar', 'charge' => 160.00, 'estimated_delivery_time' => '3-5 days'],
            ['district' => 'Sunamganj', 'charge' => 160.00, 'estimated_delivery_time' => '3-5 days'],
            ['district' => 'Sylhet', 'charge' => 160.00, 'estimated_delivery_time' => '3-5 days'],

            // Barisal Division
            ['district' => 'Barguna', 'charge' => 160.00, 'estimated_delivery_time' => '3-5 days'],
            ['district' => 'Barisal', 'charge' => 160.00, 'estimated_delivery_time' => '3-5 days'],
            ['district' => 'Bhola', 'charge' => 160.00, 'estimated_delivery_time' => '3-5 days'],
            ['district' => 'Jhalokati', 'charge' => 160.00, 'estimated_delivery_time' => '3-5 days'],
            ['district' => 'Patuakhali', 'charge' => 160.00, 'estimated_delivery_time' => '3-5 days'],
            ['district' => 'Pirojpur', 'charge' => 160.00, 'estimated_delivery_time' => '3-5 days'],

            // Rangpur Division
            ['district' => 'Dinajpur', 'charge' => 170.00, 'estimated_delivery_time' => '4-7 days'],
            ['district' => 'Gaibandha', 'charge' => 170.00, 'estimated_delivery_time' => '4-7 days'],
            ['district' => 'Kurigram', 'charge' => 170.00, 'estimated_delivery_time' => '4-7 days'],
            ['district' => 'Lalmonirhat', 'charge' => 170.00, 'estimated_delivery_time' => '4-7 days'],
            ['district' => 'Nilphamari', 'charge' => 170.00, 'estimated_delivery_time' => '4-7 days'],
            ['district' => 'Panchagarh', 'charge' => 170.00, 'estimated_delivery_time' => '4-7 days'],
            ['district' => 'Rangpur', 'charge' => 170.00, 'estimated_delivery_time' => '4-7 days'],
            ['district' => 'Thakurgaon', 'charge' => 170.00, 'estimated_delivery_time' => '4-7 days'],

            // Mymensingh Division
            ['district' => 'Jamalpur', 'charge' => 140.00, 'estimated_delivery_time' => '2-4 days'],
            ['district' => 'Mymensingh', 'charge' => 140.00, 'estimated_delivery_time' => '2-4 days'],
            ['district' => 'Netrakona', 'charge' => 140.00, 'estimated_delivery_time' => '2-4 days'],
            ['district' => 'Sherpur', 'charge' => 140.00, 'estimated_delivery_time' => '2-4 days'],
        ];

        // Insert delivery charges
        foreach ($deliveryCharges as $charge) {
            DeliveryCharge::create([
                'district' => $charge['district'],
                'upazila' => null, // District level only
                'ward' => null,
                'charge' => $charge['charge'],
                'estimated_delivery_time' => $charge['estimated_delivery_time'],
                'is_active' => true,
                'notes' => 'Default district-level delivery charge',
                'created_by' => 1, // Assuming admin user has ID 1
            ]);
        }

        // Add some example upazila-specific charges for demonstration
        $upazilaCharges = [
            // Dhaka district specific upazilas with different charges
            ['district' => 'Dhaka', 'upazila' => 'Dhanmondi', 'charge' => 50.00, 'estimated_delivery_time' => '1-2 days'],
            ['district' => 'Dhaka', 'upazila' => 'Gulshan', 'charge' => 50.00, 'estimated_delivery_time' => '1-2 days'],
            ['district' => 'Dhaka', 'upazila' => 'Banani', 'charge' => 50.00, 'estimated_delivery_time' => '1-2 days'],
            ['district' => 'Dhaka', 'upazila' => 'Uttara', 'charge' => 55.00, 'estimated_delivery_time' => '1-2 days'],
            ['district' => 'Dhaka', 'upazila' => 'Mirpur', 'charge' => 55.00, 'estimated_delivery_time' => '1-2 days'],
            
            // Chittagong district specific upazilas
            ['district' => 'Chittagong', 'upazila' => 'Kotwali', 'charge' => 120.00, 'estimated_delivery_time' => '2-3 days'],
            ['district' => 'Chittagong', 'upazila' => 'Panchlaish', 'charge' => 120.00, 'estimated_delivery_time' => '2-3 days'],
        ];

        foreach ($upazilaCharges as $charge) {
            DeliveryCharge::create([
                'district' => $charge['district'],
                'upazila' => $charge['upazila'],
                'ward' => null,
                'charge' => $charge['charge'],
                'estimated_delivery_time' => $charge['estimated_delivery_time'],
                'is_active' => true,
                'notes' => 'Upazila-specific delivery charge',
                'created_by' => 1,
            ]);
        }

        $this->command->info('Delivery charges seeded successfully!');
    }
}
