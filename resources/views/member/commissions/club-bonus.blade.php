{{-- Club Bonus View --}}
<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th>Date</th>
                <th>Club</th>
                <th>Bonus Type</th>
                <th class="text-end">Amount</th>
                <th>Status</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            @forelse($commissionsData['club_bonus']['records'] as $record)
            <tr>
                <td>
                    <div class="d-flex flex-column">
                        <span class="fw-semibold">{{ $record->created_at->format('M d, Y') }}</span>
                        <small class="text-muted">{{ $record->created_at->format('h:i A') }}</small>
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-warning bg-opacity-10 text-warning me-2" style="width: 30px; height: 30px; font-size: 0.8rem;">
                            <i class="fas fa-crown"></i>
                        </div>
                        <div>
                            <span class="fw-semibold">Club Bonus</span>
                            <br>
                            <small class="text-muted">Level {{ $record->level }} bonus earning</small>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="badge bg-warning-subtle text-warning">
                        @if($record->bonus_type)
                            {{ ucfirst(str_replace('_', ' ', $record->bonus_type)) }}
                        @else
                            Club Earning
                        @endif
                    </span>
                </td>
                <td class="text-end">
                    <span class="fw-bold text-warning fs-5">৳{{ number_format($record->amount, 2) }}</span>
                </td>
                <td>
                    <span class="badge bg-success-subtle text-success status-badge">
                        <i class="fas fa-check-circle me-1"></i> Paid
                    </span>
                </td>
                <td>
                    <button class="btn btn-outline-warning btn-sm" onclick="viewClubBonusDetails({{ $record->id }})">
                        <i class="fas fa-eye me-1"></i> View
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-5">
                    <div class="text-muted">
                        <i class="fas fa-crown fs-1 mb-3 opacity-25"></i>
                        <h6>No Club Bonus Records</h6>
                        <p class="mb-0">You haven't earned any club bonuses yet.</p>
                        <small>Join our premium clubs to start earning exclusive bonuses!</small>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($commissionsData['club_bonus']['records']->isNotEmpty())
<div class="card-footer bg-light">
    <div class="row align-items-center">
        <div class="col-md-6">
            <small class="text-muted">
                Showing latest {{ $commissionsData['club_bonus']['records']->count() }} club bonus records
            </small>
        </div>
        <div class="col-md-6 text-end">
            <div class="d-flex justify-content-end gap-2">
                <span class="badge bg-warning-subtle text-warning">
                    Total Records: {{ $commissionsData['club_bonus']['count'] }}
                </span>
                <span class="badge bg-success-subtle text-success">
                    Total Amount: ৳{{ number_format($commissionsData['club_bonus']['total'], 2) }}
                </span>
            </div>
        </div>
    </div>
</div>
@endif
