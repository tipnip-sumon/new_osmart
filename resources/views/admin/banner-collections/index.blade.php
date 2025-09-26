@extends('admin.layouts.app')

@section('title', 'Banner Collections')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/sortable.min.css" rel="stylesheet">
<style>
.sortable-ghost {
    opacity: 0.4;
}
.sortable-drag {
    opacity: 0.8;
}
.drag-handle {
    cursor: move;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Banner Collections</h1>
        <a href="{{ route('admin.banner-collections.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Banner
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Banner Collections</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="50">Order</th>
                            <th width="80">Image</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Button</th>
                            <th>Countdown</th>
                            <th width="80">Status</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="sortable-banners">
                        @forelse($banners as $banner)
                        <tr data-id="{{ $banner->id }}">
                            <td class="text-center drag-handle">
                                <i class="fas fa-grip-vertical"></i>
                                <span class="ml-2">{{ $banner->sort_order }}</span>
                            </td>
                            <td>
                                @if($banner->image)
                                <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" 
                                     class="img-thumbnail" style="width: 60px; height: 40px; object-fit: cover;">
                                @else
                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                     style="width: 60px; height: 40px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $banner->title }}</strong>
                                @if($banner->background_color !== '#f8f9fa')
                                <br><small class="text-muted">BG: {{ $banner->background_color }}</small>
                                @endif
                            </td>
                            <td>{{ Str::limit($banner->description, 80) }}</td>
                            <td>
                                <span class="badge badge-info">{{ $banner->button_text }}</span>
                                @if($banner->button_url)
                                <br><small class="text-muted">{{ Str::limit($banner->button_url, 30) }}</small>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($banner->show_countdown)
                                    @if($banner->is_countdown_active)
                                    <span class="badge badge-success">Active</span>
                                    <br><small class="text-muted">{{ $banner->countdown_end_date->format('M j, Y H:i') }}</small>
                                    @else
                                    <span class="badge badge-warning">Expired</span>
                                    @endif
                                @else
                                <span class="badge badge-secondary">Disabled</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <form method="POST" action="{{ route('admin.banner-collections.toggle-status', $banner) }}" 
                                      style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $banner->is_active ? 'btn-success' : 'btn-secondary' }}">
                                        {{ $banner->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td>
                                <a href="{{ route('admin.banner-collections.show', $banner) }}" 
                                   class="btn btn-info btn-sm" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.banner-collections.edit', $banner) }}" 
                                   class="btn btn-primary btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-sm" 
                                        onclick="confirmDelete({{ $banner->id }})" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>

                                <!-- Delete Form -->
                                <form id="delete-form-{{ $banner->id }}" 
                                      action="{{ route('admin.banner-collections.destroy', $banner) }}" 
                                      method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <p class="text-muted mb-0">No banner collections found.</p>
                                <a href="{{ route('admin.banner-collections.create') }}" class="btn btn-primary btn-sm mt-2">
                                    Create First Banner
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($banners->hasPages())
            <div class="d-flex justify-content-center">
                {{ $banners->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
<script>
// Initialize sortable
if (document.getElementById('sortable-banners')) {
    new Sortable(document.getElementById('sortable-banners'), {
        handle: '.drag-handle',
        animation: 150,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-drag',
        onEnd: function(evt) {
            const items = [];
            document.querySelectorAll('#sortable-banners tr').forEach((row, index) => {
                const id = row.getAttribute('data-id');
                if (id) {
                    items.push({
                        id: id,
                        sort_order: index + 1
                    });
                }
            });

            // Update sort order via AJAX
            fetch('{{ route("admin.banner-collections.update-order") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ items: items })
            }).then(response => {
                if (response.ok) {
                    // Update order numbers in UI
                    document.querySelectorAll('#sortable-banners tr').forEach((row, index) => {
                        const orderCell = row.querySelector('.drag-handle span');
                        if (orderCell) {
                            orderCell.textContent = index + 1;
                        }
                    });
                }
            }).catch(error => {
                console.error('Error updating order:', error);
                // Reload page on error
                location.reload();
            });
        }
    });
}

function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this banner collection? This action cannot be undone.')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush