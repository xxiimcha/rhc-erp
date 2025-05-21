@extends('layouts.admin')

@section('title', 'My Leaves')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="page-title">My Leave Requests</h4>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#requestLeaveModal">
                <i class="fas fa-plus-circle me-1"></i> Request Leave
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Filed On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaves as $leave)
                            <tr>
                                <td>{{ $leave->type }}</td>
                                <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}</td>
                                <td>{{ $leave->reason }}</td>
                                <td>
                                    @if($leave->status === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($leave->status === 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($leave->created_at)->format('M d, Y h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No leave requests found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Request Leave Modal --}}
        <div class="modal fade" id="requestLeaveModal" tabindex="-1" aria-labelledby="requestLeaveModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <form action="{{ route('employee.leaves.store') }}" method="POST" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Request Leave</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Leave Type</label>
                            <select name="type" class="form-select" required>
                                <option value="">-- Select Type --</option>
                                <option value="Vacation">Vacation</option>
                                <option value="Sick">Sick</option>
                                <option value="Emergency">Emergency</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Reason</label>
                            <textarea name="reason" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
