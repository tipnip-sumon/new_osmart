<div class="table-responsive">
    <table class="table table-hover">
        <thead class="thead-light">
            <tr>
                <th width="40">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input select-all-checkbox" id="selectAll">
                        <label class="custom-control-label" for="selectAll"></label>
                    </div>
                </th>
                <th>Product</th>
                <th>Warehouse</th>
                <th>Quantity</th>
                <th>Condition</th>
                <th>Stock Status</th>
                <th>Value</th>
                <th>Location</th>
                <th>Last Updated</th>
                <th width="150">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($inventories as $inventory)
                <tr class="inventory-row" data-id="{{ $inventory->id }}">
                    <td>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" 
                                   class="custom-control-input item-checkbox" 
                                   id="item{{ $inventory->id }}"
                                   value="{{ $inventory->id }}">
                            <label class="custom-control-label" for="item{{ $inventory->id }}"></label>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            @if($inventory->product->images && count($inventory->product->images) > 0)
                                <img src="{{ Storage::url($inventory->product->images[0]) }}" 
                                     alt="{{ $inventory->product->name }}" 
                                     class="rounded mr-2"
                                     style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded mr-2 d-flex align-items-center justify-content-center"
                                     style="width: 40px; height: 40px;">
                                    <i class="ti-package text-muted"></i>
                                </div>
                            @endif
                            <div>
                                <div class="font-weight-bold">{{ $inventory->product->name }}</div>
                                <small class="text-muted">
                                    SKU: {{ $inventory->product->sku ?? 'N/A' }}
                                    @if($inventory->batch_number)
                                        | Batch: {{ $inventory->batch_number }}
                                    @endif
                                </small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div>
                            <strong>{{ $inventory->warehouse->name }}</strong>
                            @if($inventory->location)
                                <br><small class="text-muted">{{ $inventory->location }}</small>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="quantity-info">
                            <span class="quantity-main 
                                @if($inventory->quantity <= 0) text-danger
                                @elseif($inventory->quantity <= $inventory->min_stock_level) text-warning
                                @else text-success
                                @endif">
                                {{ number_format($inventory->quantity) }}
                            </span>
                            <div class="quantity-details">
                                <small>Available: {{ number_format($inventory->available_quantity) }}</small>
                                @if($inventory->reserved_quantity > 0)
                                    <br><small>Reserved: {{ number_format($inventory->reserved_quantity) }}</small>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge condition-badge
                            @switch($inventory->condition)
                                @case('new') badge-success @break
                                @case('used') badge-info @break
                                @case('damaged') badge-warning @break
                                @case('expired') badge-danger @break
                                @default badge-secondary
                            @endswitch">
                            {{ ucfirst($inventory->condition) }}
                        </span>
                    </td>
                    <td>
                        @if($inventory->quantity <= 0)
                            <span class="badge badge-danger stock-badge">
                                <i class="ti-x-circle mr-1"></i>Out of Stock
                            </span>
                        @elseif($inventory->quantity <= $inventory->min_stock_level)
                            <span class="badge badge-warning stock-badge">
                                <i class="ti-alert-triangle mr-1"></i>Low Stock
                            </span>
                        @elseif($inventory->quantity >= $inventory->max_stock_level)
                            <span class="badge badge-info stock-badge">
                                <i class="ti-trending-up mr-1"></i>Overstock
                            </span>
                        @else
                            <span class="badge badge-success stock-badge">
                                <i class="ti-check-circle mr-1"></i>In Stock
                            </span>
                        @endif
                        
                        @if($inventory->expiry_date && $inventory->expiry_date <= now()->addDays(30))
                            <br><span class="badge badge-warning stock-badge mt-1">
                                <i class="ti-clock mr-1"></i>Expiring Soon
                            </span>
                        @endif
                    </td>
                    <td>
                        <div>
                            <strong>${{ number_format($inventory->total_value, 2) }}</strong>
                            <br><small class="text-muted">
                                @ ${{ number_format($inventory->cost_per_unit, 2) }} each
                            </small>
                        </div>
                    </td>
                    <td>
                        @if($inventory->location)
                            <span class="badge badge-light">{{ $inventory->location }}</span>
                        @else
                            <span class="text-muted">Not specified</span>
                        @endif
                        
                        @if($inventory->expiry_date)
                            <br><small class="text-muted">
                                Exp: {{ $inventory->expiry_date->format('M d, Y') }}
                            </small>
                        @endif
                    </td>
                    <td>
                        <small class="text-muted">
                            {{ $inventory->updated_at->format('M d, Y') }}
                            <br>{{ $inventory->updated_at->format('H:i') }}
                        </small>
                        @if($inventory->last_counted_at)
                            <br><small class="text-success">
                                Counted: {{ $inventory->last_counted_at->format('M d') }}
                            </small>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('inventory.show', $inventory) }}" 
                               class="btn btn-sm btn-outline-primary"
                               title="View Details"
                               data-toggle="tooltip">
                                <i class="ti-eye"></i>
                            </a>
                            
                            <a href="{{ route('inventory.edit', $inventory) }}" 
                               class="btn btn-sm btn-outline-secondary"
                               title="Edit"
                               data-toggle="tooltip">
                                <i class="ti-pencil"></i>
                            </a>
                            
                            <button type="button" 
                                    class="btn btn-sm btn-outline-info adjust-btn"
                                    data-id="{{ $inventory->id }}"
                                    data-product="{{ $inventory->product->name }}"
                                    data-quantity="{{ $inventory->quantity }}"
                                    title="Adjust Stock"
                                    data-toggle="tooltip">
                                <i class="ti-plus-minus"></i>
                            </button>
                            
                            <button type="button" 
                                    class="btn btn-sm {{ $inventory->is_active ? 'btn-outline-danger' : 'btn-outline-success' }} toggle-status-btn"
                                    data-id="{{ $inventory->id }}"
                                    data-status="{{ $inventory->is_active }}"
                                    title="{{ $inventory->is_active ? 'Deactivate' : 'Activate' }}"
                                    data-toggle="tooltip">
                                <i class="{{ $inventory->is_active ? 'ti-power-off' : 'ti-power-on' }}"></i>
                            </button>
                            
                            <button type="button" 
                                    class="btn btn-sm btn-outline-danger delete-btn"
                                    data-id="{{ $inventory->id }}"
                                    data-product="{{ $inventory->product->name }}"
                                    title="Delete"
                                    data-toggle="tooltip">
                                <i class="ti-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center py-4">
                        <div class="text-muted">
                            <i class="ti-package fa-3x mb-3"></i>
                            <h5>No inventory items found</h5>
                            <p>Try adjusting your search criteria or add new inventory items.</p>
                            <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                                <i class="ti-plus mr-2"></i>Add New Item
                            </a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($inventories->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="text-muted">
            Showing {{ $inventories->firstItem() }} to {{ $inventories->lastItem() }} of {{ $inventories->total() }} results
        </div>
        
        <div>
            {{ $inventories->appends(request()->query())->links() }}
        </div>
    </div>
@endif

@if($inventories->count() > 0)
    <div class="mt-3">
        <button type="button" class="btn btn-sm btn-outline-primary bulk-action-btn" disabled>
            <i class="ti-settings mr-2"></i>Bulk Actions
        </button>
        <button type="button" class="btn btn-sm btn-outline-success" id="refresh-table">
            <i class="ti-refresh mr-2"></i>Refresh
        </button>
    </div>
@endif

<script>
// Initialize tooltips for this partial
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
    
    // Refresh table functionality
    $('#refresh-table').on('click', function() {
        window.location.reload();
    });
});
</script>
