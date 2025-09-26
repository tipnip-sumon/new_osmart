@extends('layouts.ecomus')

@section('title', 'Delivery Information - ' . config('app.name'))
@section('description', 'Learn about our delivery options, shipping times, and coverage areas')

@section('content')
<!-- page-title -->
<div class="tf-page-title">
    <div class="container-full">
        <div class="heading text-center">Delivery Information</div>
        <p class="text-center text-secondary mt-2">Fast, reliable delivery across Bangladesh</p>
    </div>
</div>
<!-- /page-title -->

<!-- Section Delivery -->
<section class="flat-spacing-11">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="delivery-content">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <small class="text-muted">Last updated: {{ now()->format('F d, Y') }}</small>
                        <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">
                            <i class="icon icon-arrow-left"></i> Back to Home
                        </a>
                    </div>

                    <div class="delivery-info">
                        <!-- Quick Overview -->
                        <div class="row mb-5">
                            <div class="col-md-4 mb-3">
                                <div class="delivery-highlight text-center">
                                    <div class="highlight-icon">
                                        <i class="icon icon-truck"></i>
                                    </div>
                                    <h4>Nationwide Delivery</h4>
                                    <p>We deliver to all 64 districts in Bangladesh</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="delivery-highlight text-center">
                                    <div class="highlight-icon">
                                        <i class="icon icon-clock"></i>
                                    </div>
                                    <h4>Fast Shipping</h4>
                                    <p>Express delivery available in major cities</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="delivery-highlight text-center">
                                    <div class="highlight-icon">
                                        <i class="icon icon-shield"></i>
                                    </div>
                                    <h4>Secure Packaging</h4>
                                    <p>Your items are safely packed and insured</p>
                                </div>
                            </div>
                        </div>

                        <h2>Delivery Options</h2>
                        <div class="delivery-options mb-5">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="delivery-option">
                                        <div class="option-header">
                                            <h4><i class="icon icon-zap text-warning me-2"></i>Express Delivery</h4>
                                            <span class="badge bg-warning">1-2 Days</span>
                                        </div>
                                        <p class="text-muted mb-3">Fast delivery for urgent orders</p>
                                        <ul>
                                            <li>Available in Dhaka, Chittagong, Sylhet</li>
                                            <li>Same-day delivery in select areas</li>
                                            <li>Priority handling and packaging</li>
                                            <li>Real-time tracking</li>
                                        </ul>
                                        <div class="pricing">
                                            <strong>Delivery Charge: ৳80-150</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="delivery-option">
                                        <div class="option-header">
                                            <h4><i class="icon icon-truck text-primary me-2"></i>Standard Delivery</h4>
                                            <span class="badge bg-primary">3-5 Days</span>
                                        </div>
                                        <p class="text-muted mb-3">Regular delivery for all areas</p>
                                        <ul>
                                            <li>Available nationwide</li>
                                            <li>Reliable and cost-effective</li>
                                            <li>SMS and email notifications</li>
                                            <li>Safe and secure packaging</li>
                                        </ul>
                                        <div class="pricing">
                                            <strong>Delivery Charge: ৳60-120</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2>Free Shipping Policy</h2>
                        <div class="free-shipping mb-5">
                            <div class="alert alert-success">
                                <h5><i class="icon icon-gift me-2"></i>Enjoy Free Shipping!</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Inside Dhaka:</h6>
                                        <ul class="mb-0">
                                            <li>Orders above ৳1,500 - Free standard delivery</li>
                                            <li>Orders above ৳3,000 - Free express delivery</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Outside Dhaka:</h6>
                                        <ul class="mb-0">
                                            <li>Orders above ৳2,000 - Free standard delivery</li>
                                            <li>Orders above ৳5,000 - Free express delivery</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2>Delivery Areas & Timeline</h2>
                        <div class="delivery-areas mb-5">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="area-card">
                                        <h5 class="text-success">Metro Areas</h5>
                                        <p class="small text-muted">Major cities with fast delivery</p>
                                        <ul>
                                            <li>Dhaka: 1-2 days</li>
                                            <li>Chittagong: 2-3 days</li>
                                            <li>Sylhet: 2-3 days</li>
                                            <li>Rajshahi: 2-3 days</li>
                                            <li>Khulna: 2-3 days</li>
                                            <li>Barisal: 3-4 days</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="area-card">
                                        <h5 class="text-info">District Towns</h5>
                                        <p class="small text-muted">All district headquarters</p>
                                        <ul>
                                            <li>All 64 districts covered</li>
                                            <li>Standard delivery: 3-5 days</li>
                                            <li>Express delivery: 2-4 days</li>
                                            <li>Regular courier services</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="area-card">
                                        <h5 class="text-warning">Remote Areas</h5>
                                        <p class="small text-muted">Rural and hard-to-reach locations</p>
                                        <ul>
                                            <li>Upazila and union levels</li>
                                            <li>Delivery: 5-7 days</li>
                                            <li>May require local coordination</li>
                                            <li>Additional charges may apply</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2>Order Processing</h2>
                        <div class="order-processing mb-5">
                            <div class="processing-steps">
                                <div class="row">
                                    <div class="col-md-2 mb-3">
                                        <div class="step-item">
                                            <div class="step-icon">1</div>
                                            <h6>Order Placed</h6>
                                            <p class="small">Confirmation email sent</p>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <div class="step-item">
                                            <div class="step-icon">2</div>
                                            <h6>Payment Verified</h6>
                                            <p class="small">1-2 hours processing</p>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <div class="step-item">
                                            <div class="step-icon">3</div>
                                            <h6>Order Prepared</h6>
                                            <p class="small">Quality check & packaging</p>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <div class="step-item">
                                            <div class="step-icon">4</div>
                                            <h6>Shipped</h6>
                                            <p class="small">Tracking info provided</p>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <div class="step-item">
                                            <div class="step-icon">5</div>
                                            <h6>Out for Delivery</h6>
                                            <p class="small">Final delivery notification</p>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <div class="step-item">
                                            <div class="step-icon">6</div>
                                            <h6>Delivered</h6>
                                            <p class="small">Enjoy your purchase!</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2>Packaging & Safety</h2>
                        <div class="packaging-info mb-5">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <h5><i class="icon icon-package text-primary me-2"></i>Secure Packaging</h5>
                                    <ul>
                                        <li>Bubble wrap for fragile items</li>
                                        <li>Waterproof packaging during monsoon</li>
                                        <li>Multiple layers for electronics</li>
                                        <li>Branded packaging for gifts</li>
                                    </ul>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <h5><i class="icon icon-shield-check text-success me-2"></i>Insurance & Protection</h5>
                                    <ul>
                                        <li>All shipments insured up to product value</li>
                                        <li>Damage protection during transit</li>
                                        <li>Photo verification at delivery</li>
                                        <li>Replacement guarantee for damages</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <h2>Tracking Your Order</h2>
                        <div class="tracking-info mb-5">
                            <p>Stay updated on your order status:</p>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <h6>Tracking Methods:</h6>
                                    <ul>
                                        <li>SMS notifications for status updates</li>
                                        <li>Email alerts at each milestone</li>
                                        <li>Online tracking via order ID</li>
                                        <li>Call our hotline for live updates</li>
                                    </ul>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <h6>Track via:</h6>
                                    <ul>
                                        <li><strong>Website:</strong> My Account → Orders</li>
                                        <li><strong>Phone:</strong> +880-1234-567890</li>
                                        <li><strong>Email:</strong> support@{{ request()->getHost() }}</li>
                                        <li><strong>WhatsApp:</strong> +880-1234-567890</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <h2>Special Delivery Services</h2>
                        <div class="special-services mb-5">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="service-card">
                                        <h5><i class="icon icon-calendar text-info me-2"></i>Scheduled Delivery</h5>
                                        <p>Choose your preferred delivery date and time slot</p>
                                        <ul>
                                            <li>Morning: 9 AM - 1 PM</li>
                                            <li>Afternoon: 2 PM - 6 PM</li>
                                            <li>Evening: 6 PM - 9 PM</li>
                                        </ul>
                                        <small class="text-muted">Additional ৳50 charge</small>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="service-card">
                                        <h5><i class="icon icon-gift text-danger me-2"></i>Gift Packaging</h5>
                                        <p>Beautiful gift wrapping and personal messages</p>
                                        <ul>
                                            <li>Premium gift boxes available</li>
                                            <li>Custom gift messages</li>
                                            <li>Special occasion packaging</li>
                                        </ul>
                                        <small class="text-muted">From ৳100 extra</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="contact-delivery">
                            <div class="alert alert-primary">
                                <h5><i class="icon icon-headphones me-2"></i>Need Help with Your Delivery?</h5>
                                <p class="mb-2">Our customer service team is ready to assist:</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul class="mb-0">
                                            <li><strong>Hotline:</strong> 16263 (24/7)</li>
                                            <li><strong>WhatsApp:</strong> +880-1234-567890</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="mb-0">
                                            <li><strong>Email:</strong> delivery@{{ request()->getHost() }}</li>
                                            <li><strong>Live Chat:</strong> 9 AM - 10 PM daily</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /Section Delivery -->

<style>
.delivery-highlight {
    padding: 2rem 1rem;
    border-radius: 8px;
    background: #f8f9fa;
    height: 100%;
}

.highlight-icon {
    width: 80px;
    height: 80px;
    background: var(--primary-color, #007bff);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 2rem;
    color: white;
}

.delivery-option {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 1.5rem;
    height: 100%;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.option-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 1rem;
}

.option-header h4 {
    margin: 0;
    flex: 1;
}

.pricing {
    background: #f8f9fa;
    padding: 0.75rem;
    border-radius: 4px;
    text-align: center;
    margin-top: 1rem;
}

.area-card {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 1.5rem;
    height: 100%;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.step-item {
    text-align: center;
    padding: 1rem 0.5rem;
}

.step-icon {
    width: 40px;
    height: 40px;
    background: var(--primary-color, #007bff);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.75rem;
    font-weight: bold;
}

.service-card {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 1.5rem;
    height: 100%;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

@media (max-width: 768px) {
    .delivery-content {
        padding: 1rem;
    }
    
    .option-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .step-item {
        padding: 1rem 0.25rem;
    }
}
</style>
@endsection