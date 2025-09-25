@extends('member.layouts.app')

@section('title', 'Affiliate Dashboard')

@section('content')

<!-- Welcome Message Alert -->
@if(session('login_success') || !session('dashboard_visited'))
<div class="row">
    <div class="col-12">
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-left: 5px solid #047857 !important;">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="bx bx-check-circle fs-24 text-white"></i>
                </div>
                <div class="flex-grow-1">
                    <h5 class="text-white mb-1 fw-bold">
                        <i class="bx bx-party me-2"></i>Success!
                    </h5>
                    <p class="text-white mb-0 opacity-90">
                        Welcome to your affiliate dashboard, <strong>{{ Auth::user()->name ?? Auth::user()->username }}</strong>! 
                        You're all set to start earning commissions.
                    </p>
                </div>
                <div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            <div class="mt-2">
                <small class="text-white opacity-75">
                    <i class="bx bx-info-circle me-1"></i>
                    Get started by adding funds, activating packages, or inviting new members to your network.
                </small>
            </div>
        </div>
    </div>
</div>
@php
    session(['dashboard_visited' => true]);
@endphp
@endif

<style>
/* News Ticker Styles */
.news-ticker-container {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: white;
    padding: 0;
    margin-bottom: 1rem;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    position: relative;
}

.news-ticker-header {
    background: rgba(255, 255, 255, 0.1);
    padding: 8px 16px;
    font-weight: 600;
    font-size: 14px;
    display: flex;
    align-items: center;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.news-ticker-header i {
    margin-right: 8px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.news-ticker {
    height: 50px;
    overflow: hidden;
    position: relative;
    display: flex;
    align-items: center;
    padding: 0 16px;
}

.news-ticker-content {
    display: flex;
    align-items: center;
    white-space: nowrap;
    animation: scroll-left 60s linear infinite;
    padding-left: 100%;
}

.news-ticker-item {
    margin-right: 80px;
    display: inline-flex;
    align-items: center;
    font-size: 14px;
    font-weight: 500;
}

.news-ticker-item .badge {
    margin-right: 8px;
    font-size: 10px;
    padding: 4px 8px;
}

@keyframes scroll-left {
    0% { transform: translate3d(0, 0, 0); }
    100% { transform: translate3d(-100%, 0, 0); }
}

.news-ticker:hover .news-ticker-content {
    animation-play-state: paused;
}

/* International Smart Card Design System */
:root {
    /* Light Theme Variables */
    --smart-card-bg-light: #ffffff;
    --smart-card-border-light: rgba(0, 0, 0, 0.08);
    --smart-card-shadow-light: 0 2px 12px rgba(0, 0, 0, 0.08);
    --smart-card-text-primary-light: #1a1a1a;
    --smart-card-text-secondary-light: #6b7280;
    --smart-card-accent-light: #3b82f6;
    
    /* Dark Theme Variables */
    --smart-card-bg-dark: #1f2937;
    --smart-card-border-dark: rgba(255, 255, 255, 0.1);
    --smart-card-shadow-dark: 0 2px 12px rgba(0, 0, 0, 0.3);
    --smart-card-text-primary-dark: #f9fafb;
    --smart-card-text-secondary-dark: #9ca3af;
    --smart-card-accent-dark: #60a5fa;
}

/* Smart Card Base Styles */
.smart-card {
    background: var(--smart-card-bg-light) !important;
    border: 1px solid var(--smart-card-border-light) !important;
    border-radius: 16px !important;
    box-shadow: var(--smart-card-shadow-light) !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    position: relative !important;
    overflow: hidden !important;
    color: var(--smart-card-text-primary-light) !important;
}

.smart-card:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12) !important;
}

.smart-card .card-header {
    background: transparent !important;
    border-bottom: 1px solid var(--smart-card-border-light) !important;
    padding: 1.25rem 1.5rem !important;
    font-weight: 600 !important;
    color: var(--smart-card-text-primary-light) !important;
}

.smart-card .card-body {
    padding: 1.5rem !important;
    color: var(--smart-card-text-primary-light) !important;
}

/* Icon Colors - Light Theme */
.smart-card .bx {
    color: var(--smart-card-accent-light) !important;
}

.smart-card .avatar.bg-primary-transparent .bx {
    color: #3b82f6 !important;
}

.smart-card .avatar.bg-success-transparent .bx {
    color: #10b981 !important;
}

.smart-card .avatar.bg-info-transparent .bx {
    color: #06b6d4 !important;
}

.smart-card .avatar.bg-warning-transparent .bx {
    color: #f59e0b !important;
}

/* Text Colors */
.smart-card h1,
.smart-card h2,
.smart-card h3,
.smart-card h4,
.smart-card h5,
.smart-card h6 {
    color: var(--smart-card-text-primary-light) !important;
}

.smart-card .text-muted {
    color: var(--smart-card-text-secondary-light) !important;
}

/* Dark Theme Support */
[data-theme="dark"] .smart-card,
[data-theme-mode="dark"] .smart-card {
    background: var(--smart-card-bg-dark) !important;
    border-color: var(--smart-card-border-dark) !important;
    box-shadow: var(--smart-card-shadow-dark) !important;
    color: var(--smart-card-text-primary-dark) !important;
}

