<tr>
    <td>{{ $user->name }}</td>
    <td>{{ $user->username }}</td>
    <td>{{ ucfirst($user->role) }}</td>
    <td>
        @if ($user->is_active)
            <span class="badge bg-success">Active</span>
        @else
            <span class="badge bg-danger">Inactive</span>
        @endif
    </td>
    <td class="text-center">
        <button class="btn btn-sm btn-primary me-1" title="Edit">
            <i class="fas fa-edit"></i>
        </button>
        <button class="btn btn-sm btn-danger" title="Delete">
            <i class="fas fa-trash-alt"></i>
        </button>
    </td>
</tr>
