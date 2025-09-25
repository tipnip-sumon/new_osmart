<!-- Start::app-sidebar -->
<aside class="app-sidebar sticky" id="sidebar">
    <!-- Sidebar Custom Styles -->
    <style>
        /* Points Summary Widget */
        .points-summary .points-widget {
            background: linear-gradient(135deg, #1e3a8a 0%, #3730a3 50%, #581c87 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            margin: 10px 15px;
            padding: 15px;
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(30, 58, 138, 0.3);
            position: relative;
            overflow: hidden;
        }

        .points-summary .points-widget::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }
        
        .points-container {
            width: 100%;
            position: relative;
            z-index: 1;
        }
        
        .points-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }
        
        .points-header i {
            font-size: 18px;
            color: #fbbf24 !important;
            text-shadow: 0 0 8px rgba(251, 191, 36, 0.5);
        }
        
        .points-title {
            font-weight: 600;
            font-size: 14px;
            color: #e5e7eb;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }
        
        .points-balance {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }
        
        .point-item {
            flex: 1;
            text-align: center;
            padding: 8px 6px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .point-item:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        
        .point-label {
            display: block;
            font-size: 10px;
            font-weight: 500;
            color: #cbd5e1;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
            opacity: 0.9;
        }
        
        .point-value {
            display: block;
            font-weight: 700;
            font-size: 13px;
            color: #ffffff;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        /* Specific colors for different point types */
        .point-item:first-child .point-value {
            color: #22c55e; /* Green for Reserve points */
            text-shadow: 0 0 8px rgba(34, 197, 94, 0.4);
        }

        .point-item:last-child .point-value {
            color: #3b82f6; /* Blue for Active points */
            text-shadow: 0 0 8px rgba(59, 130, 246, 0.4);
        }
        
        /* Quick Action Button */
        .quick-action .package-quick-action {
            background: linear-gradient(135deg, #059669 0%, #047857 50%, #065f46 100%);
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            margin: 8px 15px;
            padding: 12px 16px;
            box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);

        /* KYC Menu Specific Styles */
        .slide.has-sub .side-menu__item .badge {
            font-size: 9px;
            padding: 2px 6px;
            border-radius: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .slide.has-sub .slide-menu .side-menu__item .badge {
            font-size: 8px;
            padding: 1px 5px;
            margin-left: 5px;
        }
        
        /* KYC Status Icons */
        .slide.has-sub .slide-menu .side-menu__item i.bx {
            width: 16px;
            height: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
            color: #6b7280;
        }
        
        .slide.has-sub .slide-menu .side-menu__item.active i.bx {
            color: #3b82f6;
        }
        
        /* KYC Progress Animation */
        .slide.has-sub .side-menu__item .badge.bg-warning {
            animation: pulse-warning 2s ease-in-out infinite;
        }
        
        @keyframes pulse-warning {
            0%, 100% {
                opacity: 1;
                transform: scale(1);
            }
            50% {
                opacity: 0.8;
                transform: scale(0.95);
            }
        }
        
        .slide.has-sub .side-menu__item .badge.bg-success {
            animation: pulse-success 3s ease-in-out infinite;
        }
        
        @keyframes pulse-success {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }
            animation: pulse-glow 3s infinite;
            transition: all 0.3s ease;
        }
        
        .quick-action .package-quick-action:hover {
            background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .quick-action .package-quick-action .side-menu__icon {
            color: #6ee7b7 !important;
            text-shadow: 0 0 8px rgba(110, 231, 183, 0.5);
        }

        .quick-action .package-quick-action .side-menu__label .text-success {
            color: #d1fae5 !important;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        .quick-action .package-quick-action .side-menu__label small.text-muted {
            color: #a7f3d0 !important;
            opacity: 0.9;
        }
        
        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
            }
            50% {
                box-shadow: 0 6px 20px rgba(16, 185, 129, 0.5);
            }
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .points-balance {
                flex-direction: column;
                gap: 8px;
            }
            
            .point-item {
                width: 100%;
            }
        }
    </style>

    <!-- Start::main-sidebar-header -->
    <div class="main-sidebar-header">
        <a href="{{ route('member.dashboard') }}" class="header-logo">
            <div class="logo-container">
                <div class="logo-text">
                    <span class="logo-o">O</span><span class="logo-dash">-</span><span class="logo-smart">SMART</span>
                </div>
                <div class="logo-tagline">eCommerce Platform</div>
            </div>
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

                <!-- Points Summary Widget -->
                <li class="slide points-summary">
                    <div class="side-menu__item points-widget">
                        <div class="points-container">
                            <div class="points-header">
                                <i class="bx bx-coin-stack"></i>
                                <span class="points-title">My Points</span>
                            </div>
                            <div class="points-balance">
                                <div class="point-item">
                                    <span class="point-label">Reserve</span>
                                    <span class="point-value">{{ number_format(auth()->user()->reserve_points ?? 0) }}</span>
                                </div>
                                <div class="point-item">
                                    <span class="point-label">Active</span>
                                    <span class="point-value">{{ number_format(auth()->user()->active_points ?? 0) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                <!-- Quick Action: Package Activation -->
                @if((auth()->user()->reserve_points ?? 0) >= 100)
                <li class="slide quick-action">
                    <a href="{{ route('member.packages.index') }}" class="side-menu__item package-quick-action">
                        <i class="bx bx-rocket side-menu__icon"></i>
                        <span class="side-menu__label">
                            <span class="fw-bold">Activate Package</span>
                            <small class="d-block">Points Ready!</small>
                        </span>
                    </a>
                </li>
                @endif
                <!-- Network & Team -->
                <li class="slide__category"><span class="category-name">Network & Team</span></li>
                
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

                <!-- Generation Levels -->
                <li class="slide">
                    <a href="{{ route('member.generations') }}" class="side-menu__item {{ request()->routeIs('member.generations') ? 'active' : '' }}">
                        <i class="bx bx-sitemap side-menu__icon"></i>
                        <span class="side-menu__label">Generations</span>
                    </a>
                </li>

                <!-- Rank & Achievements -->
                <li class="slide has-sub {{ request()->routeIs('member.rank*') || request()->routeIs('member.matching.rank.salary.report') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item">
                        <i class="bx bx-crown side-menu__icon"></i>
                        <span class="side-menu__label">Rank & Achievements</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('member.rank') }}" class="side-menu__item {{ request()->routeIs('member.rank') ? 'active' : '' }}">
                                <i class="bx bx-trophy"></i>
                                Binary Rank Status
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.matching.rank.salary.report') }}" class="side-menu__item {{ request()->routeIs('member.matching.rank.salary.report') ? 'active' : '' }}">
                                <i class="bx bx-bar-chart-alt-2"></i>
                                Rank Salary Report
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Financial Section -->
                <li class="slide__category"><span class="category-name">Financial</span></li>
                    
                <!-- Account Upgrade / Plan Purchase -->
                <li class="slide" style="display: none;">
                    <a href="{{ route('member.plan_purchase') }}" class="side-menu__item {{ request()->routeIs('member.plan_purchase') ? 'active' : '' }}">
                        <i class="bx bx-arrow-to-top side-menu__icon"></i>
                        <span class="side-menu__label">Account Upgrade</span>
                    </a>
                </li>

                <!-- Invest -->
                <li class="slide has-sub {{ request()->routeIs('member.invest*') ? 'open' : '' }}" style="display: none;">
                    <a href="javascript:void(0);" class="side-menu__item">
                        <i class="bx bx-trending-up side-menu__icon"></i>
                        <span class="side-menu__label">Invest</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('member.invest.dashboard') }}" class="side-menu__item {{ request()->routeIs('member.invest.dashboard') ? 'active' : '' }}">
                                <i class="bx bx-tachometer"></i>
                                Investment Dashboard
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.invest.create') }}" class="side-menu__item {{ request()->routeIs('member.invest.create') ? 'active' : '' }}">
                                <i class="bx bx-plus-medical"></i>
                                New Investment
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.invest.index') }}" class="side-menu__item {{ request()->routeIs('member.invest.index') ? 'active' : '' }}">
                                <i class="bx bx-line-chart-down"></i>
                                My Investments
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- Package Activation -->
                <li class="slide has-sub {{ request()->routeIs('member.packages*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item">
                        <i class="bx bx-package side-menu__icon"></i>
                        <span class="side-menu__label">Package Activation</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('member.packages.index') }}" class="side-menu__item {{ request()->routeIs('member.packages.index') ? 'active' : '' }}">
                                <i class="bx bx-rocket"></i>
                                Activate Package
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.packages.index') }}" class="side-menu__item {{ request()->routeIs('member.packages.current') ? 'active' : '' }}">
                                <i class="bx bx-package"></i>
                                Current Package
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.packages.history') }}" class="side-menu__item {{ request()->routeIs('member.packages.history') ? 'active' : '' }}">
                                <i class="bx bx-history"></i>
                                Package History
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- Commissions & Earnings -->
                <li class="slide">
                    <a href="{{ route('member.commissions') }}" class="side-menu__item {{ request()->routeIs('member.commissions') ? 'active' : '' }}">
                        <i class="bx bx-money side-menu__icon"></i>
                        <span class="side-menu__label">Commissions</span>
                    </a>
                </li>

                <!-- Matching Bonus -->
                <li class="slide has-sub {{ request()->routeIs('member.matching*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item">
                        <i class="bx bx-target-lock side-menu__icon"></i>
                        <span class="side-menu__label">Matching Bonus</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('member.matching.dashboard') }}" class="side-menu__item {{ request()->routeIs('member.matching.dashboard') ? 'active' : '' }}">
                                <i class="bx bx-tachometer"></i>
                                Matching Dashboard
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.matching.history') }}" class="side-menu__item {{ request()->routeIs('member.matching.history') ? 'active' : '' }}">
                                <i class="bx bx-history"></i>
                                Matching History
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.matching.qualifications') }}" class="side-menu__item {{ request()->routeIs('member.matching.qualifications') ? 'active' : '' }}">
                                <i class="bx bx-award"></i>
                                Qualifications
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.matching.calculator') }}" class="side-menu__item {{ request()->routeIs('member.matching.calculator') ? 'active' : '' }}">
                                <i class="bx bx-calculator"></i>
                                Bonus Calculator
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Daily Cashback -->
                <li class="slide has-sub {{ request()->routeIs('member.daily-cashback*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item">
                        <i class="bx bx-gift side-menu__icon"></i>
                        <span class="side-menu__label">Daily Cashback</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('member.daily-cashback.dashboard') }}" class="side-menu__item {{ request()->routeIs('member.daily-cashback.dashboard') ? 'active' : '' }}">
                                <i class="bx bx-tachometer"></i>
                                Cashback Dashboard
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.daily-cashback.history') }}" class="side-menu__item {{ request()->routeIs('member.daily-cashback.history') ? 'active' : '' }}">
                                <i class="bx bx-history"></i>
                                Cashback History
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.daily-cashback.pending') }}" class="side-menu__item {{ request()->routeIs('member.daily-cashback.pending') ? 'active' : '' }}">
                                <i class="bx bx-time-five"></i>
                                Pending Cashbacks
                                @if(Auth::check())
                                    @php
                                        $pendingCount = \App\Models\UserDailyCashback::where('user_id', Auth::id())->where('status', 'pending')->count();
                                    @endphp
                                    @if($pendingCount > 0)
                                        <span class="badge bg-warning ms-2">{{ $pendingCount }}</span>
                                    @endif
                                @endif
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Wallet & Transactions -->
                <li class="slide has-sub {{ request()->routeIs('member.wallet*') || request()->routeIs('member.withdraw*') || request()->routeIs('member.transfer*') || request()->routeIs('member.add-fund*') || request()->routeIs('member.fund-history*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item">
                        <i class="bx bx-wallet side-menu__icon"></i>
                        <span class="side-menu__label">Wallet & Transactions</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('member.wallet') }}" class="side-menu__item {{ request()->routeIs('member.wallet') ? 'active' : '' }}">
                                <i class="bx bx-wallet"></i>
                                My Wallet
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.add-fund') }}" class="side-menu__item {{ request()->routeIs('member.add-fund*') ? 'active' : '' }}">
                                <i class="bx bx-plus-circle"></i>
                                Add Fund
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.fund-history') }}" class="side-menu__item {{ request()->routeIs('member.fund-history*') ? 'active' : '' }}">
                                <i class="bx bx-history"></i>
                                Fund History
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.withdraw') }}" class="side-menu__item {{ request()->routeIs('member.withdraw') ? 'active' : '' }}">
                                <i class="bx bx-credit-card"></i>
                                Withdraw
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.transfer') }}" class="side-menu__item {{ request()->routeIs('member.transfer') ? 'active' : '' }}">
                                <i class="bx bx-transfer"></i>
                                Transfer
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Reports Section -->
                <li class="slide__category"><span class="category-name">Reports & Analytics</span></li>
                
                <!-- Reports Submenu -->
                <li class="slide has-sub {{ request()->routeIs('member.reports.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item">
                        <i class="bx bx-bar-chart side-menu__icon"></i>
                        <span class="side-menu__label">Reports</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('member.reports.sales') }}" class="side-menu__item {{ request()->routeIs('member.reports.sales') ? 'active' : '' }}">
                                <i class="bx bx-trending-up"></i>
                                Sales Report
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.reports.commission') }}" class="side-menu__item {{ request()->routeIs('member.reports.commission') ? 'active' : '' }}">
                                <i class="bx bx-line-chart"></i>
                                Commission Report
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.reports.team') }}" class="side-menu__item {{ request()->routeIs('member.reports.team') ? 'active' : '' }}">
                                <i class="bx bx-group"></i>
                                Team Report
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.reports.payout') }}" class="side-menu__item {{ request()->routeIs('member.reports.payout') ? 'active' : '' }}">
                                <i class="bx bx-receipt"></i>
                                Payout Report
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Business Section -->
                <li class="slide__category"><span class="category-name">Business</span></li>
                
                <!-- Link Sharing & Affiliate Earnings (FIRST PRIORITY) -->
                <li class="slide has-sub {{ request()->routeIs('member.link-sharing*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item">
                        <i class="bx bx-share-alt side-menu__icon"></i>
                        <span class="side-menu__label">Link Sharing</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('member.link-sharing.dashboard') }}" class="side-menu__item {{ request()->routeIs('member.link-sharing.dashboard') ? 'active' : '' }}">
                                <i class="bx bx-tachometer"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.link-sharing.history') }}" class="side-menu__item {{ request()->routeIs('member.link-sharing.history') ? 'active' : '' }}">
                                <i class="bx bx-history"></i>
                                Sharing History
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.link-sharing.stats') }}" class="side-menu__item {{ request()->routeIs('member.link-sharing.stats') ? 'active' : '' }}">
                                <i class="bx bx-bar-chart-alt-2"></i>
                                Performance Stats
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.link-sharing.upgrade') }}" class="side-menu__item {{ request()->routeIs('member.link-sharing.upgrade') ? 'active' : '' }}">
                                <i class="bx bx-trending-up"></i>
                                Package Upgrade
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Orders & Points Management (COMBINED MENU) -->
                <li class="slide has-sub {{ request()->routeIs('member.orders*') || request()->routeIs('member.direct-point-purchase*') || request()->routeIs('member.points*') || request()->routeIs('member.point-transactions*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item">
                        <i class="bx bx-shopping-bag side-menu__icon"></i>
                        <span class="side-menu__label">Orders & Points</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <!-- Order Management Section -->
                        <li class="slide__category"><span class="category-name">Order Management</span></li>
                        <li class="slide">
                            <a href="{{ route('member.orders.create') }}" class="side-menu__item {{ request()->routeIs('member.orders.create') ? 'active' : '' }}">
                                <i class="bx bx-plus-circle"></i>
                                Create Order
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.orders.index') }}" class="side-menu__item {{ request()->routeIs('member.orders.index') ? 'active' : '' }}">
                                <i class="bx bx-list-ul"></i>
                                Order History
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.orders.index', ['status' => 'pending']) }}" class="side-menu__item {{ request()->routeIs('member.orders.index') && request('status') === 'pending' ? 'active' : '' }}">
                                <i class="bx bx-clock"></i>
                                Pending Orders
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.orders.index', ['status' => 'completed']) }}" class="side-menu__item {{ request()->routeIs('member.orders.index') && request('status') === 'completed' ? 'active' : '' }}">
                                <i class="bx bx-check-circle"></i>
                                Completed Orders
                            </a>
                        </li>
                        
                        <!-- Points Management Section -->
                        <li class="slide__category"><span class="category-name">Points Management</span></li>
                        <li class="slide">
                            <a href="{{ route('member.direct-point-purchase.index') }}" class="side-menu__item {{ request()->routeIs('member.direct-point-purchase.index') ? 'active' : '' }}">
                                <i class="bx bx-shopping-bag"></i>
                                Purchase Points
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.point-transactions.index') }}" class="side-menu__item {{ request()->routeIs('member.point-transactions.*') ? 'active' : '' }}">
                                <i class="bx bx-history"></i>
                                Point Transactions
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Products/Inventory -->
                <li class="slide has-sub {{ request()->routeIs('member.products*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item">
                        <i class="bx bx-package side-menu__icon"></i>
                        <span class="side-menu__label">Products</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('member.products.index') }}" class="side-menu__item {{ request()->routeIs('member.products.index') ? 'active' : '' }}">
                                <i class="bx bx-grid-alt"></i>
                                All Products
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.products.favorites') }}" class="side-menu__item {{ request()->routeIs('member.products.favorites') ? 'active' : '' }}">
                                <i class="bx bx-heart"></i>
                                My Favorites
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.products.shared') }}" class="side-menu__item {{ request()->routeIs('member.products.shared') ? 'active' : '' }}">
                                <i class="bx bx-share-alt"></i>
                                Shared Products
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.products.commissions') }}" class="side-menu__item {{ request()->routeIs('member.products.commissions') ? 'active' : '' }}">
                                <i class="bx bx-line-chart"></i>
                                Product Commissions
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Support & Resources -->
                <li class="slide__category"><span class="category-name">Support & Resources</span></li>

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

                <!-- KYC Verification -->
                @auth
                <li class="slide has-sub {{ request()->routeIs('member.kyc.*') ? 'open' : '' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('member.kyc.*') ? 'active' : '' }}">
                        <i class="bx bx-shield-check side-menu__icon"></i>
                        <span class="side-menu__label">KYC Verification</span>
                        @if(auth()->user() && !auth()->user()->is_kyc_verified)
                            <span class="badge bg-warning ms-auto">Pending</span>
                        @elseif(auth()->user() && auth()->user()->is_kyc_verified)
                            <span class="badge bg-success ms-auto">Verified</span>
                        @endif
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide side-menu__label1">
                            <a href="javascript:void(0)">KYC Verification</a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.kyc.index') }}" class="side-menu__item {{ request()->routeIs('member.kyc.index') ? 'active' : '' }}">
                                <i class="bx bx-file-blank"></i>
                                KYC Dashboard
                                @if(auth()->user() && auth()->user()->kyc_status === 'pending')
                                    <span class="badge bg-info ms-auto">Review</span>
                                @endif
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.kyc.step', 1) }}" class="side-menu__item">
                                <i class="bx bx-user-check"></i>
                                Personal Information
                                @if(auth()->user() && auth()->user()->kyc_status === 'not_started')
                                    <span class="badge bg-secondary ms-auto">Start</span>
                                @endif
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.kyc.step', 2) }}" class="side-menu__item">
                                <i class="bx bx-id-card"></i>
                                Document Information
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.kyc.step', 3) }}" class="side-menu__item">
                                <i class="bx bx-map"></i>
                                Address Verification
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.kyc.step', 4) }}" class="side-menu__item">
                                <i class="bx bx-cloud-upload"></i>
                                Document Upload
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('member.kyc.step', 5) }}" class="side-menu__item">
                                <i class="bx bx-check-circle"></i>
                                Review & Submit
                            </a>
                        </li>
                        @if(auth()->user() && auth()->user()->kyc_status === 'verified')
                        <li class="slide">
                            <a href="{{ route('member.kyc.certificate') }}" class="side-menu__item {{ request()->routeIs('member.kyc.certificate') ? 'active' : '' }}">
                                <i class="bx bx-certification"></i>
                                Verification Certificate
                                <span class="badge bg-success ms-auto">Download</span>
                            </a>
                        </li>
                        @endif
                        @if(auth()->user() && auth()->user()->kyc_status === 'rejected')
                        <li class="slide">
                            <a href="#" class="side-menu__item" onclick="event.preventDefault(); document.getElementById('kyc-resubmit-form').submit();">
                                <i class="bx bx-revision"></i>
                                Resubmit Documents
                                <span class="badge bg-danger ms-auto">Action</span>
                            </a>
                            <form id="kyc-resubmit-form" action="{{ route('member.kyc.resubmit') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                        @endif
                    </ul>
                </li>
                @endauth

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
                <!-- Vendor Application for Affiliate Members -->
                @if(auth()->user()->role === 'affiliate')
                <li class="slide">
                    <a href="{{ route('member.vendor-application') }}" class="side-menu__item {{ request()->routeIs('member.vendor-application*') ? 'active' : '' }}" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 50%, #b45309 100%); color: white; border-radius: 10px; margin: 8px 15px; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);">
                        <i class="bx bx-store side-menu__icon" style="color: #fef3c7 !important;"></i>
                        <span class="side-menu__label">
                            <span class="fw-bold">Become Vendor</span>
                            <small class="d-block" style="color: #fef3c7;">Apply for Store Access</small>
                        </span>
                        <span class="badge bg-light text-dark">Apply</span>
                    </a>
                </li>
                @endif
                <!-- Referral Link -->
                <li class="slide">
                    <a href="#" class="side-menu__item" onclick="copyReferralLink()">
                        <i class="bx bx-link side-menu__icon"></i>
                        <span class="side-menu__label">Copy Referral Link</span>
                        <i class="bx bx-copy ms-auto"></i>
                    </a>
                </li>

                <!-- Logout -->
                <li class="slide">
                    <a href="#" class="side-menu__item text-danger" onclick="event.preventDefault(); confirmLogout();">
                        <i class="bx bx-log-out side-menu__icon"></i>
                        <span class="side-menu__label">Logout</span>
                    </a>
                    <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>

            <div class="slide-right" id="slide-right">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> 
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path> 
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
    const referralLink = `{{ url('/affiliate/register?ref=' . (auth()->user()->username ?? auth()->user()->id)) }}`;
    navigator.clipboard.writeText(referralLink).then(function() {
        // Show success message
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Copied!',
                text: 'Referral link copied to clipboard',
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            alert('Referral link copied to clipboard!');
        }
    }).catch(function() {
        // Fallback for older browsers
        prompt('Copy this referral link:', referralLink);
    });
}
</script>

