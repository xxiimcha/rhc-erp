@extends('layouts.admin')
@section('title', 'Payroll Parameters')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="row mb-4">
            <div class="col-sm-6">
                <h4>Payroll Parameters</h4>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <ul class="nav nav-tabs" id="settingsTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="basic-tab" data-bs-toggle="tab" href="#basic" role="tab">General Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="sss-tab" data-bs-toggle="tab" href="#sss" role="tab">SSS Brackets</a>
                    </li>
                </ul>

                <div class="tab-content pt-3">
                    {{-- General Settings --}}
                    <div class="tab-pane fade show active" id="basic" role="tabpanel">
                        <form action="{{ route('admin.settings.payroll.update') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                @foreach ([
                                    ['label' => 'OT Multiplier', 'name' => 'ot_multiplier'],
                                    ['label' => 'Late Deduction per Minute', 'name' => 'late_deduction'],
                                    ['label' => 'PhilHealth %', 'name' => 'philhealth_rate'],
                                    ['label' => 'Pag-IBIG %', 'name' => 'pagibig_rate'],
                                    ['label' => 'Regular Holiday Rate', 'name' => 'regular_holiday_rate'],
                                    ['label' => 'Special Holiday Rate', 'name' => 'special_holiday_rate'],
                                    ['label' => 'Rest Day Rate', 'name' => 'rest_day_rate'],
                                    ['label' => 'Night Differential (%)', 'name' => 'night_diff_percent'],
                                    ['label' => 'Monthly Working Days', 'name' => 'monthly_working_days'],
                                    ['label' => 'Monthly Working Hours', 'name' => 'monthly_working_hours'],
                                    ['label' => '13th Month Base (Months)', 'name' => 'thirteenth_month_base']
                                ] as $field)
                                    <div class="col-md-4">
                                        <label>{{ $field['label'] }}</label>
                                        <input type="number" step="0.01" name="{{ $field['name'] }}" value="{{ old($field['name'], $settings->{$field['name']} ?? '') }}" class="form-control" readonly required>
                                    </div>
                                @endforeach

                                <div class="col-md-4">
                                    <label>13th Month Distribution</label>
                                    <select name="thirteenth_month_distribution" id="distribution" class="form-select" required>
                                        <option value="monthly" {{ old('thirteenth_month_distribution', $settings->thirteenth_month_distribution ?? '') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="semiannual" {{ old('thirteenth_month_distribution', $settings->thirteenth_month_distribution ?? '') === 'semiannual' ? 'selected' : '' }}>Semiannual</option>
                                        <option value="yearend" {{ old('thirteenth_month_distribution', $settings->thirteenth_month_distribution ?? '') === 'yearend' ? 'selected' : '' }}>Year-End Only</option>
                                    </select>
                                </div>
                            </div>

                            @php
                                $months = [
                                    '01' => 'January', '02' => 'February', '03' => 'March',
                                    '04' => 'April', '05' => 'May', '06' => 'June',
                                    '07' => 'July', '08' => 'August', '09' => 'September',
                                    '10' => 'October', '11' => 'November', '12' => 'December'
                                ];
                            @endphp

                            <div class="row mt-4" id="semiannual-months-section" style="display:none;">
                                <div class="col-12">
                                    <label><strong>13th Month Semiannual Periods</strong></label>
                                    <div class="row g-3">
                                        @foreach ([
                                            'first_half_from' => 'First Half: From',
                                            'first_half_to' => 'First Half: To',
                                            'second_half_from' => 'Second Half: From',
                                            'second_half_to' => 'Second Half: To'
                                        ] as $key => $label)
                                            <div class="col-md-3">
                                                <label>{{ $label }}</label>
                                                <select name="{{ $key }}" class="form-select">
                                                    @foreach ($months as $num => $name)
                                                        <option value="{{ $num }}" {{ old($key, $settings->{$key} ?? '') == $num ? 'selected' : '' }}>{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endforeach
                                        <div class="col-12">
                                            <input type="text" name="thirteenth_month_months" id="thirteenth_month_months" class="form-control mt-2">
                                            <small class="text-muted">Selected months will apply to all years.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="button" id="editSettingsBtn" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Update Settings
                                </button>
                                <button type="submit" id="saveSettingsBtn" class="btn btn-success d-none">
                                    <i class="fas fa-save"></i> Save
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- SSS Bracket Table --}}
                    <div class="tab-pane fade" id="sss" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>SSS Contribution Brackets</h5>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSSSModal">
                                <i class="fas fa-plus-circle"></i> Add Bracket
                            </button>
                        </div>

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Range From</th>
                                    <th>Range To</th>
                                    <th>Contribution</th>
                                    <th>Employer Share</th>
                                    <th>Employee Share</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sssBrackets as $bracket)
                                    <tr>
                                        <td>{{ number_format($bracket->range_from, 2) }}</td>
                                        <td>{{ number_format($bracket->range_to, 2) }}</td>
                                        <td>{{ number_format($bracket->total_contribution, 2) }}</td>
                                        <td>{{ number_format($bracket->employer_share, 2) }}</td>
                                        <td>{{ number_format($bracket->employee_share, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                @include('partials.modals.add_sss_bracket')
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    const distSelect = document.querySelector('select[name="thirteenth_month_distribution"]');
    const semiannualSection = document.getElementById('semiannual-months-section');
    const monthsField = document.getElementById('thirteenth_month_months');

    function updateDistributionUI() {
        const value = distSelect.value;
        semiannualSection.style.display = value === 'semiannual' ? 'block' : 'none';
        if (value === 'monthly') monthsField.value = '01,02,03,04,05,06,07,08,09,10,11,12';
        else if (value === 'yearend') monthsField.value = '12';
    }

    function calculateSemiannualMonths() {
        const fhFrom = parseInt(document.querySelector('[name="first_half_from"]').value);
        const fhTo = parseInt(document.querySelector('[name="first_half_to"]').value);
        const shFrom = parseInt(document.querySelector('[name="second_half_from"]').value);
        const shTo = parseInt(document.querySelector('[name="second_half_to"]').value);
        let months = [];
        for (let m = fhFrom; m <= fhTo; m++) months.push(m.toString().padStart(2, '0'));
        for (let m = shFrom; m <= shTo; m++) months.push(m.toString().padStart(2, '0'));
        monthsField.value = months.join(',');
    }

    distSelect.addEventListener('change', () => {
        updateDistributionUI();
        if (distSelect.value === 'semiannual') calculateSemiannualMonths();
    });

    document.querySelectorAll('[name^="first_half"], [name^="second_half"]').forEach(select => {
        select.addEventListener('change', () => {
            if (distSelect.value === 'semiannual') calculateSemiannualMonths();
        });
    });

    document.querySelector('form').addEventListener('submit', function () {
        if (distSelect.value === 'semiannual') calculateSemiannualMonths();
    });

    updateDistributionUI();
    if (distSelect.value === 'semiannual') calculateSemiannualMonths();
</script>
@endpush
@endsection
