<!-- Modal Shopping Cart -->
<div class="modal fullRight fade modal-shopping-cart" id="shoppingCart">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="header">
                <div class="title fw-5">Shopping Cart</div>
                <span class="icon-close icon-close-popup" data-bs-dismiss="modal"></span>
            </div>
            <div class="wrap">
                <div class="tf-mini-cart-threshold">
                    <div class="tf-progress-bar">
                        <span style="width: 50%;"></span>
                    </div>
                    <div class="tf-progress-msg">
                        Buy <span class="price fw-6">{{ formatCurrency(75.00) }}</span> more to enjoy <span class="fw-6">Free Shipping</span>
                    </div>
                </div>
                <div class="tf-mini-cart-wrap">
                    <div class="tf-mini-cart-main">
                        <div class="tf-mini-cart-sroll">
                            <div class="tf-mini-cart-items" id="cart-items-container">
                                <!-- Cart items will be loaded here via JavaScript -->
                                <div class="tf-mini-cart-empty text-center py-4" style="display: none;">
                                    <div class="mb-3">
                                        <i class="icon icon-bag" style="font-size: 3rem; color: #ddd;"></i>
                                    </div>
                                    <h5 class="mb-2">Your cart is empty</h5>
                                    <p class="text-muted mb-3">Start shopping to fill your cart</p>
                                    <a href="{{ route('shop.index') }}" class="tf-btn btn-fill radius-3">Continue Shopping</a>
                                </div>
                            </div>
                            <div class="tf-mini-cart-bottom">
                                <div class="tf-mini-cart-tool">
                                    <div class="tf-mini-cart-tool-btn btn-add-note">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="18" viewBox="0 0 16 18" fill="currentColor"><path d="M5.12187 16.4582H2.78952C2.02045 16.4582 1.39476 15.8325 1.39476 15.0634V2.78952C1.39476 2.02045 2.02045 1.39476 2.78952 1.39476H11.3634C12.1325 1.39476 12.7582 2.02045 12.7582 2.78952V7.07841C12.7582 7.46357 13.0704 7.77579 13.4556 7.77579C13.8407 7.77579 14.1529 7.46357 14.1529 7.07841V2.78952C14.1529 1.25138 12.9016 0 11.3634 0H2.78952C1.25138 0 0 1.25138 0 2.78952V15.0634C0 16.6015 1.25138 17.8529 2.78952 17.8529H5.12187C5.50703 17.8529 5.81925 17.5407 5.81925 17.1555C5.81925 16.7704 5.50703 16.4582 5.12187 16.4582Z"></path><path d="M15.3882 10.0971L10.2896 15.1956C10.1138 15.3714 9.86633 15.4704 9.60819 15.4704H6.58887C6.20371 15.4704 5.89148 15.1582 5.89148 14.773V11.7537C5.89148 11.4955 5.99052 11.2481 6.16632 11.0723L11.2649 5.97376C12.0251 5.21353 13.2563 5.21353 14.0166 5.97376L15.3882 7.34536C16.1484 8.10559 16.1484 9.33687 15.3882 10.0971ZM7.28597 12.7435V13.0755H7.61801L12.4358 8.2577L12.1038 7.92566L7.28597 12.7435ZM13.118 7.01053L13.45 7.34258L14.3732 6.41936L14.0412 6.08732C13.7739 5.82001 13.3394 5.82001 13.072 6.08732L13.118 7.01053Z"></path></svg>
                                        Add Order Note
                                    </div>
                                    <div class="tf-mini-cart-tool-btn btn-estimate-shipping">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="18" viewBox="0 0 26 18" fill="currentColor"><path fill-rule="evenodd" clip-rule="evenodd" d="M0 0.811989C0 0.36354 0.36354 0 0.811989 0H15.4278C15.8763 0 16.2398 0.36354 16.2398 0.811989V10.8936H18.0548C18.2963 10.8936 18.5226 10.9956 18.6837 11.1734L25.1694 18.2806C25.3692 18.4994 25.3494 18.8441 25.1228 19.0409C24.9239 19.2154 24.6301 19.2104 24.4371 19.0297L18.2705 12.2676H16.2398V17.1883C16.2398 17.6367 15.8763 18.0003 15.4278 18.0003H0.811989C0.36354 18.0003 0 17.6367 0 17.1883V0.811989ZM1.62398 1.62398V16.3763H14.6158V1.62398H1.62398Z"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M6 4.87805C6 4.42961 6.36354 4.06607 6.81199 4.06607H8.62398C9.07243 4.06607 9.43597 4.42961 9.43597 4.87805C9.43597 5.3265 9.07243 5.69004 8.62398 5.69004H6.81199C6.36354 5.69004 6 5.3265 6 4.87805Z"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M6 9.02439C6 8.57595 6.36354 8.21241 6.81199 8.21241H8.62398C9.07243 8.21241 9.43597 8.57595 9.43597 9.02439C9.43597 9.47284 9.07243 9.83638 8.62398 9.83638H6.81199C6.36354 9.83638 6 9.47284 6 9.02439Z"></path></svg>
                                        Estimate Shipping
                                    </div>
                                </div>
                                <div class="tf-mini-cart-bottom-wrap">
                                    <div class="tf-cart-totals-discounts">
                                        <div class="tf-cart-total">Subtotal</div>
                                        <div class="tf-totals-total-value fw-6" id="cart-subtotal">{{ formatCurrency(0.00) }}</div>
                                    </div>
                                    <div class="tf-cart-tax">Taxes and <a href="#">shipping</a> calculated at checkout</div>
                                    <div class="tf-mini-cart-view-checkout">
                                        <a href="{{ route('cart.index') }}" class="tf-btn btn-outline radius-3 link w-100 justify-content-center">View Cart</a>
                                        <a href="{{ route('checkout.index') }}" class="tf-btn btn-fill animate-hover-btn radius-3 w-100 justify-content-center"><span>Check Out</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tf-mini-cart-tool-openable add-note">
                        <div class="tf-mini-cart-tool-header">
                            <div class="tf-mini-cart-tool-text">
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="18" viewBox="0 0 16 18" fill="currentColor"><path d="M5.12187 16.4582H2.78952C2.02045 16.4582 1.39476 15.8325 1.39476 15.0634V2.78952C1.39476 2.02045 2.02045 1.39476 2.78952 1.39476H11.3634C12.1325 1.39476 12.7582 2.02045 12.7582 2.78952V7.07841C12.7582 7.46357 13.0704 7.77579 13.4556 7.77579C13.8407 7.77579 14.1529 7.46357 14.1529 7.07841V2.78952C14.1529 1.25138 12.9016 0 11.3634 0H2.78952C1.25138 0 0 1.25138 0 2.78952V15.0634C0 16.6015 1.25138 17.8529 2.78952 17.8529H5.12187C5.50703 17.8529 5.81925 17.5407 5.81925 17.1555C5.81925 16.7704 5.50703 16.4582 5.12187 16.4582Z"></path><path d="M15.3882 10.0971L10.2896 15.1956C10.1138 15.3714 9.86633 15.4704 9.60819 15.4704H6.58887C6.20371 15.4704 5.89148 15.1582 5.89148 14.773V11.7537C5.89148 11.4955 5.99052 11.2481 6.16632 11.0723L11.2649 5.97376C12.0251 5.21353 13.2563 5.21353 14.0166 5.97376L15.3882 7.34536C16.1484 8.10559 16.1484 9.33687 15.3882 10.0971ZM7.28597 12.7435V13.0755H7.61801L12.4358 8.2577L12.1038 7.92566L7.28597 12.7435ZM13.118 7.01053L13.45 7.34258L14.3732 6.41936L14.0412 6.08732C13.7739 5.82001 13.3394 5.82001 13.072 6.08732L13.118 7.01053Z"></path></svg>
                                </div>
                                <span>Add Order Note</span>
                            </div>
                            <button type="button" class="tf-mini-cart-tool-close-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M15 5L5 15M5 5l10 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </button>
                        </div>
                        <div class="tf-mini-cart-tool-content">
                            <label for="Cart-note" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--text-primary);">Order Note</label>
                            <textarea name="note" id="Cart-note" placeholder="How can we help you?"></textarea>
                            <button type="button" class="tf-btn btn-outline radius-3 justify-content-center">Save</button>
                        </div>
                    </div>
                    <div class="tf-mini-cart-tool-openable estimate-shipping">
                        <div class="tf-mini-cart-tool-header">
                            <div class="tf-mini-cart-tool-text">
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="18" viewBox="0 0 26 18" fill="currentColor"><path fill-rule="evenodd" clip-rule="evenodd" d="M0 0.811989C0 0.36354 0.36354 0 0.811989 0H15.4278C15.8763 0 16.2398 0.36354 16.2398 0.811989V10.8936H18.0548C18.2963 10.8936 18.5226 10.9956 18.6837 11.1734L25.1694 18.2806C25.3692 18.4994 25.3494 18.8441 25.1228 19.0409C24.9239 19.2154 24.6301 19.2104 24.4371 19.0297L18.2705 12.2676H16.2398V17.1883C16.2398 17.6367 15.8763 18.0003 15.4278 18.0003H0.811989C0.36354 18.0003 0 17.6367 0 17.1883V0.811989ZM1.62398 1.62398V16.3763H14.6158V1.62398H1.62398Z"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M6 4.87805C6 4.42961 6.36354 4.06607 6.81199 4.06607H8.62398C9.07243 4.06607 9.43597 4.42961 9.43597 4.87805C9.43597 5.3265 9.07243 5.69004 8.62398 5.69004H6.81199C6.36354 5.69004 6 5.3265 6 4.87805Z"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M6 9.02439C6 8.57595 6.36354 8.21241 6.81199 8.21241H8.62398C9.07243 8.21241 9.43597 8.57595 9.43597 9.02439C9.43597 9.47284 9.07243 9.83638 8.62398 9.83638H6.81199C6.36354 9.83638 6 9.47284 6 9.02439Z"></path></svg>
                                </div>
                                <span>Estimate Shipping</span>
                            </div>
                            <button type="button" class="tf-mini-cart-tool-close-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M15 5L5 15M5 5l10 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </button>
                        </div>
                        <div class="tf-mini-cart-tool-content">
                            <div class="field">
                                <p>District</p>
                                <select class="tf-select w-100" id="ShippingDistrict" name="district">
                                    <option value="">Select District</option>
                                    <option value="Dhaka">Dhaka</option>
                                    <option value="Chittagong">Chittagong</option>
                                    <option value="Sylhet">Sylhet</option>
                                    <option value="Rajshahi">Rajshahi</option>
                                    <option value="Khulna">Khulna</option>
                                    <option value="Barisal">Barisal</option>
                                    <option value="Rangpur">Rangpur</option>
                                    <option value="Mymensingh">Mymensingh</option>
                                </select>
                            </div>
                            <div class="field" id="upazila-field" style="display: none;">
                                <p>Upazila</p>
                                <select class="tf-select w-100" id="ShippingUpazila" name="upazila">
                                    <option value="">Select Upazila</option>
                                </select>
                            </div>
                            <div class="field" id="ward-field" style="display: none;">
                                <p>Ward</p>
                                <select class="tf-select w-100" id="ShippingWard" name="ward">
                                    <option value="">Select Ward</option>
                                </select>
                            </div>
                            <div id="shipping-result" class="shipping-result" style="display: none;">
                                <div class="shipping-charge" id="shipping-charge-display"></div>
                                <div class="delivery-time" id="delivery-time-display"></div>
                            </div>
                            <div class="tf-cart-tool-btns justify-content-center">
                                <button type="button" class="tf-mini-cart-tool-primary w-100" id="calculate-shipping-btn">Calculate Shipping</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Modal Shopping Cart -->