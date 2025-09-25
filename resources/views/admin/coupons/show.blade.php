@extends('admin.layouts.app')

@section('title', 'Coupon Details')

@push('styles')
<style>
    .coupon-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 30px;
        margin-bottom: 30px;
    }
    .coupon-code {
        font-size: 2rem;
        font-weight: bold;
        letter-spacing: 2px;
        background: rgba(255, 255, 255, 0.1);
        padding: 10px 20px;
        border-radius: 8px;
        display: inline-block;
        border: 2px dashed rgba(255, 255, 255, 0.3);
    }
    .stat-card {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-left: 4px solid #667eea;
        margin-bottom: 20px;
    }
    .stat-number {
        font-size: 24px;
        font-weight: bold;
        color: #667eea;
        margin-bottom: 5px;
    }
    .stat-label {
        color: #6c757d;
        font-size: 14px;
    }
    .info-card {
        background: #fff;
        border-radius: 8px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #f8f9fa;
    }
    .info-row:last-child {
        border-bottom: none;
    }
    .info-label {
        font-weight: 600;
        color: #495057;
    }
    .info-value {
        color: #6c757d;
    }
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-active { background: #d4edda; color: #155724; }
    .status-expired { background: #f8d7da; color: #721c24; }
    .status-scheduled { background: #d1ecf1; color: #0c5460; }
    .status-inactive { background: #f6f6f6; color: #6c757d; }
    .usage-table th {
        background: #f8f9fa;
        border: none;
        font-weight: 600;
    }
    .chart-container {
        position: relative;
        height: 300px;
        margin-top: 20px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <a href="{{ route('admin.coupons.index') }}" class="text-decoration-none">
                <i class="fas fa-tags"></i> Coupons
            </a>
            <span class="text-muted">/</span> Details
        </h1>
        <div class="d-sm-flex">
            @if(auth()->check() && (auth()->user()->role === 'admin' || $coupon->vendor_id === auth()->id()))
                <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-primary mr-2">
                    <i class="fas fa-edit"></i> Edit Coupon
                </a>
                <button type="button" class="btn btn-{{ $coupon->is_active ? 'warning' : 'success' }}" 
                        onclick="toggleStatus({{ $coupon->id }})">
                    <i class="fas fa-{{ $coupon->is_active ? 'pause' : 'play' }}"></i> 
                    {{ $coupon->is_active ? 'Deactivate' : 'Activate' }}
                </button>
            @endif
        </div>
    </div>

    <!-- Coupon Header -->
    <div class="coupon-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="coupon-code">{{ $coupon->code }}</div>
                <h3 class="mt-3 mb-1">{{ $coupon->name }}</h3>
                @if($coupon->description)
                    <p class="mb-0 opacity-75">{{ $coupon->description }}</p>
                @endif
            </div>
            <div class="col-md-4 text-end">
                <div class="mb-2">
                    <span class="status-badge status-{{ $coupon->status_slug }}">
                        {{ $coupon->status_name }}
                    </span>
                </div>
                <div class="text-white-50">
                    Created {{ $coupon->created_at->diffForHumans() }}
                    @if($coupon->creator)
                        by {{ $coupon->creator->name }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-number">{{ number_format($usageStats['total_usage']) }}</div>
                <div class="stat-label">Total Uses</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-number">${{ number_format($usageStats['total_discount'], 2) }}</div>
                <div class="stat-label">Total Discount Given</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-number">{{ number_format($usageStats['unique_users']) }}</div>
                <div class="stat-label">Unique Users</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-number">
                    @if($coupon->usage_limit)
                        {{ number_format((($usageStats['total_usage'] / $coupon->usage_limit) * 100), 1) }}%
                    @else
                        ∞
                    @endif
                </div>
                <div class="stat-label">Usage Rate</div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Coupon Details -->
        <div class="col-md-6">
            <div class="info-card">
                <h5 class="mb-3"><i class="fas fa-info-circle"></i> Coupon Details</h5>
                
                <div class="info-row">
                    <span class="info-label">Type:</span>
                    <span class="info-value">{{ $coupon->type_name }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Discount Value:</span>
                    <span class="info-value">{{ $coupon->discount_text }}</span>
                </div>
                
                @if($coupon->minimum_amount)
                <div class="info-row">
                    <span class="info-label">Minimum Amount:</span>
                    <span class="info-value">${{ number_format($coupon->minimum_amount, 2) }}</span>
                </div>
                @endif
                
                @if($coupon->maximum_discount)
                <div class="info-row">
                    <span class="info-label">Maximum Discount:</span>
                    <span class="info-value">${{ number_format($coupon->maximum_discount, 2) }}</span>
                </div>
                @endif
                
                <div class="info-row">
                    <span class="info-label">Usage Limit:</span>
                    <span class="info-value">
                        @if($coupon->usage_limit)
                            {{ number_format($coupon->usage_limit) }} times
                        @else
                            Unlimited
                        @endif
                    </span>
                </div>
                
                @if($coupon->usage_limit_per_user)
                <div class="info-row">
                    <span class="info-label">Per User Limit:</span>
                    <span class="info-value">{{ $coupon->usage_limit_per_user }} time(s)</span>
                </div>
                @endif
                
                <div class="info-row">
                    <span class="info-label">Valid From:</span>
                    <span class="info-value">
                        @if($coupon->start_date)
                            {{ $coupon->start_date->format('M d, Y g:i A') }}
                        @else
                            Immediately
                        @endif
                    </span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Valid Until:</span>
                    <span class="info-value">
                        @if($coupon->end_date)
                            {{ $coupon->end_date->format('M d, Y g:i A') }}
                            <br><small class="text-muted">{{ $coupon->end_date->diffForHumans() }}</small>
                        @else
                            Never expires
                        @endif
                    </span>
                </div>
                
                @if($coupon->vendor)
                <div class="info-row">
                    <span class="info-label">Vendor:</span>
                    <span class="info-value">{{ $coupon->vendor->name }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Settings & Restrictions -->
        <div class="col-md-6">
            <div class="info-card">
                <h5 class="mb-3"><i class="fas fa-cog"></i> Settings & Restrictions</h5>
                
                <div class="info-row">
                    <span class="info-label">Auto Apply:</span>
                    <span class="info-value">
                        @if($coupon->auto_apply)
                            <i class="fas fa-check text-success"></i> Yes
                        @else
                            <i class="fas fa-times text-danger"></i> No
                        @endif
                    </span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Stackable:</span>
                    <span class="info-value">
                        @if($coupon->stackable)
                            <i class="fas fa-check text-success"></i> Yes
                        @else
                            <i class="fas fa-times text-danger"></i> No
                        @endif
                    </span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Free Shipping:</span>
                    <span class="info-value">
                        @if($coupon->free_shipping)
                            <i class="fas fa-check text-success"></i> Yes
                        @else
                            <i class="fas fa-times text-danger"></i> No
                        @endif
                    </span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">First Order Only:</span>
                    <span class="info-value">
                        @if($coupon->first_order_only)
                            <i class="fas fa-check text-success"></i> Yes
                        @else
                            <i class="fas fa-times text-danger"></i> No
                        @endif
                    </span>
                </div>
                
                @if($coupon->applicable_products)
                <div class="info-row">
                    <span class="info-label">Applicable Products:</span>
                    <span class="info-value">{{ count(json_decode($coupon->applicable_products, true)) }} product(s)</span>
                </div>
                @endif
                
                @if($coupon->applicable_categories)
                <div class="info-row">
                    <span class="info-label">Applicable Categories:</span>
                    <span class="info-value">{{ count(json_decode($coupon->applicable_categories, true)) }} category(s)</span>
                </div>
                @endif
                
                @if($coupon->exclude_products)
                <div class="info-row">
                    <span class="info-label">Excluded Products:</span>
                    <span class="info-value">{{ count(json_decode($coupon->exclude_products, true)) }} product(s)</span>
                </div>
                @endif
                
                @if($coupon->exclude_categories)
                <div class="info-row">
                    <span class="info-label">Excluded Categories:</span>
                    <span class="info-value">{{ count(json_decode($coupon->exclude_categories, true)) }} category(s)</span>
                </div>
                @endif
                
                @if($coupon->priority)
                <div class="info-row">
                    <span class="info-label">Priority:</span>
                    <span class="info-value">{{ $coupon->priority }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Usage -->
    @if($usageStats['recent_usage']->count() > 0)
    <div class="info-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0"><i class="fas fa-history"></i> Recent Usage</h5>
            <small class="text-muted">Last {{ $usageStats['recent_usage']->count() }} uses</small>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover usage-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Order</th>
                        <th>Discount Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usageStats['recent_usage'] as $usage)
                    <tr>
                        <td>
                            @if($usage->user)
                                <div>{{ $usage->user->name }}</div>
                                <small class="text-muted">{{ $usage->user->email }}</small>
                            @else
                                <span class="text-muted">Guest</span>
                            @endif
                        </td>
                        <td>
                            @if($usage->order)
                                <a href="{{ route('admin.orders.show', $usage->order->id) }}" class="text-decoration-none">
                                    #{{ $usage->order->order_number }}
                                </a>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            <strong>${{ number_format($usage->discount_amount, 2) }}</strong>
                        </td>
                        <td>
                            {{ $usage->created_at->format('M d, Y g:i A') }}
                            <br><small class="text-muted">{{ $usage->created_at->diffForHumans() }}</small>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Usage Chart -->
    @if($usageStats['usage_by_month']->count() > 0)
    <div class="info-card">
        <h5 class="mb-3"><i class="fas fa-chart-line"></i> Usage Analytics ({{ now()->year }})</h5>
        <div class="chart-container">
            <canvas id="usageChart"></canvas>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    @if($usageStats['usage_by_month']->count() > 0)
    // Usage Chart
    const ctx = document.getElementById('usageChart').getContext('2d');
    const usageData = @json($usageStats['usage_by_month']);
    
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const usageByMonth = new Array(12).fill(0);
    const discountByMonth = new Array(12).fill(0);
    
    usageData.forEach(data => {
        usageByMonth[data.month - 1] = data.usage_count;
        discountByMonth[data.month - 1] = data.total_discount;
    });
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Usage Count',
                data: usageByMonth,
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4
            }, {
                label: 'Total Discount ($)',
                data: discountByMonth,
                borderColor: '#764ba2',
                backgroundColor: 'rgba(118, 75, 162, 0.1)',
                tension: 0.4,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });
    @endif
});

function toggleStatus(id) {
    $.post(`/admin/coupons/${id}/toggle-status`, {
        _token: $('meta[name="csrf-token"]').attr('content')
    })
    .done(function(response) {
        if (response.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: response.message,
                timer: 3000,
                showConfirmButton: false
            });
            setTimeout(() => window.location.reload(), 1000);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: response.message
            });
        }
    })
    .fail(function() {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to update coupon status'
        });
    });
}
</script>
@endpush
