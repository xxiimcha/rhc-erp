<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clocking extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'rfid_number',
        'time_in',
        'time_out',
        'status',
        'late_minutes',
        'overtime_minutes',
        'hours_worked'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
