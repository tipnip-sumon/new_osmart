@extends('layouts.ecomus')

@section('title', 'Customer Support - ' . config('app.name'))
@section('description', '24/7 customer support for all your shopping needs')

@section('content')
<!-- page-title -->
<div class="tf-page-title">
    <div class="container-full">
        <div class="heading text-center">Customer Support</div>
        <p class="text-center text-secondary mt-2">We're here to help you 24/7</p>
    </div>
</div>
<!-- /page-title -->

<!-- Section Support -->
<section class="flat-spacing-11">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="support-content">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <small class="text-muted">Support available 24/7</small>
                        <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">
                            <i class="icon icon-arrow-left"></i> Back to Home
                        </a>
                    </div>

                    <!-- Quick Contact -->
                    <div class="quick-contact mb-5">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="contact-method text-center">
                                    <div class="contact-icon">
                                        <i class="icon icon-phone"></i>
                                    </div>
                                    <h5>Call Us</h5>
                                    <p>16263 (Toll Free)</p>
                                    <small>24/7 Available</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="contact-method text-center">
                                    <div class="contact-icon">
                                        <i class="icon icon-message-circle"></i>
                                    </div>
                                    <h5>Live Chat</h5>
                                    <p>Instant Support</p>
                                    <small>9 AM - 10 PM</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="contact-method text-center">
                                    <div class="contact-icon">
                                        <i class="icon icon-mail"></i>
                                    </div>
                                    <h5>Email Us</h5>
                                    <p>support@{{ request()->getHost() }}</p>
                                    <small>Reply within 2 hours</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="contact-method text-center">
                                    <div class="contact-icon whatsapp">
                                        <i class="fab fa-whatsapp"></i>
                                    </div>
                                    <h5>WhatsApp</h5>
                                    <p>+880-1234-567890</p>
                                    <small>Quick responses</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h2>Frequently Asked Questions</h2>
                    <div class="faq-section mb-5">
                        <div class="accordion" id="supportFaq">
                            <!-- Order Related -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="orderHeading">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#orderCollapse">
                                        <i class="icon icon-shopping-cart me-2"></i>
                                        Order Related Questions
                                    </button>
                                </h2>
                                <div id="orderCollapse" class="accordion-collapse collapse show" data-bs-parent="#supportFaq">
                                    <div class="accordion-body">
                                        <div class="faq-item">
                                            <h6>How can I track my order?</h6>
                                            <p>You can track your order by visiting 'My Account' → 'Orders' and clicking on your order number. You'll also receive SMS and email updates.</p>
                                        </div>
                                        <div class="faq-item">
                                            <h6>Can I cancel my order?</h6>
                                            <p>Yes, you can cancel your order within 2 hours of placing it. After that, please contact customer support for assistance.</p>
                                        </div>
                                        <div class="faq-item">
                                            <h6>How do I modify my order?</h6>
                                            <p>Order modifications are possible within 1 hour of placing the order. Contact us immediately for changes.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="paymentHeading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#paymentCollapse">
                                        <i class="icon icon-credit-card me-2"></i>
                                        Payment & Billing
                                    </button>
                                </h2>
                                <div id="paymentCollapse" class="accordion-collapse collapse" data-bs-parent="#supportFaq">
                                    <div class="accordion-body">
                                        <div class="faq-item">
                                            <h6>What payment methods do you accept?</h6>
                                            <p>We accept bKash, Nagad, Rocket, bank transfers, credit/debit cards, and cash on delivery.</p>
                                        </div>
                                        <div class="faq-item">
                                            <h6>Is my payment information secure?</h6>
                                            <p>Yes, we use industry-standard encryption to protect all payment information.</p>
                                        </div>
                                        <div class="faq-item">
                                            <h6>When will my payment be charged?</h6>
                                            <p>Payment is processed immediately upon order confirmation.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Shipping -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="shippingHeading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#shippingCollapse">
                                        <i class="icon icon-truck me-2"></i>
                                        Shipping & Delivery
                                    </button>
                                </h2>
                                <div id="shippingCollapse" class="accordion-collapse collapse" data-bs-parent="#supportFaq">
                                    <div class="accordion-body">
                                        <div class="faq-item">
                                            <h6>How much does shipping cost?</h6>
                                            <p>Shipping costs vary by location (৳60-150). Free shipping available on orders above ৳1,500 in Dhaka.</p>
                                        </div>
                                        <div class="faq-item">
                                            <h6>How long does delivery take?</h6>
                                            <p>Standard delivery: 3-5 days. Express delivery: 1-2 days in major cities.</p>
                                        </div>
                                        <div class="faq-item">
                                            <h6>Do you deliver to my area?</h6>
                                            <p>We deliver nationwide to all 64 districts in Bangladesh.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Returns -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="returnsHeading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#returnsCollapse">
                                        <i class="icon icon-refresh-ccw me-2"></i>
                                        Returns & Refunds
                                    </button>
                                </h2>
                                <div id="returnsCollapse" class="accordion-collapse collapse" data-bs-parent="#supportFaq">
                                    <div class="accordion-body">
                                        <div class="faq-item">
                                            <h6>What is your return policy?</h6>
                                            <p>We offer 7-day returns for most items. Items must be unused and in original packaging.</p>
                                        </div>
                                        <div class="faq-item">
                                            <h6>How do I return an item?</h6>
                                            <p>Contact customer support to initiate a return. We'll arrange pickup from your location.</p>
                                        </div>
                                        <div class="faq-item">
                                            <h6>When will I get my refund?</h6>
                                            <p>Refunds are processed within 5-7 business days after we receive the returned item.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h2>Support Categories</h2>
                    <div class="support-categories mb-5">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="category-card">
                                    <h5><i class="icon icon-shopping-bag text-primary me-2"></i>Order Support</h5>
                                    <ul>
                                        <li>Order tracking</li>
                                        <li>Order modifications</li>
                                        <li>Delivery updates</li>
                                        <li>Order cancellation</li>
                                    </ul>
                                    <a href="#" class="btn btn-outline-primary btn-sm">Get Help</a>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="category-card">
                                    <h5><i class="icon icon-settings text-success me-2"></i>Technical Support</h5>
                                    <ul>
                                        <li>Website issues</li>
                                        <li>Account problems</li>
                                        <li>Payment failures</li>
                                        <li>App troubleshooting</li>
                                    </ul>
                                    <a href="#" class="btn btn-outline-success btn-sm">Get Help</a>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="category-card">
                                    <h5><i class="icon icon-info text-info me-2"></i>Product Support</h5>
                                    <ul>
                                        <li>Product information</li>
                                        <li>Size guides</li>
                                        <li>Warranty claims</li>
                                        <li>Usage instructions</li>
                                    </ul>
                                    <a href="#" class="btn btn-outline-info btn-sm">Get Help</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h2>Live Chat Widget</h2>
                    <div class="live-chat-section mb-5">
                        <div class="chat-widget">
                            <div class="chat-header">
                                <h5><i class="icon icon-message-circle me-2"></i>Live Support Chat</h5>
                                <span class="badge bg-success">Online</span>
                            </div>
                            <div class="chat-body">
                                <p>Start a conversation with our support team. Average response time: 2 minutes</p>
                                <div class="chat-features">
                                    <div class="row">
                                        <div class="col-6">
                                            <ul class="list-unstyled">
                                                <li><i class="icon icon-check text-success me-1"></i> Instant responses</li>
                                                <li><i class="icon icon-check text-success me-1"></i> Screen sharing support</li>
                                            </ul>
                                        </div>
                                        <div class="col-6">
                                            <ul class="list-unstyled">
                                                <li><i class="icon icon-check text-success me-1"></i> File attachments</li>
                                                <li><i class="icon icon-check text-success me-1"></i> Order assistance</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary w-100">
                                    <i class="icon icon-message-circle me-2"></i>Start Live Chat
                                </button>
                            </div>
                        </div>
                    </div>

                    <h2>Self-Service Options</h2>
                    <div class="self-service mb-5">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h5><i class="icon icon-book text-primary me-2"></i>Help Center</h5>
                                <p>Browse our comprehensive knowledge base for instant answers.</p>
                                <ul>
                                    <li>Step-by-step guides</li>
                                    <li>Video tutorials</li>
                                    <li>Troubleshooting tips</li>
                                    <li>Product manuals</li>
                                </ul>
                                <a href="#" class="btn btn-outline-primary">Visit Help Center</a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h5><i class="icon icon-user text-success me-2"></i>My Account</h5>
                                <p>Manage your orders, profile, and preferences independently.</p>
                                <ul>
                                    <li>Order history and tracking</li>
                                    <li>Profile management</li>
                                    <li>Address book</li>
                                    <li>Wishlist management</li>
                                </ul>
                                <a href="{{ route('profile.show') }}" class="btn btn-outline-success">Go to Account</a>
                            </div>
                        </div>
                    </div>

                    <h2>Escalation Process</h2>
                    <div class="escalation-process mb-5">
                        <div class="alert alert-info">
                            <h5><i class="icon icon-alert-circle me-2"></i>If Your Issue Isn't Resolved</h5>
                            <p class="mb-3">We're committed to resolving every issue. If you're not satisfied with the initial support:</p>
                            <ol>
                                <li><strong>Supervisor Review:</strong> Request to speak with a supervisor</li>
                                <li><strong>Management Escalation:</strong> Email: manager@{{ request()->getHost() }}</li>
                                <li><strong>Executive Complaint:</strong> For serious issues: executive@{{ request()->getHost() }}</li>
                                <li><strong>Feedback:</strong> Share your experience to help us improve</li>
                            </ol>
                        </div>
                    </div>

                    <div class="contact-hours">
                        <div class="alert alert-primary">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6><i class="icon icon-clock me-2"></i>Support Hours</h6>
                                    <ul class="mb-0">
                                        <li><strong>Phone:</strong> 24/7</li>
                                        <li><strong>Live Chat:</strong> 9 AM - 10 PM</li>
                                        <li><strong>Email:</strong> 24/7 (2-hour response)</li>
                                        <li><strong>WhatsApp:</strong> 24/7</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6><i class="icon icon-map-pin me-2"></i>Office Address</h6>
                                    <p class="mb-0">
                                        {{ config('app.name') }} Customer Care<br>
                                        Level 5, House 10, Road 5<br>
                                        Dhanmondi, Dhaka 1205<br>
                                        <strong>Hotline:</strong> 16263
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /Section Support -->

