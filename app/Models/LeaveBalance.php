<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_name',
        'year',
        'total_leaves',
        'leaves_taken',
        'remaining_leaves',
    ];
}
