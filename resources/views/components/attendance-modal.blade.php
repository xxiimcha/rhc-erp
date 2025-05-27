
    <!-- Attendance Modal -->
    <div class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="attendanceModalLabel">Attendance for {{ $cutoff }} ({{ \Carbon\Carbon::parse($month)->format('F Y') }})</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if(isset($attendanceLogs) && count($attendanceLogs))
                <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                    <th>Date</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Hours Worked</th>
                    <th>Late (min)</th>
                    <th>Overtime (min)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendanceLogs as $log)
                    <tr>
                    <td>{{ \Carbon\Carbon::parse($log->time_in)->format('Y-m-d') }}</td>
                    <td>{{ \Carbon\Carbon::parse($log->time_in)->format('h:i A') }}</td>
                    <td>{{ $log->time_out ? \Carbon\Carbon::parse($log->time_out)->format('h:i A') : '-' }}</td>
                    <td>{{ $log->hours_worked ?? 0 }}</td>
                    <td>{{ $log->late_minutes ?? 0 }}</td>
                    <td>{{ $log->overtime_minutes ?? 0 }}</td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
                @else
                <p class="text-center text-muted">No attendance records found for this cutoff.</p>
                @endif
            </div>
            </div>
        </div>
    </div>