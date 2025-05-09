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
                </ul>

                {{-- Tab Content --}}
                <div class="tab-content mt-3" id="userTabsContent">
                    {{-- Admin Tab --}}
                    <div class="tab-pane fade show active" id="admin" role="tabpanel">
                        <table class="table table-bordered table-striped dt-responsive nowrap" id="adminTable" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
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

                    {{-- Employee Tab --}}
                    <div class="tab-pane fade" id="employee" role="tabpanel">
                        <table class="table table-bordered table-striped dt-responsive nowrap" id="employeeTable" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
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
                    <input type="hidden" name="role" value="admin">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Department</label>
                        <select name="department" class="form-select" required>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept }}">{{ $dept }}</option>
                            @endforeach
                        </select>
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
        $('#adminTable, #employeeTable').DataTable({
            responsive: true,
            pageLength: 10,
            columnDefs: [{ orderable: false, targets: -1 }]
        });
    });
</script>
@endsection
