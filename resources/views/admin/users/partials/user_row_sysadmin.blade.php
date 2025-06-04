<tr>
    <td>{{ $user->name }}</td>
    <td>{{ $user->username }}</td>
    <td>
        @if ($user->card_id)
            {{ $user->card_id }}
        @else
            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#assignCardModal{{ $user->id }}">
                Assign Card Number
            </button>
        @endif
    </td>
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
