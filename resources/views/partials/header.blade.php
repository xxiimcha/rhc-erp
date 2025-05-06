<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4">
    <a class="navbar-brand" href="#">
        <img src="{{ asset('assets/images/rhc-logo.png') }}" alt="RHC" height="30">
        <span class="ms-2 fw-bold">RHC ERP</span>
    </a>
    <ul class="navbar-nav ms-auto">
        <li class="nav-item">
            <a href="#" class="nav-link">Welcome, Admin</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('logout') }}" class="nav-link">Logout</a>
        </li>
    </ul>
</nav>
