@extends('admin.layouts.app')

@section('title', 'Transfer History')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Transfer History</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('vendor.transfers.index') }}">Transfers</a></li>
                        <li class="breadcrumb-item active">History</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h4 class="mb-0">{{ $stats['total_transfers'] }}</h4>
                            <p class="text-muted mb-0">Total Transfers</p>
                        </div>
                        <div class="col-4">
                            <div class="text-end">
                                <i class="fas fa-exchange-alt text-primary" style="font-size: 24px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h4 class="mb-0">৳{{ number_format($stats['total_amount'], 2) }}</h4>
                            <p class="text-muted mb-0">Total Amount</p>
                        </div>
                        <div class="col-4">
                            <div class="text-end">
                                <i class="fas fa-money-bill-wave text-success" style="font-size: 24px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h4 class="mb-0">{{ $stats['completed_transfers'] }}</h4>
                            <p class="text-muted mb-0">Completed</p>
                        </div>
                        <div class="col-4">
                            <div class="text-end">
                                <i class="fas fa-check-circle text-success" style="font-size: 24px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h4 class="mb-0">{{ $stats['pending_transfers'] }}</h4>
                            <p class="text-muted mb-0">Pending</p>
                        </div>
                        <div class="col-4">
                            <div class="text-end">
                                <i class="fas fa-clock text-warning" style="font-size: 24px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transfer History Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Transfer History</h4>
                    <div class="card-header-actions">
                        <button class="btn btn-sm btn-success" onclick="exportTransfers()">
                            <i class="fas fa-download me-1"></i> Export CSV
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filter -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search transfers...">
                        </div>
                        <div class="col-md-3">
                            <select id="statusFilter" class="form-select">
                                <option value="">All Status</option>
                                <option value="completed">Completed</option>
                                <option value="pending">Pending</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" id="dateFrom" class="form-control" placeholder="From Date">
                        </div>
                        <div class="col-md-3">
                            <input type="date" id="dateTo" class="form-control" placeholder="To Date">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="transfersTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Recipient</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Reference</th>
                                    <th>Purpose</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- DataTables CSS and JS -->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
// Ensure jQuery is loaded
$(document).ready(function() {
    // Initialize DataTable directly since we loaded it via CDN
    initDataTable();
});

function initDataTable() {
    let table = $('#transfersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("vendor.transfers.history-data") }}',
            data: function(d) {
                d.search_value = $('#searchInput').val();
                d.status = $('#statusFilter').val();
                d.date_from = $('#dateFrom').val();
                d.date_to = $('#dateTo').val();
            }
        },
        columns: [
            {
                data: 'created_at',
                name: 'created_at',
                render: function(data) {
                    return new Date(data).toLocaleDateString() + ' ' + new Date(data).toLocaleTimeString();
                }
            },
            {
                data: 'to_user',
                name: 'to_user.name',
                render: function(data, type, row) {
                    return `
                        <div>
                            <strong>${data.name}</strong><br>
                            <small class="text-muted">${data.phone || 'N/A'}</small>
                        </div>
                    `;
                }
            },
            {
                data: 'amount',
                name: 'amount',
                render: function(data) {
                    return '৳' + parseFloat(data).toLocaleString();
                }
            },
            {
                data: 'status',
                name: 'status',
                render: function(data) {
                    let badgeClass = '';
                    switch(data) {
                        case 'completed':
                            badgeClass = 'bg-success';
                            break;
                        case 'pending':
                            badgeClass = 'bg-warning';
                            break;
                        case 'cancelled':
                            badgeClass = 'bg-danger';
                            break;
                        default:
                            badgeClass = 'bg-secondary';
                    }
                    return `<span class="badge ${badgeClass}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                }
            },
            {
                data: 'reference',
                name: 'reference',
                render: function(data) {
                    return data ? `<code>${data}</code>` : '-';
                }
            },
            {
                data: 'purpose',
                name: 'purpose',
                render: function(data) {
                    return data || '-';
                }
            }
        ],
        order: [[0, 'desc']],
        pageLength: 25,
        responsive: true,
        dom: 'Bfrtip',
        buttons: []
    });

    // Search functionality
    $('#searchInput').on('keyup', function() {
        table.ajax.reload();
    });

    $('#statusFilter, #dateFrom, #dateTo').on('change', function() {
        table.ajax.reload();
    });
}

function exportTransfers() {
    let params = new URLSearchParams({
        export: 'csv',
        search_value: $('#searchInput').val() || '',
        status: $('#statusFilter').val() || '',
        date_from: $('#dateFrom').val() || '',
        date_to: $('#dateTo').val() || ''
    });
    
    window.location.href = '{{ route("vendor.transfers.history-data") }}?' + params.toString();
}
</script>
@endsection