[data-theme="dark"] .smart-card .card-header,
[data-theme-mode="dark"] .smart-card .card-header {
    border-bottom-color: var(--smart-card-border-dark) !important;
    color: var(--smart-card-text-primary-dark) !important;
}

[data-theme="dark"] .smart-card .card-body,
[data-theme-mode="dark"] .smart-card .card-body {
    color: var(--smart-card-text-primary-dark) !important;
}

[data-theme="dark"] .smart-card h1,
[data-theme="dark"] .smart-card h2,
[data-theme="dark"] .smart-card h3,
[data-theme="dark"] .smart-card h4,
[data-theme="dark"] .smart-card h5,
[data-theme="dark"] .smart-card h6,
[data-theme-mode="dark"] .smart-card h1,
[data-theme-mode="dark"] .smart-card h2,
[data-theme-mode="dark"] .smart-card h3,
[data-theme-mode="dark"] .smart-card h4,
[data-theme-mode="dark"] .smart-card h5,
[data-theme-mode="dark"] .smart-card h6 {
    color: var(--smart-card-text-primary-dark) !important;
}

[data-theme="dark"] .smart-card .text-muted,
[data-theme-mode="dark"] .smart-card .text-muted {
    color: var(--smart-card-text-secondary-dark) !important;
}

/* Dark Theme Icon Colors */
[data-theme="dark"] .smart-card .bx,
[data-theme-mode="dark"] .smart-card .bx {
    color: var(--smart-card-accent-dark) !important;
}

[data-theme="dark"] .smart-card .avatar.bg-primary-transparent .bx,
[data-theme-mode="dark"] .smart-card .avatar.bg-primary-transparent .bx {
    color: #60a5fa !important;
}

[data-theme="dark"] .smart-card .avatar.bg-success-transparent .bx,
[data-theme-mode="dark"] .smart-card .avatar.bg-success-transparent .bx {
    color: #34d399 !important;
}

[data-theme="dark"] .smart-card .avatar.bg-info-transparent .bx,
[data-theme-mode="dark"] .smart-card .avatar.bg-info-transparent .bx {
    color: #22d3ee !important;
}

[data-theme="dark"] .smart-card .avatar.bg-warning-transparent .bx,
[data-theme-mode="dark"] .smart-card .avatar.bg-warning-transparent .bx {
    color: #fbbf24 !important;
}

/* Success Color Support for Available Balance */
.bg-purple-transparent {
    background: rgba(147, 51, 234, 0.1) !important;
}

.text-purple {
    color: #9333ea !important;
}

[data-theme="dark"] .smart-card .avatar.bg-purple-transparent .bx,
[data-theme-mode="dark"] .smart-card .avatar.bg-purple-transparent .bx {
    color: #a855f7 !important;
}

/* Progressive Enhancement Gradients */
.smart-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #3b82f6, #06b6d4, #10b981);
    border-radius: 16px 16px 0 0;
    opacity: 0.8;
}

.smart-card:hover::before {
    opacity: 1;
}

/* Commission Card Styles */
.commission-card {
    transition: all 0.3s ease;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(248, 250, 252, 0.8) 100%);
    backdrop-filter: blur(10px);
}

.commission-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12) !important;
}

.commission-card .avatar-sm {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
}

.commission-card .card-body {
    position: relative;
    overflow: hidden;
}

.commission-card .card-body::before {
    content: '';
    position: absolute;
    top: 0;
    right: -20px;
    width: 40px;
    height: 100%;
    background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transform: skewX(-15deg);
    opacity: 0;
    transition: all 0.6s ease;
}

.commission-card:hover .card-body::before {
    opacity: 1;
    right: calc(100% + 20px);
}

/* Income card clickable cursor */
.card[onclick] {
    cursor: pointer;
    user-select: none;
}

