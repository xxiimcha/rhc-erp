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
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                    <i class="fas fa-user-plus"></i> Add Employee
                </button>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <table id="employeeTable" class="table table-bordered table-striped dt-responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Full Name</th>
                            <th>Position</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Sample static row --}}
                        <tr>
                            <td>EMP001</td>
                            <td>Charmaine Cator</td>
                            <td>HR Officer</td>
                            <td>Human Resources</td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Add Employee Modal --}}
        <div class="modal fade" id="addEmployeeModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <form class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">Add New Employee</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Position</label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Department</label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save Employee</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@section('scripts')
<script>
    $(function () {
        $('#employeeTable').DataTable({
            responsive: true,
            pageLength: 10
        });
    });
</script>
@endsection
