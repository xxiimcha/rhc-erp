@extends('layouts.admin')

@section('title', 'Assign Workdays')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="row mb-4">
            <div class="col-sm-6">
                <h4 class="page-title">Assign Workdays</h4>
            </div>
        </div>

        {{-- Table --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.hr.workdays.store') }}" method="POST">
                    @csrf
                    <table class="table table-bordered table-striped align-middle text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Employee</th>
                                @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                                    <th>{{ $day }}</th>
                                @endforeach
                                <th>Shift Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($employees as $index => $emp)
                            @php
                                $empWorkdays = $workdays[$emp->id] ?? collect();
                                $assignedDays = $empWorkdays->pluck('day_of_week')->toArray();
                                $shiftTime = $empWorkdays->first()?->shift_time;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    {{ $emp->first_name }} {{ $emp->last_name }}
                                    <input type="hidden" name="rows[{{ $index }}][employee_id]" value="{{ $emp->id }}">
                                </td>
                                @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                                <td>
                                    <input type="checkbox"
                                        name="rows[{{ $index }}][workdays][]"
                                        value="{{ $day }}"
                                        {{ in_array($day, $assignedDays) ? 'checked' : '' }}>
                                </td>
                                @endforeach
                                <td>
                                    <input type="text"
                                        name="rows[{{ $index }}][shift_time]"
                                        class="form-control"
                                        placeholder="e.g. 08:00 AM - 05:00 PM"
                                        value="{{ $shiftTime }}">
                                </td>
                                <td>
                                    <button type="submit" name="submit_index" value="{{ $index }}" class="btn btn-success btn-sm">Save</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">No employees found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
