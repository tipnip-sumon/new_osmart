@extends('layouts.ecomus')

@section('title', 'Checkout')

@section('content')
    <!-- page-title -->
    <div class="tf-page-title">
        <div class="container-full">
            <div class="heading text-center">Check Out</div>
        </div>
    </div>
    <!-- /page-title -->

    <!-- page-cart -->
    <section class="flat-spacing-11">
        <div class="container">
            <div class="tf-page-cart-wrap layout-2">
                <div class="tf-page-cart-item">
                    <h5 class="fw-5 mb_20">Billing details</h5>
                    <form class="form-checkout" method="POST" action="{{ route('orders.store') }}">
                        @csrf
                        <div class="box grid-2">
                            <fieldset class="fieldset">
                                <label for="first_name">First Name</label>
                                <input type="text" name="first_name" id="first_name" placeholder="First Name" value="{{ old('first_name') }}" required>
                                @error('first_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </fieldset>
                            <fieldset class="fieldset">
                                <label for="last_name">Last Name</label>
                                <input type="text" name="last_name" id="last_name" placeholder="Last Name" value="{{ old('last_name') }}" required>
                                @error('last_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </fieldset>
                        </div>
                        <fieldset class="box fieldset">
                            <label for="country">Country/Region</label>
                            <div class="select-custom">
                                <select class="tf-select w-100" id="country" name="country" required>
                                    <option value="">Select Country</option>
                                    <option value="Bangladesh" {{ old('country') == 'Bangladesh' ? 'selected' : '' }}>Bangladesh</option>
                                    <option value="India" {{ old('country') == 'India' ? 'selected' : '' }}>India</option>
                                    <option value="Pakistan" {{ old('country') == 'Pakistan' ? 'selected' : '' }}>Pakistan</option>
                                </select>
                                @error('country')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </fieldset>
                        <fieldset class="box fieldset">
                            <label for="city">Town/City</label>
                            <input type="text" name="city" id="city" placeholder="City" value="{{ old('city') }}" required>
                            @error('city')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </fieldset>
                        <fieldset class="box fieldset">
                            <label for="address">Address</label>
                            <input type="text" name="address" id="address" placeholder="Street address" value="{{ old('address') }}" required>
                            @error('address')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </fieldset>
                        <fieldset class="box fieldset">
                            <label for="phone">Phone Number</label>
                            <input type="tel" name="phone" id="phone" placeholder="Phone Number" value="{{ old('phone') }}" required>
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </fieldset>
                        <fieldset class="box fieldset">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" placeholder="Email" value="{{ old('email', auth()->user()->email ?? '') }}" required>
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </fieldset>
                        <fieldset class="box fieldset">
                            <label for="note">Order notes (optional)</label>
                            <textarea name="note" id="note" placeholder="Notes about your order, e.g. special notes for delivery">{{ old('note') }}</textarea>
                        </fieldset>
                </div>
                <div class="tf-page-cart-footer">
                    <div class="tf-cart-footer-inner">
                        <h5 class="fw-5 mb_20">Your order</h5>
                        <div class="tf-page-cart-checkout widget-wrap-checkout">
                            <ul class="wrap-checkout-product">
                                @if(session('cart') && count(session('cart')) > 0)
                                    @foreach(session('cart') as $id => $item)
                                    <li class="checkout-product-item">
                                        <figure class="img-product">
                                            <img src="{{ 
                                                isset($item['image']) && $item['image'] 
                                                    ? (str_starts_with($item['image'], 'http') 
                                                        ? $item['image'] 
                                                        : asset('storage/' . $item['image'])) 
                                                    : asset('storage/default-product.jpg') 
                                            }}" alt="product">
                                            <span class="quantity">{{ $item['quantity'] }}</span>
                                        </figure>
                                        <div class="content">
                                            <div class="info">
                                                <p class="name">{{ $item['name'] }}</p>
                                                @if(isset($item['size']) || isset($item['color']))
                                                    <span class="variant">
                                                        {{ isset($item['color']) ? $item['color'] : '' }}
                                                        {{ isset($item['color']) && isset($item['size']) ? ' / ' : '' }}
                                                        {{ isset($item['size']) ? $item['size'] : '' }}
                                                    </span>
                                                @endif
                                            </div>
                                            <span class="price">৳{{ number_format($item['price'] * $item['quantity']) }}</span>
                                        </div>
                                    </li>
                                    @endforeach
                                @else
                                    <li class="text-center py-4">
                                        <p class="text-muted">Your cart is empty</p>
                                    </li>
                                @endif
                            </ul>
                            
                            @if(session('cart') && count(session('cart')) > 0)
                                <div class="coupon-box">
                                    <input type="text" name="coupon_code" placeholder="Discount code" value="{{ old('coupon_code') }}">
                                    <button type="button" class="tf-btn btn-sm radius-3 btn-fill btn-icon animate-hover-btn" onclick="applyCoupon()">Apply</button>
                                </div>
                                
                                @php
                                    $subtotal = 0;
                                    if(session('cart')) {
                                        foreach(session('cart') as $item) {
                                            $subtotal += $item['price'] * $item['quantity'];
                                        }
                                    }
                                    $shipping = session('shipping_cost', 0);
                                    $discount = session('discount', 0);
                                    $total = $subtotal + $shipping - $discount;
                                @endphp
                                
                                <div class="checkout-summary">
                                    <div class="d-flex justify-content-between line pb_10">
                                        <span>Subtotal</span>
                                        <span>৳{{ number_format($subtotal) }}</span>
                                    </div>
                                    @if($shipping > 0)
                                        <div class="d-flex justify-content-between line pb_10">
                                            <span>Shipping</span>
                                            <span>৳{{ number_format($shipping) }}</span>
                                        </div>
                                    @endif
                                    @if($discount > 0)
                                        <div class="d-flex justify-content-between line pb_10">
                                            <span>Discount</span>
                                            <span class="text-success">-৳{{ number_format($discount) }}</span>
                                        </div>
                                    @endif
                                    <div class="d-flex justify-content-between line pb_20">
                                        <h6 class="fw-5">Total</h6>
                                        <h6 class="total fw-5">৳{{ number_format($total) }}</h6>
                                    </div>
                                </div>
                                
                                <div class="wd-check-payment">
                                    <div class="fieldset-radio mb_20">
                                        <input type="radio" name="payment_method" id="bank" class="tf-check" value="bank" {{ old('payment_method') == 'bank' ? 'checked' : '' }}>
                                        <label for="bank">Direct bank transfer</label>
                                    </div>
                                    <div class="fieldset-radio mb_20">
                                        <input type="radio" name="payment_method" id="delivery" class="tf-check" value="cash_on_delivery" {{ old('payment_method') == 'cash_on_delivery' ? 'checked' : 'checked' }}>
                                        <label for="delivery">Cash on delivery</label>
                                    </div>
                                    @error('payment_method')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    
                                    <p class="text_black-2 mb_20">Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our <a href="#" class="text-decoration-underline">privacy policy</a>.</p>
                                    
                                    <div class="box-checkbox fieldset-radio mb_20">
                                        <input type="checkbox" id="check-agree" class="tf-check" name="terms" required {{ old('terms') ? 'checked' : '' }}>
                                        <label for="check-agree" class="text_black-2">I have read and agree to the website <a href="#" class="text-decoration-underline">terms and conditions</a>.</label>
                                        @error('terms')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <button type="submit" class="tf-btn radius-3 btn-fill btn-icon animate-hover-btn justify-content-center">Place order</button>
                            @endif
                        </div>
                    </div>
                </div>
                    </form>
            </div>
        </div>
    </section>
    <!-- /page-cart -->

    <script>
        function applyCoupon() {
            const couponCode = document.querySelector('input[name="coupon_code"]').value;
            if (!couponCode) {
                alert('Please enter a coupon code');
                return;
            }
            
            fetch('{{ route("checkout.apply-coupon") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    coupon_code: couponCode
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Invalid coupon code');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while applying the coupon');
            });
        }
    </script>
@endsection