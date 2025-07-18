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
                <div class="flex gap-1">
                    <x-button icon="o-eye" wire:click="showDetailModal({{ $manages_mails->id }})" spinner class="btn-xs"
                        tooltip="Detail" />
                    @can('email-edit')
                        <x-button icon="o-pencil" wire:click="showEditModal({{ $manages_mails->id }})" spinner class="btn-xs"
                            tooltip="Edit" />
                    @endcan
                    @can('email-delete')
                        <x-button icon="o-trash" wire:click="showDeleteModal({{ $manages_mails->id }})" spinner class="btn-xs"
                            tooltip="Delete" />
                    @endcan
                </div>
            @endscope
        </x-table>

    </x-card>

    {{-- modal-create-muncul --}}
    <x-modal wire:model="createForm" title="New Recipient" class="backdrop-blur">
        <x-form wire:submit="save">
            <x-select label="Type" wire:model="type" :options="$mail_type" placeholder="Select Type" />
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
    <x-modal wire:model="detailModal" title="User Mail Details" subtitle="{{ $name }}" class="backdrop-blur">
        <p>{{ $name }}</p>
        <p>{{ $email }}</p>
        <p>{{ $type }}</p>

        <x-slot:actions>
            <x-button label="Close" @click="$wire.detailModal = false" />
        </x-slot:actions>
    </x-modal>


    {{-- modal-edit-muncul --}}
    <x-modal wire:model="editModal" title="Edit Mail" class="backdrop-blur">
        <x-form wire:submit="update">
            <x-select label="Type" wire:model="type_mail_id" :options="$mail_type" placeholder="Select Type" />
            <x-input label="Name" icon="o-user" wire:model="name" />
            <x-input label="E-Mail" icon="o-envelope" wire:model="email" />
            {{-- Notice `omit-error` --}}
            {{-- <x-input label="Number" wire:model="number" omit-error hint="This is required, but we suppress the error message" /> --}}

            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.editModal = false" />
                <x-button label="Save" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>


    {{-- modal-delete-muncul --}}
    <x-modal wire:model="deleteModal" title="Are you sure?" subtitle="delete this user mail?">
        <x-form no-separator>
            {{-- Notice we are using now the `actions` slot from `x-form`, not from modal --}}
            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.deleteModal = false" />
                <x-button label="Confirm" class="btn-primary" @click="$wire.delete({{ $select_id }})" spinner />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
