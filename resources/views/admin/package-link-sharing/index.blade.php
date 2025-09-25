@extends('admin.layouts.app')

@section('title', 'Package Link Sharing Settings')

@section('content')
<div class="main-content">
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h1 class="page-title fw-semibold fs-18 mb-0">Package Link Sharing Settings</h1>
            <div class="">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Package Link Sharing</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="ms-auto pageheader-btn">
            <a href="{{ route('admin.package-link-sharing.create') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> Add New Package Setting
            </a>
            <a href="{{ route('admin.package-link-sharing.statistics') }}" class="btn btn-info">
                <i class="bx bx-bar-chart"></i> View Statistics
            </a>
        </div>
    </div>
    <!-- Page Header Close -->

    <!-- Row -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Package Settings List</div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($settings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Plan & Package</th>
                                        <th>Plan Price</th>
                                        <th>Daily Share Limit</th>
                                        <th>Click Reward (TK)</th>
                                        <th>Daily Earning Limit (TK)</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($settings as $setting)
                                    <tr>
                                        <td>
                                            <div>
                                                <span class="fw-semibold">{{ $setting->display_name }}</span>
                                                @if($setting->plan)
                                                    <br><small class="text-muted">Plan: {{ $setting->plan->name }}</small>
                                                @else
                                                    <br><small class="text-warning">Custom Package</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($setting->plan)
                                                <span class="text-primary fw-semibold">৳ {{ number_format($setting->package_price, 2) }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $setting->daily_share_limit }}</span>
                                        </td>
                                        <td>
                                            <span class="text-success fw-semibold">৳ {{ number_format($setting->click_reward_amount, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="text-info fw-semibold">৳ {{ number_format($setting->daily_earning_limit, 2) }}</span>
                                        </td>
                                        <td>
                                            @if($setting->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.package-link-sharing.show', $setting) }}" 
                                                   class="btn btn-sm btn-info" title="View">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                                <a href="{{ route('admin.package-link-sharing.edit', $setting) }}" 
                                                   class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.package-link-sharing.toggle-active', $setting) }}" 
                                                      method="POST" style="display: inline-block;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-{{ $setting->is_active ? 'secondary' : 'success' }}" 
                                                            title="{{ $setting->is_active ? 'Deactivate' : 'Activate' }}">
                                                        <i class="bx bx-{{ $setting->is_active ? 'pause' : 'play' }}"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.package-link-sharing.destroy', $setting) }}" 
                                                      method="POST" style="display: inline-block;" 
                                                      onsubmit="return confirm('Are you sure you want to delete this package setting?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <span class="text-muted">
                                    Showing {{ $settings->firstItem() }} to {{ $settings->lastItem() }} of {{ $settings->total() }} results
                                </span>
                            </div>
                            <div>
                                {{ $settings->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="bx bx-package" style="font-size: 4rem; color: #ccc;"></i>
                            </div>
                            <h5>No Package Settings Found</h5>
                            <p class="text-muted">Create your first package link sharing setting to get started.</p>
                            <a href="{{ route('admin.package-link-sharing.create') }}" class="btn btn-primary">
                                <i class="bx bx-plus"></i> Create Package Setting
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- End Row -->
</div>
@endsection
