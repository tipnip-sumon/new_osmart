@extends('member.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row my-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Investment Details</h4>
                    <div>
                        <a href="{{ route('member.invest.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-left"></i> Back to Investments
                        </a>
                        <a href="{{ route('member.invest.dashboard') }}" class="btn btn-outline-primary">
                            <i class="bx bx-home"></i> Dashboard
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <!-- Investment Status Alert -->
                            <div class="alert 
                                @if($investment->isCompleted()) alert-success
                                @elseif($investment->isActive()) alert-info
                                @else alert-warning @endif">
                                <div class="d-flex align-items-center">
                                    <i class="bx 
                                        @if($investment->isCompleted()) bx-check-circle
                                        @elseif($investment->isActive()) bx-time-five
                                        @else bx-pause-circle @endif fa-2x me-3"></i>
                                    <div>
                                        <h6 class="mb-1">
                                            @if($investment->isCompleted())
                                                Investment Completed
                                            @elseif($investment->isActive())
                                                Investment Active
                                            @else
                                                Investment Inactive
                                            @endif
                                        </h6>
                                        <p class="mb-0">
                                            @if($investment->isCompleted())
                                                This investment has been completed and all returns have been paid.
                                            @elseif($investment->isActive())
                                                This investment is currently active and generating returns.
                                            @else
                                                This investment is currently inactive.
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Investment Information -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6>Investment Information</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Transaction ID:</strong></td>
                                            <td>{{ $investment->trx }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Plan:</strong></td>
                                            <td>{{ $investment->plan->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Investment Amount:</strong></td>
                                            <td class="text-primary fw-bold">{{ formatCurrency($investment->amount) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Expected Interest:</strong></td>
                                            <td class="text-success fw-bold">{{ formatCurrency($investment->interest) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total Expected:</strong></td>
                                            <td class="text-info fw-bold">{{ formatCurrency($investment->should_pay) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Paid So Far:</strong></td>
                                            <td class="text-success fw-bold">{{ formatCurrency($investment->paid) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Remaining:</strong></td>
                                            <td class="text-warning fw-bold">{{ formatCurrency($investment->remaining_amount) }}</td>
                                        </tr>
                                        @if($investment->token_discount > 0)
                                        <tr>
                                            <td><strong>Token Discount:</strong></td>
                                            <td class="text-success">{{ formatCurrency($investment->token_discount) }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td><strong>Wallet Used:</strong></td>
                                            <td class="text-capitalize">{{ $investment->wallet_type }} Wallet</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6>Status & Timing</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                @if($investment->isCompleted())
                                                    <span class="badge bg-success">Completed</span>
                                                @elseif($investment->isActive())
                                                    <span class="badge bg-primary">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Capital Status:</strong></td>
                                            <td>
                                                @if($investment->capital_status)
                                                    <span class="badge bg-success">Returned</span>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Period:</strong></td>
                                            <td>{{ $investment->period }} {{ $investment->time_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Returns Received:</strong></td>
                                            <td>
                                                <span class="fw-bold">{{ $investment->return_rec_time }}</span> of 
                                                <span class="fw-bold">{{ $investment->period }}</span>
                                                <span class="text-muted">({{ number_format(($investment->return_rec_time / $investment->period) * 100, 1) }}%)</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Created:</strong></td>
                                            <td>{{ $investment->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Next Return:</strong></td>
                                            <td>
                                                @if($investment->isActive() && !$investment->isCompleted())
                                                    <span class="text-info fw-bold">{{ $investment->next_time->format('M d, Y H:i') }}</span>
                                                    <br><small class="text-muted">{{ $investment->next_time->diffForHumans() }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Last Return:</strong></td>
                                            <td>
                                                @if($investment->last_time)
                                                    {{ $investment->last_time->format('M d, Y H:i') }}
                                                    <br><small class="text-muted">{{ $investment->last_time->diffForHumans() }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mb-4">
                                <h6><i class="bx bx-trending-up"></i> Investment Progress</h6>
                                @php
                                    $progressPercentage = $investment->period > 0 ? ($investment->return_rec_time / $investment->period) * 100 : 0;
                                    $progressClass = $progressPercentage == 100 ? 'bg-success' : ($progressPercentage > 50 ? 'bg-info' : 'bg-primary');
                                @endphp
                                <div class="progress mb-2" style="height: 20px;">
                                    <div class="progress-bar {{ $progressClass }}" role="progressbar" style="width: {{ $progressPercentage }}%" 
                                         aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100">
                                        {{ number_format($progressPercentage, 1) }}%
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">
                                        <i class="bx bx-calendar"></i> {{ $investment->return_rec_time }} of {{ $investment->period }} returns completed
                                    </small>
                                    <small class="text-muted">
                                        <i class="bx bx-time"></i> {{ $investment->period - $investment->return_rec_time }} returns remaining
                                    </small>
                                </div>
                            </div>

                            <!-- Payment Progress -->
                            <div class="mb-4">
                                <h6><i class="bx bx-dollar"></i> Payment Progress</h6>
                                @php
                                    $paymentProgress = $investment->should_pay > 0 ? ($investment->paid / $investment->should_pay) * 100 : 0;
                                @endphp
                                <div class="progress mb-2" style="height: 15px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $paymentProgress }}%" 
                                         aria-valuenow="{{ $paymentProgress }}" aria-valuemin="0" aria-valuemax="100">
                                        {{ number_format($paymentProgress, 1) }}%
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <small class="text-success">
                                        <i class="bx bx-check"></i> Paid: {{ formatCurrency($investment->paid) }}
                                    </small>
                                    <small class="text-warning">
                                        <i class="bx bx-time"></i> Remaining: ${{ number_format($investment->remaining_amount, 2) }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <!-- Quick Stats -->
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-title"><i class="bx bx-bar-chart"></i> Quick Stats</h6>
                                    <div class="row text-center">
                                        <div class="col-6 mb-3">
                                            <div class="h4 text-primary">${{ number_format($investment->amount, 2) }}</div>
                                            <small class="text-muted">Invested</small>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <div class="h4 text-success">${{ number_format($investment->paid, 2) }}</div>
                                            <small class="text-muted">Received</small>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <div class="h4 text-warning">${{ number_format($investment->remaining_amount, 2) }}</div>
                                            <small class="text-muted">Remaining</small>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <div class="h4 text-info">{{ number_format($investment->profit_percentage, 1) }}%</div>
                                            <small class="text-muted">Profit Rate</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Plan Details -->
                            @if($investment->plan)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h6 class="card-title"><i class="bx bx-package"></i> Plan Details</h6>
                                    <div class="mb-2">
                                        <strong class="text-primary">{{ $investment->plan->name }}</strong>
                                    </div>
                                    <table class="table table-sm mb-0">
                                        <tr>
                                            <td>Interest Rate:</td>
                                            <td class="text-success fw-bold">{{ $investment->plan->interest }}%</td>
                                        </tr>
                                        <tr>
                                            <td>Duration:</td>
                                            <td class="fw-bold">{{ $investment->plan->time }} {{ $investment->plan->time_name }}</td>
                                        </tr>
                                        <tr>
                                            <td>Min Amount:</td>
                                            <td>${{ number_format($investment->plan->minimum, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Max Amount:</td>
                                            <td>${{ number_format($investment->plan->maximum, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Capital Back:</td>
                                            <td>
                                                @if($investment->plan->capital_back)
                                                    <span class="text-success"><i class="bx bx-check"></i> Yes</span>
                                                @else
                                                    <span class="text-muted"><i class="bx bx-x"></i> No</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                    @if($investment->plan->description)
                                        <hr class="my-2">
                                        <small class="text-muted">{{ $investment->plan->description }}</small>
                                    @endif
                                </div>
                            </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title"><i class="bx bx-cog"></i> Actions</h6>
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('member.invest.create') }}" class="btn btn-primary">
                                            <i class="bx bx-plus"></i> New Investment
                                        </a>
                                        <a href="{{ route('member.invest.index') }}" class="btn btn-outline-secondary">
                                            <i class="bx bx-list-ul"></i> All Investments
                                        </a>
                                        <button type="button" class="btn btn-outline-info" onclick="window.print()">
                                            <i class="bx bx-printer"></i> Print Details
                                        </button>
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
@media print {
    .btn, .card-header .btn, .navbar, .sidebar {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
@endsection
