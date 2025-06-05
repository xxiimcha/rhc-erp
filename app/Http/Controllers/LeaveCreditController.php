<?php
namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LeaveBalance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LeaveCreditController extends Controller
{
    public function allocateCreditsForCurrentYear()
    {
        $year = Carbon::now()->year;

        $leaveTypes = [
            'Vacation'  => 5,
            'Sick'      => 5,
            'Emergency' => 5,
            'Birthday'  => 1,
        ];

        $employees = Employee::all();
        $newlyAllocated = 0;

        foreach ($employees as $employee) {
            $exists = LeaveBalance::where('employee_id', $employee->id)
                        ->where('year', $year)
                        ->exists();

            if (!$exists) {
                $hireAnniversary = Carbon::parse($employee->date_hired)->startOfDay();

                // Make sure they were hired in or before current year
                if ($hireAnniversary->year <= $year) {
                    foreach ($leaveTypes as $type => $credits) {
                        LeaveBalance::create([
                            'employee_id'      => $employee->id,
                            'year'             => $year,
                            'leave_type'       => $type,
                            'leaves_taken'     => 0,
                            'remaining_leaves' => $credits,
                        ]);
                    }
                    $newlyAllocated++;
                }
            }
        }

        return response()->json([
            'message' => "Leave credits allocated for {$newlyAllocated} employee(s) for {$year}."
        ]);
    }
}
