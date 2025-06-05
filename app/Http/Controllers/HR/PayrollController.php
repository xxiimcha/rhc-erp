<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Clocking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use App\Models\PayrollSetting;

use App\Models\Payroll;
use App\Models\HistoricalPayroll;

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
        $matchedEntries = [];
        $inserted = 0;
    
        $netPayAliases = ['Net Pay', 'NetPay', 'Netpay', 'Net Pay ', ' Net Pay'];
        $normalizedNetPayAliases = array_map([$this, 'normalizeKey'], $netPayAliases);
    
        foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
            $sheetTitle = $sheet->getTitle();
            $highestRow = $sheet->getHighestRow();
            $headers = [];
            $rawHeaderNames = [];
            $col = 1;
            $foundNetPay = false;
    
            while (true) {
                $columnLetter = Coordinate::stringFromColumnIndex($col);
                $value = (string) $sheet->getCell($columnLetter . '7')->getValue();
                $normalized = $this->normalizeKey($value);
    
                if (trim($value) !== '') {
                    $headers[$normalized] = $col;
                    $rawHeaderNames[] = $value;
    
                    if (in_array($normalized, $normalizedNetPayAliases)) {
                        $foundNetPay = true;
                        break;
                    }
                }
    
                $col++;
                if ($col > 100) break;
            }
    
            Log::info("Sheet '$sheetTitle' headers: " . json_encode($rawHeaderNames));
    
            for ($row = 8; $row <= $highestRow; $row++) {
                $rowData = [];
    
                foreach ($headers as $normalizedKey => $colIndex) {
                    $colLetter = Coordinate::stringFromColumnIndex($colIndex);
                    $cell = $sheet->getCell($colLetter . $row);
                    $cellValue = $cell->isFormula()
                        ? $cell->getOldCalculatedValue()
                        : $cell->getCalculatedValue();
                    $rowData[$normalizedKey] = trim((string) $cellValue);
                }
    
                $staffCell = strtolower(trim($rowData['staff'] ?? ''));
                if (
                    $staffCell === '' ||
                    str_contains($staffCell, 'prepared') ||
                    str_contains($staffCell, 'verified') ||
                    str_contains($staffCell, 'approved') ||
                    str_contains($staffCell, 'total')
                ) {
                    continue;
                }
    
                $nameRaw = trim($rowData['staff']);
                $nameParts = array_map('trim', explode(',', strtoupper($nameRaw)));
                $lastName = $nameParts[0] ?? '';
                $firstName = $nameParts[1] ?? '';
    
                $employee = Employee::whereRaw("CONCAT(UPPER(TRIM(first_name)), ' ', UPPER(TRIM(last_name))) LIKE ?", ["%$firstName $lastName%"])
                    ->orWhereRaw("CONCAT(UPPER(TRIM(last_name)), ', ', UPPER(TRIM(first_name))) LIKE ?", ["%$lastName, $firstName%"])
                    ->first();
    
                if (!$employee) {
                    $unmatched[] = $nameRaw;
                    continue;
                }
    
                $getVal = function ($aliases) use ($rowData) {
                    foreach ($aliases as $alias) {
                        $key = $this->normalizeKey($alias);
                        if (isset($rowData[$key]) && trim($rowData[$key]) !== '' && trim($rowData[$key]) !== '-') {
                            return floatval(str_replace(',', '', $rowData[$key]));
                        }
                    }
                    return 0.00;
                };
    
                HistoricalPayroll::create([
                    'employee_id'       => $employee->id,
                    'employee_name'     => $nameRaw,
                    'department'        => $sheetTitle,
                    'basic_salary'      => $getVal(['Basic Salary', 'Basic']),
                    'allowance'         => $getVal(['Allowance']),
                    'adjustment'        => $getVal(['ADJ', 'Adjustment']),
                    'ot'                => $getVal(['OT']),
                    'rdot'              => $getVal(['RDOT']),
                    'sh_ot'             => $getVal(['SH OT']),
                    'sh'                => $getVal(['SH']),
                    'lh_rh'             => $getVal(['LH/RH']),
                    'rnd'               => $getVal(['RND']),
                    'tardiness'         => $getVal(['Tardiness']),
                    'absences'          => $getVal(['Absences']),
                    'gross'             => $getVal(['Gross', 'Gross Pay']),
                    'sss'               => $getVal(['SSS']),
                    'philhealth'        => $getVal(['PhilHealth', 'Philhealth']),
                    'pagibig'           => $getVal(['Pagibig', 'Pag-ibig']),
                    'others'            => $getVal(['Others']),
                    'total_deduction'   => $getVal(['Total Deduction', 'Total Ded']),
                    'net_pay'           => $getVal($netPayAliases),
                    'sheet'             => $sheetTitle,
                    'cutoff'            => $request->input('cutoff'),
                    'period'            => $this->getValidPeriodDate($request->input('month'), $request->input('cutoff')),

                ]);
    
                $matchedEntries[] = [
                    'employee_name' => $nameRaw,
                    'department'    => $sheetTitle,
                    'gross'         => $getVal(['Gross', 'Gross Pay']),
                    'net_pay'       => $getVal($netPayAliases),
                ];
    
                $inserted++;
            }
        }
    
        return redirect()->back()->with([
            'success' => "Historical payroll imported successfully. Inserted: $inserted",
            'matched' => $matchedEntries,
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
    
        $employees = Employee::all();
    
        // Use accurate period date
        $period = $cutoff === '1-15'
            ? "$month-15"
            : Carbon::createFromFormat('Y-m', $month)->endOfMonth()->format('Y-m-d');
    
        $historicalPayrolls = DB::table('historical_payrolls')
            ->where('cutoff', $cutoff)
            ->where('period', $period)
            ->get()
            ->keyBy('employee_id');
    
        return view('hr.payroll.index', compact(
            'employees', 'cutoff', 'month', 'start', 'end', 'historicalPayrolls'
        ));
    }    

    public function compute($id, Request $request)
    {
        $cutoff = $request->get('cutoff');
        $monthString = $request->get('month');
        $month = $monthString;
    
        $employee = Employee::with('activeSalary')->findOrFail($id);

        $totalGrossPayroll = Payroll::where('employee_id', $employee->id)->sum('total_salary');
        $totalGrossHistorical = HistoricalPayroll::where('employee_id', $employee->id)->sum('gross');
        $totalGross = $totalGrossPayroll + $totalGrossHistorical;
        $thirteenthMonth = $totalGross / 12;

        $employeeNumber = $employee->employee_id; // This is '17001', '25002', etc.
        $settings = PayrollSetting::first();
    
        $monthBase = Carbon::createFromFormat('Y-m', $monthString);
        $start = $cutoff === '16-30'
            ? $monthBase->copy()->day(16)->startOfDay()
            : $monthBase->copy()->day(1)->startOfDay();

        $cutoffEnd = $cutoff === '16-30'
            ? $monthBase->copy()->endOfMonth()->endOfDay()
            : $monthBase->copy()->day(15)->endOfDay();
        
        $today = Carbon::today()->endOfDay();
        $end = $cutoffEnd->lt($today) ? $cutoffEnd : $today;
    
        Log::info("Computing payroll for employee_id={$id} (employee_no={$employeeNumber}), Cutoff={$cutoff}, Date range: {$start} to {$end}");
    
        // Working days (Mon–Fri)
        $workingDays = collect();
        $date = $start->copy();
        while ($date->lte($end)) {
            if (!in_array($date->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) {
                $workingDays->push($date->copy());
            }
            $date->addDay();
        }
    
        $attendanceDates = Clocking::where('employee_id', $employeeNumber)
            ->whereBetween('time_in', [$start, $end])
            ->selectRaw('DATE(time_in) as date')
            ->distinct()
            ->pluck('date')
            ->map(fn($d) => Carbon::parse($d)->format('Y-m-d'));
    
        $daysAbsent = $workingDays->filter(fn($day) => !$attendanceDates->contains($day->format('Y-m-d')));
        $daysAbsentCount = $daysAbsent->count();
        
        $absentDates = $daysAbsent->map(fn($day) => $day->format('Y-m-d'))->toArray();
        
        Log::info("Days absent for employee_id={$employeeNumber}: {$daysAbsentCount}");
        Log::info("Absent dates: ", $absentDates);
    
        $lateEntries = Clocking::where('employee_id', $employeeNumber)
            ->whereBetween('time_in', [$start, $end])
            ->whereNotNull('late_minutes')
            ->get(['id', 'time_in', 'late_minutes']);
    
        $totalLateMinutes = $lateEntries->sum(fn($entry) => abs($entry->late_minutes ?? 0));
    
        Log::info("Days absent for employee_id={$employeeNumber}: {$daysAbsentCount}");
        Log::info("Late entries:", $lateEntries->toArray());
        Log::info("Total late minutes for employee_id={$employeeNumber}: {$totalLateMinutes}");
    
        $attendanceLogs = Clocking::where('employee_id', $employeeNumber)
            ->whereBetween('time_in', [$start, $end])
            ->orderBy('time_in')
            ->get();
    
        return view('hr.payroll.compute', compact(
            'employee',
            'cutoff',
            'month',
            'settings',
            'daysAbsentCount',
            'totalLateMinutes',
            'attendanceLogs',
            'totalGross',
            'thirteenthMonth'
        ));
    }
    
    
    public function payslip($id, Request $request)
    {
        $cutoff = $request->get('cutoff');
        $month = $request->get('month');
        $period = $cutoff === '1-15' ? "$month-15" : "$month-30";

        $employee = Employee::findOrFail($id);

        $payroll = DB::table('payrolls')
            ->where('employee_id', $id)
            ->where('cutoff', $cutoff)
            ->where('period', $period)
            ->first();

        if (!$payroll) {
            $payroll = DB::table('historical_payrolls')
                ->where('employee_id', $id)
                ->where('cutoff', $cutoff)
                ->where('period', $period)
                ->first();
        }

        if (!$payroll) {
            return back()->with('error', 'Payroll record not found.');
        }

        return view('hr.payroll.payslip', compact('employee', 'payroll', 'cutoff', 'month'));
    }

    private function normalizeKey($string) {
        return strtolower(trim(preg_replace('/[\s\x{00A0}\x{2000}-\x{200B}\x{3000}]/u', ' ', $string)));
    }

    private function getValidPeriodDate($month, $cutoff)
    {
        $date = $cutoff === '1-15'
            ? Carbon::createFromFormat('Y-m', $month)->day(15)
            : Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        return $date->format('Y-m-d');
    }

}