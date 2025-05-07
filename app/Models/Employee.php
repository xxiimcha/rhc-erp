<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'email',
        'contact_number',
        'date_of_birth',
        'gender',
        'address',
        'position',
        'department',
        'employment_type',
        'date_hired',
        'philhealth_no',
        'sss_no',
        'pagibig_no',
        'tin_no',
        'rfid_number'
    ];
}
