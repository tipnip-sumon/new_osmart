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
