<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Clocking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\HistoricalPayroll;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));

        return view('hr.payroll.cutoffs', compact('month'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'payroll_file' => 'required|mimes:xlsx,xls',
        ]);
    
        $file = $request->file('payroll_file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $unmatched = [];
        $inserted = 0;
    
        // Helper to fetch correct value from multiple possible keys
        function getValue($rowData, $possibleKeys) {
            foreach ($possibleKeys as $key) {
                if (isset($rowData[$key]) && trim($rowData[$key]) !== '' && trim($rowData[$key]) !== '-') {
                    return floatval(str_replace(',', '', $rowData[$key]));
                }
            }
            return 0.00;
        }
    
        foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
            $sheetTitle = $sheet->getTitle();
            $highestRow = $sheet->getHighestRow();
            Log::info("Processing sheet: $sheetTitle");
    
            // Read headers from row 7
            $headers = [];
            $col = 1;
            while (true) {
                $columnLetter = Coordinate::stringFromColumnIndex($col);
                $value = trim((string) $sheet->getCell($columnLetter . '7')->getValue());
                if ($value === '') break;
                $headers[] = $value;
                $col++;
            }
    
            $map = array_flip($headers);
            Log::info("Headers found:", $headers);
            Log::info("Mapped indices:", $map);
    
            for ($row = 8; $row <= $highestRow; $row++) {
                $rowData = [];
    
                foreach ($map as $key => $colIndex) {
                    $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
                    $cellValue = trim((string) $sheet->getCell($colLetter . $row)->getCalculatedValue()); // fix here
                    $rowData[$key] = $cellValue;
                }
                
                // Filter junk footer and headers
                $staffCell = strtolower(trim($rowData['Staff'] ?? ''));
                if (
                    $staffCell === '' ||
                    str_contains($staffCell, 'prepared') ||
                    str_contains($staffCell, 'verified') ||
                    str_contains($staffCell, 'approved') ||
                    str_contains($staffCell, 'total')
                ) {
                    continue;
                }
    
                $nameRaw = trim($rowData['Staff']);
                $nameParts = explode(',', $nameRaw);
                $lastName = strtoupper(trim($nameParts[0] ?? ''));
                $firstName = strtoupper(trim(preg_replace('/[^A-Za-z ]/', '', $nameParts[1] ?? '')));
    
                $employee = Employee::whereRaw('UPPER(first_name) LIKE ?', ['%' . $firstName . '%'])
                    ->whereRaw('UPPER(last_name) LIKE ?', ['%' . $lastName . '%'])
                    ->first();
    
                if (!$employee) {
                    Log::warning("Unmatched employee name: {$nameRaw} on sheet {$sheetTitle}");
                    $unmatched[] = $nameRaw;
                    continue;
                }
    
                HistoricalPayroll::create([
                    'employee_id'    => $employee->id,
                    'employee_name'  => $nameRaw,
                    'department'     => $sheetTitle,
                    'basic_salary'   => getValue($rowData, ['Basic Salary']),
                    'allowance'      => getValue($rowData, ['Allowance']),
                    'gross'          => getValue($rowData, ['Gross']),
                    'sss'            => getValue($rowData, ['SSS']),
                    'philhealth'     => getValue($rowData, ['PhilHealth', 'Philhealth', 'Phil Health']),
                    'pagibig'        => getValue($rowData, ['Pagibig', 'Pag-ibig']),
                    'net_pay'        => getValue($rowData, ['Net Pay', 'NetPay']),
                    'sheet'          => $sheetTitle,
                    'cutoff'         => 'Dec 15',
                    'period'         => '2024-12-15',
                ]);
    
                $inserted++;
            }
        }
    
        return redirect()->back()->with([
            'success' => "Historical payroll imported successfully. Inserted: $inserted",
            'unmatched' => $unmatched,
        ]);
    }
    

    public function view(Request $request)
    {
        $cutoff = $request->get('cutoff', '1-15');
        $month = $request->get('month', now()->format('Y-m'));

        [$start, $end] = $cutoff === '16-30'
            ? [Carbon::parse("$month-16"), Carbon::parse("$month")->endOfMonth()]
            : [Carbon::parse("$month-01"), Carbon::parse("$month-15")];

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
        $monthString = $request->get('month');
        $employee = Employee::with('activeSalary')->findOrFail($id);

        $monthBase = Carbon::createFromFormat('Y-m', $monthString);

        if ($cutoff === '16-30') {
            $start = $monthBase->copy()->day(16)->startOfDay();
            $end = $monthBase->copy()->endOfMonth()->endOfDay();
        } else {
            $start = $monthBase->copy()->day(1)->startOfDay();
            $end = $monthBase->copy()->day(15)->endOfDay();
        }

        $workingDays = collect();
        $date = $start->copy();
        while ($date->lte($end)) {
            if (!in_array($date->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) {
                $workingDays->push($date->copy());
            }
            $date->addDay();
        }

        $attendanceDays = Clocking::where('employee_id', $id)
            ->whereBetween('time_in', [$start, $end])
            ->selectRaw('DATE(time_in) as date')
            ->distinct()
            ->pluck('date')
            ->map(fn($d) => Carbon::parse($d));

        $attendanceDates = $attendanceDays->map(fn($d) => $d->format('Y-m-d'));

        $daysAbsent = $workingDays->filter(function ($day) use ($attendanceDates) {
            return !$attendanceDates->contains($day->format('Y-m-d'));
        });

        $totalLateMinutes = Clocking::where('employee_id', $id)
            ->whereBetween('time_in', [$start, $end])
            ->sum('late_minutes');

        return view('hr.payroll.compute', compact(
            'employee',
            'cutoff',
            'month',
            'daysAbsent',
            'totalLateMinutes'
        ));
    }

    public function payslip($id, Request $request)
    {
        $cutoff = $request->get('cutoff');
        $month = $request->get('month');
        $employee = Employee::findOrFail($id);

        return view('hr.payroll.payslip', compact('employee', 'cutoff', 'month'));
    }
}
