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
                        <button type="button"
                            class="badge border-0 {{ $salary->status === 'active' ? 'bg-success' : 'bg-secondary' }}"
                            onclick="confirmToggle('{{ route('admin.hr.employees.salaries.toggle', $salary->id) }}')">
                            {{ ucfirst($salary->status) }}
                        </button>
                    </td>
                    <td>{{ $salary->remarks }}</td>
                </tr>
            @empty
                <tr><td colspan="4">No salary records found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
function confirmToggle(url) {
    Swal.fire({
        title: 'Set as Active Salary?',
        text: "Only one salary can be active at a time. Continue?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, set active',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create a form dynamically and submit it
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';

            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'PATCH';

            form.appendChild(csrf);
            form.appendChild(method);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>

