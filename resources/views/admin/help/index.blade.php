@extends('admin.layouts.app')

@section('title', 'Help & Support')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Help & Support</h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Help & Support</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <div class="row">
            <!-- Quick Help Cards -->
            <div class="col-xl-4 col-lg-6">
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <span class="avatar avatar-lg bg-primary-transparent">
                                <i class="ri-book-open-line fs-24"></i>
                            </span>
                        </div>
                        <h5 class="fw-semibold mb-2">Documentation</h5>
                        <p class="text-muted mb-3">Access comprehensive guides and documentation for the MLM E-commerce platform.</p>
                        <button class="btn btn-primary">View Documentation</button>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6">
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <span class="avatar avatar-lg bg-success-transparent">
                                <i class="ri-video-line fs-24"></i>
                            </span>
                        </div>
                        <h5 class="fw-semibold mb-2">Video Tutorials</h5>
                        <p class="text-muted mb-3">Watch step-by-step video tutorials for managing your MLM platform.</p>
                        <button class="btn btn-success">Watch Videos</button>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6">
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <span class="avatar avatar-lg bg-info-transparent">
                                <i class="ri-customer-service-line fs-24"></i>
                            </span>
                        </div>
                        <h5 class="fw-semibold mb-2">Live Support</h5>
                        <p class="text-muted mb-3">Get instant help from our support team via live chat or phone.</p>
                        <button class="btn btn-info">Contact Support</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- FAQ Section -->
            <div class="col-xl-8">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Frequently Asked Questions</div>
                    </div>
                    <div class="card-body">
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faq1">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                        How do I manage user ranks and commissions?
                                    </button>
                                </h2>
                                <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>To manage user ranks and commissions:</p>
                                        <ol>
                                            <li>Navigate to <strong>Users Management</strong> from the sidebar</li>
                                            <li>Select the user you want to modify</li>
                                            <li>Click <strong>Edit</strong> to change their rank, commission rate, or status</li>
                                            <li>The system automatically calculates commissions based on the MLM plan</li>
                                        </ol>
                                        <p>Commission rates are tied to user ranks: Starter (5%), Bronze (8%), Silver (12%), Gold (18%), Platinum (22%), Diamond (25%)</p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faq2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                        How do I add new products with PV points?
                                    </button>
                                </h2>
                                <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>Adding products with PV (Point Value) points:</p>
                                        <ol>
                                            <li>Go to <strong>Products Management</strong> → <strong>Add Product</strong></li>
                                            <li>Fill in product details (name, description, images)</li>
                                            <li>Set the product price and assign PV points</li>
                                            <li>Configure MLM settings like commission eligibility</li>
                                            <li>Save the product to make it available for purchase</li>
                                        </ol>
                                        <p><strong>Note:</strong> PV points determine commission calculations in the MLM structure.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faq3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                        How do I process orders and track commissions?
                                    </button>
                                </h2>
                                <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>Order processing and commission tracking:</p>
                                        <ol>
                                            <li>Monitor orders in <strong>Orders Management</strong></li>
                                            <li>Update order status (Pending → Processing → Shipped → Delivered)</li>
                                            <li>Commissions are automatically calculated when orders are completed</li>
                                            <li>View commission breakdowns in the order details</li>
                                            <li>Track genealogy commissions for multiple levels</li>
                                        </ol>
                                        <p>Commission distribution follows the MLM plan with direct and level-based commissions.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faq4">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                                        How do I configure MLM settings and compensation plan?
                                    </button>
                                </h2>
                                <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>Configuring MLM compensation plan:</p>
                                        <ol>
                                            <li>Access <strong>Settings</strong> → <strong>MLM Configuration</strong></li>
                                            <li>Set commission percentages for each rank level</li>
                                            <li>Configure genealogy depth (how many levels earn commissions)</li>
                                            <li>Set rank advancement requirements (PV, downline count)</li>
                                            <li>Define bonus structures and qualification criteria</li>
                                        </ol>
                                        <p>The platform supports binary, unilevel, and matrix compensation plans.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faq5">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5">
                                        How do I manage vendor applications and approvals?
                                    </button>
                                </h2>
                                <div id="collapse5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>Vendor management process:</p>
                                        <ol>
                                            <li>Review vendor applications in <strong>Vendors Management</strong></li>
                                            <li>Verify vendor documentation and business details</li>
                                            <li>Approve or reject applications with comments</li>
                                            <li>Set commission rates for approved vendors</li>
                                            <li>Monitor vendor performance and sales</li>
                                        </ol>
                                        <p>Approved vendors can list products and participate in the MLM commission structure.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Support Contact -->
            <div class="col-xl-4">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Contact Support</div>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="mb-3">
                                <label for="support_subject" class="form-label">Subject</label>
                                <select class="form-select" id="support_subject">
                                    <option value="">Select a topic</option>
                                    <option value="technical">Technical Issue</option>
                                    <option value="commission">Commission Problem</option>
                                    <option value="user_management">User Management</option>
                                    <option value="product_management">Product Management</option>
                                    <option value="vendor_issue">Vendor Issue</option>
                                    <option value="payment">Payment Problem</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="priority" class="form-label">Priority</label>
                                <select class="form-select" id="priority">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="support_message" class="form-label">Message</label>
                                <textarea class="form-control" id="support_message" rows="4" placeholder="Describe your issue in detail..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ri-send-plane-line me-1"></i> Send Support Request
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Contact Information</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex align-items-center">
                                <span class="avatar avatar-sm bg-primary-transparent me-2">
                                    <i class="ri-phone-line"></i>
                                </span>
                                <div>
                                    <div class="fw-semibold">Phone Support</div>
                                    <div class="text-muted fs-13">+1-800-MLM-HELP</div>
                                    <div class="text-muted fs-12">24/7 Available</div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex align-items-center">
                                <span class="avatar avatar-sm bg-success-transparent me-2">
                                    <i class="ri-mail-line"></i>
                                </span>
                                <div>
                                    <div class="fw-semibold">Email Support</div>
                                    <div class="text-muted fs-13">support@mlmecommerce.com</div>
                                    <div class="text-muted fs-12">Response within 2 hours</div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex align-items-center">
                                <span class="avatar avatar-sm bg-info-transparent me-2">
                                    <i class="ri-chat-3-line"></i>
                                </span>
                                <div>
                                    <div class="fw-semibold">Live Chat</div>
                                    <div class="text-muted fs-13">Available 9 AM - 6 PM EST</div>
                                    <div class="text-muted fs-12">Instant response</div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-0">
                            <div class="d-flex align-items-center">
                                <span class="avatar avatar-sm bg-warning-transparent me-2">
                                    <i class="ri-ticket-line"></i>
                                </span>
                                <div>
                                    <div class="fw-semibold">Support Tickets</div>
                                    <div class="text-muted fs-13">Track your requests</div>
                                    <div class="text-muted fs-12">Full history available</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Status -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">System Status</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Platform Status</span>
                                <span class="badge bg-success-transparent">Operational</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Payment Gateway</span>
                                <span class="badge bg-success-transparent">Online</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Email Service</span>
                                <span class="badge bg-success-transparent">Active</span>
                            </div>
                        </div>
                        <div class="mb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Commission Processing</span>
                                <span class="badge bg-success-transparent">Running</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Information -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Quick Reference Guide</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <h6 class="fw-semibold mb-2">User Management</h6>
                                <ul class="list-unstyled">
                                    <li><i class="ri-arrow-right-s-line text-muted"></i> Add new members</li>
                                    <li><i class="ri-arrow-right-s-line text-muted"></i> Update ranks</li>
                                    <li><i class="ri-arrow-right-s-line text-muted"></i> Manage genealogy</li>
                                    <li><i class="ri-arrow-right-s-line text-muted"></i> Track commissions</li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <h6 class="fw-semibold mb-2">Product Management</h6>
                                <ul class="list-unstyled">
                                    <li><i class="ri-arrow-right-s-line text-muted"></i> Add products</li>
                                    <li><i class="ri-arrow-right-s-line text-muted"></i> Set PV points</li>
                                    <li><i class="ri-arrow-right-s-line text-muted"></i> Manage inventory</li>
                                    <li><i class="ri-arrow-right-s-line text-muted"></i> Configure pricing</li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <h6 class="fw-semibold mb-2">Order Processing</h6>
                                <ul class="list-unstyled">
                                    <li><i class="ri-arrow-right-s-line text-muted"></i> Process orders</li>
                                    <li><i class="ri-arrow-right-s-line text-muted"></i> Update status</li>
                                    <li><i class="ri-arrow-right-s-line text-muted"></i> Track shipping</li>
                                    <li><i class="ri-arrow-right-s-line text-muted"></i> Manage returns</li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <h6 class="fw-semibold mb-2">MLM Settings</h6>
                                <ul class="list-unstyled">
                                    <li><i class="ri-arrow-right-s-line text-muted"></i> Commission rates</li>
                                    <li><i class="ri-arrow-right-s-line text-muted"></i> Rank requirements</li>
                                    <li><i class="ri-arrow-right-s-line text-muted"></i> Bonus structures</li>
                                    <li><i class="ri-arrow-right-s-line text-muted"></i> Plan configuration</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
    // Support form submission
    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const subject = document.getElementById('support_subject').value;
        const priority = document.getElementById('priority').value;
        const message = document.getElementById('support_message').value;
        
        if (!subject || !message) {
            alert('Please fill in all required fields.');
            return;
        }
        
        // Here you would normally send the support request to the server
        alert('Support request submitted successfully! You will receive a response within 2 hours.');
        
        // Clear the form
        this.reset();
    });

    // Live chat simulation
    document.querySelector('.btn-info').addEventListener('click', function() {
        alert('Live chat feature will be available soon. Please use email or phone support for immediate assistance.');
    });
</script>
@endpush
@endsection
