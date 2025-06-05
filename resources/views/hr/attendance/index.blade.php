@extends('layouts.admin')

@section('title', 'Attendance Tracking')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="row mb-4">
            <div class="col-sm-6">
                <h4 class="page-title">Attendance Tracking</h4>
            </div>
            <div class="col-sm-6 text-end">
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#manualAttendanceModal">
                    <i class="fas fa-plus"></i> Add Time In/Out
                </button>
            </div>
        </div>

        <!-- Filter Tabs -->
        <ul class="nav nav-tabs mb-3" id="attendanceTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ request('filter') === null ? 'active' : '' }}" href="{{ route('admin.hr.attendance.index') }}">All</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('filter') === 'daily' ? 'active' : '' }}" href="{{ route('admin.hr.attendance.index', ['filter' => 'daily']) }}">Daily</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('filter') === 'weekly' ? 'active' : '' }}" href="{{ route('admin.hr.attendance.index', ['filter' => 'weekly']) }}">Weekly</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('filter') === 'monthly' ? 'active' : '' }}" href="{{ route('admin.hr.attendance.index', ['filter' => 'monthly']) }}">Monthly</a>
            </li>
        </ul>

        <form method="GET" id="dateFilterForm" class="row g-2 align-items-end mb-3">
            <input type="hidden" name="filter" id="filterValue" value="{{ request('filter') }}">

            <div class="col-md-3 filter-all d-none">
                <label class="form-label mb-0">Select Date:</label>
                <input type="date" id="date" class="form-control"
                    value="{{ request('date') ?? \Carbon\Carbon::now()->toDateString() }}">
            </div>

            <div class="col-md-3 filter-daily filter-weekly d-none">
                <label class="form-label mb-0">From Date:</label>
                <input type="date" id="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-3 filter-daily filter-weekly d-none">
                <label class="form-label mb-0">To Date:</label>
                <input type="date" id="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>

            <div class="col-md-3 filter-monthly d-none">
                <label class="form-label mb-0">From Month:</label>
                <input type="month" id="month_from" class="form-control" value="{{ request('month_from') }}">
            </div>
            <div class="col-md-3 filter-monthly d-none">
                <label class="form-label mb-0">To Month:</label>
                <input type="month" id="month_to" class="form-control" value="{{ request('month_to') }}">
            </div>

            <div class="col-auto">
                <button type="button" id="applyFilterBtn" class="btn btn-secondary">Apply</button>
            </div>
        </form>

        <div class="card shadow">
            <div class="card-body">
                <table id="datatable" class="table table-hover table-bordered table-striped dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead class="table-danger">
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Position</th>
                            <th>Date</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Status</th>
                            <th>Late (min)</th>
                            <th>Overtime (min)</th>
                            <th>Hours Worked</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attendances as $index => $rec)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $rec->employee->first_name }} {{ $rec->employee->last_name }}</td>
                            <td>{{ $rec->employee->position }}</td>
                            <td>{{ \Carbon\Carbon::parse($rec->created_at)->format('M d, Y') }}</td>
                            <td>{{ $rec->time_in ? \Carbon\Carbon::parse($rec->time_in)->format('h:i A') : '-' }}</td>
                            <td>{{ $rec->time_out ? \Carbon\Carbon::parse($rec->time_out)->format('h:i A') : '-' }}</td>
                            <td>
                                @if ($rec->status === 'late')
                                    <span class="badge bg-danger">Late</span>
                                @else
                                    <span class="badge bg-success">On Time</span>
                                @endif
                            </td>
                            <td>{{ $rec->late_minutes ?? 0 }}</td>
                            <td>{{ $rec->overtime_minutes ?? 0 }}</td>
                            <td>{{ $rec->hours_worked ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Manual Time In/Out Modal -->
        <div class="modal fade" id="manualAttendanceModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.hr.attendance.manual.store') }}" method="POST">
                        @csrf
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Manual Time In/Out</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="employee_id" class="form-label">Select Employee</label>
                                <select name="employee_id" class="form-select" required>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->employee_id }}">
                                            {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->position }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="time_in" class="form-label">Time In</label>
                                <input type="datetime-local" name="time_in" id="manual-time-in" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="time_out" class="form-label">Time Out</label>
                                <input type="datetime-local" name="time_out" class="form-control">
                            </div>
                            <input type="hidden" name="date" id="manual-date">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    $(document).ready(function () {
        const filter = "{{ request('filter') ?? '' }}";

        function toggleDateInputs() {
            $('.filter-all, .filter-daily, .filter-weekly, .filter-monthly').addClass('d-none');

            switch (filter) {
                case 'daily':
                    $('.filter-daily').removeClass('d-none');
                    break;
                case 'weekly':
                    $('.filter-weekly').removeClass('d-none');
                    break;
                case 'monthly':
                    $('.filter-monthly').removeClass('d-none');
                    break;
                default:
                    $('.filter-all').removeClass('d-none');
                    break;
            }
        }

        toggleDateInputs();
        $('#applyFilterBtn').on('click', function () {
            const filter = $('#filterValue').val();
            const url = new URL(window.location.href.split('?')[0]);
            const params = new URLSearchParams();

            if (filter) {
                params.append('filter', filter);
            }

            switch (filter) {
                case 'daily':
                case 'weekly':
                    const from = $('#date_from').val();
                    const to = $('#date_to').val();
                    if (from) params.append('date_from', from);
                    if (to) params.append('date_to', to);
                    break;
                case 'monthly':
                    const monthFrom = $('#month_from').val();
                    const monthTo = $('#month_to').val();
                    if (monthFrom) params.append('month_from', monthFrom);
                    if (monthTo) params.append('month_to', monthTo);
                    break;
                default:
                    const date = $('#date').val();
                    if (date) params.append('date', date);
                    break;
            }

            window.location.href = `${url.href}?${params.toString()}`;
        });

        const table = $('#datatable').DataTable({
            responsive: true,
            pageLength: 10
        });

        $('#searchInput').on('keyup', function () {
            table.search(this.value).draw();
        });

        // Set current date in MM/DD/YYYY format
        const now = new Date();
        const mm = String(now.getMonth() + 1).padStart(2, '0');
        const dd = String(now.getDate()).padStart(2, '0');
        const yyyy = now.getFullYear();
        const formattedToday = `${mm}/${dd}/${yyyy}`;
        $('#manual-date').val(formattedToday);

        // Optionally update date when time-in changes
        $('#manual-time-in').on('change', function () {
            if (this.value) {
                const date = new Date(this.value);
                const mm = String(date.getMonth() + 1).padStart(2, '0');
                const dd = String(date.getDate()).padStart(2, '0');
                const yyyy = date.getFullYear();
                $('#manual-date').val(`${mm}/${dd}/${yyyy}`);
            }
        });
    });
</script>
@endsection
