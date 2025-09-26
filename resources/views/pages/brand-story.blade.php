@extends('layouts.ecomus')

@section('title', 'Brand Story - ' . config('app.name'))
@section('description', 'Learn about our journey, values, and commitment to quality')

@section('content')
<!-- page-title -->
<div class="tf-page-title">
    <div class="container-full">
        <div class="heading text-center">Our Brand Story</div>
        <p class="text-center text-secondary mt-2">From humble beginnings to trusted marketplace</p>
    </div>
</div>
<!-- /page-title -->

<!-- Section Brand Story -->
<section class="flat-spacing-11">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="brand-story-content">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <small class="text-muted">Est. 2020</small>
                        <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">
                            <i class="icon icon-arrow-left"></i> Back to Home
                        </a>
                    </div>

                    <!-- Hero Section -->
                    <div class="story-hero mb-5">
                        <div class="row align-items-center">
                            <div class="col-md-6 mb-4">
                                <h2 class="display-5 mb-3">Building Bangladesh's Digital Marketplace</h2>
                                <p class="lead">{{ siteName() ?? config('app.name') }} was born from a simple vision: to make quality products accessible to every Bangladeshi, whether they're in bustling Dhaka or remote villages.</p>
                                <p>Today, we've grown into one of Bangladesh's most trusted e-commerce platforms, serving millions of customers across all 64 districts.</p>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="story-image">
                                    <img src="{{ asset('assets/img/brand/brand-story.jpg') }}" alt="Brand Story" class="img-fluid rounded-lg shadow-sm" onerror="this.src='https://via.placeholder.com/500x400/f8f9fa/6c757d?text=Brand+Story';">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Our Journey -->
                    <div class="our-journey mb-5">
                        <h2>Our Journey</h2>
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-year">2020</div>
                                <div class="timeline-content">
                                    <h4>The Beginning</h4>
                                    <p>Started as a small team with big dreams in a shared office space in Dhaka. Our first order was a mobile phone to a customer in Chittagong.</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">2021</div>
                                <div class="timeline-content">
                                    <h4>Rapid Growth</h4>
                                    <p>Expanded to 20+ districts, launched our mobile app, and introduced cash-on-delivery services. Reached 10,000+ happy customers.</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">2022</div>
                                <div class="timeline-content">
                                    <h4>Nationwide Expansion</h4>
                                    <p>Achieved nationwide delivery coverage, partnered with local vendors, and introduced our affiliate program. 100,000+ orders delivered.</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">2023</div>
                                <div class="timeline-content">
                                    <h4>Digital Innovation</h4>
                                    <p>Launched advanced features like live chat, product reviews, and AI-powered recommendations. Became a trusted name in e-commerce.</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">2024</div>
                                <div class="timeline-content">
                                    <h4>Community Focus</h4>
                                    <p>Introduced MLM program, vendor partnerships, and community-driven initiatives. Supporting local businesses across Bangladesh.</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">2025</div>
                                <div class="timeline-content">
                                    <h4>Future Forward</h4>
                                    <p>Continuing innovation with sustainability focus, blockchain integration, and expanding into new product categories.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Our Mission & Vision -->
                    <div class="mission-vision mb-5">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="mission-card">
                                    <div class="card-icon">
                                        <i class="icon icon-target"></i>
                                    </div>
                                    <h3>Our Mission</h3>
                                    <p>To democratize access to quality products and services across Bangladesh, empowering both consumers and entrepreneurs through technology and trust.</p>
                                    <ul>
                                        <li>Making quality products accessible</li>
                                        <li>Supporting local businesses</li>
                                        <li>Creating economic opportunities</li>
                                        <li>Building trust through transparency</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="vision-card">
                                    <div class="card-icon">
                                        <i class="icon icon-eye"></i>
                                    </div>
                                    <h3>Our Vision</h3>
                                    <p>To become Bangladesh's most trusted and innovative digital marketplace, bridging the gap between urban and rural commerce while fostering inclusive growth.</p>
                                    <ul>
                                        <li>Leading digital marketplace</li>
                                        <li>Inclusive economic growth</li>
                                        <li>Technology-driven solutions</li>
                                        <li>Sustainable business practices</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Our Values -->
                    <div class="our-values mb-5">
                        <h2>Our Core Values</h2>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="value-item text-center">
                                    <div class="value-icon">
                                        <i class="icon icon-shield-check"></i>
                                    </div>
                                    <h4>Trust & Integrity</h4>
                                    <p>Every transaction, every interaction, and every decision is guided by honesty and transparency.</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="value-item text-center">
                                    <div class="value-icon">
                                        <i class="icon icon-users"></i>
                                    </div>
                                    <h4>Customer First</h4>
                                    <p>Our customers' success and satisfaction drive everything we do, from product selection to service delivery.</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="value-item text-center">
                                    <div class="value-icon">
                                        <i class="icon icon-zap"></i>
                                    </div>
                                    <h4>Innovation</h4>
                                    <p>We constantly evolve and embrace new technologies to serve our community better.</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="value-item text-center">
                                    <div class="value-icon">
                                        <i class="icon icon-heart"></i>
                                    </div>
                                    <h4>Community Care</h4>
                                    <p>We believe in giving back and supporting the communities that have supported us.</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="value-item text-center">
                                    <div class="value-icon">
                                        <i class="icon icon-award"></i>
                                    </div>
                                    <h4>Quality Excellence</h4>
                                    <p>We never compromise on quality, from products we sell to services we provide.</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="value-item text-center">
                                    <div class="value-icon">
                                        <i class="icon icon-globe"></i>
                                    </div>
                                    <h4>Inclusive Growth</h4>
                                    <p>We create opportunities for everyone, regardless of location or background.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Impact Numbers -->
                    <div class="impact-numbers mb-5">
                        <h2>Our Impact in Numbers</h2>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="number-card text-center">
                                    <h3 class="counter-number">500K+</h3>
                                    <p>Happy Customers</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="number-card text-center">
                                    <h3 class="counter-number">50K+</h3>
                                    <p>Products Available</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="number-card text-center">
                                    <h3 class="counter-number">64</h3>
                                    <p>Districts Covered</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="number-card text-center">
                                    <h3 class="counter-number">5000+</h3>
                                    <p>Partner Vendors</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Team Section -->
                    <div class="our-team mb-5">
                        <h2>Meet Our Leadership</h2>
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <div class="team-member text-center">
                                    <div class="member-photo">
                                        <img src="{{ asset('assets/img/team/ceo.jpg') }}" alt="CEO" class="img-fluid rounded-circle" onerror="this.src='https://via.placeholder.com/200x200/f8f9fa/6c757d?text=CEO';">
                                    </div>
                                    <h4>Md. Rahman Ahmed</h4>
                                    <p class="text-muted">Chief Executive Officer</p>
                                    <p class="small">"Our vision is to create a platform where every Bangladeshi can access quality products and build their dreams."</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="team-member text-center">
                                    <div class="member-photo">
                                        <img src="{{ asset('assets/img/team/cto.jpg') }}" alt="CTO" class="img-fluid rounded-circle" onerror="this.src='https://via.placeholder.com/200x200/f8f9fa/6c757d?text=CTO';">
                                    </div>
                                    <h4>Fatema Khatun</h4>
                                    <p class="text-muted">Chief Technology Officer</p>
                                    <p class="small">"Technology should serve humanity, making life easier and opportunities more accessible."</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="team-member text-center">
                                    <div class="member-photo">
                                        <img src="{{ asset('assets/img/team/coo.jpg') }}" alt="COO" class="img-fluid rounded-circle" onerror="this.src='https://via.placeholder.com/200x200/f8f9fa/6c757d?text=COO';">
                                    </div>
                                    <h4>Karim Hassan</h4>
                                    <p class="text-muted">Chief Operations Officer</p>
                                    <p class="small">"Operational excellence and customer satisfaction are the pillars of our success."</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sustainability -->
                    <div class="sustainability mb-5">
                        <h2>Our Commitment to Sustainability</h2>
                        <div class="row align-items-center">
                            <div class="col-md-6 mb-4">
                                <h4>Environmental Responsibility</h4>
                                <p>We're committed to reducing our environmental impact while growing our business:</p>
                                <ul>
                                    <li><strong>Eco-friendly Packaging:</strong> Biodegradable and recyclable materials</li>
                                    <li><strong>Carbon Neutral Shipping:</strong> Offsetting delivery emissions</li>
                                    <li><strong>Digital First:</strong> Reducing paper usage through digital receipts</li>
                                    <li><strong>Local Sourcing:</strong> Supporting local vendors to reduce transport</li>
                                    <li><strong>Waste Reduction:</strong> Minimizing packaging waste</li>
                                </ul>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="sustainability-image">
                                    <img src="{{ asset('assets/img/brand/sustainability.jpg') }}" alt="Sustainability" class="img-fluid rounded shadow-sm" onerror="this.src='https://via.placeholder.com/400x300/28a745/ffffff?text=Sustainability';">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Future Plans -->
                    <div class="future-plans mb-5">
                        <div class="alert alert-primary">
                            <h4><i class="icon icon-trending-up me-2"></i>Looking Ahead</h4>
                            <p class="mb-3">Our roadmap for the next few years includes exciting developments:</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="mb-0">
                                        <li>AI-powered personalized shopping</li>
                                        <li>Blockchain-based supply chain transparency</li>
                                        <li>Drone delivery in urban areas</li>
                                        <li>Virtual try-on experiences</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="mb-0">
                                        <li>Expanded financial services</li>
                                        <li>Cross-border e-commerce</li>
                                        <li>Enhanced vendor support programs</li>
                                        <li>Community-driven marketplace features</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Join Our Journey -->
                    <div class="join-journey text-center">
                        <h3>Join Our Journey</h3>
                        <p class="lead">Be part of Bangladesh's digital commerce revolution</p>
                        <p>Whether you're a customer looking for quality products, a vendor wanting to reach new markets, or someone interested in our affiliate program, there's a place for you in our community.</p>
                        <div class="cta-buttons mt-4">
                            <a href="{{ route('register') }}" class="btn btn-primary me-3">
                                <i class="icon icon-user-plus me-2"></i>Join as Customer
                            </a>
                            <a href="{{ route('affiliate.info') }}" class="btn btn-outline-primary me-3">
                                <i class="icon icon-trending-up me-2"></i>Become Affiliate
                            </a>
                            <a href="{{ route('contact.show') }}" class="btn btn-outline-secondary">
                                <i class="icon icon-mail me-2"></i>Partner with Us
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /Section Brand Story -->

