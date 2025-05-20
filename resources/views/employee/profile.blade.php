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
                    <div class="col-md-4 text-center border-end">
                    <img src="{{ $employee->photo_path ? asset('employees/' . $employee->photo_path) : asset('assets/images/default-avatar.png') }}"
     class="img-fluid rounded-circle mb-3" style="max-width: 150px;" alt="Profile Image">


                        <h5 class="fw-bold">{{ $employee->first_name }} {{ $employee->last_name }}</h5>
                        <p class="text-muted mb-1">{{ $employee->position }}</p>
                        <p class="text-muted">{{ $employee->department_name }}</p>
                    </div>

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
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
