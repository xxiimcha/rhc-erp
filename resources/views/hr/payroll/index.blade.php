@extends('layouts.admin')

@section('title', 'Payroll Summary')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="row mb-4">
            <div class="col-sm-6">
                <h4 class="page-title">Payroll Summary ({{ $cutoff }} | {{ \Carbon\Carbon::parse($month)->format('F Y') }})</h4>
            </div>
            <div class="col-sm-6 text-sm-end">
                <a href="{{ route('admin.hr.payroll.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to Cutoffs
                </a>
            </div>
        </div>

        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @php
            $grouped = $employees->groupBy('department');
            $cutoffDate = \Carbon\Carbon::parse($month . '-01'); // reference date for filtering
        @endphp

        @foreach ($grouped as $department => $groupedEmployees)
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <strong>{{ $department ?? 'No Department' }}</strong>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Full Name</th>
                            <th>Gross Pay</th>
                            <th>Total Deduction</th>
                            <th>Net Pay</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $validEmployees = $groupedEmployees->filter(function ($employee) use ($cutoffDate) {
                                return \Carbon\Carbon::parse($employee->date_hired)->lessThanOrEqualTo($cutoffDate);
                            });
                        @endphp

                        @forelse ($validEmployees as $employee)
                        @php
                            $payroll = $historicalPayrolls[$employee->id] ?? null;
                        @endphp
                        <tr>
                            <td>{{ $employee->employee_id }}</td>
                            <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                            <td>{{ number_format($payroll->gross ?? 0, 2) }}</td>
                            <td>{{ number_format(($payroll->sss ?? 0) + ($payroll->philhealth ?? 0) + ($payroll->pagibig ?? 0), 2) }}</td>
                            <td>{{ number_format($payroll->net_pay ?? 0, 2) }}</td>
                            <td class="text-end">
                                @if (!$payroll)
                                <a href="{{ url('admin/hr/payroll/compute/' . $employee->id) }}?cutoff={{ $cutoff }}&month={{ $month }}"
                                    class="btn btn-sm btn-warning me-1">
                                    <i class="fas fa-calculator"></i> Compute
                                </a>
                                @endif
                                <a href="{{ url('admin/hr/payroll/payslip/' . $employee->id) }}?cutoff={{ $cutoff }}&month={{ $month }}"
                                class="btn btn-sm btn-info">
                                    <i class="fas fa-receipt"></i> Generate Payslip
                                </a>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No employees eligible for this cutoff period.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach

    </div>
</div>
@endsection