<style>
.story-hero h2 {
    color: var(--primary-color, #007bff);
    font-weight: 700;
}

.timeline {
    position: relative;
    padding: 2rem 0;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 50%;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--primary-color, #007bff);
    transform: translateX(-50%);
}

.timeline-item {
    position: relative;
    margin-bottom: 3rem;
    display: flex;
    align-items: center;
}

.timeline-item:nth-child(odd) {
    flex-direction: row-reverse;
    text-align: right;
}

.timeline-year {
    background: var(--primary-color, #007bff);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-weight: bold;
    margin: 0 2rem;
    min-width: 80px;
    text-align: center;
    position: relative;
    z-index: 2;
}

.timeline-content {
    flex: 1;
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.timeline-content h4 {
    color: var(--primary-color, #007bff);
    margin-bottom: 0.5rem;
}

.mission-card, .vision-card {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    height: 100%;
    border-top: 4px solid var(--primary-color, #007bff);
}

.card-icon {
    width: 80px;
    height: 80px;
    background: var(--primary-color, #007bff);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
    font-size: 2rem;
    color: white;
}

.value-item {
    padding: 1.5rem;
    height: 100%;
}

.value-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, var(--primary-color, #007bff), #0056b3);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.8rem;
    color: white;
}

.number-card {
    background: white;
    padding: 2rem 1rem;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-top: 3px solid var(--primary-color, #007bff);
}

.counter-number {
    font-size: 2.5rem;
    font-weight: bold;
    color: var(--primary-color, #007bff);
    margin-bottom: 0.5rem;
}

.team-member {
    background: white;
    padding: 2rem 1rem;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    height: 100%;
}

.member-photo {
    width: 120px;
    height: 120px;
    margin: 0 auto 1rem;
    overflow: hidden;
    border: 4px solid var(--primary-color, #007bff);
}

.member-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

@media (max-width: 768px) {
    .timeline::before {
        left: 20px;
    }
    
    .timeline-item {
        flex-direction: column !important;
        text-align: left !important;
        padding-left: 3rem;
    }
    
    .timeline-year {
        position: absolute;
        left: -2rem;
        margin: 0;
        min-width: 60px;
        font-size: 0.9rem;
    }
    
    .timeline-content {
        margin-left: 0;
    }
    
    .cta-buttons .btn {
        margin-bottom: 0.5rem;
        display: block;
        margin-right: 0 !important;
    }
}
</style>
@endsection