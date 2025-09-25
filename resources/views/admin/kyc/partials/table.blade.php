<div class="table-responsive kyc-table">
    @if($kycVerifications->count() > 0)
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th width="50">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="selectAll">
                            <label class="form-check-label" for="selectAll"></label>
                        </div>
                    </th>
                    <th>User Details</th>
                    <th>KYC Information</th>
                    <th>Documents</th>
                    <th>Status</th>
                    <th>Risk Level</th>
                    <th>Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kycVerifications as $kyc)
                <tr>
                    <td>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input kyc-checkbox" value="{{ $kyc->id }}">
                        </div>
                    </td>
                    
                    <!-- User Details -->
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm rounded-circle bg-light d-flex align-items-center justify-content-center me-2">
                                <i class="fas fa-user text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">
                                    <a href="{{ route('admin.kyc.show', $kyc->id) }}" class="text-dark">
                                        {{ $kyc->user->name ?? 'N/A' }}
                                    </a>
                                </h6>
                                <small class="text-muted">{{ $kyc->user->email ?? 'N/A' }}</small>
                                @if($kyc->user->phone)
                                    <br><small class="text-muted">{{ $kyc->user->phone }}</small>
                                @endif
                            </div>
                        </div>
                    </td>
                    
                    <!-- KYC Information -->
                    <td>
                        <div>
                            <strong>{{ $kyc->full_name }}</strong>
                            <br><small class="text-muted">{{ ucfirst($kyc->document_type) }}: {{ $kyc->document_number }}</small>
                            @if($kyc->phone_number)
                                <br><small class="text-muted">Phone: {{ $kyc->phone_number }}</small>
                            @endif
                            @if($kyc->date_of_birth)
                                <br><small class="text-muted">DOB: {{ $kyc->date_of_birth->format('d M, Y') }}</small>
                            @endif
                        </div>
                    </td>
                    
                    <!-- Documents -->
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            @if($kyc->document_front_image)
                                <a href="{{ route('admin.kyc.document.view', [$kyc->id, 'front']) }}" 
                                   target="_blank" class="btn btn-sm btn-outline-primary" 
                                   title="View Front Document">
                                    <i class="fas fa-id-card"></i>
                                </a>
                            @endif
                            @if($kyc->document_back_image)
                                <a href="{{ route('admin.kyc.document.view', [$kyc->id, 'back']) }}" 
                                   target="_blank" class="btn btn-sm btn-outline-primary" 
                                   title="View Back Document">
                                    <i class="fas fa-id-card fa-flip-horizontal"></i>
                                </a>
                            @endif
                            @if($kyc->user_photo)
                                <a href="{{ route('admin.kyc.document.view', [$kyc->id, 'selfie']) }}" 
                                   target="_blank" class="btn btn-sm btn-outline-info" 
                                   title="View User Photo">
                                    <i class="fas fa-camera"></i>
                                </a>
                            @endif
                            @if($kyc->utility_bill)
                                <a href="{{ route('admin.kyc.document.view', [$kyc->id, 'utility']) }}" 
                                   target="_blank" class="btn btn-sm btn-outline-success" 
                                   title="View Utility Bill">
                                    <i class="fas fa-file-invoice"></i>
                                </a>
                            @endif
                            @if($kyc->user_signature)
                                <a href="{{ route('admin.kyc.document.view', [$kyc->id, 'signature']) }}" 
                                   target="_blank" class="btn btn-sm btn-outline-secondary" 
                                   title="View Signature">
                                    <i class="fas fa-signature"></i>
                                </a>
                            @endif
                        </div>
                    </td>
                    
                    <!-- Status -->
                    <td>
                        @php
                            $statusConfig = [
                                'draft' => ['class' => 'secondary', 'icon' => 'edit'],
                                'pending' => ['class' => 'warning', 'icon' => 'clock'],
                                'under_review' => ['class' => 'info', 'icon' => 'eye'],
                                'verified' => ['class' => 'success', 'icon' => 'check-circle'],
                                'rejected' => ['class' => 'danger', 'icon' => 'times-circle']
                            ];
                            $config = $statusConfig[$kyc->status] ?? ['class' => 'secondary', 'icon' => 'question'];
                        @endphp
                        <span class="badge bg-{{ $config['class'] }} status-badge">
                            <i class="fas fa-{{ $config['icon'] }} me-1"></i>
                            {{ ucfirst(str_replace('_', ' ', $kyc->status)) }}
                        </span>
                        @if($kyc->verified_at)
                            <br><small class="text-success">
                                <i class="fas fa-check me-1"></i>
                                {{ $kyc->verified_at->format('d M, Y') }}
                            </small>
                        @elseif($kyc->rejected_at)
                            <br><small class="text-danger">
                                <i class="fas fa-times me-1"></i>
                                {{ $kyc->rejected_at->format('d M, Y') }}
                            </small>
                        @elseif($kyc->submitted_at)
                            <br><small class="text-muted">
                                Submitted: {{ $kyc->submitted_at->format('d M, Y') }}
                            </small>
                        @endif
                    </td>
                    
                    <!-- Risk Level -->
                    <td>
                        @php
                            $riskConfig = [
                                'low' => ['class' => 'success', 'icon' => 'shield-alt'],
                                'medium' => ['class' => 'warning', 'icon' => 'exclamation-triangle'],
                                'high' => ['class' => 'danger', 'icon' => 'exclamation-circle']
                            ];
                            $riskClass = $riskConfig[$kyc->risk_level] ?? ['class' => 'secondary', 'icon' => 'shield'];
                        @endphp
                        <span class="badge bg-{{ $riskClass['class'] }} risk-badge">
                            <i class="fas fa-{{ $riskClass['icon'] }} me-1"></i>
                            {{ ucfirst($kyc->risk_level) }}
                        </span>
                        @if($kyc->risk_notes)
                            <br><small class="text-muted" title="{{ $kyc->risk_notes }}">
                                <i class="fas fa-sticky-note me-1"></i>
                                Has notes
                            </small>
                        @endif
                    </td>
                    
                    <!-- Submitted -->
                    <td>
                        <div class="text-nowrap">
                            @if($kyc->submitted_at)
                                <strong>{{ $kyc->submitted_at->format('d M, Y') }}</strong>
                                <br><small class="text-muted">{{ $kyc->submitted_at->format('h:i A') }}</small>
                                <br><small class="text-muted">{{ $kyc->submitted_at->diffForHumans() }}</small>
                            @else
                                <span class="text-muted">Not submitted</span>
                            @endif
                        </div>
                    </td>
                    
                    <!-- Actions -->
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.kyc.show', $kyc->id) }}" 
                               class="btn btn-sm btn-outline-primary" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            @if($kyc->status === 'pending')
                                <button type="button" class="btn btn-sm btn-outline-info" 
                                        onclick="updateKycStatus({{ $kyc->id }}, 'under_review')" 
                                        title="Mark Under Review">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-success" 
                                        onclick="updateKycStatus({{ $kyc->id }}, 'verified')" 
                                        title="Approve">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                        onclick="updateKycStatus({{ $kyc->id }}, 'rejected')" 
                                        title="Reject">
                                    <i class="fas fa-times"></i>
                                </button>
                            @elseif($kyc->status === 'under_review')
                                <button type="button" class="btn btn-sm btn-outline-success" 
                                        onclick="updateKycStatus({{ $kyc->id }}, 'approved')" 
                                        title="Approve">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                        onclick="updateKycStatus({{ $kyc->id }}, 'rejected')" 
                                        title="Reject">
                                    <i class="fas fa-times"></i>
                                </button>
                            @elseif($kyc->status === 'verified')
                                <button type="button" class="btn btn-sm btn-outline-warning" 
                                        onclick="updateKycStatus({{ $kyc->id }}, 'under_review')" 
                                        title="Mark Under Review">
                                    <i class="fas fa-eye"></i>
                                </button>
                            @elseif($kyc->status === 'rejected')
                                <button type="button" class="btn btn-sm btn-outline-info" 
                                        onclick="updateKycStatus({{ $kyc->id }}, 'under_review')" 
                                        title="Review Again">
                                    <i class="fas fa-redo"></i>
                                </button>
                            @endif
                            
                            <div class="dropdown d-inline">
                                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                        data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="showActivityLog({{ $kyc->id }})">
                                            <i class="fas fa-history me-2"></i> Activity Log
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="updateRiskLevel({{ $kyc->id }})">
                                            <i class="fas fa-shield-alt me-2"></i> Update Risk Level
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    @if($kyc->document_front_image || $kyc->document_back_image || $kyc->user_photo || $kyc->utility_bill)
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="downloadAllDocuments({{ $kyc->id }})">
                                                <i class="fas fa-download me-2"></i> Download All Documents
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                <p class="mb-0 text-muted">
                    Showing {{ $kycVerifications->firstItem() }} to {{ $kycVerifications->lastItem() }} 
                    of {{ $kycVerifications->total() }} results
                </p>
            </div>
            <div>
                {{ $kycVerifications->appends(request()->query())->links() }}
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No KYC verifications found</h5>
            <p class="text-muted">Try adjusting your filters or search criteria</p>
        </div>
    @endif
</div>