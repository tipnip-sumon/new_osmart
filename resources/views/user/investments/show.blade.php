@extends('user.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Investment Details</h4>
                    <a href="{{ route('user.investments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Investments
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
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
                                            <td>${{ number_format($investment->amount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Expected Interest:</strong></td>
                                            <td>${{ number_format($investment->interest, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total Expected:</strong></td>
                                            <td>${{ number_format($investment->should_pay, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Paid So Far:</strong></td>
                                            <td>${{ number_format($investment->paid, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Remaining:</strong></td>
                                            <td>${{ number_format($investment->remaining_amount, 2) }}</td>
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
                                            <td>{{ $investment->return_rec_time }}/{{ $investment->period }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Created:</strong></td>
                                            <td>{{ $investment->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Next Return:</strong></td>
                                            <td>
                                                @if($investment->isActive() && !$investment->isCompleted())
                                                    {{ $investment->next_time->format('M d, Y H:i') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Last Return:</strong></td>
                                            <td>
                                                @if($investment->last_time)
                                                    {{ $investment->last_time->format('M d, Y H:i') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mb-4">
                                <h6>Investment Progress</h6>
                                @php
                                    $progressPercentage = $investment->period > 0 ? ($investment->return_rec_time / $investment->period) * 100 : 0;
                                @endphp
                                <div class="progress mb-2">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $progressPercentage }}%" 
                                         aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100">
                                        {{ number_format($progressPercentage, 1) }}%
                                    </div>
                                </div>
                                <small class="text-muted">
                                    {{ $investment->return_rec_time }} of {{ $investment->period }} returns completed
                                </small>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Quick Stats</h6>
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

                            @if($investment->plan)
                            <div class="card mt-3">
                                <div class="card-body">
                                    <h6 class="card-title">Plan Details</h6>
                                    <p><strong>{{ $investment->plan->name }}</strong></p>
                                    <p class="mb-1">Interest: {{ $investment->plan->interest }}%</p>
                                    <p class="mb-1">Duration: {{ $investment->plan->time }} {{ $investment->plan->time_name }}</p>
                                    @if($investment->plan->capital_back)
                                        <p class="mb-0"><small class="text-success"><i class="fas fa-check"></i> Capital Back</small></p>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
