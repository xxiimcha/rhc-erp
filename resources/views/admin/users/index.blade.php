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
                                    <th>Card ID</th> <!-- Added -->
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

        <!-- Assign Card Modal -->
        <div class="modal fade" id="assignCardModal{{ $user->id }}" tabindex="-1" aria-labelledby="assignCardModalLabel{{ $user->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <form id="assignCardForm{{ $user->id }}" class="modal-content shadow rounded">
                    @csrf
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title w-100 text-center m-0" id="assignCardModalLabel{{ $user->id }}">
                            Assign Card Number
                        </h5>
                        <button type="button" class="btn-close btn-close-white position-absolute end-0 me-2" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-center text-muted mb-3">Please tap the RFID card on the reader.</p>
                        <input type="text"
                            name="card_id"
                            id="cardInput{{ $user->id }}"
                            class="form-control text-center fs-5 fw-bold py-3"
                            placeholder="Waiting for card..."
                            required autofocus>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success w-100" onclick="submitAssignCard({{ $user->id }})">Save</button>
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
    
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('assignCardModal{{ $user->id }}');
        const input = document.getElementById('cardInput{{ $user->id }}');

        if (modal && input) {
            modal.addEventListener('shown.bs.modal', function () {
                input.focus();
            });

            // Prevent form submission when pressing enter
            input.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                }
            });
        }
    });

    function submitAssignCard(userId) {
        const input = document.getElementById(`cardInput${userId}`);
        const cardId = input.value.trim();
        console.log('Submitting card ID:', cardId); // Added console log

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
            console.log('Server response:', data); // Added console log
            if (data.success) {
                showToast('Card successfully assigned!', 'success');
                document.getElementById(`assignCardModal${userId}`).querySelector('.btn-close').click();
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'Something went wrong.', 'danger');
            }
        })
        .catch((error) => {
            console.error('Error submitting card:', error); // Added console log
            showToast('Failed to assign card. Try again.', 'danger');
        });
    }

    function showToast(message, type = 'success') {
        const toastContainer = document.createElement('div');
        toastContainer.className = `toast align-items-center text-white bg-${type} border-0 position-fixed top-0 end-0 m-4`;
        toastContainer.setAttribute('role', 'alert');
        toastContainer.setAttribute('aria-live', 'assertive');
        toastContainer.setAttribute('aria-atomic', 'true');

        toastContainer.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;

        document.body.appendChild(toastContainer);
        const bsToast = new bootstrap.Toast(toastContainer);
        bsToast.show();
        setTimeout(() => toastContainer.remove(), 4000);
    }
</script>
@endsection
