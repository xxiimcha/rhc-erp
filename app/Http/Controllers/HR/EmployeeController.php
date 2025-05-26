<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Clocking;
use App\Models\Employee;
use App\Models\EmployeeSalary;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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

        // Get selected year and month from query parameters
        $selectedYear = request()->input('year', now()->format('Y'));
        $selectedMonth = request()->input('month', now()->format('m'));

        // Safely combine into a valid date string
        $monthDate = \Carbon\Carbon::createFromFormat('Y-m', $selectedYear . '-' . $selectedMonth);

        // Attendance records for the selected month
        $clockings = Clocking::where('employee_id', $employee->employee_id)
            ->whereYear('time_in', $monthDate->year)
            ->whereMonth('time_in', $monthDate->month)
            ->orderBy('time_in', 'asc')
            ->get()
            ->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item->time_in)->format('Y-m-d');
            });

        // Fetch holidays if applicable (optional)
        $holidays = []; // Replace with your logic if needed

        return view('hr.employees.show', [
            'employee' => $employee,
            'clockings' => $clockings,
            'month' => $monthDate->format('Y-m'),
            'holidays' => $holidays,
            'selectedYear' => $selectedYear,
            'selectedMonth' => $selectedMonth,
        ]);
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
    
        $year = date('y', strtotime($request->date_hired)); // last 2 digits
        $count = Employee::whereYear('date_hired', '20' . $year)->count() + 1;
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

    public function import(Request $request)
    {
        $request->validate([
            'employee_excel' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('employee_excel');
        $spreadsheet = IOFactory::load($file);
        $rows = $spreadsheet->getActiveSheet()->toArray();

        $skipped = 0;
        $imported = 0;

        // Name parser helper
        function parseFullName($fullName) {
            $fullName = trim(preg_replace('/\s+/', ' ', strtoupper($fullName)));
            $nameParts = explode(' ', $fullName);
            $lastName = array_pop($nameParts);

            $middleName = '';
            $firstName = '';

            foreach (array_reverse($nameParts) as $i => $part) {
                if (preg_match('/^[A-Z]\.$/', $part)) {
                    $middleName = $part;
                    unset($nameParts[count($nameParts) - 1 - $i]);
                    break;
                }
            }

            $firstName = implode(' ', $nameParts);

            return [
                'first_name' => trim($firstName),
                'middle_name' => trim($middleName),
                'last_name' => trim($lastName),
            ];
        }

        foreach (array_slice($rows, 1) as $row) {
            $fullName   = trim($row[0]);
            $position   = $row[1];
            $department = $row[2];
            $sss        = $row[3];
            $tin        = $row[4];
            $pagibig    = $row[5];
            $address    = $row[6];

            $dob = is_numeric($row[7])
                ? Date::excelToDateTimeObject($row[7])->format('Y-m-d')
                : date('Y-m-d', strtotime($row[7]));

            $hired = is_numeric($row[8])
                ? Date::excelToDateTimeObject($row[8])->format('Y-m-d')
                : date('Y-m-d', strtotime($row[8]));

            $parsed = parseFullName($fullName);

            $exists = Employee::where('first_name', $parsed['first_name'])
                        ->where('last_name', $parsed['last_name'])
                        ->where('date_of_birth', $dob)
                        ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            $year = date('y', strtotime($hired));
            $count = Employee::whereYear('date_hired', '20' . $year)->count() + 1;
            $employeeId = $year . str_pad($count, 3, '0', STR_PAD_LEFT);

            $employee = Employee::create([
                'employee_id'      => $employeeId,
                'first_name'       => $parsed['first_name'],
                'middle_name'      => $parsed['middle_name'],
                'last_name'        => $parsed['last_name'],
                'address'          => $address,
                'position'         => $position,
                'department'       => $department,
                'date_of_birth'    => $dob,
                'date_hired'       => $hired,
                'sss_no'           => $sss,
                'pagibig_no'       => $pagibig,
                'tin_no'           => $tin,
                'philhealth_no'    => null,
                'email'            => strtolower(Str::slug($employeeId)) . '@rhc-erp.local',
                'contact_number'   => '',
                'gender'           => '',
                'employment_type'  => 'Regular',
            ]);

            User::create([
                'name'      => $parsed['first_name'] . ' ' . $parsed['last_name'],
                'username'  => $employeeId,
                'email'     => $employeeId,
                'password'  => bcrypt($employeeId),
                'role'      => 'employee',
                'is_active' => 1,
            ]);

            $imported++;
        }

        return back()->with('success', "$imported employee(s) imported. $skipped skipped.");
    }

    public function uploadPhoto(Request $request, $id)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        $employee = Employee::findOrFail($id);
    
        // Delete old photo if exists
        if ($employee->photo_path && file_exists(public_path('employees/' . $employee->photo_path))) {
            unlink(public_path('employees/' . $employee->photo_path));
        }
    
        // Store the file inside /public/employees/
        $filename = uniqid('emp_') . '.' . $request->file('photo')->getClientOriginalExtension();
        $request->file('photo')->move(public_path('employees'), $filename);
    
        $employee->update([
            'photo_path' => $filename,
        ]);
    
        return back()->with('success', 'Profile photo updated successfully.');
    }
    
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        return view('hr.employees.edit', compact('employee'));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'position' => 'required',
            'department' => 'required',
            'address' => 'required',
        ]);

        $employee->update($request->only([
            'first_name', 'middle_name', 'last_name', 'suffix', 'position', 'department', 'address'
        ]));

        return redirect()->route('admin.hr.employees.index')->with('success', 'Employee updated successfully.');
    }

    public function toggle($id)
    {
        $salary = EmployeeSalary::findOrFail($id);

        // Set all other salaries of the employee to inactive
        EmployeeSalary::where('employee_id', $salary->employee_id)
            ->where('id', '!=', $salary->id)
            ->update(['status' => 'inactive']);

        // Set this one to active
        $salary->status = 'active';
        $salary->save();

        return back()->with('success', 'Salary status updated.');
    }
}
