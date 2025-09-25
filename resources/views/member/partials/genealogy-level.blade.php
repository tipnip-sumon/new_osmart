@if(count($members) > 0)
    <div class="tree-level level-{{ $level }}" data-level="{{ $level }}">
        <div class="level-header">
            <span class="level-badge">Level {{ $level }} - {{ count($members) }} Member{{ count($members) > 1 ? 's' : '' }}</span>
        </div>
        <div class="tree-nodes">
            @foreach($members as $member)
                <div class="tree-node downline-node" 
                     data-user-id="{{ $member['id'] }}" 
                     data-level="{{ $member['level'] }}"
                     onclick="showMemberDetails({{ $member['id'] }})">
                    <div class="node-content">
                        <div class="avatar avatar-sm avatar-rounded">
                            <span class="fw-semibold">{{ substr($member['name'], 0, 2) }}</span>
                        </div>
                        <div class="node-info">
                            <h6 class="mb-1 fs-13">{{ $member['name'] }}</h6>
                            <p class="mb-0 fs-10">{{ $member['referral_code'] }}</p>
                            <span class="badge bg-{{ $member['status'] == 'active' ? 'success' : 'warning' }}-transparent fs-9">
                                {{ ucfirst($member['status']) }}
                            </span>
                        </div>
                        
                        @if($member['has_downline'])
                            <button class="btn btn-xs btn-primary-light expand-btn" 
                                    onclick="event.stopPropagation(); toggleNodeChildren({{ $member['id'] }}, this)">
                                <i class="fe fe-plus"></i>
                            </button>
                        @endif
                        
                        <!-- Member Stats -->
                        <div class="member-stats">
                            <div class="stat-box">
                                <div class="fw-semibold text-primary fs-10">{{ $member['downline_count'] }}</div>
                                <small class="text-muted fs-9">Team</small>
                            </div>
                            <div class="stat-box">
                                <div class="fw-semibold text-success fs-10">${{ number_format($member['business'], 0) }}</div>
                                <small class="text-muted fs-9">Sales</small>
                            </div>
                            <div class="stat-box">
                                <div class="fw-semibold text-info fs-10">{{ $member['join_date']->format('M y') }}</div>
                                <small class="text-muted fs-9">Joined</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Children Container (Hidden by default) -->
                    <div class="children-container d-none" id="children-{{ $member['id'] }}">
                        @if(count($member['children']) > 0)
                            @include('member.partials.genealogy-level', ['members' => $member['children'], 'level' => $level + 1])
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

<script>
function showMemberDetails(memberId) {
    // Show member details modal or sidebar (future enhancement)
    console.log('Show details for member:', memberId);
}

function toggleNodeChildren(memberId, button) {
    const container = document.getElementById(`children-${memberId}`);
    const icon = button.querySelector('i');
    
    if (container.classList.contains('d-none')) {
        // Show children
        container.classList.remove('d-none');
        icon.classList.remove('fe-plus');
        icon.classList.add('fe-minus');
        button.classList.add('expanded');
    } else {
        // Hide children
        container.classList.add('d-none');
        icon.classList.remove('fe-minus');
        icon.classList.add('fe-plus');
        button.classList.remove('expanded');
    }
}
</script>
