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
                                    @include('admin.users.partials.user_row_admin')
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
                                    @include('admin.users.partials.user_row_employee')
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
                                    <th>Card ID</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users->where('role', 'system_admin') as $user)
                                    @include('admin.users.partials.user_row_sysadmin')
                                @endforeach
                            </tbody>
                        </table>

                        {{-- Move modals here --}}
                        @foreach ($users->where('role', 'system_admin') as $user)
                            <div class="modal fade" id="assignCardModal{{ $user->id }}" tabindex="-1" aria-labelledby="assignCardModalLabel{{ $user->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                    <form id="assignCardForm{{ $user->id }}" class="modal-content shadow rounded"
                                        onsubmit="submitAssignCard(event, {{ $user->id }})">
                                        @csrf
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title w-100 text-center m-0" id="assignCardModalLabel{{ $user->id }}">
                                                Assign Card Number
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white position-absolute end-0 me-2" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <p class="text-muted">Please tap the RFID card on the reader.</p>
                                            <input type="text"
                                                name="card_id"
                                                id="cardInput{{ $user->id }}"
                                                class="form-control form-control-lg text-center fw-bold"
                                                placeholder="Waiting for card..."
                                                required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-success w-100" onclick="submitAssignCard({{ $user->id }})">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
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
                    <input type="hidden" name="department[]" value="" id="emptyDepartmentFallback">


                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Create User</button>
                </div>
            </form>
          </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#adminTable, #employeeTable, #sysadminTable').DataTable({
            responsive: true,
            pageLength: 10,
            columnDefs: [{ orderable: false, targets: -1 }]
        });

        
        function toggleDepartmentField() {
            const selectedRole = $('#roleSelect').val();
            const $departmentField = $('#departmentField');
            const $departmentSelect = $departmentField.find('select');
            const $fallbackHiddenInput = $('#emptyDepartmentFallback');

            if (selectedRole === 'system_admin') {
                $departmentField.hide();
                $departmentSelect.prop('disabled', true);
                $fallbackHiddenInput.prop('disabled', false);
            } else {
                $departmentField.show();
                $departmentSelect.prop('disabled', false);
                $fallbackHiddenInput.prop('disabled', true);
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

        toggleDepartmentField();
        generateUsername();

        $('#roleSelect').on('change', toggleDepartmentField);
        $('#firstName, #lastName').on('input', generateUsername);
    });
    
    function submitAssignCard(event, userId) {
        if (event) event.preventDefault(); // Prevent default form submission

        const input = document.getElementById(`cardInput${userId}`);
        const cardId = input.value.trim();

        if (!cardId) {
            showToast('Card ID is required', 'danger');
            return;
        }

        const formData = new FormData();
        formData.append('card_id', cardId);

        fetch(`/admin/users/${userId}/assign-card`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Card successfully assigned!', 'success');
                document.getElementById(`assignCardModal${userId}`).querySelector('.btn-close').click();
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'Something went wrong.', 'danger');
            }
        })
        .catch((error) => {
            console.error('Error submitting card:', error);
            showToast('Failed to assign card. Try again.', 'danger');
        });
    }


    function showToast(message, type = 'success') {
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "4000"
        };

        if (type === 'success') toastr.success(message);
        else if (type === 'danger' || type === 'error') toastr.error(message);
        else if (type === 'warning') toastr.warning(message);
        else toastr.info(message);
    }
</script>
@endsection
