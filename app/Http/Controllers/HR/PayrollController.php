<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Clocking;
use Carbon\Carbon;
use DB;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $cutoff = $request->get('cutoff');

        // If no cutoff, show only the cutoff periods for the selected month
        if (!$cutoff) {
            return view('hr.payroll.cutoffs', compact('month'));
        }

        return $this->view($request);
    }

    public function view(Request $request)
    {
        $cutoff = $request->get('cutoff', '1-15');
        $month = $request->get('month', now()->format('Y-m'));

        // Determine cutoff range
        [$start, $end] = $cutoff === '16-30'
            ? [Carbon::parse("$month-16"), Carbon::parse($month)->endOfMonth()]
            : [Carbon::parse("$month-01"), Carbon::parse("$month-15")];

        // Get summarized clocking data
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

        return view('hr.payroll.index', compact('employees', 'clockings', 'cutoff', 'month', 'start', 'end'));
    }
}
