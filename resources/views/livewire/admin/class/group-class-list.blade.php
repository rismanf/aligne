<div>
    @php
        $config = [
            'spellChecker' => true,
            'toolbar' => ['heading', 'bold', 'italic', '|', 'preview'],
            'maxHeight' => '200px',
        ];
    @endphp

    <x-card>
        <div class="flex justify-end mb-4">
            @can('class-create')
                <x-button label="Add" icon="o-plus" wire:click="showAddModal()" class="btn-primary btn-xs p-2" />
            @endcan
        </div>

        <x-hr target="gotoPage" />
        <x-table class="text-xs" :headers="$t_headers" :rows="$class">
            {{-- Special `row_number` scope --}}
            @scope('cell_image_original', $class)
                <img src="{{ asset('storage/' . $class->image_original) }}" alt="Image" class="w-50 h-20 rounded shadow" />
            @endscope
            {{-- Special `actions` slot --}}
            @scope('cell_action', $class)
                <div class="flex gap-1">
                    <x-button icon="o-eye" wire:click="showDetailModal({{ $class->id }})" spinner class="btn-xs" />
                    @can('class-edit')
                        <x-button icon="o-pencil" wire:click="showEditModal({{ $class->id }})" spinner class="btn-xs" />
                    @endcan
                    @can('class-delete')
                        <x-button icon="o-trash" wire:click="showDeleteModal({{ $class->id }})" spinner class="btn-xs" />
                    @endcan
                </div>
            @endscope
        </x-table>
    </x-card>

    {{-- modal-create-muncul --}}
    <x-modal wire:model="createForm" title="New Class" class="backdrop-blur">
        <x-form wire:submit="save">
            <x-file label="Cover Image" wire:model="image" accept="image/*" required />
            <x-input label="Name" wire:model="name" required />
            <x-markdown label="Description" wire:model="description" :config="$config" />
            {{-- Notice `omit-error` --}}
            {{-- <x-input label="Number" wire:model="number" omit-error hint="This is required, but we suppress the error message" /> --}}

            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.createForm = false" />
                <x-button label="Save" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    {{-- modal-edit-muncul --}}
    <x-modal wire:model="editForm" title="New Class" class="backdrop-blur">
        <x-form wire:submit="update">
            <x-file label="Cover Image" wire:model="image_edit" accept="image/*"
                hint="if you don't want to change the image, just leave it blank" />
            @if ($image)
                <div class="mb-4">
                    <img src="{{ asset('storage/' . $image) }}"class="w-32 h-auto rounded shadow" />
                </div>
            @endif
            <x-input label="Name" wire:model="name" required />
            <x-markdown label="Description" wire:model="description" :config="$config" />
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
        <img src="{{ asset('storage/' . $image) }}" alt="" width="100px">
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
                <x-button label="Cancel" @click="$wire.deleteForm = false" />
                <x-button label="Delete" class="btn-primary" wire:click="delete" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
