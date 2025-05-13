<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PayrollSetting;

class PayrollSettingsSeeder extends Seeder
{
    public function run(): void
    {
        PayrollSetting::updateOrCreate(['id' => 1], [
            'annual_months'               => 12,
            'work_days_per_year'          => 261,
            'work_hours_per_day'          => 8,
            'minutes_per_day'             => 480,
            'monthly_working_days'        => 22,
            'monthly_working_hours'       => 176,
            'ot_multiplier'               => 1.25,
            'night_diff_percent'          => 10.00,
            'sh_rate_percent'             => 30.00,
            'rh_rate_multiplier'          => 2.60,
            'ots_rate_multiplier'         => 2.00,
            'rest_day_extra_percent'      => 30.00,
            'percent_hourly_rate'         => 25.00,
            'late_deduction_base_divisor' => 480.00,
            'philhealth_rate'             => 4.50,
            'pagibig_rate'                => 2.00,
            'thirteenth_month_base'       => 12,
            'default_daily_rate'          => 480.00,
        ]);        
    }
}
