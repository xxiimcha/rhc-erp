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

            // Read headers only until "Net Pay" is reached
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
                if ($col > 100) break; // safety stop
            }

            Log::info("Sheet '$sheetTitle' headers: " . json_encode($rawHeaderNames));
            if ($foundNetPay) {
                Log::info("Sheet '$sheetTitle' matched Net Pay header.");
            } else {
                Log::warning("Sheet '$sheetTitle' has NO recognizable Net Pay header.");
            }
            Log::debug("Normalized header keys for '$sheetTitle': " . implode(', ', array_keys($headers)));

            $processedRows = 0;
            $rowNumbers = [];

            for ($row = 8; $row <= $highestRow; $row++) {
                $rowData = [];

                foreach ($headers as $normalizedKey => $colIndex) {
                    $colLetter = Coordinate::stringFromColumnIndex($colIndex);
                    $cell = $sheet->getCell($colLetter . $row);
                    $cellValue = $cell->isFormula()
                        ? $cell->getOldCalculatedValue()
                        : $cell->getCalculatedValue();
                    $cellValue = trim((string) $cellValue);
                    $rowData[$normalizedKey] = $cellValue;
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

                $basic = $getVal(['Basic Salary', 'Basic']);
                $allow = $getVal(['Allowance']);
                $gross = $getVal(['Gross', 'Gross Pay', 'Total Gross']);
                $net = $getVal($netPayAliases);
                $sss = $getVal(['SSS']);
                $philhealth = $getVal(['PhilHealth', 'Philhealth', 'Phil Health']);
                $pagibig = $getVal(['Pagibig', 'Pag-ibig']);

                HistoricalPayroll::create([
                    'employee_id'    => $employee->id,
                    'employee_name'  => $nameRaw,
                    'department'     => $sheetTitle,
                    'basic_salary'   => $basic,
                    'allowance'      => $allow,
                    'gross'          => $gross,
                    'sss'            => $sss,
                    'philhealth'     => $philhealth,
                    'pagibig'        => $pagibig,
                    'net_pay'        => $net,
                    'sheet'          => $sheetTitle,
                    'cutoff'         => $request->input('cutoff'),
                    'period'         => $request->input('month') . '-' . ($request->input('cutoff') === '1-15' ? '15' : '30'),
                ]);

                $matchedEntries[] = [
                    'employee_name' => $nameRaw,
                    'basic_salary' => $basic,
                    'allowance' => $allow,
                    'gross' => $gross,
                    'net_pay' => $net,
                    'department' => $sheetTitle
                ];

                $processedRows++;
                $rowNumbers[] = $row;
                $inserted++;
            }

            Log::info("Sheet '$sheetTitle' processed with $processedRows data rows. Rows: [" . implode(', ', $rowNumbers) . "]");
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
    
        // Fetch historical payrolls for the given cutoff/period
        $historicalPayrolls = DB::table('historical_payrolls')
            ->where('cutoff', $cutoff)
            ->where('period', $month . '-' . ($cutoff === '1-15' ? '15' : '30'))
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
        $employee = Employee::with('activeSalary')->findOrFail($id);

        $monthBase = Carbon::createFromFormat('Y-m', $monthString);
        $start = $cutoff === '16-30'
            ? $monthBase->copy()->day(16)->startOfDay()
            : $monthBase->copy()->day(1)->startOfDay();
        $end = $cutoff === '16-30'
            ? $monthBase->copy()->endOfMonth()->endOfDay()
            : $monthBase->copy()->day(15)->endOfDay();

        $workingDays = collect();
        $date = $start->copy();
        while ($date->lte($end)) {
            if (!in_array($date->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) {
                $workingDays->push($date->copy());
            }
            $date->addDay();
        }

        $attendanceDates = Clocking::where('employee_id', $id)
            ->whereBetween('time_in', [$start, $end])
            ->selectRaw('DATE(time_in) as date')
            ->distinct()
            ->pluck('date')
            ->map(fn($d) => Carbon::parse($d)->format('Y-m-d'));

        $daysAbsent = $workingDays->filter(fn($day) => !$attendanceDates->contains($day->format('Y-m-d')));
        $totalLateMinutes = Clocking::where('employee_id', $id)
            ->whereBetween('time_in', [$start, $end])
            ->sum('late_minutes');

        return view('hr.payroll.compute', compact(
            'employee', 'cutoff', 'month', 'daysAbsent', 'totalLateMinutes'
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

    // ✅ Normalize function moved to global scope in class
    private function normalizeKey($string) {
        return strtolower(trim(preg_replace('/[\s\x{00A0}\x{2000}-\x{200B}\x{3000}]/u', ' ', $string)));
    }
}
