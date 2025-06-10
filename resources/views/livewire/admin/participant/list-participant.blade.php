<div>
    <x-card >
        <div class="flex justify-end mb-4">
            {{-- <x-input label="Search" placeholder="Search" wire:model="search" class="w-1/2" /> --}}
            <x-button label="Add" icon="o-plus" onclick="window.location.href='{{ route('admin.participant.create') }}'" class="btn-primary btn-xs p-2" />
        </div>
        
        <x-hr target="gotoPage" />
        <x-table
            class="text-xs"
            :headers="$t_headers"
            :rows="$participants"
            with-pagination
            {{-- per-page="[1]" --}}
            {{-- :per-page-values="[1]"  --}}
        >
             {{-- Special `actions` slot --}}
            @scope('cell_action', $participants)
                <div class="flex gap-1">
                    <x-button icon="o-eye" onclick="window.location.href='{{ route('admin.participant.show', $participants->id) }}'" spinner class="btn-xs" />
                    <x-button icon="o-trash" wire:click="showDeleteModal({{ $participants->id }})" spinner class="btn-xs" />
                </div>
            @endscope
        </x-table>
    </x-card>

    {{-- modal-create-muncul --}}
    <x-modal wire:model="createForm" title="New Participant" class="backdrop-blur">
        <x-form wire:submit="save">
            <x-input label="Title" icon="o-user" wire:model="title" />
            {{-- Notice `omit-error` --}}
            {{-- <x-input label="Number" wire:model="number" omit-error hint="This is required, but we suppress the error message" /> --}}
         
            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.createForm = false" />
                <x-button label="Save" class="btn-primary" type="submit" spinner="save"  />
            </x-slot:actions>
        </x-form>
    </x-modal>


    {{-- modal-delete-muncul --}}
    <x-modal wire:model="deleteModal" title="Are you sure?" subtitle="delete this user?">
        <x-form no-separator>
     
            {{-- Notice we are using now the `actions` slot from `x-form`, not from modal --}}
            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.deleteModal = false" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
