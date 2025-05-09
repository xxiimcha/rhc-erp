<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Edit User - {{ $user->name }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="role" value="{{ $user->role }}">

                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" value="{{ $user->username }}" readonly>
                </div>

                @php
                    $selectedDepartments = explode(',', $user->department);
                @endphp
                <div class="mb-3">
                    <label class="form-label">Department(s)</label>
                    <select name="department[]" class="form-select" multiple required>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept }}" {{ in_array($dept, $selectedDepartments) ? 'selected' : '' }}>
                                {{ $dept }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="is_active" class="form-select">
                        <option value="1" {{ $user->is_active ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Save Changes</button>
            </div>
        </form>
    </div>
</div>
