@extends('layouts.admin')

@section('title', 'Payroll Cutoff Periods')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="row mb-4">
            <div class="col-sm-6">
                <h4 class="page-title">Payroll Cutoff Periods</h4>
            </div>
        </div>

        @php
            use Carbon\Carbon;
            use Illuminate\Support\Facades\DB;

            $currentMonth = Carbon::now()->startOfMonth();
            $months = [];

            for ($i = 0; $i < 7; $i++) {
                $months[] = $currentMonth->copy()->subMonths($i);
            }

            $existingPayrollData = [];

            // Preload counts for all combinations to avoid repetitive DB calls in loop
            foreach ($months as $monthDate) {
                $monthStr = $monthDate->format('Y-m');
                foreach (['1-15', '16-30'] as $cutoff) {
                    $payrollExists = DB::table('payrolls')
                        ->where('cutoff', $cutoff)
                        ->whereBetween('period', [
                            $monthDate->copy()->startOfMonth()->format('Y-m-d'),
                            $monthDate->copy()->endOfMonth()->format('Y-m-d')
                        ])
                        ->exists();

                    $historicalExists = DB::table('historical_payrolls')
                        ->where('cutoff', $cutoff)
                        ->whereBetween('period', [
                            $monthDate->copy()->startOfMonth()->format('Y-m-d'),
                            $monthDate->copy()->endOfMonth()->format('Y-m-d')
                        ])
                        ->exists();


                    $existingPayrollData[$monthStr][$cutoff] = $payrollExists || $historicalExists;
                }
            }
        @endphp


        @foreach ($months as $monthDate)
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                {{ $monthDate->format('F Y') }}
            </div>
            <div class="card-body">
                <table class="table table-bordered text-center mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Cutoff Period</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $monthString = $monthDate->format('Y-m');
                        @endphp

                        {{-- 1–15 --}}
                        <tr>
                            <td>First Half (1–15)</td>
                            <td>{{ $monthDate->copy()->startOfMonth()->toFormattedDateString() }}</td>
                            <td>{{ $monthDate->copy()->startOfMonth()->addDays(14)->toFormattedDateString() }}</td>
                            <td>
                                <a href="{{ route('admin.hr.payroll.view', [
                                    'cutoff' => '1-15',
                                    'month' => $monthString
                                ]) }}" class="btn btn-sm btn-primary">View</a>

                                @unless ($existingPayrollData[$monthString]['1-15'])
                                    <button type="button" class="btn btn-sm btn-success mt-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#uploadModal"
                                            data-month="{{ $monthString }}"
                                            data-cutoff="1-15">
                                        Upload Excel
                                    </button>
                                @endunless
                            </td>
                        </tr>

                        {{-- 16–30 --}}
                        <tr>
                            <td>Second Half (16–30/31)</td>
                            <td>{{ $monthDate->copy()->startOfMonth()->addDays(15)->toFormattedDateString() }}</td>
                            <td>{{ $monthDate->copy()->endOfMonth()->toFormattedDateString() }}</td>
                            <td>
                                <a href="{{ route('admin.hr.payroll.view', [
                                    'cutoff' => '16-30',
                                    'month' => $monthString
                                ]) }}" class="btn btn-sm btn-primary">View</a>

                                @unless ($existingPayrollData[$monthString]['16-30'])
                                    <button type="button" class="btn btn-sm btn-success mt-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#uploadModal"
                                            data-month="{{ $monthString }}"
                                            data-cutoff="16-30">
                                        Upload Excel
                                    </button>
                                @endunless
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
        @endforeach

    </div>
</div>

{{-- Upload Modal --}}
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.hr.payroll.import') }}" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="uploadModalLabel">Upload Payroll Excel</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="month" id="modalMonth">
                <input type="hidden" name="cutoff" id="modalCutoff">

                <div class="mb-3">
                    <label for="payrollFile" class="form-label">Choose Excel file</label>
                    <input type="file" name="payroll_file" id="payrollFile" class="form-control" accept=".xlsx,.xls" required>
                </div>
            </div>
            <div class="modal-footer">
                <div id="uploadSpinner" class="spinner-border text-success d-none me-2" role="status">
                    <span class="visually-hidden">Uploading...</span>
                </div>
                <button type="submit" class="btn btn-success">Import</button>
            </div>
        </form>
    </div>
</div>

{{-- Import Success Modal --}}
<div class="modal fade" id="importSuccessModal" tabindex="-1" aria-labelledby="importSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="importSuccessModalLabel">Import Summary</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="successSummary">
                <div class="alert alert-success">{{ session('success') }}</div>

                @if(session('matched') && count(session('matched')))
                <div class="table-responsive mb-3">
                    <table class="table table-bordered table-striped">
                        <thead class="table-primary">
                            <tr>
                                <th>#</th>
                                <th>Employee Name</th>
                                <th>Department</th>
                                <th>Gross</th>
                                <th>Net Pay</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(session('matched') as $index => $row)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $row['employee_name'] ?? '-' }}</td>
                                <td>{{ $row['department'] ?? '-' }}</td>
                                <td>{{ number_format($row['gross'] ?? 0, 2) }}</td>
                                <td>{{ number_format($row['net_pay'] ?? 0, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif


                @if(session('unmatched') && count(session('unmatched')))
                <div class="alert alert-warning">
                    <strong>Unmatched Entries:</strong>
                    <ul class="mb-0">
                        @foreach(session('unmatched') as $entry)
                            <li>{{ $entry }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const uploadModal = document.getElementById('uploadModal');
    uploadModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        document.getElementById('modalMonth').value = button.getAttribute('data-month');
        document.getElementById('modalCutoff').value = button.getAttribute('data-cutoff');
        document.getElementById('uploadSpinner').classList.add('d-none');
    });

    const form = document.querySelector('#uploadModal form');
    form.addEventListener('submit', () => {
        document.getElementById('uploadSpinner').classList.remove('d-none');
    });

    const successMsg = @json(session('success'));
    const matched = @json(session('matched', []));
    const unmatched = @json(session('unmatched', []));

    if (successMsg) {
        const modal = new bootstrap.Modal(document.getElementById('importSuccessModal'));
        const summaryDiv = document.getElementById('successSummary');
        summaryDiv.innerHTML = `<div class="alert alert-success">${successMsg}</div>`;

        if (matched.length) {
            let table = `
                <div class="table-responsive mb-3">
                    <table class="table table-bordered table-striped">
                        <thead class="table-primary">
                            <tr>
                                <th>#</th>
                                <th>Employee Name</th>
                                <th>Department</th>
                                <th>Gross</th>
                                <th>Net Pay</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            matched.forEach((row, index) => {
                table += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${row.employee_name ?? '-'}</td>
                        <td>${row.department ?? '-'}</td>
                        <td>${parseFloat(row.gross || 0).toFixed(2)}</td>
                        <td>${parseFloat(row.net_pay || 0).toFixed(2)}</td>
                    </tr>
                `;
            });

            table += `
                        </tbody>
                    </table>
                </div>
            `;

            summaryDiv.innerHTML += table;
        }

        modal.show();
    }
});
</script>

@endsection