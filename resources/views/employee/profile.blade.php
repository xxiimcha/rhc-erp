@extends('layouts.admin')

@section('title', 'My Profile')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="row mb-4">
            <div class="col-12">
                <h4 class="page-title">My Profile</h4>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Employee Information</h5>
            </div>
            <div class="card-body">
                <div class="row align-items-start">
                    {{-- LEFT COLUMN --}}
                    <div class="col-md-4 text-center border-end">
                        <img src="{{ $employee->photo_path ? asset('employees/' . $employee->photo_path) : asset('assets/images/default-avatar.png') }}"
                             class="img-fluid rounded-circle mb-3" style="max-width: 150px;" alt="Profile Image">

                        <h5 class="fw-bold">{{ $employee->first_name }} {{ $employee->last_name }}</h5>
                        <p class="text-muted mb-1">{{ $employee->position }}</p>
                        <p class="text-muted">{{ $employee->department_name }}</p>

                        <hr class="my-4">

                        {{-- Emergency Contact --}}
                        <div class="card border mb-4">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-semibold">Emergency Contact Information</h6>
                                <span class="badge bg-info text-dark">Personal</span>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('employee.profile.updateEmergency') }}">
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Contact Person:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            <input type="text" name="emergency_contact_name" class="form-control"
                                                value="{{ $emergencyContact->name ?? '' }}" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Contact Number:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            <input type="text" name="emergency_contact_number" class="form-control"
                                                value="{{ $emergencyContact->contact_number ?? '' }}" required>
                                        </div>
                                    </div>

                                    @if(isset($emergencyContact->updated_at))
                                        <p class="text-muted small mt-2">
                                            Last Updated: {{ \Carbon\Carbon::parse($emergencyContact->updated_at)->format('F d, Y h:i A') }}
                                        </p>
                                    @endif

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-save me-1"></i> Save Contact Details
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Government IDs --}}
                        <div class="card border">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-semibold">Government Identification</h6>
                                <span class="badge bg-secondary">Confidential</span>
                            </div>
                            <div class="card-body text-start">
                                <div class="mb-2"><strong>SSS Number:</strong> {{ $employee->sss_no ?? 'N/A' }}</div>
                                <div class="mb-2"><strong>PhilHealth Number:</strong> {{ $employee->philhealth_no ?? 'N/A' }}</div>
                                <div class="mb-2"><strong>Pag-IBIG Number:</strong> {{ $employee->pagibig_no ?? 'N/A' }}</div>
                                <div class="mb-2"><strong>TIN Number:</strong> {{ $employee->tin_no ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT COLUMN --}}
                    <div class="col-md-8">
                        <div class="row mb-3">
                            <div class="col-sm-4 fw-semibold">Employee No:</div>
                            <div class="col-sm-8">{{ $employee->employee_id }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4 fw-semibold">Email:</div>
                            <div class="col-sm-8">{{ $employee->email }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4 fw-semibold">Contact No:</div>
                            <div class="col-sm-8">{{ $employee->contact_number }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4 fw-semibold">Birthdate:</div>
                            <div class="col-sm-8">{{ \Carbon\Carbon::parse($employee->birthdate)->format('F d, Y') }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4 fw-semibold">Address:</div>
                            <div class="col-sm-8">{{ $employee->address }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4 fw-semibold">Civil Status:</div>
                            <div class="col-sm-8">{{ $employee->civil_status ?? 'N/A' }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4 fw-semibold">Gender:</div>
                            <div class="col-sm-8">{{ $employee->gender ?? 'N/A' }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4 fw-semibold">Date Hired:</div>
                            <div class="col-sm-8">
                                {{ $employee->date_hired ? \Carbon\Carbon::parse($employee->date_hired)->format('F d, Y') : 'N/A' }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4 fw-semibold">Position Level:</div>
                            <div class="col-sm-8">{{ $employee->position ?? 'N/A' }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4 fw-semibold">Employment Status:</div>
                            <div class="col-sm-8">{{ $employee->employment_type ?? 'N/A' }}</div>
                        </div>
                    </div> {{-- /.col-md-8 --}}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
