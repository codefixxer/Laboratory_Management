<div class="topbar-custom">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between">
                        <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">
                            <li>
                                <button class="button-toggle-menu nav-link">
                                    <i data-feather="menu" class="noti-icon"></i>
                                </button>
                            </li>
                            <li class="d-none d-lg-block">
                                @php
                                $hour = now()->format('H');
                                if ($hour < 12) {
                                    $greeting = "Good Morning";
                                } elseif ($hour < 17) {
                                    $greeting = "Good Afternoon";
                                } elseif ($hour < 20) {
                                    $greeting = "Good Evening";
                                } else {
                                    $greeting = "Good Night";
                                }
                            @endphp
                            
                            <h5 class="mb-0">{{ $greeting }}, {{ Auth::user()->name }}</h5>
                                                        </li>
                        </ul>

                        <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">

                       

                            <li class="d-none d-sm-flex">
                                <button type="button" class="btn nav-link" data-toggle="fullscreen">
                                    <i data-feather="maximize" class="align-middle fullscreen noti-icon"></i>
                                </button>
                            </li>

                            
        
                            <li class="dropdown notification-list topbar-dropdown">
                                <a class="nav-link dropdown-toggle nav-user me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
<!-- Example Blade code -->
<img
    src="{{ Auth::user()->profile_picture 
            ? asset('storage/' . Auth::user()->profile_picture) 
            : asset('assets/images/users/dp.jpg') }}"
    alt="User Image"
    width="50"
    height="50"
    class="rounded-circle"
    style="
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #fff;
        border-top: 5px solid #287f71;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    "
>
                                    <span class="pro-user-name ms-1">
                                        {{ ucfirst(Auth::user()->role) }}
                                        <i class="mdi mdi-chevron-down"></i> 
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                             
                               
        
        
        
                                    <!-- item-->
                                    <!-- Hidden Logout Form -->
<!-- Hidden Logout Form -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<!-- Logout Link -->
<a class="dropdown-item notify-item" href="{{ route('logout') }}"
   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
    <i class="mdi mdi-location-exit fs-16 align-middle"></i>
    <span>Logout</span>
</a>



        
                                </div>
                            </li>
        
                        </ul>
                    </div>

                </div>
               
            </div>