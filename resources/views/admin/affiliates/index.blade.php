@extends('admin.layouts.app')

@section('title', 'Affiliate Management')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h2 class="card-title mb-0">
                                <i class="fas fa-users-cog text-primary me-2"></i>
                                Affiliate Management
                            </h2>
                            <p class="text-muted mb-0">Manage affiliate users and their performance</p>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.affiliates.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Add Affiliate
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-users fa-2x text-primary mb-3"></i>
                    <h4 class="card-title">{{ $totalAffiliates ?? 0 }}</h4>
                    <p class="card-text text-muted">Total Affiliates</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-mouse-pointer fa-2x text-success mb-3"></i>
                    <h4 class="card-title">{{ $totalClicks ?? 0 }}</h4>
                    <p class="card-text text-muted">Total Clicks</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-percentage fa-2x text-warning mb-3"></i>
                    <h4 class="card-title">{{ $conversionRate ?? 0 }}%</h4>
                    <p class="card-text text-muted">Conversion Rate</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-dollar-sign fa-2x text-info mb-3"></i>
                    <h4 class="card-title">${{ number_format($totalCommissions ?? 0, 2) }}</h4>
                    <p class="card-text text-muted">Total Commissions</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Affiliates Table --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Affiliate Users</h5>
                </div>
                <div class="card-body">
                    @if(isset($affiliates) && $affiliates->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Clicks</th>
                                        <th>Conversions</th>
                                        <th>Total Earned</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($affiliates as $affiliate)
                                    <tr>
                                        <td>{{ $affiliate->id }}</td>
                                        <td>{{ $affiliate->name }}</td>
                                        <td>{{ $affiliate->email }}</td>
                                        <td>{{ $affiliate->clicks_count ?? 0 }}</td>
                                        <td>{{ $affiliate->commissions_count ?? 0 }}</td>
                                        <td>${{ number_format($affiliate->total_earned ?? 0, 2) }}</td>
                                        <td>
                                            <span class="badge badge-{{ $affiliate->status == 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($affiliate->status ?? 'inactive') }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.affiliates.show', $affiliate) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.affiliates.edit', $affiliate) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if(method_exists($affiliates, 'links'))
                            {{ $affiliates->links() }}
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No affiliates found</h5>
                            <p class="text-muted">Get started by adding your first affiliate user.</p>
                            <a href="{{ route('admin.affiliates.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Add First Affiliate
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTables if needed
    if ($.fn.DataTable) {
        $('.table').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[0, 'desc']]
        });
    }
});
</script>
@endpush
