@extends('layouts.admin')

@section('title', 'Payslip')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="row mb-4">
            <div class="col-sm-6">
                <h4 class="page-title">Payslip: {{ $employee->first_name }} {{ $employee->last_name }}</h4>
            </div>
            <div class="col-sm-6 text-sm-end">
                <a href="{{ route('admin.hr.payroll.view', ['cutoff' => $cutoff, 'month' => $month]) }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to Summary
                </a>
            </div>
        </div>

        @php
            function formatAmount($val) {
                return ($val ?? 0) == 0 ? '--' : number_format($val, 2);
            }

            $totalDeductions = 
                ($payroll->sss ?? 0) +
                ($payroll->philhealth ?? 0) +
                ($payroll->pagibig ?? 0) +
                ($payroll->tardiness ?? 0) +
                ($payroll->absences ?? 0) +
                ($payroll->others ?? 0);
        @endphp

        <div class="card shadow">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-4">
                    <div>
                        <h5>Employee Information</h5>
                        <p class="mb-1"><strong>Name:</strong> {{ $employee->first_name }} {{ $employee->last_name }}</p>
                        <p class="mb-1"><strong>Employee ID:</strong> {{ $employee->employee_id }}</p>
                        <p class="mb-1"><strong>Department:</strong> {{ $employee->department }}</p>
                    </div>
                    <div class="text-end">
                        <h5>Payroll Period</h5>
                        <p class="mb-1"><strong>Cutoff:</strong> {{ $cutoff }}</p>
                        <p class="mb-1"><strong>Period Date:</strong> {{ $payroll->period }}</p>
                    </div>
                </div>

                <div class="row">
                    {{-- Earnings --}}
                    <div class="col-md-6">
                        <h6 class="text-success">Earnings</h6>
                        <table class="table table-bordered mb-4">
                            <tr><th>Basic Salary</th><td>{{ formatAmount($payroll->basic_salary) }}</td></tr>
                            <tr><th>Allowance</th><td>{{ formatAmount($payroll->allowance) }}</td></tr>
                            <tr><th>Adjustment</th><td>{{ formatAmount($payroll->adjustment) }}</td></tr>
                            <tr><th>OT</th><td>{{ formatAmount($payroll->ot) }}</td></tr>
                            <tr><th>RDOT</th><td>{{ formatAmount($payroll->rdot) }}</td></tr>
                            <tr><th>SH OT</th><td>{{ formatAmount($payroll->sh_ot) }}</td></tr>
                            <tr><th>SH</th><td>{{ formatAmount($payroll->sh) }}</td></tr>
                            <tr><th>LH/RH</th><td>{{ formatAmount($payroll->lh_rh) }}</td></tr>
                            <tr><th>RND</th><td>{{ formatAmount($payroll->rnd) }}</td></tr>
                            <tr class="table-primary">
                                <th>Gross Pay</th>
                                <td><strong>{{ formatAmount($payroll->gross) }}</strong></td>
                            </tr>
                        </table>
                    </div>

                    {{-- Deductions --}}
                    <div class="col-md-6">
                        <h6 class="text-danger">Deductions</h6>
                        <table class="table table-bordered mb-4">
                            <tr><th>SSS</th><td>{{ formatAmount($payroll->sss) }}</td></tr>
                            <tr><th>PhilHealth</th><td>{{ formatAmount($payroll->philhealth) }}</td></tr>
                            <tr><th>Pag-IBIG</th><td>{{ formatAmount($payroll->pagibig) }}</td></tr>
                            <tr><th>Tardiness</th><td>{{ formatAmount($payroll->tardiness) }}</td></tr>
                            <tr><th>Absences</th><td>{{ formatAmount($payroll->absences) }}</td></tr>
                            <tr><th>Other Deductions</th><td>{{ formatAmount($payroll->others) }}</td></tr>
                            <tr class="table-danger">
                                <th>Total Deductions</th>
                                <td><strong>{{ $totalDeductions == 0 ? '--' : number_format($totalDeductions, 2) }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Net Pay --}}
                <div class="text-end">
                    <h4 class="text-dark">Net Pay: 
                        <span class="text-success">{{ formatAmount($payroll->net_pay) }}</span>
                    </h4>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
