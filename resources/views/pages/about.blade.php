@extends('layouts.app')

@section('title', 'About Us - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="display-4 text-center mb-5">About {{ config('app.name') }}</h1>
            
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h2 class="h3 mb-4">Welcome to Our MLM Ecommerce Platform</h2>
                    
                    <p class="lead">
                        {{ config('app.name') }} is a cutting-edge Multi-Level Marketing (MLM) ecommerce platform designed to empower entrepreneurs and create sustainable income opportunities through our innovative business model.
                    </p>
                    
                    <h3 class="h4 mt-4 mb-3">Our Mission</h3>
                    <p>
                        To provide a reliable, transparent, and profitable platform where individuals can build their own business networks while offering high-quality products to customers worldwide.
                    </p>
                    
                    <h3 class="h4 mt-4 mb-3">Why Choose Us?</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Proven Business Model</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Quality Products</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Fair Compensation Plan</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> 24/7 Support</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Training & Resources</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Global Reach</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Secure Platform</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Mobile-Friendly</li>
                            </ul>
                        </div>
                    </div>
                    
                    <h3 class="h4 mt-4 mb-3">Our Commitment</h3>
                    <p>
                        We are committed to maintaining the highest standards of integrity, transparency, and customer satisfaction. Our team works tirelessly to ensure that every member of our community has the tools and support they need to succeed.
                    </p>
                    
                    <div class="text-center mt-5">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Join Us Today</a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-primary btn-lg ms-3">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
