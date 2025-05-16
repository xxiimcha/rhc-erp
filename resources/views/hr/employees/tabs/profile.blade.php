<div class="row g-4">

    {{-- Left Column: Profile Picture --}}
    <div class="col-md-4">
        <div class="card shadow-sm text-center border-0">
            <div class="card-body">

                {{-- Profile Picture with Preview --}}
                <div class="position-relative d-inline-block mb-3">
                <img id="preview-photo"
                    data-original="{{ $employee->photo_path 
                        ? asset('storage/employees/' . $employee->photo_path) 
                        : asset('assets/images/avatar-placeholder.jpg') }}"
                    src="{{ $employee->photo_path 
                        ? asset('storage/employees/' . $employee->photo_path) 
                        : asset('assets/images/avatar-placeholder.jpg') }}"
                    alt="Profile Photo"
                    class="rounded-circle border border-2"
                    style="width: 150px; height: 150px; object-fit: cover;">

                    {{-- Camera Button --}}
                    <button type="button"
                            class="btn btn-light position-absolute bottom-0 end-0 shadow rounded-circle"
                            style="transform: translate(25%, 25%); padding: 6px 8px;"
                            data-bs-toggle="modal"
                            data-bs-target="#uploadPhotoModal">
                        <i class="fas fa-camera"></i>
                    </button>
                </div>

                <h5 class="mt-3 mb-1 fw-semibold">
                    {{ $employee->first_name }} {{ $employee->middle_name }} {{ $employee->last_name }}
                </h5>
                <p class="text-muted mb-0">{{ $employee->position }}</p>
                <p class="text-muted">{{ $employee->department }}</p>
            </div>
        </div>
    </div>

    {{-- Right Column: Info Tables --}}
    <div class="col-md-8">

        {{-- Personal Info --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3">Personal Information</h5>
                <div class="table-responsive">
                    <table class="table table-sm table-borderless mb-0">
                        <tbody>
                            <tr><th>Email</th><td class="text-muted">{{ $employee->email ?? 'N/A' }}</td></tr>
                            <tr><th>Contact Number</th><td class="text-muted">{{ $employee->contact_number ?? 'N/A' }}</td></tr>
                            <tr><th>Gender</th><td class="text-muted">{{ $employee->gender ? ucfirst($employee->gender) : 'N/A' }}</td></tr>
                            <tr><th>Birthdate</th><td class="text-muted">{{ $employee->date_of_birth }}</td></tr>
                            <tr><th>Address</th><td class="text-muted">{{ $employee->address ?? 'N/A' }}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Employment Info --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3">Employment Details</h5>
                <div class="table-responsive">
                    <table class="table table-sm table-borderless mb-0">
                        <tbody>
                            <tr><th>Employee ID</th><td class="text-muted">{{ $employee->employee_id }}</td></tr>
                            <tr><th>Employment Type</th><td class="text-muted">{{ ucfirst($employee->employment_type) }}</td></tr>
                            <tr><th>Date Hired</th><td class="text-muted">{{ $employee->date_hired }}</td></tr>
                            <tr><th>Monthly Salary</th><td class="text-muted">₱{{ number_format($employee->latestSalary?->amount ?? 0, 2) }}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Government IDs --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">Government Identifications</h5>
                <div class="table-responsive">
                    <table class="table table-sm table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th>PhilHealth No</th><td class="text-muted">{{ $employee->philhealth_no ?? 'N/A' }}</td>
                                <th>SSS No</th><td class="text-muted">{{ $employee->sss_no ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Pag-IBIG No</th><td class="text-muted">{{ $employee->pagibig_no ?? 'N/A' }}</td>
                                <th>TIN No</th><td class="text-muted">{{ $employee->tin_no ?? 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Upload Modal --}}
<div class="modal fade" id="uploadPhotoModal" tabindex="-1" aria-labelledby="uploadPhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.hr.employees.photo.upload', $employee->id) }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="uploadPhotoModalLabel">Upload Profile Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                {{-- Modal Preview --}}
                <div class="text-center mb-3">
                    <img id="modal-preview-photo"
                        data-original="{{ $employee->photo_path 
                            ? asset('storage/employees/' . $employee->photo_path) 
                            : asset('assets/images/avatar-placeholder.jpg') }}"
                        src="{{ $employee->photo_path 
                            ? asset('storage/employees/' . $employee->photo_path) 
                            : asset('assets/images/avatar-placeholder.jpg') }}"
                        class="rounded-circle border"
                        style="width: 120px; height: 120px; object-fit: cover;"
                        alt="Modal Preview">
                </div>

                {{-- Drag & Drop Area --}}
                <div class="mb-3 text-center">
                    <label for="photo" class="form-label d-block">Drag & drop or click to select</label>
                    <div id="drop-zone" class="border border-2 rounded p-4" style="cursor: pointer;">
                        <i class="fas fa-upload fa-2x mb-2 text-muted"></i>
                        <p class="text-muted">Drag image here or click to browse</p>
                        <input type="file" name="photo" id="photo" accept="image/*" class="form-control d-none">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Upload</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>
<script>
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('photo');
    const previewPhoto = document.getElementById('preview-photo');
    const modalPreview = document.getElementById('modal-preview-photo');
    const uploadModal = document.getElementById('uploadPhotoModal');

    // Drag & drop file selection
    dropZone.addEventListener('click', () => fileInput.click());

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('bg-light');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('bg-light');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('bg-light');
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            fileInput.files = e.dataTransfer.files;
            previewImage(file);
        }
    });

    fileInput.addEventListener('change', () => {
        const file = fileInput.files[0];
        if (file && file.type.startsWith('image/')) {
            previewImage(file);
        }
    });

    function previewImage(file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            previewPhoto.src = e.target.result;
            modalPreview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    // Reset previews to original when modal closes
    uploadModal.addEventListener('hidden.bs.modal', () => {
        previewPhoto.src = previewPhoto.dataset.original;
        modalPreview.src = modalPreview.dataset.original;
        fileInput.value = ''; // reset file input
    });
</script>
