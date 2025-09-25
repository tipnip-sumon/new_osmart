@extends('layouts.app')

@section('title', 'Affiliate Program')

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
                                    <h1 class="display-4 mb-3">Join Our Affiliate Program</h1>
                                    <p class="lead mb-4">Build your network, earn commissions, and grow your income with our MLM affiliate program. Start your journey to financial freedom today!</p>
                                    <div class="hero-btn-group">
                                        <a class="btn btn-warning btn-lg me-2" href="{{ route('affiliate.register') }}">Join Now</a>
                                        <a class="btn btn-outline-warning btn-lg" href="{{ route('affiliate.login') }}">Member Login</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="hero-thumbnail">
                                    <img src="{{ asset('assets/img/bg-img/affiliate-hero.jpg') }}" alt="Affiliate Program">
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
                <h2 class="text-center mb-4">Why Join Our Affiliate Program?</h2>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="feature-card h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon">
                            <i class="ti ti-currency-dollar fs-1 text-warning"></i>
                        </div>
                        <h5 class="card-title">High Commissions</h5>
                        <p class="card-text">Earn competitive commissions on every sale and from your network's performance.</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="feature-card h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon">
                            <i class="ti ti-network fs-1 text-success"></i>
                        </div>
                        <h5 class="card-title">Binary MLM System</h5>
                        <p class="card-text">Build your network with our proven binary compensation plan and maximize your earnings.</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="feature-card h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon">
                            <i class="ti ti-school fs-1 text-info"></i>
                        </div>
                        <h5 class="card-title">Training & Support</h5>
                        <p class="card-text">Get comprehensive training materials and ongoing support to help you succeed.</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="feature-card h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon">
                            <i class="ti ti-chart-pie fs-1 text-primary"></i>
                        </div>
                        <h5 class="card-title">Real-time Analytics</h5>
                        <p class="card-text">Track your performance, commissions, and network growth with detailed analytics.</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="feature-card h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon">
                            <i class="ti ti-gift fs-1 text-danger"></i>
                        </div>
                        <h5 class="card-title">Bonuses & Rewards</h5>
                        <p class="card-text">Unlock achievement bonuses and special rewards as you grow your network.</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="feature-card h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon">
                            <i class="ti ti-clock fs-1 text-secondary"></i>
                        </div>
                        <h5 class="card-title">Passive Income</h5>
                        <p class="card-text">Build a sustainable passive income stream that grows with your network.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Commission Structure -->
        <div class="row mt-5">
            <div class="col-12">
                <h2 class="text-center mb-4">Commission Structure</h2>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-md-4">
                <div class="commission-card text-center">
                    <div class="commission-icon">
                        <i class="ti ti-shopping-cart"></i>
                    </div>
                    <h5>Direct Sales</h5>
                    <div class="commission-rate">5-15%</div>
                    <p>Earn commission on products sold directly through your referral link.</p>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="commission-card text-center">
                    <div class="commission-icon">
                        <i class="ti ti-users"></i>
                    </div>
                    <h5>Team Building</h5>
                    <div class="commission-rate">2-10%</div>
                    <p>Earn from your team's sales in the binary compensation plan.</p>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="commission-card text-center">
                    <div class="commission-icon">
                        <i class="ti ti-star"></i>
                    </div>
                    <h5>Achievement Bonus</h5>
                    <div class="commission-rate">Up to 25%</div>
                    <p>Special bonuses for reaching milestones and leadership levels.</p>
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
            <div class="col-12 col-md-3">
                <div class="step-card text-center">
                    <div class="step-number">1</div>
                    <h5>Sign Up</h5>
                    <p>Register for free and get your unique referral code.</p>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="step-card text-center">
                    <div class="step-number">2</div>
                    <h5>Share</h5>
                    <p>Share your referral link with friends and family.</p>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="step-card text-center">
                    <div class="step-number">3</div>
                    <h5>Build</h5>
                    <p>Help your referrals build their own networks.</p>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="step-card text-center">
                    <div class="step-number">4</div>
                    <h5>Earn</h5>
                    <p>Start earning commissions from your network's success.</p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="cta-section text-center bg-warning text-dark p-5 rounded">
                    <h3>Ready to Start Earning?</h3>
                    <p class="lead mb-4">Join thousands of successful affiliates already building their financial future with us.</p>
                    <a class="btn btn-dark btn-lg" href="{{ route('affiliate.register') }}">Start Your Affiliate Journey</a>
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

.commission-card {
    border: 2px solid #7f8c8d;
    border-radius: 15px;
    padding: 30px 20px;
    background: linear-gradient(135deg, #ecf0f1, #d5dbdb);
}

.commission-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #7f8c8d;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    margin: 0 auto 20px;
}

.commission-rate {
    font-size: 32px;
    font-weight: bold;
    color: #2c3e50;
    margin: 15px 0;
}

.step-card {
    padding: 30px 20px;
}

.step-number {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #7f8c8d;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: bold;
    margin: 0 auto 20px;
}

.cta-section {
    background: linear-gradient(135deg, #7f8c8d 0%, #95a5a6 100%) !important;
}
</style>
@endsection
