@extends('layouts.admin')

@section('title', 'My Leaves')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="page-title mb-0">📋 My Leave Requests</h4>
            <a href="{{ route('employee.leaves.form') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-plus-circle me-1"></i> Apply for Leave
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Filter and Tabs --}}
        <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap">
            {{-- Month Filter --}}
            <form method="GET" class="d-flex align-items-center gap-2">
                <label for="month" class="mb-0 fw-semibold">Filter by Month:</label>
                <select name="month" id="month" class="form-select form-select-sm" onchange="this.form.submit()">
                    @php
                        $now = now();
                        for ($i = 0; $i < 12; $i++) {
                            $month = $now->copy()->subMonths($i);
                            $value = $month->format('Y-m');
                            $label = $month->format('F Y');
                            $selected = request('month') === $value ? 'selected' : '';
                            echo "<option value=\"$value\" $selected>$label</option>";
                        }
                    @endphp
                </select>
            </form>

            {{-- Tabs --}}
            <ul class="nav nav-tabs mt-3 mt-md-0" id="leaveTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ request('tab') === 'upcoming' || !request('tab') ? 'active' : '' }}"
                       href="{{ route('employee.leaves.index', ['tab' => 'upcoming', 'month' => request('month')]) }}">
                        Upcoming
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('tab') === 'completed' ? 'active' : '' }}"
                       href="{{ route('employee.leaves.index', ['tab' => 'completed', 'month' => request('month')]) }}">
                        Completed
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('tab') === 'cancelled' ? 'active' : '' }}"
                       href="{{ route('employee.leaves.index', ['tab' => 'cancelled', 'month' => request('month')]) }}">
                        Cancelled
                    </a>
                </li>
            </ul>
        </div>

        {{-- Leaves Display --}}
        @php
            $tab = request('tab') ?? 'upcoming';
            $month = request('month');
            $filteredLeaves = $leaves->filter(function ($leave) use ($tab, $month) {
                $start = \Carbon\Carbon::parse($leave->start_date);
                $end = \Carbon\Carbon::parse($leave->end_date);
                $now = now();
                $matchesMonth = $month ? $start->format('Y-m') === $month : true;

                if ($tab === 'completed') {
                    return $leave->status === 'approved' && $end->lt($now) && $matchesMonth;
                } elseif ($tab === 'cancelled') {
                    return $leave->status === 'cancelled' && $matchesMonth;
                } else {
                    return in_array($leave->status, ['pending', 'approved']) && $start->gte($now) && $matchesMonth;
                }
            });
        @endphp

        @forelse($filteredLeaves as $leave)
            <div class="card border-start border-4 
                @if($leave->type === 'Vacation') border-success 
                @elseif($leave->type === 'Sick') border-warning 
                @elseif($leave->type === 'Birthday') border-info 
                @else border-secondary @endif
                mb-3 shadow-sm position-relative">

                {{-- Badge + Ellipsis --}}
                <div class="position-absolute top-0 end-0 p-2 text-end">
                    <div class="d-flex align-items-center justify-content-end gap-2">
                        @if($leave->status === 'approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif($leave->status === 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @elseif($leave->status === 'cancelled')
                            <span class="badge bg-danger">Cancelled</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                        
                        {{-- Dropdown (hidden when cancelled) --}}
                        @if($leave->status !== 'cancelled')
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    @if($leave->status === 'pending')
                                        <li>
                                            <button type="button" class="dropdown-item text-danger" onclick="confirmCancel({{ $leave->id }})">
                                                Cancel
                                            </button>
                                        </li>
                                    @endif
                                    @if($leave->attachment)
                                        <li>
                                            <a href="{{ asset('storage/' . $leave->attachment) }}" target="_blank" class="dropdown-item">
                                                <i class="fas fa-paperclip me-1"></i> View Attachment
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        @endif

                    </div>

                    {{-- Pay Badges --}}
                    <div class="mt-2">
                        @if($leave->with_pay)
                            <span class="badge bg-primary d-block">With Pay</span>
                        @endif
                        @if($leave->without_pay)
                            <span class="badge bg-secondary d-block">Without Pay</span>
                        @endif
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="card-body pe-5">
                    <h5 class="mb-1">
                        <i class="fas fa-plane-departure me-1 text-muted"></i>
                        <strong>{{ $leave->type }} Leave</strong>
                    </h5>
                    <p class="mb-0 text-muted small">
                        <i class="fas fa-calendar-alt me-1"></i>
                        {{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }} —
                        {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                    </p>
                    <p class="mb-2 text-muted small">
                        <i class="fas fa-clock me-1"></i>
                        Filed on {{ \Carbon\Carbon::parse($leave->created_at)->format('M d, Y h:i A') }}
                    </p>

                    <hr class="my-2">

                    <div>
                        <strong class="d-block text-muted">Reason:</strong>
                        <p class="mb-0">{{ $leave->reason }}</p>
                    </div>
                </div>

                {{-- Hidden Cancel Form --}}
                <form id="cancel-form-{{ $leave->id }}" method="POST" action="{{ route('employee.leaves.cancel', $leave->id) }}">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        @empty
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-1"></i> No leave requests found for this view.
            </div>
        @endforelse

    </div>
</div>

{{-- SweetAlert CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Confirmation Script --}}
<script>
    function confirmCancel(leaveId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This leave request will be cancelled.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, cancel it!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('cancel-form-' + leaveId).submit();
            }
        });
    }
</script>
@endsection
