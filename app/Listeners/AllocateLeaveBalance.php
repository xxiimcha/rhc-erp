<?php
namespace App\Listeners;

use Illuminate\Auth\Events\Authenticated;
use App\Models\Employee;
use App\Models\LeaveBalance;
use Carbon\Carbon;

class AllocateLeaveBalance
{
    public function handle(Authenticated $event)
    {
        $user = $event->user;

        // Match the employee record by email or a proper identifier
        $employee = Employee::where('email', $user->email)->first();

        if (!$employee || !$employee->employee_id || !$employee->date_hired) {
            return;
        }

        $today = Carbon::now();
        $hiredAnniversaryThisYear = Carbon::parse($employee->date_hired)->setYear($today->year);

        // Optional: only proceed if today >= anniversary
        if ($today->lt($hiredAnniversaryThisYear)) {
            return;
        }

        $year = $today->year;

        // Check if this employee already has balances for this year
        $alreadyAllocated = LeaveBalance::where('employee_id', $employee->employee_id)
            ->where('year', $year)
            ->exists();

        if ($alreadyAllocated) return;

        // Allocate initial leave balances
        $leaveTypes = [
            'Vacation'  => 5,
            'Sick'      => 5,
            'Emergency' => 5,
            'Birthday'  => 1,
        ];

        foreach ($leaveTypes as $type => $credit) {
            LeaveBalance::create([
                'employee_id'      => $employee->employee_id,
                'year'             => $year,
                'leave_type'       => $type,
                'leaves_taken'     => 0,
                'remaining_leaves' => $credit,
            ]);
        }
    }
}
