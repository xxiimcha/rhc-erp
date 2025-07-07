@extends('layouts.admin')

@section('title', 'Compute Payslip')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Compute Payslip for {{ $employee->first_name }} {{ $employee->last_name }}</h4>
            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <div class="card shadow">
            <div class="card-body">
            <div class="mb-3 text-end">
            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#attendanceModal">
                <i class="fas fa-calendar-alt"></i> View Attendance Logs
            </button>
        </div>

                <form action="{{ route('admin.hr.payroll.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                    <input type="hidden" name="cutoff" value="{{ $cutoff }}">
                    <input type="hidden" name="month" value="{{ $month }}">

                    {{-- Header --}}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>Employee ID</label>
                            <input type="text" class="form-control" value="{{ $employee->employee_id }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label>Cutoff Period</label>
                            <input type="text" class="form-control" value="{{ $cutoff }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label>Month</label>
                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}" readonly>
                        </div>
                    </div>

                    <hr>
                    <h5 class="text-primary">Rate Details</h5>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label>Basic Pay</label>
                            <input type="number" name="basic_pay" class="form-control" value="{{ $employee->activeSalary->amount ?? 0 }}" required>
                        </div>
                        <div class="col-md-3">
                            <label>Rate/Day</label>
                            <input type="number" id="ratePerDay" name="rate_per_day" class="form-control" readonly>
                        </div>
                        <div class="col-md-3">
                            <label>Rate/Hour</label>
                            <input type="number" name="rate_per_hour" class="form-control" readonly>
                        </div>
                        <div class="col-md-3">
                            <label>Rate/OTS</label>
                            <input type="number" name="rate_ots" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label>15-Day Pay</label>
                            <input type="number" id="halfMonthPay" name="half_month_pay" class="form-control" readonly>
                        </div>
                        <div class="col-md-3">
                            <label>% of Hourly Rate</label>
                            <input type="number" name="percent_hourly_rate" class="form-control" readonly>
                        </div>
                        <div class="col-md-3">
                            <label>SH Rate</label>
                            <input type="number" name="sh_rate" class="form-control" readonly>
                        </div>
                        <div class="col-md-3">
                            <label>RH Rate</label>
                            <input type="number" name="rh_rate" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label>Rest Day Rate</label>
                            <input type="number" name="restday_rate" class="form-control" readonly>
                        </div>
                        <div class="col-md-3">
                            <label>RND</label>
                            <input type="number" name="rnd" class="form-control" readonly>
                        </div>
                    </div>

                    <hr>
                    <h5 class="text-success">Actual Computation</h5>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label>Allowance</label>
                            <input type="number" name="allowance" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Adjusted OT</label>
                            <input type="number" name="adjusted_ot" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Days Absent</label>
                            <input type="number" name="days_absent" class="form-control" value="{{ $daysAbsentCount ?? 0 }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label>Tardiness (Minutes)</label>
                            <input type="number" name="tardiness" class="form-control" value="{{ $totalLateMinutes ?? 0 }}" readonly>

                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label>Restday Pay</label>
                            <input type="number" name="restday_pay" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Special Holiday Pay</label>
                            <input type="number" name="special_holiday_pay" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Regular OT Pay</label>
                            <input type="number" name="reg_ot_pay" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>RH OT Pay</label>
                            <input type="number" name="rh_ot_pay" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label>SH OT Pay</label>
                            <input type="number" name="sh_ot_pay" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Total OT Pay</label>
                            <input type="number" name="total_ot" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Pag-IBIG</label>
                            <input type="number" name="pagibig" class="form-control" value="0">
                        </div>
                        <div class="col-md-3">
                            <label>SSS</label>
                            <input type="number" name="sss" class="form-control" value="0">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label>Tardiness Deduction</label>
                            <input type="number" name="tardiness_deduction" class="form-control" readonly>
                        </div>
                        <div class="col-md-3">
                            <label>Total Salary</label>
                            <input type="number" name="total_salary" class="form-control" readonly>
                        </div>
                    </div>

                    <hr>
                    <h5 class="text-warning">13th Month Pay</h5>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label>Total Gross from Payrolls & Historical Payrolls</label>
                            <input type="number" id="displayedGross" class="form-control" value="{{ number_format($totalGross ?? 0, 2, '.', '') }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label>Computed 13th Month Pay</label>
                            <input type="number" name="thirteenth_month" class="form-control" value="{{ number_format($thirteenthMonth ?? 0, 2, '.', '') }}" readonly>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check-circle"></i> Save Computation
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
<script>
    const totalGrossBackend = {{ $totalGross ?? 0 }};
    const payrollSettings = @json($settings);

    function calculateDerivedFields() {
        const basicPay = parseFloat(document.querySelector('[name="basic_pay"]').value) || 0;
        const pagibig = parseFloat(document.querySelector('[name="pagibig"]').value) || 0;
        const sss = parseFloat(document.querySelector('[name="sss"]').value) || 0;
        const allowance = parseFloat(document.querySelector('[name="allowance"]').value) || 0;
        const restdayPay = parseFloat(document.querySelector('[name="restday_pay"]').value) || 0;
        const shPay = parseFloat(document.querySelector('[name="special_holiday_pay"]').value) || 0;
        const rhPay = parseFloat(document.querySelector('[name="rh_ot_pay"]').value) || 0;
        const regOt = parseFloat(document.querySelector('[name="reg_ot_pay"]').value) || 0;
        const shOt = parseFloat(document.querySelector('[name="sh_ot_pay"]').value) || 0;
        const adjustedOt = parseFloat(document.querySelector('[name="adjusted_ot"]').value) || 0;
        const tardinessMinutes = parseFloat(document.querySelector('[name="tardiness"]').value) || 0;
        const daysAbsent = parseFloat(document.querySelector('[name="days_absent"]').value) || 0;

        const workingDays = payrollSettings.monthly_working_days || 21.75;
        const workingHours = payrollSettings.monthly_working_hours || 174;
        const dailyRate = payrollSettings.daily_rate || (basicPay * 12) / payrollSettings.work_days_per_year;
        const ratePerHour = dailyRate / 8;
        const halfMonthPay = basicPay / 2;

        const percentOfHourly = ratePerHour * (payrollSettings.night_diff_percent || 0.1);
        const rndRate = ratePerHour * (payrollSettings.night_diff_percent || 0.1);
        const shRate = dailyRate * (payrollSettings.special_holiday_rate || 0.3);
        const rhRate = ratePerHour * (payrollSettings.regular_holiday_rate || 2.6);
        const rateOTS = (dailyRate * (payrollSettings.rate_ots || 2)) / 8;
        const restDayRate = dailyRate + shRate;

        const tardinessDeduction = (dailyRate / 480) * tardinessMinutes;
        const absentDeduction = daysAbsent * dailyRate;

        const totalEarnings = halfMonthPay + allowance + restdayPay + shPay + rhPay + regOt + shOt + adjustedOt;
        const totalDeductions = tardinessDeduction + absentDeduction + pagibig + sss;
        const totalSalary = totalEarnings - totalDeductions;

        const updatedGross = totalGrossBackend + totalSalary;
        const thirteenthMonth = updatedGross / 12;

        // Set calculated values
        document.getElementById('ratePerDay').value = dailyRate.toFixed(2);
        document.getElementById('halfMonthPay').value = halfMonthPay.toFixed(2);
        document.querySelector('[name="rate_per_hour"]').value = ratePerHour.toFixed(2);
        document.querySelector('[name="percent_hourly_rate"]').value = percentOfHourly.toFixed(2);
        document.querySelector('[name="rnd"]').value = rndRate.toFixed(2);
        document.querySelector('[name="sh_rate"]').value = shRate.toFixed(2);
        document.querySelector('[name="rh_rate"]').value = rhRate.toFixed(2);
        document.querySelector('[name="rate_ots"]').value = rateOTS.toFixed(2);
        document.querySelector('[name="restday_rate"]').value = restDayRate.toFixed(2);
        document.querySelector('[name="tardiness_deduction"]').value = tardinessDeduction.toFixed(2);
        document.querySelector('[name="total_salary"]').value = totalSalary.toFixed(2);

        // Update gross and 13th month
        document.getElementById('displayedGross').value = updatedGross.toFixed(2);
        document.querySelector('[name="thirteenth_month"]').value = thirteenthMonth.toFixed(2);
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', calculateDerivedFields);
        });
        calculateDerivedFields();
    });
</script>


@endsection
