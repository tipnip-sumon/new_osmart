@extends('user.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">My Investments</h4>
                    <a href="{{ route('user.investments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> New Investment
                    </a>
                </div>
                <div class="card-body">
                    @if($investments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Plan</th>
                                        <th>Amount</th>
                                        <th>Interest</th>
                                        <th>Should Pay</th>
                                        <th>Paid</th>
                                        <th>Status</th>
                                        <th>Next Return</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($investments as $investment)
                                        <tr>
                                            <td>{{ $investment->trx }}</td>
                                            <td>{{ $investment->plan->name ?? 'N/A' }}</td>
                                            <td>${{ number_format($investment->amount, 2) }}</td>
                                            <td>${{ number_format($investment->interest, 2) }}</td>
                                            <td>${{ number_format($investment->should_pay, 2) }}</td>
                                            <td>${{ number_format($investment->paid, 2) }}</td>
                                            <td>
                                                @if($investment->isCompleted())
                                                    <span class="badge bg-success">Completed</span>
                                                @elseif($investment->isActive())
                                                    <span class="badge bg-primary">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($investment->isActive() && !$investment->isCompleted())
                                                    {{ $investment->next_time->format('M d, Y H:i') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('user.investments.show', $investment) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        {{ $investments->links() }}
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                            <h5>No Investments Found</h5>
                            <p class="text-muted">Start your investment journey today!</p>
                            <a href="{{ route('user.investments.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create Investment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
