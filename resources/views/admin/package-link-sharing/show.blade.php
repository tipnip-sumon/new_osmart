@extends('admin.layouts.app')

@section('title', 'Package Link Sharing Details')

@section('content')
<div class="main-content">
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h1 class="page-title fw-semibold fs-18 mb-0">Package: {{ ucfirst($packageLinkSharingSetting->package_name) }}</h1>
            <div class="">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.package-link-sharing.index') }}">Package Link Sharing</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Details</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="ms-auto pageheader-btn">
            <a href="{{ route('admin.package-link-sharing.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Back to List
            </a>
            <a href="{{ route('admin.package-link-sharing.edit', $packageLinkSharingSetting) }}" class="btn btn-warning">
                <i class="bx bx-edit"></i> Edit
            </a>
            <form action="{{ route('admin.package-link-sharing.toggle-active', $packageLinkSharingSetting) }}" 
                  method="POST" style="display: inline-block;">
                @csrf
                <button type="submit" class="btn btn-{{ $packageLinkSharingSetting->is_active ? 'secondary' : 'success' }}">
                    <i class="bx bx-{{ $packageLinkSharingSetting->is_active ? 'pause' : 'play' }}"></i>
                    {{ $packageLinkSharingSetting->is_active ? 'Deactivate' : 'Activate' }}
                </button>
            </form>
        </div>
    </div>
    <!-- Page Header Close -->

    <div class="row">
        <!-- Package Details -->
        <div class="col-xl-8">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Package Configuration</div>
                    <div class="ms-auto">
                        <span class="badge bg-{{ $packageLinkSharingSetting->is_active ? 'success' : 'danger' }} fs-12">
                            {{ $packageLinkSharingSetting->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-muted">Package Name</label>
                                <div class="fs-14 fw-semibold">{{ ucfirst($packageLinkSharingSetting->package_name) }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-muted">Status</label>
                                <div>
                                    <span class="badge bg-{{ $packageLinkSharingSetting->is_active ? 'success' : 'danger' }}">
                                        {{ $packageLinkSharingSetting->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-muted">Daily Share Limit</label>
                                <div class="fs-14 fw-semibold text-primary">{{ $packageLinkSharingSetting->daily_share_limit }} shares</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-muted">Click Reward</label>
                                <div class="fs-14 fw-semibold text-success">৳ {{ number_format($packageLinkSharingSetting->click_reward_amount, 2) }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-muted">Daily Earning Limit</label>
                                <div class="fs-14 fw-semibold text-info">
                                    @if($packageLinkSharingSetting->daily_earning_limit > 0)
                                        ৳ {{ number_format($packageLinkSharingSetting->daily_earning_limit, 2) }}
                                    @else
                                        <span class="text-muted">Unlimited</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-muted">Total Share Limit</label>
                                <div class="fs-14 fw-semibold">
                                    @if($packageLinkSharingSetting->total_share_limit)
                                        {{ number_format($packageLinkSharingSetting->total_share_limit) }} shares
                                    @else
                                        <span class="text-muted">Unlimited</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($packageLinkSharingSetting->conditions)
                    <div class="mt-4">
                        <label class="form-label fw-semibold text-muted">Additional Conditions</label>
                        <div class="bg-light p-3 rounded">
                            <pre class="mb-0 text-dark">{{ json_encode($packageLinkSharingSetting->conditions, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics & Analytics -->
        <div class="col-xl-4">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Package Analytics</div>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="avatar avatar-xl avatar-rounded bg-primary-transparent">
                            <i class="bx bx-package fs-24"></i>
                        </div>
                        <h5 class="mt-3 mb-1">{{ ucfirst($packageLinkSharingSetting->package_name) }}</h5>
                        <p class="text-muted mb-0">Package Link Sharing</p>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="text-center mb-3">
                                <div class="fs-20 fw-semibold text-primary">{{ $packageLinkSharingSetting->daily_share_limit }}</div>
                                <div class="fs-12 text-muted">Daily Shares</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center mb-3">
                                <div class="fs-20 fw-semibold text-success">৳{{ number_format($packageLinkSharingSetting->click_reward_amount, 2) }}</div>
                                <div class="fs-12 text-muted">Per Click</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center mb-3">
                                <div class="fs-20 fw-semibold text-info">
                                    ৳{{ number_format($packageLinkSharingSetting->daily_earning_limit, 2) }}
                                </div>
                                <div class="fs-12 text-muted">Daily Limit</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center mb-3">
                                <div class="fs-20 fw-semibold text-warning">
                                    ৳{{ number_format($packageLinkSharingSetting->daily_share_limit * $packageLinkSharingSetting->click_reward_amount, 2) }}
                                </div>
                                <div class="fs-12 text-muted">Max Daily</div>
                            </div>
                        </div>
                    </div>

                    <div class="border-top pt-3 mt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Created</span>
                            <span class="fw-semibold">{{ $packageLinkSharingSetting->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Last Updated</span>
                            <span class="fw-semibold">{{ $packageLinkSharingSetting->updated_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity (Placeholder) -->
            <div class="card custom-card mt-3">
                <div class="card-header">
                    <div class="card-title">Recent Activity</div>
                </div>
                <div class="card-body">
                    <div class="text-center py-4">
                        <i class="bx bx-chart" style="font-size: 3rem; color: #ccc;"></i>
                        <p class="text-muted mt-2">Activity tracking coming soon...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Usage Guidelines -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Package Configuration Guidelines</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-semibold mb-3"><i class="bx bx-info-circle text-info"></i> Best Practices</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="bx bx-check text-success"></i> Set realistic daily share limits based on package tier</li>
                                <li class="mb-2"><i class="bx bx-check text-success"></i> Configure earning limits to prevent abuse</li>
                                <li class="mb-2"><i class="bx bx-check text-success"></i> Use conditions for advanced package requirements</li>
                                <li class="mb-2"><i class="bx bx-check text-success"></i> Monitor performance and adjust as needed</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-semibold mb-3"><i class="bx bx-cog text-warning"></i> Current Configuration Impact</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <strong>Maximum daily earning potential:</strong> 
                                    ৳{{ number_format($packageLinkSharingSetting->daily_share_limit * $packageLinkSharingSetting->click_reward_amount, 2) }}
                                </li>
                                <li class="mb-2">
                                    <strong>Actual daily limit:</strong> 
                                    ৳{{ number_format($packageLinkSharingSetting->daily_earning_limit, 2) }}
                                </li>
                                <li class="mb-2">
                                    <strong>Clicks needed for limit:</strong> 
                                    {{ ceil($packageLinkSharingSetting->daily_earning_limit / $packageLinkSharingSetting->click_reward_amount) }} clicks
                                </li>
                                <li class="mb-2">
                                    <strong>Efficiency ratio:</strong> 
                                    {{ number_format(($packageLinkSharingSetting->daily_earning_limit / ($packageLinkSharingSetting->daily_share_limit * $packageLinkSharingSetting->click_reward_amount)) * 100, 1) }}%
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
