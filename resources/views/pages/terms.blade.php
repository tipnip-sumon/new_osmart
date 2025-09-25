@extends('layouts.app')

@section('title', 'Terms of Service - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="display-4 text-center mb-5">Terms of Service</h1>
            
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <p class="text-muted">Last updated: {{ date('F d, Y') }}</p>
                    
                    <h2 class="h4 mt-4 mb-3">1. Acceptance of Terms</h2>
                    <p>
                        By accessing and using {{ config('app.name') }}, you accept and agree to be bound by the terms and provision of this agreement.
                    </p>
                    
                    <h2 class="h4 mt-4 mb-3">2. Business Opportunity</h2>
                    <p>
                        {{ config('app.name') }} provides a legitimate business opportunity through our MLM (Multi-Level Marketing) program. All participants must comply with applicable laws and regulations in their jurisdiction.
                    </p>
                    
                    <h2 class="h4 mt-4 mb-3">3. Income Disclaimers</h2>
                    <p>
                        Success in this business requires hard work, dedication, and consistency. We make no guarantees about your level of success or income as individual results will vary based on your effort, skill, and market conditions.
                    </p>
                    
                    <h2 class="h4 mt-4 mb-3">4. Code of Conduct</h2>
                    <ul>
                        <li>All members must conduct business in an ethical and professional manner</li>
                        <li>No false or misleading income claims are permitted</li>
                        <li>Respect for all members and customers is required</li>
                        <li>Compliance with all applicable laws and regulations</li>
                    </ul>
                    
                    <h2 class="h4 mt-4 mb-3">5. Compensation Plan</h2>
                    <p>
                        Our compensation plan is designed to reward productive sales activity and team building. Detailed information about commission structures and requirements can be found in your member dashboard.
                    </p>
                    
                    <h2 class="h4 mt-4 mb-3">6. Product Returns</h2>
                    <p>
                        We offer a satisfaction guarantee on all products. Returns must be initiated within 30 days of purchase in accordance with our return policy.
                    </p>
                    
                    <h2 class="h4 mt-4 mb-3">7. Termination</h2>
                    <p>
                        Either party may terminate the agreement at any time with or without cause. Upon termination, all rights and obligations cease except those that by their nature should survive.
                    </p>
                    
                    <h2 class="h4 mt-4 mb-3">8. Limitation of Liability</h2>
                    <p>
                        {{ config('app.name') }} shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising from your use of our platform.
                    </p>
                    
                    <h2 class="h4 mt-4 mb-3">9. Changes to Terms</h2>
                    <p>
                        We reserve the right to modify these terms at any time. Changes will be effective immediately upon posting to our website.
                    </p>
                    
                    <h2 class="h4 mt-4 mb-3">10. Contact Information</h2>
                    <p>
                        If you have any questions about these Terms of Service, please contact us at:
                        <br>Email: legal@{{ strtolower(str_replace(' ', '', config('app.name'))) }}.com
                        <br>Phone: +1 (555) 123-4567
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
