@extends('layouts.ecomus')

@section('title', 'Shipping Information')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="mb-4">Shipping Information</h1>
            
            <div class="card">
                <div class="card-body">
                    <h3>Shipping Options</h3>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Standard Shipping</h4>
                            <p><strong>Free on orders over $75</strong></p>
                            <p>Delivery: 5-7 business days</p>
                            <p>Cost: $5.99</p>
                        </div>
                        <div class="col-md-6">
                            <h4>Express Shipping</h4>
                            <p>Delivery: 2-3 business days</p>
                            <p>Cost: $12.99</p>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h4>Overnight Shipping</h4>
                            <p>Delivery: 1 business day</p>
                            <p>Cost: $24.99</p>
                        </div>
                        <div class="col-md-6">
                            <h4>International Shipping</h4>
                            <p>Delivery: 7-14 business days</p>
                            <p>Cost: Starting at $19.99</p>
                        </div>
                    </div>
                    
                    <h4 class="mt-4">Processing Time</h4>
                    <p>Orders are processed within 1-2 business days (Monday-Friday, excluding holidays). You will receive a tracking number once your order has shipped.</p>
                    
                    <h4>Shipping Restrictions</h4>
                    <ul>
                        <li>We currently ship within the United States and select international locations</li>
                        <li>Some items may have shipping restrictions due to size or regulations</li>
                        <li>Expedited shipping is not available for oversized items</li>
                    </ul>
                    
                    <div class="alert alert-info">
                        <strong>Note:</strong> Delivery times may vary during peak seasons and holidays. We'll keep you updated on any delays.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection