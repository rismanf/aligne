<div>
    @php
        $config = [
            'spellChecker' => true,
            'toolbar' => ['heading', 'bold', 'italic', '|', 'preview'],
            'maxHeight' => '70px',
        ];

        $config_money = [
            'prefix' => '',
            'thousands' => ',',
            'decimal' => '.',
            'precision' => 0,
        ];
    @endphp

    <x-card>
        <div class="flex justify-end mb-4">
            {{-- <x-input label="Search" placeholder="Search" wire:model="search" class="w-1/2" /> --}}
            <x-button label="Add" icon="o-plus" wire:click="showAddModal()" class="btn-primary btn-xs p-2" />
        </div>

        <x-hr target="gotoPage" />
        <x-table class="text-xs" :headers="$t_headers" :rows="$products">
            {{-- Special `row_number` scope --}}


            {{-- Special `actions` slot --}}
            @scope('cell_action', $products)
                <div class="flex gap-1">
                    <x-button icon="o-pencil" wire:click="showEditModal({{ $products->id }})" spinner class="btn-xs" />
                    <x-button icon="o-eye" wire:click="showDetailModal({{ $products->id }})" spinner class="btn-xs" />
                    <x-button icon="o-trash" wire:click="showDeleteModal({{ $products->id }})" spinner class="btn-xs" />
                </div>
            @endscope
        </x-table>
    </x-card>

    {{-- modal-create-muncul --}}
    <x-modal wire:model="createForm" title="New Product" class="backdrop-blur">
        <x-form wire:submit="save">
            <x-input label="Name" wire:model="name" />
            <x-input label="Price" wire:model="price" money />
            <x-input label="Kouta" wire:model="kuota" />
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
            <x-input label="Name" wire:model="name" />
            <x-input label="Price" wire:model="price" money />
            <x-input label="Kouta" wire:model="kuota" />
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
        <p>{{ $name }}</p>
        <label for="description">Price</label>
        <p>{{ $price }}</p>
        <label for="description">Kuota</label>
        <p>{{ $kuota }}</p>
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
