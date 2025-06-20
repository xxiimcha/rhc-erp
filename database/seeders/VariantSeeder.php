<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Variant;

class VariantSeeder extends Seeder
{
    public function run(): void
    {
        Variant::insert([
            ['name' => 'Basic', 'description' => 'Basic salon setup'],
            ['name' => 'Standard', 'description' => 'Includes basic plus marketing support'],
            ['name' => 'Premium', 'description' => 'All-inclusive with business coaching'],
        ]);
    }
}
