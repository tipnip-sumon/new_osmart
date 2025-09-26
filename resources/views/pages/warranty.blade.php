@extends('layouts.ecomus')

@section('title', 'Warranty Information - ' . config('app.name'))
@section('description', 'Learn about our product warranty policies and coverage')

@section('content')
<!-- page-title -->
<div class="tf-page-title">
    <div class="container-full">
        <div class="heading text-center">Warranty Information</div>
        <p class="text-center text-secondary mt-2">Your peace of mind is our priority</p>
    </div>
</div>
<!-- /page-title -->

<!-- Section Warranty -->
<section class="flat-spacing-11">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="warranty-content">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <small class="text-muted">Last updated: {{ now()->format('F d, Y') }}</small>
                        <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">
                            <i class="icon icon-arrow-left"></i> Back to Home
                        </a>
                    </div>

                    <div class="warranty-info">
                        <div class="alert alert-success mb-4">
                            <h5><i class="icon icon-check-circle me-2"></i>Comprehensive Warranty Coverage</h5>
                            <p class="mb-0">All products purchased from {{ siteName() ?? config('app.name') }} come with manufacturer warranty and our additional protection guarantee.</p>
                        </div>

                        <h2>Warranty Coverage</h2>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <div class="warranty-card">
                                    <h4><i class="icon icon-smartphone text-primary me-2"></i>Electronics</h4>
                                    <ul>
                                        <li>Smartphones: 12-24 months</li>
                                        <li>Laptops: 12-36 months</li>
                                        <li>Accessories: 6-12 months</li>
                                        <li>Smart devices: 12 months</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="warranty-card">
                                    <h4><i class="icon icon-shirt text-primary me-2"></i>Fashion Items</h4>
                                    <ul>
                                        <li>Clothing: 30 days quality guarantee</li>
                                        <li>Shoes: 90 days manufacturing defects</li>
                                        <li>Bags: 6 months material warranty</li>
                                        <li>Jewelry: 12 months craftsmanship</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <h2>What's Covered</h2>
                        <div class="covered-items mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="text-success"><i class="icon icon-check me-2"></i>Manufacturing Defects</h5>
                                    <ul>
                                        <li>Material defects</li>
                                        <li>Workmanship issues</li>
                                        <li>Factory defects</li>
                                        <li>Component failures</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-success"><i class="icon icon-check me-2"></i>Functional Issues</h5>
                                    <ul>
                                        <li>Performance problems</li>
                                        <li>Software malfunctions</li>
                                        <li>Battery issues</li>
                                        <li>Hardware failures</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <h2>What's NOT Covered</h2>
                        <div class="not-covered-items mb-4">
                            <div class="alert alert-warning">
                                <h5><i class="icon icon-warning me-2"></i>Exclusions</h5>
                                <ul class="mb-0">
                                    <li>Physical damage due to accidents or misuse</li>
                                    <li>Water damage (unless specifically waterproof)</li>
                                    <li>Normal wear and tear</li>
                                    <li>Damage from unauthorized repairs</li>
                                    <li>Software issues caused by user modifications</li>
                                    <li>Cosmetic damage that doesn't affect functionality</li>
                                </ul>
                            </div>
                        </div>

                        <h2>How to Claim Warranty</h2>
                        <div class="warranty-process mb-4">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <div class="process-step">
                                        <div class="step-number">1</div>
                                        <h5>Contact Us</h5>
                                        <p>Reach out via email, phone, or support chat with your order details.</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="process-step">
                                        <div class="step-number">2</div>
                                        <h5>Provide Details</h5>
                                        <p>Share photos/videos of the issue and your purchase receipt.</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="process-step">
                                        <div class="step-number">3</div>
                                        <h5>Assessment</h5>
                                        <p>Our team will review your case and determine the best solution.</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="process-step">
                                        <div class="step-number">4</div>
                                        <h5>Resolution</h5>
                                        <p>Get repair, replacement, or refund based on the warranty terms.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2>Required Information</h2>
                        <div class="required-info mb-4">
                            <p>To process your warranty claim, please have the following ready:</p>
                            <ul>
                                <li>Order number or receipt</li>
                                <li>Product serial number (if applicable)</li>
                                <li>Clear photos or videos showing the issue</li>
                                <li>Purchase date and location</li>
                                <li>Description of the problem</li>
                            </ul>
                        </div>

                        <h2>Response Times</h2>
                        <div class="response-times mb-4">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="time-card text-center">
                                        <h4 class="text-primary">24 Hours</h4>
                                        <p>Initial Response</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="time-card text-center">
                                        <h4 class="text-primary">2-3 Days</h4>
                                        <p>Assessment Complete</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="time-card text-center">
                                        <h4 class="text-primary">5-7 Days</h4>
                                        <p>Resolution</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2>Extended Warranty Options</h2>
                        <div class="extended-warranty mb-4">
                            <p>For additional peace of mind, consider our extended warranty plans:</p>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="warranty-plan">
                                        <h5>Premium Protection</h5>
                                        <ul>
                                            <li>Extended coverage up to 3 years</li>
                                            <li>Accidental damage protection</li>
                                            <li>Priority support</li>
                                            <li>Free pickup and delivery</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="warranty-plan">
                                        <h5>Complete Care</h5>
                                        <ul>
                                            <li>All Premium Protection benefits</li>
                                            <li>Liquid damage coverage</li>
                                            <li>Replacement device during repair</li>
                                            <li>Annual health check-up</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="contact-warranty">
                            <div class="alert alert-info">
                                <h5><i class="icon icon-phone me-2"></i>Need Help with Your Warranty?</h5>
                                <p class="mb-2">Our customer support team is here to assist you:</p>
                                <ul class="mb-0">
                                    <li><strong>Email:</strong> warranty@{{ request()->getHost() }}</li>
                                    <li><strong>Phone:</strong> +880-1234-567890</li>
                                    <li><strong>Live Chat:</strong> Available 9 AM - 6 PM (Sun-Thu)</li>
                                    <li><strong>Support Hours:</strong> 24/7 for urgent issues</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /Section Warranty -->

<style>
.warranty-content h2 {
    color: var(--primary-color, #007bff);
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-size: 1.5rem;
    font-weight: 600;
    border-bottom: 2px solid var(--primary-color, #007bff);
    padding-bottom: 0.5rem;
}

.warranty-card {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    border-left: 4px solid var(--primary-color, #007bff);
    height: 100%;
}

.warranty-card h4 {
    color: var(--primary-color, #007bff);
    margin-bottom: 1rem;
}

.process-step {
    text-align: center;
    padding: 1rem;
}

.step-number {
    width: 50px;
    height: 50px;
    background: var(--primary-color, #007bff);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.2rem;
    margin: 0 auto 1rem;
}

.time-card {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.warranty-plan {
    background: #fff;
    padding: 1.5rem;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.warranty-plan h5 {
    color: var(--success-color, #28a745);
    margin-bottom: 1rem;
    font-weight: 600;
}

@media (max-width: 768px) {
    .warranty-content {
        padding: 1rem;
    }
    
    .warranty-content h2 {
        font-size: 1.25rem;
    }
    
    .process-step {
        padding: 1rem 0.5rem;
    }
}
</style>
@endsection