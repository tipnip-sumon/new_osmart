@extends('admin.layouts.app')

@section('title', 'Edit Plan - ' . $plan->name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Edit Plan - {{ $plan->name }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.plans.index') }}">Plans</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.plans.update', $plan) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <!-- Basic Information -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Basic Information</h5>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Plan Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $plan->name) }}" 
                                           placeholder="Enter plan name" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fixed_amount" class="form-label">Fixed Amount (BDT) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('fixed_amount') is-invalid @enderror" 
                                           id="fixed_amount" name="fixed_amount" value="{{ old('fixed_amount', $plan->fixed_amount) }}" 
                                           placeholder="0.00" required>
                                    @error('fixed_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="minimum" class="form-label">Minimum Amount (BDT) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('minimum') is-invalid @enderror" 
                                           id="minimum" name="minimum" value="{{ old('minimum', $plan->minimum) }}" 
                                           placeholder="0.00" required>
                                    @error('minimum')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="maximum" class="form-label">Maximum Amount (BDT) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('maximum') is-invalid @enderror" 
                                           id="maximum" name="maximum" value="{{ old('maximum', $plan->maximum) }}" 
                                           placeholder="0.00" required>
                                    @error('maximum')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Enter plan description">{{ old('description', $plan->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Plan Image Upload -->
                        <div class="mb-3">
                            <label for="image" class="form-label">Plan/Package Image</label>
                            
                            @php
                                // Prepare existing images for display (adapted for plans)
                                $existingImages = [];
                                if ($plan->image_data) {
                                    $imageData = is_string($plan->image_data) ? json_decode($plan->image_data, true) : $plan->image_data;
                                    if (is_array($imageData)) {
                                        $existingImages[] = $imageData;
                                    }
                                } elseif ($plan->image) {
                                    $existingImages[] = $plan->image;
                                }
                            @endphp
                            
                            @if(!empty($existingImages))
                                <div class="mt-4">
                                    <h6 class="mb-3">
                                        <i class="ri-image-line me-2"></i>Current Image 
                                        <span class="badge bg-secondary">{{ count($existingImages) }}</span>
                                    </h6>
                                    
                                    <div class="current-images-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px;">
                                        @foreach($existingImages as $index => $image)
                                            <div class="current-image-item" style="position: relative; border: 1px solid #dee2e6; border-radius: 8px; overflow: hidden; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                                @if(is_array($image) && isset($image['sizes']))
                                                    <!-- New format with multiple sizes -->
                                                    @php
                                                        // Safely get image URL with proper type checking for new format
                                                        $imageUrl = '';
                                                        if (isset($image['sizes']) && is_array($image['sizes'])) {
                                                            // Try medium size first (best for display)
                                                            if (isset($image['sizes']['medium']['url']) && is_string($image['sizes']['medium']['url'])) {
                                                                $imageUrl = $image['sizes']['medium']['url'];
                                                            } elseif (isset($image['sizes']['large']['url']) && is_string($image['sizes']['large']['url'])) {
                                                                $imageUrl = $image['sizes']['large']['url'];
                                                            } elseif (isset($image['sizes']['original']['url']) && is_string($image['sizes']['original']['url'])) {
                                                                $imageUrl = $image['sizes']['original']['url'];
                                                            } elseif (isset($image['sizes']['small']['url']) && is_string($image['sizes']['small']['url'])) {
                                                                $imageUrl = $image['sizes']['small']['url'];
                                                            }
                                                            // Fallback to path-based approach if URL not found
                                                            elseif (isset($image['sizes']['medium']['path']) && is_string($image['sizes']['medium']['path'])) {
                                                                $imageUrl = asset('storage/' . $image['sizes']['medium']['path']);
                                                            } elseif (isset($image['sizes']['original']['path']) && is_string($image['sizes']['original']['path'])) {
                                                                $imageUrl = asset('storage/' . $image['sizes']['original']['path']);
                                                            }
                                                        } elseif (isset($image['path']) && is_string($image['path'])) {
                                                            $imageUrl = asset('uploads/' . $image['path']);
                                                        }
                                                        
                                                        // Fallback if no valid URL found
                                                        if (empty($imageUrl)) {
                                                            $imageUrl = asset('admin-assets/images/media/1.jpg'); // Default placeholder
                                                        }
                                                    @endphp
                                                    <img src="{{ $imageUrl }}" alt="Plan Image {{ $index + 1 }}" 
                                                         style="width: 100%; height: 150px; object-fit: cover;">
                                                    <div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.7); color: white; padding: 5px; font-size: 0.75rem;">
                                                        <i class="ri-star-line text-warning"></i> Plan Image
                                                    </div>
                                                @else
                                                    <!-- Legacy format - simple path -->
                                                    @php
                                                        // Handle legacy format with type checking
                                                        $legacyImageUrl = '';
                                                        if (is_string($image)) {
                                                            // Try storage path first, then uploads
                                                            $legacyImageUrl = asset('storage/' . $image);
                                                        } elseif (is_array($image) && isset($image['url']) && is_string($image['url'])) {
                                                            $legacyImageUrl = $image['url'];
                                                        } elseif (is_array($image) && isset($image['path']) && is_string($image['path'])) {
                                                            $legacyImageUrl = asset('storage/' . $image['path']);
                                                        } else {
                                                            $legacyImageUrl = asset('admin-assets/images/media/1.jpg'); // Default placeholder
                                                        }
                                                    @endphp
                                                    <img src="{{ $legacyImageUrl }}" alt="Plan Image {{ $index + 1 }}" 
                                                         style="width: 100%; height: 150px; object-fit: cover;">
                                                    <div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.7); color: white; padding: 5px; font-size: 0.75rem;">
                                                        <i class="ri-star-line text-warning"></i> Plan Image
                                                    </div>
                                                @endif
                                                <!-- Remove button -->
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        onclick="removeExistingImage({{ $index }})"
                                                        style="position: absolute; top: 5px; right: 5px; border-radius: 50%; width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                                    <i class="ri-close-line" style="font-size: 12px;"></i>
                                                </button>
                                                <!-- Undo button (initially hidden) -->
                                                <button type="button" class="btn btn-sm btn-success undo-remove-btn" 
                                                        onclick="undoRemoveImage({{ $index }})"
                                                        style="position: absolute; top: 40px; right: 5px; border-radius: 4px; padding: 4px 8px; font-size: 10px; display: none;">
                                                    <i class="ri-refresh-line" style="font-size: 10px;"></i> Undo
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="alert alert-info mt-3">
                                        <i class="ri-information-line me-2"></i>You can remove the current image and upload a new one, or leave it as is.
                                    </div>
                                </div>
                            @endif
                            
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            <div class="form-text">
                                Upload an image for this plan/package. Supported formats: JPEG, PNG, JPG, GIF, WebP. Max size: 2MB.
                                @if($plan->image || $plan->image_data)
                                    Leave empty to keep current image.
                                @endif
                            </div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <!-- Image Preview -->
                            <div id="image-preview" class="mt-2" style="display: none;">
                                <p class="mb-1"><strong>New Image Preview:</strong></p>
                                <img id="preview-img" src="" alt="Image Preview" 
                                     style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px; padding: 5px;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Point System -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Point System</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="points" class="form-label">Points Required <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('points') is-invalid @enderror" 
                                           id="points" name="points" value="{{ old('points', $plan->points) }}" 
                                           placeholder="0" required>
                                    @error('points')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="point_value" class="form-label">Point Value (BDT) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('point_value') is-invalid @enderror" 
                                           id="point_value" name="point_value" value="{{ old('point_value', $plan->point_value) }}" 
                                           placeholder="6.00" required>
                                    @error('point_value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Total Value</label>
                                    <input type="text" class="form-control" id="total_value" readonly 
                                           placeholder="Points × Point Value">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Commission System -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Commission System</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="spot_commission_rate" class="form-label">Spot Commission Rate (%) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('spot_commission_rate') is-invalid @enderror" 
                                           id="spot_commission_rate" name="spot_commission_rate" value="{{ old('spot_commission_rate', $plan->spot_commission_rate) }}" 
                                           placeholder="15.00" required>
                                    @error('spot_commission_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Set to 0 to use fixed sponsor amount instead</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fixed_sponsor" class="form-label">Fixed Sponsor Amount (BDT) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('fixed_sponsor') is-invalid @enderror" 
                                           id="fixed_sponsor" name="fixed_sponsor" value="{{ old('fixed_sponsor', $plan->fixed_sponsor) }}" 
                                           placeholder="0.00" required>
                                    @error('fixed_sponsor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Used when spot commission rate is 0</small>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <strong>Commission Calculation:</strong><br>
                            <span id="commission_preview">If spot commission rate > 0: (Points × Point Value × Spot Commission Rate) / 100<br>Otherwise: Fixed Sponsor Amount</span>
                        </div>
                    </div>
                </div>

                <!-- Interest Settings -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Interest Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="interest" class="form-label">Interest Rate <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('interest') is-invalid @enderror" 
                                           id="interest" name="interest" value="{{ old('interest', $plan->interest) }}" 
                                           placeholder="0.00" required>
                                    @error('interest')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="interest_type" class="form-label">Interest Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('interest_type') is-invalid @enderror" 
                                            id="interest_type" name="interest_type" required>
                                        <option value="1" {{ old('interest_type', $plan->interest_type) == '1' ? 'selected' : '' }}>Percentage (%)</option>
                                        <option value="0" {{ old('interest_type', $plan->interest_type) == '0' ? 'selected' : '' }}>Fixed Amount (BDT)</option>
                                    </select>
                                    @error('interest_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="time" class="form-label">Duration <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('time') is-invalid @enderror" 
                                           id="time" name="time" value="{{ old('time', $plan->time) }}" 
                                           placeholder="0" required>
                                    @error('time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="time_name" class="form-label">Time Unit <span class="text-danger">*</span></label>
                                    <select class="form-select @error('time_name') is-invalid @enderror" 
                                            id="time_name" name="time_name" required>
                                        <option value="days" {{ old('time_name', $plan->time_name) == 'days' ? 'selected' : '' }}>Days</option>
                                        <option value="weeks" {{ old('time_name', $plan->time_name) == 'weeks' ? 'selected' : '' }}>Weeks</option>
                                        <option value="months" {{ old('time_name', $plan->time_name) == 'months' ? 'selected' : '' }}>Months</option>
                                        <option value="years" {{ old('time_name', $plan->time_name) == 'years' ? 'selected' : '' }}>Years</option>
                                    </select>
                                    @error('time_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="repeat_time" class="form-label">Repeat Time</label>
                                    <input type="number" class="form-control @error('repeat_time') is-invalid @enderror" 
                                           id="repeat_time" name="repeat_time" value="{{ old('repeat_time', $plan->repeat_time) }}" 
                                           placeholder="0">
                                    @error('repeat_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Sidebar -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Plan Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                       id="status" name="status" {{ old('status', $plan->status) ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">Active Status</label>
                            </div>
                            <small class="text-muted">Enable this plan for users</small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                       id="featured" name="featured" {{ old('featured', $plan->featured) ? 'checked' : '' }}>
                                <label class="form-check-label" for="featured">Featured Plan</label>
                            </div>
                            <small class="text-muted">Show as featured plan</small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                       id="point_based" name="point_based" {{ old('point_based', $plan->point_based) ? 'checked' : '' }}>
                                <label class="form-check-label" for="point_based">Point-Based Plan</label>
                            </div>
                            <small class="text-muted">Uses point system instead of money</small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                       id="instant_activation" name="instant_activation" {{ old('instant_activation', $plan->instant_activation) ? 'checked' : '' }}>
                                <label class="form-check-label" for="instant_activation">Instant Activation</label>
                            </div>
                            <small class="text-muted">Activate immediately upon joining</small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                       id="capital_back" name="capital_back" {{ old('capital_back', $plan->capital_back) ? 'checked' : '' }}>
                                <label class="form-check-label" for="capital_back">Capital Back</label>
                            </div>
                            <small class="text-muted">Return capital to user</small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                       id="lifetime" name="lifetime" {{ old('lifetime', $plan->lifetime) ? 'checked' : '' }}>
                                <label class="form-check-label" for="lifetime">Lifetime Plan</label>
                            </div>
                            <small class="text-muted">No expiration date</small>
                        </div>
                    </div>
                </div>

                <!-- Plan Info -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Plan Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <small class="text-muted">Plan ID:</small>
                            <div class="fw-medium">{{ $plan->id }}</div>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Created:</small>
                            <div>{{ $plan->created_at->format('M d, Y') }}</div>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Updated:</small>
                            <div>{{ $plan->updated_at->format('M d, Y') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line align-bottom me-1"></i> Update Plan
                            </button>
                            <a href="{{ route('admin.plans.show', $plan) }}" class="btn btn-info">
                                <i class="ri-eye-line align-bottom me-1"></i> View Plan
                            </a>
                            <a href="{{ route('admin.plans.index') }}" class="btn btn-secondary">
                                <i class="ri-arrow-left-line align-bottom me-1"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pointsInput = document.getElementById('points');
    const pointValueInput = document.getElementById('point_value');
    const totalValueInput = document.getElementById('total_value');
    const spotCommissionInput = document.getElementById('spot_commission_rate');
    const fixedSponsorInput = document.getElementById('fixed_sponsor');
    const commissionPreview = document.getElementById('commission_preview');

    function updateCalculations() {
        // Update total value
        const points = parseFloat(pointsInput.value) || 0;
        const pointValue = parseFloat(pointValueInput.value) || 0;
        const totalValue = points * pointValue;
        totalValueInput.value = `৳${totalValue.toFixed(2)}`;

        // Update commission preview
        const spotCommission = parseFloat(spotCommissionInput.value) || 0;
        const fixedSponsor = parseFloat(fixedSponsorInput.value) || 0;
        
        let preview = '';
        if (spotCommission > 0) {
            const commissionAmount = (points * pointValue * spotCommission) / 100;
            preview = `Percentage-based: (${points} × ৳${pointValue} × ${spotCommission}%) = ৳${commissionAmount.toFixed(2)}`;
        } else {
            preview = `Fixed amount: ৳${fixedSponsor.toFixed(2)}`;
        }
        
        if (spotCommission > 0 && fixedSponsor > 0) {
            preview += `<br><small class="text-warning">Note: Both values set, percentage will be used</small>`;
        }
        
        commissionPreview.innerHTML = preview;
    }

    // Add event listeners
    pointsInput.addEventListener('input', updateCalculations);
    pointValueInput.addEventListener('input', updateCalculations);
    spotCommissionInput.addEventListener('input', updateCalculations);
    fixedSponsorInput.addEventListener('input', updateCalculations);

    // Initial calculation
    updateCalculations();

    // Image preview functionality
    document.getElementById('image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('image-preview');
        const previewImg = document.getElementById('preview-img');
        
        if (file) {
            // Check file size (2MB limit)
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                this.value = '';
                preview.style.display = 'none';
                return;
            }
            
            // Check file type
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                alert('Please select a valid image file (JPEG, PNG, JPG, GIF, WebP)');
                this.value = '';
                preview.style.display = 'none';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });

    // Image removal functionality
    window.removeExistingImage = function(index) {
        const imageItem = document.querySelectorAll('.current-image-item')[index];
        const removeBtn = imageItem.querySelector('.btn-danger');
        const undoBtn = imageItem.querySelector('.undo-remove-btn');
        
        // Hide the image with opacity and add overlay
        imageItem.style.opacity = '0.5';
        imageItem.style.position = 'relative';
        
        // Add overlay
        const overlay = document.createElement('div');
        overlay.className = 'removed-overlay';
        overlay.style.cssText = `
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(220, 53, 69, 0.8);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            z-index: 10;
        `;
        overlay.textContent = 'WILL BE REMOVED';
        imageItem.appendChild(overlay);
        
        // Hide remove button, show undo button
        removeBtn.style.display = 'none';
        undoBtn.style.display = 'block';
        
        // Add hidden input to mark for removal
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'remove_existing_images[]';
        hiddenInput.value = index;
        hiddenInput.className = 'remove-marker-' + index;
        document.querySelector('form').appendChild(hiddenInput);
    };
    
    window.undoRemoveImage = function(index) {
        const imageItem = document.querySelectorAll('.current-image-item')[index];
        const removeBtn = imageItem.querySelector('.btn-danger');
        const undoBtn = imageItem.querySelector('.undo-remove-btn');
        const overlay = imageItem.querySelector('.removed-overlay');
        
        // Restore image appearance
        imageItem.style.opacity = '1';
        
        // Remove overlay
        if (overlay) {
            overlay.remove();
        }
        
        // Show remove button, hide undo button
        removeBtn.style.display = 'flex';
        undoBtn.style.display = 'none';
        
        // Remove hidden input
        const hiddenInput = document.querySelector('.remove-marker-' + index);
        if (hiddenInput) {
            hiddenInput.remove();
        }
    };
});
</script>
@endpush
