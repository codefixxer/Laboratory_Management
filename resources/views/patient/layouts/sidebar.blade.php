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
                    <a class="tp-link" href="{{ route('patient.dashboard') }}">
                        <i data-feather="home" class="align-middle me-2 fs-18"></i>
                        <span> Dashboard</span>
                    </a>
                </li>
<li>
    <a href="#pending" data-bs-toggle="collapse" aria-expanded="true" class="active">
        <i class="mdi mdi-file-document-multiple-outline align-middle me-2 fs-18"></i>
        <span> Reports </span>
        <span class="menu-arrow"></span>
    </a>
    <div class="collapse show" id="pending">
        <ul class="nav-second-level">
            <li>
                <a href="{{ route('patient.pendingReports') }}" class="tp-link">Pending Reports</a>
            </li>
            <li>
                <a href="{{ route('patient.reports.completed') }}" class="tp-link">Completed Reports</a>
            </li>
        </ul>
    </div>
</li>


                        

                            

                                           <li class="menu-title mt-2">Auth</li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
                            <li    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <a href="apps-todolist.html" class="tp-link" href="{{ route('logout') }}">
    <i class="mdi mdi-location-exit fs-16 align-middle"></i>
                                    <span> Logout </span>
                                </a>
                            </li>

                        </ul>
            
                    </div>

                    <div class="clearfix"></div>

                </div>
            </div>
