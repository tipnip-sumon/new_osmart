@extends('member.layouts.app')

@section('title', 'Pending Cashbacks')

@section('styles')
<style>
    .pending-card {
        background: linear-gradient(135deg, #fff7ed 0%, #fed7aa 100%);
        border-radius: 15px;
        border: 1px solid #fdba74;
    }
    
    .requirement-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border-left: 4px solid #e5e7eb;
    }
    
    .requirement-card.completed {
        border-left-color: #10b981;
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    }
    
    .requirement-card.in-progress {
        border-left-color: #f59e0b;
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    }
    
    .requirement-card.not-started {
        border-left-color: #ef4444;
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    }
    
    .progress-bar-container {
        background: #f3f4f6;
        border-radius: 10px;
        height: 8px;
        overflow: hidden;
    }
    
    .progress-bar-fill {
        height: 100%;
        border-radius: 10px;
        transition: width 0.5s ease;
    }
    
    .progress-bar-fill.completed { background: linear-gradient(90deg, #10b981, #34d399); }
    .progress-bar-fill.in-progress { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .progress-bar-fill.not-started { background: linear-gradient(90deg, #ef4444, #f87171); }
    
    .pending-item {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        border-left: 4px solid #f59e0b;
        margin-bottom: 15px;
    }
    
    .pending-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    
    .amount-pending {
        font-size: 1.5rem;
        font-weight: 700;
        color: #d97706;
    }
    
    .total-pending-display {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        color: white;
        border-radius: 15px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .requirement-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: white;
    }
    
    .requirement-icon.completed { background: #10b981; }
    .requirement-icon.in-progress { background: #f59e0b; }
    .requirement-icon.not-started { background: #ef4444; }
    
    .countdown-badge {
        background: #fee2e2;
        color: #dc2626;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .countdown-badge.warning {
        background: #fef3c7;
        color: #d97706;
    }
    
    .countdown-badge.safe {
        background: #dcfce7;
        color: #15803d;
    }
</style>
@endsection

@section('content')
<div class="main-content">
    <div class="container-fluid my-4">
        
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
            <div>
                <nav>
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item">
                            <a href="{{ route('member.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('member.daily-cashback.dashboard') }}">Daily Cashback</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Pending</li>
                    </ol>
                </nav>
                <p class="fw-semibold fs-15 mb-0">Complete referral requirements to unlock your pending cashbacks</p>
            </div>
            <div class="btn-list">
                <a href="{{ route('member.daily-cashback.dashboard') }}" class="btn btn-primary-light btn-wave">
                    <i class="ri-dashboard-line me-1"></i> Dashboard
                </a>
                <a href="{{ route('member.daily-cashback.history') }}" class="btn btn-secondary-light btn-wave">
                    <i class="ri-history-line me-1"></i> History
                </a>
            </div>
        </div>

        <!-- Total Pending Display -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card total-pending-display">
                    <div class="card-body py-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="ri-time-line fs-48"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 text-white-50">Total Pending Cashbacks</h6>
                                        <h2 class="mb-0">৳{{ number_format($totalPendingAmount, 2) }}</h2>
                                        <small class="text-white-75">{{ $pendingCashbacks->total() }} pending payments waiting for release</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <div class="text-white-75">
                                    <i class="ri-information-line me-1"></i>
                                    Complete referral requirements to unlock all pending amounts at once!
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($totalPendingAmount > 0)
            <!-- Referral Requirements Progress -->
            <div class="row mb-4">
                @foreach($referralProgress as $planId => $progress)
                    @php $plan = $pendingCashbacks->where('plan_id', $planId)->first()->plan @endphp
                    <div class="col-xl-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="card-title">
                                        <i class="ri-gift-line me-2"></i>{{ $plan->name }} - Referral Requirements
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        @if(isset($progress['overall']))
                                            <span class="badge {{ $progress['overall']['all_completed'] ? 'bg-success' : 'bg-warning' }}">
                                                {{ number_format($progress['overall']['percentage'], 1) }}% Complete
                                            </span>
                                        @endif
                                        <span class="badge bg-secondary">
                                            ৳{{ number_format($pendingCashbacks->where('plan_id', $planId)->sum('cashback_amount'), 2) }} Pending
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @if(isset($progress['direct_referrals']))
                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <div class="requirement-card {{ $progress['direct_referrals']['completed'] ? 'completed' : 'in-progress' }} p-3">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="requirement-icon {{ $progress['direct_referrals']['completed'] ? 'completed' : 'in-progress' }}">
                                                        <i class="ri-group-line"></i>
                                                    </div>
                                                    <div class="ms-3">
                                                        <h6 class="mb-0">Direct Referrals</h6>
                                                        <small class="text-muted">{{ $progress['direct_referrals']['current'] }} / {{ $progress['direct_referrals']['required'] }}</small>
                                                    </div>
                                                </div>
                                                <div class="progress-bar-container">
                                                    <div class="progress-bar-fill {{ $progress['direct_referrals']['completed'] ? 'completed' : 'in-progress' }}" 
                                                         style="width: {{ $progress['direct_referrals']['percentage'] }}%"></div>
                                                </div>
                                                <div class="text-end mt-1">
                                                    <small class="fw-semibold">{{ number_format($progress['direct_referrals']['percentage'], 1) }}%</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if(isset($progress['team_size']))
                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <div class="requirement-card {{ $progress['team_size']['completed'] ? 'completed' : 'in-progress' }} p-3">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="requirement-icon {{ $progress['team_size']['completed'] ? 'completed' : 'in-progress' }}">
                                                        <i class="ri-team-line"></i>
                                                    </div>
                                                    <div class="ms-3">
                                                        <h6 class="mb-0">Team Size</h6>
                                                        <small class="text-muted">{{ $progress['team_size']['current'] }} / {{ $progress['team_size']['required'] }}</small>
                                                    </div>
                                                </div>
                                                <div class="progress-bar-container">
                                                    <div class="progress-bar-fill {{ $progress['team_size']['completed'] ? 'completed' : 'in-progress' }}" 
                                                         style="width: {{ $progress['team_size']['percentage'] }}%"></div>
                                                </div>
                                                <div class="text-end mt-1">
                                                    <small class="fw-semibold">{{ number_format($progress['team_size']['percentage'], 1) }}%</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if(isset($progress['min_investment']))
                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <div class="requirement-card {{ $progress['min_investment']['completed'] ? 'completed' : 'in-progress' }} p-3">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="requirement-icon {{ $progress['min_investment']['completed'] ? 'completed' : 'in-progress' }}">
                                                        <i class="ri-money-dollar-circle-line"></i>
                                                    </div>
                                                    <div class="ms-3">
                                                        <h6 class="mb-0">Team Investment</h6>
                                                        <small class="text-muted">৳{{ number_format($progress['min_investment']['current'], 0) }} / ৳{{ number_format($progress['min_investment']['required'], 0) }}</small>
                                                    </div>
                                                </div>
                                                <div class="progress-bar-container">
                                                    <div class="progress-bar-fill {{ $progress['min_investment']['completed'] ? 'completed' : 'in-progress' }}" 
                                                         style="width: {{ $progress['min_investment']['percentage'] }}%"></div>
                                                </div>
                                                <div class="text-end mt-1">
                                                    <small class="fw-semibold">{{ number_format($progress['min_investment']['percentage'], 1) }}%</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if(isset($progress['time_limit']))
                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <div class="requirement-card {{ $progress['time_limit']['expired'] ? 'not-started' : 'in-progress' }} p-3">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="requirement-icon {{ $progress['time_limit']['expired'] ? 'not-started' : 'in-progress' }}">
                                                        <i class="ri-timer-line"></i>
                                                    </div>
                                                    <div class="ms-3">
                                                        <h6 class="mb-0">Time Limit</h6>
                                                        <small class="text-muted">{{ $progress['time_limit']['remaining'] }} days remaining</small>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    @if($progress['time_limit']['expired'])
                                                        <span class="countdown-badge">Expired</span>
                                                    @elseif($progress['time_limit']['remaining'] <= 7)
                                                        <span class="countdown-badge">{{ $progress['time_limit']['remaining'] }} days left</span>
                                                    @elseif($progress['time_limit']['remaining'] <= 30)
                                                        <span class="countdown-badge warning">{{ $progress['time_limit']['remaining'] }} days left</span>
                                                    @else
                                                        <span class="countdown-badge safe">{{ $progress['time_limit']['remaining'] }} days left</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                @if(isset($progress['overall']) && $progress['overall']['all_completed'])
                                    <div class="alert alert-success-transparent">
                                        <div class="d-flex align-items-center">
                                            <i class="ri-check-line fs-20 me-2"></i>
                                            <div>
                                                <strong>Congratulations!</strong> You've completed all referral requirements. 
                                                Your pending cashbacks will be released in the next processing cycle (within 24 hours).
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pending Cashbacks List -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="ri-list-check-line me-2"></i>Pending Cashback Details
                                <span class="badge bg-warning-transparent ms-2">{{ $pendingCashbacks->count() }} items</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Desktop View -->
                            <div class="table-responsive d-none d-md-block">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Package</th>
                                            <th>Amount</th>
                                            <th>Days Pending</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pendingCashbacks as $cashback)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-secondary-transparent">
                                                        {{ $cashback->cashback_date->format('M j, Y') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-semibold">{{ $cashback->plan->name }}</span>
                                                        <small class="text-muted">{{ $cashback->plan->daily_cashback_min }}-{{ $cashback->plan->daily_cashback_max }} TK range</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="amount-pending">
                                                        ৳{{ number_format($cashback->cashback_amount, 2) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-warning">
                                                        {{ $cashback->created_at->diffInDays() }} days
                                                    </span>
                                                </td>
                                                <td>
                                                    <small class="text-muted">{{ Str::limit($cashback->remarks, 40) }}</small>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Mobile View -->
                            <div class="d-block d-md-none">
                                @foreach($pendingCashbacks as $cashback)
                                    <div class="pending-item p-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="badge bg-warning">Pending</span>
                                            <div class="text-muted small">{{ $cashback->cashback_date->format('M j') }}</div>
                                        </div>
                                        
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <h6 class="mb-0">{{ $cashback->plan->name }}</h6>
                                            <span class="amount-pending">৳{{ number_format($cashback->cashback_amount, 2) }}</span>
                                        </div>
                                        
                                        <small class="text-muted">{{ Str::limit($cashback->remarks, 60) }}</small>
                                        
                                        <div class="mt-2">
                                            <small class="text-warning">
                                                <i class="ri-time-line"></i> Pending for {{ $cashback->created_at->diffInDays() }} days
                                            </small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $pendingCashbacks->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- No Pending Cashbacks -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <img src="{{ asset('admin-assets/images/media/media-67.svg') }}" alt="" class="w-25">
                            <h5 class="mt-3">No Pending Cashbacks</h5>
                            <p class="text-muted">Great! You don't have any pending cashbacks. All your earnings are up to date.</p>
                            <a href="{{ route('member.daily-cashback.dashboard') }}" class="btn btn-primary">Back to Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Auto refresh every 2 minutes to check for requirement updates
    setTimeout(function() {
        location.reload();
    }, 120000);
    
    // Tooltip initialization
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Progress bar animations
    $('.progress-bar-fill').each(function() {
        var $this = $(this);
        var width = $this.attr('style').match(/width:\s*(\d+(?:\.\d+)?)%/);
        if (width) {
            $this.css('width', '0%');
            setTimeout(function() {
                $this.css('width', width[0]);
            }, 500);
        }
    });
});
</script>
@endsection