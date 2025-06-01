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
                    <a class="tp-link" href="{{ route('admin.dashboard') }}">
                        <i data-feather="home" class="align-middle me-2 fs-18"></i>
                        <span> Dashboard</span>
                    </a>
                </li>

                <li class="menu-title">Pages</li>

                <li>
                    <a href="#sidebarAuth" data-bs-toggle="collapse">
                        <i data-feather="users" class="align-middle me-2 fs-18"></i>
                        <span> User Management </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarAuth">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{route('admin.users.create')}}" class="tp-link">
                                    Add Users
                                </a>
                            </li>
                            <li>
                                <a href="{{route('admin.users.index')}}" class="tp-link">
                                    Users List
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="#sidebarLc" data-bs-toggle="collapse">
                        <i class="mdi mdi-card-account-details-outline align-middle me-2 fs-18"></i>
                        <span> Loyalty Card </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarLc">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{route('admin.lc.pending')}}" class="tp-link">
                                    Pending LC Users
                                </a>
                            </li>
                            <li>
                                <a href="{{route('admin.lc.aloted')}}" class="tp-link">
                                    Aloted LC users
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="#sidebarpanal" data-bs-toggle="collapse">
                        <i class="mdi mdi-account-group-outline align-middle me-2 fs-18"></i>
                        <span>External Panel</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarpanal">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('admin.external.add') }}" class="tp-link">
                                    Add Panel
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.external.view') }}" class="tp-link">
                                    View Panel
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="#sidestafpanal" data-bs-toggle="collapse">
                        <i class="mdi mdi-account-tie align-middle me-2 fs-18"></i>
                        <span>Staff Panel</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidestafpanal">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('admin.staff.add') }}" class="tp-link">
                                    Add Panel
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.staff.view') }}" class="tp-link">
                                    View Panel
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="#sidetestpanal" data-bs-toggle="collapse">
                        <i class="mdi mdi-flask-outline align-middle me-2 fs-18"></i>
                        <span>Test</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidetestpanal">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('admin.test.create') }}" class="tp-link">
                                    Add Test
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.test.index') }}" class="tp-link">
                                    View Test
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="#sidetestcatpanal" data-bs-toggle="collapse">
                        <i class="mdi mdi-format-list-bulleted-type align-middle me-2 fs-18"></i>
                        <span>Test Category</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidetestcatpanal">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('admin.testcategory.create') }}" class="tp-link">
                                    Add Test Category
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.testcategory.index') }}" class="tp-link">
                                    View Test Category
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="#sidetestranpanal" data-bs-toggle="collapse">
                        <i class="mdi mdi-chart-bar align-middle me-2 fs-18"></i>
                        <span>Test Ranges</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidetestranpanal">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('admin.testrange.create') }}" class="tp-link">
                                    Add Test Ranges
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.testrange.index') }}" class="tp-link">
                                    View Test Ranges
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="#sidetestrefpanal" data-bs-toggle="collapse">
                        <i class="mdi mdi-account-arrow-right-outline align-middle me-2 fs-18"></i>
                        <span>Referral</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidetestrefpanal">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('admin.referral.create') }}" class="tp-link">
                                    Add Referral
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.referral.index') }}" class="tp-link">
                                    View Referral
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="#sidereport" data-bs-toggle="collapse">
                        <i class="mdi mdi-file-document-outline align-middle me-2 fs-18"></i>
                        <span>Report</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidereport">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('admin.revoked') }}" class="tp-link">
                                    View Revoke
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.pending') }}" class="tp-link">
                                    View Pending
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.accepted') }}" class="tp-link">
                                    View Accepted
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="menu-title mt-2">Finentials</li>

                <li>
                    <a class="tp-link" href="{{ route('admin.sale_report') }}">
                        <i class="mdi mdi-cash-multiple align-middle me-2 fs-18"></i>
                        <span> Sale Report </span>
                    </a>
                </li>
                <li>
                    <a class="tp-link" href="{{ route('admin.expense_report') }}">
                        <i class="mdi mdi-currency-usd align-middle me-2 fs-18"></i>
                        <span> Expense Report </span>
                    </a>
                </li>
                <li>
                    <a class="tp-link" href="{{ route('admin.comparison_report') }}">
                        <i class="mdi mdi-chart-line align-middle me-2 fs-18"></i>
                        <span> Sales Comparision </span>
                    </a>
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
