<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Clocking;
use App\Models\Employee;
use App\Models\EmployeeSalary;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::latest()->get();
        return view('hr.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('hr.employees.create');
    }

    public function show($id)
    {
        $employee = Employee::findOrFail($id);

        $month = request()->input('month', now()->format('Y-m'));
        $clockings = Clocking::where('employee_id', $employee->employee_id)
            ->whereYear('time_in', Carbon::parse($month)->year)
            ->whereMonth('time_in', Carbon::parse($month)->month)
            ->orderBy('time_in', 'asc')
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->time_in)->format('Y-m-d');
            });

        return view('hr.employees.show', compact('employee', 'clockings', 'month'));
    }

    public function storeRfid(Request $request, $id)
    {
        $request->validate([
            'rfid_number' => 'required|unique:employees,rfid_number',
        ]);

        $employee = Employee::findOrFail($id);
        $employee->rfid_number = $request->rfid_number;
        $employee->save();

        return redirect()->route('admin.hr.employees.index')->with('success', 'RFID enrolled successfully.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:employees,email',
            'contact_number' => 'required',
            'date_of_birth' => 'required|date',
            'gender' => 'required',
            'address' => 'required',
            'position' => 'required',
            'department' => 'required',
            'employment_type' => 'required',
            'date_hired' => 'required|date',
        ]);
    
        $year = date('Y', strtotime($request->date_hired));
        $count = Employee::whereYear('date_hired', $year)->count() + 1;
        $employeeId = $year . str_pad($count, 3, '0', STR_PAD_LEFT);
    
        $employee = Employee::create([
            'employee_id' => $employeeId,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'suffix' => $request->suffix,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
            'position' => $request->position,
            'department' => $request->department,
            'employment_type' => $request->employment_type,
            'date_hired' => $request->date_hired,
            'philhealth_no' => $request->philhealth_no,
            'sss_no' => $request->sss_no,
            'pagibig_no' => $request->pagibig_no,
            'tin_no' => $request->tin_no,
        ]);
    
        // Auto-create user account
        User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'username' => $employeeId,
            'email' => $employeeId, // use employeeId as both email and username if required
            'password' => bcrypt($employeeId),
            'role' => 'employee',
            'is_active' => 1,
        ]);        
    
        return redirect()->route('admin.hr.employees.index')->with('success', 'Employee and user account created.');
    }    

    public function storeSalary(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'remarks' => 'nullable|string|max:255',
        ]);

        // Ensure only one active salary per employee
        if ($request->status === 'active') {
            EmployeeSalary::where('employee_id', $id)->update(['status' => 'inactive']);
        }

        EmployeeSalary::create([
            'employee_id' => $id,
            'amount' => $request->amount,
            'rate_type' => 'fixed',
            'status' => $request->status,
            'remarks' => $request->remarks,
        ]);

        return back()->with('success', 'Salary record added.');
    }

}
