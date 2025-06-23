<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicePricelistSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('service_pricelists')->insert([
            // Haircut
            ['category' => 'Haircut', 'service_name' => 'JUNIOR', 'length_type' => null, 'price' => 149.99, 'is_quoted' => false],
            ['category' => 'Haircut', 'service_name' => 'SENIOR', 'length_type' => null, 'price' => 249.99, 'is_quoted' => false],
            ['category' => 'Haircut', 'service_name' => 'STAR',   'length_type' => null, 'price' => 349.99, 'is_quoted' => false],

            // Hair Color (Permanent)
            ['category' => 'Hair Color (Permanent)', 'service_name' => 'NATURAL COLORS SPA', 'length_type' => 'Minimum Shoulder', 'price' => 699.99, 'is_quoted' => false],
            ['category' => 'Hair Color (Permanent)', 'service_name' => 'NATURAL COLORS SPA', 'length_type' => 'Shoulder Level', 'price' => null, 'is_quoted' => true],
            ['category' => 'Hair Color (Permanent)', 'service_name' => 'NATURAL COLORS SPA', 'length_type' => 'Below Shoulder', 'price' => null, 'is_quoted' => true],

            ['category' => 'Hair Color (Permanent)', 'service_name' => 'NATURAL COLOR SPA - BROWN SERIES', 'length_type' => 'Minimum Shoulder', 'price' => 899.99, 'is_quoted' => false],
            ['category' => 'Hair Color (Permanent)', 'service_name' => 'NATURAL COLOR SPA - BROWN SERIES', 'length_type' => 'Shoulder Level', 'price' => null, 'is_quoted' => true],
            ['category' => 'Hair Color (Permanent)', 'service_name' => 'NATURAL COLOR SPA - BROWN SERIES', 'length_type' => 'Below Shoulder', 'price' => null, 'is_quoted' => true],

            ['category' => 'Hair Color (Permanent)', 'service_name' => 'MONETTI COLORANT', 'length_type' => 'Minimum Shoulder', 'price' => 1499.99, 'is_quoted' => false],
            ['category' => 'Hair Color (Permanent)', 'service_name' => 'MONETTI COLORANT', 'length_type' => 'Shoulder Level', 'price' => null, 'is_quoted' => true],
            ['category' => 'Hair Color (Permanent)', 'service_name' => 'MONETTI COLORANT', 'length_type' => 'Below Shoulder', 'price' => null, 'is_quoted' => true],

            ['category' => 'Hair Color (Permanent)', 'service_name' => 'MATRIX COLOR W-BROWN', 'length_type' => 'Minimum Shoulder', 'price' => 899.99, 'is_quoted' => false],
            ['category' => 'Hair Color (Permanent)', 'service_name' => 'MATRIX COLOR W-BROWN', 'length_type' => 'Shoulder Level', 'price' => null, 'is_quoted' => true],
            ['category' => 'Hair Color (Permanent)', 'service_name' => 'MATRIX COLOR W-BROWN', 'length_type' => 'Below Shoulder', 'price' => null, 'is_quoted' => true],

            ['category' => 'Hair Color (Permanent)', 'service_name' => 'MATRIX SO COLOR', 'length_type' => 'Minimum Shoulder', 'price' => 899.99, 'is_quoted' => false],
            ['category' => 'Hair Color (Permanent)', 'service_name' => 'MATRIX SO COLOR', 'length_type' => 'Shoulder Level', 'price' => null, 'is_quoted' => true],
            ['category' => 'Hair Color (Permanent)', 'service_name' => 'MATRIX SO COLOR', 'length_type' => 'Below Shoulder', 'price' => null, 'is_quoted' => true],

            ['category' => 'Hair Color (Permanent)', 'service_name' => 'MATRIX WONDER LIGHT', 'length_type' => 'Minimum Shoulder', 'price' => 999.99, 'is_quoted' => false],
            ['category' => 'Hair Color (Permanent)', 'service_name' => 'MATRIX WONDER LIGHT', 'length_type' => 'Shoulder Level', 'price' => null, 'is_quoted' => true],
            ['category' => 'Hair Color (Permanent)', 'service_name' => 'MATRIX WONDER LIGHT', 'length_type' => 'Below Shoulder', 'price' => null, 'is_quoted' => true],

            ['category' => 'Hair Color (Permanent)', 'service_name' => 'L\'OREAL COLORS', 'length_type' => 'Minimum Shoulder', 'price' => 1499.99, 'is_quoted' => false],
            ['category' => 'Hair Color (Permanent)', 'service_name' => 'L\'OREAL COLORS', 'length_type' => 'Shoulder Level', 'price' => null, 'is_quoted' => true],
            ['category' => 'Hair Color (Permanent)', 'service_name' => 'L\'OREAL COLORS', 'length_type' => 'Below Shoulder', 'price' => null, 'is_quoted' => true],

            // Hair Color (Semi-Permanent)
            ['category' => 'Hair Color (Semi-Permanent)', 'service_name' => 'MATRIX COLOR SYNC GLOSS TREATMENT (MEN)', 'length_type' => 'Minimum Shoulder', 'price' => 699.99, 'is_quoted' => false],
            ['category' => 'Hair Color (Semi-Permanent)', 'service_name' => 'MATRIX COLOR SYNC GLOSS TREATMENT (MEN)', 'length_type' => 'Shoulder Level', 'price' => null, 'is_quoted' => true],
            ['category' => 'Hair Color (Semi-Permanent)', 'service_name' => 'MATRIX COLOR SYNC GLOSS TREATMENT (MEN)', 'length_type' => 'Below Shoulder', 'price' => null, 'is_quoted' => true],

            ['category' => 'Hair Color (Semi-Permanent)', 'service_name' => 'MATRIX COLOR SYNC GLOSS TREATMENT (WOMEN)', 'length_type' => 'Minimum Shoulder', 'price' => 1499.99, 'is_quoted' => false],
            ['category' => 'Hair Color (Semi-Permanent)', 'service_name' => 'MATRIX COLOR SYNC GLOSS TREATMENT (WOMEN)', 'length_type' => 'Shoulder Level', 'price' => null, 'is_quoted' => true],
            ['category' => 'Hair Color (Semi-Permanent)', 'service_name' => 'MATRIX COLOR SYNC GLOSS TREATMENT (WOMEN)', 'length_type' => 'Below Shoulder', 'price' => null, 'is_quoted' => true],

            ['category' => 'Hair Color (Semi-Permanent)', 'service_name' => 'L\'OREAL DIARICHESSE/DIALIGHT/MAJIREL (MEN)', 'length_type' => 'Minimum Shoulder', 'price' => 1499.99, 'is_quoted' => false],
            ['category' => 'Hair Color (Semi-Permanent)', 'service_name' => 'L\'OREAL DIARICHESSE/DIALIGHT/MAJIREL (MEN)', 'length_type' => 'Shoulder Level', 'price' => null, 'is_quoted' => true],
            ['category' => 'Hair Color (Semi-Permanent)', 'service_name' => 'L\'OREAL DIARICHESSE/DIALIGHT/MAJIREL (MEN)', 'length_type' => 'Below Shoulder', 'price' => null, 'is_quoted' => true],

            ['category' => 'Hair Color (Semi-Permanent)', 'service_name' => 'L\'OREAL DIARICHESSE/DIALIGHT/MAJIREL (WOMEN)', 'length_type' => 'Minimum Shoulder', 'price' => 1999.99, 'is_quoted' => false],
            ['category' => 'Hair Color (Semi-Permanent)', 'service_name' => 'L\'OREAL DIARICHESSE/DIALIGHT/MAJIREL (WOMEN)', 'length_type' => 'Shoulder Level', 'price' => null, 'is_quoted' => true],
            ['category' => 'Hair Color (Semi-Permanent)', 'service_name' => 'L\'OREAL DIARICHESSE/DIALIGHT/MAJIREL (WOMEN)', 'length_type' => 'Below Shoulder', 'price' => null, 'is_quoted' => true],
        ]);
    }
}
