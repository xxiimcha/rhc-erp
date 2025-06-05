@extends('layouts.admin')

@section('title', 'My Leaves')
@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="page-title">My Leave Requests</h4>
            <a href="{{ route('employee.leaves.form') }}" class="btn btn-outline-secondary btn-sm me-2">
                <i class="fas fa-file-alt me-1"></i> Leave Form
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @forelse($leaves as $leave)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between flex-wrap">
                        <div class="mb-2">
                            <h5 class="mb-1">{{ $leave->type }} Leave</h5>
                            <p class="mb-0 text-muted">
                                <strong>From:</strong> {{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}<br>
                                <strong>To:</strong> {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                            </p>
                        </div>
                        <div class="text-end mb-2">
                            @if($leave->status === 'approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($leave->status === 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                            <p class="mb-0 small text-muted">Filed: {{ \Carbon\Carbon::parse($leave->created_at)->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <strong>Reason:</strong><br>
                        <p class="mb-0">{{ $leave->reason }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info text-center">
                No leave requests found.
            </div>
        @endforelse

    </div>
</div>
@endsection
