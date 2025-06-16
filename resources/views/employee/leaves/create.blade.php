@extends('layouts.admin')

@section('title', 'Leave Application Form')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <form method="POST" action="{{ route('employee.leaves.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Leave Application Form</h5>
                </div>
                <div class="card-body">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Name:</label>
                            <input type="text" class="form-control" value="{{ $employee->first_name }} {{ $employee->last_name }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label>Dept./Branch:</label>
                            <input type="text" class="form-control" value="{{ $employee->department }}" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Address:</label>
                            <input type="text" class="form-control" value="{{ $employee->address }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label>Contact #:</label>
                            <input type="text" class="form-control" value="{{ $employee->contact_number }}" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Date Filed:</label>
                            <input type="text" class="form-control" value="{{ now()->format('F d, Y') }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label>Attachment (Optional)</label>
                            <input type="file" name="attachment" class="form-control" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Type of Leave</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>No. of Days</th>
                                    <th>With Pay</th>
                                    <th>W/O Pay</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="type" id="leave-type" class="form-select" required>
                                            <option value="">-- Select Leave Type --</option>
                                            <option value="Vacation">Vacation Leave</option>
                                            <option value="Sick">Sick Leave</option>
                                            <option value="Birthday">Birthday Leave</option>
                                        </select>
                                    </td>
                                    <td><input type="date" name="start_date" class="form-control date-from" required></td>
                                    <td><input type="date" name="end_date" class="form-control date-to" required></td>
                                    <td><input type="number" name="days" class="form-control" id="days-field" readonly></td>
                                    <td><input type="checkbox" name="with_pay" value="1"></td>
                                    <td><input type="checkbox" name="without_pay" value="1"></td>
                                    <td><input type="text" name="reason" class="form-control" required></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i> Submit Leave Application
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const typeSelect = document.getElementById('leave-type');
    const withPay = document.querySelector('input[name="with_pay"]');
    const withoutPay = document.querySelector('input[name="without_pay"]');
    const fromInput = document.querySelector('.date-from');
    const toInput = document.querySelector('.date-to');
    const daysField = document.getElementById('days-field');

    let availableCredits = 0;

    const warning = document.createElement('div');
    warning.classList.add('mt-2', 'text-danger', 'small');
    daysField.parentNode.appendChild(warning);

    function countWeekdays(start, end) {
        let count = 0;
        const current = new Date(start);
        while (current <= end) {
            const day = current.getDay();
            if (day !== 0 && day !== 6) count++;
            current.setDate(current.getDate() + 1);
        }
        return count;
    }

    function calculateDays() {
        const fromDate = new Date(fromInput.value);
        const toDate = new Date(toInput.value);

        if (!isNaN(fromDate) && !isNaN(toDate) && fromDate <= toDate) {
            const totalDays = countWeekdays(fromDate, toDate);
            daysField.value = totalDays;

            if (withPay.checked && totalDays > availableCredits) {
                const excess = totalDays - availableCredits;
                warning.innerText = `Note: You have ${availableCredits} paid day(s). ${excess} day(s) will be without pay.`;
                withoutPay.checked = true;
            } else {
                warning.innerText = '';
                if (!withPay.checked) {
                    withoutPay.checked = false;
                }
            }
        } else {
            daysField.value = '';
            warning.innerText = '';
            withoutPay.checked = false;
        }
    }

    typeSelect.addEventListener('change', function () {
        const type = this.value;
        withPay.checked = false;
        withoutPay.checked = false;
        withPay.disabled = true;
        withoutPay.disabled = true;
        warning.innerText = '';
        availableCredits = 0;

        if (!type) return;

        fetch(`/employee/check-balance?type=${type}`)
            .then(res => res.json())
            .then(data => {
                availableCredits = parseInt(data.remaining) || 0;

                if (availableCredits > 0) {
                    withPay.disabled = false;
                    withoutPay.disabled = false;
                } else {
                    withPay.disabled = true;
                    withoutPay.disabled = false;
                    withoutPay.checked = true;
                }

                calculateDays();
            })
            .catch(err => {
                console.error('Error fetching balance:', err);
                withPay.disabled = true;
                withoutPay.disabled = true;
                warning.innerText = 'Unable to fetch leave balance.';
            });
    });

    fromInput.addEventListener('change', calculateDays);
    toInput.addEventListener('change', calculateDays);
    withPay.addEventListener('change', calculateDays);
});
</script>
@endsection
