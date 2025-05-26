<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeWorkday extends Model
{
    protected $fillable = [
        'employee_id',
        'day_of_week',
        'shift_time',
        'updated_by',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
