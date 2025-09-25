@extends('member.layouts.app')

@section('title', 'Package History')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Package History</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('member.packages.index') }}">Packages</a></li>
                        <li class="breadcrumb-item active" aria-current="page">History</li>
                    </ol>
                </nav>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            Package Transaction History
                        </div>
                        <div>
                            <span class="badge bg-primary-transparent me-2">{{ $packageHistory->count() }} Records</span>
                            <a href="{{ route('member.packages.index') }}" class="btn btn-sm btn-outline-primary">
                                <i class="bx bx-package me-1"></i>
                                My Packages
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($packageHistory->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table text-nowrap table-striped table-bordered">
                                    <thead class="table-primary">
                                        <tr>
                                            <th width="15%">Date</th>
                                            <th width="15%">Action</th>
                                            <th width="15%">Package</th>
                                            <th width="10%">Tier</th>
                                            <th width="12%">Points</th>
                                            <th width="12%">Amount</th>
                                            <th width="10%">Source</th>
                                            <th width="11%">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($packageHistory as $history)
                                        <tr>
                                            <td>
                                                <small>{{ $history->created_at->format('M d, Y') }}</small><br>
                                                <small class="text-muted">{{ $history->created_at->format('H:i A') }}</small>
                                            </td>
                                            <td>
                                                @if($history->action_type == 'purchase')
                                                    <span class="badge bg-success-transparent">
                                                        <i class="bx bx-plus me-1"></i>Purchase
                                                    </span>
                                                @elseif($history->action_type == 'payout')
                                                    <span class="badge bg-warning-transparent">
                                                        <i class="bx bx-minus me-1"></i>Payout
                                                    </span>
                                                @elseif($history->action_type == 'upgrade')
                                                    <span class="badge bg-info-transparent">
                                                        <i class="bx bx-up-arrow me-1"></i>Upgrade
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary-transparent">{{ ucfirst($history->action_type) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $history->plan->name ?? 'Package' }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary-transparent">{{ $history->package_tier }}</span>
                                            </td>
                                            <td>
                                                @if($history->action_type == 'purchase')
                                                    <span class="text-success fw-semibold">
                                                        +{{ number_format($history->points_changed) }}
                                                    </span>
                                                @elseif($history->action_type == 'payout')
                                                    <span class="text-warning fw-semibold">
                                                        {{ number_format($history->points_changed) }}
                                                    </span>
                                                @else
                                                    <span class="text-info fw-semibold">
                                                        {{ $history->points_changed > 0 ? '+' : '' }}{{ number_format($history->points_changed) }}
                                                    </span>
                                                @endif
                                                <br>
                                                <small class="text-muted">After: {{ number_format($history->points_after) }}</small>
                                            </td>
                                            <td>
                                                @if($history->amount_involved > 0)
                                                    ৳{{ number_format($history->amount_involved, 2) }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ ucfirst(str_replace('_', ' ', $history->source)) }}</span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#detailsModal{{ $history->id }}">
                                                    <i class="bx bx-info-circle"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Summary Cards -->
                            <div class="row mt-4">
                                <div class="col-md-3">
                                    <div class="card bg-success-transparent border-success">
                                        <div class="card-body text-center">
                                            <h5 class="text-success">{{ $packageHistory->where('action_type', 'purchase')->count() }}</h5>
                                            <p class="text-success mb-0">Total Purchases</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-warning-transparent border-warning">
                                        <div class="card-body text-center">
                                            <h5 class="text-warning">{{ $packageHistory->where('action_type', 'payout')->count() }}</h5>
                                            <p class="text-warning mb-0">Total Payouts</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-primary-transparent border-primary">
                                        <div class="card-body text-center">
                                            <h5 class="text-primary">{{ number_format($packageHistory->where('action_type', 'purchase')->sum('points_changed')) }}</h5>
                                            <p class="text-primary mb-0">Points Earned</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-secondary-transparent border-secondary">
                                        <div class="card-body text-center">
                                            <h5 class="text-secondary">৳{{ number_format($packageHistory->where('action_type', 'purchase')->sum('amount_involved'), 2) }}</h5>
                                            <p class="text-secondary mb-0">Total Invested</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bx bx-history text-muted" style="font-size: 3rem;"></i>
                                <h5 class="text-muted mt-3">No Package History</h5>
                                <p class="text-muted">
                                    You haven't made any package transactions yet. 
                                    Start by purchasing your first package to begin earning.
                                </p>
                                <a href="{{ route('member.packages.index') }}" class="btn btn-primary">
                                    <i class="bx bx-package me-2"></i>
                                    View Available Packages
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detail Modals -->
@foreach($packageHistory as $history)
<div class="modal fade" id="detailsModal{{ $history->id }}" tabindex="-1" aria-labelledby="detailsModalLabel{{ $history->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel{{ $history->id }}">
                    Transaction Details - {{ $history->plan->name ?? 'Package' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6"><strong>Transaction ID:</strong></div>
                    <div class="col-6">#{{ $history->id }}</div>
                    
                    <div class="col-6"><strong>Date & Time:</strong></div>
                    <div class="col-6">{{ $history->created_at->format('M d, Y H:i A') }}</div>
                    
                    <div class="col-6"><strong>Action Type:</strong></div>
                    <div class="col-6">{{ ucfirst($history->action_type) }}</div>
                    
                    <div class="col-6"><strong>Package Tier:</strong></div>
                    <div class="col-6">{{ $history->package_tier }} Points</div>
                    
                    <div class="col-6"><strong>Points Before:</strong></div>
                    <div class="col-6">{{ number_format($history->points_before) }}</div>
                    
                    <div class="col-6"><strong>Points Changed:</strong></div>
                    <div class="col-6">
                        @if($history->points_changed > 0)
                            <span class="text-success">+{{ number_format($history->points_changed) }}</span>
                        @else
                            <span class="text-warning">{{ number_format($history->points_changed) }}</span>
                        @endif
                    </div>
                    
                    <div class="col-6"><strong>Points After:</strong></div>
                    <div class="col-6">{{ number_format($history->points_after) }}</div>
                    
                    @if($history->amount_involved > 0)
                    <div class="col-6"><strong>Amount Involved:</strong></div>
                    <div class="col-6">৳{{ number_format($history->amount_involved, 2) }}</div>
                    @endif
                    
                    <div class="col-6"><strong>Source:</strong></div>
                    <div class="col-6">{{ ucfirst(str_replace('_', ' ', $history->source)) }}</div>
                    
                    @if($history->product_id)
                    <div class="col-6"><strong>Product ID:</strong></div>
                    <div class="col-6">{{ $history->product_id }}</div>
                    @endif
                    
                    @if($history->order_id)
                    <div class="col-6"><strong>Order ID:</strong></div>
                    <div class="col-6">{{ $history->order_id }}</div>
                    @endif
                </div>
                
                @if($history->metadata)
                    @php
                        $metadata = is_string($history->metadata) ? json_decode($history->metadata, true) : $history->metadata;
                    @endphp
                    @if($metadata && is_array($metadata))
                    <hr>
                    <h6>Additional Information:</h6>
                    <div class="row">
                        @foreach($metadata as $key => $value)
                            <div class="col-6"><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong></div>
                            <div class="col-6">{{ $value }}</div>
                        @endforeach
                    </div>
                    @endif
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced table interactions
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8f9fa';
        });
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
    
    // Auto-refresh every 30 seconds (optional)
    // setInterval(() => {
    //     if (confirm('Refresh package history?')) {
    //         location.reload();
    //     }
    // }, 30000);
});
</script>
@endsection
