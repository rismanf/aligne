<div>
    <x-card>
        <div class="flex justify-between items-center mb-4">
            <div>
                <h2 class="text-xl font-semibold">Package Category Management</h2>
                <p class="text-sm text-gray-600">Manage membership package categories</p>
            </div>
            <div class="flex gap-2">
                <x-button label="Back to Packages" icon="o-arrow-left"
                    onclick="window.location.href='{{ route('admin.product.index') }}'" class="btn-outline" />
                <x-button label="Add New Category" icon="o-plus" wire:click="showAddModal()" class="btn-primary" />
            </div>
        </div>

        <x-hr target="gotoPage" />

        <x-table class="text-xs" :headers="$t_headers" :rows="$categories_data" With-pagination>
            {{-- Status scope --}}
            @scope('cell_is_active', $categories_data)
                @if ($categories_data->is_active)
                    <span class="badge badge-success">Active</span>
                @else
                    <span class="badge badge-error">Inactive</span>
                @endif
            @endscope

            {{-- Actions scope --}}
            @scope('cell_action', $categories_data)
                <div class="flex gap-1">
                    <x-button icon="o-pencil" wire:click="showEditModal({{ $categories_data->id }})" spinner class="btn-xs btn-warning" tooltip="Edit Package" />
                    <x-button icon="o-eye" wire:click="showDetailModal({{ $categories_data->id }})" spinner class="btn-xs btn-info" tooltip="View Details" />
                    <x-button icon="o-trash" wire:click="showDeleteModal({{ $categories_data->id }})" spinner class="btn-xs btn-error" tooltip="Delete Package" />
                </div>
            @endscope
        </x-table>
    </x-card>

    {{-- Create Modal --}}
    <x-modal wire:model="createForm" title="Create New Category" class="backdrop-blur">
        <x-form wire:submit="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="System Name" wire:model="name" placeholder="e.g., signature"
                    hint="A unique identifier for the package category" />
                <x-input label="Sort Order" wire:model="sort_order" type="number" placeholder="e.g., 1" />
            </div>

            <x-input label="Display Name" wire:model="display_name" placeholder="e.g., SIGNATURE CLASS PACK" />

            <x-textarea label="Description" wire:model="description" placeholder="Brief description of this category..."
                rows="3" />

            <x-checkbox label="Active" wire:model="is_active" />

            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.createForm = false" />
                <x-button label="Create Category" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    {{-- Edit Modal --}}
    <x-modal wire:model="editForm" title="Edit Category" class="backdrop-blur">
        <x-form wire:submit="update">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="System Name" wire:model="name" disabled />
                <x-input label="Sort Order" wire:model="sort_order" type="number" />
            </div>

            <x-input label="Display Name" wire:model="display_name" />

            <x-textarea label="Description" wire:model="description" rows="3" />

            <x-checkbox label="Active" wire:model="is_active" />

            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.editForm = false" />
                <x-button label="Update Category" class="btn-primary" type="submit" spinner="update" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    {{-- Delete Modal --}}
    <x-modal wire:model="deleteForm" title="Delete Category" subtitle="This action cannot be undone.">
        <div class="alert alert-warning">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
            <span>Warning: This will permanently delete the category. Make sure no products are using this
                category!</span>
        </div>

        <x-form no-separator>
            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.deleteForm = false" />
                <x-button label="Delete Category" class="btn-error" wire:click="delete" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
