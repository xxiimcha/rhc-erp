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

        {{-- Tabs --}}
        <ul class="nav nav-tabs mb-3" id="leaveTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active position-relative" id="pending-tab" data-bs-toggle="tab" href="#pending" role="tab">
                    For Approval
                    @if($leaves->where('status', 'pending')->count() > 0)
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                            <span class="visually-hidden">Pending</span>
                        </span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="approved-tab" data-bs-toggle="tab" href="#approved" role="tab">Approved</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="cancelled-tab" data-bs-toggle="tab" href="#cancelled" role="tab">Cancelled</a>
            </li>
        </ul>


        <div class="tab-content">
            <div class="tab-pane fade show active" id="pending" role="tabpanel">
                @include('hr.leaves.cards', ['filteredLeaves' => $leaves->where('status', 'pending')])
            </div>
            <div class="tab-pane fade" id="approved" role="tabpanel">
                @include('hr.leaves.cards', ['filteredLeaves' => $leaves->where('status', 'approved')])
            </div>
            <div class="tab-pane fade" id="cancelled" role="tabpanel">
                @include('hr.leaves.cards', ['filteredLeaves' => $leaves->where('status', 'cancelled')])
            </div>
        </div>

    </div>
</div>

{{-- Add Leave Modal --}}
<div class="modal fade" id="addLeaveModal" tabindex="-1" aria-labelledby="addLeaveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('admin.hr.leave.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">File Emergency Leave Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Employee Name</label>
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
                        <label class="form-label">Leave Type</label>
                        <input type="text" class="form-control" value="Emergency" disabled>
                        <input type="hidden" name="leave_type" value="Emergency">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">From</label>
                        <input type="date" name="from_date" class="form-control" required min="{{ now()->toDateString() }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">To</label>
                        <input type="date" name="to_date" class="form-control" required min="{{ now()->toDateString() }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Reason</label>
                        <textarea name="reason" rows="3" class="form-control" required></textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Attachment <small class="text-muted">(Optional PDF, JPG, PNG)</small></label>
                        <input type="file" name="attachment" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
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
