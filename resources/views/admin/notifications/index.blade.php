@extends('admin.layouts.app')

@section('title', 'Notifications')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Notifications</h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Notifications</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Notifications -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            All Notifications
                        </div>
                        <div class="d-flex">
                            <button class="btn btn-primary btn-sm me-2">
                                <i class="ri-check-double-line"></i> Mark All Read
                            </button>
                            <button class="btn btn-secondary btn-sm" type="button" data-bs-toggle="dropdown">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Clear All</a></li>
                                <li><a class="dropdown-item" href="#">Settings</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <!-- New Order Notification -->
                            <div class="list-group-item d-flex align-items-center">
                                <div class="me-3">
                                    <span class="avatar avatar-md bg-primary-transparent">
                                        <i class="ri-shopping-cart-line fs-16"></i>
                                    </span>
                                </div>
                                <div class="flex-fill">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="mb-1">New order placed</h6>
                                            <p class="mb-0 text-muted">Order #ORD-2025-001 has been placed by John Smith</p>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-primary-transparent">New</span>
                                            <div class="text-muted fs-12 mt-1">2 hours ago</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Low Stock Alert -->
                            <div class="list-group-item d-flex align-items-center">
                                <div class="me-3">
                                    <span class="avatar avatar-md bg-warning-transparent">
                                        <i class="ri-alert-line fs-16"></i>
                                    </span>
                                </div>
                                <div class="flex-fill">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="mb-1">Low stock alert</h6>
                                            <p class="mb-0 text-muted">Fitness Tracker Pro is running low on stock (30 items left)</p>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-warning-transparent">Warning</span>
                                            <div class="text-muted fs-12 mt-1">4 hours ago</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- New User Registration -->
                            <div class="list-group-item d-flex align-items-center">
                                <div class="me-3">
                                    <span class="avatar avatar-md bg-success-transparent">
                                        <i class="ri-user-add-line fs-16"></i>
                                    </span>
                                </div>
                                <div class="flex-fill">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="mb-1">New user registration</h6>
                                            <p class="mb-0 text-muted">Alice Cooper has joined the MLM network</p>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-success-transparent">Info</span>
                                            <div class="text-muted fs-12 mt-1">6 hours ago</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Commission Milestone -->
                            <div class="list-group-item d-flex align-items-center">
                                <div class="me-3">
                                    <span class="avatar avatar-md bg-info-transparent">
                                        <i class="ri-medal-line fs-16"></i>
                                    </span>
                                </div>
                                <div class="flex-fill">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="mb-1">Commission milestone reached</h6>
                                            <p class="mb-0 text-muted">Sarah Johnson has reached $1,000 in total commissions</p>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-info-transparent">Achievement</span>
                                            <div class="text-muted fs-12 mt-1">8 hours ago</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Received -->
                            <div class="list-group-item d-flex align-items-center">
                                <div class="me-3">
                                    <span class="avatar avatar-md bg-success-transparent">
                                        <i class="ri-money-dollar-circle-line fs-16"></i>
                                    </span>
                                </div>
                                <div class="flex-fill">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="mb-1">Payment received</h6>
                                            <p class="mb-0 text-muted">Payment of $249.97 received for order #ORD-2025-001</p>
                                        </div>
                                        <div class="text-end">
                                            <div class="text-muted fs-12 mt-1">1 day ago</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Rank Promotion -->
                            <div class="list-group-item d-flex align-items-center">
                                <div class="me-3">
                                    <span class="avatar avatar-md bg-warning-transparent">
                                        <i class="ri-vip-crown-line fs-16"></i>
                                    </span>
                                </div>
                                <div class="flex-fill">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="mb-1">Rank promotion</h6>
                                            <p class="mb-0 text-muted">Mike Davis has been promoted to Silver rank</p>
                                        </div>
                                        <div class="text-end">
                                            <div class="text-muted fs-12 mt-1">1 day ago</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Review -->
                            <div class="list-group-item d-flex align-items-center">
                                <div class="me-3">
                                    <span class="avatar avatar-md bg-primary-transparent">
                                        <i class="ri-star-line fs-16"></i>
                                    </span>
                                </div>
                                <div class="flex-fill">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="mb-1">New product review</h6>
                                            <p class="mb-0 text-muted">Premium Health Supplement received a 5-star review</p>
                                        </div>
                                        <div class="text-end">
                                            <div class="text-muted fs-12 mt-1">2 days ago</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- System Update -->
                            <div class="list-group-item d-flex align-items-center">
                                <div class="me-3">
                                    <span class="avatar avatar-md bg-secondary-transparent">
                                        <i class="ri-settings-line fs-16"></i>
                                    </span>
                                </div>
                                <div class="flex-fill">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="mb-1">System update completed</h6>
                                            <p class="mb-0 text-muted">MLM compensation plan has been updated successfully</p>
                                        </div>
                                        <div class="text-end">
                                            <div class="text-muted fs-12 mt-1">3 days ago</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Load More -->
                        <div class="card-footer text-center">
                            <button class="btn btn-light btn-sm">
                                <i class="ri-refresh-line me-2"></i>Load More Notifications
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Mark notification as read functionality
    document.addEventListener('DOMContentLoaded', function() {
        const notifications = document.querySelectorAll('.list-group-item');
        
        notifications.forEach(notification => {
            notification.addEventListener('click', function() {
                // Remove new badge if present
                const badge = this.querySelector('.badge');
                if (badge && badge.textContent === 'New') {
                    badge.remove();
                }
                
                // Add read state styling
                this.style.opacity = '0.7';
            });
        });
    });
</script>
@endsection
