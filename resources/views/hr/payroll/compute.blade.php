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
                <form action="" method="POST">
                    @csrf

                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                    <input type="hidden" name="cutoff" value="{{ $cutoff }}">
                    <input type="hidden" name="month" value="{{ $month }}">

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
                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($month)->format('F Y') }}" readonly>
                        </div>
                    </div>

                    <hr>

                                    
                    <!-- Row 1: Basic Info -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label>Basic Pay</label>
                            <input type="number" name="basic_pay" class="form-control" value="{{ $employee->activeSalary->amount ?? 0 }}" required>
                        </div>
                        <div class="col-md-3">
                            <label>Rate/Day</label>
                            <input type="number" name="rate_per_day" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>15-Day Pay</label>
                            <input type="number" name="half_month_pay" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Allowance</label>
                            <input type="number" name="allowance" class="form-control">
                        </div>
                    </div>

                    <!-- Row 2: Adjustments & Attendance -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label>Adjusted OT</label>
                            <input type="number" name="adjusted_ot" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Days Absent</label>
                            <input type="number" name="days_absent" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>RND</label>
                            <input type="number" name="rnd" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Leave Hours</label>
                            <input type="number" name="leave_hours" class="form-control">
                        </div>
                    </div>

                    <!-- Row 3: Rates -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label>Rate/Hour</label>
                            <input type="number" name="rate_per_hour" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>% of Hourly Rate</label>
                            <input type="number" name="percent_hourly_rate" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Restday Pay</label>
                            <input type="number" name="restday_pay" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Special Holiday Pay</label>
                            <input type="number" name="special_holiday_pay" class="form-control">
                        </div>
                    </div>

                    <!-- Row 4: Overtime -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label>Regular OT Pay</label>
                            <input type="number" name="reg_ot_pay" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>RH OT Pay</label>
                            <input type="number" name="rh_ot_pay" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>SH OT Pay</label>
                            <input type="number" name="sh_ot_pay" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Total OT Pay</label>
                            <input type="number" name="total_ot" class="form-control">
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
@endsection
