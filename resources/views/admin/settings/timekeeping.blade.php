@extends('layouts.admin')

@section('title', 'Timekeeping Config')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h4 class="page-title">Timekeeping Configuration</h4>
        </div>
    </div>

    <!-- Filter Year -->
    <div class="row mb-3">
        <div class="col-md-3">
            <form method="GET" action="">
                <label for="yearFilter">Select Year</label>
                <select name="year" id="yearFilter" class="form-select" onchange="this.form.submit()">
                    @for ($y = date('Y'); $y >= 2020; $y--)
                        <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
        </div>
    </div>

    <!-- Holiday Table -->
    <div class="card shadow">
        <div class="card-body">
            <h5 class="mb-3">Holidays for {{ $year }}</h5>
            <table class="table table-bordered" id="datatable">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Holiday Name</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($holidays as $index => $holiday)
                        @php
                            $isPast = \Carbon\Carbon::parse($holiday->date)->isPast();
                        @endphp
                        <tr class="{{ $isPast ? 'table-secondary text-muted' : '' }}">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $holiday->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($holiday->date)->format('F d, Y') }}</td>
                            <td>{{ $holiday->description ?? '-' }}</td>
                            <td>
                                @if (!$isPast)
                                    <!-- Move Date Button -->
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#moveDateModal{{ $holiday->id }}">
                                        <i class="fas fa-calendar-alt"></i> Move Date
                                    </button>

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.settings.timekeeping.delete', $holiday->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this holiday?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>

                                    <!-- Move Date Modal -->
                                    <div class="modal fade" id="moveDateModal{{ $holiday->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <form method="POST" action="{{ route('admin.settings.timekeeping.updateDate', $holiday->id) }}" class="modal-content">
                                                @csrf
                                                @method('PATCH')
                                                <div class="modal-header bg-warning text-dark">
                                                    <h5 class="modal-title">Move Holiday Date</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Current Date: <strong>{{ \Carbon\Carbon::parse($holiday->date)->format('F d, Y') }}</strong></p>
                                                    <div class="mb-3">
                                                        <label for="new_date_{{ $holiday->id }}" class="form-label">New Date</label>
                                                        <input type="date" class="form-control" id="new_date_{{ $holiday->id }}" name="new_date" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-warning" type="submit">Move</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">----</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No holidays found.</td></tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('#datatable').DataTable();
    });
</script>
@endpush
