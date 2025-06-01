<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar>
        <div id="sidebar-menu">

            <div class="logo-box">
                <a href="index.html" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-sm.png')}}" alt="" height="60">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-light.png')}}" alt="" height="24">
                    </span>
                </a>
                <a href="index.html" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-sm.png')}}" alt="" height="60">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-dark.png')}}" alt="" height="60">
                    </span>
                </a>
            </div>

            <ul id="side-menu">

                <li class="menu-title">Menu</li>

                <li>
                    <a class="tp-link" href="{{ route('manager.dashboard') }}">
                        <i data-feather="home" class="align-middle me-2 fs-18"></i>
                        <span> Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="#sidebarrep" data-bs-toggle="collapse">
                        <i class="mdi mdi-file-clock-outline align-middle me-2 fs-18"></i>
                        <span>Pending Reports </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarrep">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('pendingReports') }}" class="tp-link">View Reports</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#sidebarrepaccept" data-bs-toggle="collapse">
                        <i class="mdi mdi-file-check-outline align-middle me-2 fs-18"></i>
                        <span>Accepted Reports </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarrepaccept">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('manager.accepted-reports') }}" class="tp-link">View Reports</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#sidebarreprevoke" data-bs-toggle="collapse">
                        <i class="mdi mdi-file-cancel-outline align-middle me-2 fs-18"></i>
                        <span>Revoke Reports </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarreprevoke">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('manager.revoked-reports') }}" class="tp-link">View Reports</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="menu-title mt-2">Auth</li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <li onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <a class="tp-link" href="{{ route('logout') }}">
                        <i class="mdi mdi-logout align-middle me-2 fs-18"></i>
                        <span> Logout </span>
                    </a>
                </li>

            </ul>

        </div>

        <div class="clearfix"></div>

    </div>
</div>
