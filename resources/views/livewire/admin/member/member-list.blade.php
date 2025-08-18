<div>
    <!-- Header with Filters -->
    <x-card>
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">👥 Member Management</h2>
                <p class="text-sm text-gray-600">Manage and view all registered members</p>
            </div>
            
            <!-- Filters -->
            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                <x-input 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Search members..." 
                    icon="o-magnifying-glass"
                    class="w-full sm:w-64" />
                
                <x-select 
                    wire:model.live="status_filter" 
                    :options="[
                        ['id' => 'all', 'name' => 'All Status'],
                        ['id' => 'active', 'name' => 'Active Members'],
                        ['id' => 'inactive', 'name' => 'Inactive Members']
                    ]"
                    class="w-full sm:w-40" />
                
                <x-select 
                    wire:model.live="membership_filter" 
                    :options="[
                        ['id' => 'all', 'name' => 'All Memberships'],
                        ['id' => 'paid', 'name' => 'Paid'],
                        ['id' => 'unpaid', 'name' => 'Unpaid'],
                        ['id' => 'expired', 'name' => 'Expired']
                    ]"
                    class="w-full sm:w-40" />
            </div>
        </div>
    </x-card>

    <!-- Members Table -->
    <x-card class="mt-4">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left">
                            <button wire:click="sortBy('name')" class="flex items-center gap-1 hover:text-primary">
                                Name
                                @if($sortBy === 'name')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-xs"></i>
                                @endif
                            </button>
                        </th>
                        <th class="text-left">Contact</th>
                        <th class="text-center">Status</th>
                        <th class="text-left">Current Membership</th>
                        <th class="text-center">Total Memberships</th>
                        <th class="text-center">
                            <button wire:click="sortBy('created_at')" class="flex items-center gap-1 hover:text-primary">
                                Joined Date
                                @if($sortBy === 'created_at')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-xs"></i>
                                @endif
                            </button>
                        </th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                        <tr class="hover:bg-gray-50">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar placeholder">
                                        <div class="bg-neutral text-neutral-content rounded-full w-10">
                                            <span class="text-sm">{{ substr($member->name, 0, 2) }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-semibold">{{ $member->name }}</div>
                                        <div class="text-sm text-gray-500">ID: {{ $member->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-sm">
                                    <div class="flex items-center gap-1">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                        {{ $member->email }}
                                    </div>
                                    @if($member->phone)
                                        <div class="flex items-center gap-1 mt-1">
                                            <i class="fas fa-phone text-gray-400"></i>
                                            {{ $member->phone }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                @if($member->is_active)
                                    <span class="badge badge-success gap-1">
                                        <i class="fas fa-check-circle"></i>
                                        Active
                                    </span>
                                @else
                                    <span class="badge badge-error gap-1">
                                        <i class="fas fa-times-circle"></i>
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($member->active_membership)
                                    <div class="text-sm">
                                        <div class="font-medium text-green-600">
                                            {{ $member->active_membership->membership->name }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            @if($member->active_membership->expires_at)
                                                Expires: {{ $member->active_membership->expires_at->format('d M Y') }}
                                            @else
                                                No expiration
                                            @endif
                                        </div>
                                    </div>
                                @elseif($member->latest_membership)
                                    <div class="text-sm">
                                        <div class="font-medium text-gray-500">
                                            {{ $member->latest_membership->membership->name }}
                                        </div>
                                        <div class="text-xs text-red-500">
                                            @if($member->latest_membership->payment_status === 'unpaid')
                                                Unpaid
                                            @elseif($member->latest_membership->expires_at && $member->latest_membership->expires_at < now())
                                                Expired
                                            @else
                                                Inactive
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">No membership</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge badge-outline">{{ $member->membership_count }}</span>
                            </td>
                            <td class="text-center">
                                <div class="text-sm">
                                    {{ $member->created_at->format('d M Y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $member->created_at->diffForHumans() }}
                                </div>
                            </td>
                            <td class="text-center">
                                <x-button 
                                    icon="o-eye" 
                                    wire:click="showMemberDetail({{ $member->id }})" 
                                    class="btn-xs btn-outline btn-primary"
                                    tooltip="View Details" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8">
                                <div class="text-gray-500">
                                    <i class="fas fa-users text-4xl mb-3"></i>
                                    <p>No members found</p>
                                    @if($search || $status_filter !== 'all' || $membership_filter !== 'all')
                                        <p class="text-sm">Try adjusting your filters</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($members->hasPages())
            <div class="mt-4">
                {{ $members->links() }}
            </div>
        @endif
    </x-card>

    <!-- Member Detail Modal -->
    <x-modal wire:model="showDetailModal" title="Member Details" class="backdrop-blur" box-class="!max-w-4xl">
        @if($selectedMember)
            <div class="space-y-6">
                <!-- Member Info -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center gap-4">
                        <div class="avatar placeholder">
                            <div class="bg-primary text-primary-content rounded-full w-16">
                                <span class="text-lg">{{ substr($selectedMember->name, 0, 2) }}</span>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold">{{ $selectedMember->name }}</h3>
                            <p class="text-gray-600">{{ $selectedMember->email }}</p>
                            @if($selectedMember->phone)
                                <p class="text-gray-600">{{ $selectedMember->phone }}</p>
                            @endif
                            <p class="text-sm text-gray-500">Member since {{ $selectedMember->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Membership History -->
                <div>
                    <h4 class="text-lg font-semibold mb-3">Membership History</h4>
                    @if(count($memberMemberships) > 0)
                        <div class="overflow-x-auto">
                            <table class="table table-sm w-full">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th>Package</th>
                                        <th>Price</th>
                                        <th>Payment Status</th>
                                        <th>Status</th>
                                        <th>Period</th>
                                        <th>Purchased</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($memberMemberships as $membership)
                                        <tr class="{{ $membership['is_active'] ? 'bg-green-50' : '' }}">
                                            <td>
                                                <div class="font-medium">{{ $membership['membership_name'] }}</div>
                                                @if($membership['is_active'])
                                                    <span class="badge badge-success badge-xs">Current</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="font-semibold">
                                                    Rp {{ number_format($membership['price'], 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $paymentClass = match($membership['payment_status']) {
                                                        'paid' => 'badge-success',
                                                        'unpaid' => 'badge-error',
                                                        'waiting_confirmation' => 'badge-warning',
                                                        default => 'badge-secondary'
                                                    };
                                                @endphp
                                                <span class="badge {{ $paymentClass }}">
                                                    {{ ucfirst($membership['payment_status']) }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $statusClass = match($membership['status']) {
                                                        'active' => 'badge-success',
                                                        'inactive' => 'badge-error',
                                                        'expired' => 'badge-warning',
                                                        default => 'badge-secondary'
                                                    };
                                                @endphp
                                                <span class="badge {{ $statusClass }}">
                                                    {{ ucfirst($membership['status']) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="text-sm">
                                                    @if($membership['starts_at'])
                                                        <div>From: {{ \Carbon\Carbon::parse($membership['starts_at'])->format('d M Y') }}</div>
                                                    @endif
                                                    @if($membership['expires_at'])
                                                        <div>To: {{ \Carbon\Carbon::parse($membership['expires_at'])->format('d M Y') }}</div>
                                                        @if($membership['remaining_days'] !== null)
                                                            <div class="text-xs {{ $membership['remaining_days'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                                {{ $membership['remaining_days'] > 0 ? round($membership['remaining_days']) . ' days left' : 'Expired' }}
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div class="text-green-600">No expiration</div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-sm">
                                                    {{ \Carbon\Carbon::parse($membership['created_at'])->format('d M Y') }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ \Carbon\Carbon::parse($membership['created_at'])->diffForHumans() }}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-credit-card text-4xl mb-3"></i>
                            <p>No membership history found</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <x-slot:actions>
            <x-button label="Close" @click="$wire.showDetailModal = false" />
        </x-slot:actions>
    </x-modal>

    <!-- Custom Styles -->
    <style>
        .table th {
            background-color: #f8fafc;
            font-weight: 600;
            color: #374151;
            padding: 12px;
        }
        
        .table td {
            padding: 12px;
            vertical-align: middle;
        }
        
        .badge {
            font-size: 11px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 6px;
        }
        
        .badge-success {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .badge-error {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .badge-outline {
            background-color: transparent;
            border: 1px solid #d1d5db;
            color: #6b7280;
        }
    </style>
</div>
