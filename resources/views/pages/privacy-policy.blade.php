@extends('layouts.ecomus')

@section('title', 'Privacy Policy - ' . config('app.name'))
@section('description', 'Read our privacy policy to understand how we protect your personal information')

@section('content')
<!-- page-title -->
<div class="tf-page-title">
    <div class="container-full">
        <div class="heading text-center">Privacy Policy</div>
        <p class="text-center text-secondary mt-2">Your privacy is important to us</p>
    </div>
</div>
<!-- /page-title -->

<!-- Section Privacy -->
<section class="flat-spacing-11">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="content-privacy">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <small class="text-muted">Last updated: {{ now()->format('F d, Y') }}</small>
                        <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">
                            <i class="icon icon-arrow-left"></i> Back to Home
                        </a>
                    </div>
                    
                    @if($settings && $settings->privacy_policy)
                        {!! $settings->privacy_policy !!}
                    @else
                        <div class="default-privacy-content">
                            <h2>1. Information We Collect</h2>
                            <p>We collect several different types of information for various purposes to provide and improve our service to you.</p>
                            
                            <h3>Personal Data</h3>
                            <p>While using our service, we may ask you to provide us with certain personally identifiable information that can be used to contact or identify you. This may include:</p>
                            <ul>
                                <li>Email address</li>
                                <li>First name and last name</li>
                                <li>Phone number</li>
                                <li>Address, State, Province, ZIP/Postal code, City</li>
                                <li>Cookies and usage data</li>
                            </ul>
                            
                            <h3>Usage Data</h3>
                            <p>We may also collect information about how the service is accessed and used. This usage data may include information such as your computer's Internet Protocol address, browser type, browser version, the pages of our service that you visit, the time and date of your visit, and other diagnostic data.</p>
                            
                            <h2>2. How We Use Your Information</h2>
                            <p>{{ siteName() ?? config('app.name') }} uses the collected data for various purposes:</p>
                            <ul>
                                <li>To provide and maintain our service</li>
                                <li>To notify you about changes to our service</li>
                                <li>To allow you to participate in interactive features when you choose to do so</li>
                                <li>To provide customer support</li>
                                <li>To gather analysis or valuable information so that we can improve our service</li>
                                <li>To monitor the usage of our service</li>
                                <li>To detect, prevent and address technical issues</li>
                                <li>To process your orders and payments</li>
                                <li>To send you marketing communications (with your consent)</li>
                            </ul>
                            
                            <h2>3. Data Protection</h2>
                            <p>The security of your data is important to us, but remember that no method of transmission over the Internet, or method of electronic storage is 100% secure. We implement appropriate security measures to protect your personal information, including:</p>
                            <ul>
                                <li>Encryption of sensitive data during transmission</li>
                                <li>Secure storage of personal information</li>
                                <li>Regular security assessments</li>
                                <li>Access controls and authentication measures</li>
                            </ul>
                            
                            <h2>4. Information Sharing</h2>
                            <p>We do not sell, trade, or otherwise transfer your personal information to third parties except as described in this privacy policy:</p>
                            <ul>
                                <li>With your consent</li>
                                <li>To comply with legal obligations</li>
                                <li>To protect our rights and safety</li>
                                <li>With service providers who assist us in operating our website</li>
                                <li>In connection with a business transfer or merger</li>
                            </ul>
                            
                            <h2>5. Cookies and Tracking Technologies</h2>
                            <p>We use cookies and similar tracking technologies to track activity on our service and hold certain information. You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent.</p>
                            
                            <h3>Types of Cookies We Use:</h3>
                            <ul>
                                <li><strong>Essential Cookies:</strong> Required for the website to function properly</li>
                                <li><strong>Analytics Cookies:</strong> Help us understand how visitors interact with our website</li>
                                <li><strong>Marketing Cookies:</strong> Used to track visitors across websites for advertising purposes</li>
                                <li><strong>Functional Cookies:</strong> Enable enhanced functionality and personalization</li>
                            </ul>
                            
                            <h2>6. Your Rights</h2>
                            <p>You have certain rights regarding your personal data:</p>
                            <ul>
                                <li>Right to access your personal data</li>
                                <li>Right to rectification of inaccurate data</li>
                                <li>Right to erasure ("right to be forgotten")</li>
                                <li>Right to restrict processing</li>
                                <li>Right to data portability</li>
                                <li>Right to object to processing</li>
                                <li>Right to withdraw consent</li>
                            </ul>
                            
                            <h2>7. Data Retention</h2>
                            <p>We will retain your personal data only for as long as necessary for the purposes outlined in this privacy policy. We will retain and use your information to the extent necessary to comply with our legal obligations, resolve disputes, and enforce our policies.</p>
                            
                            <h2>8. Children's Privacy</h2>
                            <p>Our service does not address anyone under the age of 18. We do not knowingly collect personally identifiable information from children under 18. If you are a parent or guardian and you are aware that your child has provided us with personal data, please contact us.</p>
                            
                            <h2>9. International Data Transfers</h2>
                            <p>Your information may be transferred to — and maintained on — computers located outside of your state, province, country or other governmental jurisdiction where the data protection laws may differ from those of your jurisdiction.</p>
                            
                            <h2>10. Changes to This Privacy Policy</h2>
                            <p>We may update our privacy policy from time to time. We will notify you of any changes by posting the new privacy policy on this page and updating the "Last updated" date.</p>
                            
                            <h2>11. Contact Us</h2>
                            <p>If you have any questions about this privacy policy, please contact us:</p>
                            <ul>
                                <li>Email: {{ $settings->contact_email ?? 'privacy@' . request()->getHost() }}</li>
                                <li>Phone: {{ $settings->contact_phone ?? 'Available on our contact page' }}</li>
                                @if($settings && $settings->contact_address)
                                    <li>Address: {{ $settings->contact_address }}</li>
                                @endif
                            </ul>
                            
                            <div class="alert alert-info mt-4">
                                <h5><i class="icon icon-info-circle me-2"></i>Quick Summary</h5>
                                <p class="mb-0">We collect your information to provide better service, we protect your data with industry-standard security measures, we don't sell your information to third parties, and you have control over your data. If you have questions, please contact us.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /Section Privacy -->

<style>
.content-privacy h2 {
    color: var(--primary-color, #007bff);
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-size: 1.5rem;
    font-weight: 600;
    border-bottom: 2px solid var(--primary-color, #007bff);
    padding-bottom: 0.5rem;
}

.content-privacy h3 {
    color: var(--secondary-color, #6c757d);
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
    font-size: 1.25rem;
    font-weight: 500;
}

.content-privacy p {
    margin-bottom: 1rem;
    line-height: 1.6;
}

.content-privacy ul, .content-privacy ol {
    margin-bottom: 1rem;
    padding-left: 2rem;
}

.content-privacy ul li, .content-privacy ol li {
    margin-bottom: 0.5rem;
    line-height: 1.5;
}

.default-privacy-content {
    background-color: #f8f9fa;
    padding: 2rem;
    border-radius: 8px;
    border-left: 4px solid var(--success-color, #28a745);
}

.alert-info {
    background-color: #e3f2fd;
    border: 1px solid #2196f3;
    color: #1976d2;
    padding: 1rem;
    border-radius: 6px;
}

.alert-info h5 {
    color: #1976d2;
    margin-bottom: 0.5rem;
}

@media (max-width: 768px) {
    .content-privacy {
        padding: 1rem;
    }
    
    .content-privacy h2 {
        font-size: 1.25rem;
    }
    
    .content-privacy h3 {
        font-size: 1.1rem;
    }
}
</style>
@endsection