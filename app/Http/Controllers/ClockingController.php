<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clocking;
use App\Models\Employee;
use Carbon\Carbon;

class ClockingController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'rfid' => 'required|string'
        ]);

        $rfid = $request->rfid;
        $employee = Employee::where('rfid_number', $rfid)->first();

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.'
            ]);
        }

        $now = Carbon::now('Asia/Manila');
        $today = $now->toDateString();

        $startTime = Carbon::parse($today . ' 08:00:00', 'Asia/Manila');
        $graceTime = Carbon::parse($today . ' 08:05:00', 'Asia/Manila');
        $endTime = Carbon::parse($today . ' 17:00:00', 'Asia/Manila');

        // Check if this employee has a clocking record today
        $clock = Clocking::where('employee_id', $employee->employee_id)
            ->whereDate('created_at', $today)
            ->first();

        $status = 'on-time';
        $lateMinutes = 0;
        $overtimeMinutes = 0;

        if (!$clock) {
            // First scan = Clock In
            if ($now->greaterThan($graceTime)) {
                $status = 'late';
                $lateMinutes = $now->diffInMinutes($startTime);
            }

            $clock = Clocking::create([
                'employee_id' => $employee->employee_id,
                'rfid_number' => $rfid,
                'time_in' => $now,
                'status' => $status,
                'late_minutes' => $lateMinutes
            ]);
        } else {
            // Second scan = Clock Out
            $clock->time_out = $now;

            if ($now->greaterThan($endTime)) {
                $overtimeMinutes = $now->diffInMinutes($endTime);
                $clock->overtime_minutes = $overtimeMinutes;
            }

            $clock->save();
        }

        return response()->json([
            'success' => true,
            'employee' => [
                'name' => "{$employee->first_name} {$employee->last_name}",
                'position' => $employee->position
            ],
            'last_in' => $clock->time_in ? Carbon::parse($clock->time_in)->format('h:i A') : '-',
            'last_out' => $clock->time_out ? Carbon::parse($clock->time_out)->format('h:i A') : '-',
            'status' => ucfirst($clock->status),
            'late_minutes' => $clock->late_minutes ?? 0,
            'overtime_minutes' => $clock->overtime_minutes ?? 0
        ]);
    }
}
