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
                    <a class="tp-link" href="{{ route('receptionist.dashboard') }}">
                        <i data-feather="home" class="align-middle me-2 fs-18"></i>
                        <span> Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="#sidetestrefpanal" data-bs-toggle="collapse">
                        <i class="mdi mdi-flask-outline align-middle me-2 fs-18"></i>
                        <span>Test</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidetestrefpanal">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('testsave.showForm') }}" class="tp-link">Add Test</a>
                            </li>
                            <li>
                                <a href="{{ route('receptionist.customers') }}" class="tp-link">Accepted Reports</a>
                            </li>
                            {{-- <li>    
                                <a href="{{ route('receptionist.customers.revoked') }}" class="tp-link">Revoked Reports</a>
                            </li> --}}
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#sidestockrefpanal" data-bs-toggle="collapse">
                        <i class="mdi mdi-cube-outline align-middle me-2 fs-18"></i>
                        <span>Stock</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidestockrefpanal">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('stock.create') }}" class="tp-link">Add Stock</a>
                            </li>
                            <li>
                                {{-- <a href="{{ route('admin.referral.index') }}" class="tp-link">View Test Ranges</a> --}}
                            </li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#sidedebit" data-bs-toggle="collapse">
                        <i class="mdi mdi-arrow-down-bold-box-outline align-middle me-2 fs-18"></i>
                        <span>Debit</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidedebit">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('debit.create') }}" class="tp-link">Add Stock</a>
                            </li>
                            <li>
                                <a href="{{ route('debit.index') }}" class="tp-link">View Stock</a>
                            </li>
                            <li>
                                {{-- <a href="{{ route('admin.referral.index') }}" class="tp-link">View Test Ranges</a> --}}
                            </li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#sidecredit" data-bs-toggle="collapse">
                        <i class="mdi mdi-arrow-up-bold-box-outline align-middle me-2 fs-18"></i>
                        <span>Credit</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidecredit">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('credit.create') }}" class="tp-link">Add credit</a>
                            </li>
                            <li>
                                <a href="{{ route('credit.index') }}" class="tp-link">View credit</a>
                            </li>
                            <li>
                                {{-- <a href="{{ route('admin.referral.index') }}" class="tp-link">View Test Ranges</a> --}}
                            </li>
                        </ul>
                    </div>
                </li>
                
                <li class="menu-title mt-2">Finentials</li>
                <li>
                    <a class="tp-link" href="{{ route('receptionist.sale_report') }}">
                        <i class="mdi mdi-cash-multiple align-middle me-2 fs-18"></i>
                        <span> Sale Report </span>
                    </a>
                </li>
                <li>
                    <a class="tp-link" href="{{ route('receptionist.expenses') }}">
                        <i class="mdi mdi-currency-usd align-middle me-2 fs-18"></i>
                        <span> Expense Report </span>
                    </a>
                </li>
                <li>
                    <a class="tp-link" href="{{ route('receptionist.comparison_report') }}">
                        <i class="mdi mdi-chart-line align-middle me-2 fs-18"></i>
                        <span> Sale Comparision </span>
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
