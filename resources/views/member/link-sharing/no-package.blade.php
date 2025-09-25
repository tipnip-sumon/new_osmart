@extends('member.layouts.app')

@section('title', 'Link Sharing - Package Required')

@section('content')
<div class="main-content">
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h1 class="page-title fw-semibold fs-18 mb-0">Link Sharing</h1>
            <div class="">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Link Sharing</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- Page Header Close -->

    <!-- No Package Card -->
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card custom-card">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="bx bx-package" style="font-size: 5rem; color: #fd7e14;"></i>
                    </div>
                    
                    <h3 class="mb-3">Package Activation Required</h3>
                    <p class="text-muted mb-4 fs-15">
                        To start earning through link sharing, you need to activate a package first. 
                        Each package offers different earning limits and rewards per click.
                    </p>

                    <!-- Package Benefits -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center justify-content-center mb-3">
                                <span class="avatar avatar-md bg-success-gradient">
                                    <i class="bx bx-money fs-18"></i>
                                </span>
                            </div>
                            <h5>Earn Per Click</h5>
                            <p class="text-muted">Get rewarded for every unique person who clicks your affiliate links</p>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center justify-content-center mb-3">
                                <span class="avatar avatar-md bg-primary-gradient">
                                    <i class="bx bx-share-alt fs-18"></i>
                                </span>
                            </div>
                            <h5>Daily Sharing</h5>
                            <p class="text-muted">Share multiple product links daily based on your package tier</p>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center justify-content-center mb-3">
                                <span class="avatar avatar-md bg-warning-gradient">
                                    <i class="bx bx-trending-up fs-18"></i>
                                </span>
                            </div>
                            <h5>Higher Packages</h5>
                            <p class="text-muted">Upgrade for higher daily limits and better earning potential</p>
                        </div>
                    </div>

                    <!-- Sample Package Comparison -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3">Package Examples:</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>Package</th>
                                            <th>Daily Shares</th>
                                            <th>Per Click</th>
                                            <th>Max Daily Earning</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>Starter</strong></td>
                                            <td>10 links</td>
                                            <td>2 TK</td>
                                            <td>20 TK</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Silver</strong></td>
                                            <td>25 links</td>
                                            <td>2 TK</td>
                                            <td>50 TK</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Gold</strong></td>
                                            <td>50 links</td>
                                            <td>2 TK</td>
                                            <td>100 TK</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Diamond</strong></td>
                                            <td>100 links</td>
                                            <td>2 TK</td>
                                            <td>200 TK</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <small class="text-muted">* Actual packages may vary. Contact admin for current rates.</small>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('member.packages.index') }}" class="btn btn-primary btn-lg">
                            <i class="bx bx-rocket"></i> Activate Package
                        </a>
                        <a href="{{ route('member.dashboard') }}" class="btn btn-secondary btn-lg">
                            <i class="bx bx-arrow-back"></i> Back to Dashboard
                        </a>
                    </div>

                    <!-- Help Section -->
                    <div class="mt-4 pt-4 border-top">
                        <h6>Need Help?</h6>
                        <p class="text-muted mb-3">
                            If you have questions about packages or link sharing, our support team is here to help.
                        </p>
                        <a href="{{ route('member.support') }}" class="btn btn-outline-info">
                            <i class="bx bx-support"></i> Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
