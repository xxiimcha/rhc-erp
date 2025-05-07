<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

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
        return view('hr.employees.show', compact('employee'));
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
        $employeeId = 'EMP' . $year . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        Employee::create([
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

        return redirect()->route('admin.hr.employees.index')->with('success', 'Employee created.');
    }
}
