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
        'gross',
        'sss',
        'philhealth',
        'pagibig',
        'net_pay',
        'sheet',
        'cutoff',
        'period',
    ];
}
