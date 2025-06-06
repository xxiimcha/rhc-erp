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
        $filter = $request->input('filter'); // Accepts: daily, weekly, monthly
        $date = $request->input('date', Carbon::now()->toDateString());
        $year = Carbon::parse($date)->year;
    
        // Base query with eager load
        $query = Clocking::with('employee')->orderBy('clock_date', 'desc');
    
        // Apply filter using clock_date
        switch ($filter) {
            case 'daily':
            case 'weekly':
                if ($request->filled(['date_from', 'date_to'])) {
                    $query->whereBetween('clock_date', [
                        Carbon::parse($request->date_from),
                        Carbon::parse($request->date_to)
                    ]);
                }
                break;
        
            case 'monthly':
                if ($request->filled(['month_from', 'month_to'])) {
                    $from = Carbon::parse($request->month_from)->startOfMonth();
                    $to = Carbon::parse($request->month_to)->endOfMonth();
                    $query->whereBetween('clock_date', [$from, $to]);
                }
                break;
        
            case null:
            case '':
                if ($request->filled('date')) {
                    $query->whereDate('clock_date', Carbon::parse($request->date));
                }
                break;
        
            default:
                $query->whereDate('clock_date', $date); // fallback
                break;
        }        
    
        // Fetch data
        $attendances = $query->get();
        $employees = Employee::orderBy('first_name')->get();
        $holidays = $this->getPhilippineHolidays($year);
    
        return view('hr.attendance.index', [
            'attendances' => $attendances,
            'selectedDate' => $date,
            'employees' => $employees,
            'holidays' => $holidays,
            'filter' => $filter
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
            'date' => 'required|date',
            'time_in' => 'required|date',
            'time_out' => 'nullable|date|after_or_equal:time_in',
        ]);
    
        $employeeId = $request->employee_id;
        $date = Carbon::parse($request->date)->toDateString();
        $timeIn = Carbon::parse($request->time_in, 'Asia/Manila');
        $timeOut = $request->time_out ? Carbon::parse($request->time_out, 'Asia/Manila') : null;
    
        $employee = Employee::where('employee_id', $employeeId)->first();
        $rfid = $employee && $employee->rfid_number ? $employee->rfid_number : '-';
    
        $standardIn = Carbon::parse($date . ' 08:00:00', 'Asia/Manila');
        $grace = Carbon::parse($date . ' 08:05:00', 'Asia/Manila');
        $standardOut = Carbon::parse($date . ' 17:00:00', 'Asia/Manila');
    
        $status = $timeIn->gt($grace) ? 'late' : 'on-time';
        $late = $status === 'late' ? $timeIn->diffInMinutes($standardIn) : 0;
        $overtime = $timeOut && $timeOut->gt($standardOut) ? $timeOut->diffInMinutes($standardOut) : 0;
        $workStart = $timeIn->lt($standardIn) ? $standardIn : $timeIn;
        $worked = $timeOut ? round($timeOut->diffInMinutes($workStart) / 60, 2) : null;
    
        $record = Clocking::where('employee_id', $employeeId)
            ->whereDate('clock_date', $date)
            ->first();
    
        if ($record) {
            $record->update([
                'time_in' => $timeIn,
                'time_out' => $timeOut,
                'status' => $status,
                'late_minutes' => $late,
                'overtime_minutes' => $overtime,
                'hours_worked' => $worked,
                'clock_date' => $date,
                'rfid_number' => $rfid, // Update rfid if needed
            ]);
        } else {
            Clocking::create([
                'employee_id' => $employeeId,
                'rfid_number' => $rfid,
                'photo_path' => null,
                'time_in' => $timeIn,
                'time_out' => $timeOut,
                'status' => $status,
                'late_minutes' => $late,
                'overtime_minutes' => $overtime,
                'hours_worked' => $worked,
                'clock_date' => $date,
            ]);
        }
    
        return redirect()->back()->with('success', 'Attendance entry saved successfully.');
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
