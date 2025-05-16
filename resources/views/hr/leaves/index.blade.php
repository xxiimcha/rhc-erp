@extends('layouts.admin')

@section('title', 'Leave Management')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="row mb-4">
            <div class="col-sm-6">
                <h4 class="page-title">Leave Management</h4>
            </div>
            <div class="col-sm-6 text-sm-end">
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addLeaveModal">
                    <i class="fas fa-plus-circle"></i> Add Leave Request
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Leave Type</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Status</th>
                            <th>Reason</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($leaves as $index => $leave)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $leave->employee_name }}</td>
                            <td>{{ $leave->leave_type }}</td>
                            <td>{{ $leave->from_date }}</td>
                            <td>{{ $leave->to_date }}</td>
                            <td>
                                <span class="badge 
                                    @if($leave->status === 'Approved') bg-success 
                                    @elseif($leave->status === 'Pending') bg-warning 
                                    @else bg-danger 
                                    @endif">
                                    {{ $leave->status }}
                                </span>
                            </td>
                            <td>{{ $leave->reason }}</td>
                            <td>
                                <a href="{{ route('admin.hr.leave.edit', $leave->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                <form method="POST" action="{{ route('admin.hr.leave.destroy', $leave->id) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No leave requests found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

{{-- Add Leave Modal --}}
<div class="modal fade" id="addLeaveModal" tabindex="-1" aria-labelledby="addLeaveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('admin.hr.leave.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Leave Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label for="employee_name" class="form-label">Employee Name</label>
                        <select name="employee_name" class="form-control" required>
                            <option value="">-- Select Employee --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->first_name }} {{ $emp->last_name }}">
                                    {{ $emp->first_name }} {{ $emp->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="leave_type" class="form-label">Leave Type</label>
                        <select name="leave_type" class="form-control" required>
                            <option value="Sick">Sick</option>
                            <option value="Vacation">Vacation</option>
                            <option value="Emergency">Emergency</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="from_date" class="form-label">From</label>
                        <input type="date" name="from_date" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="to_date" class="form-label">To</label>
                        <input type="date" name="to_date" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label for="reason" class="form-label">Reason</label>
                        <textarea name="reason" rows="3" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit Leave</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
