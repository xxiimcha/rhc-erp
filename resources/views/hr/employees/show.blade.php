@extends('layouts.admin')

@section('title', 'Employee Details')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Employee Details: {{ $employee->employee_id }}</h4>
            <a href="{{ route('admin.hr.employees.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Records
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile">Profile Info</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#attendance">Attendance</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#leaves">Leaves</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#documents">Documents</button></li>
                </ul>

                <div class="tab-content">
                    {{-- Profile Tab --}}
                    <div class="tab-pane fade show active" id="profile">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Full Name:</strong> {{ $employee->first_name }} {{ $employee->middle_name }} {{ $employee->last_name }}</p>
                                <p><strong>Email:</strong> {{ $employee->email }}</p>
                                <p><strong>Contact Number:</strong> {{ $employee->contact_number }}</p>
                                <p><strong>Gender:</strong> {{ ucfirst($employee->gender) }}</p>
                                <p><strong>Date of Birth:</strong> {{ $employee->date_of_birth }}</p>
                                <p><strong>Address:</strong> {{ $employee->address }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Position:</strong> {{ $employee->position }}</p>
                                <p><strong>Department:</strong> {{ $employee->department }}</p>
                                <p><strong>Employment Type:</strong> {{ ucfirst($employee->employment_type) }}</p>
                                <p><strong>Date Hired:</strong> {{ $employee->date_hired }}</p>
                                <hr>
                                <p><strong>PhilHealth No:</strong> {{ $employee->philhealth_no }}</p>
                                <p><strong>SSS No:</strong> {{ $employee->sss_no }}</p>
                                <p><strong>Pag-IBIG No:</strong> {{ $employee->pagibig_no }}</p>
                                <p><strong>TIN No:</strong> {{ $employee->tin_no }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Attendance Tab --}}
                    <div class="tab-pane fade" id="attendance">
                        <form method="GET" class="mb-3">
                            <label for="month">Select Month:</label>
                            <input type="month" name="month" id="month" value="{{ $month }}" onchange="this.form.submit()" class="form-control w-auto d-inline-block">
                        </form>

                        <div class="table-responsive">
                            <table class="table table-bordered text-center align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Date</th>
                                        <th>Day</th>
                                        <th>Time In</th>
                                        <th>Time Out</th>
                                        <th>Status</th>
                                        <th>Late (min)</th>
                                        <th>Overtime (min)</th>
                                        <th>Hours Worked</th>
                                        <th>Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($d = 1; $d <= \Carbon\Carbon::parse($month)->daysInMonth; $d++)
                                        @php
                                            $date = \Carbon\Carbon::parse($month)->startOfMonth()->addDays($d - 1);
                                            $dateStr = $date->format('Y-m-d');
                                            $record = $clockings[$dateStr][0] ?? null;
                                            $isAbsent = !$record && $date->isPast();
                                            $holiday = $holidays[$dateStr] ?? null;
                                            $isCurrentMonth = $date->format('Y-m') === now()->format('Y-m');
                                        @endphp
                                        <tr class="{{ $isAbsent ? 'table-danger' : '' }} {{ $holiday ? 'table-info' : '' }}">
                                            <td>{{ $date->format('M d, Y') }}</td>
                                            <td>{{ $date->format('l') }}</td>
                                            <td>{{ $record?->time_in ? \Carbon\Carbon::parse($record->time_in)->format('h:i A') : '-' }}</td>
                                            <td>{{ $record?->time_out ? \Carbon\Carbon::parse($record->time_out)->format('h:i A') : '-' }}</td>
                                            <td>
                                                @if ($record)
                                                    <span class="badge {{ $record->status === 'late' ? 'bg-danger' : 'bg-success' }}">{{ ucfirst($record->status) }}</span>
                                                @elseif($isAbsent)
                                                    <span class="badge bg-warning text-dark">Absent</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $record->late_minutes ?? ($isAbsent ? 0 : '-') }}</td>
                                            <td>{{ $record->overtime_minutes ?? ($isAbsent ? 0 : '-') }}</td>
                                            <td>{{ $record->hours_worked ?? ($isAbsent ? 0 : '-') }}</td>
                                            <td>
                                                @if ($holiday)
                                                    <span class="badge bg-primary">{{ $holiday['localName'] }} ({{ $holiday['type'] }})</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if ($isCurrentMonth)
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editAttendanceModal"
                                                            data-date="{{ $dateStr }}"
                                                            data-employee="{{ $employee->employee_id }}"
                                                            data-timein="{{ $record?->time_in }}"
                                                            data-timeout="{{ $record?->time_out }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Leaves Tab --}}
                    <div class="tab-pane fade" id="leaves">
                        <p>Leave records will go here.</p>
                    </div>

                    {{-- Documents Tab --}}
                    <div class="tab-pane fade" id="documents">
                        <p>Uploaded employee documents will go here.</p>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('editAttendanceModal');

    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const date = button.getAttribute('data-date'); // Format: YYYY-MM-DD
        const employee = button.getAttribute('data-employee');
        const timeIn = button.getAttribute('data-timein');
        const timeOut = button.getAttribute('data-timeout');

        const formatForInput = (datetime) => {
            if (!datetime) return '';
            const dt = new Date(datetime);
            const pad = (n) => n.toString().padStart(2, '0');
            return `${dt.getFullYear()}-${pad(dt.getMonth()+1)}-${pad(dt.getDate())}T${pad(dt.getHours())}:${pad(dt.getMinutes())}`;
        };

        modal.querySelector('#modal-date').value = new Date(date).toISOString().split('T')[0];
        modal.querySelector('#modal-employee-id').value = employee;
        modal.querySelector('#modal-time-in').value = formatForInput(timeIn);
        modal.querySelector('#modal-time-out').value = formatForInput(timeOut);
    });
});
</script>

@endsection
