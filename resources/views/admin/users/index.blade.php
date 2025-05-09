@php
    $departments = ['MIS', 'Human Resources', 'Franchise/FSG', 'Accounting', 'BWES', 'Maintenance', 'Executive', 'Marketing', 'RSC', 'Company Own'];
@endphp

@extends('layouts.admin')

@section('title', 'User Accounts')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="row mb-4">
            <div class="col-sm-6">
                <h4 class="page-title">User Accounts</h4>
            </div>
            <div class="col-sm-6 text-sm-end">
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="fas fa-user-plus"></i> Add User
                </button>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-body">

                {{-- Tabs --}}
                <ul class="nav nav-tabs" id="userTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="admin-tab" data-bs-toggle="tab" href="#admin" role="tab">Admins</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="employee-tab" data-bs-toggle="tab" href="#employee" role="tab">Employees</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="sysadmin-tab" data-bs-toggle="tab" href="#sysadmin" role="tab">System Administrators</a>
                    </li>
                </ul>

                {{-- Tab Content --}}
                <div class="tab-content mt-3" id="userTabsContent">
                    <div class="tab-pane fade show active" id="admin" role="tabpanel">
                        <table class="table table-bordered table-striped dt-responsive nowrap" id="adminTable" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Department(s)</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users->where('role', 'admin') as $user)
                                    @include('admin.users.partials.user_row')
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="employee" role="tabpanel">
                        <table class="table table-bordered table-striped dt-responsive nowrap" id="employeeTable" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users->where('role', 'employee') as $user)
                                    @include('admin.users.partials.user_row')
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="sysadmin" role="tabpanel">
                        <table class="table table-bordered table-striped dt-responsive nowrap" id="sysadminTable" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users->where('role', 'system_admin') as $user)
                                    @include('admin.users.partials.user_row')
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        {{-- Add Modal --}}
        <div class="modal fade" id="addUserModal" tabindex="-1">
          <div class="modal-dialog">
            <form action="{{ route('admin.users.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Add New User</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-control" name="first_name" id="firstName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="last_name" id="lastName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username (Auto-generated)</label>
                        <input type="text" class="form-control" name="username" id="generatedUsername" readonly required>
                        <small class="text-muted">Password will be the same as the username.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" id="roleSelect" class="form-select" required>
                            <option value="admin">Admin</option>
                            <option value="system_admin">System Administrator</option>
                        </select>
                    </div>
                    <div class="mb-3" id="departmentField">
                        <label class="form-label">Department(s)</label>
                        <select name="department[]" class="form-select" multiple required>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept }}">{{ $dept }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple.</small>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Create User</button>
                </div>
            </form>
          </div>
        </div>

    </div>
</div>

{{-- Reusable Row Partial for User + Modals --}}
@foreach ($users as $user)
    @include('admin.users.partials.user_modal')
@endforeach

<script>
    $(document).ready(function () {
        $('#adminTable, #employeeTable, #sysadminTable').DataTable({
            responsive: true,
            pageLength: 10,
            columnDefs: [{ orderable: false, targets: -1 }]
        });

        function toggleDepartmentField() {
            const selectedRole = $('#roleSelect').val();
            if (selectedRole === 'system_admin') {
                $('#departmentField').hide();
            } else {
                $('#departmentField').show();
            }
        }

        function generateUsername() {
            const first = $('#firstName').val().trim().toUpperCase();
            const last = $('#lastName').val().trim().toUpperCase();
            if (first && last) {
                const username = first.charAt(0) + last.replace(/\s+/g, '');
                $('#generatedUsername').val(username);
            } else {
                $('#generatedUsername').val('');
            }
        }

        // Initial logic
        toggleDepartmentField();
        generateUsername();

        // Bind events
        $('#roleSelect').on('change', toggleDepartmentField);
        $('#firstName, #lastName').on('input', generateUsername);
    });
</script>
@endsection
