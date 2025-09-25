@extends('admin.layouts.app')

@section('title', 'Brand Details')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Brand Details</h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.brands.index') }}">Brands</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $brand['name'] }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">{{ $brand['name'] }}</div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.brands.edit', $brand['id']) }}" class="btn btn-warning btn-sm">
                                <i class="bx bx-edit me-1"></i>Edit Brand
                            </a>
                            <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bx bx-arrow-back me-1"></i>Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="text-center mb-4">
                                    @if($brand->logo)
                                        @php
                                            $logoUrl = null;
                                            $logoPath = null;
                                            
                                            // Handle logo_data whether it's array or JSON string
                                            $logoData = $brand->logo_data;
                                            if (is_string($logoData)) {
                                                $logoData = json_decode($logoData, true);
                                            }
                                            
                                            // Try to get from logo_data first (new system)
                                            if ($logoData && is_array($logoData) && isset($logoData['sizes']['large']['url'])) {
                                                $logoUrl = $logoData['sizes']['large']['url'];
                                                $logoPath = $logoData['sizes']['large']['path'] ?? $brand->logo;
                                            } elseif ($logoData && is_array($logoData) && isset($logoData['sizes']['large']['path'])) {
                                                // If we have path but no URL, construct it
                                                $logoPath = $logoData['sizes']['large']['path'];
                                                $logoUrl = asset('storage/' . $logoPath);
                                            } else {
                                                // Fallback to simple logo field (old system)
                                                $logoPath = $brand->logo;
                                                $logoUrl = asset('storage/' . $logoPath);
                                            }
                                        @endphp
                                        <img src="{{ $logoUrl }}" 
                                             alt="{{ $brand->name }}" 
                                             class="img-fluid" 
                                             style="max-width: 200px;"
                                             data-debug-path="{{ $logoPath }}"
                                             data-full-url="{{ $logoUrl }}"
                                             onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDIwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIiBmaWxsPSIjZjhmOWZhIi8+CjxwYXRoIGQ9Ik0xMDAgNjBDNzguNDkwNiA2MCA2MCA3OC40OTA2IDYwIDEwMEM2MCAxMjEuNTA5IDc4LjQ5MDYgMTQwIDEwMCAxNDBDMTIxLjUwOSAxNDAgMTQwIDEyMS41MDkgMTQwIDEwMEMxNDAgNzguNDkwNiAxMjEuNTA5IDYwIDEwMCA2MFpNMTAwIDcyQzEwNi42MjcgNzIgMTEyIDc3LjM3MjYgMTEyIDg0QzExMiA5MC42Mjc0IDEwNi42MjcgOTYgMTAwIDk2QzkzLjM3MjYgOTYgODggOTAuNjI3NCA4OCA4NEM4OCA3Ny4zNzI2IDkzLjM3MjYgNzIgMTAwIDcyWk0xMDAgMTI4Qzg2IDEyOCA3NCAzMjIgNzIgMTEwQzcyIDEwNC41IDgyIDk4IDEwMCA5OEMxMTggOTggMTI4IDEwNC41IDEyOCAxMTBDMTI2IDEyMiAxMTQgMTI4IDEwMCAxMjhaIiBmaWxsPSIjNmM3NTdkIi8+Cjwvc3ZnPgo=';">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center bg-light text-muted" 
                                             style="width: 200px; height: 200px; border-radius: 8px; font-weight: bold; font-size: 48px;">
                                            {{ strtoupper(substr($brand->name, 0, 2)) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-8">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="150"><strong>Brand Name:</strong></td>
                                        <td>{{ $brand->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Slug:</strong></td>
                                        <td>{{ $brand->slug }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Description:</strong></td>
                                        <td>{{ $brand->description ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Website:</strong></td>
                                        <td>
                                            @if($brand->website_url)
                                                <a href="{{ $brand->website_url }}" target="_blank">{{ $brand->website_url }}</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td>{{ $brand->email ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Phone:</strong></td>
                                        <td>{{ $brand->phone ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            <span class="badge bg-{{ $brand->status === 'Active' ? 'success' : 'danger' }}">
                                                {{ $brand->status }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Featured:</strong></td>
                                        <td>
                                            <span class="badge bg-{{ $brand->is_featured ? 'warning' : 'secondary' }}">
                                                {{ $brand->is_featured ? 'Yes' : 'No' }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Products Count:</strong></td>
                                        <td>0 products</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Created:</strong></td>
                                        <td>{{ $brand->created_at->format('F j, Y') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
