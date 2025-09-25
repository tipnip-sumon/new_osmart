@extends('layouts.ecomus')

@section('title', 'Store Locations')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Our Store Locations</h1>
            <p class="lead mb-5">Visit us at any of our convenient locations or shop online for delivery anywhere.</p>
        </div>
    </div>
    
    <div class="row">
        <!-- Main Store -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Downtown Flagship Store</h5>
                    <p class="card-text">
                        <strong>Address:</strong><br>
                        123 Main Street<br>
                        New York, NY 10001<br><br>
                        
                        <strong>Phone:</strong> (555) 123-4567<br>
                        <strong>Email:</strong> flagship@osmart.com<br><br>
                        
                        <strong>Hours:</strong><br>
                        Monday - Saturday: 9:00 AM - 9:00 PM<br>
                        Sunday: 11:00 AM - 7:00 PM
                    </p>
                </div>
                <div class="card-footer">
                    <a href="https://maps.google.com/?q=123+Main+Street+New+York+NY+10001" 
                       target="_blank" class="btn btn-primary btn-sm">Get Directions</a>
                </div>
            </div>
        </div>
        
        <!-- Mall Location -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Westfield Mall</h5>
                    <p class="card-text">
                        <strong>Address:</strong><br>
                        456 Shopping Center Blvd<br>
                        Los Angeles, CA 90210<br><br>
                        
                        <strong>Phone:</strong> (555) 234-5678<br>
                        <strong>Email:</strong> westfield@osmart.com<br><br>
                        
                        <strong>Hours:</strong><br>
                        Monday - Saturday: 10:00 AM - 10:00 PM<br>
                        Sunday: 12:00 PM - 8:00 PM
                    </p>
                </div>
                <div class="card-footer">
                    <a href="https://maps.google.com/?q=456+Shopping+Center+Blvd+Los+Angeles+CA+90210" 
                       target="_blank" class="btn btn-primary btn-sm">Get Directions</a>
                </div>
            </div>
        </div>
        
        <!-- Outlet Store -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Outlet Center</h5>
                    <p class="card-text">
                        <strong>Address:</strong><br>
                        789 Outlet Drive<br>
                        Miami, FL 33101<br><br>
                        
                        <strong>Phone:</strong> (555) 345-6789<br>
                        <strong>Email:</strong> outlet@osmart.com<br><br>
                        
                        <strong>Hours:</strong><br>
                        Monday - Saturday: 9:00 AM - 9:00 PM<br>
                        Sunday: 10:00 AM - 8:00 PM
                    </p>
                </div>
                <div class="card-footer">
                    <a href="https://maps.google.com/?q=789+Outlet+Drive+Miami+FL+33101" 
                       target="_blank" class="btn btn-primary btn-sm">Get Directions</a>
                </div>
            </div>
        </div>
        
        <!-- Chicago Store -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Chicago Loop</h5>
                    <p class="card-text">
                        <strong>Address:</strong><br>
                        321 State Street<br>
                        Chicago, IL 60601<br><br>
                        
                        <strong>Phone:</strong> (555) 456-7890<br>
                        <strong>Email:</strong> chicago@osmart.com<br><br>
                        
                        <strong>Hours:</strong><br>
                        Monday - Saturday: 9:00 AM - 8:00 PM<br>
                        Sunday: 11:00 AM - 6:00 PM
                    </p>
                </div>
                <div class="card-footer">
                    <a href="https://maps.google.com/?q=321+State+Street+Chicago+IL+60601" 
                       target="_blank" class="btn btn-primary btn-sm">Get Directions</a>
                </div>
            </div>
        </div>
        
        <!-- Dallas Store -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Dallas Galleria</h5>
                    <p class="card-text">
                        <strong>Address:</strong><br>
                        654 Galleria Way<br>
                        Dallas, TX 75240<br><br>
                        
                        <strong>Phone:</strong> (555) 567-8901<br>
                        <strong>Email:</strong> dallas@osmart.com<br><br>
                        
                        <strong>Hours:</strong><br>
                        Monday - Saturday: 10:00 AM - 9:00 PM<br>
                        Sunday: 12:00 PM - 7:00 PM
                    </p>
                </div>
                <div class="card-footer">
                    <a href="https://maps.google.com/?q=654+Galleria+Way+Dallas+TX+75240" 
                       target="_blank" class="btn btn-primary btn-sm">Get Directions</a>
                </div>
            </div>
        </div>
        
        <!-- Seattle Store -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Seattle Waterfront</h5>
                    <p class="card-text">
                        <strong>Address:</strong><br>
                        987 Pike Place<br>
                        Seattle, WA 98101<br><br>
                        
                        <strong>Phone:</strong> (555) 678-9012<br>
                        <strong>Email:</strong> seattle@osmart.com<br><br>
                        
                        <strong>Hours:</strong><br>
                        Monday - Saturday: 9:00 AM - 8:00 PM<br>
                        Sunday: 10:00 AM - 7:00 PM
                    </p>
                </div>
                <div class="card-footer">
                    <a href="https://maps.google.com/?q=987+Pike+Place+Seattle+WA+98101" 
                       target="_blank" class="btn btn-primary btn-sm">Get Directions</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Coming Soon Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="alert alert-info">
                <h4 class="alert-heading">Coming Soon!</h4>
                <p>We're expanding! New store locations opening in Boston, Atlanta, and Phoenix this year. 
                   <a href="{{ route('contact.show') }}" class="alert-link">Contact us</a> for updates on opening dates.</p>
            </div>
        </div>
    </div>
    
    <!-- Online Store Callout -->
    <div class="row mt-4">
        <div class="col-12 text-center">
            <div class="bg-light p-4 rounded">
                <h3>Can't visit a store?</h3>
                <p class="lead">Shop online and enjoy free shipping on orders over $75, plus easy returns and exchanges.</p>
                <a href="{{ route('home') }}" class="btn btn-primary btn-lg">Shop Online</a>
            </div>
        </div>
    </div>
</div>
@endsection