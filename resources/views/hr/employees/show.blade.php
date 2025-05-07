@extends('layouts.admin')

@section('title', 'Employee Details')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Employee Details: {{ $employee->employee_id }}</h4>
            <a href="{{ route('admin.hr.employees.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Records
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <ul class="nav nav-tabs mb-4" id="employeeTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">Profile Info</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="attendance-tab" data-bs-toggle="tab" data-bs-target="#attendance" type="button" role="tab">Attendance</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="leaves-tab" data-bs-toggle="tab" data-bs-target="#leaves" type="button" role="tab">Leaves</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">Documents</button>
                    </li>
                </ul>

                <div class="tab-content" id="employeeTabContent">
                    {{-- Profile Tab --}}
                    <div class="tab-pane fade show active" id="profile" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Full Name:</strong> {{ $employee->first_name }} {{ $employee->middle_name }} {{ $employee->last_name }}</p>
                                <p><strong>Email:</strong> {{ $employee->email }}</p>
                                <p><strong>Contact Number:</strong> {{ $employee->contact_number }}</p>
                                <p><strong>Gender:</strong> {{ ucfirst($employee->gender) }}</p>
                                <p><strong>Date of Birth:</strong> {{ $employee->date_of_birth }}</p>
                                <p><strong>Address:</strong> {{ $employee->address }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Position:</strong> {{ $employee->position }}</p>
                                <p><strong>Department:</strong> {{ $employee->department }}</p>
                                <p><strong>Employment Type:</strong> {{ ucfirst($employee->employment_type) }}</p>
                                <p><strong>Date Hired:</strong> {{ $employee->date_hired }}</p>
                                <hr>
                                <p><strong>PhilHealth No:</strong> {{ $employee->philhealth_no }}</p>
                                <p><strong>SSS No:</strong> {{ $employee->sss_no }}</p>
                                <p><strong>Pag-IBIG No:</strong> {{ $employee->pagibig_no }}</p>
                                <p><strong>TIN No:</strong> {{ $employee->tin_no }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Attendance Tab --}}
                    <div class="tab-pane fade" id="attendance" role="tabpanel">
                        <p>Attendance records will go here.</p>
                        {{-- You can include a partial here if needed --}}
                    </div>

                    {{-- Leaves Tab --}}
                    <div class="tab-pane fade" id="leaves" role="tabpanel">
                        <p>Leave records will go here.</p>
                    </div>

                    {{-- Documents Tab --}}
                    <div class="tab-pane fade" id="documents" role="tabpanel">
                        <p>Uploaded employee documents will go here.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
