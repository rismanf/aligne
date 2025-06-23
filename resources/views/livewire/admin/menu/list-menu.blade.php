<div>
    <x-card>
        <x-hr target="gotoPage" />
        <x-table class="text-xs" :headers="$t_headers" :rows="$menus">
            {{-- Special `actions` slot --}}
            @scope('cell_action', $menus)
                @can('participant-edit')
                    <div class="flex gap-1">
                        <x-button icon="o-eye" class="btn-xs" wire:click="showDetailModal({{ $menus->id }})" />
                    </div>
                @endcan
            @endscope
        </x-table>

    </x-card>


    {{-- modal-show-muncul --}}
    <x-modal wire:model="showModal" title="Menu Details" subtitle="{{$name}}" class="backdrop-blur" box-class="!max-w-4xl">
        <x-form wire:submit="save">
            <x-input label="Menu" wire:model="name" />
            <x-input label="Title" wire:model="title" />
            <x-input label="Description" wire:model="description" />
            <x-input label="Keywords" wire:model="keywords" />

            <x-slot:actions>
                <x-button label="Close" @click="$wire.showModal = false" />
                <x-button label="Update" class="btn-warning"  wire:click="updatemenu({{ $selectmenu_id }})" spinner />
            </x-slot:actions>
        </x-form>
    </x-modal>

</div>
