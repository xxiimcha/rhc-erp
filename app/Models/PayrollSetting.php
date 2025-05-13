<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollSetting extends Model
{
    protected $table = 'payroll_settings';

    protected $fillable = [
        'daily_rate',
        'sss_rate',
        'pagibig_rate',
        'philhealth_rate',
        'ot_multiplier',
        'late_deduction',
    ];

    public $timestamps = true;
}
