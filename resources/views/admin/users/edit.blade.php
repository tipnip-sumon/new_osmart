@extends('admin.layouts.app')

@section('title', 'Edit User - ' . $user['name'])

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">
                <i class="bi bi-person-gear me-2"></i>Edit User: {{ $user['name'] }}
            </h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.show', $user['id']) }}">{{ $user['name'] }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- User Activity Status -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-info d-flex align-items-center">
                    <i class="bi bi-info-circle me-2"></i>
                    <div>
                        <strong>Last Login:</strong> {{ $user['last_login_at'] ? \Carbon\Carbon::parse($user['last_login_at'])->diffForHumans() : 'Never' }} |
                        <strong>Member Since:</strong> {{ \Carbon\Carbon::parse($user['created_at'])->format('M d, Y') }} |
                        <strong>Total Orders:</strong> {{ $user['orders_count'] ?? 0 }} |
                        <strong>Current Status:</strong> 
                        <span class="badge {{ $user['status'] == 'active' ? 'bg-success' : ($user['status'] == 'suspended' ? 'bg-danger' : 'bg-secondary') }}">
                            {{ ucfirst($user['status']) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.users.update', $user['id']) }}" method="POST" enctype="multipart/form-data" id="editUserForm">
            @csrf
            @method('PUT')
            
            <div class="row">
                <!-- Basic Information -->
                <div class="col-xl-8">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="bi bi-person-vcard me-2"></i>Basic Information
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name', explode(' ', $user['name'])[0]) }}" required>
                                        @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name', isset(explode(' ', $user['name'])[1]) ? explode(' ', $user['name'])[1] : '') }}" required>
                                        @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user['email']) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            <i class="bi bi-shield-check text-{{ $user['email_verified_at'] ? 'success' : 'warning' }}"></i>
                                            {{ $user['email_verified_at'] ? 'Email Verified' : 'Email Not Verified' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user['phone'] ?? '') }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                                        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $user['date_of_birth'] ?? '') }}">
                                        @error('date_of_birth')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="gender" class="form-label">Gender</label>
                                        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                            <option value="">Select Gender</option>
                                            <option value="male" {{ old('gender', $user['gender'] ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender', $user['gender'] ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ old('gender', $user['gender'] ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $user['address'] ?? '') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Password Update -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="bi bi-key me-2"></i>Password Update
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>Leave password fields empty if you don't want to change the password.
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">New Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Minimum 8 characters with uppercase, lowercase, number and special character</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- MLM Information -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="bi bi-diagram-3 me-2"></i>MLM Information
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sponsor_id" class="form-label">Sponsor ID</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control @error('sponsor_id') is-invalid @enderror" id="sponsor_id" name="sponsor_id" value="{{ old('sponsor_id', $user['sponsor_id'] ?? '') }}" placeholder="Enter sponsor user ID">
                                            <button class="btn btn-outline-secondary" type="button" id="searchSponsor">
                                                <i class="bi bi-search"></i>
                                            </button>
                                        </div>
                                        <div id="sponsorInfo" class="mt-2">
                                            @if($user['sponsor_id'] ?? false)
                                                <div class="alert alert-info">
                                                    <strong>Current Sponsor:</strong> ID {{ $user['sponsor_id'] }}
                                                </div>
                                            @endif
                                        </div>
                                        @error('sponsor_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="position" class="form-label">Position</label>
                                        <select class="form-select @error('position') is-invalid @enderror" id="position" name="position">
                                            <option value="">Auto Assign</option>
                                            <option value="left" {{ old('position', $user['position'] ?? '') == 'left' ? 'selected' : '' }}>Left</option>
                                            <option value="right" {{ old('position', $user['position'] ?? '') == 'right' ? 'selected' : '' }}>Right</option>
                                        </select>
                                        @error('position')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="rank" class="form-label">Current Rank</label>
                                        <select class="form-select @error('rank') is-invalid @enderror" id="rank" name="rank">
                                            <option value="bronze" {{ old('rank', $user['rank'] ?? 'bronze') == 'bronze' ? 'selected' : '' }}>Bronze</option>
                                            <option value="silver" {{ old('rank', $user['rank'] ?? 'bronze') == 'silver' ? 'selected' : '' }}>Silver</option>
                                            <option value="gold" {{ old('rank', $user['rank'] ?? 'bronze') == 'gold' ? 'selected' : '' }}>Gold</option>
                                            <option value="platinum" {{ old('rank', $user['rank'] ?? 'bronze') == 'platinum' ? 'selected' : '' }}>Platinum</option>
                                            <option value="diamond" {{ old('rank', $user['rank'] ?? 'bronze') == 'diamond' ? 'selected' : '' }}>Diamond</option>
                                        </select>
                                        @error('rank')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="pv_points" class="form-label">PV Points</label>
                                        <input type="number" class="form-control @error('pv_points') is-invalid @enderror" id="pv_points" name="pv_points" value="{{ old('pv_points', $user['pv_points'] ?? 0) }}" min="0" step="0.01">
                                        @error('pv_points')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Current PV: {{ number_format($user['pv_points'] ?? 0, 2) }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="commission_balance" class="form-label">Commission Balance (৳)</label>
                                        <input type="number" class="form-control @error('commission_balance') is-invalid @enderror" id="commission_balance" name="commission_balance" value="{{ old('commission_balance', $user['commission_balance'] ?? 0) }}" min="0" step="0.01">
                                        @error('commission_balance')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="wallet_balance" class="form-label">Wallet Balance (৳)</label>
                                        <input type="number" class="form-control @error('wallet_balance') is-invalid @enderror" id="wallet_balance" name="wallet_balance" value="{{ old('wallet_balance', $user['wallet_balance'] ?? 0) }}" min="0" step="0.01">
                                        @error('wallet_balance')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <div class="col-xl-4">
                    <!-- Current Profile Picture -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="bi bi-camera me-2"></i>Profile Picture
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <div class="avatar-preview">
                                    <img id="avatarPreview" src="{{ $user['avatar'] ?? '/assets/img/default-avatar.svg' }}" class="rounded-circle" width="120" height="120" alt="Avatar Preview">
                                </div>
                            </div>
                            <div class="mb-3">
                                <input type="file" class="form-control @error('avatar') is-invalid @enderror" id="avatar" name="avatar" accept="image/*">
                                @error('avatar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Upload JPG, PNG or GIF. Max size 2MB</div>
                            </div>
                            @if($user['avatar'] ?? false)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remove_avatar" name="remove_avatar" value="1">
                                    <label class="form-check-label text-danger" for="remove_avatar">
                                        Remove current avatar
                                    </label>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Account Settings -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="bi bi-gear me-2"></i>Account Settings
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="role" class="form-label">User Role <span class="text-danger">*</span></label>
                                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="user" {{ old('role', $user['role'] ?? 'user') == 'user' ? 'selected' : '' }}>Regular User</option>
                                    <option value="distributor" {{ old('role', $user['role'] ?? 'user') == 'distributor' ? 'selected' : '' }}>Distributor</option>
                                    <option value="manager" {{ old('role', $user['role'] ?? 'user') == 'manager' ? 'selected' : '' }}>Manager</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Account Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                    <option value="active" {{ old('status', $user['status'] ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $user['status'] ?? 'active') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="suspended" {{ old('status', $user['status'] ?? 'active') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="email_verified" name="email_verified" value="1" {{ old('email_verified', $user['email_verified_at']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="email_verified">
                                    Email Verified
                                </label>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="send_notification" name="send_notification" value="1" {{ old('send_notification') ? 'checked' : '' }}>
                                <label class="form-check-label" for="send_notification">
                                    Send Update Notification
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- User Statistics -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="bi bi-graph-up me-2"></i>User Statistics
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Member Since:</span>
                                <span>{{ \Carbon\Carbon::parse($user['created_at'])->format('M d, Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Total Orders:</span>
                                <span>{{ $user['orders_count'] ?? 0 }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Total Spent:</span>
                                <span>৳{{ number_format($user['total_spent'] ?? 0, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Commission Earned:</span>
                                <span>৳{{ number_format($user['total_commission'] ?? 0, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Team Size:</span>
                                <span>{{ $user['team_count'] ?? 0 }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Current Rank:</span>
                                <span id="rankPreview" class="badge bg-secondary">{{ ucfirst($user['rank'] ?? 'bronze') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="bi bi-clock-history me-2"></i>Recent Activity
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Last Login</h6>
                                        <p class="timeline-text">{{ $user['last_login_at'] ? \Carbon\Carbon::parse($user['last_login_at'])->diffForHumans() : 'Never logged in' }}</p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Account Created</h6>
                                        <p class="timeline-text">{{ \Carbon\Carbon::parse($user['created_at'])->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-info"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Profile Updated</h6>
                                        <p class="timeline-text">{{ \Carbon\Carbon::parse($user['updated_at'])->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="row">
                <div class="col-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left me-2"></i>Back to Users
                                    </a>
                                    <a href="{{ route('admin.users.show', $user['id']) }}" class="btn btn-outline-info ms-2">
                                        <i class="bi bi-eye me-2"></i>View Profile
                                    </a>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-outline-primary me-2" id="previewBtn">
                                        <i class="bi bi-eye me-2"></i>Preview Changes
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-2"></i>Update User
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-eye me-2"></i>Update Preview
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="previewContent">
                    <!-- Preview content will be populated here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="$('#editUserForm').submit()">
                        <i class="bi bi-save me-2"></i>Update User
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline-item {
    position: relative;
    padding-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -25px;
    top: 5px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.timeline-content h6 {
    margin-bottom: 5px;
    font-size: 14px;
}

.timeline-content p {
    margin-bottom: 0;
    font-size: 12px;
    color: #6c757d;
}

.timeline::before {
    content: '';
    position: absolute;
    left: -21px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}
</style>

<script>
$(document).ready(function() {
    // Password toggle
    $('#togglePassword').click(function() {
        const passwordField = $('#password');
        const icon = $(this).find('i');
        
        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            icon.removeClass('bi-eye').addClass('bi-eye-slash');
        } else {
            passwordField.attr('type', 'password');
            icon.removeClass('bi-eye-slash').addClass('bi-eye');
        }
    });

    // Avatar preview
    $('#avatar').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#avatarPreview').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });

    // Remove avatar checkbox
    $('#remove_avatar').change(function() {
        if ($(this).is(':checked')) {
            $('#avatarPreview').attr('src', '/assets/img/default-avatar.svg');
        } else {
            $('#avatarPreview').attr('src', '{{ $user["avatar"] ?? "/assets/img/default-avatar.svg" }}');
        }
    });

    // Live preview updates
    $('#rank').change(function() {
        const rank = $(this).val() || 'bronze';
        const rankColors = {
            bronze: 'bg-secondary',
            silver: 'bg-info',
            gold: 'bg-warning',
            platinum: 'bg-primary',
            diamond: 'bg-success'
        };
        $('#rankPreview').removeClass().addClass('badge ' + rankColors[rank]).text(rank.charAt(0).toUpperCase() + rank.slice(1));
    });

    // Sponsor search
    $('#searchSponsor').click(function() {
        const sponsorId = $('#sponsor_id').val();
        if (!sponsorId) {
            alert('Please enter a sponsor ID');
            return;
        }

        // AJAX call to search sponsor
        $.ajax({
            url: '/admin/users/search-sponsor',
            type: 'GET',
            data: { id: sponsorId },
            success: function(response) {
                if (response.success) {
                    $('#sponsorInfo').html(`
                        <div class="alert alert-success">
                            <strong>Sponsor Found:</strong> ${response.sponsor.name} (${response.sponsor.email})
                        </div>
                    `);
                } else {
                    $('#sponsorInfo').html(`
                        <div class="alert alert-warning">
                            <strong>Not Found:</strong> No user found with ID: ${sponsorId}
                        </div>
                    `);
                }
            },
            error: function() {
                $('#sponsorInfo').html(`
                    <div class="alert alert-danger">
                        <strong>Error:</strong> Unable to search sponsor
                    </div>
                `);
            }
        });
    });

    // Preview functionality
    $('#previewBtn').click(function() {
        const changes = [];
        
        // Compare with original values
        const originalFirstName = '{{ explode(' ', $user['name'])[0] }}';
        const originalLastName = '{{ isset(explode(' ', $user['name'])[1]) ? explode(' ', $user['name'])[1] : '' }}';
        const originalEmail = '{{ $user['email'] }}';
        const originalPhone = '{{ $user['phone'] ?? '' }}';
        const originalStatus = '{{ $user['status'] ?? 'active' }}';
        const originalRole = '{{ $user['role'] ?? 'user' }}';
        
        if ($('#first_name').val() !== originalFirstName) {
            changes.push(`<strong>First Name:</strong> ${originalFirstName} → ${$('#first_name').val()}`);
        }
        if ($('#last_name').val() !== originalLastName) {
            changes.push(`<strong>Last Name:</strong> ${originalLastName} → ${$('#last_name').val()}`);
        }
        if ($('#email').val() !== originalEmail) {
            changes.push(`<strong>Email:</strong> ${originalEmail} → ${$('#email').val()}`);
        }
        if ($('#phone').val() !== originalPhone) {
            changes.push(`<strong>Phone:</strong> ${originalPhone || 'N/A'} → ${$('#phone').val() || 'N/A'}`);
        }
        if ($('#status').val() !== originalStatus) {
            changes.push(`<strong>Status:</strong> ${originalStatus} → ${$('#status').val()}`);
        }
        if ($('#role').val() !== originalRole) {
            changes.push(`<strong>Role:</strong> ${originalRole} → ${$('#role').val()}`);
        }
        
        if ($('#password').val()) {
            changes.push(`<strong>Password:</strong> Will be updated`);
        }
        
        if ($('#avatar')[0].files.length > 0) {
            changes.push(`<strong>Avatar:</strong> New image will be uploaded`);
        }
        
        if ($('#remove_avatar').is(':checked')) {
            changes.push(`<strong>Avatar:</strong> Current avatar will be removed`);
        }

        let previewHtml = `
            <div class="row">
                <div class="col-md-3 text-center">
                    <img src="${$('#avatarPreview').attr('src')}" class="rounded-circle mb-3" width="100" height="100">
                </div>
                <div class="col-md-9">
                    <h5>${$('#first_name').val()} ${$('#last_name').val()}</h5>
                    <p class="text-muted">${$('#email').val()}</p>
                    <span class="badge bg-${$('#status').val() === 'active' ? 'success' : ($('#status').val() === 'suspended' ? 'danger' : 'secondary')}">${$('#status').val()}</span>
                    <span class="badge bg-primary">${$('#role').val()}</span>
                </div>
            </div>
            <hr>
            <h6>Changes to be made:</h6>
        `;
        
        if (changes.length > 0) {
            previewHtml += '<ul class="list-unstyled">';
            changes.forEach(change => {
                previewHtml += `<li class="mb-1"><i class="bi bi-arrow-right text-primary me-2"></i>${change}</li>`;
            });
            previewHtml += '</ul>';
        } else {
            previewHtml += '<p class="text-muted">No changes detected.</p>';
        }
        
        $('#previewContent').html(previewHtml);
        $('#previewModal').modal('show');
    });

    // Form validation
    $('#editUserForm').submit(function(e) {
        const password = $('#password').val();
        const confirmPassword = $('#password_confirmation').val();
        
        if (password && password !== confirmPassword) {
            e.preventDefault();
            alert('Passwords do not match!');
            return false;
        }
        
        if (password && password.length < 8) {
            e.preventDefault();
            alert('Password must be at least 8 characters long!');
            return false;
        }
    });
});
</script>
@endpush
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" id="email" name="email" value="{{ $user['email'] }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="phone" class="form-label">Phone Number</label>
                                                <input type="text" class="form-control" id="phone" name="phone" value="{{ $user['phone'] }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="birth_date" class="form-label">Date of Birth</label>
                                                <input type="date" class="form-control" id="birth_date" name="birth_date" value="{{ date('Y-m-d', strtotime('-' . rand(25, 55) . ' years')) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="gender" class="form-label">Gender</label>
                                                <select class="form-select" id="gender" name="gender">
                                                    <option value="">Select Gender</option>
                                                    <option value="male" {{ rand(0,1) ? 'selected' : '' }}>Male</option>
                                                    <option value="female" {{ rand(0,1) ? 'selected' : '' }}>Female</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="bio" class="form-label">Bio</label>
                                        <textarea class="form-control" id="bio" name="bio" rows="3" placeholder="Tell us about yourself...">Professional network marketer with {{ rand(2, 10) }} years of experience in the industry.</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Address Information -->
                            <div class="card custom-card">
                                <div class="card-header">
                                    <div class="card-title">Address Information</div>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="street" class="form-label">Street Address</label>
                                        <input type="text" class="form-control" id="street" name="street" value="{{ $user['address']['street'] }}">
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="city" class="form-label">City</label>
                                                <input type="text" class="form-control" id="city" name="city" value="{{ $user['address']['city'] }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="state" class="form-label">State/Province</label>
                                                <input type="text" class="form-control" id="state" name="state" value="{{ $user['address']['state'] }}">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="zip" class="form-label">ZIP/Postal Code</label>
                                                <input type="text" class="form-control" id="zip" name="zip" value="{{ $user['address']['zip'] }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="country" class="form-label">Country</label>
                                                <select class="form-select" id="country" name="country">
                                                    <option value="">Select Country</option>
                                                    <option value="US" {{ $user['address']['country'] == 'USA' ? 'selected' : '' }}>United States</option>
                                                    <option value="CA" {{ $user['address']['country'] == 'Canada' ? 'selected' : '' }}>Canada</option>
                                                    <option value="UK" {{ $user['address']['country'] == 'UK' ? 'selected' : '' }}>United Kingdom</option>
                                                    <option value="AU" {{ $user['address']['country'] == 'Australia' ? 'selected' : '' }}>Australia</option>
                                                    <option value="DE" {{ $user['address']['country'] == 'Germany' ? 'selected' : '' }}>Germany</option>
                                                    <option value="FR" {{ $user['address']['country'] == 'France' ? 'selected' : '' }}>France</option>
                                                    <option value="IN" {{ $user['address']['country'] == 'India' ? 'selected' : '' }}>India</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Security Settings -->
                            <div class="card custom-card">
                                <div class="card-header">
                                    <div class="card-title">Security Settings</div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="password" class="form-label">New Password</label>
                                                <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="two_factor_enabled" name="two_factor_enabled" {{ rand(0,1) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="two_factor_enabled">
                                                    Enable Two-Factor Authentication
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="email_verified" name="email_verified" {{ rand(0,1) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="email_verified">
                                                    Email Verified
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status and MLM Settings -->
                        <div class="col-xl-4">
                            <div class="card custom-card">
                                <div class="card-header">
                                    <div class="card-title">Account Status</div>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="Active" {{ $user['status'] == 'Active' ? 'selected' : '' }}>Active</option>
                                            <option value="Inactive" {{ $user['status'] == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                            <option value="Suspended" {{ $user['status'] == 'Suspended' ? 'selected' : '' }}>Suspended</option>
                                            <option value="Pending" {{ $user['status'] == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="role" class="form-label">Role</label>
                                        <select class="form-select" id="role" name="role">
                                            <option value="Customer" {{ $user['role'] == 'Customer' ? 'selected' : '' }}>Customer</option>
                                            <option value="Distributor" {{ $user['role'] == 'Distributor' ? 'selected' : '' }}>Distributor</option>
                                            <option value="Manager" {{ $user['role'] == 'Manager' ? 'selected' : '' }}>Manager</option>
                                            <option value="Admin" {{ $user['role'] == 'Admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="rank" class="form-label">MLM Rank</label>
                                        <select class="form-select" id="rank" name="rank">
                                            <option value="Starter" {{ $user['rank'] == 'Starter' ? 'selected' : '' }}>Starter</option>
                                            <option value="Bronze" {{ $user['rank'] == 'Bronze' ? 'selected' : '' }}>Bronze</option>
                                            <option value="Silver" {{ $user['rank'] == 'Silver' ? 'selected' : '' }}>Silver</option>
                                            <option value="Gold" {{ $user['rank'] == 'Gold' ? 'selected' : '' }}>Gold</option>
                                            <option value="Platinum" {{ $user['rank'] == 'Platinum' ? 'selected' : '' }}>Platinum</option>
                                            <option value="Diamond" {{ $user['rank'] == 'Diamond' ? 'selected' : '' }}>Diamond</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="sponsor_id" class="form-label">Sponsor</label>
                                        <select class="form-select" id="sponsor_id" name="sponsor_id">
                                            <option value="">No Sponsor</option>
                                            <option value="1" {{ $user['sponsor'] != 'N/A' ? 'selected' : '' }}>{{ $user['sponsor'] != 'N/A' ? $user['sponsor'] : 'John Smith' }}</option>
                                            <option value="2">Sarah Johnson</option>
                                            <option value="3">Mike Davis</option>
                                            <option value="4">Lisa Wilson</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- MLM Statistics -->
                            <div class="card custom-card">
                                <div class="card-header">
                                    <div class="card-title">MLM Statistics</div>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="personal_volume" class="form-label">Personal Volume (PV)</label>
                                        <input type="number" class="form-control" id="personal_volume" name="personal_volume" value="{{ $user['statistics']['total_pv'] }}" step="0.01">
                                    </div>

                                    <div class="mb-3">
                                        <label for="group_volume" class="form-label">Group Volume (GV)</label>
                                        <input type="number" class="form-control" id="group_volume" name="group_volume" value="{{ $user['statistics']['total_pv'] * 2.5 }}" step="0.01">
                                    </div>

                                    <div class="mb-3">
                                        <label for="commission_rate" class="form-label">Commission Rate (%)</label>
                                        <input type="number" class="form-control" id="commission_rate" name="commission_rate" value="{{ rand(5, 25) }}" step="0.1" min="0" max="100">
                                    </div>

                                    <div class="mb-3">
                                        <label for="direct_referrals" class="form-label">Direct Referrals</label>
                                        <input type="number" class="form-control" id="direct_referrals" name="direct_referrals" value="{{ rand(5, 50) }}" readonly>
                                    </div>

                                    <div class="mb-0">
                                        <label for="total_downline" class="form-label">Total Downline</label>
                                        <input type="number" class="form-control" id="total_downline" name="total_downline" value="{{ $user['statistics']['downline_count'] }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Notification Settings -->
                            <div class="card custom-card">
                                <div class="card-header">
                                    <div class="card-title">Notification Settings</div>
                                </div>
                                <div class="card-body">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" {{ rand(0,1) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="email_notifications">
                                            Email Notifications
                                        </label>
                                    </div>

                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="sms_notifications" name="sms_notifications" {{ rand(0,1) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sms_notifications">
                                            SMS Notifications
                                        </label>
                                    </div>

                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="commission_alerts" name="commission_alerts" {{ rand(0,1) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="commission_alerts">
                                            Commission Alerts
                                        </label>
                                    </div>

                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="rank_promotion_alerts" name="rank_promotion_alerts" {{ rand(0,1) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rank_promotion_alerts">
                                            Rank Promotion Alerts
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card custom-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('admin.users.show', $user['id']) }}" class="btn btn-light">Cancel</a>
                                        <button type="reset" class="btn btn-secondary">Reset</button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ri-save-line me-1"></i> Update User
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@push('scripts')
<script>
    // Password confirmation validation
    document.getElementById('password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmation = this.value;
        
        if (password !== confirmation) {
            this.setCustomValidity('Passwords do not match');
        } else {
            this.setCustomValidity('');
        }
    });

    // Commission rate based on rank
    document.getElementById('rank').addEventListener('change', function() {
        const commissionRates = {
            'Starter': 5,
            'Bronze': 8,
            'Silver': 12,
            'Gold': 18,
            'Platinum': 22,
            'Diamond': 25
        };
        
        const selectedRank = this.value;
        if (commissionRates[selectedRank]) {
            document.getElementById('commission_rate').value = commissionRates[selectedRank];
        }
    });
</script>
@endpush
@endsection
