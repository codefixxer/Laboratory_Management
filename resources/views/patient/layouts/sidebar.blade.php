 <div class="app-sidebar-menu">
                <div class="h-100" data-simplebar>
                    <div id="sidebar-menu">

                        <div class="logo-box">
                            <a href="index.html" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="{{ asset('assets/images/logo-sm.png')}}" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('assets/images/logo-light.png')}}" alt="" height="24">
                                </span>
                            </a>
                            <a href="index.html" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{ asset('assets/images/logo-sm.png')}}" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('assets/images/logo-dark.png')}}" alt="" height="24">
                                </span>
                            </a>
                        </div>

                        <ul id="side-menu">

                            <li class="menu-title">Menu</li>

                            <li>
                                <a href="#sidebarDashboards" data-bs-toggle="collapse">
                                    <i data-feather="home"></i>
                                    <span> Dashboard </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="sidebarDashboards">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a href="index.html" class="tp-link">CRM</a>
                                        </li>
                                        <li>
                                            <a href="analytics.html" class="tp-link">Analytics</a>
                                        </li>
                                        <li>
                                            <a href="ecommerce.html" class="tp-link">eCommerce</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="#pending" data-bs-toggle="collapse">
                                    <i data-feather="home"></i>
                                    <span> Reports </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="pending">
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
                                <a href="apps-todolist.html" class="tp-link" href="{{ route('logout') }}>
    <i class="mdi mdi-location-exit fs-16 align-middle"></i>
                                    <span> Logout </span>
                                </a>
                            </li>

                        </ul>
            
                    </div>

                    <div class="clearfix"></div>

                </div>
            </div>
