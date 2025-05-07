<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Clocking System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet">
    <style>
        body {
            background-color: #e9ecef;
            font-family: 'Segoe UI', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .clocking-container {
            background: #fff;
            border-radius: 16px;
            padding: 40px 35px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }

        .clocking-header {
            margin-bottom: 30px;
            text-align: center;
        }

        .clocking-header .date {
            font-size: 1rem;
            color: #6c757d;
        }

        .clocking-header .clock {
            font-size: 3rem;
            font-weight: bold;
            color: #0d6efd;
        }

        #rfidInput {
            font-size: 1.2rem;
        }

        .btn-scan {
            font-size: 1.1rem;
            padding: 12px;
        }

        #employeeInfo {
            margin-top: 35px;
            display: none;
            background-color: #f8f9fa;
            border-left: 5px solid #0d6efd;
            padding: 20px;
            border-radius: 10px;
        }

        .employee-info h5 {
            font-weight: 600;
        }

        .status-label {
            font-weight: bold;
        }

        .status-late {
            color: #dc3545;
        }

        .status-ontime {
            color: #198754;
        }
    </style>
</head>
<body>
    <div class="clocking-container">
        <div class="clocking-header">
            <div class="date" id="dateDisplay">-- -- ----</div>
            <div class="clock" id="clockDisplay">--:--:--</div>
        </div>

        <form id="clockingForm" method="POST" action="{{ route('clocking.submit') }}">
            @csrf
            <input type="text" name="rfid" id="rfidInput" class="form-control form-control-lg text-center mb-3" placeholder="Tap your RFID card..." autofocus required>
            <button type="submit" class="btn btn-success btn-scan w-100">
                <i class="fas fa-door-open"></i> Clock In / Out
            </button>
        </form>

        <div id="employeeInfo" class="employee-info">
            <h5 id="empName">Full Name: -</h5>
            <p id="empPosition">Position: -</p>
            <p><strong>Last Time In:</strong> <span id="lastIn">-</span></p>
            <p><strong>Last Time Out:</strong> <span id="lastOut">-</span></p>
            <p id="lateDisplay" class="status-late"></p>
            <p id="overtimeDisplay" class="status-ontime"></p>
            <p class="status-label" id="statusLabel"></p>
        </div>
    </div>

    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script>
        function updateClock() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('dateDisplay').textContent = now.toLocaleDateString('en-US', options);
            document.getElementById('clockDisplay').textContent = now.toLocaleTimeString('en-US', { timeZone: 'Asia/Manila' });
        }
        setInterval(updateClock, 1000);
        updateClock();

        setInterval(() => {
            document.getElementById('rfidInput').focus();
        }, 1000);

        $('#clockingForm').on('submit', function (e) {
            e.preventDefault();
            let rfid = $('#rfidInput').val();
            if (!rfid) return;

            $.ajax({
                url: "{{ route('clocking.submit') }}",
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    rfid: rfid
                },
                success: function (res) {
                    if (res.success) {
                        $('#empName').text('Full Name: ' + res.employee.name);
                        $('#empPosition').text('Position: ' + res.employee.position);
                        $('#lastIn').text(res.last_in ?? '-');
                        $('#lastOut').text(res.last_out ?? '-');

                        const statusEl = $('#statusLabel');
                        if (res.status.toLowerCase() === 'late') {
                            statusEl.text('Status: You are late today').removeClass('status-ontime').addClass('status-late');
                            $('#lateDisplay').text('Late by: ' + res.late_minutes + ' minute(s)');
                        } else {
                            statusEl.text('Status: On Time').removeClass('status-late').addClass('status-ontime');
                            $('#lateDisplay').text('');
                        }

                        if (res.overtime_minutes > 0) {
                            $('#overtimeDisplay').text('Overtime: ' + res.overtime_minutes + ' minute(s)');
                        } else {
                            $('#overtimeDisplay').text('');
                        }

                        $('#employeeInfo').fadeIn();
                    } else {
                        alert(res.message || 'Employee not found');
                    }

                    $('#rfidInput').val('');
                },
                error: function () {
                    alert('Error processing clock-in.');
                    $('#rfidInput').val('');
                }
            });
        });
    </script>
</body>
</html>
