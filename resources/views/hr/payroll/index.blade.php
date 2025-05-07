@extends('layouts.admin')

@section('title', 'Payroll Management')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Payroll Management</h4>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <p class="text-muted">This page will display employee payroll summaries, deductions, and net pay.</p>
                
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Employee ID</th>
                            <th>Full Name</th>
                            <th>Base Salary</th>
                            <th>Deductions</th>
                            <th>Net Pay</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Example row (replace this with dynamic loop later) --}}
                        <tr>
                            <td>2025001</td>
                            <td>Juan Dela Cruz</td>
                            <td>₱20,000.00</td>
                            <td>₱2,000.00</td>
                            <td>₱18,000.00</td>
                            <td>
                                <button class="btn btn-sm btn-primary">Generate Payslip</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
