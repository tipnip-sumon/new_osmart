@extends('admin.layouts.app')

@section('title', 'Mini Vendor Details')

@section('page-header')
<h3 class="page-title">Mini Vendor Details</h3>
<ul class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('vendor.mini-vendors.index') }}">Mini Vendors</a></li>
    <li class="breadcrumb-item active">Details</li>
</ul>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Mini Vendor Details</h1>
            <p class="text-muted">View and manage mini vendor information</p>
        </div>
        <a href="{{ route('vendor.mini-vendors.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Mini Vendors
        </a>
    </div>

    <div class="row">
        <!-- Mini Vendor Profile -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Mini Vendor Profile</h6>
                    @if($miniVendor->status === 'active')
                        <span class="badge badge-success">Active</span>
                    @elseif($miniVendor->status === 'inactive')
                        <span class="badge badge-secondary">Inactive</span>
                    @else
                        <span class="badge badge-danger">Suspended</span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img class="img-profile rounded-circle" 
                             src="{{ $miniVendor->affiliate->avatar ? asset('uploads/users/' . $miniVendor->affiliate->avatar) : asset('admin-assets/img/undraw_profile.svg') }}"
                             style="width: 100px; height: 100px;">
                        <h5 class="mt-3 mb-1">{{ $miniVendor->affiliate->name }}</h5>
                        <p class="text-muted">{{ $miniVendor->affiliate->email }}</p>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-6">
                            <p class="mb-1"><strong>Phone:</strong></p>
                            <p class="text-muted">{{ $miniVendor->affiliate->phone ?: 'N/A' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-1"><strong>District:</strong></p>
                            <p class="text-muted">{{ $miniVendor->district ?: 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-6">
                            <p class="mb-1"><strong>Role:</strong></p>
                            <span class="badge badge-info">{{ $miniVendor->affiliate->role }}</span>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-1"><strong>Member Since:</strong></p>
                            <p class="text-muted">{{ $miniVendor->affiliate->created_at->format('M Y') }}</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        @if($miniVendor->status !== 'suspended')
                        <div class="col-6">
                            <form action="{{ route('vendor.mini-vendors.update-status', $miniVendor) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="{{ $miniVendor->status === 'active' ? 'inactive' : 'active' }}">
                                <button type="submit" class="btn btn-{{ $miniVendor->status === 'active' ? 'warning' : 'success' }} btn-sm btn-block">
                                    <i class="fas fa-{{ $miniVendor->status === 'active' ? 'pause' : 'play' }}"></i>
                                    {{ $miniVendor->status === 'active' ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </div>
                        @endif
                        <div class="col-{{ $miniVendor->status !== 'suspended' ? '6' : '12' }}">
                            <form action="{{ route('vendor.mini-vendors.destroy', $miniVendor) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to remove this mini vendor assignment?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-block">
                                    <i class="fas fa-trash"></i> Remove Assignment
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Commission & Statistics -->
        <div class="col-xl-8 col-lg-7">
            <!-- Commission Stats -->
            <div class="row mb-4">
                <div class="col-xl-6 col-md-6 mb-3">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Commission Rate</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $miniVendor->commission_rate }}%</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-percentage fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-md-6 mb-3">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Total Earned Commission</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        ৳{{ number_format($miniVendor->total_earned_commission, 2) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assignment Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Assignment Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Assigned Date:</strong>
                                <p class="text-muted mb-0">{{ $miniVendor->created_at->format('F d, Y \a\t g:i A') }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>Last Updated:</strong>
                                <p class="text-muted mb-0">{{ $miniVendor->updated_at->format('F d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Current Status:</strong>
                                <p class="mb-0">
                                    @if($miniVendor->status === 'active')
                                        <span class="badge badge-success">Active</span>
                                    @elseif($miniVendor->status === 'inactive')
                                        <span class="badge badge-secondary">Inactive</span>
                                    @else
                                        <span class="badge badge-danger">Suspended</span>
                                    @endif
                                </p>
                            </div>
                            <div class="mb-3">
                                <strong>Assignment Duration:</strong>
                                <p class="text-muted mb-0">{{ $miniVendor->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity (placeholder for future implementation) -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Transfer Activity</h6>
                </div>
                <div class="card-body">
                    <div class="text-center py-4">
                        <i class="fas fa-chart-line fa-3x text-gray-300 mb-3"></i>
                        <h5 class="text-gray-600">Transfer History</h5>
                        <p class="text-muted">
                            Transfer activity and commission history will be displayed here once transfers are made to this mini vendor.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.img-profile {
    object-fit: cover;
}
</style>
@endpush