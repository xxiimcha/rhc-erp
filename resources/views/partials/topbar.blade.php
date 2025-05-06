<header id="page-topbar">
    <div class="navbar-header">
        <div class="navbar-logo-box">
            <a href="{{ url('dashboard') }}" class="logo logo-dark">
                <span class="logo-sm">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="logo-sm-dark" height="50">
                </span>
                <span class="logo-lg">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="logo-dark" height="50">
                </span>
            </a>
            <a href="{{ url('dashboard') }}" class="logo logo-light">
                <span class="logo-sm">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="logo-sm-light" height="50">
                </span>
                <span class="logo-lg">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="logo-light" height="50">
                </span>
            </a>
            <button type="button" class="btn btn-sm top-icon sidebar-btn" id="sidebar-btn">
                <i class="mdi mdi-menu-open align-middle fs-19"></i>
            </button>
        </div>

        <div class="d-flex justify-content-between menu-sm px-3 ms-auto">
            <div class="d-flex align-items-center gap-2">
                <!-- Dropdowns omitted for brevity (Apps and Features) -->
            </div>

            <div class="d-flex align-items-center gap-2">
                <form class="app-search d-none d-lg-block">
                    <div class="position-relative">
                        <input type="text" class="form-control" placeholder="Search...">
                        <span class="fab fa-sistrix fs-17 align-middle"></span>
                    </div>
                </form>

                <!-- Notifications -->
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn btn-sm top-icon" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell align-middle"></i>
                        <span class="btn-marker"><i class="marker marker-dot text-danger"></i></span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0">
                        <div class="p-3 bg-info">
                            <h6 class="text-white m-0">Notifications</h6>
                        </div>
                        <div data-simplebar style="max-height: 230px;">
                            <a href="#" class="text-reset notification-item">
                                <div class="d-flex">
                                    <div class="avatar avatar-xs me-3">
                                        <span class="rounded fs-16"><i class="mdi mdi-file-document-outline"></i></span>
                                    </div>
                                    <div class="flex-1">
                                        <h6 class="mb-1">New report received</h6>
                                        <p class="mb-0 text-muted fs-12"><i class="mdi mdi-clock-outline"></i> 3 min ago</p>
                                    </div>
                                </div>
                            </a>
                            <!-- More notification items as needed -->
                        </div>
                        <div class="p-2 border-top text-center">
                            <a class="btn btn-sm btn-link text-muted" href="#"><i class="mdi mdi-arrow-right-circle me-1"></i> View More..</a>
                        </div>
                    </div>
                </div>

                <!-- Profile -->
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn btn-sm top-icon p-0" data-bs-toggle="dropdown">
                        <img class="rounded avatar-2xs p-0" src="{{ asset('assets/images/users/avatar-6.png') }}" alt="Header Avatar">
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="#"><i class="far fa-address-card me-2"></i> Profile</a>
                        <a class="dropdown-item" href="#"><i class="far fa-comments me-2"></i> Messages</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="{{ url('auth-login') }}"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
