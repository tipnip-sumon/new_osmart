<!-- Start::app-sidebar -->
<aside class="app-sidebar sticky" id="sidebar">
    <!-- Start::main-sidebar-header -->
    <div class="main-sidebar-header">
        <a href="{{ route('member.dashboard') }}" class="header-logo">
            <img src="{{ asset('admin-assets/images/brand-logos/desktop-logo.png') }}" alt="logo" class="desktop-logo">
            <img src="{{ asset('admin-assets/images/brand-logos/toggle-logo.png') }}" alt="logo" class="toggle-logo">
            <img src="{{ asset('admin-assets/images/brand-logos/desktop-dark.png') }}" alt="logo" class="desktop-dark">
            <img src="{{ asset('admin-assets/images/brand-logos/toggle-dark.png') }}" alt="logo" class="toggle-dark">
        </a>
    </div>
    <!-- End::main-sidebar-header -->

    <!-- Start::main-sidebar -->
    <div class="main-sidebar" id="sidebar-scroll">
        <!-- Start::nav -->
        <nav class="main-menu-container nav nav-pills flex-column sub-open">
            <div class="slide-left" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> 
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path> 
                </svg>
            </div>
            
            <ul class="main-menu">
                <!-- Member Dashboard -->
                <li class="slide__category"><span class="category-name">Member Area</span></li>
                <li class="slide">
                    <a href="{{ route('member.dashboard') }}" class="side-menu__item {{ request()->routeIs('member.dashboard') ? 'active' : '' }}">
                        <i class="bx bx-home side-menu__icon"></i>
                        <span class="side-menu__label">Dashboard</span>
                    </a>
                </li>

                <!-- Genealogy/Binary Tree -->
                <li class="slide">
                    <a href="{{ route('member.genealogy') }}" class="side-menu__item {{ request()->routeIs('member.genealogy') ? 'active' : '' }}">
                        <i class="bx bx-network-chart side-menu__icon"></i>
                        <span class="side-menu__label">Genealogy</span>
                    </a>
                </li>

                <!-- Binary Tree -->
                <li class="slide">
                    <a href="{{ route('member.binary') }}" class="side-menu__item {{ request()->routeIs('member.binary') ? 'active' : '' }}">
                        <i class="bx bx-git-branch side-menu__icon"></i>
                        <span class="side-menu__label">Binary Tree</span>
                    </a>
                </li>

                <!-- Sponsor Management -->
                <li class="slide">
                    <a href="{{ route('member.sponsor') }}" class="side-menu__item {{ request()->routeIs('member.sponsor') ? 'active' : '' }}">
                        <i class="bx bx-user-plus side-menu__icon"></i>
                        <span class="side-menu__label">Sponsor</span>
                    </a>
                </li>

                <!-- Rank & Achievements -->
                <li class="slide">
                    <a href="{{ route('member.rank') }}" class="side-menu__item {{ request()->routeIs('member.rank') ? 'active' : '' }}">
                        <i class="bx bx-crown side-menu__icon"></i>
                        <span class="side-menu__label">Rank & Achievements</span>
                    </a>
                </li>

                <!-- Financial Section -->
                <li class="slide__category"><span class="category-name">Financial</span></li>
                
                <!-- Commissions & Earnings -->
                <li class="slide">
                    <a href="{{ route('member.commissions') }}" class="side-menu__item {{ request()->routeIs('member.commissions') ? 'active' : '' }}">
                        <i class="bx bx-money side-menu__icon"></i>
                        <span class="side-menu__label">Commissions</span>
                    </a>
                </li>

                <!-- Withdraw -->
                <li class="slide">
                    <a href="{{ route('member.withdraw') }}" class="side-menu__item {{ request()->routeIs('member.withdraw') ? 'active' : '' }}">
                        <i class="bx bx-credit-card side-menu__icon"></i>
                        <span class="side-menu__label">Withdraw</span>
                    </a>
                </li>

                <!-- Transfer -->
                <li class="slide">
                    <a href="{{ route('member.transfer') }}" class="side-menu__item {{ request()->routeIs('member.transfer') ? 'active' : '' }}">
                        <i class="bx bx-transfer side-menu__icon"></i>
                        <span class="side-menu__label">Transfer</span>
                    </a>
                </li>

                <!-- Wallet/Balance -->
                <li class="slide">
                    <a href="{{ route('member.wallet') }}" class="side-menu__item {{ request()->routeIs('member.wallet') ? 'active' : '' }}">
                        <i class="bx bx-wallet side-menu__icon"></i>
                        <span class="side-menu__label">Wallet</span>
                    </a>
                </li>

                <!-- Reports Section -->
                <li class="slide__category"><span class="category-name">Reports</span></li>
                
                <!-- Sales Report -->
                <li class="slide">
                    <a href="{{ route('member.reports.sales') }}" class="side-menu__item {{ request()->routeIs('member.reports.sales') ? 'active' : '' }}">
                        <i class="bx bx-bar-chart side-menu__icon"></i>
                        <span class="side-menu__label">Sales Report</span>
                    </a>
                </li>

                <!-- Commission Report -->
                <li class="slide">
                    <a href="{{ route('member.reports.commission') }}" class="side-menu__item {{ request()->routeIs('member.reports.commission') ? 'active' : '' }}">
                        <i class="bx bx-line-chart side-menu__icon"></i>
                        <span class="side-menu__label">Commission Report</span>
                    </a>
                </li>

                <!-- Team Report -->
                <li class="slide">
                    <a href="{{ route('member.reports.team') }}" class="side-menu__item {{ request()->routeIs('member.reports.team') ? 'active' : '' }}">
                        <i class="bx bx-group side-menu__icon"></i>
                        <span class="side-menu__label">Team Report</span>
                    </a>
                </li>

                <!-- Payout Report -->
                <li class="slide">
                    <a href="{{ route('member.reports.payout') }}" class="side-menu__item {{ request()->routeIs('member.reports.payout') ? 'active' : '' }}">
                        <i class="bx bx-receipt side-menu__icon"></i>
                        <span class="side-menu__label">Payout Report</span>
                    </a>
                </li>

                <!-- Business Section -->
                <li class="slide__category"><span class="category-name">Business</span></li>
                
                <!-- My Orders -->
                <li class="slide">
                    <a href="{{ route('member.orders.index') }}" class="side-menu__item {{ request()->routeIs('member.orders.*') ? 'active' : '' }}">
                        <i class="bx bx-shopping-bag side-menu__icon"></i>
                        <span class="side-menu__label">My Orders</span>
                    </a>
                </li>

                <!-- Products/Inventory -->
                <li class="slide">
                    <a href="{{ route('member.products.index') }}" class="side-menu__item {{ request()->routeIs('member.products*') ? 'active' : '' }}">
                        <i class="bx bx-package side-menu__icon"></i>
                        <span class="side-menu__label">Products</span>
                    </a>
                </li>

                <!-- Generation Levels -->
                <li class="slide">
                    <a href="{{ route('member.generations') }}" class="side-menu__item {{ request()->routeIs('member.generations') ? 'active' : '' }}">
                        <i class="bx bx-sitemap side-menu__icon"></i>
                        <span class="side-menu__label">Generations</span>
                    </a>
                </li>

                <!-- Support & Resources -->
                <li class="slide__category"><span class="category-name">Support</span></li>

                <!-- Training Center -->
                <li class="slide">
                    <a href="{{ route('member.training') }}" class="side-menu__item {{ request()->routeIs('member.training') ? 'active' : '' }}">
                        <i class="bx bx-book side-menu__icon"></i>
                        <span class="side-menu__label">Training Center</span>
                    </a>
                </li>

                <!-- Support Tickets -->
                <li class="slide">
                    <a href="{{ route('member.support') }}" class="side-menu__item {{ request()->routeIs('member.support') ? 'active' : '' }}">
                        <i class="bx bx-support side-menu__icon"></i>
                        <span class="side-menu__label">Support</span>
                    </a>
                </li>

                <!-- Profile Management -->
                <li class="slide">
                    <a href="{{ route('member.profile') }}" class="side-menu__item {{ request()->routeIs('member.profile') ? 'active' : '' }}">
                        <i class="bx bx-user side-menu__icon"></i>
                        <span class="side-menu__label">My Profile</span>
                    </a>
                </li>

                <!-- Quick Links -->
                <li class="slide__category"><span class="category-name">Quick Actions</span></li>

                <!-- Visit Store -->
                <li class="slide">
                    <a href="{{ route('home') }}" class="side-menu__item" target="_blank">
                        <i class="bx bx-store side-menu__icon"></i>
                        <span class="side-menu__label">Visit Store</span>
                        <i class="bx bx-link-external ms-auto"></i>
                    </a>
                </li>
                </li>

                <!-- Referral Link -->
                <li class="slide">
                    <a href="#" class="side-menu__item" onclick="copyReferralLink()">
                        <i class="bx bx-share side-menu__icon"></i>
                        <span class="side-menu__label">Share Referral</span>
                    </a>
                </li>

                <!-- Help & Support -->
                <li class="slide">
                    <a href="#" class="side-menu__item">
                        <i class="bx bx-support side-menu__icon"></i>
                        <span class="side-menu__label">Help & Support</span>
                    </a>
                </li>

                <!-- Logout -->
                <li class="slide">
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <a href="#" class="side-menu__item text-danger" onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="bx bx-log-out side-menu__icon"></i>
                            <span class="side-menu__label">Logout</span>
                        </a>
                    </form>
                </li>
            </ul>

            <div class="slide-right" id="slide-right">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> 
                    <path d="m10.707 17.707 5.707-5.707-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path> 
                </svg>
            </div>
        </nav>
        <!-- End::nav -->
    </div>
    <!-- End::main-sidebar -->
</aside>
<!-- End::app-sidebar -->

<script>
function copyReferralLink() {
    const referralCode = '{{ Auth::user()->referral_code ?? Auth::user()->id }}';
    const referralLink = `{{ url('/') }}/register?ref=${referralCode}`;
    
    navigator.clipboard.writeText(referralLink).then(function() {
        // Show success message
        alert('Referral link copied to clipboard!');
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