/* Additional color classes for commission cards */
.text-teal { color: #14b8a6 !important; }
.text-violet { color: #8b5cf6 !important; }
.bg-teal-subtle { background-color: rgba(20, 184, 166, 0.1) !important; }
.bg-violet-subtle { background-color: rgba(139, 92, 246, 0.1) !important; }

/* Summary stats in income wallet */
.income-details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.5rem;
}
</style>

<!-- News Ticker -->
@php
    $activeNotices = App\Models\AdminNotice::active()->byPriority()->get();
@endphp

@if($activeNotices->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="news-ticker-container">
            <div class="news-ticker-header">
                <i class="bx bx-broadcast"></i>
                <span>Latest Updates</span>
            </div>
            <div class="news-ticker">
                <div class="news-ticker-content">
                    @foreach($activeNotices as $notice)
                        <div class="news-ticker-item">
                            <span class="badge 
                                @if($notice->type == 'success') bg-success
                                @elseif($notice->type == 'warning') bg-warning
                                @elseif($notice->type == 'danger') bg-danger
                                @else bg-info
                                @endif
                            ">{{ strtoupper($notice->type) }}</span>
                            {{ $notice->message }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Priority Action Buttons -->
<div class="row mt-4 mb-4">
    <div class="col-12">
        <div class="card smart-card border-0 quick-actions-card">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h5 class="mb-1" style="color: white !important;">
                            <i class="bx bx-zap me-2"></i>Quick Actions
                        </h5>
                        <small style="color: rgba(255, 255, 255, 0.8) !important;">Essential actions for your business growth</small>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('member.add-fund') }}" class="btn btn-info btn-sm px-3">
                            <i class="bx bx-plus-circle me-1"></i> Add Fund
                        </a>
                        <a href="{{ route('member.direct-point-purchase.index') }}" class="btn btn-primary btn-sm px-3">
                            <i class="bx bx-coin me-1"></i> Point Purchase
                        </a>
                        <a href="{{ route('member.packages.index') }}" class="btn btn-secondary btn-sm px-3">
                            <i class="bx bx-package me-1"></i> Activate Package
                        </a>
                        <a href="{{ route('member.link-sharing.dashboard') }}" class="btn btn-warning btn-sm px-3">
                            <i class="bx bx-share-alt me-1"></i> Link Sharing
                        </a>
                        <a href="{{ route('member.orders.create') }}" class="btn btn-success btn-sm px-3">
                            <i class="bx bx-shopping-bag me-1"></i> New Order
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Wallet Cards - Serial Order -->
<div class="row mt-4 my-4">
    <!-- 1. Available Balance -->
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card smart-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="mb-2">
                            <span class="d-block fw-medium">Available Balance</span>
                        </div>
                        <h4 class="fw-semibold mb-2" id="available_balance">{{ formatCurrency((Auth::user()->deposit_wallet ?? 0) + (Auth::user()->interest_wallet ?? 0)) }}</h4>
                        <div>
                            <span class="text-success me-1"><i class="ri-wallet-line align-middle"></i>Total</span>
                            <span class="text-muted">available funds</span>
                        </div>
                    </div>
                    <div class="ms-2">
                        <span class="avatar avatar-md bg-success-transparent">
                            <i class="bx bx-wallet fs-18"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Purchase Wallet -->
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card smart-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="mb-2">
                            <span class="d-block fw-medium">Purchase Wallet</span>
                        </div>
                        <h4 class="fw-semibold mb-2" id="deposit_wallet">{{ formatCurrency($walletStats['deposit_wallet'] ?? 0) }}</h4>
                        <div>
                            <a href="{{ route('member.add-fund') }}" class="btn btn-sm btn-outline-info">
                                <i class="bx bx-plus-circle me-1"></i> Add Fund
                            </a>
                        </div>
                    </div>
                    <div class="ms-2">
                        <span class="avatar avatar-md bg-info-transparent">
                            <i class="bx bx-shopping-bag fs-18"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Income Wallet -->
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card smart-card" style="cursor: pointer;" onclick="toggleIncomeDetails()">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="mb-2">
                            <span class="d-block fw-medium">Income Wallet</span>
                        </div>
                        <h4 class="fw-semibold mb-2" id="income_wallet">{{ formatCurrency($walletStats['income_wallet'] ?? 0) }}</h4>
                        <div>
                            <span class="text-success me-1"><i class="ri-money-dollar-circle-line align-middle"></i>Total Earnings</span>
                            <span class="text-muted">{{ formatCurrency($bonusStats['total_bonus'] ?? 0) }}</span>
                        </div>
                    </div>
                    <div class="ms-2">
                        <span class="avatar avatar-md bg-success-transparent">
                            <i class="bx bx-dollar fs-18"></i>
                        </span>
                    </div>
                </div>
                
                <!-- Commission Breakdown Details (Initially Hidden) -->
                <div id="incomeDetails" class="mt-3" style="display: none;">
                    <hr class="mb-2">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted"><i class="ri-handshake-line me-1"></i>Sponsor:</small>
                                <small class="fw-medium text-primary">{{ formatCurrency($bonusStats['sponsor_bonus'] ?? 0) }}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted"><i class="ri-git-branch-line me-1"></i>Binary:</small>
                                <small class="fw-medium text-info">{{ formatCurrency($bonusStats['binary_bonus'] ?? 0) }}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted"><i class="ri-team-line me-1"></i>Team:</small>
                                <small class="fw-medium text-warning">{{ formatCurrency($bonusStats['team_bonus'] ?? 0) }}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted"><i class="ri-medal-line me-1"></i>Rank:</small>
                                <small class="fw-medium text-danger">{{ formatCurrency($bonusStats['rank_bonus'] ?? 0) }}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted"><i class="ri-share-line me-1"></i>Link Share:</small>
                                <small class="fw-medium text-success">{{ formatCurrency($bonusStats['link_share_bonus'] ?? 0) }}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted"><i class="ri-money-dollar-box-line me-1"></i>Salary:</small>
                                <small class="fw-medium text-secondary">{{ formatCurrency($bonusStats['rank_salary'] ?? 0) }}</small>
                            </div>
                        </div>
                        @if(($bonusStats['cash_back'] ?? 0) > 0)
                        <div class="col-6">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted"><i class="ri-refund-2-line me-1"></i>Cashback:</small>
                                <small class="fw-medium text-teal">{{ formatCurrency($bonusStats['cash_back'] ?? 0) }}</small>
                            </div>
                        </div>
                        @endif
                        @if(($bonusStats['kyc_bonus'] ?? 0) > 0)
                        <div class="col-6">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted"><i class="ri-shield-check-line me-1"></i>KYC Bonus:</small>
                                <small class="fw-medium text-purple">{{ formatCurrency($bonusStats['kyc_bonus'] ?? 0) }}</small>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="text-center mt-2">
                        <a href="{{ route('member.commissions') }}" class="btn btn-sm btn-outline-primary">
                            <i class="ri-eye-line me-1"></i>View Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Reserve Points -->
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card smart-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="mb-2">
                            <span class="d-block fw-medium">Reserve Points</span>
                        </div>
                        <h4 class="fw-semibold mb-2" id="reserve_points">{{ number_format(Auth::user()->reserve_points ?? 0) }}</h4>
                        <div>
                            <span class="text-primary me-1"><i class="ri-coin-line align-middle"></i>Available</span>
                            <span class="text-muted">points</span>
                        </div>
                    </div>
                    <div class="ms-2">
                        <span class="avatar avatar-md bg-primary-transparent">
                            <i class="bx bx-coin-stack fs-18"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Commission Breakdown Cards -->
<div class="row mt-4" style="display:none;">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="card-title mb-0">
                <i class="ri-money-dollar-circle-line text-success me-2"></i>Commission Breakdown
            </h5>
            <a href="{{ route('member.commissions') }}" class="btn btn-sm btn-outline-primary">
                <i class="ri-eye-line me-1"></i>View All
            </a>
        </div>
    </div>
    
    <!-- Sponsor Bonus -->
    <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-6 col-sm-12">
        <div class="card border-0 shadow-sm commission-card" style="border-left: 4px solid #3b82f6 !important;">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <div class="avatar-sm bg-primary-subtle rounded">
                            <i class="ri-handshake-line fs-18 text-primary"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1 fw-medium" style="font-size: 11px;">SPONSOR BONUS</p>
                        <h6 class="mb-0 text-primary fw-semibold" id="sponsor-bonus">{{ formatCurrency($bonusStats['sponsor_bonus'] ?? 0) }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Binary Bonus -->
    <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-6 col-sm-12">
        <div class="card border-0 shadow-sm commission-card" style="border-left: 4px solid #06b6d4 !important;">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <div class="avatar-sm bg-info-subtle rounded">
                            <i class="ri-git-branch-line fs-18 text-info"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1 fw-medium" style="font-size: 11px;">BINARY BONUS</p>
                        <h6 class="mb-0 text-info fw-semibold" id="binary-bonus">{{ formatCurrency($bonusStats['binary_bonus'] ?? 0) }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Team Bonus -->
    <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-6 col-sm-12">
        <div class="card border-0 shadow-sm commission-card" style="border-left: 4px solid #f59e0b !important;">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <div class="avatar-sm bg-warning-subtle rounded">
                            <i class="ri-team-line fs-18 text-warning"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1 fw-medium" style="font-size: 11px;">TEAM BONUS</p>
                        <h6 class="mb-0 text-warning fw-semibold" id="team-bonus">{{ formatCurrency($bonusStats['team_bonus'] ?? 0) }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Rank Bonus -->
    <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-6 col-sm-12">
        <div class="card border-0 shadow-sm commission-card" style="border-left: 4px solid #ef4444 !important;">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <div class="avatar-sm bg-danger-subtle rounded">
                            <i class="ri-medal-line fs-18 text-danger"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1 fw-medium" style="font-size: 11px;">RANK BONUS</p>
                        <h6 class="mb-0 text-danger fw-semibold" id="rank_bonus">{{ formatCurrency($bonusStats['rank_bonus'] ?? 0) }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Link Share Bonus -->
    <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-6 col-sm-12">
        <div class="card border-0 shadow-sm commission-card" style="border-left: 4px solid #10b981 !important;">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <div class="avatar-sm bg-success-subtle rounded">
                            <i class="ri-share-line fs-18 text-success"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1 fw-medium" style="font-size: 11px;">LINK SHARE</p>
                        <h6 class="mb-0 text-success fw-semibold" id="link-share-bonus">{{ formatCurrency($bonusStats['link_share_bonus'] ?? 0) }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Rank Salary -->
    <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-6 col-sm-12">
        <div class="card border-0 shadow-sm commission-card" style="border-left: 4px solid #6b7280 !important;">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <div class="avatar-sm bg-secondary-subtle rounded">
                            <i class="ri-money-dollar-box-line fs-18 text-secondary"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1 fw-medium" style="font-size: 11px;">RANK SALARY</p>
                        <h6 class="mb-0 text-secondary fw-semibold" id="rank-salary">{{ formatCurrency($bonusStats['rank_salary'] ?? 0) }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @if(($bonusStats['cash_back'] ?? 0) > 0)
    <!-- Cashback -->
    <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-6 col-sm-12">
        <div class="card border-0 shadow-sm commission-card" style="border-left: 4px solid #14b8a6 !important;">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <div class="avatar-sm bg-teal-subtle rounded">
                            <i class="ri-refund-2-line fs-18 text-teal"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1 fw-medium" style="font-size: 11px;">CASHBACK</p>
                        <h6 class="mb-0 text-teal fw-semibold" id="cash-back">{{ formatCurrency($bonusStats['cash_back'] ?? 0) }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    @if(($bonusStats['kyc_bonus'] ?? 0) > 0)
    <!-- KYC Bonus -->
    <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-6 col-sm-12">
        <div class="card border-0 shadow-sm commission-card" style="border-left: 4px solid #8b5cf6 !important;">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <div class="avatar-sm bg-violet-subtle rounded">
                            <i class="ri-shield-check-line fs-18 text-violet"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1 fw-medium" style="font-size: 11px;">KYC BONUS</p>
                        <h6 class="mb-0 text-violet fw-semibold" id="kyc-bonus">{{ formatCurrency($bonusStats['kyc_bonus'] ?? 0) }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Transfer and Withdraw Cards -->
<div class="row mt-4">
    <!-- 5. Transfer Funds -->
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card smart-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="mb-2">
                            <span class="d-block fw-medium">Transfer Funds</span>
                        </div>
                        <h4 class="fw-semibold mb-2" id="total-balance">{{ formatCurrency($walletStats['total_balance'] ?? 0) }}</h4>
                        <div>
                            <a href="{{ route('member.transfer') }}" class="btn btn-sm btn-outline-primary">
                                <i class="bx bx-transfer me-1"></i> Transfer
                            </a>
                        </div>
                    </div>
                    <div class="ms-2">
                        <span class="avatar avatar-md bg-primary-transparent">
                            <i class="bx bx-transfer fs-18"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 6. Available for Withdraw -->
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card smart-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="mb-2">
                            <span class="d-block fw-medium">Total Withdrawn</span>
                        </div>
                        <h4 class="fw-semibold mb-2" id="total-withdrawn">{{ formatCurrency($walletStats['withdrawn_amount'] ?? 0) }}</h4>
                        <div>
                            <a href="{{ route('member.withdraw') }}" class="btn btn-sm btn-outline-success">
                                <i class="bx bx-credit-card me-1"></i> Withdraw
                            </a>
                        </div>
                    </div>
                    <div class="ms-2">
                        <span class="avatar avatar-md bg-success-transparent">
                            <i class="bx bx-credit-card fs-18"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Rank -->
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card smart-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="mb-2">
                            <span class="d-block fw-medium">Current Rank</span>
                        </div>
                        <h4 class="fw-semibold mb-2">{{ $affiliateStats['current_rank'] ?? 'Bronze' }}</h4>
                        <div>
                            <a href="{{ route('member.rank') }}" class="btn btn-sm btn-outline-warning">
                                <i class="bx bx-crown me-1"></i> View Details
                            </a>
                        </div>
                    </div>
                    <div class="ms-2">
                        <span class="avatar avatar-md bg-warning-transparent">
                            <i class="bx bx-crown fs-18"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Team Members -->
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card smart-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="mb-2">
                            <span class="d-block fw-medium">Total Team Member</span>
                        </div>
                        <h4 class="fw-semibold mb-2">{{ number_format($affiliateStats['total_downline'] ?? 0) }}</h4>
                        <div>
                            <span class="text-primary me-1"><i class="ri-team-line align-middle"></i>All Levels</span>
                            <span class="text-muted">downline</span>
                        </div>
                    </div>
                    <div class="ms-2">
                        <span class="avatar avatar-md bg-primary-transparent">
                            <i class="bx bx-group fs-18"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- All Income/Bonus Cards Section -->
<div class="row mt-4">
    <!-- Cash Back -->
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card smart-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="mb-2">
                            <span class="d-block fw-medium">Cash Back</span>
                        </div>
                        <h4 class="fw-semibold mb-2" id="cash-back">{{ formatCurrency($bonusStats['cash_back'] ?? 0) }}</h4>
                        <div>
                            <span class="text-warning me-1"><i class="ri-clock-line align-middle"></i>Pending</span>
                            <span class="text-muted" id="pending-cashback-amount">{{ formatCurrency($pendingCashbackAmount ?? 0) }}</span>
                        </div>
                    </div>
                    <div class="ms-2">
                        <span class="avatar avatar-md bg-info-transparent">
                            <i class="bx bx-receipt fs-18"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sponsor Bonus -->
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card smart-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="mb-2">
                            <span class="d-block fw-medium">Sponsor Bonus</span>
                        </div>
                        <h4 class="fw-semibold mb-2" id="sponsor-bonus">{{ formatCurrency($bonusStats['sponsor_bonus'] ?? 0) }}</h4>
                        <div>
                            <span class="text-warning me-1"><i class="ri-user-star-line align-middle"></i>Direct</span>
                            <span class="text-muted">referrals</span>
                        </div>
                    </div>
                    <div class="ms-2">
                        <span class="avatar avatar-md bg-warning-transparent">
                            <i class="bx bx-user-plus fs-18"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Binary Bonus -->
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card smart-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="mb-2">
                            <span class="d-block fw-medium">Binary Bonus</span>
                        </div>
                        <h4 class="fw-semibold mb-2" id="binary-bonus">{{ formatCurrency($bonusStats['binary_bonus'] ?? 0) }}</h4>
                        <div>
                            <span class="text-danger me-1"><i class="ri-git-branch-line align-middle"></i>Binary</span>
                            <span class="text-muted">matching</span>
                        </div>
                    </div>
                    <div class="ms-2">
                        <span class="avatar avatar-md bg-danger-transparent">
                            <i class="bx bx-git-branch fs-18"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Bonus -->
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card smart-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="mb-2">
                            <span class="d-block fw-medium">Team Bonus</span>
                        </div>
                        <h4 class="fw-semibold mb-2" id="team-bonus">{{ formatCurrency($bonusStats['team_bonus'] ?? 0) }}</h4>
                        <div>
                            <span class="text-indigo me-1"><i class="ri-team-line align-middle"></i>Team</span>
                            <span class="text-muted">volume</span>
                        </div>
                    </div>
                    <div class="ms-2">
                        <span class="avatar avatar-md bg-indigo-transparent">
                            <i class="bx bx-group fs-18"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Income Cards -->
<div class="row mt-4">
    <!-- Rank Bonus -->
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card smart-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="mb-2">
                            <span class="d-block fw-medium">Rank Bonus</span>
                        </div>
                        <h4 class="fw-semibold mb-2" id="rank_bonus">{{ formatCurrency($bonusStats['rank_bonus'] ?? 0) }}</h4>
                        <div>
                            <span class="text-warning me-1"><i class="ri-medal-line align-middle"></i>Achievement</span>
                            <span class="text-muted">rewards</span>
                        </div>
                    </div>
                    <div class="ms-2">
                        <span class="avatar avatar-md bg-warning-transparent">
                            <i class="bx bx-medal fs-18"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rank Salary -->
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card smart-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="mb-2">
                            <span class="d-block fw-medium">Rank Salary</span>
                        </div>
                        <h4 class="fw-semibold mb-2" id="rank-salary">{{ formatCurrency($bonusStats['rank_salary'] ?? 0) }}</h4>
                        <div>
                            <span class="text-success me-1"><i class="ri-award-line align-middle"></i>Monthly</span>
                            <span class="text-muted">salary</span>
                        </div>
                    </div>
                    <div class="ms-2">
                        <span class="avatar avatar-md bg-success-transparent">
                            <i class="bx bx-award fs-18"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Link Share Bonus -->
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card smart-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="mb-2">
                            <span class="d-block fw-medium">Link Share Bonus</span>
                        </div>
                        <h4 class="fw-semibold mb-2" id="link-share-bonus">{{ formatCurrency($bonusStats['link_share_bonus'] ?? 0) }}</h4>
                        <div>
                            <span class="text-warning me-1"><i class="ri-share-line align-middle"></i>৳2 per</span>
                            <span class="text-muted">link share</span>
                        </div>
                    </div>
                    <div class="ms-2">
                        <span class="avatar avatar-md bg-warning-transparent">
                            <i class="bx bx-share-alt fs-18"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KYC Bonus -->
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12">
        <div class="card smart-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="mb-2">
                            <span class="d-block fw-medium">KYC Bonus</span>
                        </div>
                        <h4 class="fw-semibold mb-2" id="kyc-bonus">{{ formatCurrency($bonusStats['kyc_bonus'] ?? 0) }}</h4>
                        <div>
                            <span class="text-info me-1"><i class="ri-verified-badge-line align-middle"></i>Verification</span>
                            <span class="text-muted">reward</span>
                        </div>
                    </div>
                    <div class="ms-2">
                        <span class="avatar avatar-md bg-info-transparent">
                            <i class="bx bx-badge-check fs-18"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities Section -->
<div class="row mt-4">
    <!-- Recent Commissions -->
    <div class="col-xl-8 col-lg-8">
        <div class="card smart-card">
            <div class="card-header justify-content-between">
                <div class="card-title">
                    Recent Commissions
                </div>
                <div class="dropdown">
                    <a href="{{ route('member.commissions') }}" class="btn btn-outline-light btn-icons btn-sm">
                        View All <i class="ri-arrow-right-s-line ms-1"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($recentCommissions->count() > 0)
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Level</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentCommissions as $commission)
                                <tr>
                                    <td>
                                        <span class="fw-medium">{{ $commission->created_at->format('M d, Y') }}</span>
                                        <br>
                                        <span class="text-muted fs-12">{{ $commission->created_at->format('h:i A') }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = 'bg-primary-transparent';
                                            $displayType = ucfirst(str_replace('_', ' ', $commission->commission_type));
                                            
                                            switch($commission->commission_type) {
                                                case 'direct':
                                                    $badgeClass = 'bg-success-transparent';
                                                    $displayType = 'Direct Referral';
                                                    break;
                                                case 'matching_bonus':
                                                    $badgeClass = 'bg-info-transparent';
                                                    $displayType = 'Binary Matching';
                                                    break;
                                                case 'tier_bonus':
                                                    $badgeClass = 'bg-warning-transparent';
                                                    $displayType = 'Level Bonus';
                                                    break;
                                                case 'binary':
                                                    $badgeClass = 'bg-purple-transparent';
                                                    $displayType = 'Binary Commission';
                                                    break;
                                            }
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ $displayType }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($commission->commission_type == 'matching_bonus')
                                            <span class="fw-medium">Binary Tree</span>
                                        @else
                                            <span class="fw-medium">Level {{ $commission->level ?? 1 }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-success">
                                            {{ formatCurrency($commission->commission_amount) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($commission->status == 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($commission->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($commission->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bx bx-receipt fs-40 text-muted"></i>
                        <p class="text-muted mt-2">No commissions yet. Start building your network!</p>
                        <a href="{{ route('member.sponsor') }}" class="btn btn-primary btn-sm">
                            <i class="bx bx-user-plus me-1"></i> Invite Members
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions & Team Summary -->
    <div class="col-xl-4 col-lg-4">
        <!-- Quick Actions -->
        <div class="card smart-card mb-3">
            <div class="card-header">
                <div class="card-title">Quick Actions</div>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <!-- Priority Actions First -->
                    <a href="{{ route('member.add-fund') }}" class="btn btn-success btn-sm">
                        <i class="bx bx-plus-circle me-2"></i> Add Fund
                    </a>
                    <a href="{{ route('member.direct-point-purchase.index') }}" class="btn btn-primary btn-sm">
                        <i class="bx bx-coin me-2"></i> Point Purchase
                    </a>
                    <a href="{{ route('member.packages.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-package me-2"></i> Activate Package
                    </a>
                    <a href="{{ route('member.link-sharing.dashboard') }}" class="btn btn-warning btn-sm">
                        <i class="bx bx-share-alt me-2"></i> Link Sharing
                    </a>
                    
                    <!-- Order Actions -->
                    <a href="{{ route('member.orders.create') }}" class="btn btn-primary btn-sm">
                        <i class="bx bx-plus-circle me-2"></i> Create New Order
                    </a>
                    <a href="{{ route('member.orders.index') }}" class="btn btn-outline-dark btn-sm">
                        <i class="bx bx-list-ul me-2"></i> View Orders
                    </a>
                    
                    <!-- Network Actions -->
                    <a href="{{ route('member.genealogy') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bx bx-network-chart me-2"></i> View Network Tree
                    </a>
                    <a href="{{ route('member.binary') }}" class="btn btn-outline-info btn-sm">
                        <i class="bx bx-git-branch me-2"></i> Binary Structure
                    </a>
                    
                    <!-- Financial Actions -->
                    <a href="{{ route('member.withdraw') }}" class="btn btn-outline-success btn-sm">
                        <i class="bx bx-credit-card me-2"></i> Request Withdrawal
                    </a>
                    <a href="{{ route('member.transfer') }}" class="btn btn-outline-warning btn-sm">
                        <i class="bx bx-transfer me-2"></i> Transfer Funds
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Referrals -->
        <div class="card smart-card">
            <div class="card-header justify-content-between">
                <div class="card-title">Recent Referrals</div>
                <a href="{{ route('member.sponsor') }}" class="btn btn-outline-light btn-icons btn-sm">
                    View All
                </a>
            </div>
            <div class="card-body">
                @if($recentReferrals->count() > 0)
                    @foreach($recentReferrals as $referral)
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <span class="avatar avatar-md bg-primary-transparent">
                                {{ strtoupper(substr($referral->name, 0, 1)) }}
                            </span>
                        </div>
                        <div class="flex-fill">
                            <p class="mb-0 fw-medium">{{ $referral->name }}</p>
                            <span class="text-muted fs-12">{{ $referral->created_at->diffForHumans() }}</span>
                        </div>
                        <div>
                            <span class="badge bg-success-transparent">New</span>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-3">
                        <i class="bx bx-user-plus fs-30 text-muted"></i>
                        <p class="text-muted mt-2 mb-2">No referrals yet</p>
                        <a href="{{ route('member.sponsor') }}" class="btn btn-sm btn-primary">
                            Start Referring
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Binary Progress Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card smart-card">
            <div class="card-header justify-content-between">
                <div class="card-title">Binary Tree Progress</div>
                <a href="{{ route('member.binary') }}" class="btn btn-outline-light btn-icons btn-sm">
                    View Full Tree <i class="ri-arrow-right-s-line ms-1"></i>
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fw-medium">Left Leg Volume</span>
                            <span class="fw-semibold">{{ formatCurrency($affiliateStats['left_volume'] ?? 0) }}</span>
                        </div>
                        <div class="progress progress-lg mb-3">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fw-medium">Right Leg Volume</span>
                            <span class="fw-semibold">{{ formatCurrency($affiliateStats['right_volume'] ?? 0) }}</span>
                        </div>
                        <div class="progress progress-lg mb-3">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 45%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <small class="text-muted">
                        Keep your legs balanced for maximum commission potential
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
                                </div>
            </div>
        </div>
    </div>
</div>



<style>
.bg-primary-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.rank-badge {
    text-align: center;
}

.progress-lg {
    height: 12px;
}

.avatar {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

.card-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.smart-card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
    border-radius: 0.375rem;
}

.text-white-50 {
    color: rgba(255, 255, 255, 0.5) !important;
}

/* Quick Actions Card - Force white text visibility */
.quick-actions-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
}

.quick-actions-card * {
    color: white !important;
}

.quick-actions-card small {
    color: rgba(255, 255, 255, 0.8) !important;
}

.quick-actions-card h5 {
    color: white !important;
}

/* Additional color variants for new cards */
.bg-indigo-transparent {
    background-color: rgba(102, 126, 234, 0.1);
    color: #667eea;
}

.bg-purple-transparent {
    background-color: rgba(155, 81, 224, 0.1);
    color: #9b51e0;
}

.bg-dark-transparent {
    background-color: rgba(33, 37, 41, 0.1);
    color: #212529;
}

.text-indigo {
    color: #667eea !important;
}

.text-purple {
    color: #9b51e0 !important;
}

.text-dark {
    color: #212529 !important;
}
</style>
            </div>
        </div>
    </div>
</div>
<!-- End:: Row-1 -->



<!-- End:: Row-4 -->
@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Real-time wallet balance updates
function updateWalletBalances() {
    fetch('{{ route("member.wallet.balance") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update wallet balance cards
                updateCardValue('deposit_wallet', data.formatted.deposit_wallet);
                updateCardValue('income_wallet', data.formatted.interest_wallet);
                updateCardValue('income_wallet', data.formatted.interest_wallet); // Also update income_wallet ID
                updateCardValue('reserve_points', data.formatted.reserve_points);
                updateCardValue('available_balance', data.formatted.available_balance);
                updateCardValue('total-balance', data.formatted.total_balance);
                updateCardValue('total-withdrawn', data.formatted.withdrawn_amount);
                
                // Update bonus cards - Remove conditional checks, always update
                updateCardValue('sponsor-bonus', data.formatted.sponsor_bonus);
                updateCardValue('binary-bonus', data.formatted.binary_bonus);
                updateCardValue('team-bonus', data.formatted.team_bonus);
                updateCardValue('rank_bonus', data.formatted.rank_bonus);
                updateCardValue('link-share-bonus', data.formatted.link_share_bonus);
                updateCardValue('rank-salary', data.formatted.rank_salary);
                updateCardValue('cash-back', data.formatted.cash_back);
                updateCardValue('kyc-bonus', data.formatted.kyc_bonus);
                
                // Update pending cashback amount
                if (typeof data.formatted.pending_cashback_amount !== 'undefined') {
                    const element = document.getElementById('pending-cashback-amount');
                    if (element) {
                        element.textContent = data.formatted.pending_cashback_amount;
                    }
                }
                
                // Update timestamp and show success
                const now = new Date().toLocaleTimeString();
                console.log('✅ Wallet balances updated successfully at:', now);
                
                // Show visual feedback (optional)
                showUpdateNotification('success');
            } else {
                console.error('❌ API returned error:', data.message);
                showUpdateNotification('error');
            }
        })
        .catch(error => {
            console.error('❌ Error updating wallet balances:', error);
            showUpdateNotification('error');
        });
}

function updateCardValue(cardId, value) {
    const element = document.getElementById(cardId);
    if (element) {
        // Add animation class for visual feedback
        element.style.transition = 'color 0.3s ease';
        element.style.color = '#10b981'; // Green color for update
        element.textContent = value;
        
        // Reset color after animation
        setTimeout(() => {
            element.style.color = '';
        }, 1000);
    } else {
        console.warn('⚠️ Element not found:', cardId);
    }
}

function showUpdateNotification(type) {
    // Optional: Show a small notification
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 12px;
        z-index: 9999;
        transition: opacity 0.3s ease;
        ${type === 'success' ? 'background: #10b981; color: white;' : 'background: #ef4444; color: white;'}
    `;
    notification.textContent = type === 'success' ? '✅ Updated' : '❌ Update failed';
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => document.body.removeChild(notification), 300);
    }, 2000);
}

// Auto-update every 30 seconds
setInterval(updateWalletBalances, 30000);

// Update on page load
document.addEventListener('DOMContentLoaded', function() {
    // Initial update after 2 seconds
    setTimeout(updateWalletBalances, 2000);
});

// Toggle income details function
function toggleIncomeDetails() {
    const incomeDetails = document.getElementById('incomeDetails');
    const card = incomeDetails.closest('.card');
    
    if (incomeDetails.style.display === 'none' || incomeDetails.style.display === '') {
        incomeDetails.style.display = 'block';
        card.style.border = '2px solid #10b981';
        card.style.boxShadow = '0 4px 15px rgba(16, 185, 129, 0.2)';
        
        // Add a small animation
        incomeDetails.style.animation = 'slideDown 0.3s ease-out';
    } else {
        incomeDetails.style.display = 'none';
        card.style.border = '';
        card.style.boxShadow = '';
    }
}

// Add CSS animation for the slide down effect
const style = document.createElement('style');
style.textContent = `
    @keyframes slideDown {
        from {
            opacity: 0;
            max-height: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            max-height: 500px;
            transform: translateY(0);
        }
    }
    
    .card:hover {
        transform: translateY(-2px);
        transition: transform 0.2s ease;
    }
    
    .income-card-expanded {
        border: 2px solid #10b981 !important;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2) !important;
    }
`;
document.head.appendChild(style);

// Auto-hide welcome message after 8 seconds
document.addEventListener('DOMContentLoaded', function() {
    const welcomeAlert = document.querySelector('.alert-success');
    if (welcomeAlert && welcomeAlert.textContent.includes('Success!')) {
        setTimeout(function() {
            if (welcomeAlert && !welcomeAlert.classList.contains('d-none')) {
                const bsAlert = new bootstrap.Alert(welcomeAlert);
                bsAlert.close();
            }
        }, 8000); // 8 seconds
    }
});
</script>
@endpush
