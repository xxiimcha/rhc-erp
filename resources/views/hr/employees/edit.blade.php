@extends('layouts.admin')

@section('title', 'Edit Employee')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="row mb-4">
            <div class="col-sm-6">
                <h4 class="page-title">Edit Employee</h4>
            </div>
            <div class="col-sm-6 text-sm-end">
                <a href="{{ route('admin.hr.employees.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to Records
                </a>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.hr.employees.update', $employee->id) }}">
                    @csrf
                    @method('PUT')

                    <h5 class="mb-3">Personal Information</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" value="{{ $employee->first_name }}" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Middle Name</label>
                            <input type="text" name="middle_name" value="{{ $employee->middle_name }}" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" value="{{ $employee->last_name }}" class="form-control" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Suffix</label>
                            <input type="text" name="suffix" value="{{ $employee->suffix }}" class="form-control">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ $employee->email }}" class="form-control">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contact_number" value="{{ $employee->contact_number }}" class="form-control">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="date_of_birth" value="{{ $employee->date_of_birth }}" class="form-control">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">Select</option>
                                <option value="female" @if($employee->gender === 'female') selected @endif>Female</option>
                                <option value="male" @if($employee->gender === 'male') selected @endif>Male</option>
                                <option value="other" @if($employee->gender === 'other') selected @endif>Other</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="2" required>{{ $employee->address }}</textarea>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3">Employment Details</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Position</label>
                            <input type="text" name="position" value="{{ $employee->position }}" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Department</label>
                            <select name="department" class="form-select" required>
                                <option value="">Select Department</option>
                                @foreach(['MIS', 'Franchise/FSG', 'Accounting', 'BWES', 'Maintenance', 'Executive', 'Marketing', 'RSC', 'Company Own'] as $dept)
                                    <option value="{{ $dept }}" @if($employee->department === $dept) selected @endif>{{ $dept }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Employment Type</label>
                            <select name="employment_type" class="form-select">
                                <option value="">Select Type</option>
                                <option value="regular" @if($employee->employment_type === 'regular') selected @endif>Regular</option>
                                <option value="contractual" @if($employee->employment_type === 'contractual') selected @endif>Contractual</option>
                                <option value="probationary" @if($employee->employment_type === 'probationary') selected @endif>Probationary</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Date Hired</label>
                            <input type="date" name="date_hired" value="{{ $employee->date_hired }}" class="form-control">
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3">Government IDs / Numbers</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">PhilHealth Number</label>
                            <input type="text" name="philhealth_no" value="{{ $employee->philhealth_no }}" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">SSS Number</label>
                            <input type="text" name="sss_no" value="{{ $employee->sss_no }}" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pag-IBIG Number</label>
                            <input type="text" name="pagibig_no" value="{{ $employee->pagibig_no }}" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">TIN Number</label>
                            <input type="text" name="tin_no" value="{{ $employee->tin_no }}" class="form-control">
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Update Employee
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
