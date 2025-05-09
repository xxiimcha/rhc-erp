<tr>
    <td>{{ $user->name }}</td>
    <td>{{ $user->email }}</td>
    <td>{{ str_replace(',', ', ', $user->department) }}</td>
    <td>
        <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
            {{ $user->is_active ? 'Active' : 'Inactive' }}
        </span>
    </td>
    <td class="text-center">
        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
            <i class="fas fa-edit"></i>
        </button>
        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    </td>
</tr>
