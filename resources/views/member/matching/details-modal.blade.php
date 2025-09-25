<div class="row">
    <!-- Transaction Summary -->
    <div class="col-12 mb-4">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0">
                    <i class="bx bx-receipt me-2"></i>Transaction Summary
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td><strong>Transaction ID:</strong></td>
                                <td>#{{ str_pad($matching->id, 6, '0', STR_PAD_LEFT) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Date & Time:</strong></td>
                                <td>{{ $matching->created_at->format('M d, Y h:i A') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    @php
                                        $statusColor = match($matching->status) {
                                            'processed' => 'success',
                                            'completed' => 'success', 
                                            'paid' => 'primary',
                                            'pending' => 'warning',
                                            'cancelled' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusColor }}">{{ ucfirst($matching->status) }}</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td><strong>Processing Date:</strong></td>
                                <td>{{ $matching->processing_date ? $matching->processing_date->format('M d, Y') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Notes:</strong></td>
                                <td>{{ $matching->notes ?? 'Daily matching process' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Batch ID:</strong></td>
                                <td>{{ $matching->batch_id ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Point Analysis -->
    <div class="col-md-6 mb-4">
        <div class="card border-info">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">
                    <i class="bx bx-coin-stack me-2"></i>Point Analysis at Time of Processing
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Left Leg Points:</span>
                        <span class="fw-bold text-primary">{{ number_format($leftPoints, 0) }}</span>
                    </div>
                    <div class="progress mb-3" style="height: 8px;">
                        <div class="progress-bar bg-primary" style="width: {{ min(100, ($leftPoints / 100) * 100) }}%"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Right Leg Points:</span>
                        <span class="fw-bold text-success">{{ number_format($rightPoints, 0) }}</span>
                    </div>
                    <div class="progress mb-3" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: {{ min(100, ($rightPoints / 100) * 100) }}%"></div>
                    </div>
                </div>

                <hr>
                
                <div class="text-center">
                    <div class="mb-2">
                        <span class="text-muted">Points Matched:</span>
                    </div>
                    <div class="display-6 fw-bold text-info">{{ number_format($matchedPoints, 0) }}</div>
                    <small class="text-muted">Points</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Breakdown -->
    <div class="col-md-6 mb-4">
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0">
                    <i class="bx bx-money me-2"></i>Financial Breakdown
                </h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td>Matched Points:</td>
                        <td class="text-end">{{ number_format($matchedPoints, 0) }}</td>
                    </tr>
                    <tr>
                        <td>Point Value (6 Tk each):</td>
                        <td class="text-end">৳{{ number_format($pointValue, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Commission Rate:</td>
                        <td class="text-end">10%</td>
                    </tr>
                    <tr class="table-success">
                        <td><strong>Actual Bonus Earned:</strong></td>
                        <td class="text-end"><strong>৳{{ number_format($matching->matching_bonus, 2) }}</strong></td>
                    </tr>
                    @php
                        $correctCalculation = $pointValue * 0.1;
                    @endphp
                    @if(abs($correctCalculation - $matching->matching_bonus) > 0.01)
                        <tr class="table-info">
                            <td><em>Current System Calculation:</em></td>
                            <td class="text-end"><em>৳{{ number_format($correctCalculation, 2) }}</em></td>
                        </tr>
                    @endif
                </table>

                <div class="alert alert-info mt-3 mb-0">
                    <i class="bx bx-info-circle me-2"></i>
                    @php
                        $correctCalculation = $pointValue * 0.1; // 10% of point value
                    @endphp
                    <small>Calculation: {{ number_format($matchedPoints, 0) }} points × ৳6 × 10% = ৳{{ number_format($correctCalculation, 2) }}</small>
                    
                    @if(abs($correctCalculation - $matching->matching_bonus) > 0.01)
                        <br><small class="text-warning">
                            <i class="bx bx-info-circle me-1"></i>Note: Actual bonus (৳{{ number_format($matching->matching_bonus, 2) }}) differs from current calculation due to different rate used at time of processing.
                        </small>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Current Status Comparison (if different from processing time) -->
    <div class="col-12">
        <div class="card border-warning">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0">
                    <i class="bx bx-time me-2"></i>Current Binary Status
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="text-center p-3 bg-primary-transparent rounded">
                            <h6 class="text-primary">Current Left Leg Points</h6>
                            <h4 class="fw-bold">{{ number_format($currentLeftPoints, 0) }}</h4>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-center p-3 bg-success-transparent rounded">
                            <h6 class="text-success">Current Right Leg Points</h6>
                            <h4 class="fw-bold">{{ number_format($currentRightPoints, 0) }}</h4>
                        </div>
                    </div>
                </div>

                @php
                    $currentQualified = $currentLeftPoints >= 100 && $currentRightPoints >= 100;
                @endphp

                <div class="mt-3 text-center">
                    <div class="badge {{ $currentQualified ? 'bg-success' : 'bg-danger' }} fs-6 px-3 py-2">
                        Current Status: {{ $currentQualified ? 'QUALIFIED' : 'NOT QUALIFIED' }}
                    </div>
                    @if (!$currentQualified)
                        <div class="mt-2 text-muted">
                            <small>You need at least 100 points in both legs to qualify for matching bonus.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="alert alert-light border">
            <h6 class="alert-heading">
                <i class="bx bx-info-circle me-2"></i>Understanding Point-Based Matching
            </h6>
            <ul class="mb-0">
                <li><strong>Qualification:</strong> You need minimum 100 points in both left and right legs</li>
                <li><strong>Matching:</strong> Points are matched from the weaker leg</li>
                <li><strong>Point Value:</strong> Each point is valued at ৳6</li>
                <li><strong>Commission:</strong> You earn 10% commission on matched point value</li>
                <li><strong>Carry Forward:</strong> Unmatched points carry forward for future matching</li>
            </ul>
        </div>
    </div>
</div>
