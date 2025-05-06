@extends('layouts.admin')

@section('title', 'Roles & Permissions')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="row mb-4">
            <div class="col-sm-6">
                <h4 class="page-title">Roles & Permissions</h4>
            </div>
            <div class="col-sm-6 text-sm-end">
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                    <i class="fas fa-plus"></i> Add Role
                </button>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <table id="rolesTable" class="table table-bordered table-striped dt-responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Role</th>
                            <th class="text-center">Manage Users</th>
                            <th class="text-center">Edit Settings</th>
                            <th class="text-center">View Dashboard</th>
                            <th class="text-center">View Reports</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Static data with editable permission checkboxes --}}
                        <tr>
                            <td><strong>Admin</strong></td>
                            <td class="text-center">
                                <input type="checkbox" class="perm-toggle" data-role="admin" data-permission="manage_users" checked>
                            </td>
                            <td class="text-center">
                                <input type="checkbox" class="perm-toggle" data-role="admin" data-permission="edit_settings" checked>
                            </td>
                            <td class="text-center">
                                <input type="checkbox" class="perm-toggle" data-role="admin" data-permission="view_dashboard">
                            </td>
                            <td class="text-center">
                                <input type="checkbox" class="perm-toggle" data-role="admin" data-permission="view_reports" checked>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>User</strong></td>
                            <td class="text-center">
                                <input type="checkbox" class="perm-toggle" data-role="user" data-permission="manage_users">
                            </td>
                            <td class="text-center">
                                <input type="checkbox" class="perm-toggle" data-role="user" data-permission="edit_settings">
                            </td>
                            <td class="text-center">
                                <input type="checkbox" class="perm-toggle" data-role="user" data-permission="view_dashboard" checked>
                            </td>
                            <td class="text-center">
                                <input type="checkbox" class="perm-toggle" data-role="user" data-permission="view_reports">
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Add Role Modal --}}
        <div class="modal fade" id="addRoleModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <form class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Add New Role</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Role Name</label>
                            <input type="text" class="form-control" name="role_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Assign Permissions</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="manage_users">
                                        <label class="form-check-label">Manage Users</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="edit_settings">
                                        <label class="form-check-label">Edit Settings</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="view_dashboard">
                                        <label class="form-check-label">View Dashboard</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="view_reports">
                                        <label class="form-check-label">View Reports</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save Role</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
    $(function () {
        $('#rolesTable').DataTable({
            responsive: true,
            pageLength: 10,
            columnDefs: [{ orderable: false, targets: -1 }]
        });

        // Permission toggle handler (placeholder for AJAX)
        $('.perm-toggle').on('change', function () {
            const role = $(this).data('role');
            const permission = $(this).data('permission');
            const isChecked = $(this).is(':checked');

            console.log(`Role: ${role}, Permission: ${permission}, Allowed: ${isChecked}`);

            // TODO: Replace with AJAX call to update the backend
        });
    });
</script>
@endsection
