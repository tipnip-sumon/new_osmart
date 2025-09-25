@extends('admin.layouts.app')

@section('title', 'Admin Profile')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Admin Profile</h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Profile</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <div class="row">
            <!-- Profile Information -->
            <div class="col-xl-4">
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <div class="mb-4">
                            <span class="avatar avatar-xxl avatar-rounded bg-primary">
                                <i class="ri-user-line fs-24"></i>
                            </span>
                        </div>
                        <h5 class="fw-semibold mb-1">Admin User</h5>
                        <p class="text-muted mb-3">System Administrator</p>
                        
                        <!-- Admin Status -->
                        <div class="mb-3">
                            <span class="badge bg-success-transparent me-2">Active</span>
                            <span class="badge bg-primary-transparent"><i class="ri-shield-user-line me-1"></i>Super Admin</span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 justify-content-center">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                <i class="ri-edit-line me-1"></i> Edit Profile
                            </button>
                            <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                <i class="ri-lock-line me-1"></i> Change Password
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Contact Information</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted">Email</label>
                            <div>admin@mlmecommerce.com</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Phone</label>
                            <div>+1-555-ADMIN (26346)</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Last Login</label>
                            <div>{{ date('M d, Y h:i A') }}</div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label text-muted">Account Created</label>
                            <div>January 15, 2024</div>
                        </div>
                    </div>
                </div>

                <!-- Security Settings -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Security Settings</div>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="two_factor" checked>
                            <label class="form-check-label" for="two_factor">
                                Two-Factor Authentication
                            </label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="email_notifications" checked>
                            <label class="form-check-label" for="email_notifications">
                                Email Notifications
                            </label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="login_alerts" checked>
                            <label class="form-check-label" for="login_alerts">
                                Login Alerts
                            </label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="system_alerts" checked>
                            <label class="form-check-label" for="system_alerts">
                                System Alerts
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Activity and Settings -->
            <div class="col-xl-8">
                <!-- System Statistics -->
                <div class="row">
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <div class="card custom-card">
                            <div class="card-body text-center">
                                <div class="mb-2">
                                    <span class="avatar avatar-md bg-primary-transparent">
                                        <i class="ri-shopping-cart-line fs-16"></i>
                                    </span>
                                </div>
                                <h4 class="fw-semibold mb-1">{{ rand(500, 2000) }}</h4>
                                <p class="text-muted mb-0">Total Orders</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <div class="card custom-card">
                            <div class="card-body text-center">
                                <div class="mb-2">
                                    <span class="avatar avatar-md bg-success-transparent">
                                        <i class="ri-group-line fs-16"></i>
                                    </span>
                                </div>
                                <h4 class="fw-semibold mb-1">{{ rand(100, 500) }}</h4>
                                <p class="text-muted mb-0">Total Users</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <div class="card custom-card">
                            <div class="card-body text-center">
                                <div class="mb-2">
                                    <span class="avatar avatar-md bg-info-transparent">
                                        <i class="ri-box-line fs-16"></i>
                                    </span>
                                </div>
                                <h4 class="fw-semibold mb-1">{{ rand(50, 200) }}</h4>
                                <p class="text-muted mb-0">Products</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <div class="card custom-card">
                            <div class="card-body text-center">
                                <div class="mb-2">
                                    <span class="avatar avatar-md bg-warning-transparent">
                                        <i class="ri-money-dollar-circle-line fs-16"></i>
                                    </span>
                                </div>
                                <h4 class="fw-semibold mb-1">${{ number_format(rand(10000, 50000), 0) }}</h4>
                                <p class="text-muted mb-0">Revenue</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Recent Activity</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Description</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <span class="avatar avatar-sm bg-primary-transparent">
                                                <i class="ri-user-add-line"></i>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">User Created</div>
                                            <div class="text-muted fs-12">Created new user account for John Smith</div>
                                        </td>
                                        <td>{{ date('M d, Y h:i A', strtotime('-2 hours')) }}</td>
                                        <td><span class="badge bg-success-transparent">Success</span></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="avatar avatar-sm bg-success-transparent">
                                                <i class="ri-shopping-cart-line"></i>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">Order Processed</div>
                                            <div class="text-muted fs-12">Approved order #ORD-2025-001</div>
                                        </td>
                                        <td>{{ date('M d, Y h:i A', strtotime('-4 hours')) }}</td>
                                        <td><span class="badge bg-success-transparent">Success</span></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="avatar avatar-sm bg-info-transparent">
                                                <i class="ri-box-line"></i>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">Product Updated</div>
                                            <div class="text-muted fs-12">Modified Premium Health Supplement details</div>
                                        </td>
                                        <td>{{ date('M d, Y h:i A', strtotime('-6 hours')) }}</td>
                                        <td><span class="badge bg-info-transparent">Info</span></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="avatar avatar-sm bg-warning-transparent">
                                                <i class="ri-settings-line"></i>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">System Settings</div>
                                            <div class="text-muted fs-12">Updated payment gateway configuration</div>
                                        </td>
                                        <td>{{ date('M d, Y h:i A', strtotime('-1 day')) }}</td>
                                        <td><span class="badge bg-warning-transparent">Warning</span></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="avatar avatar-sm bg-secondary-transparent">
                                                <i class="ri-shield-check-line"></i>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">Security Update</div>
                                            <div class="text-muted fs-12">Enabled two-factor authentication</div>
                                        </td>
                                        <td>{{ date('M d, Y h:i A', strtotime('-2 days')) }}</td>
                                        <td><span class="badge bg-secondary-transparent">Security</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- System Information -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">System Information</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Platform Version</label>
                                    <div>MLM E-Commerce v2.1.0</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Laravel Version</label>
                                    <div>Laravel 12.x</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">PHP Version</label>
                                    <div>PHP 8.3</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Database</label>
                                    <div>MySQL 8.0</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Server</label>
                                    <div>Apache 2.4</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Environment</label>
                                    <div><span class="badge bg-success-transparent">Production</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="admin_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="admin_name" value="Admin User">
                    </div>
                    <div class="mb-3">
                        <label for="admin_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="admin_email" value="admin@mlmecommerce.com">
                    </div>
                    <div class="mb-3">
                        <label for="admin_phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="admin_phone" value="+1-555-ADMIN">
                    </div>
                    <div class="mb-3">
                        <label for="admin_role" class="form-label">Role</label>
                        <input type="text" class="form-control" id="admin_role" value="System Administrator" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirm_password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Form validation for password change
    document.getElementById('confirm_password').addEventListener('input', function() {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = this.value;
        
        if (newPassword !== confirmPassword) {
            this.setCustomValidity('Passwords do not match');
        } else {
            this.setCustomValidity('');
        }
    });

    // Security settings toggle handler
    document.querySelectorAll('.form-check-input').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const setting = this.id;
            const enabled = this.checked;
            
            // Here you would make an AJAX call to update the setting
            console.log(`${setting} is now ${enabled ? 'enabled' : 'disabled'}`);
            
            // Show success message
            const toast = document.createElement('div');
            toast.className = 'toast-container position-fixed top-0 end-0 p-3';
            toast.innerHTML = `
                <div class="toast show" role="alert">
                    <div class="toast-body">
                        Security setting updated successfully!
                    </div>
                </div>
            `;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 3000);
        });
    });
</script>
@endpush
@endsection
