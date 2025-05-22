@extends('layouts.admin')

@section('title', 'My Payroll')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <h4 class="page-title mb-3">Payroll Summary</h4>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between">
                <span>Latest Payslip</span>
            </div>
            <div class="card-body">
                @if($latestPayroll)
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Gross Pay:</strong> <span>₱{{ number_format($latestPayroll->gross, 2) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Deductions:</strong> <span>₱{{ number_format($latestPayroll->deductions, 2) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between text-success fw-bold">
                        <strong>Net Pay:</strong> <span>₱{{ number_format($latestPayroll->net_pay, 2) }}</span>
                    </li>
                </ul>
                <div class="text-end mt-3">
                    <a href="{{ route('employee.payroll.download', $latestPayroll->id) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-download me-1"></i> Download Payslip
                    </a>
                </div>
                @else
                    <p class="text-muted">No payroll record found.</p>
                @endif
            </div>
        </div>

        <h5 class="mb-3">Payslip History</h5>
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Cutoff Period</th>
                            <th>Gross Pay</th>
                            <th>Deductions</th>
                            <th>Net Pay</th>
                            <th>Date Processed</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payrolls as $payroll)
                        <tr>
                            <td>{{ $payroll->cutoff_period }}</td>
                            <td>₱{{ number_format($payroll->gross, 2) }}</td>
                            <td>₱{{ number_format($payroll->deductions, 2) }}</td>
                            <td>₱{{ number_format($payroll->net_pay, 2) }}</td>
                            <td>{{ \Carbon\Carbon::parse($payroll->created_at)->format('F d, Y') }}</td>
                            <td>
                                <a href="{{ route('employee.payroll.download', $payroll->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-file-pdf"></i> View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No payroll records available.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
