<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Clocking;
use App\Models\Employee;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::now()->toDateString());
        $year = Carbon::parse($date)->year;

        // Fetch attendances
        $attendances = Clocking::with('employee')
            ->whereDate('created_at', $date)
            ->orderBy('created_at', 'desc')
            ->get();

        // Fetch all employees
        $employees = Employee::orderBy('first_name')->get();

        // Fetch Philippine holidays via public API
        $holidays = $this->getPhilippineHolidays($year);

        return view('hr.attendance.index', [
            'attendances' => $attendances,
            'selectedDate' => $date,
            'employees' => $employees,
            'holidays' => $holidays
        ]);
    }

    public function edit($id)
    {
        $record = Clocking::with('employee')->findOrFail($id);
        return view('hr.attendance.edit', compact('record'));
    }

    public function storeManual(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'time_in' => 'required|date',
            'time_out' => 'nullable|date|after_or_equal:time_in',
        ]);

        $timezone = 'Asia/Manila';

        $in = Carbon::parse($request->time_in, $timezone);
        $out = $request->time_out ? Carbon::parse($request->time_out, $timezone) : null;

        $date = $in->toDateString();
        $startTime = Carbon::parse($date . ' 08:00:00', $timezone);
        $graceTime = Carbon::parse($date . ' 08:05:00', $timezone);
        $endTime = Carbon::parse($date . ' 17:00:00', $timezone);

        $status = $in->gt($graceTime) ? 'late' : 'on-time';
        $late = $status === 'late' ? $in->diffInMinutes($startTime) : 0;
        $overtime = $out && $out->gt($endTime) ? $out->diffInMinutes($endTime) : 0;

        // If early, work starts at 8AM. Otherwise, actual time in.
        $workStart = $in->lt($startTime) ? $startTime : $in;
        $worked = $out ? round(($out->diffInMinutes($workStart)) / 60, 2) : null;

        Clocking::create([
            'employee_id' => $request->employee_id,
            'rfid_number' => '-', // manual entry
            'time_in' => $in,
            'time_out' => $out,
            'status' => $status,
            'late_minutes' => $late,
            'overtime_minutes' => $overtime,
            'hours_worked' => $worked
        ]);

        return redirect()->back()->with('success', 'Manual attendance entry added.');
    }

    // Fetch PH holidays from API and return as a keyed collection
    private function getPhilippineHolidays($year)
    {
        try {
            return cache()->remember("holidays_{$year}_PH", 86400, function () use ($year) {
                $response = Http::get("https://date.nager.at/api/v3/PublicHolidays/{$year}/PH");
                if ($response->successful()) {
                    return collect($response->json())->mapWithKeys(function ($holiday) {
                        return [
                            $holiday['date'] => [
                                'name' => $holiday['name'],
                                'localName' => $holiday['localName'],
                                'type' => $holiday['types'][0] ?? 'Public'
                            ]
                        ];
                    });
                }
                return collect();
            });
        } catch (\Exception $e) {
            return collect();
        }
    }
}
