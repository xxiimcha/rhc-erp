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
                            <tr>
                                <th>Basic Salary</th>
                                <td>{{ number_format($payroll->basic_salary ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Allowance</th>
                                <td>{{ number_format($payroll->allowance ?? 0, 2) }}</td>
                            </tr>
                            <tr class="table-primary">
                                <th>Total Gross Pay</th>
                                <td><strong>{{ number_format($payroll->gross ?? 0, 2) }}</strong></td>
                            </tr>
                        </table>
                    </div>

                    {{-- Deductions --}}
                    <div class="col-md-6">
                        <h6 class="text-danger">Deductions</h6>
                        <table class="table table-bordered mb-4">
                            <tr>
                                <th>SSS</th>
                                <td>{{ number_format($payroll->sss ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <th>PhilHealth</th>
                                <td>{{ number_format($payroll->philhealth ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Pag-IBIG</th>
                                <td>{{ number_format($payroll->pagibig ?? 0, 2) }}</td>
                            </tr>
                            <tr class="table-danger">
                                <th>Total Deductions</th>
                                <td><strong>{{ number_format(($payroll->sss ?? 0) + ($payroll->philhealth ?? 0) + ($payroll->pagibig ?? 0), 2) }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Net Pay --}}
                <div class="text-end">
                    <h4 class="text-dark">Net Pay: 
                        <span class="text-success">{{ number_format($payroll->net_pay ?? 0, 2) }}</span>
                    </h4>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
