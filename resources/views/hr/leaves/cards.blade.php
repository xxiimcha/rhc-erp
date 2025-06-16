<div class="row">
    @if($filteredLeaves->count())
        @foreach ($filteredLeaves as $leave)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card shadow border-0 h-100">
                    <div class="card-header bg-light border-bottom-0 d-flex justify-content-end">
                        <span class="badge rounded-pill
                            @if(strtolower($leave->status) === 'approved') bg-success
                            @elseif(strtolower($leave->status) === 'pending') bg-warning text-dark
                            @elseif(strtolower($leave->status) === 'cancelled') bg-secondary
                            @else bg-danger
                            @endif">
                            {{ ucfirst($leave->status) }}
                        </span>
                    </div>
                    <div class="card-body pb-0">
                        <div class="mb-2">
                            <h6 class="mb-1">{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</h6>
                            <p class="text-muted small mb-0">{{ $leave->type }} Leave</p>
                        </div>
                        <ul class="list-unstyled mb-3">
                            <li><strong>Duration:</strong> {{ \Carbon\Carbon::parse($leave->start_date)->format('F d, Y') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('F d, Y') }}</li>
                            <li><strong>Date Filed:</strong> {{ \Carbon\Carbon::parse($leave->created_at)->addHours(8)->format('F d, Y') }}</li>
                        </ul>
                        <p class="mb-0"><strong>Reason:</strong><br>{{ $leave->reason }}</p>
                    </div>
                    @if(strtolower($leave->status) === 'pending')
                    <div class="card-footer bg-transparent border-top-0 d-flex justify-content-end">
                        <!-- Hidden Approve Form -->
                        <form id="approve-form-{{ $leave->id }}" action="{{ route('admin.hr.leave.approve', $leave->id) }}" method="POST" class="d-none">
                            @csrf
                        </form>
                        <!-- Approve Button -->
                        <button type="button" class="btn btn-success btn-sm me-2" onclick="confirmApproval('{{ $leave->id }}', '{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}')">
                            Approve
                        </button>

                        <!-- Hidden Reject Form -->
                        <form id="reject-form-{{ $leave->id }}" action="{{ route('admin.hr.leave.reject', $leave->id) }}" method="POST" class="d-none">
                            @csrf
                        </form>
                        <!-- Reject Button -->
                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmRejection('{{ $leave->id }}', '{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}')">
                            Reject
                        </button>
                    </div>

                    @endif
                </div>
            </div>
        @endforeach
    @else
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-1"></i> No pending leave requests.
            </div>
        </div>
    @endif
</div>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: "{{ session('success') }}",
        confirmButtonColor: '#28a745'
    });
</script>
@endif

<script>
function confirmApproval(id, name) {
    Swal.fire({
        title: 'Approve Leave Request',
        text: `Are you sure you want to approve the leave request for ${name}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Approve',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#28a745',
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`approve-form-${id}`).submit();
        }
    });
}

function confirmRejection(id, name) {
    Swal.fire({
        title: 'Reject Leave Request',
        text: `Are you sure you want to reject the leave request for ${name}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Reject',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#dc3545',
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`reject-form-${id}`).submit();
        }
    });
}
</script>

