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
                            <th>Full Name</th>
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
                            <td>{{ $employee->first_name }} {{ $employee->middle_name }} {{ $employee->last_name }}</td>
                            <td>{{ $employee->position }}</td>
                            <td>{{ $employee->department }}</td>
                            <td>
                                <span class="badge bg-success">Active</span>
                            </td>
                            <td class="text-center">
                                <a href="#" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                <a href="#" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
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
    });
</script>
@endsection
