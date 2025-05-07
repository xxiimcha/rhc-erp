<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    /**
     * Display the payroll page.
     */
    public function index()
    {
        // You can fetch data here such as employee salaries, deductions, etc.
        // Example: $payrolls = Payroll::with('employee')->latest()->get();

        return view('hr.payroll.index');
    }
}
