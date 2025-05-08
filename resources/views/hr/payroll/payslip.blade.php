@extends('layouts.admin')

@section('title', 'Payslip')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Payslip for {{ $employee->first_name }} {{ $employee->last_name }}</h4>
            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <p><strong>Employee ID:</strong> {{ $employee->employee_id }}</p>
                <p><strong>Cutoff:</strong> {{ $cutoff }}</p>
                <p><strong>Month:</strong> {{ \Carbon\Carbon::parse($month)->format('F Y') }}</p>

                <hr>
                <p>This is a placeholder for the actual payslip data.</p>
            </div>
        </div>

    </div>
</div>
@endsection
