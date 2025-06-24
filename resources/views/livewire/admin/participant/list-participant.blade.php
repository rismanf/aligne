<div>
    <x-card>
        <x-hr target="gotoPage" />
        <x-table class="text-xs" :headers="$t_headers" :rows="$participants" :sort-by="$sortBy" with-pagination per-page="perPage"
            :per-page-values="[5, 10, 50]">
            {{-- Special `actions` slot --}}


            @scope('cell_action', $participants)
                <div class="flex gap-1">
                    <x-button icon="o-check-circle" class="btn-xs btn-success" wire:click="showDetailModal({{ $participants->id }})" tooltip="Approve" />
                </div>

                <div class="flex gap-1">
                    <x-button icon="o-x-circle" class="btn-xs btn-error" wire:click="showDetailModal({{ $participants->id }})" tooltip="Reject"/>
                </div>

                <div class="flex gap-1">
                    <x-button icon="o-eye" class="btn-xs" wire:click="showDetailModal({{ $participants->id }})" tooltip="Detail"/>
                </div>
            @endscope
        </x-table>

    </x-card>

    {{-- modal-show-muncul --}}
    <x-modal wire:model="showModal" title="Participant Details" class="backdrop-blur">
        <x-form wire:submit="save">
            @if (!empty($detail_participants))
                <h1 class="text-xs mb-2">Full Name: {{ $detail_participants->full_name }}</h1>
                <h1 class="text-xs mb-2">Email: {{ $detail_participants->email }}</h1>
                <h1 class="text-xs mb-2">Phone: {{ $detail_participants->phone }}</h1>
                <h1 class="text-xs mb-2">Company: {{ $detail_participants->company }}</h1>
                <h1 class="text-xs mb-2">Job Title: {{ $detail_participants->job_title }}</h1>
                <h1 class="text-xs mb-2">Price: Rp.{{ number_format($detail_participants->price, 0, ',', '.') }}</h1>
            @endif
            <x-slot:actions>
                <x-button label="Close" @click="$wire.showModal = false" />
            </x-slot:actions>
        </x-form>
    </x-modal>


    {{-- modal-delete-muncul --}}
    <x-modal wire:model="deleteModal" title="Are you sure?" subtitle="delete this user?">
        <x-form no-separator>
            @if (!empty($detail_participants))
                <h1 class="text-xs mb-2">Full Name: {{ $detail_participants->full_name }}</h1>
                <h1 class="text-xs mb-2">Email: {{ $detail_participants->email }}</h1>
            @endif
            {{-- Notice we are using now the `actions` slot from `x-form`, not from modal --}}
            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.deleteModal = false" />
                <x-button label="Confirm" class="btn-primary" @click="$wire.deleteParticipant({{ $selectedUserId }})"
                    spinner />
            </x-slot:actions>
        </x-form>
    </x-modal>

    {{-- modal-confrim-muncul --}}
    <x-modal wire:model="confirmModal" title="Summary of Your Participant">
        <x-form no-separator>
            <div class="text-xs  mb-4">
                <h1>Participant Total: {{ $total_participant }}</h1>
                <h1>Total Price: Rp.{{ number_format($total_price, 0, ',', '.') }}</h1>
            </div>
            {{-- Notice we are using now the `actions` slot from `x-form`, not from modal --}}
            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.confirmModal = false" />
                <x-button label="Confirm" class="btn-primary" @click="$wire.confirm({{ $selectedUserId }})" spinner />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
