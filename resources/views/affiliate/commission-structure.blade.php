@extends('layouts.frontend')

@section('title', $title ?? 'Commission Structure')
@section('meta_description', $description ?? 'Detailed breakdown of our affiliate commission structure and rewards.')

@push('styles')
<style>
    .commission-hero {
        padding: 80px 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .commission-content {
        padding: 80px 0;
    }
    
    .commission-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .commission-card:hover {
        transform: translateY(-5px);
        border-color: #667eea;
    }
    
    .commission-card.featured {
        border-color: #667eea;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .commission-card.featured .commission-type {
        color: rgba(255,255,255,0.9);
    }
    
    .commission-type {
        font-size: 1.5rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
    }
    
    .commission-rate {
        font-size: 3rem;
        font-weight: 800;
        color: #667eea;
        margin-bottom: 15px;
    }
    
    .commission-card.featured .commission-rate {
        color: white;
    }
    
    .commission-description {
        color: #666;
        line-height: 1.6;
        margin-bottom: 20px;
    }
    
    .commission-card.featured .commission-description {
        color: rgba(255,255,255,0.9);
    }
    
    .bonus-timeline {
        position: relative;
        padding: 40px 0;
    }
    
    .timeline-item {
        position: relative;
        padding: 20px 0 20px 60px;
        border-left: 2px solid #e9ecef;
    }
    
    .timeline-item:last-child {
        border-left: 2px solid transparent;
    }
    
    .timeline-icon {
        position: absolute;
        left: -12px;
        top: 25px;
        width: 24px;
        height: 24px;
        background: #667eea;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
        font-weight: bold;
    }
    
    .timeline-content {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .qualification-table {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .qualification-table th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        font-weight: 600;
        border: none;
    }
    
    .qualification-table td {
        padding: 15px 20px;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }
    
    .qualification-table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .badge-level {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 10px;
    }
    
    .calculation-example {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 30px;
        margin: 30px 0;
    }
    
    .example-step {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        border-left: 4px solid #667eea;
    }
    
    .example-step:last-child {
        margin-bottom: 0;
    }
    
    .cta-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 80px 0;
        text-align: center;
    }
    
    .cta-button {
        background: white;
        color: #667eea;
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
        box-shadow: 0 10px 25px rgba(255,255,255,0.3);
        color: #667eea;
        text-decoration: none;
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="commission-hero">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-4">Commission Structure</h1>
                <p class="lead mb-4">Understand how you can earn with our comprehensive commission and bonus system designed to reward your success.</p>
            </div>
        </div>
    </div>
</section>

<!-- Commission Types -->
<section class="commission-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto mb-5">
                <h2 class="text-center mb-4">Commission Types</h2>
                <p class="text-center text-muted">Multiple ways to earn with our affiliate program</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="commission-card">
                    <div class="commission-type">Direct Referral</div>
                    <div class="commission-rate">25%</div>
                    <div class="commission-description">
                        Earn 25% commission on every direct sale made through your referral link. This applies to all products and services purchased by your direct referrals.
                    </div>
                    <ul class="list-unstyled mb-0">
                        <li><i class="fas fa-check text-success me-2"></i>Immediate payout</li>
                        <li><i class="fas fa-check text-success me-2"></i>No volume requirements</li>
                        <li><i class="fas fa-check text-success me-2"></i>Lifetime commissions</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="commission-card featured">
                    <div class="commission-type">Binary Bonus</div>
                    <div class="commission-rate">10%</div>
                    <div class="commission-description">
                        Earn 10% bonus on the weaker leg of your binary tree. Calculated weekly based on the sales volume of your left and right teams.
                    </div>
                    <ul class="list-unstyled mb-0">
                        <li><i class="fas fa-check me-2"></i>Weekly payouts</li>
                        <li><i class="fas fa-check me-2"></i>Unlimited depth</li>
                        <li><i class="fas fa-check me-2"></i>Team building rewards</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="commission-card">
                    <div class="commission-type">Matching Bonus</div>
                    <div class="commission-rate">5-15%</div>
                    <div class="commission-description">
                        Earn matching bonuses on your direct referrals' binary bonuses. Rate increases with your leadership level and team performance.
                    </div>
                    <ul class="list-unstyled mb-0">
                        <li><i class="fas fa-check text-success me-2"></i>Multi-level matching</li>
                        <li><i class="fas fa-check text-success me-2"></i>Leadership rewards</li>
                        <li><i class="fas fa-check text-success me-2"></i>Performance-based rates</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Qualification Requirements -->
        <div class="row mt-5">
            <div class="col-lg-12">
                <h3 class="text-center mb-4">Qualification Requirements</h3>
                <div class="table-responsive qualification-table">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Level</th>
                                <th>Personal Sales</th>
                                <th>Team Volume</th>
                                <th>Direct Referrals</th>
                                <th>Matching Bonus Rate</th>
                                <th>Additional Benefits</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="badge-level">Affiliate</span></td>
                                <td>$100/month</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>Direct commissions only</td>
                            </tr>
                            <tr>
                                <td><span class="badge-level">Bronze</span></td>
                                <td>$200/month</td>
                                <td>$1,000</td>
                                <td>2 active</td>
                                <td>5%</td>
                                <td>Binary bonus qualification</td>
                            </tr>
                            <tr>
                                <td><span class="badge-level">Silver</span></td>
                                <td>$300/month</td>
                                <td>$3,000</td>
                                <td>4 active</td>
                                <td>8%</td>
                                <td>2-level matching bonus</td>
                            </tr>
                            <tr>
                                <td><span class="badge-level">Gold</span></td>
                                <td>$500/month</td>
                                <td>$8,000</td>
                                <td>6 active</td>
                                <td>12%</td>
                                <td>3-level matching bonus</td>
                            </tr>
                            <tr>
                                <td><span class="badge-level">Platinum</span></td>
                                <td>$1,000/month</td>
                                <td>$20,000</td>
                                <td>10 active</td>
                                <td>15%</td>
                                <td>Leadership bonuses + car bonus</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Bonus Timeline -->
        <div class="row mt-5">
            <div class="col-lg-8 mx-auto">
                <h3 class="text-center mb-4">Bonus Payout Timeline</h3>
                <div class="bonus-timeline">
                    <div class="timeline-item">
                        <div class="timeline-icon">1</div>
                        <div class="timeline-content">
                            <h5>Direct Commissions</h5>
                            <p class="mb-0">Paid instantly upon purchase confirmation. Available for withdrawal within 24 hours.</p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-icon">2</div>
                        <div class="timeline-content">
                            <h5>Weekly Binary Bonus</h5>
                            <p class="mb-0">Calculated every Friday and paid the following Monday. Minimum payout threshold: $50.</p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-icon">3</div>
                        <div class="timeline-content">
                            <h5>Monthly Matching Bonus</h5>
                            <p class="mb-0">Processed on the 1st of each month for the previous month's binary bonuses earned by your team.</p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-icon">4</div>
                        <div class="timeline-content">
                            <h5>Leadership Bonuses</h5>
                            <p class="mb-0">Special bonuses for Platinum level and above, calculated quarterly based on team performance.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Calculation Example -->
        <div class="row mt-5">
            <div class="col-lg-10 mx-auto">
                <h3 class="text-center mb-4">Commission Calculation Example</h3>
                <div class="calculation-example">
                    <h5 class="mb-4">Monthly Earnings Example for Silver Level Affiliate</h5>
                    
                    <div class="example-step">
                        <h6><i class="fas fa-user-friends text-primary me-2"></i>Direct Referral Commissions</h6>
                        <p class="mb-2">5 direct sales × $200 average = $1,000 in sales</p>
                        <p class="mb-0"><strong>Commission: $1,000 × 25% = $250</strong></p>
                    </div>
                    
                    <div class="example-step">
                        <h6><i class="fas fa-sitemap text-primary me-2"></i>Binary Bonus</h6>
                        <p class="mb-2">Left leg: $3,000 | Right leg: $2,000 (weaker leg)</p>
                        <p class="mb-0"><strong>Binary Bonus: $2,000 × 10% = $200 (weekly × 4) = $800</strong></p>
                    </div>
                    
                    <div class="example-step">
                        <h6><i class="fas fa-handshake text-primary me-2"></i>Matching Bonus</h6>
                        <p class="mb-2">Team's binary bonuses: $1,200 | Silver level rate: 8%</p>
                        <p class="mb-0"><strong>Matching Bonus: $1,200 × 8% = $96</strong></p>
                    </div>
                    
                    <div class="example-step" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <h6><i class="fas fa-calculator me-2"></i>Total Monthly Earnings</h6>
                        <p class="mb-2">Direct: $250 + Binary: $800 + Matching: $96</p>
                        <p class="mb-0 fs-4"><strong>Total: $1,146 per month</strong></p>
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
                <p class="mb-4">Join our affiliate program today and start building your passive income stream with our proven commission structure.</p>
                <a href="{{ route('affiliate.register') }}" class="cta-button me-3">Start Earning Now</a>
                <a href="{{ route('affiliate.info') }}" class="btn btn-outline-light">Learn More</a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Add smooth animations for the commission cards
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Apply animation to commission cards
    document.querySelectorAll('.commission-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'all 0.6s ease';
        observer.observe(card);
    });
    
    // Add click tracking for commission cards
    document.querySelectorAll('.commission-card').forEach(card => {
        card.addEventListener('click', function() {
            const type = this.querySelector('.commission-type').textContent;
            console.log(`Commission card clicked: ${type}`);
            // You can add analytics tracking here
        });
    });
</script>
@endpush