<style>
/* O-SMART eCommerce Logo Styles - Enhanced for Dark Sidebar */
.header-logo {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 1rem 0.5rem !important;
    text-decoration: none !important;
    transition: all 0.3s ease !important;
}

.logo-container {
    text-align: center;
    position: relative;
}

.logo-text {
    font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
    font-weight: 800;
    font-size: 1.75rem;
    line-height: 1;
    letter-spacing: -0.02em;
    margin-bottom: 0.25rem;
    position: relative;
    /* Bright gradient for dark sidebar */
    background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 30%, #06b6d4 70%, #14b8a6 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    filter: drop-shadow(0 0 8px rgba(96, 165, 250, 0.3));
}

.logo-o {
    display: inline-block;
    font-size: 2rem;
    font-weight: 900;
    /* Vibrant orange-yellow for high contrast */
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 50%, #f97316 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    transform: rotate(-5deg);
    margin-right: -0.1em;
    position: relative;
    filter: drop-shadow(0 0 6px rgba(251, 191, 36, 0.4));
}

.logo-o::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 120%;
    height: 120%;
    background: radial-gradient(circle, rgba(251, 191, 36, 0.2) 0%, rgba(245, 158, 11, 0.1) 50%, transparent 70%);
    transform: translate(-50%, -50%);
    border-radius: 50%;
    z-index: -1;
}

