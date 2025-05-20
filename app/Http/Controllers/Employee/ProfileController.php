<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $employee = Auth::user(); // Assumes employee is authenticated via default guard
        return view('employee.profile', compact('employee'));
    }
}
