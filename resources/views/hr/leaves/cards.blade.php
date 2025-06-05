<div class="row">
    @forelse ($filteredLeaves as $index => $leave)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center bg-light">
                    <span class="fw-bold">#{{ $index + 1 }}</span>
                    <span class="badge 
                        @if($leave->status === 'Approved') bg-success 
                        @elseif($leave->status === 'Pending') bg-warning 
                        @else bg-danger 
                        @endif">
                        {{ $leave->status }}
                    </span>
                </div>
                <div class="card-body">
                    <p><strong>Employee:</strong> {{ $leave->employee_name }}</p>
                    <p><strong>Leave Type:</strong> {{ $leave->leave_type }}</p>
                    <p><strong>From:</strong> {{ $leave->from_date }}</p>
                    <p><strong>To:</strong> {{ $leave->to_date }}</p>
                    <p><strong>Reason:</strong><br>{{ $leave->reason }}</p>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('admin.hr.leave.edit', $leave->id) }}" class="btn btn-primary btn-sm me-2">Edit</a>
                    <form method="POST" action="{{ route('admin.hr.leave.destroy', $leave->id) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center">No leave requests found.</div>
        </div>
    @endforelse
</div>