.logo-dash {
    color: #94a3b8;
    font-weight: 400;
    margin: 0 0.1em;
    text-shadow: 0 0 4px rgba(148, 163, 184, 0.5);
}

.logo-smart {
    background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 50%, #06b6d4 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    position: relative;
}

.logo-tagline {
    font-family: 'Inter', sans-serif;
    font-size: 0.6rem;
    font-weight: 500;
    color: #cbd5e1;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-top: 0.2rem;
    opacity: 0.9;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
}

/* Hover Effects - Enhanced for visibility */
.header-logo:hover .logo-text {
    transform: translateY(-1px);
    background: linear-gradient(135deg, #93c5fd 0%, #60a5fa 30%, #22d3ee 70%, #06b6d4 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    filter: drop-shadow(0 0 12px rgba(96, 165, 250, 0.5));
}

.header-logo:hover .logo-o {
    transform: rotate(0deg) scale(1.05);
    background: linear-gradient(135deg, #fde047 0%, #fbbf24 50%, #f59e0b 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    filter: drop-shadow(0 0 10px rgba(251, 191, 36, 0.6));
}

.header-logo:hover .logo-dash {
    color: #e2e8f0;
    text-shadow: 0 0 6px rgba(226, 232, 240, 0.7);
}

.header-logo:hover .logo-tagline {
    color: #60a5fa;
    opacity: 1;
    text-shadow: 0 0 4px rgba(96, 165, 250, 0.5);
}

/* Dark Theme Support - For when sidebar theme changes */
[data-theme="dark"] .logo-text,
[data-theme-mode="dark"] .logo-text {
    background: linear-gradient(135deg, #93c5fd 0%, #60a5fa 30%, #22d3ee 70%, #06b6d4 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    filter: drop-shadow(0 0 10px rgba(147, 197, 253, 0.4));
}

[data-theme="dark"] .logo-o,
[data-theme-mode="dark"] .logo-o {
    background: linear-gradient(135deg, #fde047 0%, #fbbf24 50%, #f59e0b 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    filter: drop-shadow(0 0 8px rgba(253, 224, 71, 0.5));
}

[data-theme="dark"] .logo-dash,
[data-theme-mode="dark"] .logo-dash {
    color: #cbd5e1;
    text-shadow: 0 0 4px rgba(203, 213, 225, 0.5);
}

[data-theme="dark"] .logo-tagline,
[data-theme-mode="dark"] .logo-tagline {
    color: #e2e8f0;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
}

/* Light Theme Support - For light sidebar backgrounds */
[data-theme="light"] .logo-text,
[data-theme-mode="light"] .logo-text {
    background: linear-gradient(135deg, #1e40af 0%, #3b82f6 30%, #0ea5e9 70%, #0891b2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    filter: drop-shadow(0 0 6px rgba(30, 64, 175, 0.2));
}

[data-theme="light"] .logo-o,
[data-theme-mode="light"] .logo-o {
    background: linear-gradient(135deg, #ea580c 0%, #f59e0b 50%, #d97706 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    filter: drop-shadow(0 0 4px rgba(234, 88, 12, 0.3));
}

[data-theme="light"] .logo-dash,
[data-theme-mode="light"] .logo-dash {
    color: #64748b;
    text-shadow: none;
}

[data-theme="light"] .logo-tagline,
[data-theme-mode="light"] .logo-tagline {
    color: #64748b;
    text-shadow: none;
}

/* Responsive Design */
@media (max-width: 768px) {
    .logo-text {
        font-size: 1.5rem;
    }
    
    .logo-o {
        font-size: 1.75rem;
    }
    
    .logo-tagline {
        font-size: 0.55rem;
    }
}

/* Sidebar Collapsed State */
.app-sidebar.sidebar-mini .logo-tagline {
    opacity: 0;
    transform: scale(0.8);
    transition: all 0.3s ease;
}

.app-sidebar.sidebar-mini .logo-text {
    font-size: 1.25rem;
}

.app-sidebar.sidebar-mini .logo-o {
    font-size: 1.5rem;
}

/* Animation for Logo Load */
@keyframes logoFadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.logo-container {
    animation: logoFadeIn 0.6s ease-out;
}

/* Pulse Effect for Logo */
@keyframes logoPulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.02);
    }
}

.header-logo:active .logo-container {
    animation: logoPulse 0.3s ease-in-out;
}

/* Enhanced Glow Effect for Dark Backgrounds */
@keyframes logoGlow {
    0%, 100% {
        filter: drop-shadow(0 0 8px rgba(96, 165, 250, 0.3));
    }
    50% {
        filter: drop-shadow(0 0 12px rgba(96, 165, 250, 0.5));
    }
}

.logo-text {
    animation: logoGlow 3s ease-in-out infinite;
}

/* Special effect for the O */
@keyframes oGlow {
    0%, 100% {
        filter: drop-shadow(0 0 6px rgba(251, 191, 36, 0.4));
    }
    50% {
        filter: drop-shadow(0 0 10px rgba(251, 191, 36, 0.6));
    }
}

.logo-o {
    animation: oGlow 2.5s ease-in-out infinite;
}
</style>
