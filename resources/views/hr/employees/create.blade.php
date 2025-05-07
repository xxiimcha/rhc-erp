@extends('layouts.admin')

@section('title', 'Add New Employee')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="row mb-4">
            <div class="col-sm-6">
                <h4 class="page-title">Add New Employee</h4>
            </div>
            <div class="col-sm-6 text-sm-end">
                <a href="{{ route('admin.hr.employees.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to Records
                </a>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <form id="employeeForm" method="POST" action="{{ route('admin.hr.employees.store') }}">
                    @csrf
                    {{-- Personal Information --}}
                    <h5 class="mb-3">Personal Information</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Middle Name</label>
                            <input type="text" name="middle_name" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Suffix</label>
                            <input type="text" name="suffix" class="form-control">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contact_number" class="form-control" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select" required>
                                <option value="">Select</option>
                                <option value="female">Female</option>
                                <option value="male">Male</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="2" required></textarea>
                        </div>
                    </div>

                    <hr class="my-4">
                    {{-- Employment Details --}}
                    <h5 class="mb-3">Employment Details</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Position</label>
                            <input type="text" name="position" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Department</label>
                            <select name="department" class="form-select" required>
                                <option value="">Select Department</option>
                                <option value="Human Resources">Human Resources</option>
                                <option value="Finance">Finance</option>
                                <option value="IT">IT</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Operations">Operations</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Employment Type</label>
                            <select name="employment_type" class="form-select" required>
                                <option value="regular">Regular</option>
                                <option value="contractual">Contractual</option>
                                <option value="probationary">Probationary</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Date Hired</label>
                            <input type="date" name="date_hired" class="form-control" required>
                        </div>
                    </div>

                    <hr class="my-4">
                    {{-- Government IDs --}}
                    <h5 class="mb-3">Government IDs / Numbers</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">PhilHealth Number</label>
                            <input type="text" name="philhealth_no" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">SSS Number</label>
                            <input type="text" name="sss_no" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pag-IBIG Number</label>
                            <input type="text" name="pagibig_no" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">TIN Number</label>
                            <input type="text" name="tin_no" class="form-control">
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Save Employee
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Summary Modal --}}
<div class="modal fade" id="summaryModal" tabindex="-1" aria-labelledby="summaryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="summaryModalLabel">Review Employee Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <ul id="summaryList" class="list-group">
          <!-- Populated by JS -->
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Edit</button>
        <button type="button" class="btn btn-success" id="confirmSubmit">Confirm & Save</button>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('employeeForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);
    const summaryList = document.getElementById('summaryList');
    summaryList.innerHTML = ''; // Clear

    const summaryFields = {
        'first_name': 'First Name',
        'middle_name': 'Middle Name',
        'last_name': 'Last Name',
        'suffix': 'Suffix',
        'email': 'Email Address',
        'contact_number': 'Contact Number',
        'date_of_birth': 'Date of Birth',
        'gender': 'Gender',
        'address': 'Address',
        'position': 'Position',
        'department': 'Department',
        'employment_type': 'Employment Type',
        'date_hired': 'Date Hired',
        'philhealth_no': 'PhilHealth No.',
        'sss_no': 'SSS No.',
        'pagibig_no': 'Pag-IBIG No.',
        'tin_no': 'TIN No.'
    };

    for (const [name, label] of Object.entries(summaryFields)) {
        const value = formData.get(name) || '-';
        summaryList.innerHTML += `<li class="list-group-item d-flex justify-content-between"><strong>${label}</strong><span>${value}</span></li>`;
    }

    const modal = new bootstrap.Modal(document.getElementById('summaryModal'));
    modal.show();

    document.getElementById('confirmSubmit').onclick = () => {
        form.submit();
    };
});
</script>
@endsection
