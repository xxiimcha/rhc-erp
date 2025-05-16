<div class="d-flex justify-content-end mb-3">
    <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#salaryModal">
        <i class="fas fa-plus"></i> Add Salary
    </button>
</div>

<div class="table-responsive">
    <table class="table table-bordered text-center">
        <thead class="table-success">
            <tr>
                <th>Date</th>
                <th>Amount (₱)</th>
                <th>Status</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($employee->salaries as $salary)
                <tr>
                    <td>{{ $salary->created_at->format('M d, Y') }}</td>
                    <td>₱{{ number_format($salary->amount, 2) }}</td>
                    <td>
                        <span class="badge {{ $salary->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($salary->status) }}
                        </span>
                    </td>
                    <td>{{ $salary->remarks }}</td>
                </tr>
            @empty
                <tr><td colspan="4">No salary records found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
