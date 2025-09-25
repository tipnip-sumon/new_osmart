@extends('layouts.app')

@section('title', 'Products - ' . config('app.name'))

@section('content')
<div class="container my-5">
    <h1 class="text-center mb-5">Products</h1>
    
    <div class="row">
        @foreach($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card">
                    @if(!empty($product->images) && is_array($product->images))
                        @php
                            $firstImage = $product->images[0];
                            $imageUrl = is_array($firstImage) && isset($firstImage['urls']) 
                                ? ($firstImage['urls']['medium'] ?? $firstImage['urls']['small'] ?? $firstImage['urls']['thumbnail'] ?? null)
                                : null;
                        @endphp
                        @if($imageUrl)
                            <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <span class="text-muted">No Image</span>
                            </div>
                        @endif
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <span class="text-muted">No Image</span>
                        </div>
                    @endif
                    
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">${{ number_format($product->sale_price ?? $product->price, 2) }}</p>
                        
                        <button type="button" class="btn btn-primary add-to-cart-btn" 
                                data-product-id="{{ $product->id }}">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="text-center mt-4">
        <a href="{{ route('cart.index') }}" class="btn btn-success">
            View Cart <span id="cart-count">(0)</span>
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add to cart functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-to-cart-btn')) {
            const productId = e.target.getAttribute('data-product-id');
            
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Product added to cart!');
                    updateCartCount();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding product to cart');
            });
        }
    });
    
    // Update cart count
    function updateCartCount() {
        fetch('/cart/count')
        .then(response => response.json())
        .then(data => {
            document.getElementById('cart-count').textContent = '(' + data.count + ')';
        });
    }
    
    // Load initial cart count
    updateCartCount();
});
</script>
@endsection
