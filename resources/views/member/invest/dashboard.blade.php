@extends('member.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Investment Statistics Cards -->
    <div class="row mb-4 my-4">
        <div class="col-lg-3 col-md-6 col-12 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="text-white mb-1">{{ formatCurrency($stats['total_invested']) }}</h4>
                            <p class="mb-0">Total Invested</p>
                        </div>
                        <i class="bx bx-chart-line fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="text-white mb-1">{{ formatCurrency($stats['total_returned']) }}</h4>
                            <p class="mb-0">Total Returned</p>
                        </div>
                        <i class="bx bx-money fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="text-white mb-1">{{ $stats['active_investments'] }}</h4>
                            <p class="mb-0">Active Investments</p>
                        </div>
                        <i class="bx bx-time fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="text-white mb-1">{{ $stats['completed_investments'] }}</h4>
                            <p class="mb-0">Completed</p>
                        </div>
                        <i class="bx bx-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Investments -->
        <div class="col-lg-8 col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Recent Investments</h5>
                    <div>
                        <a href="{{ route('member.invest.create') }}" class="btn btn-primary btn-sm">
                            <i class="bx bx-plus"></i> New Investment
                        </a>
                        <a href="{{ route('member.invest.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bx bx-list-ul"></i> View All
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($recentInvestments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Plan</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Progress</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentInvestments as $investment)
                                        <tr>
                                            <td>{{ $investment->trx }}</td>
                                            <td>{{ $investment->plan->name ?? 'N/A' }}</td>
                                            <td>{{ formatCurrency($investment->amount) }}</td>
                                            <td>
                                                @if($investment->isCompleted())
                                                    <span class="badge bg-success">Completed</span>
                                                @elseif($investment->isActive())
                                                    <span class="badge bg-primary">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $progress = $investment->period > 0 ? ($investment->return_rec_time / $investment->period) * 100 : 0;
                                                @endphp
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <small class="text-muted">{{ number_format($progress, 1) }}%</small>
                                            </td>
                                            <td>
                                                <a href="{{ route('member.invest.show', $investment) }}" class="btn btn-sm btn-info">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bx bx-chart-line fa-3x text-muted mb-3"></i>
                            <h6>No Investments Yet</h6>
                            <p class="text-muted">Start your investment journey today!</p>
                            <a href="{{ route('member.invest.create') }}" class="btn btn-primary">
                                <i class="bx bx-plus"></i> Create Investment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Investment Plans -->
        <div class="col-lg-4 col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Available Plans</h5>
                </div>
                <div class="card-body">
                    @if($activePlans->count() > 0)
                        @foreach($activePlans as $plan)
                            <div class="border rounded p-3 mb-3">
                                <h6 class="mb-2">{{ $plan->name }}</h6>
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted">Interest:</small>
                                        <div class="fw-bold text-success">{{ $plan->interest }}%</div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Duration:</small>
                                        <div class="fw-bold">{{ $plan->time }} {{ $plan->time_name }}</div>
                                    </div>
                                </div>
                                <hr class="my-2">
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted">Min:</small>
                                        <div class="fw-bold">{{ formatCurrency($plan->minimum) }}</div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Max:</small>
                                        <div class="fw-bold">{{ formatCurrency($plan->maximum) }}</div>
                                    </div>
                                </div>
                                @if($plan->capital_back)
                                    <div class="mt-2">
                                        <small class="text-success"><i class="bx bx-check"></i> Capital Back</small>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                        <div class="text-center">
                            <a href="{{ route('member.invest.create') }}" class="btn btn-primary btn-sm">
                                <i class="bx bx-plus"></i> Start Investing
                            </a>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="bx bx-package fa-2x text-muted mb-2"></i>
                            <p class="text-muted">No plans available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
