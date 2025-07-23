<div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-stat class="" title="Total Member" value="{{ $participants }}" icon="o-user-group"
            tooltip-bottom="Total Member" color="text-primary" wire:click="showDetailModal('All Participant')" />

        <x-stat class="" title="Active member" value="{{ $total_participant }}" icon="o-user"
            tooltip-bottom="Total Active member" color="text-primary"
            wire:click="showDetailModal('Participant General Admission')" />

        <x-stat class="" title="Active member" value="{{ $total_sponsor }}" icon="o-user"
            tooltip-bottom="Active member" color="text-primary" wire:click="showDetailModal('Participant Sponsor')" />

        <x-stat class="" title="Partner" value="{{ $total_partner }}" icon="o-users"
            tooltip-bottom="Total Partner" color="text-primary" wire:click="showDetailModal('Participant Partner')" />

        <x-stat class="" title="Waiting" value="{{ $need_attention }}" icon="o-exclamation-triangle"
            tooltip-bottom="Waiting Approval" class="text-yellow-300" color="text-yellow-500"
            wire:click="showDetailModal('Participant Need Attention')" />

        <x-stat class="" title="Approved" value="{{ $approved }}" icon="o-check-circle"
            tooltip-bottom="Approved" class="text-green-300" color="text-green-500"
            wire:click="showDetailModal('Participant Approved')" />

        <x-stat class="" title="Rejected" value="{{ $rejected }}" icon="o-x-circle" tooltip-bottom="Rejected"
            class="text-orange-500" color="text-pink-500" wire:click="showDetailModal('Participant Rejected')" />

    </div>
    <!--Modal Detail -->
    <x-modal wire:model="showModal" title="{{ $type }} Details" class="backdrop-blur" box-class="!max-w-4xl">
        <x-table class="text-xs" :headers="$t_headers" :rows="$this->paginatedDataList">
            @scope('cell_status', $paginatedDataList)
                @if ($paginatedDataList->status == 'Waiting')
                    <x-badge value="{{ $paginatedDataList->status }}" class="badge-warning badge-soft" />
                @elseif($paginatedDataList->status == 'Approved')
                    <x-badge value="{{ $paginatedDataList->status }}" class="badge-success badge-soft" />
                @elseif($paginatedDataList->status == 'Rejected')
                    <x-badge value="{{ $paginatedDataList->status }}" class="badge-error badge-soft" />
                @endif
            @endscope
        </x-table>

        <div class="mt-4 flex justify-between items-center text-sm">
            <button wire:click="previousPage" @disabled($currentPage === 1)">
                Prev
            </button>

            <span>
                Page {{ $currentPage }} of {{ $this->totalPages }}
            </span>

            <button wire:click="nextPage" @disabled($currentPage === $this->totalPages)">
                Next
            </button>
        </div>

        <x-slot:actions>
            <x-button label="Close" @click="$wire.showModal = false" />
        </x-slot:actions>
    </x-modal>


    {{-- <x-modal wire:model="showModal2" title="{{ $type }} Details" class="backdrop-blur">
        <x-form wire:submit="save">
            @foreach ($data_list as $value)
                @php
                    $statusClass = match ($value->status) {
                        'Waiting' => 'badge-warning',
                        'Approved' => 'badge-success',
                        'Rejected' => 'badge-error',
                        default => 'badge-secondary',
                    };
                @endphp
                <x-list-item :item="$value">
                    <x-slot:avatar>
                        <x-badge value="{{ $value->status }}" class="{{ $statusClass }} badge-soft" />
                    </x-slot:avatar>
                    <x-slot:value>
                        {{ $value->full_name }}
                    </x-slot:value>
                    <x-slot:sub-value>
                        {{ $value->company . ' (' . $value->job_title . ')' }}
                    </x-slot:sub-value>
                </x-list-item>
            @endforeach
            <x-slot:actions>
                <x-button label="Close" @click="$wire.showModal = false" />
            </x-slot:actions>
        </x-form>
    </x-modal> --}}
</div>