<style>
.contact-method {
    padding: 1.5rem 1rem;
    border-radius: 8px;
    background: #f8f9fa;
    height: 100%;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.contact-method:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.contact-icon {
    width: 60px;
    height: 60px;
    background: var(--primary-color, #007bff);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
    color: white;
}

.contact-icon.whatsapp {
    background: #25d366;
}

.accordion-button {
    background: #f8f9fa;
    border: none;
    font-weight: 600;
}

.accordion-button:not(.collapsed) {
    color: var(--primary-color, #007bff);
    background: #e3f2fd;
}

.faq-item {
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #f0f0f0;
}

.faq-item:last-child {
    border-bottom: none;
}

.faq-item h6 {
    color: var(--primary-color, #007bff);
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.category-card {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 1.5rem;
    height: 100%;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.category-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}

.chat-widget {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    overflow: hidden;
    max-width: 400px;
    margin: 0 auto;
}

.chat-header {
    background: var(--primary-color, #007bff);
    color: white;
    padding: 1rem;
    display: flex;
    justify-content: between;
    align-items: center;
}

.chat-header h5 {
    margin: 0;
    flex: 1;
}

.chat-body {
    padding: 1.5rem;
    background: white;
}

.chat-features {
    margin: 1rem 0;
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .support-content {
        padding: 1rem;
    }
    
    .contact-method {
        margin-bottom: 1rem;
    }
    
    .chat-widget {
        max-width: 100%;
    }
}
</style>
@endsection