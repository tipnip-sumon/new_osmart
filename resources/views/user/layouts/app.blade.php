<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'User Dashboard') - {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/img/icons/favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('assets/img/icons/favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/icons/icon.png') }}">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lineicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.css') }}">
    
    <!-- Style CSS -->
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
    
    <!-- Additional styles -->
    @stack('styles')
    
    <style>
        .user-dashboard {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        
        .dashboard-sidebar {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
        
        .dashboard-content {
            margin-top: 20px;
        }
        
        .dashboard-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            border: none;
        }
        
        .stat-card {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .stat-card h3 {
            margin: 10px 0 5px 0;
            font-weight: bold;
        }
        
        .stat-card p {
            margin: 0;
            color: #6c757d;
        }
        
        .nav-pills .nav-link {
            border-radius: 0;
            border: none;
            padding: 12px 20px;
            margin: 2px 0;
            color: #495057;
        }
        
        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .progress-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            font-weight: bold;
            color: white;
        }
        
        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .commission-item {
            border-left: 4px solid;
            padding-left: 15px;
            margin-bottom: 15px;
        }
        
        .commission-item.direct { border-color: #28a745; }
        .commission-item.team { border-color: #007bff; }
        .commission-item.leadership { border-color: #ffc107; }
        
        .rank-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        
        .customer-badge {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        
        .nav-divider {
            border-top: 1px solid #e9ecef;
            margin: 10px 0;
            height: 1px;
        }
        
        .sidenav-nav li a.text-warning {
            color: #ffc107 !important;
            font-weight: 600;
        }
        
        .sidenav-nav li a.text-warning:hover {
            background: rgba(255, 193, 7, 0.1);
        }
    </style>
</head>

<body class="user-dashboard">
    <!-- Header -->
    <div class="header-area" id="headerArea">
        <div class="container h-100 d-flex align-items-center justify-content-between">
            <!-- Logo Wrapper -->
            <div class="logo-wrapper">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('assets/img/core-img/logo-small.png') }}" alt="">
                </a>
            </div>

            <!-- Search Form -->
            <div class="top-search-form">
                <form action="{{ route('search') }}" method="GET">
                    <input class="form-control" type="search" name="q" placeholder="Enter your keyword">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
            </div>

            <!-- Navbar Toggler -->
            <div class="suha-navbar-toggler d-flex flex-wrap" id="suhaNavbarToggler">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>

    <!-- Sidenav Black Overlay -->
    <div class="sidenav-black-overlay"></div>

    <!-- Side Nav -->
    <div class="suha-sidenav-wrapper" id="sidenavWrapper">
        <!-- Sidenav Profile -->
        <div class="sidenav-profile">
            <div class="user-profile">
                <img src="{{ asset('assets/img/default-avatar.svg') }}" alt="User" class="user-avatar">
            </div>
            <div class="user-info">
                <h6 class="user-name mb-0">{{ Auth::user()->name ?? 'User' }}</h6>
                @if(Auth::user() && Auth::user()->role === 'affiliate')
                    <span class="rank-badge">{{ Auth::user()->rank ?? 'Affiliate' }}</span>
                @else
                    <span class="customer-badge">Customer</span>
                @endif
            </div>
        </div>

        <!-- Sidenav Nav -->
        <ul class="sidenav-nav ps-0">
            <li><a href="{{ route('user.dashboard') }}"><i class="lni lni-dashboard"></i>Dashboard</a></li>
            
            @if(Auth::user() && Auth::user()->role === 'affiliate')
                <!-- Affiliate Menu Items -->
                <li><a href="{{ route('user.genealogy') }}"><i class="lni lni-network"></i>My Network</a></li>
                <li><a href="{{ route('user.commissions') }}"><i class="lni lni-money-protection"></i>Commissions</a></li>
                <li><a href="{{ route('user.training') }}"><i class="lni lni-graduation"></i>Training</a></li>
                <li class="nav-divider"></li>
            @endif
            
            <!-- Common Menu Items -->
            <li><a href="{{ route('user.profile') }}"><i class="lni lni-user"></i>Profile</a></li>
            <li><a href="{{ route('orders.index') }}"><i class="lni lni-shopping-basket"></i>My Orders</a></li>
            <li><a href="{{ route('wishlist.index') }}"><i class="lni lni-heart"></i>My Wishlist</a></li>
            <li><a href="{{ route('shop.grid') }}"><i class="lni lni-store"></i>Shop Products</a></li>
            
            @if(Auth::user() && Auth::user()->role !== 'affiliate')
                <!-- Customer Menu Items -->
                <li class="nav-divider"></li>
                <li><a href="{{ route('affiliate.register') }}" class="text-warning"><i class="lni lni-crown"></i>Join as Affiliate</a></li>
            @endif
            
            <li class="nav-divider"></li>
            <li><a href="{{ route('home') }}"><i class="lni lni-home"></i>Back to Home</a></li>
            <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="lni lni-power-switch"></i>Logout</a></li>
        </ul>
        
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>

    <!-- Main Content -->
    <div class="page-content-wrapper">
        <div class="container">
            @yield('content')
        </div>
    </div>

    <!-- Footer Nav -->
    <div class="footer-nav-area" id="footerNav">
        <div class="container h-100 px-0">
            <div class="suha-footer-nav h-100">
                <ul class="h-100 d-flex align-items-center justify-content-between ps-0">
                    <li class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('user.dashboard') }}">
                            <i class="lni lni-dashboard"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('user.genealogy') ? 'active' : '' }}">
                        <a href="{{ route('user.genealogy') }}">
                            <i class="lni lni-network"></i>
                            Network
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('user.commissions') ? 'active' : '' }}">
                        <a href="{{ route('user.commissions') }}">
                            <i class="lni lni-money-protection"></i>
                            Earnings
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('user.profile') ? 'active' : '' }}">
                        <a href="{{ route('user.profile') }}">
                            <i class="lni lni-user"></i>
                            Profile
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('shop.*') ? 'active' : '' }}">
                        <a href="{{ route('shop.grid') }}">
                            <i class="lni lni-store"></i>
                            Shop
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- All JavaScript Files -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('assets/js/active.js') }}"></script>
    
    <!-- Additional scripts -->
    @stack('scripts')
</body>
</html>
