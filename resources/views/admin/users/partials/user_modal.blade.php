<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
  <div class="modal-dialog">
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="modal-content">
        @csrf @method('PUT')
        <div class="modal-header bg-info text-white">
            <h5 class="modal-title">Edit User</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="role" value="admin">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Department</label>
                <select name="department" class="form-select" required>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept }}" {{ $user->department == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                </select>
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
