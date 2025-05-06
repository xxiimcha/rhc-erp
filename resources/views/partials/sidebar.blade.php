<div class="sidebar-left">
    <div data-simplebar class="h-100">
        <div id="sidebar-menu">
            <ul class="left-menu list-unstyled" id="side-menu">
                <li>
                    <a href="{{ url('dashboard') }}">
                        <i class="fas fa-desktop"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="menu-title">Pages</li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="fa fa-unlock-alt"></i>
                        <span>Authentication</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="{{ url('auth-login') }}">Login</a></li>
                        <li><a href="{{ url('auth-register') }}">Register</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="fa fa-unlink"></i>
                        <span>Error</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="{{ url('pages-404') }}">Error 404</a></li>
                        <li><a href="{{ url('pages-500') }}">Error 500</a></li>
                    </ul>
                </li>
                <li><a href="{{ url('pages-starter') }}"><i class="fas fa-pager"></i> Starter Page</a></li>
                <li><a href="{{ url('pages-comingsoon') }}"><i class="fas fa-tape"></i> Coming Soon</a></li>
            </ul>
        </div>
    </div>
</div>
