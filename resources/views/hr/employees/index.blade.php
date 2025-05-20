@extends('layouts.admin')

@section('title', 'Employee Records')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="row mb-4">
            <div class="col-sm-6">
                <h4 class="page-title">Employee Records</h4>
            </div>
            <div class="col-sm-6 text-sm-end">
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#importEmployeeModal">
                    <i class="fas fa-file-upload"></i> Import Excel
                </button>
                <a href="{{ route('admin.hr.employees.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-user-plus"></i> Add Employee
                </a>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <table id="employeeTable" class="table table-bordered table-striped dt-responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Photo & Full Name</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employees as $employee)
                    <tr>
                        <td>{{ $employee->employee_id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $employee->photo_path 
                                    ? asset('storage/employees/' . $employee->photo_path) 
                                    : asset('assets/images/avatar-placeholder.jpg') }}"
                                    alt="Profile"
                                    class="rounded-circle"
                                    style="width: 40px; height: 40px; object-fit: cover;">
                                <span>{{ $employee->first_name }} {{ $employee->middle_name }} {{ $employee->last_name }}</span>
                            </div>
                        </td>
                        <td>{{ $employee->position }}</td>
                        <td>{{ $employee->department }}</td>
                        <td>
                            <span class="badge bg-success">Active</span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.hr.employees.show', $employee->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#rfidModal{{ $employee->id }}">
                                <i class="fas fa-id-card"></i>
                            </a>
                            <a href="{{ route('admin.hr.employees.edit', $employee->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="#" method="POST" style="display:inline-block;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                </table>

                @foreach ($employees as $employee)
                    <div class="modal fade" id="rfidModal{{ $employee->id }}" tabindex="-1" aria-labelledby="rfidModalLabel{{ $employee->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content"> {{-- 🟢 This was missing --}}
                                <form method="POST" action="{{ route('admin.hr.employees.rfid.store', $employee->id) }}">
                                    @csrf
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title" id="rfidModalLabel{{ $employee->id }}">
                                            Enroll RFID for {{ $employee->employee_id }}
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <label for="rfid_number" class="form-label">RFID Card Number</label>
                                        <input type="text" name="rfid_number" class="form-control" required placeholder="Tap card or enter manually">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-save"></i> Save RFID
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
                <!-- Import Employees Modal -->
                <div class="modal fade" id="importEmployeeModal" tabindex="-1" aria-labelledby="importEmployeeModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('admin.hr.employees.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="importEmployeeModalLabel">Import Employees via Excel</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <label for="employee_excel" class="form-label">Upload Excel File</label>
                                    <input type="file" name="employee_excel" class="form-control" accept=".xlsx,.xls" required>
                                    <div class="mt-2 small text-muted">Accepted formats: .xlsx, .xls</div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-upload"></i> Import
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
    $(function () {
        $('#employeeTable').DataTable({
            responsive: true,
            pageLength: 10
        });

        // Focus the RFID input when the modal is shown
        $('div[id^="rfidModal"]').on('shown.bs.modal', function () {
            $(this).find('input[name="rfid_number"]').focus();
        });
    });
</script>
@endsection
