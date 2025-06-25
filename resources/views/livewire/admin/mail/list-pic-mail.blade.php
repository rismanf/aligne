<div>
    <x-card>
        @can('email-create')
            <div class="flex justify-end mb-4">
                {{-- <x-input label="Search" placeholder="Search" wire:model="search" class="w-1/2" /> --}}
                <x-button label="Add" icon="o-plus" @click="$wire.createForm = true" class="btn-primary btn-xs p-2" />
            </div>
        @endcan
        <x-hr target="gotoPage" />
        <x-table class="text-xs" :headers="$t_headers" :rows="$manages_mails">
            {{-- Special `actions` slot --}}
            @scope('cell_action', $manages_mails)
                @can('participant-edit')
                    <div class="flex gap-1">
                        <x-button icon="o-eye" class="btn-xs" wire:click="showDetailModal({{ $manages_mails->id }})" />
                    </div>
                @endcan
            @endscope
        </x-table>

    </x-card>

    {{-- modal-create-muncul --}}
    <x-modal wire:model="createForm" title="New Recipient" class="backdrop-blur">
        <x-form wire:submit="save">
            <x-select label="Type" wire:model="type" :options="$mail_type" />
            <x-input label="Name" icon="o-user" wire:model="name" />
            <x-input label="E-Mail" icon="o-envelope" wire:model="email" />
            {{-- Notice `omit-error` --}}
            {{-- <x-input label="Number" wire:model="number" omit-error hint="This is required, but we suppress the error message" /> --}}

            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.createForm = false" />
                <x-button label="Save" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    {{-- modal-show-muncul --}}
    <x-modal wire:model="showModal" title="Menu Details" subtitle="{{ $name }}" class="backdrop-blur"
        box-class="!max-w-4xl">
        <x-form wire:submit="save">
            <x-input label="Menu" wire:model="name" />
            <x-input label="Title" wire:model="title" />
            <x-input label="Description" wire:model="description" />
            <x-input label="Keywords" wire:model="keywords" />

            <x-slot:actions>
                <x-button label="Close" @click="$wire.showModal = false" />
                <x-button label="Update" class="btn-warning" wire:click="updatemenu({{ $select_id }})" spinner />
            </x-slot:actions>
        </x-form>
    </x-modal>

</div>
