@extends('layouts.app')

@section('title', 'Vendor Partnership Program')

@section('content')
<div class="page-content-wrapper">
    <div class="container">
        <!-- Hero Section -->
        <div class="row">
            <div class="col-12">
                <div class="hero-wrapper">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-12 col-lg-6">
                                <div class="hero-content">
                                    <h1 class="display-4 mb-3">Become a Vendor Partner</h1>
                                    <p class="lead mb-4">Join our marketplace and reach thousands of customers worldwide. Start selling your products with our comprehensive vendor platform.</p>
                                    <div class="hero-btn-group">
                                        <a class="btn btn-primary btn-lg me-2" href="{{ route('vendor.register') }}">Apply Now</a>
                                        <a class="btn btn-outline-primary btn-lg" href="{{ route('contact.show') }}">Contact Us</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="hero-thumbnail">
                                    <img src="{{ asset('assets/img/bg-img/vendor-hero.jpg') }}" alt="Vendor Partnership">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Benefits Section -->
        <div class="row mt-5">
            <div class="col-12">
                <h2 class="text-center mb-4">Why Partner With Us?</h2>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="feature-card h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon">
                            <i class="ti ti-users fs-1 text-primary"></i>
                        </div>
                        <h5 class="card-title">Large Customer Base</h5>
                        <p class="card-text">Access to thousands of active customers looking for quality products like yours.</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="feature-card h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon">
                            <i class="ti ti-chart-line fs-1 text-success"></i>
                        </div>
                        <h5 class="card-title">Increase Sales</h5>
                        <p class="card-text">Boost your revenue with our proven marketing strategies and promotional tools.</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="feature-card h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon">
                            <i class="ti ti-headset fs-1 text-info"></i>
                        </div>
                        <h5 class="card-title">Dedicated Support</h5>
                        <p class="card-text">Get dedicated vendor support to help you succeed on our platform.</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="feature-card h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon">
                            <i class="ti ti-shield-check fs-1 text-warning"></i>
                        </div>
                        <h5 class="card-title">Secure Payments</h5>
                        <p class="card-text">Secure and timely payment processing with multiple payment options.</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="feature-card h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon">
                            <i class="ti ti-device-analytics fs-1 text-danger"></i>
                        </div>
                        <h5 class="card-title">Analytics & Reports</h5>
                        <p class="card-text">Comprehensive analytics to track your performance and optimize sales.</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="feature-card h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon">
                            <i class="ti ti-truck-delivery fs-1 text-secondary"></i>
                        </div>
                        <h5 class="card-title">Easy Shipping</h5>
                        <p class="card-text">Integrated shipping solutions to make order fulfillment seamless.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- How It Works -->
        <div class="row mt-5">
            <div class="col-12">
                <h2 class="text-center mb-4">How It Works</h2>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-md-4">
                <div class="step-card text-center">
                    <div class="step-number">1</div>
                    <h5>Apply</h5>
                    <p>Submit your vendor application with business details and product information.</p>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="step-card text-center">
                    <div class="step-number">2</div>
                    <h5>Review</h5>
                    <p>Our team reviews your application and verifies your business credentials.</p>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="step-card text-center">
                    <div class="step-number">3</div>
                    <h5>Start Selling</h5>
                    <p>Once approved, set up your store and start listing your products to reach customers.</p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="cta-section text-center bg-primary text-white p-5 rounded">
                    <h3>Ready to Start Your Vendor Journey?</h3>
                    <p class="lead mb-4">Join hundreds of successful vendors already growing their business with us.</p>
                    <a class="btn btn-light btn-lg" href="{{ route('vendor.register') }}">Apply for Vendor Account</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.feature-card {
    border: 1px solid #e9ecef;
    border-radius: 10px;
    padding: 20px;
    transition: all 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.feature-icon {
    margin-bottom: 20px;
}

.step-card {
    padding: 30px 20px;
}

.step-number {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #34495e;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: bold;
    margin: 0 auto 20px;
}

.cta-section {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%) !important;
}
</style>
@endsection
