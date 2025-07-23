<div>
    <x-card>
        <div class="flex justify-end mb-4">
            {{-- <x-input label="Search" placeholder="Search" wire:model="search" class="w-1/2" /> --}}
            <x-button label="Add" icon="o-plus" wire:click="showAddModal()" class="btn-primary btn-xs p-2" />
        </div>

        <x-hr target="gotoPage" />
        <x-table class="text-xs" :headers="$t_headers" :rows="$trainers">
            {{-- Special `row_number` scope --}}
            @scope('cell_avatar', $trainers)
                <x-avatar image="{{ $trainers->avatar ? '/storage/' . $trainers->avatar : asset('/image/empty-user.webp') }}"
                    class="!w-10" />
            @endscope

            {{-- Special `actions` slot --}}
            @scope('cell_action', $trainers)
                <div class="flex gap-1">
                    <x-button icon="o-pencil" wire:click="showEditModal({{ $trainers->id }})" spinner class="btn-xs" />
                    <x-button icon="o-eye" wire:click="showDetailModal({{ $trainers->id }})" spinner class="btn-xs" />
                    <x-button icon="o-trash" wire:click="showDeleteModal({{ $trainers->id }})" spinner class="btn-xs" />
                </div>
            @endscope
        </x-table>
    </x-card>

    {{-- modal-create-muncul --}}
    <x-modal wire:model="createForm" title="New Class" class="backdrop-blur">
        <x-form wire:submit="save">
            <x-file label="Avatar" wire:model="avatar" accept="image/png, image/jpeg" crop-after-change>
                <img src="{{ $user->avatar ?? '/image/empty-user.webp' }}" class="h-36 rounded-lg" />
            </x-file>

            <x-input label="Name" wire:model="name" />
            <x-input label="Title" wire:model="title" />

            {{-- <x-input label="X" sublabel="optional" wire:model="x_app" /> --}}
            {{-- Notice `omit-error` --}}
            {{-- <x-input label="Number" wire:model="number" omit-error hint="This is required, but we suppress the error message" /> --}}

            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.createForm = false" />
                <x-button label="Save" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    {{-- modal-edit-muncul --}}
    <x-modal wire:model="editForm" title="Edit Trainer" class="backdrop-blur">
        <x-form wire:submit="update">
            @if ($avatar_edit != null)
                <x-file label="Foto" wire:model="avatar" accept="image/png, image/jpeg" crop-after-change>
                    <img src="{{ $trainer->avatar ? asset('storage/' . $trainer->avatar) : '/image/empty-user.webp' }}"
                        class="h-36 rounded-lg" />
                </x-file>
            @endif

            {{-- /image/empty-user.webp --}}
            <x-input label="Name" wire:model="name" />
            <x-input label="Title" wire:model="title" />
            {{-- Notice `omit-error` --}}
            {{-- <x-input label="Number" wire:model="number" omit-error hint="This is required, but we suppress the error message" /> --}}

            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.editForm = false" />
                <x-button label="Save" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    {{-- modal-detail-muncul --}}
    <x-modal wire:model="detailForm" title="Detail Class" class="backdrop-blur">

        <label for="name">Name</label>
        <p>{{ $name }}</p>
        <label for="description">Description</label>
        <p>{{ $description }}</p>
        {{-- Notice `omit-error` --}}
        {{-- <x-input label="Number" wire:model="number" omit-error hint="This is required, but we suppress the error message" /> --}}

        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.detailForm = false" />
        </x-slot:actions>
    </x-modal>

    {{-- modal-delete-muncul --}}
    <x-modal wire:model="deleteForm" title="Are you sure?" subtitle="delete this user?">
        <x-form no-separator>

            {{-- Notice we are using now the `actions` slot from `x-form`, not from modal --}}
            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.deleteModal = false" />
                <x-button label="Delete" class="btn-primary" wire:click="delete" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
