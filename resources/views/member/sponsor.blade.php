@extends('member.layouts.app')

@section('title', 'Sponsor Management')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Sponsor Management</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Sponsor</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <div class="row">
            <!-- My Sponsor Info -->
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            <i class="fe fe-user-plus me-2"></i>My Sponsor
                        </div>
                    </div>
                    <div class="card-body">
                        @if($sponsorInfo)
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar avatar-lg avatar-rounded me-3">
                                    <img src="{{ asset('admin-assets/images/users/default.jpg') }}" alt="sponsor" class="rounded-circle">
                                </div>
                                <div class="flex-fill">
                                    <h6 class="fw-semibold mb-1">{{ $sponsorInfo->name }}</h6>
                                    <p class="mb-1 text-muted fs-12">ID: {{ $sponsorInfo->referral_code ?? $sponsorInfo->id }}</p>
                                    <p class="mb-0 text-muted fs-12">{{ $sponsorInfo->email }}</p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-6">
                                    <div class="border p-3 rounded text-center">
                                        <h5 class="fw-semibold text-primary mb-1">{{ $sponsorInfo->created_at->format('M Y') }}</h5>
                                        <p class="text-muted mb-0 fs-12">Joined Date</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="border p-3 rounded text-center">
                                        <h5 class="fw-semibold text-success mb-1">Active</h5>
                                        <p class="text-muted mb-0 fs-12">Status</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <div class="avatar avatar-xl avatar-rounded bg-light mb-3">
                                    <i class="fe fe-user-x fs-24 text-muted"></i>
                                </div>
                                <h6 class="fw-semibold mb-1">No Sponsor</h6>
                                <p class="text-muted mb-0">You don't have a sponsor assigned</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sponsor Summary -->
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-bar-chart-2 me-2"></i>Sponsorship Summary
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm avatar-rounded bg-primary-transparent me-3">
                                        <i class="fe fe-users text-primary"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-semibold mb-0">{{ $directReferrals->count() }}</h5>
                                        <p class="text-muted mb-0 fs-12">Direct Referrals</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm avatar-rounded bg-success-transparent me-3">
                                        <i class="fe fe-user-check text-success"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-semibold mb-0">{{ $directReferrals->where('status', 'active')->count() }}</h5>
                                        <p class="text-muted mb-0 fs-12">Active Members</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm avatar-rounded bg-warning-transparent me-3">
                                        <i class="fe fe-dollar-sign text-warning"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-semibold mb-0">${{ number_format($directReferrals->sum('total_business') ?? 0, 2) }}</h5>
                                        <p class="text-muted mb-0 fs-12">Total Business</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm avatar-rounded bg-info-transparent me-3">
                                        <i class="fe fe-trending-up text-info"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-semibold mb-0">${{ number_format($directReferrals->sum('commission_earned') ?? 0, 2) }}</h5>
                                        <p class="text-muted mb-0 fs-12">Commission Earned</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fs-12 text-muted">Performance this month</span>
                                <span class="fs-12 fw-semibold text-success">+{{ $directReferrals->where('created_at', '>=', now()->startOfMonth())->count() }} new</span>
                            </div>
                            <div class="progress progress-xs">
                                <div class="progress-bar bg-gradient-primary" style="width: {{ min(($directReferrals->count() / 10) * 100, 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Direct Referrals Table -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            <i class="fe fe-users me-2"></i>My Direct Referrals ({{ $directReferrals->count() }})
                        </div>
                        <div class="d-flex">
                            <button class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#inviteModal">
                                <i class="fe fe-plus me-1"></i>Invite New Member
                            </button>
                            <button class="btn btn-success btn-sm">
                                <i class="fe fe-download me-1"></i>Export
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($directReferrals->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Member</th>
                                            <th scope="col">ID</th>
                                            <th scope="col">Join Date</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Business</th>
                                            <th scope="col">Commission</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($directReferrals as $index => $referral)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm avatar-rounded me-2">
                                                        <img src="{{ asset('admin-assets/images/users/default.jpg') }}" alt="user">
                                                    </div>
                                                    <div>
                                                        <p class="mb-0 fw-semibold">{{ $referral->name }}</p>
                                                        <p class="mb-0 text-muted fs-12">{{ $referral->email }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ $referral->referral_code ?? $referral->id }}</span>
                                            </td>
                                            <td>{{ $referral->created_at->format('M d, Y') }}</td>
                                            <td>
                                                @if($referral->status == 'active')
                                                    <span class="badge bg-success-transparent">Active</span>
                                                @elseif($referral->status == 'inactive')
                                                    <span class="badge bg-warning-transparent">Inactive</span>
                                                @else
                                                    <span class="badge bg-secondary-transparent">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-semibold text-success">${{ number_format($referral->total_business ?? 0, 2) }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold text-primary">${{ number_format($referral->commission_earned ?? 0, 2) }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-primary-light" title="View Details">
                                                        <i class="fe fe-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-success-light" title="Send Message">
                                                        <i class="fe fe-message-circle"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="avatar avatar-xl avatar-rounded bg-light mb-3">
                                    <i class="fe fe-users fs-24 text-muted"></i>
                                </div>
                                <h6 class="fw-semibold mb-1">No Direct Referrals Yet</h6>
                                <p class="text-muted mb-3">Start building your network by inviting new members</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inviteModal">
                                    <i class="fe fe-plus me-1"></i>Invite Your First Member
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Referral Link Card -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-link me-2"></i>Your Referral Link
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="input-group">
                            <input type="text" class="form-control" id="referralLink" value="{{ url('/register?ref=' . ($user->referral_code ?? $user->id)) }}" readonly>
                            <button class="btn btn-primary" type="button" onclick="copyReferralLink()">
                                <i class="fe fe-copy me-1"></i>Copy Link
                            </button>
                        </div>
                        <div class="mt-3 d-flex flex-wrap gap-2">
                            <button class="btn btn-sm btn-info-light" onclick="shareWhatsApp()">
                                <i class="fe fe-message-circle me-1"></i>Share on WhatsApp
                            </button>
                            <button class="btn btn-sm btn-primary-light" onclick="shareEmail()">
                                <i class="fe fe-mail me-1"></i>Share via Email
                            </button>
                            <button class="btn btn-sm btn-success-light" onclick="generateQR()">
                                <i class="fe fe-grid me-1"></i>Generate QR Code
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Invite Modal -->
<div class="modal fade" id="inviteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Invite New Member</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Name *</label>
                        <input type="text" class="form-control" placeholder="Enter full name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" class="form-control" placeholder="Enter email address">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="tel" class="form-control" placeholder="Enter phone number">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Personal Message</label>
                        <textarea class="form-control" rows="3" placeholder="Add a personal invitation message..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Send Invitation</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function copyReferralLink() {
    const referralLink = document.getElementById('referralLink');
    referralLink.select();
    referralLink.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(referralLink.value);
    
    // Show success message
    Swal.fire({
        icon: 'success',
        title: 'Copied!',
        text: 'Referral link copied to clipboard',
        showConfirmButton: false,
        timer: 1500
    });
}

function shareWhatsApp() {
    const link = document.getElementById('referralLink').value;
    const message = `Join our amazing MLM network! Use my referral link: ${link}`;
    const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
}

function shareEmail() {
    const link = document.getElementById('referralLink').value;
    const subject = 'Join Our Network - Exclusive Invitation';
    const body = `Hi there!\n\nI'd like to invite you to join our amazing MLM network. Use my referral link to get started:\n\n${link}\n\nLooking forward to having you on our team!\n\nBest regards`;
    const mailtoUrl = `mailto:?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
    window.location.href = mailtoUrl;
}

function generateQR() {
    // This would integrate with a QR code library
    Swal.fire({
        icon: 'info',
        title: 'QR Code Generator',
        text: 'QR code generation feature coming soon!',
    });
}
</script>
@endpush
