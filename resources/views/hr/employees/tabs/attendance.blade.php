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
