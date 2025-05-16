@php
    $currentYear = now()->year;
    $currentMonth = now()->month;
    $selectedYear = request('year', $currentYear);
    $selectedMonth = request('month', now()->format('m'));

    $years = range($currentYear - 2, $currentYear); // Adjust if needed
@endphp

{{-- Year Tabs --}}
<ul class="nav nav-tabs mb-3">
    @foreach ($years as $year)
        <li class="nav-item">
            <a class="nav-link {{ $year == $selectedYear ? 'active' : '' }}"
               href="?year={{ $year }}&month=01">
                {{ $year }}
            </a>
        </li>
    @endforeach
</ul>

{{-- Month Tabs within Selected Year --}}
<ul class="nav nav-pills mb-4 flex-wrap">
    @for ($m = 1; $m <= 12; $m++)
        @continue($selectedYear == $currentYear && $m > $currentMonth)
        @php $mPadded = str_pad($m, 2, '0', STR_PAD_LEFT); @endphp
        <li class="nav-item me-1 mb-1">
            <a class="nav-link {{ $mPadded == $selectedMonth ? 'active' : '' }}"
               href="?year={{ $selectedYear }}&month={{ $mPadded }}">
                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
            </a>
        </li>
    @endfor
</ul>

@php
    $month = "$selectedYear-$selectedMonth";
    $startOfMonth = \Carbon\Carbon::parse($month)->startOfMonth();
    $endOfMonth = \Carbon\Carbon::parse($month)->endOfMonth();
@endphp

{{-- Calendar View --}}
<div class="table-responsive">
    <table class="table table-bordered text-center align-middle">
        <thead class="table-dark">
            <tr>
                @for ($i = 0; $i < 7; $i++)
                    <th>{{ \Carbon\Carbon::now()->startOfWeek()->addDays($i)->format('l') }}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @php $date = $startOfMonth->copy()->startOfWeek(); @endphp
            @while ($date->lessThanOrEqualTo($endOfMonth->copy()->endOfWeek()))
                <tr>
                    @for ($i = 0; $i < 7; $i++)
                        @php
                            $current = $date->copy();
                            $dateStr = $current->format('Y-m-d');
                            $record = $clockings[$dateStr][0] ?? null;
                            $isAbsent = !$record && $current->isPast() && $current->month == $startOfMonth->month;
                            $holiday = $holidays[$dateStr] ?? null;
                            $isCurrentMonth = $current->format('Y-m') === now()->format('Y-m');
                        @endphp
                        <td class="p-1 align-top {{ $current->month != $startOfMonth->month ? 'bg-light' : '' }} {{ $isAbsent ? 'bg-danger text-white' : '' }} {{ $holiday ? 'bg-info' : '' }}">
                            <div class="fw-bold">{{ $current->format('d') }}</div>
                            <div class="small">
                                <strong>In:</strong> {{ $record?->time_in ? \Carbon\Carbon::parse($record->time_in)->format('h:i A') : '-' }}<br>
                                <strong>Out:</strong> {{ $record?->time_out ? \Carbon\Carbon::parse($record->time_out)->format('h:i A') : '-' }}<br>
                                @if ($record)
                                    <span class="badge {{ $record->status === 'late' ? 'bg-warning' : 'bg-success' }}">{{ ucfirst($record->status) }}</span>
                                @elseif($isAbsent)
                                    <span class="badge bg-light text-dark">Absent</span>
                                @endif
                                @if ($holiday)
                                    <div class="text-primary small">{{ $holiday['localName'] }}</div>
                                @endif
                            </div>
                        </td>
                        @php $date->addDay(); @endphp
                    @endfor
                </tr>
            @endwhile
        </tbody>
    </table>
</div>
