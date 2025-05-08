<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSalary extends Model
{
    protected $fillable = ['employee_id', 'amount', 'rate_type', 'status', 'remarks'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
