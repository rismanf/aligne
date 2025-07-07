<div>
    <x-card>
        <div class="flex justify-end mb-4">
            {{-- <x-input label="Search" placeholder="Search" wire:model="search" class="w-1/2" /> --}}
            <x-button label="Add" icon="o-plus" @click="$wire.createForm = true" class="btn-primary btn-xs p-2" />
        </div>

        <x-hr target="gotoPage" />
        <x-table class="text-xs" :headers="$t_headers" :rows="$schedules">
            {{-- Special `row_number` scope --}}


            {{-- Special `actions` slot --}}
            @scope('cell_action', $news)
                <div class="flex gap-1">
                    <x-button icon="o-pencil" wire:click="showEditModal({{ $news->id }})" spinner class="btn-xs" />
                    <x-button icon="o-eye" wire:click="showDetailModal({{ $news->id }})" spinner class="btn-xs" />
                    <x-button icon="o-trash" wire:click="showDeleteModal({{ $news->id }})" spinner class="btn-xs" />
                </div>
            @endscope
        </x-table>
    </x-card>

    {{-- modal-create-muncul --}}
    <x-modal wire:model="createForm" title="New Schedule" class="backdrop-blur">
        <x-form wire:submit="save">
            <x-input label="Name" icon="o-user" wire:model="name" />
            <x-datetime label="Date" wire:model="schedule_at" type="datetime-local" />
            <x-select label="Trainer" wire:model="trainer" :options="$trainer_data" placeholder="Select a Trainer" />
            <x-select label="Class" wire:model="calases" :options="$calases_data" placeholder="Select a Class" />
            <x-input label="Kuota" wire:model="kuota" />
            <x-input label="Duration" wire:model="duration" />
            {{-- Notice `omit-error` --}}
            {{-- <x-input label="Number" wire:model="number" omit-error hint="This is required, but we suppress the error message" /> --}}

            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.createForm = false" />
                <x-button label="Save" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>


    {{-- modal-edit-muncul --}}
    <x-modal wire:model="editForm" title="Edit Schedule" class="backdrop-blur">
        <x-form wire:submit="update">
            <x-datetime label="Date" wire:model="schedule_at" type="datetime-local" />
            <x-select label="Trainer" wire:model="trainer" :options="$trainer_data" placeholder="Select a Trainer" />
            <x-select label="Class" wire:model="calases" :options="$calases_data" placeholder="Select a Class" />
            <x-input label="Kuota" wire:model="kuota" />
            {{-- Notice `omit-error` --}}
            {{-- <x-input label="Number" wire:model="number" omit-error hint="This is required, but we suppress the error message" /> --}}

            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.editForm = false" />
                <x-button label="Save" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
