@extends('admin.layouts.app')

@section('title', 'Approved Vendors')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Approved Vendors</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.vendors.index') }}">Vendors</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Approved</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Approved Vendors List -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Active Approved Vendors</div>
                    </div>
                    <div class="card-body">
                        <!-- Approved vendors content here -->
                        <div class="alert alert-success" role="alert">
                            <i class="bx bx-check-circle"></i> Showing all approved and active vendors
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
