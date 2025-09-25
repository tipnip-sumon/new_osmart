@extends('member.layouts.app')

@section('title', 'Support Center')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Support Center</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Support</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Quick Support Options -->
        <div class="row mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card support-card">
                    <div class="card-body text-center">
                        <div class="avatar avatar-lg bg-primary-transparent mb-3">
                            <i class="fe fe-message-circle fs-24"></i>
                        </div>
                        <h6 class="fw-semibold mb-2">Live Chat</h6>
                        <p class="text-muted mb-3">Get instant help from our support team</p>
                        <button class="btn btn-primary btn-sm" onclick="startLiveChat()">Start Chat</button>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card support-card">
                    <div class="card-body text-center">
                        <div class="avatar avatar-lg bg-success-transparent mb-3">
                            <i class="fe fe-phone fs-24"></i>
                        </div>
                        <h6 class="fw-semibold mb-2">Phone Support</h6>
                        <p class="text-muted mb-3">Call us for immediate assistance</p>
                        <button class="btn btn-success btn-sm" onclick="showPhoneNumbers()">Call Now</button>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card support-card">
                    <div class="card-body text-center">
                        <div class="avatar avatar-lg bg-warning-transparent mb-3">
                            <i class="fe fe-mail fs-24"></i>
                        </div>
                        <h6 class="fw-semibold mb-2">Email Support</h6>
                        <p class="text-muted mb-3">Send us an email with your query</p>
                        <button class="btn btn-warning btn-sm" onclick="composeEmail()">Send Email</button>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card support-card">
                    <div class="card-body text-center">
                        <div class="avatar avatar-lg bg-info-transparent mb-3">
                            <i class="fe fe-help-circle fs-24"></i>
                        </div>
                        <h6 class="fw-semibold mb-2">FAQ</h6>
                        <p class="text-muted mb-3">Find answers to common questions</p>
                        <button class="btn btn-info btn-sm" onclick="showFAQ()">View FAQ</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Support Tickets -->
            <div class="col-xl-8">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            <i class="fe fe-inbox me-2"></i>My Support Tickets
                        </div>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newTicketModal">
                            <i class="fe fe-plus me-1"></i>New Ticket
                        </button>
                    </div>
                    <div class="card-body">
                        @if($supportTickets->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Ticket ID</th>
                                            <th>Subject</th>
                                            <th>Status</th>
                                            <th>Priority</th>
                                            <th>Created</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($supportTickets as $ticket)
                                        <tr>
                                            <td><span class="badge bg-light text-dark">#{{ $ticket->id ?? '001' }}</span></td>
                                            <td>{{ $ticket->subject ?? 'Sample Ticket' }}</td>
                                            <td>
                                                <span class="badge bg-{{ ($ticket->status ?? 'open') == 'open' ? 'warning' : (($ticket->status ?? 'open') == 'closed' ? 'success' : 'info') }}-transparent">
                                                    {{ ucfirst($ticket->status ?? 'Open') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ ($ticket->priority ?? 'medium') == 'high' ? 'danger' : (($ticket->priority ?? 'medium') == 'low' ? 'secondary' : 'warning') }}-transparent">
                                                    {{ ucfirst($ticket->priority ?? 'Medium') }}
                                                </span>
                                            </td>
                                            <td>{{ $ticket->created_at ?? now()->format('M d, Y') }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-primary-light" onclick="viewTicket({{ $ticket->id ?? 1 }})">
                                                    <i class="fe fe-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="avatar avatar-xl avatar-rounded bg-light mb-3">
                                    <i class="fe fe-inbox fs-24 text-muted"></i>
                                </div>
                                <h6 class="fw-semibold mb-1">No Support Tickets</h6>
                                <p class="text-muted mb-3">You haven't created any support tickets yet</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newTicketModal">
                                    <i class="fe fe-plus me-1"></i>Create Your First Ticket
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Support Resources -->
            <div class="col-xl-4">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-book me-2"></i>Support Resources
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="support-resources">
                            <div class="resource-item">
                                <div class="resource-icon">
                                    <i class="fe fe-book-open text-primary"></i>
                                </div>
                                <div class="resource-content">
                                    <h6>User Guide</h6>
                                    <p class="text-muted mb-1">Complete guide to using our platform</p>
                                    <a href="#" class="btn btn-sm btn-primary-light">Download</a>
                                </div>
                            </div>
                            <div class="resource-item">
                                <div class="resource-icon">
                                    <i class="fe fe-video text-success"></i>
                                </div>
                                <div class="resource-content">
                                    <h6>Video Tutorials</h6>
                                    <p class="text-muted mb-1">Step-by-step video guides</p>
                                    <a href="#" class="btn btn-sm btn-success-light">Watch</a>
                                </div>
                            </div>
                            <div class="resource-item">
                                <div class="resource-icon">
                                    <i class="fe fe-users text-info"></i>
                                </div>
                                <div class="resource-content">
                                    <h6>Community Forum</h6>
                                    <p class="text-muted mb-1">Connect with other members</p>
                                    <a href="#" class="btn btn-sm btn-info-light">Join</a>
                                </div>
                            </div>
                            <div class="resource-item">
                                <div class="resource-icon">
                                    <i class="fe fe-download text-warning"></i>
                                </div>
                                <div class="resource-content">
                                    <h6>Downloads</h6>
                                    <p class="text-muted mb-1">Forms, brochures and marketing materials</p>
                                    <a href="#" class="btn btn-sm btn-warning-light">Browse</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-phone me-2"></i>Contact Information
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="contact-info">
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fe fe-phone text-primary"></i>
                                </div>
                                <div class="contact-details">
                                    <h6>Phone Support</h6>
                                    <p class="mb-1">+1-800-123-4567</p>
                                    <small class="text-muted">Mon-Fri: 9AM-6PM EST</small>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fe fe-mail text-success"></i>
                                </div>
                                <div class="contact-details">
                                    <h6>Email Support</h6>
                                    <p class="mb-1">support@company.com</p>
                                    <small class="text-muted">24/7 Response</small>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fe fe-map-pin text-info"></i>
                                </div>
                                <div class="contact-details">
                                    <h6>Office Address</h6>
                                    <p class="mb-1">123 Business Street<br>Suite 100, City, State 12345</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="row mt-4">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-help-circle me-2"></i>Frequently Asked Questions
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="accordion accordion-flush" id="faqAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faq1Heading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="false">
                                        How do I reset my password?
                                    </button>
                                </h2>
                                <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        You can reset your password by going to the login page and clicking "Forgot Password". Enter your email address and we'll send you a reset link.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faq2Heading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="false">
                                        How do I update my profile information?
                                    </button>
                                </h2>
                                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Go to your Profile page from the main menu. You can update your personal information, contact details, and preferences there.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faq3Heading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="false">
                                        How do I track my referrals and commissions?
                                    </button>
                                </h2>
                                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        You can track your referrals in the "Sponsor" section and view commission details in the "Reports" section of your dashboard.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faq4Heading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" aria-expanded="false">
                                        What payment methods are accepted?
                                    </button>
                                </h2>
                                <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        We accept all major credit cards, PayPal, bank transfers, and cryptocurrency payments. Check the payment section for more details.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- New Ticket Modal -->
<div class="modal fade" id="newTicketModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Create New Support Ticket</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="ticketForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category *</label>
                            <select class="form-select" name="category" required>
                                <option value="">Select Category</option>
                                <option value="technical">Technical Support</option>
                                <option value="billing">Billing & Payments</option>
                                <option value="account">Account Issues</option>
                                <option value="general">General Inquiry</option>
                                <option value="bug">Bug Report</option>
                                <option value="feature">Feature Request</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Priority *</label>
                            <select class="form-select" name="priority" required>
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Subject *</label>
                            <input type="text" class="form-control" name="subject" placeholder="Brief description of your issue" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description *</label>
                            <textarea class="form-control" name="description" rows="5" placeholder="Please provide detailed information about your issue..." required></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Attachments</label>
                            <input type="file" class="form-control" name="attachments" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                            <small class="text-muted">Maximum 5 files, 10MB each. Allowed: JPG, PNG, PDF, DOC, DOCX</small>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitTicket()">
                    <i class="fe fe-send me-1"></i>Submit Ticket
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.support-card {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.support-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.resource-item, .contact-item {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    padding: 15px 0;
    border-bottom: 1px solid #f1f3f4;
}

.resource-item:last-child, .contact-item:last-child {
    border-bottom: none;
}

.resource-icon, .contact-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: #f8f9fa;
}

.resource-content h6, .contact-details h6 {
    margin-bottom: 5px;
    font-size: 14px;
}

.resource-content p, .contact-details p {
    margin-bottom: 8px;
    font-size: 13px;
}

.accordion-button:not(.collapsed) {
    background-color: rgba(var(--primary-rgb), 0.1);
    color: var(--primary);
}
</style>
@endpush

@push('scripts')
<script>
function startLiveChat() {
    Swal.fire({
        title: 'Live Chat',
        text: 'Live chat feature will be available soon!',
        icon: 'info',
        confirmButtonText: 'OK'
    });
}

function showPhoneNumbers() {
    Swal.fire({
        title: 'Phone Support',
        html: `
            <div class="text-start">
                <p><strong>General Support:</strong> +1-800-123-4567</p>
                <p><strong>Technical Support:</strong> +1-800-123-4568</p>
                <p><strong>Billing Support:</strong> +1-800-123-4569</p>
                <hr>
                <p class="text-muted small">Hours: Monday-Friday 9AM-6PM EST</p>
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'OK'
    });
}

function composeEmail() {
    const subject = 'Support Request';
    const body = 'Hello Support Team,\n\nI need assistance with:\n\n[Please describe your issue here]\n\nBest regards,\n{{ $user->name }}';
    const mailtoUrl = `mailto:support@company.com?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
    window.location.href = mailtoUrl;
}

function showFAQ() {
    document.querySelector('[href="#faqAccordion"]')?.scrollIntoView({ behavior: 'smooth' });
}

function submitTicket() {
    const form = document.getElementById('ticketForm');
    const formData = new FormData(form);
    
    // Validate required fields
    const category = formData.get('category');
    const subject = formData.get('subject');
    const description = formData.get('description');
    
    if (!category || !subject || !description) {
        Swal.fire('Error', 'Please fill in all required fields', 'error');
        return;
    }

    Swal.fire({
        title: 'Submitting...',
        text: 'Please wait while we create your support ticket',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Simulate API call
    setTimeout(() => {
        Swal.fire({
            title: 'Success!',
            text: 'Your support ticket has been created. Ticket ID: #' + Math.floor(Math.random() * 1000),
            icon: 'success'
        });
        
        document.querySelector('#newTicketModal .btn-close').click();
        form.reset();
    }, 2000);
}

function viewTicket(ticketId) {
    Swal.fire({
        title: 'Ticket #' + ticketId,
        html: `
            <div class="text-start">
                <h6>Subject: Sample Support Issue</h6>
                <p><strong>Status:</strong> <span class="badge bg-warning-transparent">Open</span></p>
                <p><strong>Priority:</strong> <span class="badge bg-info-transparent">Medium</span></p>
                <p><strong>Created:</strong> ${new Date().toLocaleDateString()}</p>
                <hr>
                <p><strong>Description:</strong></p>
                <p>This is a sample ticket description. The actual ticket details would be loaded here.</p>
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'Close',
        showCancelButton: true,
        cancelButtonText: 'Reply'
    });
}
</script>
@endpush
