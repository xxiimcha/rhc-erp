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

            $currentMonth = Carbon::now()->startOfMonth();
            $months = [];

            // Example: show cutoffs for Jan to current month
            for ($i = 0; $i < 6; $i++) {
                $months[] = $currentMonth->copy()->subMonths($i);
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
                        <tr>
                            <td>First Half (1–15)</td>
                            <td>{{ $monthDate->copy()->startOfMonth()->toFormattedDateString() }}</td>
                            <td>{{ $monthDate->copy()->startOfMonth()->addDays(14)->toFormattedDateString() }}</td>
                            <td>
                                <a href="{{ route('admin.hr.payroll.view', [
                                    'cutoff' => '1-15',
                                    'month' => $monthDate->format('Y-m')
                                ]) }}" class="btn btn-sm btn-primary">View</a>
                            </td>
                        </tr>
                        <tr>
                            <td>Second Half (16–30/31)</td>
                            <td>{{ $monthDate->copy()->startOfMonth()->addDays(15)->toFormattedDateString() }}</td>
                            <td>{{ $monthDate->copy()->endOfMonth()->toFormattedDateString() }}</td>
                            <td>
                                <a href="{{ route('admin.hr.payroll.view', [
                                    'cutoff' => '16-30',
                                    'month' => $monthDate->format('Y-m')
                                ]) }}" class="btn btn-sm btn-primary">View</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach

    </div>
</div>
@endsection
