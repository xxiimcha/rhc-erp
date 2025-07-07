<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollSetting extends Model
{
    protected $fillable = [
        'ot_multiplier',
        'late_deduction',
        'philhealth_rate',
        'pagibig_rate',
        'regular_holiday_rate',
        'special_holiday_rate',
        'rest_day_rate',
        'night_diff_percent',
        'rate_ots',
        'monthly_working_days',
        'monthly_working_hours',
        'thirteenth_month_base',
        'daily_rate',
        'thirteenth_month_distribution',
        'thirteenth_month_months',
        'first_half_from',
        'first_half_to',
        'second_half_from',
        'second_half_to',
    ];
}
