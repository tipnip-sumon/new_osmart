@extends('layouts.app')

@section('title', 'Investment Plans - Bangladeshi Currency')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-coins text-success me-2"></i>
                        Investment Plans with BD Currency ({{ getCurrencySymbol() }})
                    </h4>
                    <p class="text-muted mb-0">All amounts displayed in {{ getCurrencyName() }}</p>
                </div>
                <div class="card-body">
                    <!-- Currency Testing Section -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Currency Formatting Examples</h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Standard Format:</small><br>
                                            <strong>{{ formatCurrency(50000) }}</strong><br>
                                            <strong>{{ formatCurrency(250000) }}</strong><br>
                                            <strong>{{ formatCurrency(1500000) }}</strong><br>
                                            <strong>{{ formatCurrency(25000000) }}</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Bangladeshi Style:</small><br>
                                            <strong>{{ formatCurrencyBangladeshi(50000) }}</strong><br>
                                            <strong>{{ formatCurrencyBangladeshi(250000) }}</strong><br>
                                            <strong>{{ formatCurrencyBangladeshi(1500000) }}</strong><br>
                                            <strong>{{ formatCurrencyBangladeshi(25000000) }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h6 class="card-title">Currency Information</h6>
                                    <p class="mb-1"><strong>Symbol:</strong> {{ getCurrencySymbol() }}</p>
                                    <p class="mb-1"><strong>Name:</strong> {{ getCurrencyName() }}</p>
                                    <p class="mb-1"><strong>Code:</strong> {{ config('currency.default_currency') }}</p>
                                    <p class="mb-0"><strong>Country:</strong> {{ config('currency.locale.region') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Plans Display -->
                    @if(isset($plans) && $plans->count() > 0)
                    <div class="row">
                        @foreach($plans as $plan)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 @if($plan->featured) border-primary @endif">
                                @if($plan->featured)
                                <div class="card-header bg-primary text-white text-center">
                                    <i class="fas fa-star me-1"></i> Featured Plan
                                </div>
                                @endif
                                
                                <div class="card-body">
                                    <h5 class="card-title text-center">{{ $plan->name }}</h5>
                                    
                                    @if($plan->isFixedAmount())
                                    <div class="text-center mb-3">
                                        <h3 class="text-primary mb-0">{{ $plan->formatted_fixed_amount }}</h3>
                                        <small class="text-muted">Fixed Investment</small>
                                    </div>
                                    @else
                                    <div class="text-center mb-3">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <strong>{{ $plan->formatted_minimum }}</strong><br>
                                                <small class="text-muted">Minimum</small>
                                            </div>
                                            <div>
                                                <strong>{{ $plan->formatted_maximum }}</strong><br>
                                                <small class="text-muted">Maximum</small>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <div class="row text-center mb-3">
                                        <div class="col-6">
                                            <h4 class="text-success mb-0">{{ $plan->formatted_interest }}</h4>
                                            <small class="text-muted">
                                                @if($plan->isPercentageBased())
                                                    Daily Return
                                                @else
                                                    Fixed Daily
                                                @endif
                                            </small>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="text-info mb-0">{{ $plan->duration }}</h4>
                                            <small class="text-muted">Duration</small>
                                        </div>
                                    </div>
                                    
                                    @if($plan->description)
                                    <p class="card-text">{{ Str::limit($plan->description, 100) }}</p>
                                    @endif
                                    
                                    <div class="mt-3">
                                        <div class="row text-center">
                                            <div class="col-4">
                                                @if($plan->capital_back)
                                                <i class="fas fa-check-circle text-success"></i><br>
                                                <small>Capital Back</small>
                                                @else
                                                <i class="fas fa-times-circle text-danger"></i><br>
                                                <small>No Capital Back</small>
                                                @endif
                                            </div>
                                            <div class="col-4">
                                                @if($plan->lifetime)
                                                <i class="fas fa-infinity text-warning"></i><br>
                                                <small>Lifetime</small>
                                                @else
                                                <i class="fas fa-calendar text-info"></i><br>
                                                <small>Fixed Term</small>
                                                @endif
                                            </div>
                                            <div class="col-4">
                                                @if($plan->status)
                                                <i class="fas fa-play-circle text-success"></i><br>
                                                <small>Active</small>
                                                @else
                                                <i class="fas fa-pause-circle text-secondary"></i><br>
                                                <small>Inactive</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-footer">
                                    <!-- Example calculations with BD currency -->
                                    @if($plan->isFixedAmount())
                                    @php
                                        $exampleAmount = $plan->fixed_amount;
                                        $dailyReturn = $plan->calculateReturn($exampleAmount);
                                        $totalReturn = $plan->getTotalPotentialProfit($exampleAmount);
                                    @endphp
                                    @else
                                    @php
                                        $exampleAmount = ($plan->minimum + $plan->maximum) / 2;
                                        $dailyReturn = $plan->calculateReturn($exampleAmount);
                                        $totalReturn = $plan->getTotalPotentialProfit($exampleAmount);
                                    @endphp
                                    @endif
                                    
                                    <div class="small text-muted">
                                        <strong>Example with {{ formatCurrency($exampleAmount) }}:</strong><br>
                                        Daily: {{ formatCurrency($dailyReturn) }}<br>
                                        @if($totalReturn !== 'Unlimited')
                                            Total Potential: {{ formatCurrency($totalReturn) }}
                                        @else
                                            Total Potential: Unlimited
                                        @endif
                                    </div>
                                    
                                    @if($plan->status)
                                    <button class="btn btn-primary btn-sm w-100 mt-2">Invest Now</button>
                                    @else
                                    <button class="btn btn-secondary btn-sm w-100 mt-2" disabled>Currently Unavailable</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i>
                        No investment plans available at the moment. Please check back later.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.card.border-primary {
    border-width: 2px !important;
}

.text-primary {
    color: #6f42c1 !important;
}

.bg-primary {
    background-color: #6f42c1 !important;
}

.btn-primary {
    background-color: #6f42c1;
    border-color: #6f42c1;
}

.btn-primary:hover {
    background-color: #5a2d91;
    border-color: #5a2d91;
}
</style>
@endsection
