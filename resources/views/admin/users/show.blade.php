@extends('admin.layouts.app')

@section('title', 'User Details - ' . $user['name'])

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">
                <i class="bi bi-person-circle me-2"></i>User Profile: {{ $user['name'] }}
            </h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $user['name'] }}</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Quick Action Buttons -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('admin.users.edit', $user['id']) }}" class="btn btn-primary">
                                    <i class="bi bi-pencil me-2"></i>Edit User
                                </a>
                                <button class="btn btn-outline-success" onclick="toggleUserStatus('{{ $user['id'] }}')">
                                    <i class="bi bi-{{ $user['status'] == 'active' ? 'pause' : 'play' }} me-2"></i>
                                    {{ $user['status'] == 'active' ? 'Suspend' : 'Activate' }}
                                </button>
                                <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#loginAsUserModal">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Login as User
                                </button>
                                <button class="btn btn-outline-warning" onclick="sendWelcomeEmail('{{ $user['id'] }}')">
                                    <i class="bi bi-envelope me-2"></i>Send Email
                                </button>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Back to Users
                                </a>
                                <div class="dropdown">
                                    <button class="btn btn-outline-danger dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="exportUserData('{{ $user['id'] }}')">
                                            <i class="bi bi-download me-2"></i>Export Data
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="resetPassword('{{ $user['id'] }}')">
                                            <i class="bi bi-key me-2"></i>Reset Password
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteUser('{{ $user['id'] }}')">
                                            <i class="bi bi-trash me-2"></i>Delete User
                                        </a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- User Profile Card -->
            <div class="col-xl-4">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="bi bi-person-badge me-2"></i>Profile Information
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-4">
                            <div class="avatar-preview">
                                <img src="{{ $user['avatar'] ?? '/assets/img/default-avatar.svg' }}" class="rounded-circle border" width="120" height="120" alt="{{ $user['name'] }}">
                            </div>
                        </div>
                        
                        <h4 class="fw-semibold mb-1">{{ $user['name'] }}</h4>
                        <p class="text-muted mb-2">{{ $user['email'] }}</p>
                        
                        <div class="d-flex justify-content-center gap-2 mb-3">
                            <span class="badge bg-{{ $user['status'] == 'active' ? 'success' : ($user['status'] == 'suspended' ? 'danger' : 'secondary') }} fs-12">
                                <i class="bi bi-circle-fill me-1"></i>{{ ucfirst($user['status']) }}
                            </span>
                            @php
                                $rankColors = [
                                    'bronze' => 'secondary',
                                    'silver' => 'info', 
                                    'gold' => 'warning',
                                    'platinum' => 'primary',
                                    'diamond' => 'success'
                                ];
                                $rankIcons = [
                                    'bronze' => 'award',
                                    'silver' => 'award-fill',
                                    'gold' => 'trophy',
                                    'platinum' => 'trophy-fill',
                                    'diamond' => 'gem'
                                ];
                            @endphp
                            <span class="badge bg-{{ $rankColors[$user['rank'] ?? 'bronze'] }} fs-12">
                                <i class="bi bi-{{ $rankIcons[$user['rank'] ?? 'bronze'] }} me-1"></i>{{ ucfirst($user['rank'] ?? 'Bronze') }}
                            </span>
                        </div>

                        <div class="row text-center">
                            <div class="col-4">
                                <div class="border-end">
                                    <h5 class="mb-1 text-primary">{{ $user['orders_count'] ?? 0 }}</h5>
                                    <p class="text-muted mb-0 fs-12">Orders</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border-end">
                                    <h5 class="mb-1 text-success">৳{{ number_format($user['total_spent'] ?? 0, 0) }}</h5>
                                    <p class="text-muted mb-0 fs-12">Total Spent</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <h5 class="mb-1 text-warning">{{ number_format($user['pv_points'] ?? 0, 0) }}</h5>
                                <p class="text-muted mb-0 fs-12">PV Points</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="bi bi-telephone me-2"></i>Contact Details
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <div>
                                    <i class="bi bi-envelope text-primary me-2"></i>
                                    <strong>Email:</strong>
                                </div>
                                <div class="text-end">
                                    <div>{{ $user['email'] }}</div>
                                    @if($user['email_verified_at'])
                                        <small class="text-success"><i class="bi bi-check-circle me-1"></i>Verified</small>
                                    @else
                                        <small class="text-warning"><i class="bi bi-exclamation-circle me-1"></i>Not Verified</small>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <div>
                                    <i class="bi bi-phone text-primary me-2"></i>
                                    <strong>Phone:</strong>
                                </div>
                                <span>{{ $user['phone'] ?? 'N/A' }}</span>
                            </div>
                            
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <div>
                                    <i class="bi bi-calendar text-primary me-2"></i>
                                    <strong>Date of Birth:</strong>
                                </div>
                                <span>{{ $user['date_of_birth'] ? \Carbon\Carbon::parse($user['date_of_birth'])->format('M d, Y') : 'N/A' }}</span>
                            </div>
                            
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <div>
                                    <i class="bi bi-gender-ambiguous text-primary me-2"></i>
                                    <strong>Gender:</strong>
                                </div>
                                <span>{{ $user['gender'] ? ucfirst($user['gender']) : 'N/A' }}</span>
                            </div>
                            
                            @if($user['address'])
                            <div class="list-group-item border-0 px-0">
                                <div class="mb-1">
                                    <i class="bi bi-geo-alt text-primary me-2"></i>
                                    <strong>Address:</strong>
                                </div>
                                <div class="text-muted">{{ $user['address'] }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="bi bi-shield-check me-2"></i>Account Information
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <strong>User ID:</strong>
                                <span class="badge bg-light text-dark">{{ $user['id'] }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <strong>Role:</strong>
                                <span class="badge bg-primary">{{ ucfirst($user['role'] ?? 'User') }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <strong>Member Since:</strong>
                                <span>{{ \Carbon\Carbon::parse($user['created_at'])->format('M d, Y') }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <strong>Last Login:</strong>
                                <span>{{ $user['last_login_at'] ? \Carbon\Carbon::parse($user['last_login_at'])->diffForHumans() : 'Never' }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <strong>Profile Updated:</strong>
                                <span>{{ \Carbon\Carbon::parse($user['updated_at'])->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-xl-8">
                <!-- MLM Information -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="bi bi-diagram-3 me-2"></i>MLM Network Information
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3">Network Details</h6>
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item d-flex justify-content-between border-0 px-0">
                                        <strong>Sponsor ID:</strong>
                                        <span>{{ $user['sponsor_id'] ?? 'N/A' }}</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between border-0 px-0">
                                        <strong>Position:</strong>
                                        <span class="badge bg-info">{{ ucfirst($user['position'] ?? 'Auto') }}</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between border-0 px-0">
                                        <strong>Team Size:</strong>
                                        <span class="badge bg-success">{{ $user['team_count'] ?? 0 }}</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between border-0 px-0">
                                        <strong>Generation Level:</strong>
                                        <span>{{ $user['level'] ?? 1 }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-3">Earnings & Points</h6>
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item d-flex justify-content-between border-0 px-0">
                                        <strong>PV Points:</strong>
                                        <span class="text-warning fw-bold">{{ number_format($user['pv_points'] ?? 0, 2) }}</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between border-0 px-0">
                                        <strong>Commission Balance:</strong>
                                        <span class="text-success fw-bold">৳{{ number_format($user['commission_balance'] ?? 0, 2) }}</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between border-0 px-0">
                                        <strong>Wallet Balance:</strong>
                                        <span class="text-primary fw-bold">৳{{ number_format($user['wallet_balance'] ?? 0, 2) }}</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between border-0 px-0">
                                        <strong>Total Earnings:</strong>
                                        <span class="text-info fw-bold">৳{{ number_format($user['total_commission'] ?? 0, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="card custom-card">
                            <div class="card-body text-center">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <div class="avatar avatar-md bg-primary-transparent rounded">
                                        <i class="bi bi-cart3 fs-18"></i>
                                    </div>
                                </div>
                                <h4 class="mb-1">{{ $user['orders_count'] ?? 0 }}</h4>
                                <p class="text-muted mb-0 fs-12">Total Orders</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card custom-card">
                            <div class="card-body text-center">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <div class="avatar avatar-md bg-success-transparent rounded">
                                        <i class="bi bi-people fs-18"></i>
                                    </div>
                                </div>
                                <h4 class="mb-1">{{ $user['referrals_count'] ?? 0 }}</h4>
                                <p class="text-muted mb-0 fs-12">Direct Referrals</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card custom-card">
                            <div class="card-body text-center">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <div class="avatar avatar-md bg-warning-transparent rounded">
                                        <i class="bi bi-trophy fs-18"></i>
                                    </div>
                                </div>
                                <h4 class="mb-1">{{ number_format($user['pv_points'] ?? 0, 0) }}</h4>
                                <p class="text-muted mb-0 fs-12">PV Points</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card custom-card">
                            <div class="card-body text-center">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <div class="avatar avatar-md bg-info-transparent rounded">
                                        <i class="bi bi-wallet2 fs-18"></i>
                                    </div>
                                </div>
                                <h4 class="mb-1">৳{{ number_format(($user['commission_balance'] ?? 0) + ($user['wallet_balance'] ?? 0), 0) }}</h4>
                                <p class="text-muted mb-0 fs-12">Total Balance</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="card-title">
                            <i class="bi bi-bag-check me-2"></i>Recent Orders
                        </div>
                        <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>PV Earned</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($user['recent_orders']) && count($user['recent_orders']) > 0)
                                        @foreach($user['recent_orders'] as $order)
                                        <tr>
                                            <td>#{{ $order['id'] }}</td>
                                            <td>{{ \Carbon\Carbon::parse($order['created_at'])->format('M d, Y') }}</td>
                                            <td>৳{{ number_format($order['total'], 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $order['status'] == 'completed' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($order['status']) }}
                                                </span>
                                            </td>
                                            <td>{{ $order['pv_earned'] ?? 0 }}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No orders found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Commission History -->
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="card-title">
                            <i class="bi bi-graph-up me-2"></i>Commission History
                        </div>
                        <a href="#" class="btn btn-sm btn-outline-success">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>From</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($user['commission_history']) && count($user['commission_history']) > 0)
                                        @foreach($user['commission_history'] as $commission)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($commission['created_at'])->format('M d, Y') }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ ucfirst($commission['type']) }}</span>
                                            </td>
                                            <td>{{ $commission['from_user'] ?? 'System' }}</td>
                                            <td class="text-success">৳{{ number_format($commission['amount'], 2) }}</td>
                                            <td>
                                                <span class="badge bg-success">{{ ucfirst($commission['status']) }}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No commission history found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Team Members -->
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="card-title">
                            <i class="bi bi-people me-2"></i>Direct Team Members
                        </div>
                        <a href="#" class="btn btn-sm btn-outline-info">View Network Tree</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Join Date</th>
                                        <th>Rank</th>
                                        <th>PV Points</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($user['team_members']) && count($user['team_members']) > 0)
                                        @foreach($user['team_members'] as $member)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $member['avatar'] ?? '/assets/img/default-avatar.svg' }}" class="rounded-circle me-2" width="30" height="30">
                                                    {{ $member['name'] }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $member['position'] == 'left' ? 'primary' : 'secondary' }}">
                                                    {{ ucfirst($member['position']) }}
                                                </span>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($member['created_at'])->format('M d, Y') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $rankColors[$member['rank'] ?? 'bronze'] }}">
                                                    {{ ucfirst($member['rank'] ?? 'Bronze') }}
                                                </span>
                                            </td>
                                            <td>{{ number_format($member['pv_points'] ?? 0, 0) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $member['status'] == 'active' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($member['status']) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No team members found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Login As User Modal -->
    <div class="modal fade" id="loginAsUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login as {{ $user['name'] }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> You are about to login as this user. This action will be logged for security purposes.
                    </div>
                    <p>Are you sure you want to login as <strong>{{ $user['name'] }}</strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="loginAsUser('{{ $user['id'] }}')">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login as User
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<script>
function toggleUserStatus(userId) {
    if (confirm('Are you sure you want to change this user\'s status?')) {
        // AJAX call to toggle status
        $.ajax({
            url: `/admin/users/${userId}/toggle-status`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred while updating user status');
            }
        });
    }
}

function loginAsUser(userId) {
    // AJAX call to login as user
    $.ajax({
        url: `/admin/users/${userId}/login-as`,
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                window.open(response.redirect_url, '_blank');
                $('#loginAsUserModal').modal('hide');
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('An error occurred while trying to login as user');
        }
    });
}

function sendWelcomeEmail(userId) {
    if (confirm('Send welcome email to this user?')) {
        $.ajax({
            url: `/admin/users/${userId}/send-welcome-email`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert(response.message);
            },
            error: function() {
                alert('An error occurred while sending email');
            }
        });
    }
}

function resetPassword(userId) {
    if (confirm('Reset password for this user? A new password will be sent via email.')) {
        $.ajax({
            url: `/admin/users/${userId}/reset-password`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert(response.message);
            },
            error: function() {
                alert('An error occurred while resetting password');
            }
        });
    }
}

function exportUserData(userId) {
    window.open(`/admin/users/${userId}/export`, '_blank');
}

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        if (confirm('This will permanently delete all user data. Are you absolutely sure?')) {
            $.ajax({
                url: `/admin/users/${userId}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        window.location.href = '{{ route("admin.users.index") }}';
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while deleting user');
                }
            });
        }
    }
}

// Auto-refresh data every 30 seconds
setInterval(function() {
    // You can add AJAX calls here to refresh specific sections
}, 30000);
</script>
@endpush
