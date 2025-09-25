{{-- All Commission Types View --}}
<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Description</th>
                <th class="text-end">Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            {{-- Sponsor Bonus Records --}}
            @foreach($commissionsData['sponsor_bonus']['records'] as $record)
            <tr>
                <td>{{ $record->created_at->format('M d, Y') }}</td>
                <td>
                    <span class="badge bg-primary-subtle text-primary commission-type-badge">
                        <i class="fas fa-handshake me-1"></i> Sponsor Bonus
                    </span>
                </td>
                <td>{{ $record->description ?? 'Direct referral commission' }}</td>
                <td class="text-end fw-bold text-primary">৳{{ number_format($record->amount, 2) }}</td>
                <td>
                    <span class="badge bg-success-subtle text-success status-badge">Completed</span>
                </td>
            </tr>
            @endforeach

            {{-- Generation Bonus Records --}}
            @foreach($commissionsData['generation_bonus']['records'] as $record)
            <tr>
                <td>{{ $record->created_at->format('M d, Y') }}</td>
                <td>
                    <span class="badge bg-info-subtle text-info commission-type-badge">
                        <i class="fas fa-layer-group me-1"></i> Generation Bonus
                    </span>
                </td>
                <td>{{ $record->description ?? 'Level commission bonus' }}</td>
                <td class="text-end fw-bold text-info">৳{{ number_format($record->amount, 2) }}</td>
                <td>
                    <span class="badge bg-success-subtle text-success status-badge">Completed</span>
                </td>
            </tr>
            @endforeach

            {{-- Club Bonus Records --}}
            @foreach($commissionsData['club_bonus']['records'] as $record)
            <tr>
                <td>{{ $record->created_at->format('M d, Y') }}</td>
                <td>
                    <span class="badge bg-warning-subtle text-warning commission-type-badge">
                        <i class="fas fa-crown me-1"></i> Club Bonus
                    </span>
                </td>
                <td>
                    Club Level {{ $record->level }} Bonus
                    @if($record->description)
                        - {{ $record->description }}
                    @endif
                </td>
                <td class="text-end fw-bold text-warning">৳{{ number_format($record->amount, 2) }}</td>
                <td>
                    <span class="badge bg-success-subtle text-success status-badge">Paid</span>
                </td>
            </tr>
            @endforeach

            {{-- Daily Pool Records --}}
            @foreach($commissionsData['daily_pool']['records'] as $record)
            <tr>
                <td>{{ $record->created_at->format('M d, Y') }}</td>
                <td>
                    <span class="badge bg-success-subtle text-success commission-type-badge">
                        <i class="fas fa-swimming-pool me-1"></i> Daily Pool
                    </span>
                </td>
                <td>{{ $record->description ?? 'Daily pool distribution' }}</td>
                <td class="text-end fw-bold text-success">৳{{ number_format($record->amount, 2) }}</td>
                <td>
                    <span class="badge bg-success-subtle text-success status-badge">Completed</span>
                </td>
            </tr>
            @endforeach

            {{-- Rank Bonus Records --}}
            @foreach($commissionsData['rank_bonus']['records'] as $record)
            <tr>
                <td>{{ $record->created_at->format('M d, Y') }}</td>
                <td>
                    <span class="badge bg-danger-subtle text-danger commission-type-badge">
                        <i class="fas fa-medal me-1"></i> Rank Bonus
                    </span>
                </td>
                <td>{{ $record->description ?? 'Rank achievement bonus' }}</td>
                <td class="text-end fw-bold text-danger">৳{{ number_format($record->amount, 2) }}</td>
                <td>
                    <span class="badge bg-success-subtle text-success status-badge">Completed</span>
                </td>
            </tr>
            @endforeach

            @if($commissionsData['sponsor_bonus']['records']->isEmpty() && 
                $commissionsData['generation_bonus']['records']->isEmpty() && 
                $commissionsData['club_bonus']['records']->isEmpty() && 
                $commissionsData['daily_pool']['records']->isEmpty() && 
                $commissionsData['rank_bonus']['records']->isEmpty())
            <tr>
                <td colspan="5" class="text-center py-4">
                    <div class="text-muted">
                        <i class="fas fa-receipt fs-1 mb-3 opacity-25"></i>
                        <p>No commission records found yet</p>
                        <small>Commissions will appear here once you start earning</small>
                    </div>
                </td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
