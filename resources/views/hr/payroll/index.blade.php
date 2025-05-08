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
                            <th>Total Hours</th>
                            <th>Overtime (min)</th>
                            <th>Late (min)</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groupedEmployees as $employee)
                        @php
                            $clock = $clockings[$employee->id] ?? null;
                        @endphp
                        <tr>
                            <td>{{ $employee->employee_id }}</td>
                            <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                            <td>{{ $clock->total_hours ?? 0 }}</td>
                            <td>{{ $clock->total_overtime ?? 0 }}</td>
                            <td>{{ $clock->total_late ?? 0 }}</td>
                            <td class="text-end">
                                <a href="{{ url('admin/hr/payroll/compute/' . $employee->id) }}?cutoff={{ $cutoff }}&month={{ $month }}"
                                    class="btn btn-sm btn-warning me-1">
                                    <i class="fas fa-calculator"></i> Compute
                                </a>
                                <a href="{{ url('admin/hr/payroll/payslip/' . $employee->id) }}?cutoff={{ $cutoff }}&month={{ $month }}"
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-receipt"></i> Generate Payslip
                                </a>
                            </td>
                        </tr>
                        @endforeach

                        @if ($groupedEmployees->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center">No employees found for this department.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach

    </div>
</div>
@endsection
