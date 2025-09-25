@extends('admin.layouts.app')

@section('title', 'Edit Unit')

@section('content')
<div class="d-sm-flex d-block align-items-center justify-content-between page-header-breadcrumb">
    <h4 class="fw-medium mb-0">Edit Unit</h4>
    <div class="ms-sm-1 ms-0">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.units.index') }}">Units</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Edit Unit: {{ $unit->name }}</div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.units.update', $unit) }}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Unit Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name', $unit->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Symbol <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('symbol') is-invalid @enderror" 
                                       name="symbol" value="{{ old('symbol', $unit->symbol) }}" required>
                                @error('symbol')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="weight" {{ old('type', $unit->type) === 'weight' ? 'selected' : '' }}>Weight</option>
                                    <option value="length" {{ old('type', $unit->type) === 'length' ? 'selected' : '' }}>Length</option>
                                    <option value="volume" {{ old('type', $unit->type) === 'volume' ? 'selected' : '' }}>Volume</option>
                                    <option value="area" {{ old('type', $unit->type) === 'area' ? 'selected' : '' }}>Area</option>
                                    <option value="quantity" {{ old('type', $unit->type) === 'quantity' ? 'selected' : '' }}>Quantity</option>
                                    <option value="time" {{ old('type', $unit->type) === 'time' ? 'selected' : '' }}>Time</option>
                                    <option value="temperature" {{ old('type', $unit->type) === 'temperature' ? 'selected' : '' }}>Temperature</option>
                                    <option value="other" {{ old('type', $unit->type) === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Base Unit</label>
                                <select class="form-select @error('base_unit_id') is-invalid @enderror" name="base_unit_id">
                                    <option value="">Select Base Unit (if applicable)</option>
                                    @foreach($allUnits->where('id', '!=', $unit->id) as $baseUnit)
                                        <option value="{{ $baseUnit->id }}" {{ old('base_unit_id', $unit->base_unit_id) == $baseUnit->id ? 'selected' : '' }}>
                                            {{ $baseUnit->name }} ({{ $baseUnit->symbol }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('base_unit_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Base Factor</label>
                                <input type="number" class="form-control @error('base_factor') is-invalid @enderror" 
                                       name="base_factor" step="0.000001" 
                                       value="{{ old('base_factor', $unit->base_factor) }}">
                                <small class="text-muted">Factor to convert to the base unit (e.g., 1000 for kg to g)</small>
                                @error('base_factor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Decimal Places</label>
                                <select class="form-select @error('decimal_places') is-invalid @enderror" name="decimal_places">
                                    <option value="0" {{ old('decimal_places', $unit->decimal_places ?? 2) == 0 ? 'selected' : '' }}>0</option>
                                    <option value="1" {{ old('decimal_places', $unit->decimal_places ?? 2) == 1 ? 'selected' : '' }}>1</option>
                                    <option value="2" {{ old('decimal_places', $unit->decimal_places ?? 2) == 2 ? 'selected' : '' }}>2</option>
                                    <option value="3" {{ old('decimal_places', $unit->decimal_places ?? 2) == 3 ? 'selected' : '' }}>3</option>
                                    <option value="4" {{ old('decimal_places', $unit->decimal_places ?? 2) == 4 ? 'selected' : '' }}>4</option>
                                </select>
                                @error('decimal_places')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          name="description" rows="3">{{ old('description', $unit->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input @error('is_active') is-invalid @enderror" 
                                           type="checkbox" name="is_active" 
                                           {{ old('is_active', $unit->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label">Active</label>
                                </div>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.units.index') }}" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i>Update Unit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
