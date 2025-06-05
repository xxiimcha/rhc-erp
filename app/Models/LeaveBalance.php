<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'year',
        'vacation_leave',
        'sick_leave',
        'emergency_leave',
        'birthday_leave',
    ];
}
