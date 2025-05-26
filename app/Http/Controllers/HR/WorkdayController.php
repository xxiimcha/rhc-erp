<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\EmployeeWorkday;

class WorkdayController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        $workdays = \App\Models\EmployeeWorkday::all()->groupBy('employee_id');
    
        return view('hr.workdays.index', compact('employees', 'workdays'));
    }

    public function store(Request $request)
    {
        $rows = $request->input('rows');
        $submitIndex = $request->input('submit_index');
    
        if (!isset($rows[$submitIndex]) || !is_array($rows[$submitIndex])) {
            return back()->with('error', 'Invalid form data.');
        }
    
        $data = $rows[$submitIndex];
    
        $employeeId = $data['employee_id'] ?? null;
        $workdays = $data['workdays'] ?? [];
        $shiftTime = $data['shift_time'] ?? null;
    
        if (!$employeeId || empty($workdays) || !$shiftTime) {
            return back()->with('error', 'Please complete all required fields.');
        }
    
        foreach ($workdays as $day) {
            $exists = \App\Models\EmployeeWorkday::where('employee_id', $employeeId)
                ->where('day_of_week', $day)
                ->exists();
    
            if (!$exists) {
                \App\Models\EmployeeWorkday::create([
                    'employee_id' => $employeeId,
                    'day_of_week' => $day,
                    'shift_time' => $shiftTime,
                    'updated_by' => auth()->id(),
                ]);
            }
        }
    
        return back()->with('success', 'Workdays assigned successfully.');
    }
    
    public function destroy($id)
    {
        $workday = EmployeeWorkday::findOrFail($id);
        $workday->delete();

        return back()->with('success', 'Workday removed successfully.');
    }
}
