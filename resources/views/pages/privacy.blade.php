@extends('layouts.app')

@section('title', 'Privacy Policy - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="display-4 text-center mb-5">Privacy Policy</h1>
            
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <p class="text-muted">Last updated: {{ date('F d, Y') }}</p>
                    
                    <h2 class="h4 mt-4 mb-3">1. Information We Collect</h2>
                    <p>
                        We collect information you provide directly to us, such as when you create an account, make a purchase, or contact us for support.
                    </p>
                    
                    <h3 class="h5 mt-3 mb-2">Personal Information</h3>
                    <ul>
                        <li>Name, email address, and phone number</li>
                        <li>Billing and shipping addresses</li>
                        <li>Payment information (processed securely)</li>
                        <li>MLM sponsor information and genealogy data</li>
                    </ul>
                    
                    <h3 class="h5 mt-3 mb-2">Automatically Collected Information</h3>
                    <ul>
                        <li>Device information and IP address</li>
                        <li>Browser type and version</li>
                        <li>Usage patterns and preferences</li>
                        <li>Cookies and similar tracking technologies</li>
                    </ul>
                    
                    <h2 class="h4 mt-4 mb-3">2. How We Use Your Information</h2>
                    <ul>
                        <li>Process orders and manage your account</li>
                        <li>Calculate and distribute MLM commissions</li>
                        <li>Provide customer support and respond to inquiries</li>
                        <li>Send important updates and marketing communications</li>
                        <li>Improve our services and user experience</li>
                        <li>Comply with legal obligations</li>
                    </ul>
                    
                    <h2 class="h4 mt-4 mb-3">3. Information Sharing</h2>
                    <p>
                        We do not sell, trade, or rent your personal information to third parties. We may share information in the following circumstances:
                    </p>
                    <ul>
                        <li>With your MLM upline/downline for business purposes</li>
                        <li>With service providers who assist in our operations</li>
                        <li>When required by law or legal process</li>
                        <li>To protect our rights and prevent fraud</li>
                    </ul>
                    
                    <h2 class="h4 mt-4 mb-3">4. MLM-Specific Privacy</h2>
                    <p>
                        As part of our MLM business model:
                    </p>
                    <ul>
                        <li>Your sponsor and upline may access certain performance data</li>
                        <li>Commission information may be shared with relevant team members</li>
                        <li>Success stories may be shared (with your consent)</li>
                        <li>Genealogy information is accessible to your network</li>
                    </ul>
                    
                    <h2 class="h4 mt-4 mb-3">5. Data Security</h2>
                    <p>
                        We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.
                    </p>
                    
                    <h2 class="h4 mt-4 mb-3">6. Your Rights</h2>
                    <p>
                        You have the right to:
                    </p>
                    <ul>
                        <li>Access and update your personal information</li>
                        <li>Request deletion of your data (subject to legal requirements)</li>
                        <li>Opt-out of marketing communications</li>
                        <li>Withdraw consent where applicable</li>
                    </ul>
                    
                    <h2 class="h4 mt-4 mb-3">7. Cookies</h2>
                    <p>
                        We use cookies to enhance your browsing experience, analyze site traffic, and personalize content. You can control cookie settings through your browser preferences.
                    </p>
                    
                    <h2 class="h4 mt-4 mb-3">8. International Transfers</h2>
                    <p>
                        Your information may be transferred to and processed in countries other than your own. We ensure appropriate safeguards are in place for such transfers.
                    </p>
                    
                    <h2 class="h4 mt-4 mb-3">9. Changes to This Policy</h2>
                    <p>
                        We may update this Privacy Policy from time to time. We will notify you of any material changes by posting the new policy on this page.
                    </p>
                    
                    <h2 class="h4 mt-4 mb-3">10. Contact Us</h2>
                    <p>
                        If you have questions about this Privacy Policy, please contact us at:
                        <br>Email: privacy@{{ strtolower(str_replace(' ', '', config('app.name'))) }}.com
                        <br>Phone: +1 (555) 123-4567
                        <br>Address: 123 Business Street, Suite 100, City, State 12345
                    </p>
                    
                    <div class="text-center mt-5">
                        <a href="{{ route('register') }}" class="btn btn-primary">I Accept - Register Now</a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary ms-3">Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
