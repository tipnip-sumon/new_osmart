@extends('member.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row my-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">My Investments</h4>
                    <div>
                        <a href="{{ route('member.invest.create') }}" class="btn btn-primary">
                            <i class="bx bx-plus"></i> New Investment
                        </a>
                        <a href="{{ route('member.invest.dashboard') }}" class="btn btn-outline-primary">
                            <i class="bx bx-home"></i> Dashboard
                        </a>
                    </div>
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
                                        <th>Remaining</th>
                                        <th>Status</th>
                                        <th>Progress</th>
                                        <th>Next Return</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($investments as $investment)
                                        <tr>
                                            <td>{{ $investment->trx }}</td>
                                            <td>
                                                <div class="fw-bold">{{ $investment->plan->name ?? 'N/A' }}</div>
                                                <small class="text-muted">{{ $investment->plan->interest ?? 0 }}% - {{ $investment->period }} {{ $investment->time_name }}</small>
                                            </td>
                                            <td>{{ formatCurrency($investment->amount) }}</td>
                                            <td>{{ formatCurrency($investment->interest) }}</td>
                                            <td>{{ formatCurrency($investment->should_pay) }}</td>
                                            <td class="text-success">{{ formatCurrency($investment->paid) }}</td>
                                            <td class="text-warning">{{ formatCurrency($investment->remaining_amount) }}</td>
                                            <td>
                                                @if($investment->isCompleted())
                                                    <span class="badge bg-success">Completed</span>
                                                @elseif($investment->isActive())
                                                    <span class="badge bg-primary">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                                @if($investment->capital_status)
                                                    <br><small class="text-success">Capital Returned</small>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $progress = $investment->period > 0 ? ($investment->return_rec_time / $investment->period) * 100 : 0;
                                                @endphp
                                                <div class="progress mb-1" style="height: 8px;">
                                                    <div class="progress-bar 
                                                        @if($progress == 100) bg-success
                                                        @elseif($progress > 50) bg-info
                                                        @else bg-primary @endif" 
                                                        role="progressbar" 
                                                        style="width: {{ $progress }}%" 
                                                        aria-valuenow="{{ $progress }}" 
                                                        aria-valuemin="0" 
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <small class="text-muted">{{ $investment->return_rec_time }}/{{ $investment->period }} ({{ number_format($progress, 1) }}%)</small>
                                            </td>
                                            <td>
                                                @if($investment->isActive() && !$investment->isCompleted())
                                                    <small class="text-muted">{{ $investment->next_time->format('M d, Y') }}</small>
                                                    <br><small class="text-muted">{{ $investment->next_time->format('H:i') }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('member.invest.show', $investment) }}" class="btn btn-sm btn-info" title="View Details">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $investments->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bx bx-chart-line fa-4x text-muted mb-3"></i>
                            <h5>No Investments Found</h5>
                            <p class="text-muted">Start your investment journey today and watch your money grow!</p>
                            <a href="{{ route('member.invest.create') }}" class="btn btn-primary">
                                <i class="bx bx-plus"></i> Create Your First Investment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
