<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Clocking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));

        // Simply load the cutoff selection page
        return view('hr.payroll.cutoffs', compact('month'));
    }

    public function view(Request $request)
    {
        $cutoff = $request->get('cutoff', '1-15');
        $month = $request->get('month', now()->format('Y-m'));

        // Parse cutoff range
        [$start, $end] = $cutoff === '16-30'
            ? [Carbon::parse("$month-16"), Carbon::parse("$month")->endOfMonth()]
            : [Carbon::parse("$month-01"), Carbon::parse("$month-15")];

        // Fetch summarized clocking data
        $clockings = Clocking::select(
                'employee_id',
                DB::raw('SUM(hours_worked) as total_hours'),
                DB::raw('SUM(overtime_minutes) as total_overtime'),
                DB::raw('SUM(late_minutes) as total_late')
            )
            ->whereBetween('created_at', [$start->startOfDay(), $end->endOfDay()])
            ->groupBy('employee_id')
            ->get()
            ->keyBy('employee_id');

        $employees = Employee::all();

        return view('hr.payroll.index', compact(
            'employees',
            'clockings',
            'cutoff',
            'month',
            'start',
            'end'
        ));
    }

    public function compute($id, Request $request)
    {
        $cutoff = $request->get('cutoff');
        $monthString = $request->get('month'); // expected: '2025-05'
    
        $employee = Employee::with('activeSalary')->findOrFail($id);
    
        // Parse base date from string for correct manipulation
        $month = $request->get('month');
        $monthBase = Carbon::createFromFormat('Y-m', $month);
    
        // Define cutoff start and end dates using correct month
        if ($cutoff === '16-30') {
            $start = $monthBase->copy()->day(16)->startOfDay();
            $end = $monthBase->copy()->endOfMonth()->endOfDay();
        } else {
            $start = $monthBase->copy()->day(1)->startOfDay();
            $end = $monthBase->copy()->day(15)->endOfDay();
        }
    
        // Get all working days (Mon–Fri)
        $workingDays = collect();
        $date = $start->copy();
        while ($date->lte($end)) {
            if (!in_array($date->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) {
                $workingDays->push($date->copy());
            }
            $date->addDay();
        }
    
        // Get clocked in dates
        $attendanceDays = Clocking::where('employee_id', $id)
            ->whereBetween('time_in', [$start, $end])
            ->selectRaw('DATE(time_in) as date')
            ->distinct()
            ->pluck('date')
            ->map(fn($d) => Carbon::parse($d));

    
        // Count absent days by comparing
        $attendanceDates = $attendanceDays->map(fn($d) => $d->format('Y-m-d'));

        $daysAbsent = $workingDays->filter(function ($day) use ($attendanceDates) {
            return !$attendanceDates->contains($day->format('Y-m-d'));
        });
    
        // Tardiness (sum of late_minutes)
        $totalLateMinutes = Clocking::where('employee_id', $id)
            ->whereBetween('time_in', [$start, $end])
            ->sum('late_minutes');

        return view('hr.payroll.compute', compact(
            'employee',
            'cutoff',
            'month', // ✅ this is now available as $month in Blade
            'daysAbsent',
            'totalLateMinutes'
        ));    
    }
    

    public function payslip($id, Request $request)
    {
        $cutoff = $request->get('cutoff');
        $month = $request->get('month');

        $employee = Employee::findOrFail($id);

        // Mock payslip data for now
        return view('hr.payroll.payslip', compact('employee', 'cutoff', 'month'));
    }

}
