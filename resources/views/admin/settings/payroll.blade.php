@extends('layouts.admin')
@section('title', 'Payroll Parameters')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="row mb-4">
            <div class="col-sm-6"><h4>Payroll Parameters</h4></div>
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
                        {{-- Inside <div class="tab-pane fade show active" id="basic" role="tabpanel"> --}}
                        <form action="{{ route('admin.settings.payroll.update') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label>OT Multiplier</label>
                                    <input type="number" step="0.01" name="ot_multiplier" value="{{ old('ot_multiplier', $settings->ot_multiplier ?? '') }}" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Late Deduction per Minute</label>
                                    <input type="number" step="0.01" name="late_deduction" value="{{ old('late_deduction', $settings->late_deduction ?? '') }}" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>PhilHealth %</label>
                                    <input type="number" step="0.01" name="philhealth_rate" value="{{ old('philhealth_rate', $settings->philhealth_rate ?? '') }}" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Pag-IBIG %</label>
                                    <input type="number" step="0.01" name="pagibig_rate" value="{{ old('pagibig_rate', $settings->pagibig_rate ?? '') }}" class="form-control" required>
                                </div>

                                {{-- New Fields --}}
                                <div class="col-md-4 mb-3">
                                    <label>Regular Holiday Rate</label>
                                    <input type="number" step="0.01" name="regular_holiday_rate" value="{{ old('regular_holiday_rate', $settings->regular_holiday_rate ?? '2.00') }}" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Special Holiday Rate</label>
                                    <input type="number" step="0.01" name="special_holiday_rate" value="{{ old('special_holiday_rate', $settings->special_holiday_rate ?? '1.30') }}" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Rest Day Rate</label>
                                    <input type="number" step="0.01" name="rest_day_rate" value="{{ old('rest_day_rate', $settings->rest_day_rate ?? '1.50') }}" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Night Differential (%)</label>
                                    <input type="number" step="0.01" name="night_diff_percent" value="{{ old('night_diff_percent', $settings->night_diff_percent ?? '10') }}" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Monthly Working Days</label>
                                    <input type="number" name="monthly_working_days" value="{{ old('monthly_working_days', $settings->monthly_working_days ?? '22') }}" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Monthly Working Hours</label>
                                    <input type="number" name="monthly_working_hours" value="{{ old('monthly_working_hours', $settings->monthly_working_hours ?? '176') }}" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>13th Month Base (Months)</label>
                                    <input type="number" name="thirteenth_month_base" value="{{ old('thirteenth_month_base', $settings->thirteenth_month_base ?? '12') }}" class="form-control">
                                </div>
                            </div>

                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save</button>
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

                <!-- Add SSS Bracket Modal -->
                <div class="modal fade" id="addSSSModal" tabindex="-1" aria-labelledby="addSSSModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="#" method="POST" class="modal-content">
                            @csrf
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="addSSSModalLabel">Add SSS Bracket</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label>Range From</label>
                                    <input type="number" name="range_from" class="form-control" step="0.01" required>
                                </div>
                                <div class="mb-3">
                                    <label>Range To</label>
                                    <input type="number" name="range_to" class="form-control" step="0.01" required>
                                </div>
                                <div class="mb-3">
                                    <label>Total Contribution</label>
                                    <input type="number" name="total_contribution" class="form-control" step="0.01" required>
                                </div>
                                <div class="mb-3">
                                    <label>Employer Share</label>
                                    <input type="number" name="employer_share" class="form-control" step="0.01" required>
                                </div>
                                <div class="mb-3">
                                    <label>Employee Share</label>
                                    <input type="number" name="employee_share" class="form-control" step="0.01" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save Bracket</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
