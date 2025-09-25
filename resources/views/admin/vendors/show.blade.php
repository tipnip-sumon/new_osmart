@extends('admin.layouts.app')

@section('title', 'Vendor Details')

@push('styles')
<style>
.vendor-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    color: white;
    padding: 2rem;
    margin-bottom: 2rem;
}

.vendor-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    font-weight: 600;
    border: 4px solid rgba(255,255,255,0.3);
}

.vendor-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    border: 1px solid #e0e6ed;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #2563eb;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #64748b;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.info-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid #e0e6ed;
}

.info-card h5 {
    color: #1e293b;
    margin-bottom: 1rem;
    font-weight: 600;
    border-bottom: 2px solid #f1f5f9;
    padding-bottom: 0.5rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f1f5f9;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    color: #64748b;
    font-weight: 500;
}

.info-value {
    color: #1e293b;
    font-weight: 600;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-active {
    background: #dcfce7;
    color: #166534;
}

.status-inactive {
    background: #fee2e2;
    color: #991b1b;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .vendor-header {
        text-align: center;
    }
    
    .vendor-stats {
        grid-template-columns: 1fr;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Vendor Details</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.vendors.index') }}">Vendors</a></li>
                    <li class="breadcrumb-item active">{{ $vendor->name ?? $vendor->shop_name ?? 'Vendor Details' }}</li>
                </ol>
            </nav>
        </div>
        <div class="action-buttons">
            <a href="{{ route('admin.vendors.index') }}" class="btn btn-light">
                <i class="ti ti-arrow-left me-1"></i>Back to Vendors
            </a>
            <a href="{{ route('admin.vendors.edit', $vendor->id) }}" class="btn btn-primary">
                <i class="ti ti-edit me-1"></i>Edit Vendor
            </a>
        </div>
    </div>

    <!-- Vendor Header -->
    <div class="vendor-header">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="vendor-avatar">
                    @if($vendor->avatar)
                        <img src="{{ asset('storage/' . $vendor->avatar) }}" alt="Avatar" class="w-100 h-100 rounded-circle object-fit-cover">
                    @else
                        {{ strtoupper(substr($vendor->name ?? $vendor->shop_name ?? 'V', 0, 1)) }}
                    @endif
                </div>
            </div>
            <div class="col">
                <h2 class="mb-1">{{ $vendor->name ?? $vendor->shop_name ?? 'Unknown Vendor' }}</h2>
                <p class="mb-2 opacity-75">{{ $vendor->email }}</p>
                <div class="d-flex align-items-center gap-3">
                    <span class="status-badge {{ $vendor->status === 'active' ? 'status-active' : ($vendor->status === 'pending' ? 'status-pending' : 'status-inactive') }}">
                        {{ ucfirst($vendor->status ?? 'pending') }}
                    </span>
                    @if($vendor->is_verified_vendor)
                        <span class="badge bg-success">Verified</span>
                    @endif
                    @if($vendor->is_featured)
                        <span class="badge bg-warning">Featured</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Vendor Statistics -->
    <div class="vendor-stats">
        <div class="stat-card">
            <div class="stat-number">{{ $vendor->products()->count() }}</div>
            <div class="stat-label">Total Products</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $vendor->vendorOrders()->count() }}</div>
            <div class="stat-label">Total Orders</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">${{ number_format($vendor->balance ?? 0, 2) }}</div>
            <div class="stat-label">Current Balance</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $vendor->created_at->format('M Y') }}</div>
            <div class="stat-label">Member Since</div>
        </div>
    </div>

    <!-- Vendor Information -->
    <div class="info-grid">
        <!-- Personal Information -->
        <div class="info-card">
            <h5><i class="ti ti-user me-2"></i>Personal Information</h5>
            <div class="info-item">
                <span class="info-label">Full Name</span>
                <span class="info-value">{{ $vendor->name ?? 'Not provided' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Username</span>
                <span class="info-value">{{ $vendor->username ?? 'Not provided' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Email</span>
                <span class="info-value">{{ $vendor->email }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Phone</span>
                <span class="info-value">{{ $vendor->phone ?? 'Not provided' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Date of Birth</span>
                <span class="info-value">{{ $vendor->date_of_birth ? $vendor->date_of_birth->format('M d, Y') : 'Not provided' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Gender</span>
                <span class="info-value">{{ ucfirst($vendor->gender ?? 'Not specified') }}</span>
            </div>
        </div>

        <!-- Shop Information -->
        <div class="info-card">
            <h5><i class="ti ti-building-store me-2"></i>Shop Information</h5>
            <div class="info-item">
                <span class="info-label">Shop Name</span>
                <span class="info-value">{{ $vendor->shop_name ?? 'Not provided' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Shop Description</span>
                <span class="info-value">{{ Str::limit($vendor->shop_description ?? 'Not provided', 50) }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Shop Address</span>
                <span class="info-value">{{ $vendor->shop_address ?? 'Not provided' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Business License</span>
                <span class="info-value">{{ $vendor->business_license ?? 'Not provided' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Tax ID</span>
                <span class="info-value">{{ $vendor->tax_id ?? 'Not provided' }}</span>
            </div>
        </div>

        <!-- Address Information -->
        <div class="info-card">
            <h5><i class="ti ti-map-pin me-2"></i>Address Information</h5>
            <div class="info-item">
                <span class="info-label">Address</span>
                <span class="info-value">{{ $vendor->address ?? 'Not provided' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">City</span>
                <span class="info-value">{{ $vendor->city ?? 'Not provided' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">State</span>
                <span class="info-value">{{ $vendor->state ?? 'Not provided' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Country</span>
                <span class="info-value">{{ $vendor->country ?? 'Not provided' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Postal Code</span>
                <span class="info-value">{{ $vendor->postal_code ?? 'Not provided' }}</span>
            </div>
        </div>

        <!-- Account Information -->
        <div class="info-card">
            <h5><i class="ti ti-settings me-2"></i>Account Information</h5>
            <div class="info-item">
                <span class="info-label">Account Status</span>
                <span class="info-value">
                    <span class="status-badge {{ $vendor->status === 'active' ? 'status-active' : ($vendor->status === 'pending' ? 'status-pending' : 'status-inactive') }}">
                        {{ ucfirst($vendor->status ?? 'pending') }}
                    </span>
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Email Verified</span>
                <span class="info-value">
                    @if($vendor->email_verified_at)
                        <span class="badge bg-success">Verified</span>
                    @else
                        <span class="badge bg-warning">Pending</span>
                    @endif
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Vendor Verified</span>
                <span class="info-value">
                    @if($vendor->is_verified_vendor)
                        <span class="badge bg-success">Verified</span>
                    @else
                        <span class="badge bg-warning">Pending</span>
                    @endif
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Featured Vendor</span>
                <span class="info-value">
                    @if($vendor->is_featured)
                        <span class="badge bg-primary">Yes</span>
                    @else
                        <span class="badge bg-secondary">No</span>
                    @endif
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Registration Date</span>
                <span class="info-value">{{ $vendor->created_at->format('M d, Y h:i A') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Last Updated</span>
                <span class="info-value">{{ $vendor->updated_at->diffForHumans() }}</span>
            </div>
        </div>
    </div>

    <!-- Recent Products -->
    @if($vendor->products()->count() > 0)
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="ti ti-package me-2"></i>Recent Products</h5>
            <a href="{{ route('admin.products.index', ['vendor' => $vendor->id]) }}" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vendor->products()->latest()->limit(10)->get() as $product)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($product->images && count($product->images) > 0)
                                        <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="ti ti-package"></i>
                                        </div>
                                    @endif
                                    <a href="{{ route('admin.products.show', $product->id) }}" class="text-decoration-none">{{ $product->name }}</a>
                                </div>
                            </td>
                            <td>{{ $product->sku }}</td>
                            <td>৳{{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->stock_quantity ?? 0 }}</td>
                            <td>
                                <span class="badge bg-{{ $product->status === 'active' ? 'success' : ($product->status === 'draft' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($product->status ?? 'draft') }}
                                </span>
                            </td>
                            <td>{{ $product->created_at->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
