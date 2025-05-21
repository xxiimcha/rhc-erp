@extends('layouts.admin')

@section('title', 'Leave Application Form')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <form method="POST" action="{{ route('employee.leaves.store') }}">
            @csrf

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Leave Application Form</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Name:</label>
                            <input type="text" class="form-control" value="{{ $employee->first_name }} {{ $employee->last_name }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label>Dept./Branch:</label>
                            <input type="text" class="form-control" value="{{ $employee->department_name }}" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Address:</label>
                            <input type="text" class="form-control" value="{{ $employee->address }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label>Position:</label>
                            <input type="text" class="form-control" value="{{ $employee->position }}" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Contact #:</label>
                            <input type="text" class="form-control" value="{{ $employee->contact_number }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label>Date Filed:</label>
                            <input type="text" class="form-control" value="{{ now()->format('F d, Y') }}" readonly>
                        </div>
                    </div>

                    <div class="alert alert-info small">
                        <strong>Instructions:</strong> Please fill up the table below. For Sick Leaves of more than <strong>(2)</strong> days please attach a doctor's certificate.
                        <br><em>For certain leaves it is necessary to indicate the reason or purpose of the leave in order to get approval from your Department Head.</em>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Type of Leave</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>No. of Days</th>
                                    <th>With Pay</th>
                                    <th>W/O Pay</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (['Vacation Leave', 'Sick Leave', 'Maternity Leave', 'Paternity Leave', 'Emergency Leave'] as $type)
                                    @php $key = str_replace(' ', '_', strtolower($type)); @endphp
                                    <tr>
                                        <td>{{ $type }}</td>
                                        <td>
                                            <input type="date" name="leaves[{{ $type }}][from]" class="form-control date-from" data-type="{{ $key }}">
                                        </td>
                                        <td>
                                            <input type="date" name="leaves[{{ $type }}][to]" class="form-control date-to" data-type="{{ $key }}">
                                        </td>
                                        <td>
                                            <input type="number" step="0.5" name="leaves[{{ $type }}][days]" id="days-{{ $key }}" class="form-control" readonly>
                                        </td>
                                        <td><input type="checkbox" name="leaves[{{ $type }}][pay]" value="with"></td>
                                        <td><input type="checkbox" name="leaves[{{ $type }}][pay]" value="without"></td>
                                        <td><input type="text" name="leaves[{{ $type }}][reason]" class="form-control"></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <label>Signature:</label>
                            <input type="text" class="form-control" value="{{ $employee->first_name }} {{ $employee->last_name }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label>Date Hired:</label>
                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($employee->date_hired)->format('F d, Y') }}" readonly>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Approved by Immediate Superior:</label>
                            <input type="text" class="form-control" name="approved_by_superior">
                        </div>
                        <div class="col-md-6">
                            <label>Approved by MANCOM:</label>
                            <input type="text" class="form-control" name="approved_by_mancom">
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i> Submit Leave Application
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Script to auto-calculate days --}}
<script>
    document.querySelectorAll('.date-from, .date-to').forEach(input => {
        input.addEventListener('change', function () {
            const type = this.dataset.type;
            const fromDate = document.querySelector(`input.date-from[data-type="${type}"]`).value;
            const toDate = document.querySelector(`input.date-to[data-type="${type}"]`).value;
            const output = document.getElementById(`days-${type}`);

            if (fromDate && toDate) {
                const start = new Date(fromDate);
                const end = new Date(toDate);
                const diff = Math.floor((end - start) / (1000 * 60 * 60 * 24)) + 1;

                output.value = (diff > 0) ? diff : '';
            }
        });
    });
</script>
@endsection
