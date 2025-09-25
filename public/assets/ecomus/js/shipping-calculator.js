/**
 * Shipping Calculator for Cart Modal
 * Dynamic shipping charge calculation using DeliveryCharge model and Bangladesh locations JSON
 */

class ShippingCalculator {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.locationData = null;
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadBangladeshLocations();
        
        // Add keyboard support
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeShippingTools();
            }
        });
    }

    bindEvents() {
        // District change event
        document.addEventListener('change', (e) => {
            if (e.target.id === 'ShippingDistrict') {
                this.handleDistrictChange(e.target.value);
            } else if (e.target.id === 'ShippingUpazila') {
                this.handleUpazilaChange(e.target.value);
            } else if (e.target.id === 'ShippingWard') {
                this.calculateShipping();
            }
        });

        // Calculate shipping button
        document.addEventListener('click', (e) => {
            if (e.target.id === 'calculate-shipping-btn' || e.target.closest('#calculate-shipping-btn')) {
                e.preventDefault();
                this.calculateShipping();
            }
        });

        // Tool button events
        document.addEventListener('click', (e) => {
            if (e.target.closest('.btn-estimate-shipping')) {
                e.preventDefault();
                this.openShippingEstimate();
            }
            if (e.target.closest('.btn-add-note')) {
                e.preventDefault();
                this.openAddNote();
            }
            // Multiple selectors for close button - handle all possible click targets
            if (e.target.closest('.tf-mini-cart-tool-close-btn') || 
                e.target.classList.contains('tf-mini-cart-tool-close-btn') ||
                e.target.parentElement?.classList.contains('tf-mini-cart-tool-close-btn')) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Close button clicked');
                this.closeShippingTools();
            }
        });

        // Direct button binding as backup
        this.bindCloseButtons();
    }

    bindCloseButtons() {
        // Wait for DOM to be ready and bind close buttons directly
        setTimeout(() => {
            const closeButtons = document.querySelectorAll('.tf-mini-cart-tool-close-btn');
            closeButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Direct close button clicked');
                    this.closeShippingTools();
                });
            });
            console.log(`Bound ${closeButtons.length} close buttons directly`);
        }, 100);
    }

    async loadBangladeshLocations() {
        try {
            const response = await fetch('/data/bangladesh-locations.json');
            this.locationData = await response.json();
            this.loadDistricts();
            console.log('Bangladesh locations loaded:', this.locationData.length, 'districts');
        } catch (error) {
            console.error('Error loading Bangladesh locations:', error);
            // Fallback to API if JSON fails
            this.loadDistrictsFromAPI();
        }
    }

    loadDistricts() {
        const districtSelect = document.getElementById('ShippingDistrict');
        if (districtSelect && this.locationData) {
            // Clear existing options except first
            districtSelect.innerHTML = '<option value="">Select District</option>';
            
            this.locationData.forEach(district => {
                const option = document.createElement('option');
                option.value = district.name;
                option.textContent = district.name;
                districtSelect.appendChild(option);
            });
            
            console.log('Districts loaded from JSON');
        }
    }

    async loadDistrictsFromAPI() {
        try {
            const response = await fetch('/api/delivery/districts');
            const data = await response.json();
            
            if (data.success && data.districts) {
                const districtSelect = document.getElementById('ShippingDistrict');
                if (districtSelect) {
                    // Clear existing options except first
                    districtSelect.innerHTML = '<option value="">Select District</option>';
                    
                    data.districts.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district;
                        option.textContent = district;
                        districtSelect.appendChild(option);
                    });
                }
            }
        } catch (error) {
            console.error('Error loading districts from API:', error);
        }
    }

    handleDistrictChange(district) {
        const upazilaField = document.getElementById('upazila-field');
        const wardField = document.getElementById('ward-field');
        const upazilaSelect = document.getElementById('ShippingUpazila');
        const wardSelect = document.getElementById('ShippingWard');
        
        if (!district) {
            upazilaField.style.display = 'none';
            wardField.style.display = 'none';
            this.hideShippingResult();
            return;
        }

        // Find district in location data
        const districtData = this.locationData?.find(d => d.name === district);
        
        // Clear upazila and ward selections
        upazilaSelect.innerHTML = '<option value="">Select Upazila</option>';
        wardSelect.innerHTML = '<option value="">Select Ward</option>';
        wardField.style.display = 'none';
        
        if (districtData && districtData.upazilas && districtData.upazilas.length > 0) {
            districtData.upazilas.forEach(upazila => {
                const option = document.createElement('option');
                option.value = upazila.name;
                option.textContent = upazila.name;
                upazilaSelect.appendChild(option);
            });
            upazilaField.style.display = 'block';
            console.log(`Loaded ${districtData.upazilas.length} upazilas for ${district}`);
        } else {
            upazilaField.style.display = 'none';
            console.log(`No upazilas found for ${district}`);
            // Calculate shipping for district level
            this.calculateShipping();
        }
    }

    handleUpazilaChange(upazila) {
        const district = document.getElementById('ShippingDistrict').value;
        const wardField = document.getElementById('ward-field');
        const wardSelect = document.getElementById('ShippingWard');
        
        if (!upazila) {
            wardField.style.display = 'none';
            this.calculateShipping();
            return;
        }

        // Find district and upazila in location data
        const districtData = this.locationData?.find(d => d.name === district);
        const upazilaData = districtData?.upazilas?.find(u => u.name === upazila);
        
        wardSelect.innerHTML = '<option value="">Select Ward</option>';
        
        if (upazilaData && upazilaData.unions && upazilaData.unions.length > 0) {
            upazilaData.unions.forEach(union => {
                const option = document.createElement('option');
                option.value = union;
                option.textContent = union;
                wardSelect.appendChild(option);
            });
            wardField.style.display = 'block';
            console.log(`Loaded ${upazilaData.unions.length} unions/wards for ${upazila}`);
        } else {
            wardField.style.display = 'none';
            console.log(`No unions/wards found for ${upazila}`);
        }
        
        // Calculate shipping for upazila level
        this.calculateShipping();
    }

    async calculateShipping() {
        const district = document.getElementById('ShippingDistrict')?.value;
        const upazila = document.getElementById('ShippingUpazila')?.value;
        const ward = document.getElementById('ShippingWard')?.value;
        
        console.log('Calculating shipping for:', { district, upazila, ward });
        
        if (!district) {
            this.hideShippingResult();
            return;
        }

        // Show loading state
        this.showLoadingState();

        try {
            let url = `/api/delivery/charge?district=${encodeURIComponent(district)}`;
            if (upazila) url += `&upazila=${encodeURIComponent(upazila)}`;
            if (ward) url += `&ward=${encodeURIComponent(ward)}`;
            
            console.log('Fetching from URL:', url);
            
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                }
            });
            
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Response data:', data);
            
            if (data.success) {
                this.showShippingResult(data.formatted_charge, data.estimated_delivery_time);
            } else {
                this.showError(data.error || 'Unable to calculate shipping');
            }
        } catch (error) {
            console.error('Error calculating shipping:', error);
            this.showError('Network error occurred. Please try again.');
        }
    }

    showLoadingState() {
        const resultDiv = document.getElementById('shipping-result');
        const chargeDisplay = document.getElementById('shipping-charge-display');
        const timeDisplay = document.getElementById('delivery-time-display');
        
        if (resultDiv && chargeDisplay && timeDisplay) {
            chargeDisplay.textContent = 'Calculating...';
            timeDisplay.textContent = 'Please wait...';
            chargeDisplay.style.color = '#6B7280';
            resultDiv.style.display = 'block';
        }
    }

    showShippingResult(charge, deliveryTime) {
        const resultDiv = document.getElementById('shipping-result');
        const chargeDisplay = document.getElementById('shipping-charge-display');
        const timeDisplay = document.getElementById('delivery-time-display');
        
        if (resultDiv && chargeDisplay && timeDisplay) {
            chargeDisplay.textContent = `Shipping: ${charge}`;
            timeDisplay.textContent = `Estimated delivery: ${deliveryTime}`;
            chargeDisplay.style.color = '#4F46E5';
            resultDiv.style.display = 'block';
            
            console.log('Shipping result displayed:', { charge, deliveryTime });
        }
    }

    hideShippingResult() {
        const resultDiv = document.getElementById('shipping-result');
        if (resultDiv) {
            resultDiv.style.display = 'none';
        }
    }

    showError(message) {
        const resultDiv = document.getElementById('shipping-result');
        const chargeDisplay = document.getElementById('shipping-charge-display');
        const timeDisplay = document.getElementById('delivery-time-display');
        
        if (resultDiv && chargeDisplay && timeDisplay) {
            chargeDisplay.textContent = 'Error calculating shipping';
            timeDisplay.textContent = message;
            chargeDisplay.style.color = '#ef4444';
            resultDiv.style.display = 'block';
            
            console.error('Shipping error displayed:', message);
        }
    }

    openShippingEstimate() {
        this.closeShippingTools(); // Close any open tools first
        const estimateSection = document.querySelector('.tf-mini-cart-tool-openable.estimate-shipping');
        if (estimateSection) {
            estimateSection.classList.add('active');
            // Load locations when opening if not already loaded
            if (!this.locationData) {
                this.loadBangladeshLocations();
            }
            
            // Bind close button after opening
            setTimeout(() => {
                this.bindCloseButtons();
                
                // Focus on the district select
                const districtSelect = document.getElementById('ShippingDistrict');
                if (districtSelect) {
                    districtSelect.focus();
                }
            }, 300);
            
            console.log('Shipping estimate opened');
        }
    }

    openAddNote() {
        this.closeShippingTools(); // Close any open tools first
        const noteSection = document.querySelector('.tf-mini-cart-tool-openable.add-note');
        if (noteSection) {
            noteSection.classList.add('active');
            
            // Bind close button after opening
            setTimeout(() => {
                this.bindCloseButtons();
                
                // Focus on the textarea
                const textarea = document.getElementById('Cart-note');
                if (textarea) {
                    textarea.focus();
                }
            }, 300);
            
            console.log('Add note opened');
        }
    }

    closeShippingTools() {
        console.log('closeShippingTools called');
        const openSections = document.querySelectorAll('.tf-mini-cart-tool-openable.active');
        console.log('Found open sections:', openSections.length);
        
        if (openSections.length > 0) {
            openSections.forEach((section, index) => {
                console.log(`Closing section ${index}:`, section.classList);
                section.classList.remove('active');
            });
            console.log('Tools closed');
        } else {
            console.log('No open sections to close');
        }
    }
}

// Initialize shipping calculator when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new ShippingCalculator();
});