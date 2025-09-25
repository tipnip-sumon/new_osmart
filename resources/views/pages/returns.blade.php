@extends('layouts.ecomus')

@section('title', 'Returns & Exchanges')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="mb-4">Returns & Exchanges</h1>
            
            <div class="card">
                <div class="card-body">
                    <h3>Return Policy</h3>
                    <p>We want you to be completely satisfied with your purchase. If you're not happy with your order, you can return it within 30 days of purchase for a full refund.</p>
                    
                    <h4>Return Process</h4>
                    <ol>
                        <li>Contact our customer service team at <a href="mailto:{{ generalSettings()->support_email ?? 'support@osmart.com' }}">{{ generalSettings()->support_email ?? 'support@osmart.com' }}</a></li>
                        <li>Provide your order number and reason for return</li>
                        <li>We'll send you a prepaid return shipping label</li>
                        <li>Package your items securely and attach the return label</li>
                        <li>Drop off at any authorized shipping location</li>
                    </ol>
                    
                    <h4>Exchange Policy</h4>
                    <p>If you need a different size or color, we're happy to exchange your item. Exchanges are processed within 5-7 business days of receipt.</p>
                    
                    <h4>Conditions</h4>
                    <ul>
                        <li>Items must be in original condition with tags attached</li>
                        <li>Items must not be worn, washed, or damaged</li>
                        <li>Original packaging must be included</li>
                        <li>Some items like underwear, swimwear, and personalized items cannot be returned</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection