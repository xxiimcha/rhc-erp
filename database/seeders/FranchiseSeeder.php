<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Franchise;
use App\Models\FranchiseStaff;

class FranchiseSeeder extends Seeder
{
    public function run(): void
    {
        // Create one sample franchise
        $franchise = Franchise::create([
            'branch_code' => 'RHC001',
            'branch' => 'Reyes Haircutters Cubao',
            'region' => 'NCR',
            'location' => 'Cubao, Quezon City',
            'variant_id' => 1, // Make sure variant with ID 1 exists
            'franchise_date' => '2022-01-01',
            'end_of_contract' => '2027-01-01',
            'franchisee_name' => 'Juan Dela Cruz',
            'email_address' => 'juan@example.com',
            'contact_number' => '09171234567',
            'birthday' => '1980-05-15',
            'home_address' => '123 Katipunan Ave., Quezon City',
        ]);

        // Add sample staff to the franchise
        FranchiseStaff::insert([
            [
                'franchise_id' => $franchise->id,
                'staff_name' => 'Maria Santos',
                'staff_designation' => 'Salon Manager',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'franchise_id' => $franchise->id,
                'staff_name' => 'Carlos Reyes',
                'staff_designation' => 'Hair Stylist',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
