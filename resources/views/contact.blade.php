@extends('layouts.app')

@section('title', 'Contact Us - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="display-4 text-center mb-5">Contact Us</h1>
            
            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="card-title mb-4">Send us a Message</h3>
                            
                            <form action="{{ route('contact.submit') }}" method="POST">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="subject" class="form-label">Subject</label>
                                        <select class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" required>
                                            <option value="">Choose Subject</option>
                                            <option value="general" {{ old('subject') == 'general' ? 'selected' : '' }}>General Inquiry</option>
                                            <option value="business" {{ old('subject') == 'business' ? 'selected' : '' }}>Business Opportunity</option>
                                            <option value="support" {{ old('subject') == 'support' ? 'selected' : '' }}>Technical Support</option>
                                            <option value="partnership" {{ old('subject') == 'partnership' ? 'selected' : '' }}>Partnership</option>
                                            <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('subject')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="card-title mb-4">Get in Touch</h3>
                            
                            <div class="mb-4">
                                <h5><i class="bi bi-geo-alt-fill text-primary me-2"></i>Address</h5>
                                <p class="text-muted">
                                    {!! nl2br(e($contactInfo['address'])) !!}
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <h5><i class="bi bi-telephone-fill text-primary me-2"></i>Phone</h5>
                                <p class="text-muted">{{ $contactInfo['phone'] }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <h5><i class="bi bi-envelope-fill text-primary me-2"></i>Email</h5>
                                <p class="text-muted">{{ $contactInfo['email'] }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <h5><i class="bi bi-clock-fill text-primary me-2"></i>Business Hours</h5>
                                <div class="text-muted">
                                    @foreach($businessHours as $day => $hours)
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>{{ $day }}:</span>
                                            <span>{{ $hours }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
