<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Payroll;
use App\Models\HistoricalPayroll;

class EmployeePayrollController extends Controller
{
    public function index()
    {
        $employeeId = Auth::user()->username;
    
        // Combine current and historical payrolls
        $payrolls = Payroll::where('employee_id', $employeeId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->merge(HistoricalPayroll::where('employee_id', $employeeId)
            ->orderBy('created_at', 'desc')
            ->get());
    
        // Latest payroll (optional fallback to historical)
        $latestPayroll = $payrolls->first();
    
        return view('employee.payroll', compact('payrolls', 'latestPayroll'));
    }
    
}
