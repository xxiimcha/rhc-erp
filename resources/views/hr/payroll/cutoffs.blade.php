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

            for ($i = 0; $i < 6; $i++) {
                $months[] = $currentMonth->copy()->subMonths($i);
            }

            function hasPayrollData($month, $cutoff) {
                $count1 = DB::table('payrolls')->where('period', $month)->where('cutoff', $cutoff)->count();
                $count2 = DB::table('historical_payrolls')->where('period', $month)->where('cutoff', $cutoff)->count();
                return $count1 + $count2 > 0;
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

                                @unless (hasPayrollData($monthString, '1-15'))
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

                                @unless (hasPayrollData($monthString, '16-30'))
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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                <button type="submit" class="btn btn-success">Import</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Handling Script --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const uploadModal = document.getElementById('uploadModal');
    uploadModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const month = button.getAttribute('data-month');
        const cutoff = button.getAttribute('data-cutoff');

        document.getElementById('modalMonth').value = month;
        document.getElementById('modalCutoff').value = cutoff;
    });
});
</script>
@endsection
