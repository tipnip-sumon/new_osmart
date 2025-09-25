@extends('layouts.ecomus')

@section('title', 'Frequently Asked Questions')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h1 class="mb-4">Frequently Asked Questions</h1>
            
            <div class="accordion" id="faqAccordion">
                <!-- Order Related Questions -->
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                How can I track my order?
                            </button>
                        </h5>
                    </div>
                    <div id="collapseOne" class="collapse" data-bs-parent="#faqAccordion">
                        <div class="card-body">
                            Once your order ships, you'll receive an email with a tracking number. You can also check your order status by logging into your account and visiting the "My Orders" section.
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                What payment methods do you accept?
                            </button>
                        </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" data-bs-parent="#faqAccordion">
                        <div class="card-body">
                            We accept all major credit cards (Visa, MasterCard, American Express, Discover), PayPal, Apple Pay, Google Pay, and bank transfers.
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header" id="headingThree">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                Can I modify or cancel my order?
                            </button>
                        </h5>
                    </div>
                    <div id="collapseThree" class="collapse" data-bs-parent="#faqAccordion">
                        <div class="card-body">
                            You can modify or cancel your order within 1 hour of placing it. After this time, orders enter processing and cannot be changed. Please contact customer service immediately if you need to make changes.
                        </div>
                    </div>
                </div>
                
                <!-- Shipping Questions -->
                <div class="card">
                    <div class="card-header" id="headingFour">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">
                                How long does shipping take?
                            </button>
                        </h5>
                    </div>
                    <div id="collapseFour" class="collapse" data-bs-parent="#faqAccordion">
                        <div class="card-body">
                            Standard shipping takes 5-7 business days, Express shipping takes 2-3 business days, and Overnight shipping delivers in 1 business day. International shipping varies by location (7-14 business days).
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header" id="headingFive">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive">
                                Do you offer free shipping?
                            </button>
                        </h5>
                    </div>
                    <div id="collapseFive" class="collapse" data-bs-parent="#faqAccordion">
                        <div class="card-body">
                            Yes! We offer free standard shipping on all orders over $75 within the United States. International orders have varying shipping costs based on location and weight.
                        </div>
                    </div>
                </div>
                
                <!-- Returns & Exchanges -->
                <div class="card">
                    <div class="card-header" id="headingSix">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix">
                                What is your return policy?
                            </button>
                        </h5>
                    </div>
                    <div id="collapseSix" class="collapse" data-bs-parent="#faqAccordion">
                        <div class="card-body">
                            We accept returns within 30 days of delivery. Items must be unused, in original condition, and in original packaging. Return shipping costs are the responsibility of the customer unless the item was defective or incorrect.
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header" id="headingSeven">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven">
                                How do I return an item?
                            </button>
                        </h5>
                    </div>
                    <div id="collapseSeven" class="collapse" data-bs-parent="#faqAccordion">
                        <div class="card-body">
                            To return an item, log into your account, go to "My Orders," select the order containing the item you want to return, and follow the return instructions. You'll receive a prepaid return label for defective or incorrect items.
                        </div>
                    </div>
                </div>
                
                <!-- Product Questions -->
                <div class="card">
                    <div class="card-header" id="headingEight">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight">
                                How do I know what size to order?
                            </button>
                        </h5>
                    </div>
                    <div id="collapseEight" class="collapse" data-bs-parent="#faqAccordion">
                        <div class="card-body">
                            Each product page includes detailed size charts and measurements. If you're between sizes, we generally recommend sizing up. You can also contact our customer service team for personalized sizing advice.
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header" id="headingNine">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNine">
                                Are your products authentic?
                            </button>
                        </h5>
                    </div>
                    <div id="collapseNine" class="collapse" data-bs-parent="#faqAccordion">
                        <div class="card-body">
                            Yes, we guarantee that all products sold on our platform are 100% authentic. We work directly with authorized vendors and brands to ensure authenticity and quality.
                        </div>
                    </div>
                </div>
                
                <!-- Account Questions -->
                <div class="card">
                    <div class="card-header" id="headingTen">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTen">
                                How do I create an account?
                            </button>
                        </h5>
                    </div>
                    <div id="collapseTen" class="collapse" data-bs-parent="#faqAccordion">
                        <div class="card-body">
                            Click "Sign Up" at the top of any page and fill in your information. You can also create an account during checkout. Having an account allows you to track orders, save favorites, and checkout faster.
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-5 text-center">
                <h4>Still have questions?</h4>
                <p>Can't find what you're looking for? Our customer service team is here to help.</p>
                <a href="{{ route('contact.show') }}" class="btn btn-primary">Contact Us</a>
            </div>
        </div>
    </div>
</div>
@endsection