{{-- Generation Bonus View --}}
<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Level</th>
                <th class="text-end">Amount</th>
                <th>Status</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            @forelse($commissionsData['generation_bonus']['records'] as $record)
            <tr>
                <td>
                    <div class="d-flex flex-column">
                        <span class="fw-semibold">{{ $record->created_at->format('M d, Y') }}</span>
                        <small class="text-muted">{{ $record->created_at->format('h:i A') }}</small>
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-info bg-opacity-10 text-info me-2" style="width: 30px; height: 30px; font-size: 0.8rem;">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div>
                            <span class="fw-semibold">Generation Commission</span>
                            <br>
                            <small class="text-muted">{{ $record->description ?? 'Level bonus earned' }}</small>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="badge bg-info-subtle text-info">
                        @if(stripos($record->description, 'level') !== false)
                            Level {{ preg_replace('/.*level\s(\d+).*/i', '$1', $record->description) }}
                        @else
                            Multi-Level
                        @endif
                    </span>
                </td>
                <td class="text-end">
                    <span class="fw-bold text-info fs-5">৳{{ number_format($record->amount, 2) }}</span>
                </td>
                <td>
                    <span class="badge bg-success-subtle text-success status-badge">
                        <i class="fas fa-check-circle me-1"></i> Completed
                    </span>
                </td>
                <td>
                    <button class="btn btn-outline-info btn-sm" onclick="viewTransactionDetails({{ $record->id }})">
                        <i class="fas fa-eye me-1"></i> View
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-5">
                    <div class="text-muted">
                        <i class="fas fa-layer-group fs-1 mb-3 opacity-25"></i>
                        <h6>No Generation Bonus Records</h6>
                        <p class="mb-0">You haven't earned any generation bonuses yet.</p>
                        <small>Build your downline network to earn from multiple levels!</small>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($commissionsData['generation_bonus']['records']->isNotEmpty())
<div class="card-footer bg-light">
    <div class="row align-items-center">
        <div class="col-md-6">
            <small class="text-muted">
                Showing latest {{ $commissionsData['generation_bonus']['records']->count() }} generation bonus records
            </small>
        </div>
        <div class="col-md-6 text-end">
            <div class="d-flex justify-content-end gap-2">
                <span class="badge bg-info-subtle text-info">
                    Total Records: {{ $commissionsData['generation_bonus']['count'] }}
                </span>
                <span class="badge bg-success-subtle text-success">
                    Total Amount: ৳{{ number_format($commissionsData['generation_bonus']['total'], 2) }}
                </span>
            </div>
        </div>
    </div>
</div>
@endif
