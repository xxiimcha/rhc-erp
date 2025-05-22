<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $table = 'payrolls';

    protected $fillable = [
        'employee_id',
        'cutoff',
        'period',
        'basic_pay',
        'rate_per_day',
        'rate_per_hour',
        'rate_ots',
        'half_month_pay',
        'percent_hourly_rate',
        'sh_rate',
        'rh_rate',
        'restday_rate',
        'md',
        'allowance',
        'adjusted_ot',
        'days_absent',
        'tardiness',
        'restday_pay',
        'special_holiday_pay',
        'reg_ot_pay',
        'rh_ot_pay',
        'sh_ot_pay',
        'total_ot',
        'pagibig',
        'sss',
        'philhealth',
        'tardiness_deduction',
        'total_salary',
        'thirteenth_month',
    ];

    // Automatically cast decimals to float/double
    protected $casts = [
        'basic_pay' => 'float',
        'rate_per_day' => 'float',
        'rate_per_hour' => 'float',
        'rate_ots' => 'float',
        'half_month_pay' => 'float',
        'percent_hourly_rate' => 'float',
        'sh_rate' => 'float',
        'rh_rate' => 'float',
        'restday_rate' => 'float',
        'md' => 'float',
        'allowance' => 'float',
        'adjusted_ot' => 'float',
        'restday_pay' => 'float',
        'special_holiday_pay' => 'float',
        'reg_ot_pay' => 'float',
        'rh_ot_pay' => 'float',
        'sh_ot_pay' => 'float',
        'total_ot' => 'float',
        'pagibig' => 'float',
        'sss' => 'float',
        'philhealth' => 'float',
        'tardiness_deduction' => 'float',
        'total_salary' => 'float',
        'thirteenth_month' => 'float',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
