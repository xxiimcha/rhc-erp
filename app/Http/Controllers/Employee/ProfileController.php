<?php

namespace App\Http\Controllers\Employee;

use App\Models\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employee = Employee::where('employee_id', $user->username)->first();

        return view('employee.profile', compact('employee'));
    }
}
