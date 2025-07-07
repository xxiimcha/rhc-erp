<!-- resources/views/layouts/admin.blade.php -->
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Dashboard') | RHC Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="RHC ERP" name="description" />
    <meta content="Codebucks" name="author" />
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Core CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/libs/simplebar/simplebar.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" />

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- DataTables -->
    <link href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{ asset('assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="{{ asset('assets/js/pages/layout.js') }}"></script>
</head>
<body>
<div id="layout-wrapper">
    @include('partials.topbar')
    @include('partials.sidebar')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
    </div>
</div>

@include('partials.right-sidebar')

<!-- Core JS -->
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>

<!-- Required datatable js -->
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>

<!-- Responsive examples -->
<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>


<!-- ApexCharts -->
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/dashboard.init.js') }}"></script>

<script src="{{ asset('assets/js/pages/datatables-advanced.init.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>
@stack('scripts')
<script>
    let inactivityTimer;

    const timeoutDuration = 6 * 60 * 60 * 1000; // 6 hours
    const logoutUrl = '{{ route("logout") }}';
    const pingUrl = '{{ route("session.ping") }}';

    const resetInactivityTimer = () => {
        clearTimeout(inactivityTimer);
        inactivityTimer = setTimeout(showInactivityAlert, timeoutDuration);
    };

    const showInactivityAlert = () => {
        Swal.fire({
            title: "You've been inactive",
            text: "Do you want to stay signed in?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, keep me signed in",
            cancelButtonText: "No, log me out",
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(pingUrl); // Keep session alive
                resetInactivityTimer(); // Reset timer
            } else {
                window.location.href = logoutUrl; // End session
            }
        });
    };

    // Track user activity
    window.onload = resetInactivityTimer;
    document.onmousemove = resetInactivityTimer;
    document.onkeydown = resetInactivityTimer;
    document.onclick = resetInactivityTimer;
    document.onscroll = resetInactivityTimer;
</script>

</body>
</html>
