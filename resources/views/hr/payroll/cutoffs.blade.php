@extends('layouts.admin')

@section('title', 'Payroll Management')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Payroll Management</h4>
        </div>

        <form method="GET" class="row g-3 mb-4">
            <div class="col-auto">
                <label for="month" class="form-label">Select Month:</label>
                <input type="month" id="month" name="month" value="{{ $month }}" class="form-control">
            </div>
            <div class="col-auto align-self-end">
                <button type="submit" class="btn btn-primary">Show Cutoffs</button>
            </div>
        </form>

        <div class="card shadow-sm">
            <div class="card-body">
                <h5>Cutoffs for {{ \Carbon\Carbon::parse($month)->format('F Y') }}</h5>
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Cutoff Period</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $yearMonth = \Carbon\Carbon::parse($month);
                            $cutoff1Start = $yearMonth->copy()->startOfMonth();
                            $cutoff1End = $yearMonth->copy()->startOfMonth()->addDays(14);
                            $cutoff2Start = $yearMonth->copy()->startOfMonth()->addDays(15);
                            $cutoff2End = $yearMonth->copy()->endOfMonth();
                        @endphp

                        <tr>
                            <td>{{ $cutoff1Start->format('M d') }} - {{ $cutoff1End->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.hr.payroll.index', ['month' => $month, 'cutoff' => '1-15']) }}" class="btn btn-sm btn-primary">
                                    View
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>{{ $cutoff2Start->format('M d') }} - {{ $cutoff2End->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.hr.payroll.index', ['month' => $month, 'cutoff' => '16-30']) }}" class="btn btn-sm btn-primary">
                                    View
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
