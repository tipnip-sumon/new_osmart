@extends('admin.layouts.app')

@section('title', 'Affiliate Click Details')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h2 class="card-title mb-0">
                                <i class="fas fa-info-circle text-info me-2"></i>
                                Affiliate Click Details
                            </h2>
                            <p class="text-muted mb-0">Detailed information about click ID: {{ $click->id ?? 'N/A' }}</p>
                        </div>
                        <div class="col-auto">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.affiliate-clicks.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Back to List
                                </a>
                                <button type="button" class="btn btn-outline-danger" onclick="deleteClick()">
                                    <i class="fas fa-trash me-1"></i>Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Main Click Information --}}
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-mouse-pointer me-2"></i>Click Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Click ID:</strong></td>
                                    <td>{{ $click->id ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Affiliate:</strong></td>
                                    <td>
                                        @if(isset($click->user))
                                            <a href="{{ route('admin.affiliates.show', $click->user) }}" class="text-decoration-none">
                                                {{ $click->user->name }}
                                            </a>
                                            <br><small class="text-muted">{{ $click->user->email }}</small>
                                        @else
                                            <span class="text-muted">Unknown</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Product:</strong></td>
                                    <td>
                                        @if(isset($click->product))
                                            <a href="#" class="text-decoration-none">
                                                {{ $click->product->name }}
                                            </a>
                                            <br><small class="text-muted">SKU: {{ $click->product->sku ?? 'N/A' }}</small>
                                        @else
                                            <span class="text-muted">Unknown Product</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>IP Address:</strong></td>
                                    <td><code>{{ $click->ip_address ?? 'N/A' }}</code></td>
                                </tr>
                                <tr>
                                    <td><strong>User Agent:</strong></td>
                                    <td>
                                        <small class="text-muted">{{ $click->user_agent ?? 'N/A' }}</small>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Browser:</strong></td>
                                    <td>
                                        <i class="fab fa-{{ strtolower($click->browser_name ?? 'question') }} me-1"></i>
                                        {{ $click->browser_name ?? 'Unknown' }}
                                        @if($click->browser_version)
                                            <small class="text-muted">v{{ $click->browser_version }}</small>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Platform:</strong></td>
                                    <td>
                                        <i class="fab fa-{{ strtolower($click->platform ?? 'desktop') }} me-1"></i>
                                        {{ ucfirst($click->platform ?? 'Unknown') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Country:</strong></td>
                                    <td>
                                        @if($click->country)
                                            <span class="fi fi-{{ strtolower($click->country_code ?? '') }}"></span>
                                            {{ $click->country }}
                                        @else
                                            <span class="text-muted">Unknown</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Referrer:</strong></td>
                                    <td>
                                        @if($click->referrer)
                                            <a href="{{ $click->referrer }}" target="_blank" class="text-decoration-none">
                                                {{ $click->referrer }}
                                                <i class="fas fa-external-link-alt ms-1"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">Direct</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Clicked At:</strong></td>
                                    <td>
                                        {{ $click->clicked_at ? $click->clicked_at->format('M d, Y H:i:s') : 'N/A' }}
                                        @if($click->clicked_at)
                                            <br><small class="text-muted">{{ $click->clicked_at->diffForHumans() }}</small>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Conversion Information --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shopping-cart me-2"></i>Conversion Information
                    </h5>
                </div>
                <div class="card-body">
                    @if($click->converted_at)
                        <div class="alert alert-success">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="alert-heading mb-1">
                                        <i class="fas fa-check-circle me-2"></i>Conversion Successful!
                                    </h6>
                                    <p class="mb-0">This click resulted in a successful conversion.</p>
                                </div>
                                <div class="col-auto">
                                    <div class="text-end">
                                        <div class="h5 mb-0">{{ $click->converted_at->format('M d, Y H:i') }}</div>
                                        <small class="text-muted">{{ $click->converted_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(isset($click->commission))
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h6 class="card-title">Commission Earned</h6>
                                            <h4 class="text-success">${{ number_format($click->commission->commission_amount, 2) }}</h4>
                                            <small class="text-muted">
                                                {{ $click->commission->commission_rate }}% of ${{ number_format($click->commission->order_amount, 2) }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h6 class="card-title">Commission Status</h6>
                                            <span class="badge bg-{{ $click->commission->status == 'approved' ? 'success' : ($click->commission->status == 'pending' ? 'warning' : 'secondary') }} fs-6">
                                                {{ ucfirst($click->commission->status) }}
                                            </span>
                                            @if($click->commission->status == 'approved')
                                                <br><small class="text-success mt-1">Ready for payout</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="alert-heading mb-1">
                                        <i class="fas fa-clock me-2"></i>No Conversion Yet
                                    </h6>
                                    <p class="mb-0">This click has not resulted in a conversion yet.</p>
                                </div>
                                <div class="col-auto">
                                    <div class="text-center">
                                        <div class="h5 mb-0">
                                            {{ $click->clicked_at ? $click->clicked_at->diffForHumans() : 'N/A' }}
                                        </div>
                                        <small class="text-muted">Time since click</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Product Information --}}
            @if(isset($click->product))
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-box me-2"></i>Product Details
                    </h6>
                </div>
                <div class="card-body">
                    @if($click->product->image)
                        <img src="{{ $click->product->image }}" alt="{{ $click->product->name }}" class="img-fluid rounded mb-3">
                    @endif
                    <h6>{{ $click->product->name }}</h6>
                    <p class="text-muted small">{{ $click->product->description ?? 'No description available' }}</p>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <div class="h5 mb-0">${{ number_format($click->product->price ?? 0, 2) }}</div>
                                <small class="text-muted">Price</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="h5 mb-0">{{ $click->product->affiliate_commission_rate ?? 0 }}%</div>
                            <small class="text-muted">Commission</small>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Affiliate Information --}}
            @if(isset($click->user))
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>Affiliate Details
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                        <h6 class="mt-2 mb-0">{{ $click->user->name }}</h6>
                        <small class="text-muted">{{ $click->user->email }}</small>
                    </div>
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="border-end">
                                <div class="h6 mb-0">{{ $click->user->affiliate_clicks_count ?? 0 }}</div>
                                <small class="text-muted">Total Clicks</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-end">
                                <div class="h6 mb-0">{{ $click->user->conversions_count ?? 0 }}</div>
                                <small class="text-muted">Conversions</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="h6 mb-0">${{ number_format($click->user->total_earnings ?? 0, 2) }}</div>
                            <small class="text-muted">Earnings</small>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.affiliates.show', $click->user) }}" class="btn btn-outline-primary btn-sm w-100">
                            <i class="fas fa-eye me-1"></i>View Affiliate Profile
                        </a>
                    </div>
                </div>
            </div>
            @endif

            {{-- Geographic Information --}}
            @if($click->country)
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-globe me-2"></i>Geographic Info
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <span class="fi fi-{{ strtolower($click->country_code ?? '') }}" style="font-size: 3rem;"></span>
                        <h6 class="mt-2">{{ $click->country }}</h6>
                        @if($click->city)
                            <p class="text-muted">{{ $click->city }}</p>
                        @endif
                        @if($click->timezone)
                            <small class="text-muted">Timezone: {{ $click->timezone }}</small>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@6.6.6/css/flag-icons.min.css">
@endpush

@push('scripts')
<script>
function deleteClick() {
    if (confirm('Are you sure you want to delete this click record? This action cannot be undone.')) {
        fetch(`/admin/affiliate-clicks/{{ $click->id ?? 0 }}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route("admin.affiliate-clicks.index") }}';
            } else {
                alert('Error deleting click record');
            }
        })
        .catch(error => {
            alert('Error deleting click record');
        });
    }
}
</script>
@endpush
