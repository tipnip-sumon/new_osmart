@extends('admin.layouts.app')

@section('title', 'Delivery Charges Management')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-truck me-2"></i>Delivery Charges Management
        </h1>
        <div>
            <a href="{{ route('admin.delivery-charges.create') }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50 me-1"></i>Add New Charge
            </a>
            <button type="button" class="btn btn-info btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#bulkImportModal">
                <i class="fas fa-upload fa-sm text-white-50 me-1"></i>Bulk Import
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>All Delivery Charges
            </h6>
        </div>
        <div class="card-body">
            <!-- Search and Filter Form -->
            <form method="GET" action="{{ route('admin.delivery-charges.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="search">Search</label>
                            <input type="text" name="search" id="search" class="form-control" 
                                   placeholder="Search by district, upazila, or ward" 
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="district">Filter by District</label>
                            <select name="district" id="district" class="form-control">
                                <option value="">All Districts</option>
                                @foreach($districts as $district)
                                    <option value="{{ $district }}" 
                                            {{ request('district') == $district ? 'selected' : '' }}>
                                        {{ $district }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i>Search
                                </button>
                                <a href="{{ route('admin.delivery-charges.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo me-1"></i>Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Data Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>District</th>
                            <th>Upazila</th>
                            <th>Ward</th>
                            <th>Charge</th>
                            <th>Estimated Time</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deliveryCharges as $charge)
                            <tr>
                                <td>{{ $charge->id }}</td>
                                <td><strong>{{ $charge->district }}</strong></td>
                                <td>{{ $charge->upazila ?: '-' }}</td>
                                <td>{{ $charge->ward ?: '-' }}</td>
                                <td><span class="badge bg-success">৳{{ number_format($charge->charge, 2) }}</span></td>
                                <td>{{ $charge->estimated_delivery_time }}</td>
                                <td>{{ $charge->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.delivery-charges.show', $charge) }}" 
                                           class="btn btn-info btn-sm" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.delivery-charges.edit', $charge) }}" 
                                           class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" 
                                                onclick="deleteCharge({{ $charge->id }})" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <form id="delete-form-{{ $charge->id }}" 
                                          action="{{ route('admin.delivery-charges.destroy', $charge) }}" 
                                          method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-box-open fa-3x mb-3"></i>
                                        <p>No delivery charges found.</p>
                                        <a href="{{ route('admin.delivery-charges.create') }}" class="btn btn-primary">
                                            Add First Delivery Charge
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($deliveryCharges->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $deliveryCharges->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Bulk Import Modal -->
<div class="modal fade" id="bulkImportModal" tabindex="-1" aria-labelledby="bulkImportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkImportModalLabel">
                    <i class="fas fa-upload me-2"></i>Bulk Import Delivery Charges
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.delivery-charges.bulk-import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="csv_file" class="form-label">CSV File</label>
                        <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv,.txt" required>
                        <div class="form-text">
                            Upload a CSV file with columns: District, Upazila, Ward, Charge, Estimated Time
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-1"></i>CSV Format:</h6>
                        <p class="mb-0">The CSV file should have the following columns in order:</p>
                        <ol class="mb-0">
                            <li><strong>District</strong> (required)</li>
                            <li><strong>Upazila</strong> (optional, leave blank if not applicable)</li>
                            <li><strong>Ward</strong> (optional, leave blank if not applicable)</li>
                            <li><strong>Charge</strong> (required, numeric value)</li>
                            <li><strong>Estimated Delivery Time</strong> (optional, defaults to "3-5 business days")</li>
                        </ol>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-1"></i>Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteCharge(id) {
    if (confirm('Are you sure you want to delete this delivery charge? This action cannot be undone.')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush