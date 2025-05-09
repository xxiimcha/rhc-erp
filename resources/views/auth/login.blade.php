<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>RHC Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Codebucks" name="author" />
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}">

    <!-- CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        body {
            background-color: #f8f9fa; /* Light gray background */
        }

        .authentication-bg {
            background: none !important;
        }

        .bg-overlay {
            display: none !important;
        }
    </style>
</head>

<body>
    <div class="container-fluid authentication-bg overflow-hidden">
        <div class="row align-items-center justify-content-center min-vh-100">
            <div class="col-10 col-md-6 col-lg-4 col-xxl-3">
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="text-center">
                            <a href="{{ url('/') }}">
                                <img src="{{ asset('assets/images/logo.png') }}" alt="logo" height="100" class="auth-logo mx-auto d-block">
                            </a>
                            <p class="text-muted">Sign in to continue to ERP System.</p>
                        </div>

                        <div class="p-2 mt-4">
                            @if(session('status'))
                                <div class="alert alert-success">{{ session('status') }}</div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="">
                                @csrf

                                <div class="input-group auth-form-group-custom mb-3">
                                    <span class="input-group-text bg-primary bg-opacity-10 fs-16">
                                        <i class="mdi mdi-account-outline"></i>
                                    </span>
                                    <input type="email" class="form-control" name="email" placeholder="Enter email" value="{{ old('email') }}" required autofocus>
                                </div>

                                <div class="input-group auth-form-group-custom mb-3">
                                    <span class="input-group-text bg-primary bg-opacity-10 fs-16">
                                        <i class="mdi mdi-lock-outline"></i>
                                    </span>
                                    <input type="password" class="form-control" name="password" placeholder="Enter password" required>
                                </div>

                                <div class="mb-sm-4 d-flex justify-content-between">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">Remember me</label>
                                    </div>
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="text-muted"><i class="mdi mdi-lock me-1"></i> Forgot your password?</a>
                                    @endif
                                </div>

                                <div class="pt-2 text-center">
                                    <button class="btn btn-primary w-100" type="submit">Log In</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="{{ asset('assets/js/layout.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
</body>
</html>
