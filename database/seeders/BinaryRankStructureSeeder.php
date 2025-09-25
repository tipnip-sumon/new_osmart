<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BinaryRankStructure;

class BinaryRankStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BinaryRankStructure::seedDefaultRanks();
        
        $this->command->info('✅ Binary rank structures seeded successfully!');
    }
}