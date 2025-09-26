@extends('layouts.ecomus')

@section('title', 'Terms and Conditions - ' . config('app.name'))
@section('description', 'Read our terms and conditions for using ' . config('app.name'))

@section('content')
<!-- page-title -->
<div class="tf-page-title">
    <div class="container-full">
        <div class="heading text-center">Terms and Conditions</div>
        <p class="text-center text-secondary mt-2">Please read these terms carefully before using our services</p>
    </div>
</div>
<!-- /page-title -->

<!-- Section Terms -->
<section class="flat-spacing-11">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="content-terms">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <small class="text-muted">Last updated: {{ now()->format('F d, Y') }}</small>
                        <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">
                            <i class="icon icon-arrow-left"></i> Back to Home
                        </a>
                    </div>
                    
                    @if($settings && $settings->terms_conditions)
                        {!! $settings->terms_conditions !!}
                    @else
                        <div class="default-terms-content">
                            <h2>1. Acceptance of Terms</h2>
                            <p>By accessing and using {{ siteName() ?? config('app.name') }}, you accept and agree to be bound by the terms and provisions of this agreement.</p>
                            
                            <h2>2. Use of Service</h2>
                            <p>You may use our service for lawful purposes only. You agree not to use the service:</p>
                            <ul>
                                <li>In any way that violates any applicable federal, state, local, or international law or regulation</li>
                                <li>To transmit, or procure the sending of, any advertising or promotional material without our prior written consent</li>
                                <li>To impersonate or attempt to impersonate the company, a company employee, another user, or any other person or entity</li>
                            </ul>
                            
                            <h2>3. Shopping and Orders</h2>
                            <p>When you place an order through our website, you are offering to purchase a product subject to the following terms and conditions:</p>
                            <ul>
                                <li>All prices are subject to change without notice</li>
                                <li>We reserve the right to refuse or cancel any order</li>
                                <li>All orders are subject to product availability</li>
                                <li>We may require additional verification before accepting any order</li>
                            </ul>
                            
                            <h2>4. Payment Terms</h2>
                            <p>Payment is due at the time of purchase. We accept various payment methods including:</p>
                            <ul>
                                <li>Credit and debit cards</li>
                                <li>Mobile financial services (bKash, Nagad, Rocket)</li>
                                <li>Bank transfers</li>
                            </ul>
                            
                            <h2>5. Shipping and Delivery</h2>
                            <p>We strive to deliver your orders in a timely manner. Delivery times may vary based on your location and product availability.</p>
                            <ul>
                                <li>Standard delivery: 3-7 business days</li>
                                <li>Express delivery: 1-3 business days (where available)</li>
                                <li>Free shipping may be available for orders above a certain amount</li>
                            </ul>
                            
                            <h2>6. Returns and Refunds</h2>
                            <p>We want you to be satisfied with your purchase. If you're not completely satisfied, you may return eligible items within our return policy guidelines.</p>
                            
                            <h2>7. Privacy Policy</h2>
                            <p>Your privacy is important to us. Please review our Privacy Policy, which also governs your use of the service, to understand our practices.</p>
                            
                            <h2>8. Limitation of Liability</h2>
                            <p>In no event shall {{ siteName() ?? config('app.name') }}, nor its directors, employees, partners, agents, suppliers, or affiliates, be liable for any indirect, incidental, punitive, consequential, or similar damages.</p>
                            
                            <h2>9. Governing Law</h2>
                            <p>These Terms shall be interpreted and governed by the laws of Bangladesh, without regard to its conflict of law provisions.</p>
                            
                            <h2>10. Changes to Terms</h2>
                            <p>We reserve the right, at our sole discretion, to modify or replace these Terms at any time. If a revision is material, we will try to provide at least 30 days' notice prior to any new terms taking effect.</p>
                            
                            <h2>11. Contact Information</h2>
                            <p>If you have any questions about these Terms and Conditions, please contact us:</p>
                            <ul>
                                <li>Email: {{ $settings->contact_email ?? 'support@' . request()->getHost() }}</li>
                                <li>Phone: {{ $settings->contact_phone ?? 'Available on our contact page' }}</li>
                                @if($settings && $settings->contact_address)
                                    <li>Address: {{ $settings->contact_address }}</li>
                                @endif
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /Section Terms -->

<style>
.content-terms h2 {
    color: var(--primary-color, #007bff);
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-size: 1.5rem;
    font-weight: 600;
    border-bottom: 2px solid var(--primary-color, #007bff);
    padding-bottom: 0.5rem;
}

.content-terms h3 {
    color: var(--secondary-color, #6c757d);
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
    font-size: 1.25rem;
    font-weight: 500;
}

.content-terms p {
    margin-bottom: 1rem;
    line-height: 1.6;
}

.content-terms ul, .content-terms ol {
    margin-bottom: 1rem;
    padding-left: 2rem;
}

.content-terms ul li, .content-terms ol li {
    margin-bottom: 0.5rem;
    line-height: 1.5;
}

.default-terms-content {
    background-color: #f8f9fa;
    padding: 2rem;
    border-radius: 8px;
    border-left: 4px solid var(--primary-color, #007bff);
}

@media (max-width: 768px) {
    .content-terms {
        padding: 1rem;
    }
    
    .content-terms h2 {
        font-size: 1.25rem;
    }
    
    .content-terms h3 {
        font-size: 1.1rem;
    }
}
</style>
@endsection