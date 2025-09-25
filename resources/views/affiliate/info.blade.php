@extends('layouts.frontend')

@section('title', $title ?? 'Affiliate Program Information')
@section('meta_description', $description ?? 'Learn about our affiliate program and how you can earn commissions.')

@push('styles')
<style>
    .affiliate-info-section {
        padding: 80px 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .affiliate-content {
        padding: 60px 0;
    }
    
    .info-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }
    
    .info-card:hover {
        transform: translateY(-5px);
    }
    
    .info-card h4 {
        color: #333;
        margin-bottom: 15px;
        font-weight: 600;
    }
    
    .info-card p {
        color: #666;
        line-height: 1.6;
    }
    
    .feature-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        color: white;
        font-size: 24px;
    }
    
    .cta-section {
        background: #f8f9fa;
        padding: 60px 0;
        text-align: center;
    }
    
    .cta-button {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px 40px;
        border-radius: 50px;
        text-decoration: none;
        display: inline-block;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        font-size: 16px;
    }
    
    .cta-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        color: white;
        text-decoration: none;
    }
    
    .stats-row {
        background: white;
        border-radius: 15px;
        padding: 40px;
        margin: 40px 0;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .stat-item {
        text-align: center;
        margin-bottom: 20px;
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #667eea;
        display: block;
    }
    
    .stat-label {
        color: #666;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="affiliate-info-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-4">Join Our Affiliate Program</h1>
                <p class="lead mb-4">Earn generous commissions by promoting our products and building your network. Start your journey to financial freedom today!</p>
                <a href="{{ route('affiliate.register') }}" class="cta-button me-3">Join Now</a>
                <a href="{{ route('affiliate.commission.structure') }}" class="btn btn-outline-light">View Commission Structure</a>
            </div>
        </div>
        
        <!-- Stats Row -->
        <div class="row stats-row">
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <span class="stat-number">25%</span>
                    <span class="stat-label">Commission Rate</span>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <span class="stat-number">$500</span>
                    <span class="stat-label">Avg Monthly Earnings</span>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <span class="stat-number">10K+</span>
                    <span class="stat-label">Active Affiliates</span>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <span class="stat-number">24/7</span>
                    <span class="stat-label">Support</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Content Section -->
<section class="affiliate-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto mb-5">
                <h2 class="text-center mb-4">How It Works</h2>
                <p class="text-center text-muted">Getting started with our affiliate program is simple and straightforward.</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="info-card text-center">
                    <div class="feature-icon mx-auto">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h4>1. Sign Up</h4>
                    <p>Register for our affiliate program with your sponsor's referral code. Choose your position (left or right) in the binary tree structure.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="info-card text-center">
                    <div class="feature-icon mx-auto">
                        <i class="fas fa-share-alt"></i>
                    </div>
                    <h4>2. Promote</h4>
                    <p>Share your unique referral links and promote our products to your network. Use our marketing materials and tools to maximize your reach.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="info-card text-center">
                    <div class="feature-icon mx-auto">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <h4>3. Earn</h4>
                    <p>Receive commissions from direct referrals and binary bonus from your downline. Track your earnings in real-time through your dashboard.</p>
                </div>
            </div>
        </div>
        
        <div class="row mt-5">
            <div class="col-lg-6">
                <div class="info-card">
                    <h4><i class="fas fa-chart-line text-primary me-2"></i>Multiple Income Streams</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Direct Referral Commissions</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Binary Tree Bonuses</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Matching Bonuses</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Leadership Bonuses</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Weekly/Monthly Incentives</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="info-card">
                    <h4><i class="fas fa-tools text-primary me-2"></i>Marketing Tools & Support</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Professional Marketing Materials</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Personalized Referral Links</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Real-time Analytics Dashboard</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Training and Webinars</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>24/7 Affiliate Support</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="row mt-5">
            <div class="col-lg-12">
                <div class="info-card">
                    <h4><i class="fas fa-sitemap text-primary me-2"></i>Binary MLM Structure</h4>
                    <p>Our binary compensation plan allows you to build two legs (left and right) in your downline. You earn bonuses based on the sales volume of your weaker leg, encouraging balanced team building and maximizing your earning potential.</p>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h6>Benefits of Our Binary Plan:</h6>
                            <ul>
                                <li>Unlimited depth potential</li>
                                <li>Spillover from your upline</li>
                                <li>Balanced team building incentives</li>
                                <li>Regular bonus payments</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Getting Started:</h6>
                            <ul>
                                <li>Choose your sponsor</li>
                                <li>Select left or right position</li>
                                <li>Complete verification process</li>
                                <li>Start building your network</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h3 class="mb-4">Ready to Start Earning?</h3>
                <p class="text-muted mb-4">Join thousands of successful affiliates who are already earning with our program. Start your journey today!</p>
                <a href="{{ route('affiliate.register') }}" class="cta-button me-3">Join Affiliate Program</a>
                <a href="{{ route('affiliate.login') }}" class="btn btn-outline-secondary">Already a Member? Login</a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Add smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
    
    // Add counter animation for stats
    function animateCounters() {
        const counters = document.querySelectorAll('.stat-number');
        counters.forEach(counter => {
            const target = counter.textContent;
            const numericTarget = parseFloat(target.replace(/[^0-9.]/g, ''));
            
            if (!isNaN(numericTarget)) {
                let current = 0;
                const increment = numericTarget / 50;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= numericTarget) {
                        current = numericTarget;
                        clearInterval(timer);
                    }
                    
                    if (target.includes('%')) {
                        counter.textContent = Math.floor(current) + '%';
                    } else if (target.includes('$')) {
                        counter.textContent = '$' + Math.floor(current);
                    } else if (target.includes('K')) {
                        counter.textContent = Math.floor(current) + 'K+';
                    } else {
                        counter.textContent = target;
                    }
                }, 20);
            }
        });
    }
    
    // Trigger counter animation when section is visible
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounters();
                observer.unobserve(entry.target);
            }
        });
    });
    
    const statsSection = document.querySelector('.stats-row');
    if (statsSection) {
        observer.observe(statsSection);
    }
</script>
@endpush
