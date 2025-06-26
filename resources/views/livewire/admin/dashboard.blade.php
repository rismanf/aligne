<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

    <x-stat class="" title="Total Participants" value="{{ $participants }}" icon="o-user-group"
        tooltip-bottom="Total Participants" color="text-primary" wire:click="showDetailModal('All Participant')" />

    <x-stat class="" title="General Admission" value="{{ $total_participant }}" icon="o-user"
        tooltip-bottom="Total General Admission" color="text-primary"
        wire:click="showDetailModal('Participant General Admission')" />

    <x-stat class="" title="Sponsor" value="{{ $total_sponsor }}" icon="o-user" tooltip-bottom="Total Sponsor"
        color="text-primary" wire:click="showDetailModal('Participant Sponsor')" />

    <x-stat class="" title="Partner" value="{{ $total_partner }}" icon="o-users" tooltip-bottom="Total Partner"
        color="text-primary" wire:click="showDetailModal('Participant Partner')" />

    <x-stat class="" title="Need Attention" value="{{ $need_attention }}" icon="o-exclamation-triangle"
        tooltip-bottom="Need Attention" class="text-yellow-300" color="text-yellow-500"
        wire:click="showDetailModal('Participant Need Attention')" />

    <x-stat class="" title="Approved" value="{{ $approved }}" icon="o-check-circle" tooltip-bottom="Approved"
        class="text-green-300" color="text-green-500" wire:click="showDetailModal('Participant Approved')" />

    <x-stat class="" title="Rejected" value="{{ $rejected }}" icon="o-x-circle" tooltip-bottom="Rejected"
        class="text-orange-500" color="text-pink-500" wire:click="showDetailModal('Participant Rejected')" />

    <x-modal wire:model="showModal" title="{{ $type }} Details" class="backdrop-blur">
        <x-form wire:submit="save">
            @foreach ($data_list as $value)
                <x-list-item :item="$value">
                    <x-slot:avatar>
                        <x-badge value="{{ $value->status }}" class="badge-primary badge-soft" />
                    </x-slot:avatar>
                    <x-slot:value>
                        {{ $value->full_name }}
                    </x-slot:value>
                    <x-slot:sub-value>
                        {{ $value->company . ' (' . $value->job_title . ')'}}
                    </x-slot:sub-value>
                </x-list-item>
            @endforeach
            <x-slot:actions>
                <x-button label="Close" @click="$wire.showModal = false" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
