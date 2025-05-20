@extends('layouts.admin')

@section('title', 'Employee Details')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>{{ $employee->first_name . ' ' . $employee->last_name }}</h4>
            <a href="{{ route('admin.hr.employees.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Records
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                {{-- Tabs --}}
                <ul class="nav nav-tabs mb-4" id="employeeTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->get('tab') === 'profile' || !request()->has('tab') ? 'active' : '' }}"
                        data-bs-toggle="tab" href="#profile" role="tab">Profile Info</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->get('tab') === 'attendance' ? 'active' : '' }}"
                        data-bs-toggle="tab" href="#attendance" role="tab">Attendance</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->get('tab') === 'leaves' ? 'active' : '' }}"
                        data-bs-toggle="tab" href="#leaves" role="tab">Leaves</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->get('tab') === 'documents' ? 'active' : '' }}"
                        data-bs-toggle="tab" href="#documents" role="tab">Documents</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->get('tab') === 'salary' ? 'active' : '' }}"
                        data-bs-toggle="tab" href="#salary" role="tab">Salary</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade {{ request()->get('tab') === 'profile' || !request()->has('tab') ? 'show active' : '' }}" id="profile" role="tabpanel">
                        @include('hr.employees.tabs.profile')
                    </div>
                    <div class="tab-pane fade {{ request()->get('tab') === 'attendance' ? 'show active' : '' }}" id="attendance" role="tabpanel">
                        @include('hr.employees.tabs.attendance')
                    </div>
                    <div class="tab-pane fade {{ request()->get('tab') === 'leaves' ? 'show active' : '' }}" id="leaves" role="tabpanel">
                        @include('hr.employees.tabs.leaves')
                    </div>
                    <div class="tab-pane fade {{ request()->get('tab') === 'documents' ? 'show active' : '' }}" id="documents" role="tabpanel">
                        @include('hr.employees.tabs.documents')
                    </div>
                    <div class="tab-pane fade {{ request()->get('tab') === 'salary' ? 'show active' : '' }}" id="salary" role="tabpanel">
                        @include('hr.employees.tabs.salary')
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

{{-- Edit Attendance Modal --}}
<div class="modal fade" id="editAttendanceModal" tabindex="-1" aria-labelledby="editAttendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.hr.attendance.manual.store') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="editAttendanceModalLabel">Edit Attendance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="employee_id" id="modal-employee-id">
                <div class="mb-3">
                    <label for="modal-date" class="form-label">Date</label>
                    <input type="date" name="date" id="modal-date" class="form-control" readonly>
                </div>
                <div class="mb-3">
                    <label for="modal-time-in" class="form-label">Time In</label>
                    <input type="datetime-local" name="time_in" id="modal-time-in" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="modal-time-out" class="form-label">Time Out</label>
                    <input type="datetime-local" name="time_out" id="modal-time-out" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save Entry</button>
            </div>
        </form>
    </div>
</div>

{{-- Salary Modal --}}
<div class="modal fade" id="salaryModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.hr.employees.salary.store', $employee->id) }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Add Salary Record</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Salary Amount (₱)</label>
                    <input type="number" name="amount" class="form-control" required step="0.01" min="0">
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Remarks</label>
                    <input type="text" name="remarks" class="form-control" placeholder="e.g. Initial, Adjustment, Bonus">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Save</button>
            </div>
        </form>
    </div>
</div>

{{-- JS to preserve tab on reload via hash --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('editAttendanceModal');

    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const date = button.getAttribute('data-date');
        const employee = button.getAttribute('data-employee');
        const timeIn = button.getAttribute('data-timein');
        const timeOut = button.getAttribute('data-timeout');

        const formatForInput = (datetime) => {
            if (!datetime) return '';
            const dt = new Date(datetime);
            const pad = (n) => n.toString().padStart(2, '0');
            return `${dt.getFullYear()}-${pad(dt.getMonth() + 1)}-${pad(dt.getDate())}T${pad(dt.getHours())}:${pad(dt.getMinutes())}`;
        };

        modal.querySelector('#modal-date').value = new Date(date).toISOString().split('T')[0];
        modal.querySelector('#modal-employee-id').value = employee;
        modal.querySelector('#modal-time-in').value = formatForInput(timeIn);
        modal.querySelector('#modal-time-out').value = formatForInput(timeOut);
    });

    // Preserve tab on reload using hash
    const hash = window.location.hash;
    if (hash) {
        const tabTrigger = document.querySelector(`.nav-link[href="${hash}"]`);
        if (tabTrigger) {
            new bootstrap.Tab(tabTrigger).show();
        }
    }

    // Update hash when tab is clicked
    document.querySelectorAll('.nav-tabs .nav-link').forEach((tab) => {
        tab.addEventListener('shown.bs.tab', function (e) {
            history.replaceState(null, null, e.target.getAttribute('href'));
        });
    });
});
</script>
@endsection
