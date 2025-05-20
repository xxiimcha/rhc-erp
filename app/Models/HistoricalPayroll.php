<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricalPayroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'employee_name',
        'department',
        'basic_salary',
        'allowance',
        'adjustment',
        'ot',
        'rdot',
        'sh_ot',
        'sh',
        'lh_rh',
        'rnd',
        'tardiness',
        'absences',
        'gross',
        'sss',
        'philhealth',
        'pagibig',
        'others',
        'total_deduction',
        'net_pay',
        'sheet',
        'cutoff',
        'period',
    ];
}
